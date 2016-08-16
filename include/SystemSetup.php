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
    'Email'         => 'niko.neuhauser@gmail.com',
    'City'          => 'unknown',
    'Country'       => 'unknown',
    'ZipCode'       => '504231',
    'BankRecipient' => 'unknown',
    'Iban'          => 'unknown',
    'Bic'           => 'unknown',
    'Password'      => 'demo1234',
    'SignupDate'    => 0,
    'PaidDate'      => 0,
  ];

  static public function createMember(\Member $referralMember = null, array $data = array()) {
    $member = new \Member();

    $data['SignupDate'] = time();
    $data['PaidDate'] = time();

    $member->fromArray(array_merge(self::$memberDefaults, $data));
    if ( $referralMember )
      $member->setReferrerMember($referralMember, self::$con);

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
    $IT_SPECIALIST_EMAIL = 'niko.neuhauser@gmail.com';


    /* Setup - SYSTEM ACCOUNT
    ---------------------------------------------*/
    self::createMember(null, [
      'LastName'  => 'account',
      'FirstName' => 'system',
      'Email'     => $IT_SPECIALIST_EMAIL,
      'Num'       => \SystemStats::ACCOUNT_NUM_SYSTEM,
      'Type'      => \Member::TYPE_SYSTEM,
      'FundsLevel'=> \Member::FUNDS_LEVEL2
    ]);


    /* Setup - CEO1
    ---------------------------------------------*/
    $ceo1 = self::createMember(null, [
      'FirstName' => 'Marcus',
      'LastName'  => 'CEO',
      'Email'     => $IT_SPECIALIST_EMAIL,
      'Num'       => \SystemStats::ACCOUNT_NUM_CEO1,
      'Type'      => \Member::TYPE_CEO,
      'FundsLevel'=> \Member::FUNDS_LEVEL2
    ]);


    /* Setup - IT
    ---------------------------------------------*/
    $it = self::createMember(null, [
      'FirstName' => 'System',
      'LastName'  => 'IT',
      'Email'     => $IT_SPECIALIST_EMAIL,
      'Num'       => \SystemStats::ACCOUNT_NUM_IT,
      'Type'      => \Member::TYPE_ITSPECIALIST,
      'FundsLevel'=> \Member::FUNDS_LEVEL2
    ]);

    /* Setup - SYLVHEIM
    ---------------------------------------------*/
    $sylvheim = self::createMember(null, [
      'ReferrerId' => $ceo1->getId(),
      'FirstName' => 'Sales',
      'LastName'  => 'Management',
      'Email'     => 'test35@gmx.de',
      'Num'       => \SystemStats::ACCOUNT_SYLVHEIM,
      'Type'      => \Member::TYPE_SALES_MANAGER,
      'FundsLevel'=> \Member::FUNDS_LEVEL2
    ]);

    /* Setup - EXECUTIVE
    ---------------------------------------------*/
    $executive = self::createMember(null, [
      'FirstName' => 'Administration',
      'LastName'  => 'Executive',
      'Email'     => $IT_SPECIALIST_EMAIL,
      'Num'       => \SystemStats::ACCOUNT_EXECUTIVE,
      'Type'      => \Member::TYPE_MEMBER,
      'FundsLevel'=> \Member::FUNDS_LEVEL2
    ]);

    /* Setup - TARIC WANI
    ---------------------------------------------*/
    // $taricWani = self::createMember(null, [
    //   'FirstName' => 'Taric',
    //   'LastName'  => 'Wani',
    //   'Email'     => $IT_SPECIALIST_EMAIL,
    //   'Num'       => \SystemStats::ACCOUNT_TARIC_WANI,
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
      $executive->getId() => $executive->getType(),
      // $taricWani->getId() => $taricWani->getType(),
      // $ngoProjects->getId() => $ngoProjects->getType()
    ]);

    $salesManagerBonusIds = json_encode([
      $ceo1->getId() => $ceo1->getType(),
      $it->getId() => $it->getType(),
      $sylvheim->getId() => $sylvheim->getType(),
      $executive->getId() => $executive->getType(),
      // $taricWani->getId() => $taricWani->getType(),
      // $ngoProjects->getId() => $ngoProjects->getType()
    ]);

    $ceo1->setBonusIds($topLvlBonusIds);
    $it->setBonusIds($topLvlBonusIds);
    $sylvheim->setBonusIds($salesManagerBonusIds);
    $executive->setBonusIds($topLvlBonusIds);
    // $taricWani->setBonusIds($topLvlBonusIds);
    // $ngoProjects->setBonusIds($topLvlBonusIds);

    $ceo1->save(self::$con);
    $it->save(self::$con);
    $sylvheim->save(self::$con);
    $executive->save(self::$con);
    // $taricWani->save(self::$con);
    // $ngoProjects->save(self::$con);

    /* SET auto increment counter for member numbers
    ---------------------------------------------*/
    $sql = "SELECT setval('tbmt_member_num_seq', 1000001);";
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