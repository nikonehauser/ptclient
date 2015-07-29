<?php

namespace Tbmt;

class SystemSetup {
  /**
   * @var PropelPDO
   */
  static protected $con = null;

  static public function setCon(\PropelPDO $con) {
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

  static public function createMember(\Member $referralMember = null, array $data = array()) {
    $member = new \Member();

    $member->fromArray(array_merge(self::$memberDefaults, $data));
    if ( $referralMember )
      $member->setRefererMember($referralMember, self::$con);

    $member->save(self::$con);
    return $member;
  }

  static public function doSetup() {
    $IT_SPECIALIST_EMAIL = 'niko.neuhauser@gmail.com';

    \SystemStats::_refreshForUnitTests();
    $memberNum = \SystemStats::ROOT_ACCOUNT_NUM;


    /* Setup - SYSTEM ACCOUNT
    ---------------------------------------------*/
    self::createMember(null, [
      'LastName'  => 'system',
      'FirstName' => 'account',
      'Email'     => $IT_SPECIALIST_EMAIL,
      'Num'       => \SystemStats::SYSTEM_ACCOUNT_NUM,
      'Type'      => \Member::TYPE_SYSTEM
    ]);


    /* Setup - ROOT ACCOUNT
    ---------------------------------------------*/
    self::createMember(null, [
      'LastName'  => 'root',
      'FirstName' => 'account',
      'Email'     => $IT_SPECIALIST_EMAIL,
      'Num'       => $memberNum++,
      'Type'      => \Member::TYPE_SYSTEM
    ]);


    /* Setup - CEO1
    ---------------------------------------------*/
    self::createMember(null, [
      'LastName'  => 'system',
      'FirstName' => 'ceo',
      'Email'     => $IT_SPECIALIST_EMAIL,
      'Num'       => $memberNum++,
      'Type'      => \Member::TYPE_CEO
    ]);


    /* SET auto increment counter for member numbers
    ---------------------------------------------*/
    $sql = "SELECT setval('tbmt_member_num_seq', 1000001);";
    $stmt = self::$con->prepare($sql);
    $stmt->execute();

  }
}

?>