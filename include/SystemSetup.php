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

  static public function doSetupUnitTests() {
    $IT_SPECIALIST_EMAIL = 'niko.neuhauser@gmail.com';

    \SystemStats::_refreshForUnitTests();


    /* Setup - SYSTEM ACCOUNT
    ---------------------------------------------*/
    self::createMember(null, [
      'LastName'  => 'system',
      'FirstName' => 'account',
      'Email'     => $IT_SPECIALIST_EMAIL,
      'Num'       => \SystemStats::ACCOUNT_NUM_SYSTEM,
      'Type'      => \Member::TYPE_SYSTEM
    ]);


    /* Setup - ROOT ACCOUNT
    ---------------------------------------------*/
    self::createMember(null, [
      'LastName'  => 'root',
      'FirstName' => 'account',
      'Email'     => $IT_SPECIALIST_EMAIL,
      'Num'       => \SystemStats::ACCOUNT_NUM_ROOT,
      'Type'      => \Member::TYPE_SYSTEM
    ]);


    /* Setup - CEO1
    ---------------------------------------------*/
    $ceo1 = self::createMember(null, [
      'LastName'  => 'system',
      'FirstName' => 'ceo1',
      'Email'     => $IT_SPECIALIST_EMAIL,
      'Num'       => \SystemStats::ACCOUNT_NUM_CEO1,
      'Type'      => \Member::TYPE_CEO
    ]);


    /* SET auto increment counter for member numbers
    ---------------------------------------------*/
    $sql = "SELECT setval('tbmt_member_num_seq', 1000001);";
    $stmt = self::$con->prepare($sql);
    $stmt->execute();
  }

  static public function doSetup() {
    $IT_SPECIALIST_EMAIL = 'niko.neuhauser@gmail.com';


    /* Setup - SYSTEM ACCOUNT
    ---------------------------------------------*/
    self::createMember(null, [
      'LastName'  => 'system',
      'FirstName' => 'account',
      'Email'     => $IT_SPECIALIST_EMAIL,
      'Num'       => \SystemStats::ACCOUNT_NUM_SYSTEM,
      'Type'      => \Member::TYPE_SYSTEM
    ]);


    /* Setup - ROOT ACCOUNT
    ---------------------------------------------*/
    self::createMember(null, [
      'LastName'  => 'root',
      'FirstName' => 'account',
      'Email'     => $IT_SPECIALIST_EMAIL,
      'Num'       => \SystemStats::ACCOUNT_NUM_ROOT,
      'Type'      => \Member::TYPE_SYSTEM
    ]);


    /* Setup - CEO1
    ---------------------------------------------*/
    $ceo1 = self::createMember(null, [
      'LastName'  => 'system',
      'FirstName' => 'ceo1',
      'Email'     => $IT_SPECIALIST_EMAIL,
      'Num'       => \SystemStats::ACCOUNT_NUM_CEO1,
      'Type'      => \Member::TYPE_CEO
    ]);


    /* Setup - CEO2
    ---------------------------------------------*/
    $ceo2 = self::createMember(null, [
      'LastName'  => 'system',
      'FirstName' => 'ceo2',
      'Email'     => $IT_SPECIALIST_EMAIL,
      'Num'       => \SystemStats::ACCOUNT_NUM_CEO2,
      'Type'      => \Member::TYPE_CEO
    ]);


    /* Setup - IT
    ---------------------------------------------*/
    $it = self::createMember(null, [
      'LastName'  => 'system',
      'FirstName' => 'it',
      'Email'     => $IT_SPECIALIST_EMAIL,
      'Num'       => \SystemStats::ACCOUNT_NUM_IT,
      'Type'      => \Member::TYPE_MEMBER
    ]);


    /* Setup - LAWYER
    ---------------------------------------------*/
    $lawyer = self::createMember(null, [
      'LastName'  => 'system',
      'FirstName' => 'lawyer',
      'Email'     => $IT_SPECIALIST_EMAIL,
      'Num'       => \SystemStats::ACCOUNT_NUM_LAWYER,
      'Type'      => \Member::TYPE_MEMBER
    ]);


    /* Setup - TOP LEVEL BONUS IDS
    ---------------------------------------------*/
    $topLvlBonusIds = json_encode([$ceo1->getId(), $ceo2->getId(), $it->getId(), $lawyer->getId()]);

    $ceo1->setBonusIds($topLvlBonusIds);
    $ceo2->setBonusIds($topLvlBonusIds);
    $it->setBonusIds($topLvlBonusIds);
    $lawyer->setBonusIds($topLvlBonusIds);

    $ceo1->save(self::$con);
    $ceo2->save(self::$con);
    $it->save(self::$con);
    $lawyer->save(self::$con);


    /* SET auto increment counter for member numbers
    ---------------------------------------------*/
    $sql = "SELECT setval('tbmt_member_num_seq', 1000001);";
    $stmt = self::$con->prepare($sql);
    $stmt->execute();

  }
}

?>