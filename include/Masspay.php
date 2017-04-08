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

  static public function payouts($code = null, $exec = false) {
    $masspay = self::getInstance();
    return $masspay->run($code, $exec);
  }

  static public function checkPayouts() {
    $limit = Config::get("transferwise.check.payouts.limit", TYPE_INT);

    $whenCondition = strtotime(Config::get("transferwise.check.payouts.limit"));
    return date("r", strtotime('now'));
    $lock = new Flock(Config::get('lock.payout.check.path'));
    try {
      $lock->acquire();
    } catch (\Exception $e) {
      return ["locked"];
    }

    try {
      // SELECT payouts

    } catch (\Exception $e) {

    }

    return '';
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

  private $persistenceFile;

  public function __construct($persistenceFile) {
    $this->persistenceFile = $persistenceFile;
    $this->logger = new Logger();
  }

  public function getPersistetTokens() {
    if ( file_exists($this->persistenceFile) ) {
      return json_decode(file_get_contents($this->persistenceFile), true);
    }

    return [
      'code' => '',
      'access_token' => '',
      'refresh_token' => '',
      'expiration_time' => '',
    ];
  }

  public function persistTokens($code, $access_token, $refresh_token, $expiration_time) {
    file_put_contents($this->persistenceFile, json_encode([
      'code' => $code,
      'access_token' => $access_token,
      'refresh_token' => $refresh_token,
      'expiration_time' => $expiration_time,
    ]));
  }

  public function run($code = null, $exec = false) {
    $data = $this->getPersistetTokens();
    $data['exception'] = false;
    $data['results'] = '';

    if ( $code )
      $data['code'] = $code;

    $createLogFile = true;
    $sendLogAsEmail = false;
    $con = \Propel::getConnection();

    try {
      $transApi = new \TransferWise\ApiClient(
        Config::get('transferwise.clienturl'),
        Config::get('transferwise.clientid'),
        Config::get('transferwise.clientsecret'),
        Config::get('transferwise.redirect_target')
      );

      $transApi->setLogger($this->logger);

      $data['oauth_url'] = $transApi->getOauthUrl();

      if ( !empty($data['code']) ) {
        list($access_token, $refresh_token, $expirationTime) = $transApi->manageAuthorization(
          $data['code'],
          $data['access_token'],
          $data['refresh_token'],
          $data['expiration_time']
        );

        $this->persistTokens(
          $data['code'],
          $access_token,
          $refresh_token,
          $expirationTime
        );

        $data['access_token'] = $access_token;

        if ( $exec ) {
          $resultCounts = $this->executeTransfers($transApi, $con);
          $data['results']['wasExecution'] = true;
          $data['results'] = $resultCounts;

          if ( !empty($resultCounts['unknownPayouts']) || !empty($resultCounts['failedPayouts']) ) {
            $sendLogAsEmail = true;
          } else {
            $createLogFile = false;
          }

        } else {
          $createLogFile = false;
          $data['results'] = $this->viewPrepareTransfers($transApi, $con);
        }
      }
    } catch (\Exception $e) {
      $data['exception'] = $e->__toString();
    }

    $data['log'] = $this->logger->out();

    if ( $createLogFile ) {
      file_put_contents(
        Config::get("logs.path").'transferwise_'.(new \DateTime())->format('Y-m-d_H-i-s').'_'.uniqid(),
        $data['log']
      );
    }

    if ( $sendLogAsEmail )
      MailHelper::sendException($e, $data['log']);

    return $data;
  }

  private function prepareTransfers(\PropelPDO $con) {
    $minAmountRequired = Config::get("transferwise.execute.payouts.min.amount", TYPE_INT);
    $whenCondition = strtotime(Config::get("transferwise.execute.payouts.after.strtotime"));

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
    $sql = "UPDATE ".\TransferPeer::TABLE_NAME
            ." INNER JOIN ".\MemberPeer::TABLE_NAME." on (".\TransferPeer::MEMBER_ID." = ".\MemberPeer::ID.")"
            ." SET"
            ." ".\TransferPeer::STATE." = ".\Transfer::STATE_IN_EXECUTION
            ." WHERE"
            ." ".\TransferPeer::AMOUNT." >= $minAmountRequired"
            ." AND ".\TransferPeer::STATE." in (".\Transfer::STATE_COLLECT.", ".\Transfer::STATE_COLLECT.")"
            ." AND ".\TransferPeer::CREATION_DATE." <= :date_lastmonth"
            ." AND ".\MemberPeer::TRANSFER_FREEZED." = 0";
    $stmt = $con->prepare($sql);
    $stmt->execute([
      ':date_lastmonth' => date('Y-m-d H:i:s', $whenCondition)
    ]);

  }

  private function viewPrepareTransfers(\TransferWise\ApiClient $transApi, \PropelPDO $con) {
    $this->prepareTransfers($con);
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
    $limit = Config::get("transferwise.execute.payouts.limit", TYPE_INT);
    // SELECT * FROM ... FOR UPDATE
    // to ensure consistency through table row lock
    $sql = "SELECT * FROM ".\TransferPeer::TABLE_NAME." WHERE"
            ." ".\TransferPeer::STATE." = ".\Transfer::STATE_IN_EXECUTION
            ." ORDER BY ".\TransferPeer::STATE." desc"
            ." LIMIT $limit";
    $stmt = $con->prepare($sql);
    $stmt->execute([]);

    $formatter = new \PropelObjectFormatter();
    $formatter->setClass('Transfer');
    return $formatter->format($stmt);
  }

  private function executeTransfers(\TransferWise\ApiClient $transApi, \PropelPDO $con) {
    $successfulPayouts = 0;
    $failedPayouts = 0;
    $unknownPayouts = 0;
    $customerFailedPayouts = 0;

    $transfers = $this->getTransferInExecution($con);
    if ( count($transfers) <= 0 )
      return ["nothing do to"];

    $lock = new Flock(Config::get('lock.payout.path'));
    try {
      $lock->acquire();
    } catch (\Exception $e) {
      return ["locked"];
    }

    try {
      $this->log("EXECUTE TRANSFERS: ", $transfers);

      list($personal, $business) = $transApi->ensureRequiredProfiles();
      $bussinessProfileId = $business['id'];

      $sourceCurrency = \Transaction::$BASE_CURRENCY;
      $targetCurrency = Config::get("transferwise.target.currency");
      $targetCountry = Config::get("transferwise.target.country");
      $configReference = Config::get("transferwise.transfer.reference");

      foreach ( $transfers as $dbTransfer ) {
        $payoutInternMeta = [];
        $payoutExternMeta = [];
        $payoutResultState = \Payout::RESULT_UNKNOWN;
        $payoutExternId = null;
        $payoutExternStatus = '';
        $failedReason = null;
        $isCustomerFailure = 0;

        $member = $dbTransfer->getMember();
        $memberReference = $member->getNum();

        try {
          //
          // CREATE QUOTE
          //
          list($internQuote, $quote, $exception) = $transApi->createQuote(
            $bussinessProfileId,
            $sourceCurrency,
            $targetCurrency,
            $dbTransfer->getAmount()
          );

          $payoutInternMeta['quote'] = $internQuote;
          $payoutExternMeta['quote'] = $quote;
          if ( $exception )
            throw $exception;

          //
          // HANDLE ACCOUNT
          //
          list($internAccount, $account, $exception) = $transApi->ensureQuoteAccount(
            $member,
            $bussinessProfileId,
            $quote,
            $targetCountry
          );

          $payoutInternMeta['account'] = $internAccount;
          $payoutExternMeta['account'] = $account;
          if ( $exception ) {
            $payoutResultState = \Payout::RESULT_FAILED;
            $isCustomerFailure = 1;
            throw $exception;
          }

          //
          // CREATE TRANSFER
          //
          list($internTransfer, $transfer, $exception) = $transApi->createTransfer(
            $account['id'],
            $quote['id'],
            "$configReference $memberReference"
          );

          $payoutInternMeta['transfer'] = $internTransfer;
          $payoutExternMeta['transfer'] = $transfer;

          if ( $exception ) {
            $payoutResultState = \Payout::RESULT_FAILED;
            throw $exception;
          }

          $payoutResultState = \Payout::RESULT_SUCCESS;
          $payoutExternId = $transfer['id'];

          $payoutExternStatus = $transfer['status'];
          $dbTransfer->setState(\Transfer::STATE_DONE);
          $successfulPayouts++;

        } catch(\Exception $e) {
          $dbTransfer->setState(\Transfer::STATE_FAILED);
          $payoutInternMeta['exception'] = $e->__toString();

          if ( $e instanceof \Http\ResponseException ) {
            $failedReason = print_r($e->getResponse()->getContent(), true);
          }

          $member->setTransferFreezed(1);
        }

        try {
          $payout = new \Payout();

          $payout
            ->setTransfer($dbTransfer)
            ->setResult($payoutResultState)
            ->setCreationDate(time())
            ->setStateCheckDate(time())
            ->setInternMeta(json_encode($payoutInternMeta))
            ->setExternMeta(json_encode($payoutExternMeta))
            ->setExternId($payoutExternId)
            ->setExternState($payoutExternStatus)
            ->setIsCusomterFailure($isCustomerFailure);

          if ( $payout->isCustomerFailure() ) {
            $customerFailedPayouts++;
          } else if ( $payoutResultState === \Payout::RESULT_FAILED ) {
            $failedPayouts++;
          } else if ( $payoutResultState === \Payout::RESULT_UNKNOWN ) {
            $unknownPayouts++;
          }

          if ( $failedReason )
            $payout->setFailedReason($failedReason);

          $payout->save($con);

          $dbTransfer->setExecutionDate(time());
          $dbTransfer->setAttempts($dbTransfer->getAttempts() + 1);
          $dbTransfer->save($con);

          $member->save($con);

          if ( $payout->isCustomerFailure() ) {
            \Tbmt\MailHelper::sendFailedPayoutTransfer($member, $payout);
          }

        } catch(\Exception $e) {
          $this->log("CATCHED PAYOUT CREATION: ", $e, [
            "transferId" => $dbTransfer->getId(),
            "result" => $payoutResultState,
            "internMeta" => $payoutInternMeta,
            "externMeta" => $payoutExternMeta,
            "externId" => $payoutExternId,
            "externStatus" => $payoutExternStatus
          ]);

          throw $e;
        }

      }

      return ['executed' => [
        'successfulPayouts' => $successfulPayouts,
        'unknownPayouts' => $unknownPayouts,
        'failedPayouts' => $failedPayouts,
        'customerFailedPayouts' => $customerFailedPayouts,
      ]];
    } catch (\Exception $e) {
      throw $e;
    } finally {
      $lock->release();

    }
  }

  private function getApiClient() {
    $data = $this->getPersistetTokens();

    $transApi = new \TransferWise\ApiClient(
      Config::get('transferwise.clienturl'),
      Config::get('transferwise.clientid'),
      Config::get('transferwise.clientsecret'),
      Config::get('transferwise.redirect_target')
    );

    $transApi->manageAuthorization(
      $data['code'],
      $data['access_token'],
      $data['refresh_token'],
      $data['expiration_time']
    );

    return $transApi;
  }

  private function log() {
    if ( !$this->logger )
      return;

    $this->logger->debug(null, ...func_get_args());
  }
}
