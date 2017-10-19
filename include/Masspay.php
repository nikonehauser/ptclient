<?php

namespace Tbmt;

class Masspay {

  static private $RESULT_PROCESS_LOCKED = [["this process is locked cause it is already running - there has to be only one process executing this routine"]];

  static private function getInstance() {
    return new Masspay();
  }

  static public function cronPayouts() {
    $masspay = self::getInstance();
    $masspay->prepareTransfers(\Propel::getConnection());
    return $masspay->run(null, true);
  }

  static public function payouts($exec = false) {
    $masspay = self::getInstance();
    return $masspay->run($exec);
  }

  static public function listPayouts() {
    $masspay = self::getInstance();
    try {
      $transApi = $masspay->getApiClient();
      return $transApi->listTransfers();

    } catch (\Exception $e) {
      return $e->__toString();
    }
  }

  public function __construct() {
    $this->logger = new Logger();
  }

  public function run($exec = false) {
    $createLogFile = false;
    $sendLogAsEmail = false;
    $con = \Propel::getConnection();

    $data['results'] = [];
    $data['exception'] = false;
    try {

      if ( $exec ) {
        $this->prepareTransfers($con);

        $resultCounts = $this->executeTransfers($con);
        array_unshift($resultCounts, ['was execution', 'yes']);
        $data['results'] = $resultCounts;

      } else {
        // always prepare all available transfers
        //$this->prepareTransfers($con);

        $data['results'] = $this->viewTransferStats($con);
      }

    } catch (\Exception $e) {
      $createLogFile = true;
      $data['exception'] = $e->__toString();
    }

    $data['log'] = $this->logger->out();

    if ( $createLogFile ) {
      file_put_contents(
        Config::get("logs.path").'payouts_'.(new \DateTime())->format('Y-m-d_H-i-s').'_'.uniqid(),
        $data['log']
      );
    }

    if ( $sendLogAsEmail )
      MailHelper::sendException($e, $data['log']);

    return $data;
  }

  private function prepareTransfers(\PropelPDO $con) {
    $minAmountRequired = Config::get("payout.execute.payouts.min.amount", TYPE_INT);
    $whenCondition = strtotime(Config::get("payout.execute.payouts.after.strtotime"));

    // if we find none in exeuction -> prepare new one
    $sql = "UPDATE ONLY tbmt_transfer"
            ." SET state = ".\Transfer::STATE_IN_EXECUTION
            ." FROM ".\MemberPeer::TABLE_NAME
            ." WHERE"
            .' "tbmt_member"."id" = "tbmt_transfer"."member_id"'
            .' AND (select sum(amount) from tbmt_transaction where transfer_id = tbmt_transfer.id) >= '.$minAmountRequired
            .' AND "tbmt_transfer"."state" in ('.\Transfer::STATE_COLLECT.')'
            .' AND "tbmt_transfer"."creation_date" <= :date_lastmonth'
            .' AND "tbmt_member"."transfer_freezed" = 0'
            .' AND "tbmt_member"."deletion_date" IS NULL';
    $stmt = $con->prepare($sql);
    $stmt->execute([
      ':date_lastmonth' => date('Y-m-d H:i:s', $whenCondition)
    ]);

    $stmt->closeCursor();

    $formatter = new \PropelObjectFormatter();
    $formatter->setClass('Transfer');
    return $formatter->format($stmt);
  }

  private function viewTransferStats(\PropelPDO $con) {
    $sql = 'SELECT count(*)'
            .' FROM '.\TransferPeer::TABLE_NAME
            .' WHERE'
            .' "tbmt_transfer"."state" in ('.\Transfer::STATE_IN_EXECUTION.')';
    $stmt = $con->prepare($sql);
    $stmt->execute([]);

    $open = $this->getTransferInCollectStateCount($con);
    $waiting = $stmt->fetch()[0];

    if ( $open == 0 && $waiting == 0 ) {
      return [
        ['Open member transactions to be transfered', $open],
        ['Locked waiting transactions to be transfered', $waiting],
        ['Nothing to do']
      ];
    }

    return [
      ['Open member transactions to be transfered', $open],
      ['Locked waiting transactions to be transfered', $waiting],
      ['<a href="'.\Tbmt\Router::toModule('pay', 'index', ['doexec' => 1]).'" class="button" ><span>CREATE EXCEL</span></a>']
    ];
  }

  private function getTransferInCollectStateCount(\PropelPDO $con) {
    $sql = 'SELECT count(*)'
            .' FROM '.\TransferPeer::TABLE_NAME
            .' INNER JOIN '.\MemberPeer::TABLE_NAME.' ON "tbmt_member"."id" = "tbmt_transfer"."member_id"'
            .' WHERE'
            .' "tbmt_transfer"."state" in ('.\Transfer::STATE_COLLECT.')'
            .' AND "tbmt_member"."transfer_freezed" = 0'
            .' AND "tbmt_member"."deletion_date" IS NULL';
    $stmt = $con->prepare($sql);
    $stmt->execute([]);
    return $stmt->fetch()[0];
  }

  private function getTransferInExecution(\PropelPDO $con) {
    $limit = Config::get("payout.execute.payouts.limit", TYPE_INT);
    // SELECT * FROM ... FOR UPDATE
    // to ensure consistency through table row lock
    $sql = "SELECT * FROM ".\TransferPeer::TABLE_NAME
            ." WHERE"
            ." ".\TransferPeer::STATE." = ".\Transfer::STATE_IN_EXECUTION
            ." ORDER BY ".\TransferPeer::STATE." desc"
            ." LIMIT $limit";
    $stmt = $con->prepare($sql);
    $stmt->execute([]);

    $formatter = new \PropelObjectFormatter();
    $formatter->setClass('Transfer');
    return $formatter->format($stmt);
  }

  private function executeTransfers(\PropelPDO $con) {
    $successfulPayouts = 0;
    $failedPayouts = 0;
    $unknownPayouts = 0;
    $customerFailedPayouts = 0;

    $transfers = $this->getTransferInExecution($con);
    if ( count($transfers) <= 0 )
      return [["nothing do to"]];

    $lock = new Flock(Config::get('lock.payout.path'));

    if ( !$lock->acquire() )
      return $this->RESULT_PROCESS_LOCKED;

    if ( !$con->beginTransaction() )
      throw new Exception('Could not begin transaction');

    try {
      $payout = new \Payout();

      $payout
        ->setCreationDate(time())
        ->save($con);

      $this->log("EXECUTE TRANSFERS: ", $transfers);

      $sourceCurrency = \Transaction::$BASE_CURRENCY;
      $targetCurrency = Config::get("payout.target.currency");
      $targetCountry = Config::get("payout.target.country");
      $configReference = Config::get("payout.transfer.reference");

      $successfulPayouts = 0;

      $masspayExcels = new MasspayExcels();

      foreach ( $transfers as $dbTransfer ) {
        $successfulPayouts++;

        $masspayExcels->addTransfer($dbTransfer, $sourceCurrency, $configReference);

        $dbTransfer->setExecutionDate(time());
        $dbTransfer->setState(\Transfer::STATE_DONE);
        $dbTransfer->setPayout($payout);
        $dbTransfer->setAttempts($dbTransfer->getAttempts() + 1);
        $dbTransfer->save($con);
      }

      $filename = $masspayExcels->save();
      $payout->setMasspayFile($filename);
      $payout->save($con);

      if ( !$con->commit() )
        throw new Exception('Could not commit transaction');

      return [
        ['success full payouts in excels', $successfulPayouts]
      ];
    } catch (\Exception $e) {
      $con->rollBack();
      throw $e;
    } finally {
      $lock->release();

    }
  }

  private function log() {
    if ( !$this->logger )
      return;

    $this->logger->debug(null, ...func_get_args());
  }
}
