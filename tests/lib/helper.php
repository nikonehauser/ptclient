<?php

class DbEntityHelper {
  /**
   * @var PropelPDO
   */
  static protected $con = null;

  static private $it_member = null;

  static public function setCon(PropelPDO $con) {
    self::$con = $con;
  }

  static public function truncateDatabase(PropelPDO $con) {
    self::truncateTables([
      MemberPeer::TABLE_NAME
    ], $con);
  }

  static public function truncateTables(array $tables, PropelPDO $con) {
    $con->exec('TRUNCATE TABLE '.implode(',', $tables). ' RESTART IDENTITY CASCADE');
  }

  static private $memberDefaults = [
    'Title'         => 'title',
    'LastName'      => 'title',
    'FirstName'     => 'title',
    'Age'           => 25,
    'Email'         => 'title',
    'City'          => 'title',
    'Country'       => 'title',
    'BankRecipient' => 'title',
    'Iban'          => 'title',
    'Bic'           => 'title',
    'Password'      => 'demo1234',
  ];

  static private $memberSignup = [
    'title'          => 'test',
    'lastName'       => 'test',
    'firstName'      => 'test',
    'age'            => 25,
    'email'          => 'test',
    'city'           => 'test',
    'country'        => 'test',
    'bank_recipient' => 'test',
    'iban'           => 'test',
    'bic'            => 'test',
    'password'       => 'demo1234',
  ];

  static public function createMember(Member $referralMember = null, array $data = array()) {
    $member = new Member();

    $member->fromArray(array_merge(self::$memberDefaults, $data));
    if ( $referralMember )
      $member->setRefererMember($referralMember, self::$con);

    $now = time();
    $member->setSignupDate($now)
      ->setPaidDate($now + 100000)
      ->save(self::$con);

    return $member;
  }

  static public function createSignupMember(Member $referralMember, $receivedPaiment = true) {
    $member = Member::createFromSignup(self::$memberSignup, $referralMember, self::$con);
    if ( $receivedPaiment )
      $member->onReceivedMemberFee(time(), self::$con);

    return $member;
  }

  static public function resetBonusMembers() {
    self::$it_member = null;
  }

  static public function setUpBonusMembers($doReset = true, $options = false) {
    $options = array_merge([
      'IT' => true,
      'VL' => true,
      'OL' => true,
      'PM' => true,
      'VS2' => true,
      'VS1' => true,
    ], $options ? $options : []);

    $currentParent = null;
    $currentBonusIds = '[]';

    /* it specialists
    ---------------------------------------------*/
    $IT_t = 0;
    $IT = null;
    if ( $options['IT'] ) {
      if ( $doReset === true || self::$it_member === null ) {
        $IT_t = 0;
        $IT = self::$it_member = DbEntityHelper::createMember($currentParent, [
          'Type' => Member::TYPE_ITSPECIALIST,
          'FundsLevel' => Member::FUNDS_LEVEL2,
        ]);
      } else {
        $IT = self::$it_member;
        $IT_t = self::$it_member->getOutstandingTotal();
      }
    }


    /* marketing leader
    ---------------------------------------------*/
    $VL_t = 0;
    $VL = null;
    if ( $options['VL'] ) {
      $currentBonusIds = MemberBonusIds::populate($IT, '[]');

      $VL = DbEntityHelper::createMember($currentParent, [
        'Type' => Member::TYPE_MARKETINGLEADER,
        'FundsLevel' => Member::FUNDS_LEVEL2,
        'BonusIds' => $currentBonusIds
      ]);

      $currentParent = $VL;
    }


    /* org leader
    ---------------------------------------------*/
    $OL_t = 0;
    $OL = null;
    if ( $options['OL'] ) {
      $currentBonusIds = MemberBonusIds::populate($VL, $VL->getBonusIds());
      $OL = DbEntityHelper::createMember($currentParent, [
        'Type' => Member::TYPE_ORGLEADER,
        'FundsLevel' => Member::FUNDS_LEVEL2,
        'BonusIds' => $currentBonusIds
      ]);

      $currentParent = $OL;
    }


    /* promoter
    ---------------------------------------------*/
    $PM_t = 0;
    $PM = null;
    if ( $options['PM'] ) {
      $IT_t += 1;
      $VL_t += 1;
      if ( $OL ) $OL_t += 22; else $VL_t += 22;

      // TODO question:
      // kriegt der ol in diesem fall 22 oder 21 euro ?
      // 22 weil der ja den bonus der promoters kriegt wenn er jemand
      // wirbt ohne das ein promoter dazwischen ist?

      $PM = DbEntityHelper::createSignupMember($currentParent);
      $PM->setType(Member::TYPE_PROMOTER)
        ->setFundsLevel(Member::FUNDS_LEVEL2)
        ->save(self::$con);

      $currentParent = $PM;
    }


    /* funds level 2
    ---------------------------------------------*/
    $VS2_t = 0;
    $VS2 = null;
    if ( $options['VS2'] ) {
      $IT_t += 3;
      $VL_t += 3;
      if ( $OL ) $OL_t += 3; else $VL_t += 3;
      $PM_t += 21 + 2 * 16;
      $VS2_t += 10;
      $VS2 = DbEntityHelper::createSignupMember($currentParent);
      DbEntityHelper::createSignupMember($VS2);
      DbEntityHelper::createSignupMember($VS2);

      $currentParent = $VS2;
    }


    /* funds level 1
    ---------------------------------------------*/
    $VS1_t = 0;
    $VS1 = null;
    if ( $options['VS1'] ) {
      $IT_t += 1;
      $VL_t += 1;
      if ( $OL ) $OL_t += 1; else $VL_t += 1;
      $PM_t += 1;
      $VS2_t += 20;
      $VS1 = DbEntityHelper::createSignupMember($currentParent);
    }

    return [
      [$IT, $VL, $OL, $PM, $VS2, $VS1],
      [$IT_t, $VL_t, $OL_t, $PM_t, $VS2_t, $VS1_t],
    ];
  }
}