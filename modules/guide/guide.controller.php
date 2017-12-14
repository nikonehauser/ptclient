<?php

namespace Tbmt;

class GuideController extends BaseController {

  const MODULE_NAME = 'guide';

  protected $actions = [
    'index' => true,
    'howtopay' => true,
    'shandle' => true,
    'fhandle' => true,
  ];

  public function action_index() {
    $login = Session::getLogin();

    $formData = null;
    if ( $login && !$login->isMarkedAsPaid() ) {
      $formData = \Tbmt\Payu::prepareFormData($login, \Propel::getConnection());

      if ( $formData && $formData instanceof \Payment && $formData->getStatus() === \Payment::STATUS_EXECUTED ) {
        Session::set(Session::KEY_PAYMENT_MSG, true);
        return new ControllerActionRedirect(Router::toAccountTab('index'));
      }
    }

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index', [
        'member' => $login,
        'formData' => $formData,
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
      $reseultColor = 'notice';
      $resultMessage = 'Failure';
      $resultDesc = '-- no description available --';

      $login = Session::getLogin();
      if ( !$login )
        throw new PageNotFoundException();

      $con = \Propel::getConnection();
      $data = \Activity::exec(
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

      if ( !empty($data['error']) && $data['error'] != 'E000' ) {
        $reseultColor = 'notice';
        $resultMessage = 'Failure';
        $resultDesc = $data['error'];
      } else {
        Session::set(Session::KEY_PAYMENT_MSG, true);
        return new ControllerActionRedirect(Router::toAccountTab('index'));
      }
    } catch (\Exception $e) {
      $reseultColor = 'notice';
      $resultMessage = 'Failure';
      $resultDesc = $e->getMessage();
      $resultStack = $e->__toString();
    }

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'handleresult', [
        'member' => $login,
        'resultmessage' => $resultMessage,
        'resultcolor' => $reseultColor,
        'resultdesc' => $resultDesc,
        'resultstack' => \Tbmt\Config::get('devmode', \Tbmt\TYPE_BOOL, false) ? $resultStack : '',
      ]
    );
  }

  static public function activity_validatePayment($data, $login, $con) {
    $data = \Tbmt\Payu::processResponse($data, $login, $con);
    $payment = isset($data['payment']) ? $data['payment'] : null;
    if ( $payment )
      $data[\Activity::ARR_RELATED_RETURN_KEY] = $payment;

    return $data;
  }
}

?>
