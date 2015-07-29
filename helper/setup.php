<?php

include dirname(__FILE__).'/bootstrap.php';

define('IT_SPECIALIST_EMAIL', 'niko.neuhauser@gmail.com');

class Setup {
  /**
   * @var PropelPDO
   */
  static protected $con = null;

  static public function setCon(PropelPDO $con) {
    self::$con = $con;
  }

  static private $memberDefaults = [
    'Title'         => 'unknown',
    'LastName'      => 'unknown',
    'FirstName'     => 'unknown',
    'Age'           => 99,
    'Email'         => 'unknown',
    'City'          => 'unknown',
    'Country'       => 'unknown',
    'BankRecipient' => 'unknown',
    'Iban'          => 'unknown',
    'Bic'           => 'unknown',
    'Password'      => 'demo1234',
    'SignupDate'    => 0,
    'PaidDate'      => 0,
  ];

  static public function createMember(Member $referralMember = null, array $data = array()) {
    $member = new Member();

    $member->fromArray(array_merge(self::$memberDefaults, $data));
    if ( $referralMember )
      $member->setRefererMember($referralMember, self::$con);

    $member->save(self::$con);
    return $member;
  }
}


$con = Propel::getConnection();
Setup::setCon($con);


$memberNum = 100;


/* Setup - SYSTEM
---------------------------------------------*/
Setup::createMember(null, [
  'LastName'  => 'system',
  'FirstName' => 'handler',
  'Email'     => IT_SPECIALIST_EMAIL,
  'Num'       => $memberNum++,
  'Type'      => \Member::TYPE_SYSTEM
]);


/* Setup - CEO1
---------------------------------------------*/
Setup::createMember(null, [
  'LastName'  => 'system',
  'FirstName' => 'ceo',
  'Email'     => IT_SPECIALIST_EMAIL,
  'Num'       => $memberNum++,
  'Type'      => \Member::TYPE_CEO
]);


?>