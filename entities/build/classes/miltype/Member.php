<?php



/**
 * Skeleton subclass for representing a row from the 'tbmt_member' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.miltype
 */
class Member extends BaseMember
{

  static public $SIGNUP_FORM_FIELDS = [
    'title'                => \Tbmt\TYPE_STRING,
    'lastName'             => \Tbmt\TYPE_STRING,
    'firstName'            => \Tbmt\TYPE_STRING,
    'age'                  => \Tbmt\TYPE_STRING,
    'email'                => \Tbmt\TYPE_STRING,
    'city'                 => \Tbmt\TYPE_STRING,
    'country'              => \Tbmt\TYPE_STRING,
    'bank_recipient'       => \Tbmt\TYPE_STRING,
    'iban'                 => \Tbmt\TYPE_STRING,
    'bic'                  => \Tbmt\TYPE_STRING,
    'accept_agbs'          => \Tbmt\TYPE_STRING,
    'accept_valid_country' => \Tbmt\TYPE_STRING,
  ];

  static public $SIGNUP_FORM_FILTERS = [
    'email'                => \FILTER_VALIDATE_EMAIL,
    'lastName'             => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'firstName'            => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'age'                  => \FILTER_VALIDATE_INT,
    'firstName'            => \Tbmt\Validator::FILTER_NOT_EMPTY,

    'city'                 => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'country'              => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'bank_recipient'       => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'iban'                 => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'bic'                  => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'accept_agbs'          => \FILTER_VALIDATE_BOOLEAN,
    'accept_valid_country' => \FILTER_VALIDATE_BOOLEAN,
  ];

  static function initSignupForm(array $data = array()) {
    return \Tbmt\Arr::initMulti($data, self::$SIGNUP_FORM_FIELDS);
  }

  static function validateSignupForm(array $data = array()) {
    $data = self::initSignupForm($data);

    // Email is not required
    if ( $data['email'] === '' )
      unset($data['email']);

    return \Tbmt\Validator::getErrors($data, self::$SIGNUP_FORM_FILTERS);
  }
}
