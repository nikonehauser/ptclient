<?php

namespace Tbmt;

class ManageController extends BaseController {

  const MODULE_NAME = 'manage';

  static private $CHANGE_PASSWORD_FORM_FIELDS = [
    'old_pwd' => \Tbmt\TYPE_STRING,
    'new_pwd' => \Tbmt\TYPE_STRING,
    'new_repeat' => \Tbmt\TYPE_STRING,
  ];

  static private $CHANGE_PASSWORD_FORM_FILTERS = [
    'old_pwd' => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'new_pwd' => \Tbmt\Validator::FILTER_PASSWORD,
    'new_repeat' => \Tbmt\Validator::FILTER_NOT_EMPTY,
  ];

  static public function initChangePasswordForm(array $data = array()) {
    return \Tbmt\Arr::initMulti($data, self::$CHANGE_PASSWORD_FORM_FIELDS);
  }

  static public function validateChangePasswordForm(\Member $login, array $data = array())  {
    $data = self::initChangePasswordForm($data);

    if ( $data['new_pwd'] !== $data['new_repeat'] )
      return [false, ['new_pwd' => \Tbmt\Localizer::get('error.password_unequal')]];

    if ( !Cryption::verifyPassword($data['old_pwd'], $login->getPassword()) )
      return [false, ['old_pwd' => \Tbmt\Localizer::get('error.password')]];

    $res = \Tbmt\Validator::getErrors($data, self::$CHANGE_PASSWORD_FORM_FILTERS);
    if ( $res !== false )
      return [false, $res];

    return [true, $data];
  }

  static private $PASSWORD_RESET_FORM_FIELDS = [
    'num' => [\Tbmt\TYPE_INT, ''],
  ];

  static private $PASSWORD_RESET_FORM_FILTERS = [
    'num' => \Tbmt\Validator::FILTER_NOT_EMPTY,
  ];

  static public function initPasswordResetForm(array $data = array()) {
    return \Tbmt\Arr::initMulti($data, self::$PASSWORD_RESET_FORM_FIELDS);
  }

  static public function validatePasswordResetForm(array $data = array())  {
    $data = self::initPasswordResetForm($data);

    $res = \Tbmt\Validator::getErrors($data, self::$PASSWORD_RESET_FORM_FILTERS);
    if ( $res !== false )
      return [false, $res, null];

    $recipient = \MemberQuery::create()
      ->filterByDeletionDate(null, \Criteria::ISNULL)
      ->findOneByNum($data['num']);
    if ( $recipient == null ) {
      return [false, ['num' => \Tbmt\Localizer::get('error.member_num')], null];
    }

    return [true, $data, $recipient];

  }

  protected $actions = [
    'password_reset' => true,
    'do_reset_password' => true,
    'change_pwd' => true,
    'change_pwd_signup' => true,

  ];

  public function action_do_reset_password() {
    $data = \Tbmt\Arr::initMulti($_REQUEST, [
      'num' => TYPE_STRING,
      'exp' => TYPE_STRING,
      'hash' => TYPE_STRING,
    ]);

    $newPassword = false;
    if ( !empty($data['num']) || !empty($data['exp']) || !empty($data['hash']) ) {
      $member = \Member::getByNum($data['num']);

      if ( $member && Cryption::validatePasswordResetToken(
          $data['num'],
          $data['exp'],
          $member->getEmail(),
          $data['hash']
        ) && intval($data['exp']) + 3600 * 24 >= time() ) {
        $newPassword = bin2hex(mcrypt_create_iv(8, MCRYPT_DEV_URANDOM));

        $member->setPassword($newPassword);
        $member->save();
      }
    }

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      CURRENT_MODULE_ACTION,
      ['newPassword' => $newPassword]
    );
  }

  public function action_password_reset() {
    list($valid, $data, $recipient) = self::validatePasswordResetForm($_REQUEST);
    if ( $valid !== true ) {
      return ControllerDispatcher::renderModuleView(
        self::MODULE_NAME,
        CURRENT_MODULE_ACTION,
        ['formErrors' => $data]
      );
    }

    MailHelper::sendPasswordResetLink($recipient);

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      CURRENT_MODULE_ACTION,
      ['resetmsg' => true]
    );
  }

  public function action_change_pwd() {
    $login = Session::getLogin();
    if ( !$login )
      throw new PageNotFoundException();

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      CURRENT_MODULE_ACTION,
      []
    );
  }

  public function action_change_pwd_signup() {
    $login = Session::getLogin();
    if ( !$login )
      throw new PageNotFoundException();

    list($valid, $data) = self::validateChangePasswordForm($login, $_REQUEST);
    if ( $valid !== true ) {
      return ControllerDispatcher::renderModuleView(
        self::MODULE_NAME,
        'change_pwd',
        ['formErrors' => $data]
      );
    }

    $login->setPassword($data['new_pwd']);
    $login->save();

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'change_pwd',
      ['successmsg' => true]
    );
  }
}

?>
