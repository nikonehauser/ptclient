<?php

namespace Tbmt;

class Masspay {

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
    $createLogFile = true;
    $sendLogAsEmail = false;
    $con = \Propel::getConnection();

    $data['results'] = [];
    $data['exception'] = false;
    try {

      if ( $exec ) {
        $resultCounts = $this->executeTransfers($con);
        $data['results']['wasExecution'] = true;
        $data['results'] = $resultCounts;

        $createLogFile = false;

      } else {
        // always prepare all available transfers
        $this->prepareTransfers($con);

        $createLogFile = false;
        $data['results'] = $this->viewPreparedTransfers($con);
      }

    } catch (\Exception $e) {
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

    /*
    $sql = "SELECT count(*) FROM ".\TransferPeer::TABLE_NAME
            ." INNER JOIN ".\MemberPeer::TABLE_NAME." on (".\TransferPeer::MEMBER_ID." = ".\MemberPeer::ID.")"
            ." WHERE"
            ." ".\TransferPeer::AMOUNT." >= $minAmountRequired"
            ." AND ".\TransferPeer::STATE." = ".\Transfer::STATE_IN_EXECUTION
            ." AND ".\MemberPeer::TRANSFER_FREEZED." = 0";
    $stmt = $con->prepare($sql);
    $stmt->execute([]);
    $count = $stmt->fetch(\PDO::FETCH_NUM);
    */

    // if we find none in exeuction -> prepare new one
    $sql = "UPDATE ONLY ".\TransferPeer::TABLE_NAME
            ." SET"
            ." state = ".\Transfer::STATE_IN_EXECUTION
            ." FROM ".\MemberPeer::TABLE_NAME
            ." WHERE"
            .' "tbmt_member"."id" = "tbmt_transfer"."member_id"'
            .' AND "tbmt_transfer"."amount" >= '.$minAmountRequired
            .' AND "tbmt_transfer"."state" in ('.\Transfer::STATE_COLLECT.', '.\Transfer::STATE_COLLECT.')'
            .' AND "tbmt_transfer"."creation_date" <= :date_lastmonth'
            .' AND "tbmt_member"."transfer_freezed" = 0';
    $stmt = $con->prepare($sql);
    $stmt->execute([
      ':date_lastmonth' => date('Y-m-d H:i:s', $whenCondition)
    ]);

  }

  private function viewPreparedTransfers(\PropelPDO $con) {
    $transfers = $this->getTransferInExecution($con);

    if ( count($transfers) <= 0 )
      return ["nothing do to"];

    $this->log("PREPARED TRANSFERS: ", $transfers);

    foreach ( $transfers as $transfer ) {
      $member = $transfer->getMember();

      $results[] = [$member, $transfer];
    }

    return ['transfers' => $results];
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
      return ["nothing do to"];

    $lock = new Flock(Config::get('lock.payout.path'));

    if ( !$lock->acquire() )
      return ["locked"];

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

      $masspayExcel = new MasspayExcel();

      foreach ( $transfers as $dbTransfer ) {
        $successfulPayouts++;

        $masspayExcel->addTransfer($dbTransfer, $sourceCurrency, $configReference);

        $dbTransfer->setExecutionDate(time());
        $dbTransfer->setState(\Transfer::STATE_DONE);
        $dbTransfer->setPayout($payout);
        $dbTransfer->setAttempts($dbTransfer->getAttempts() + 1);
        $dbTransfer->save($con);
      }

      $filename = $masspayExcel->save();
      $payout->setMasspayFile($filename);
      $payout->save($con);

      if ( !$con->commit() )
        throw new Exception('Could not commit transaction');

      return ['executed' => [
        'successfulPayouts' => $successfulPayouts
      ]];
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
