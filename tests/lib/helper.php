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
  ];

  static public $memberSignup = [
    'title'          => 'unknown',
    'lastName'       => 'unknown',
    'firstName'      => 'unknown',
    'age'            => 99,
    'email'          => 'unknown',
    'city'           => 'unknown',
    'country'        => 'unknown',
    'bank_recipient' => 'unknown',
    'iban'           => 'unknown',
    'bic'            => 'unknown',
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
    $member = Member::createFromSignup(self::$memberSignup, $referralMember, null, self::$con);
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

      $currentBonusIds = MemberBonusIds::populate($IT, '[]');
    }


    /* marketing leader
    ---------------------------------------------*/
    $VL_t = 0;
    $VL = null;
    if ( $options['VL'] ) {
      $VL = DbEntityHelper::createMember($currentParent, [
        'Type' => Member::TYPE_MARKETINGLEADER,
        'FundsLevel' => Member::FUNDS_LEVEL2,
        'BonusIds' => $currentBonusIds
      ]);

      $currentBonusIds = MemberBonusIds::populate($VL, $VL->getBonusIds());
      $currentParent = $VL;
    }


    /* org leader
    ---------------------------------------------*/
    $OL_t = 0;
    $OL = null;
    if ( $options['OL'] ) {
      $OL = DbEntityHelper::createMember($currentParent, [
        'Type' => Member::TYPE_ORGLEADER,
        'FundsLevel' => Member::FUNDS_LEVEL2,
        'BonusIds' => $currentBonusIds
      ]);

      $currentBonusIds = MemberBonusIds::populate($OL, $OL->getBonusIds());
      $currentParent = $OL;
    }


    /* promoter
    ---------------------------------------------*/
    $PM_t = 0;
    $PM = null;
    if ( $options['PM'] ) {
      $IT_t += Transaction::AMOUNT_IT_BONUS;
      $VL_t += Transaction::AMOUNT_VL_BONUS;

      if ( !$OL ) $OL_t = &$VL_t;
      $OL_t += Transaction::AMOUNT_ADVERTISED_LVL2 +
        Transaction::AMOUNT_OL_BONUS +
        Transaction::AMOUNT_PM_BONUS;

      // TODO question:
      // kriegt der ol in diesem fall 22 oder 21 euro ?
      // 22 weil der ja den bonus der promoters kriegt wenn er jemand
      // wirbt ohne das ein promoter dazwischen ist?

      if ( $currentParent ) {
        $PM = DbEntityHelper::createSignupMember($currentParent);

      } else {
        $PM = DbEntityHelper::createMember($currentParent, [
          'BonusIds' => $currentBonusIds
        ]);
        $IT_t -= Transaction::AMOUNT_IT_BONUS;
        $VL_t -= Transaction::AMOUNT_VL_BONUS;
        $OL_t -= Transaction::AMOUNT_ADVERTISED_LVL2 +
          Transaction::AMOUNT_OL_BONUS +
          Transaction::AMOUNT_PM_BONUS;
      }

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
      $IT_t += Transaction::AMOUNT_IT_BONUS * 3;
      $VL_t += Transaction::AMOUNT_VL_BONUS * 3;

      if ( !$OL ) $OL_t = &$VL_t;
      $OL_t += Transaction::AMOUNT_OL_BONUS * 3;

      if ( !$PM ) $PM_t = &$OL_t;
      $PM_t += Transaction::AMOUNT_ADVERTISED_LVL2 +
        Transaction::AMOUNT_PM_BONUS +
        2 * (
          Transaction::AMOUNT_PM_BONUS + Transaction::AMOUNT_ADVERTISED_INDIRECT
        );

      $VS2_t += 2 * Transaction::AMOUNT_ADVERTISED_LVL1;
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
      $IT_t += Transaction::AMOUNT_IT_BONUS;
      $VL_t += Transaction::AMOUNT_VL_BONUS;

      if ( !$OL ) $OL_t = &$VL_t;
      $OL_t += Transaction::AMOUNT_OL_BONUS;

      if ( !$PM ) $PM_t = &$OL_t;
      $PM_t += Transaction::AMOUNT_PM_BONUS;

      if ( !$VS2 ) $VS2_t = &$PM_t;
      $VS2_t += Transaction::AMOUNT_ADVERTISED_LVL2;

      $VS1 = DbEntityHelper::createSignupMember($currentParent);
    }

    return [
      [$IT, $VL, $OL, $PM, $VS2, $VS1],
      [$IT_t, $VL_t, $OL_t, $PM_t, $VS2_t, $VS1_t],
    ];
  }
}