<?php

namespace Tbmt;

class PayController extends BaseController {

  const MODULE_NAME = 'pay';

  protected $actions = [
    'index' => true,
    'payouts' => true,
  ];

  public function dispatchAction($action, $params) {
    $login = Session::getLogin();
    if ( !$login || $login->getType() !== \Member::TYPE_ITSPECIALIST )
      throw new PermissionDeniedException();

    return parent::dispatchAction($action, $params);
  }

  public function action_index() {
    $data = Arr::initMulti($_REQUEST, [
      'code' => \Tbmt\TYPE_STRING,
      'unsettransferwise' => \Tbmt\TYPE_BOOL,
      'doexec' => \Tbmt\TYPE_BOOL,
    ]);

    $data['exception'] = false;
    $data['access_token'] = '';
    $data['exec_payments_url'] = \Tbmt\Router::toModule('pay', 'index', ['doexec' => 1]);
    $data['results'] = '';

    $logger = new Logger();

    if ( $data['unsettransferwise'] ) {
      Session::delete('transferwise_auth_code');
      Session::delete('transferwise_access_token');
      Session::delete('transferwise_refresh_token');
    }

    if ( $data['code'] )
      Session::set('transferwise_auth_code', $data['code']);
    else
      $data['code'] = Session::get('transferwise_auth_code');

    $createLogFile = true;
    $sendLogAsEmail = false;
    $con = \Propel::getConnection();

    try {
      $access_token = Session::get('transferwise_access_token');
      $refresh_token = Session::get('transferwise_refresh_token');
      $transApi = new \TransferWise\ApiClient(
        Config::get('transferwise.clienturl'),
        Config::get('transferwise.clientid'),
        Config::get('transferwise.clientsecret'),
        Config::get('transferwise.redirect_target'),
        $access_token,
        $refresh_token
      );
      $transApi->setLogger($logger);

      $data['oauth_url'] = $transApi->getOauthUrl();

      if ( !empty($data['code']) ) {
        list($access_token, $refresh_token) = $transApi->manageAuthorization(
          $data['code'],
          $access_token,
          $refresh_token
        );

        Session::set('transferwise_access_token', $access_token);
        Session::set('transferwise_refresh_token', $refresh_token);

        $data['access_token'] = $access_token;

        if ( $data['doexec'] ) {
          $resultCounts = $this->executeTransfers($transApi, $logger, $con);
          $data['results']['wasExecution'] = true;
          $data['results'] = $resultCounts;

          if ( !empty($resultCounts['unknownPayouts']) || !empty($resultCounts['failedPayouts']) ) {
            $sendLogAsEmail = true;
          } else {
            $createLogFile = false;
          }

        } else {
          $createLogFile = false;
          $data['results'] = $this->prepareTransfers($transApi, $logger, $con);
        }
      }
    } catch (\Exception $e) {
      $data['exception'] = $e->__toString();
    }

    $data['log'] = $logger->out();

    if ( $createLogFile ) {
      file_put_contents(
        Config::get("logs.path").'transferwise_'.(new \DateTime())->format('Y-m-d_H-i-s').'_'.uniqid(),
        $data['log']
      );
    }

    if ( $sendLogAsEmail )
      MailHelper::sendException($e, $data['log']);

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      $data
    );
  }

  private function prepareTransfers(\TransferWise\ApiClient $transApi, $logger, \PropelPDO $con) {
    $minAmountRequired = 10;
    $beforeOneMonth = strtotime('-1 month');
    $whenCondition = time(); // $beforeOneMonth;

    // find remaining transfer is execution
    $sql = "SELECT count(*) FROM ".\TransferPeer::TABLE_NAME." WHERE"
            ." ".\TransferPeer::AMOUNT." >= $minAmountRequired"
            ." AND ".\TransferPeer::STATE." = ".\Transfer::STATE_IN_EXECUTION;
    $stmt = $con->prepare($sql);
    $stmt->execute([]);
    $count = $stmt->fetch(\PDO::FETCH_NUM);

    if ( empty($count[0]) ) {
      // if we find none in exeuction -> prepare new one
      $sql = "UPDATE ".\TransferPeer::TABLE_NAME." SET"
              ." ".\TransferPeer::STATE." = ".\Transfer::STATE_IN_EXECUTION
              ." WHERE"
              ." ".\TransferPeer::AMOUNT." >= $minAmountRequired"
              ." AND ".\TransferPeer::STATE." in (".\Transfer::STATE_COLLECT.", ".\Transfer::STATE_COLLECT.")"
              ." AND ".\TransferPeer::CREATION_DATE." <= :date_lastmonth"
              ." LIMIT 10";
      $stmt = $con->prepare($sql);
      $stmt->execute([
        ':date_lastmonth' => date('Y-m-d H:i:s', $whenCondition)
      ]);
    }

    $transfers = $this->getTransferInExecution($con);

    if ( count($transfers) <= 0 )
      return ["nothing do to"];

    $logger->debug(null, $transfers);

    foreach ( $transfers as $transfer ) {
      $member = $transfer->getMember();

      $results[] = [$member, $transfer];
    }

    return ['transfers' => $results];
  }

  private function getTransferInExecution(\PropelPDO $con) {
    // SELECT * FROM ... FOR UPDATE
    // to ensure consistency through table row lock
    $sql = "SELECT * FROM ".\TransferPeer::TABLE_NAME." WHERE"
            ." ".\TransferPeer::STATE." = ".\Transfer::STATE_IN_EXECUTION
            ." ORDER BY ".\TransferPeer::STATE." desc"
            ." FOR UPDATE";
    $stmt = $con->prepare($sql);
    $stmt->execute([]);

    $formatter = new \PropelObjectFormatter();
    $formatter->setClass('Transfer');
    return $formatter->format($stmt);
  }

  private function executeTransfers(\TransferWise\ApiClient $transApi, $logger, \PropelPDO $con) {
    $successfulPayouts = 0;
    $failedPayouts = 0;
    $unknownPayouts = 0;

    $transfers = $this->getTransferInExecution($con);
    if ( count($transfers) <= 0 )
      return ["nothing do to"];

    $lock = new Flock(Config::get('lock.payout.path'));
    try {
      $lock->acquire();

      $logger->debug(null, $transfers);

      list($personal, $business) = $transApi->ensureRequiredProfiles();
      $bussinessProfileId = $business['id'];

      $sourceCurrency = \Transaction::$BASE_CURRENCY;
      $targetCurrency = Config::get("transferwise.target.currency");
      $targetCountry = Config::get("transferwise.target.country");

      foreach ( $transfers as $dbTransfer ) {
        $payoutInternMeta = [];
        $payoutExternMeta = [];
        $payoutResultState = \Payout::RESULT_UNKNOWN;
        $payoutExternId = null;
        $failedReason = null;
        $newDbTransferState = $dbTransfer->getState();

        $member = $dbTransfer->getMember();

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
            throw $exception;
          }

          //
          // CREATE TRANSFER
          //
          list($internTransfer, $transfer, $exception) = $transApi->createTransfer(
            $account['id'],
            $quote['id']
          );

          $payoutInternMeta['transfer'] = $internTransfer;
          $payoutExternMeta['transfer'] = $transfer;

          if ( $exception ) {
            $payoutResultState = \Payout::RESULT_FAILED;
            throw $exception;
          }

          $payoutResultState = \Payout::RESULT_SUCCESS;
          $payoutExternId = $transfer['id'];
          $dbTransfer->executeTransfer();
          $successfulPayouts++;

        } catch(\Exception $e) {
          $dbTransfer->setState(\Transfer::STATE_FAILED);
          $payoutInternMeta['exception'] = $e->__toString();

          if ( $e instanceof \Http\ResponseException ) {
            $failedReason = print_r($e->getResponse()->getContent(), true);
          }

        }

        $dbTransfer->setAttempts($dbTransfer->getAttempts() + 1);
        $dbTransfer->save($con);

        $payout = new \Payout();

        if ( $payoutResultState === \Payout::RESULT_FAILED ) {
          $payout->setFailedReason($failedReason);
          $failedPayouts++;
        } else if ( $payoutResultState === \Payout::RESULT_UNKNOWN ) {
          $unknownPayouts++;
        }

        $payout
          ->setTransfer($dbTransfer)
          ->setResult($payoutResultState)
          // -> setCreationDate(time()) -- sql defaults to current_timestamp
          ->setInternMeta(json_encode($payoutInternMeta))
          ->setExternMeta(json_encode($payoutExternMeta))
          ->setExternId($payoutExternId)
          ->save($con);

        if ( $payoutResultState === \Payout::RESULT_FAILED ) {
          \Tbmt\MailHelper::sendFailedPayoutTransfer($member, $payout);
        }

      }

      return ['executed' => [
        'successfulPayouts' => $successfulPayouts,
        'unknownPayouts' => $unknownPayouts,
        'failedPayouts' => $failedPayouts,
      ]];
    } catch (\Exception $e) {
      throw $e;
    } finally {
      $lock->release();

    }
  }
}

?>
