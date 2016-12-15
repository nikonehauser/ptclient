<?php

namespace Tbmt;

class GuideController extends BaseController {

  const MODULE_NAME = 'guide';

  protected $actions = [
    'index' => true,
    'ajax_create_ppp' => true,
    'ajax_exec_ppp' => true,
    'ajax_cancel_ppp' => true,
  ];

  public function action_index() {
    $login = Session::getLogin();

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index', [
        'member' => $login
      ]
    );
  }


  /**
   * Handle creation.
   *
   * @return [type]
   */
  public function action_ajax_create_ppp() {
    $login = Session::getLogin();
    if ( !$login ) {
      Session::terminate();

      $action = new ControllerActionAjax("PermissionDeniedException");
      $action->setHttpStatusCode(403);
      return $action;
    }

    $con = \Propel::getConnection();
    $payment = \Activity::exec(
      /*callable*/[$this, 'activity_createPPP'],
      /*func args*/[
        $login,
        $con
      ],
      /*activity.action*/\Activity::ACT_MEMBER_PAYMENT_CREATE,
      /*activity.member*/$login,
      /*activity.related*/null,
      $con
    );

    return new ControllerActionAjax(['paymentID' => $payment->getId()]);
  }


  /**
   * Handle execution.
   *
   * @return [type]
   */
  public function action_ajax_exec_ppp() {
    $login = Session::getLogin();
    if ( !$login ) {
      Session::terminate();

      $action = new ControllerActionAjax("PermissionDeniedException");
      $action->setHttpStatusCode(403);
      return $action;
    }

    $data = \Tbmt\Arr::initMulti($_REQUEST, [
      'paymentID'  => [\Tbmt\TYPE_STRING, ''],
      'payerID' => [\Tbmt\TYPE_STRING, ''],
    ]);

    if ( !$data['paymentID'] || !$data['payerID'] ) {
      $action = new ControllerActionAjax("Bad Request");
      $action->setHttpStatusCode(400);
      return $action;
    }

    $payment = \PaymentQuery::create()->findOneByGatewayPaymentId($data['paymentID']);

    // log unknown payment
    if ( !$payment ) {
      \Activity::insert(
        \Activity::ACT_MEMBER_PAYMENT_CANCEL_UNKNOWN,
        $login,
        null,
        $_REQUEST,
        null,
        $con
      );

      return new ControllerActionAjax('Received unknown payment id');
    }

    $con = \Propel::getConnection();
    \Activity::exec(
      /*callable*/[$this, 'activity_execPPP'],
      /*func args*/[
        $login,
        $payment,
        $data['paymentID'],
        $data['payerID'],
        $con
      ],
      /*activity.action*/\Activity::ACT_MEMBER_PAYMENT_EXEC,
      /*activity.member*/$login,
      /*activity.related*/null,
      $con
    );

    return new ControllerActionAjax(true);

  }

  /**
   * Handle cancel.
   *
   * @return [type]
   */
  public function action_ajax_cancel_ppp() {
    $login = Session::getLogin();
    if ( !$login ) {
      Session::terminate();

      $action = new ControllerActionAjax("PermissionDeniedException");
      $action->setHttpStatusCode(403);
      return $action;
    }

    $data = \Tbmt\Arr::initMulti($_REQUEST, [
      'paymentID'  => [\Tbmt\TYPE_STRING, ''],
      'cause' => [\Tbmt\TYPE_STRING, ''],
    ]);

    $con = \Propel::getConnection();
    if ( !$data['paymentID'] ) {
      // log unknown
      \Activity::insert(
        \Activity::ACT_MEMBER_PAYMENT_CANCEL_UNKNOWN,
        $login,
        null,
        $_REQUEST,
        null,
        $con
      );

      return new ControllerActionAjax('Canceled by unknown cause');
    }

    $payment = \PaymentQuery::create()->findOneByGatewayPaymentId($data['paymentID']);

    // log unknown payment
    if ( !$payment ) {
      \Activity::insert(
        \Activity::ACT_MEMBER_PAYMENT_CANCEL_UNKNOWN,
        $login,
        null,
        $_REQUEST,
        null,
        $con
      );

      return new ControllerActionAjax('Received unknown payment cancel');
    }

    if ( $data['cause'] === 'user' ) {
      $payment
        ->setStatus(\Payment::STATUS_USER_CANCELED)
        ->save($con);

      \Activity::insert(
        \Activity::ACT_MEMBER_PAYMENT_CANCEL_BY_USER,
        $login,
        $payment,
        $_REQUEST,
        null,
        $con
      );

      return new ControllerActionAjax('Canceled by user');
    }

    $payment
      ->setStatus(\Payment::STATUS_CANCELED)
      ->save($con);

    \Activity::insert(
      \Activity::ACT_MEMBER_PAYMENT_CANCEL,
      $login,
      $payment,
      $_REQUEST,
      null,
      $con
    );
  }


  /**
   * Handle cancel.
   *
   * @return [type]
   */
  public function activity_createPPP(\Member $member, \PropelPDO $con) {
    $invoiceNumber = \SystemStats::getIncreasedInvoiceNumber($con);
    $payPalPayment = \Tbmt\Payments::createPayPal($invoiceNumber, $con);

    $payment = new \Payment();
    $payment
      ->setStatus(\Payment::STATUS_CREATED)
      ->setType('paypal')
      ->setDate(time())
      ->setMember($member)
      ->setInvoiceNumber($invoiceNumber)
      ->setGatewayPaymentId($payPalPayment->getId())
      ->setMeta([])
      ->save($con);

    return [
      'paypalPayment' => $payPalPayment->toArray(),
      \Activity::ARR_RELATED_RETURN_KEY => $payment,
      \Activity::ARR_RESULT_RETURN_KEY => $payPalPayment
    ];
  }


  /**
   * Handle cancel.
   *
   * @return [type]
   */
  public function activity_execPPP(\Member $member, \Payment $payment, $paypalPaymentId, $paypalPayerId, \PropelPDO $con) {
    $payment
      ->setStatus(\Payment::STATUS_EXECUTED)
      ->save($con);

    $payPalPayment = \Tbmt\Payments::executePayPalPayment($paypalPaymentId, $paypalPayerId);

    $payment
      ->setMeta([$payPalPayment->toArray()])
      ->save($con);

    $member->onReceivedMemberFee(
      \Transaction::$BASE_CURRENCY, // currency
      time(), // when
      $member->getFreeInvitation() === 1, // was free invitation
      $con
    );

    return [
      'paypalPayment' => $payPalPayment->toArray(),
      \Activity::ARR_RELATED_RETURN_KEY => $payment,
      \Activity::ARR_RESULT_RETURN_KEY => $payPalPayment
    ];
  }

}

?>
