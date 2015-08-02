<?php

namespace Tbmt;

class MemberController extends BaseController {

  const MODULE_NAME = 'member';

  protected $actions = [
    'index' => true,
    'system' => true,
    'signup' => true,
    'signup_submit' => true,
    'signupSuccess' => true
  ];

  public function action_signup() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'signup'
    );
  }

  public function action_signup_submit() {
    $formErrors = [];
    list($valid, $data, $referralMember, $invitation) = \Member::validateSignupForm($_REQUEST);
    if ( $valid !== true ) {
      return ControllerDispatcher::renderModuleView(
        self::MODULE_NAME,
        'signup',
        ['formErrors' => $data]
      );
    }

    $con = $con = \Propel::getConnection();
    $member = \Member::createFromSignup($data, $referralMember, $invitation, $con);
    $member->reload(false, $con);

    Session::setLogin($member);
    return new ControllerActionRedirect(Router::toModule('account'));
  }

  public function action_index() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index'
    );
  }
}

?>
