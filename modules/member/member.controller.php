<?php

namespace Tbmt;

class MemberController extends BaseController {

  const MODULE_NAME = 'member';

  protected $actions = [
    'index' => true,
    'system' => true,
    'signup' => true,
    'signup_submit' => true,
    'signupSuccess' => true,
    'confirm_email_registration' => true
  ];

  public function action_signup() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'signup'
    );
  }

  public function action_signup_submit() {
    $formErrors = [];
    $data = array_merge($_REQUEST, [
      'referral_member_num' => Session::hasValidToken()
    ]);

    list($valid, $data, $referralMember, $invitation) = \Member::validateSignupForm($data);
    if ( $valid !== true ) {
      return ControllerDispatcher::renderModuleView(
        self::MODULE_NAME,
        'signup',
        ['formErrors' => $data]
      );
    }

    $now = time();
    $mail = $data['email'];

    $con = \Propel::getConnection();
    $emailValidation = \EmailValidation::create($now, $mail, $data, $con);
    MailHelper::sendEmailValidation(
      $mail,
      (empty($data['title']) ? '' : $data['title'].' ').$data['firstName'].' '.$data['lastName'],
      $emailValidation
    );

    return new ControllerActionRedirect(Router::toModule('member', 'signupSuccess'));
  }

  public function action_confirm_email_registration() {
    $valid = false;
    if ( empty($_REQUEST['hash']) )
      throw new PageNotFoundException();

    $emailValidation = \EmailValidation::validateHash($_REQUEST['hash']);
    if ( !$emailValidation )
      throw new InvalidDataException('Sorry the provided registration hash is invalid!');

    if ( $emailValidation->getAcceptedDate() )
      throw new InvalidDataException('Your account was created already, just login!');

    $data = json_decode($emailValidation->getMeta(), true);
    list($valid, $data, $referralMember, $invitation) = \Member::validateSignupForm($data);
    if ( $valid !== true ) {
      throw new InvalidDataException('Doh, something is wrong with the registration data!');
    }

    $con = \Propel::getConnection();
    $member = \Activity::exec(
      /*callable*/['\\Member', 'activity_createFromSignup'],
      /*func args*/[
        $data,
        $referralMember,
        $invitation,
        $con
      ],
      /*activity.action*/\Activity::ACT_MEMBER_SIGNUP,
      /*activity.member*/null,
      /*activity.related*/$referralMember,
      $con
    );
    $member->reload(false, $con);

    $emailValidation->setAcceptedDate(time());
    $emailValidation->save($con);

    Session::setLogin($member);
    Session::set(Session::KEY_SIGNUP_MSG, true);
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
