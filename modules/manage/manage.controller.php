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
    'Title'          => \Tbmt\TYPE_STRING,
    'LastName'       => \Tbmt\TYPE_STRING,
    'FirstName'      => \Tbmt\TYPE_STRING,
    'Email'          => \Tbmt\TYPE_STRING,
    'City'           => \Tbmt\TYPE_STRING,
    'ZipCode'       => \Tbmt\TYPE_STRING,
    'Bic'            => \Tbmt\TYPE_STRING,
    'Iban'           => \Tbmt\TYPE_STRING,
    'BankRecipient' => \Tbmt\TYPE_STRING,

    'Street'               => \Tbmt\TYPE_STRING,
    'StreetAdd'           => \Tbmt\TYPE_STRING,
    'BankName'            => \Tbmt\TYPE_STRING,
    'BankZipCode'        => \Tbmt\TYPE_STRING,
    'BankCity'            => \Tbmt\TYPE_STRING,
    'BankStreet'          => \Tbmt\TYPE_STRING,
    'BankCountry'         => \Tbmt\TYPE_STRING,
  ];

  static private $CHANGE_PROFILE_FORM_FILTERS = [
    'LastName'       => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'FirstName'      => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'Email'          => \FILTER_VALIDATE_EMAIL,
    'City'           => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'ZipCode'       => \Tbmt\Validator::FILTER_INDIA_PINCODE,
    'Bic'            => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'Iban'           => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'BankRecipient' => \Tbmt\Validator::FILTER_NOT_EMPTY,

    'Street'       => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'StreetAdd'       => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'BankName'       => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'BankZipCode'       => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'BankCity'       => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'BankStreet'       => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'BankCountry'       => \Tbmt\Validator::FILTER_NOT_EMPTY,
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
    if ( !$login->isExtended() ) {
      $existingFields = [
        'title'          => true,
        'lastName'       => true,
        'firstName'      => true,
        'email'          => true,
      ];

      self::$CHANGE_PROFILE_FORM_FIELDS = array_intersect_key(
        self::$CHANGE_PROFILE_FORM_FIELDS,
        $existingFields
      );
      self::$CHANGE_PROFILE_FORM_FILTERS = array_intersect_key(
        self::$CHANGE_PROFILE_FORM_FILTERS,
        $existingFields
      );
    }

    $data = self::initChangeBankingForm($data);
    $res = \Tbmt\Validator::getErrors($data, self::$CHANGE_PROFILE_FORM_FILTERS);
    if ( $res !== false )
      return [false, $res];

    return [true, $data];
  }

  static private $PASSWORD_RESET_FORM_FIELDS = [
    'num' => [\Tbmt\TYPE_STRING, ''],
  ];

  static private $PASSWORD_RESET_FORM_FILTERS = [
    'num' => \FILTER_VALIDATE_EMAIL,
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
      ->findOneByEmail($data['num']);
    if ( $recipient == null ) {
      return [false, ['num' => \Tbmt\Localizer::get('error.member_email')], null];
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

      if ( $member &&
          $member->getNum() == $data['num'] &&
          Cryption::validatePasswordResetToken(
            $data['num'],
            $data['exp'],
            $member->getEmail(),
            $data['hash']
          ) &&
          intval($data['exp']) + 3600 * 24 >= time() ) {
        $newPassword = bin2hex(mcrypt_create_iv(8, MCRYPT_DEV_URANDOM));

        $member->setPassword($newPassword);
        $member->save();
      } else {
        throw new InvalidDataException();
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
      ['formVal' => $login->toArray()]
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

    $con = \Propel::getConnection();
    if ( !$con->beginTransaction() )
      throw new \Exception('Could not begin transaction');

    try {

      $login->fromArray(array_intersect_key($data, self::$CHANGE_PROFILE_FORM_FIELDS));

      if ( $login->isExtended() ) {
        $login->setProfileVersion($login->getProfileVersion() + 1);

        // Update last rejected/failed transfer state to retrigger transfer upon
        // this profile update.
        if ( $login->getTransferFreezed() == 1 ) {
          $failedTransfers = \TransferQuery::create()
            ->filterByMember($login)
            ->filterByState(\Transfer::STATE_FAILED)
            ->find();

          foreach ( $failedTransfers as $transfer ) {
            $transfer->setState(\Transfer::STATE_IN_EXECUTION);
            $transfer->save($con);
          }

          $login->setTransferFreezed(0);
        }

      }

      $login->save($con);

      if ( !$con->commit() )
        throw new \Exception('Could not commit transaction');

      return ControllerDispatcher::renderModuleView(
        self::MODULE_NAME,
        'change_profile',
        ['successmsg' => true]
      );

    } catch (\Exception $e) {
        $con->rollBack();

        throw $e;
    }
  }
}

?>
