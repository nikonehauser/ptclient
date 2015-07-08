<?php

namespace Tbmt;

class MemberController extends BaseController {

  const MODULE_NAME = 'member';

  protected $actions = [
    'index' => true,
    'signup' => true,
    'signup_submit' => true
  ];

  public function action_signup() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'signup'
    );
  }

  public function action_signup_submit() {
    $formErrors = [];
    $data = \Member::validateSignupForm($_REQUEST);
    if ( $data !== false )
      $formErrors = $data;

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'signup',
      ['formErrors' => $formErrors]
    );
  }

  public function action_index() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index'
    );
  }
}

?>
