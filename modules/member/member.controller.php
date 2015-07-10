<?php

namespace Tbmt;

class MemberController extends BaseController {

  const MODULE_NAME = 'member';

  protected $actions = [
    'index' => true,
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
    list($valid, $data, $referralMember) = \Member::validateSignupForm($_REQUEST);
    if ( $valid !== true ) {
      return ControllerDispatcher::renderModuleView(
        self::MODULE_NAME,
        'signup',
        ['formErrors' => $data]
      );
    }

    $con = $con = \Propel::getConnection();
    $member = \Member::createFromSignup($data, $referralMember, $con);
    $member->reload(false, $con);

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'signupSuccess',
      ['newMemberNum' => $member->getNum()]
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
