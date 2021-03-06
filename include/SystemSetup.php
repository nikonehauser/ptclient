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
    'Title'         => '',
    'LastName'      => 'unknown',
    'FirstName'     => 'unknown',
    'Age'           => 99,
    'City'          => 'unknown',
    'Street'        => 'unknown',
    'StreetAdd'     => 'unknown',
    'Country'       => 'unknown',
    'ZipCode'       => '504231',
    'BankRecipient' => 'unknown',
    'Iban'          => '75622010002960', // any india bank for testing [a-zA-Z0-9]{5,20}
    'Bic'           => 'BKID0004062', // any india bank for testing
    'Password'      => 'demo1234',

    'BankName'      => '75622010002960', // any india bank for testing [a-zA-Z0-9]{5,20}
    'BankCity'      => 'BKID0004062', // any india bank for testing
    'BankStreet'    => 'demo1234',
    'BankZipCode'   => '75622010002960', // any india bank for testing [a-zA-Z0-9]{5,20}
    'BankCountry'   => 'BKID0004062', // any india bank for testing

    'SignupDate'    => 0,
    'PaidDate'      => 0,
    'IsExtended'    => 1,
    'BonusIds'      => '{}',
    'Passportfile'  => '',
    'Panfile'       => '',

    'PhotosExist'   => 1,
  ];

  static public function createMember(\Member $referralMember = null, array $data = array()) {
    $member = new \Member();

    $data['SignupDate'] = time();
    $data['PaidDate'] = time();

    $member->fromArray(array_merge(self::$memberDefaults, $data));
    if ( $referralMember )
      $member->setReferrerMember($referralMember, self::$con);

    if ( !isset($data['Hash']) )
      $member->setHash(\Member::calcHash($member));

    $member->save(self::$con);
    return $member;
  }

  static public function doSetupUnitTests() {
    \SystemStats::_refreshForUnitTests();
    self::doSetup();
  }

  /**
   * Do setup system. Execute once.
   *
   */
  static public function doSetup() {
    $financeEmail = 'finance@betterliving.social';
    $ceoEmail = 'bonus@betterliving.social';
    $itEmail = 'niko.neuhauser@gmail.com';
    $sylvheimEmail = 'test33@gmx.net';
    $executiveEmail = 'test34@gmx.net';


    /* Setup - SYSTEM ACCOUNT
    ---------------------------------------------*/
    self::createMember(null, [
      'LastName'  => 'account',
      'FirstName' => 'system',
      'Email'     => $financeEmail,
      'Num'       => \SystemStats::ACCOUNT_NUM_SYSTEM,
      'Type'      => \Member::TYPE_SYSTEM,
      'FundsLevel'=> \Member::FUNDS_LEVEL2
    ]);


    /* Setup - CEO1
    ---------------------------------------------*/
    $ceo1 = self::createMember(null, [
      'FirstName' => 'Marcus',
      'LastName'  => 'CEO',
      'Email'     => $ceoEmail,
      'Num'       => \SystemStats::ACCOUNT_NUM_CEO1,
      'Type'      => \Member::TYPE_CEO,
      'FundsLevel'=> \Member::FUNDS_LEVEL2
    ]);


    /* Setup - IT
    ---------------------------------------------*/
    $it = self::createMember(null, [
      'FirstName' => 'System',
      'LastName'  => 'IT',
      'Email'     => $itEmail,
      'Num'       => \SystemStats::ACCOUNT_NUM_IT,
      'Type'      => \Member::TYPE_ITSPECIALIST,
      'FundsLevel'=> \Member::FUNDS_LEVEL2,
      'Hash'      => '4cc5d7b8c6a54d929d0097d9d26b4d65bf2f038e'
    ]);

    /* Setup - SYLVHEIM
    ---------------------------------------------*/
    $sylvheim = self::createMember(null, [
      'ReferrerId' => $ceo1->getId(),
      'FirstName' => 'Sales',
      'LastName'  => 'Management',
      'Email'     => $sylvheimEmail,
      'Num'       => \SystemStats::ACCOUNT_SYLVHEIM,
      'Type'      => \Member::TYPE_SALES_MANAGER,
      'FundsLevel'=> \Member::FUNDS_LEVEL2
    ]);

    /* Setup - EXECUTIVE
    ---------------------------------------------*/
    // $executive = self::createMember(null, [
    //   'FirstName' => 'Administration',
    //   'LastName'  => 'Executive',
    //   'Email'     => $executiveEmail,
    //   'Num'       => \SystemStats::ACCOUNT_EXECUTIVE,
    //   'Type'      => \Member::TYPE_MEMBER,
    //   'FundsLevel'=> \Member::FUNDS_LEVEL2
    // ]);

    /* Setup - REASON_NGO_PROJECTS
    ---------------------------------------------*/
    // $ngoProjects = self::createMember(null, [
    //   'FirstName' => 'Projects',
    //   'LastName'  => 'NGO',
    //   'Email'     => $IT_SPECIALIST_EMAIL,
    //   'Num'       => \SystemStats::ACCOUNT_NGO_PROJECTS,
    //   'Type'      => \Member::TYPE_MEMBER,
    //   'FundsLevel'=> \Member::FUNDS_LEVEL2
    // ]);

    /* Setup - TOP LEVEL BONUS IDS
    ---------------------------------------------*/
    $topLvlBonusIds = json_encode([
      $ceo1->getId() => $ceo1->getType(),
      $it->getId() => $it->getType(),
      // $executive->getId() => $executive->getType(),
      // $taricWani->getId() => $taricWani->getType(),
      // $ngoProjects->getId() => $ngoProjects->getType()
    ]);

    $salesManagerBonusIds = json_encode([
      $ceo1->getId() => $ceo1->getType(),
      $it->getId() => $it->getType(),
      $sylvheim->getId() => $sylvheim->getType(),
      // $executive->getId() => $executive->getType(),
      // $taricWani->getId() => $taricWani->getType(),
      // $ngoProjects->getId() => $ngoProjects->getType()
    ]);

    $ceo1->setBonusIds($topLvlBonusIds);
    $it->setBonusIds($topLvlBonusIds);
    $sylvheim->setBonusIds($salesManagerBonusIds);
    // $executive->setBonusIds($topLvlBonusIds);
    // $taricWani->setBonusIds($topLvlBonusIds);
    // $ngoProjects->setBonusIds($topLvlBonusIds);

    $ceo1->save(self::$con);
    $it->save(self::$con);
    $sylvheim->save(self::$con);
    // $executive->save(self::$con);
    // $taricWani->save(self::$con);
    // $ngoProjects->save(self::$con);

    /* SET auto increment counter for member numbers
    ---------------------------------------------*/
    $sql = "ALTER SEQUENCE tbmt_member_num_seq RESTART WITH 1425300";
    $stmt = self::$con->prepare($sql);
    $stmt->execute();

    /* Setup - SYSTEM STATS
    ---------------------------------------------*/
    $systemStats = new \SystemStats();
    $systemStats->setInvitationIncrementer('2A15F6');
    $systemStats->save();

  }
}

?>