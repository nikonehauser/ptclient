<?php

namespace Tbmt;

class GuideController extends BaseController {

  const MODULE_NAME = 'guide';

  protected $actions = [
    'index' => true,
    'shandle' => true,
    'fhandle' => true,
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

  public function action_shandle() {
    return $this->handlePayuReturn();
  }

  public function action_fhandle() {
    return $this->handlePayuReturn();
  }

  private function handlePayuReturn() {
    try {
      $resultStack = '';
      $resultMessage = 'Failure';
      $resultDesc = '-- no description available --';

      $login = Session::getLogin();
      if ( !$login )
        throw new PageNotFoundException();

      $con = \Propel::getConnection();
      \Activity::exec(
        /*callable*/['\\Tbmt\\GuideController', 'activity_validatePayment'],
        /*func args*/[
          $_REQUEST,
          $login,
          $con
        ],
        /*activity.action*/\Activity::ACT_MEMBER_PAYMENT_EXEC,
        /*activity.member*/$login,
        /*activity.related*/null,
        $con,
        false
      );

      Session::set(Session::KEY_PAYMENT_MSG, true);
      return new ControllerActionRedirect(Router::toAccountTab('index'));
    } catch (\Exception $e) {
      $resultMessage = 'Failure';
      $resultDesc = $e->getMessage();
      $resultStack = $e->__toString();
    }

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'handleresult', [
        'member' => $login,
        'resultmessage' => $resultMessage,
        'resultdesc' => $resultDesc,
        'resultstack' => \Tbmt\Config::get('devmode', \Tbmt\TYPE_BOOL, false) ? $resultStack : '',
      ]
    );
  }

  static public function activity_validatePayment($data, $login, $con) {
    $payment = \Tbmt\Payu::processResponse($data, $login, $con);
    return [
      'data' => $payment->toArray(),
      \Activity::ARR_RELATED_RETURN_KEY => $payment
    ];
  }
}

?>
