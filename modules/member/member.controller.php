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

  private $passportFile;
  private $panFile;

  private $IMAGE_MIMETYPES = [
    'jpg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
  ];

  public function __construct() {
    $path = Config::get('signup.pics.dir');

    $this->passportFile = new \Tbmt\HtmlFile('passportfile', [
      'path' => $path,
      'mimetypes' => $this->IMAGE_MIMETYPES,
      'filesize' => 5000000, // 5 mb
    ]);

    $this->panFile = new \Tbmt\HtmlFile('panfile', [
      'path' => $path,
      'mimetypes' => $this->  IMAGE_MIMETYPES,
      'filesize' => 5000000, // 5 mb
    ]);
  }

  public function action_signup() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'signup'
    );
  }

  public function action_signup_submit() {
    if ( \Tbmt\Config::get('set.signup.in.maintenance', \Tbmt\TYPE_BOOL, false) )
      throw new PermissionDeniedException();

    $formErrors = [];
    $data = array_merge($_REQUEST, [
      'referral_member_num' => Session::hasValidToken()
    ]);

    list($valid, $data, $referralMember, $invitation) = \Member::validateSignupForm($data);

    $errors = [];
    $fileResult = $this->passportFile->validate();
    if ( $fileResult !== true ) {
      $errors = array_merge($errors, [$this->passportFile->getFilekey() => $fileResult]);
    }

    $fileResult = $this->panFile->validate();
    if ( $fileResult !== true ) {
      $errors = array_merge($errors, [$this->panFile->getFilekey() => $fileResult]);
    }

    if ( $valid !== true || count($errors) > 0 ) {
      $data = array_merge(
        $valid !== true ? $data : [],
        $errors
      );
      return ControllerDispatcher::renderModuleView(
        self::MODULE_NAME,
        'signup',
        ['formErrors' => $data]
      );
    }

    $now = time();
    $mail = $data['email'];

    $prefix = $mail.uniqid();
    $passportfile = $this->passportFile->save($prefix.$this->passportFile->getFilekey());
    $panfile = $this->panFile->save($prefix.$this->panFile->getFilekey());

    $data['passportfile'] = $passportfile;
    $data['panfile'] = $panfile;

    $con = \Propel::getConnection();
    $emailValidation = \EmailValidation::create($now, $mail, $data, $con);

    MailHelper::sendEmailValidation(
      $mail,
      $data['firstName'],
      $emailValidation
    );

    return new ControllerActionRedirect(Router::toModule('member', 'signupSuccess'));
  }

  public function action_confirm_email_registration() {
    if ( \Tbmt\Config::get('set.signup.in.maintenance', \Tbmt\TYPE_BOOL, false) )
      throw new PermissionDeniedException();

    $valid = false;
    if ( empty($_REQUEST['hash']) )
      throw new PageNotFoundException();

    $emailValidation = \EmailValidation::validateHash($_REQUEST['hash']);
    if ( !$emailValidation )
      throw new InvalidDataException('Sorry the provided registration hash is invalid!');

    if ( $emailValidation->getAcceptedDate() ) {
      Session::setLogin($emailValidation->getMember());
      return new ControllerActionRedirect(Router::toModule('account'));
    }

    $signupData = json_decode($emailValidation->getMeta(), true);
    list($valid, $data, $referralMember, $invitation) = \Member::validateSignupForm($signupData);
    if ( $valid !== true ) {
      throw new InvalidDataException('Doh, something is wrong with the registration data!');
    }

    $con = \Propel::getConnection();

    if ( !$con->beginTransaction() )
      throw new \Exception('Could not begin transaction');

    try {
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
      $emailValidation->setMember($member);
      $emailValidation->save($con);

      if ( !$con->commit() )
        throw new \Exception('Could not commit transaction');

    } catch (\Exception $e) {
        $con->rollBack();
        throw $e;
    }

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
