<?php

namespace Tbmt;

class GuideController extends BaseController {

  const MODULE_NAME = 'guide';

  protected $actions = [
    'index' => true,
    'create_ppp' => true,
    'exec_ppp' => true,
    'redirect_ppp' => true
  ];

  public function action_index() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index'
    );
  }

  public function action_create_ppp() {
    return new ControllerActionAjax(['paymentID' => \Tbmt\Payments::createPayPal()->getId()]);
  }

  public function action_exec_ppp() {

  }

  public function action_redirect_ppp() {

  }
}

?>
