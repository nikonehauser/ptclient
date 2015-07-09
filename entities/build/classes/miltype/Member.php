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
    'referer_num'          => [\Tbmt\TYPE_INT, ''],
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
    'referer_num'          => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'email'                => \FILTER_VALIDATE_EMAIL,
    'lastName'             => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'firstName'            => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'age'                  => [
      'filter' => \FILTER_VALIDATE_INT,
      'options' => [
        'min_range' => 18,
        'max_range' => 110
      ],
      'errorLabel' => 'error.age_of_18'
    ],
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

    $res = \Tbmt\Validator::getErrors($data, self::$SIGNUP_FORM_FILTERS);
    if ( $res !== false )
      return [false, $res];

    // Validate member number exists
    $parentMember = \MemberQuery::create()->findOneByNum($data['referer_num']);
    if ( $parentMember == null ) {
      return [false, ['referer_num' => \Tbmt\Localizer::get('error.referer_num')]];

    } else if ( $parentMember->getPaid() == 0 ) {
      return [false, ['referer_num' => \Tbmt\Localizer::get('error.referer_paiment_outstanding')]];
    }

    if ( !isset($data['email']) )
      $data['email'] = '';

    return [true, $data];
  }

  static function numExists($num) {
    return \MemberQuery::create()->findOneByNum($num) !== null;
  }

  static public function createFromSignup($data) {
    // This functions expects this parameter to be valid!
    // E.g. the result from self::validateSignupForm()
    $member = new Member();
    $member
      ->setFirstName($data['firstName'])
      ->setLastName($data['lastName'])
      // ->setNum() autoincrement
      ->setEmail($data['email'])
      ->setCity($data['city'])
      ->setCountry($data['country'])
      ->setAge($data['age'])
      ->setRefererNum($data['referer_num'])
      ->setBankRecipient($data['bank_recipient'])
      ->setIban($data['iban'])
      ->setBic($data['bic'])
      ->setSignupDate(time())
      ->save();

    return $member;
  }
}
