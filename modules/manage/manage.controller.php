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

  static private $CHANGE_PROFILE_FORM_FIELDS = [
    'title'          => \Tbmt\TYPE_STRING,
    'lastName'       => \Tbmt\TYPE_STRING,
    'firstName'      => \Tbmt\TYPE_STRING,
    'email'          => \Tbmt\TYPE_STRING,
    'city'           => \Tbmt\TYPE_STRING,
    'zip_code'       => \Tbmt\TYPE_STRING,
    'bic'            => \Tbmt\TYPE_STRING,
    'iban'           => \Tbmt\TYPE_STRING,
    'bank_recipient' => \Tbmt\TYPE_STRING,
  ];

  static private $CHANGE_PROFILE_FORM_FILTERS = [
    'lastName'       => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'firstName'      => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'email'          => \FILTER_VALIDATE_EMAIL,
    'city'           => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'zip_code'       => \Tbmt\Validator::FILTER_INDIA_PINCODE,
    'bic'            => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'iban'           => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'bank_recipient' => \Tbmt\Validator::FILTER_NOT_EMPTY,
  ];

  static public function initChangePasswordForm(array $data = array()) {
    return \Tbmt\Arr::initMulti($data, self::$CHANGE_PASSWORD_FORM_FIELDS);
  }

  static public function initChangeBankingForm(array $data = array()) {
    return \Tbmt\Arr::initMulti($data, self::$CHANGE_PROFILE_FORM_FIELDS);
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

  static public function validateChangeBankingForm(\Member $login, array $data = array())  {
    $data = self::initChangeBankingForm($data);
    $res = \Tbmt\Validator::getErrors($data, self::$CHANGE_PROFILE_FORM_FILTERS);
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

    'change_profile' => true,
    'change_profile_signup' => true,
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


  public function action_change_profile() {
    $login = Session::getLogin();
    if ( !$login )
      throw new PageNotFoundException();

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      CURRENT_MODULE_ACTION,
      ['formVal' => [
        'title'          => $login->getTitle(),
        'lastName'       => $login->getLastName(),
        'firstName'      => $login->getFirstName(),
        'email'          => $login->getEmail(),
        'city'           => $login->getCity(),
        'zip_code'       => $login->getZipCode(),
        'bic'            => $login->getBic(),
        'iban'           => $login->getIban(),
        'bank_recipient' => $login->getBankRecipient(),
      ]]
    );
  }

  public function action_change_profile_signup() {
    $login = Session::getLogin();
    if ( !$login )
      throw new PageNotFoundException();

    list($valid, $data) = self::validateChangeBankingForm($login, $_REQUEST);
    if ( $valid !== true ) {
      return ControllerDispatcher::renderModuleView(
        self::MODULE_NAME,
        'change_profile',
        ['formErrors' => $data]
      );
    }

    $login->setTitle($data['title']);
    $login->setLastName($data['lastName']);
    $login->setFirstName($data['firstName']);
    $login->setEmail($data['email']);
    $login->setCity($data['city']);
    $login->setZipCode($data['zip_code']);
    $login->setBic($data['bic']);
    $login->setIban($data['iban']);
    $login->setBankRecipient($data['bank_recipient']);
    $login->save();

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'change_profile',
      ['successmsg' => true]
    );
  }
}

?>
