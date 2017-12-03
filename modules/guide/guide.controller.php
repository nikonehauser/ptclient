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
    $resultStack = '';
    $resultMessage = 'Failure';
    $resultDesc = '-- no description available --';
    try {
      $login = Session::getLogin();
      if ( !$login )
        throw new PageNotFoundException();

      $data = \Tbmt\Payu::validateResponse($_REQUEST);
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
}

?>
