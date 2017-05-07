<?php

/**
 * @see file tests/Bonusstufen.doc
 */
class BonusSpreadingTest extends Tbmt_Tests_DatabaseTestCase {

  // static public function setUpBeforeClass() {
  //   parent::setUpBeforeClass();
  //   DbEntityHelper::truncateDatabase(self::$propelCon);
  // }

  // public function setUp() {
  //   parent::setUp();
  //   DbEntityHelper::resetBonusMembers();
  // }

  /**
   * Vertriebsleiter => VL
   * Organisationsleiter => OL
   * Promoter => PM
   *
   */

  public function testVariant1() {
    /**
     * VL -  OL - PM - VS2 - VS1 – Neues Mitglied
     * 1$ -  1$ - 1$ - 15$ -  5$
     * ------------------------------------------*/

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers();

    $new = DbEntityHelper::createSignupMember($VS1);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);
    $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS);
    $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

    // Setting up another tree should NOT change the bonuses for
    // the previous tree except of special types member like it specialist.
    list(
      list(, , , , , $newVS1),
      list($newTrfIT)
    ) = DbEntityHelper::setUpBonusMembers(false);

    DbEntityHelper::createSignupMember($newVS1);

    $this->assertTransferTotal($newTrfIT + Transaction::getAmountForReason(Transaction::REASON_IT_BONUS), $IT);
    $this->assertTransferTotal($trfVL, $VL);
    $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);
  }

  public function testVariant2() {
    /**
     * VL -  OL - PM - VS2 – Neues Mitglied
     * 1$ -  1$ - 1$ - 20$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers();

    $new = DbEntityHelper::createSignupMember($VS2);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);
    $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS);
    $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL2);

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);
  }

  public function testVariant3() {
    /**
     * VL -  PM - VS2 – VS1 - Neues Mitglied
     * 2$ -  1$ - 15$ -  5$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'OL' => false
    ]);

    $new = DbEntityHelper::createSignupMember($VS1);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_OL_BONUS);
    // $trfOL += 1;
    $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    // $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);
  }

  public function testVariant4() {
    /**
     * VL -  VS2 – VS1 - Neues Mitglied
     * 3$ -  15$ -  5$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'OL' => false,
        'PM' => false
    ]);

    $new = DbEntityHelper::createSignupMember($VS1);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS)
      + Transaction::getAmountForReason(Transaction::REASON_OL_BONUS)
      + Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    // $trfOL += 1;
    // $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    // $this->assertTransferTotal($trfOL, $OL);
    // $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  public function testVariant5() {
    /**
     * VL -  OL - VS2 – VS1 - Neues Mitglied
     * 1$ -  2$ - 15$ -  5$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'PM' => false
    ]);

    $new = DbEntityHelper::createSignupMember($VS1);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);
    $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    // $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    $this->assertTransferTotal($trfOL, $OL);
    // $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  public function testVariant6() {
    /**
     * VL  -  VS1 - Neues Mitglied
     * 18$ -  5$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'OL' => false,
        'PM' => false,
        'VS2' => false,
    ]);

    $new = DbEntityHelper::createSignupMember($VS1);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS)
      + Transaction::getAmountForReason(Transaction::REASON_OL_BONUS)
      + Transaction::getAmountForReason(Transaction::REASON_PM_BONUS)
      + Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    // $trfOL += 1;
    // $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    // $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    // $this->assertTransferTotal($trfOL, $OL);
    // $this->assertTransferTotal($trfPM, $PM);
    // $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  public function testVariant7() {
    /**
     * VL -  OL  – VS1 - Neues Mitglied
     * 1$ -  17$ -  5$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'PM' => false,
        'VS2' => false,
    ]);

    $new = DbEntityHelper::createSignupMember($VS1);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);
    $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS)
      + Transaction::getAmountForReason(Transaction::REASON_PM_BONUS)
      + Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    // $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    // $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    $this->assertTransferTotal($trfOL, $OL);
    // $this->assertTransferTotal($trfPM, $PM);
    // $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  public function testVariant8() {
    /**
     * PM -  VS2  – VS1 - Neues Mitglied
     * 1$ -  15$  - 5$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'VL' => false,
        'OL' => false,
    ]);

    $new = DbEntityHelper::createSignupMember($VS1);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    // $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);
    // $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS);
    $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    // $this->assertTransferTotal($trfVL, $VL);
    // $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  public function testVariant9() {
    /**
     * PM  -  VS1 - Neues Mitglied
     * 16$ -  5$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'VL' => false,
        'OL' => false,
        'VS2' => false,
    ]);

    $new = DbEntityHelper::createSignupMember($VS1);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    // $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);
    // $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS);
    $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    // $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    // $this->assertTransferTotal($trfVL, $VL);
    // $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    // $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  public function testVariant10() {
    /**
     * PM - Neues Mitglied
     * 21$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'VL' => false,
        'OL' => false,
    ]);

    $new = DbEntityHelper::createSignupMember($PM);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    // $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);
    // $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS);
    $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL2);
    // $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    // $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    // $this->assertTransferTotal($trfVL, $VL);
    // $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  public function testVariant11() {
    /**
     * VL - Neues Mitglied
     * 23$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
    ]);

    $new = DbEntityHelper::createSignupMember($VL);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_OL_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_PM_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL2);
    // $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS);
    // $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    // $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    // $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  public function testVariant12() {
    /**
     * OL - Neues Mitglied
     * 22$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(true, [
        'VL' => false,
    ]);

    $new = DbEntityHelper::createSignupMember($OL);

    $trfIT += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
    // $trfVL += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);
    $trfOL += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_PM_BONUS) +
      Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL2);
    // $trfPM += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
    // $trfVS2 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT);
    // $trfVS1 += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTransferTotal($trfIT, $IT);
    // $this->assertTransferTotal($trfVL, $VL);
    $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

  }

  static private $singupFormData = [
    'title'          => 'unknown',
    'lastName'       => 'unknown',
    'firstName'      => 'unknown',
    'age'            => 99,
    'email'          => 'unknown@un.de',
    'city'           => 'unknown',
    'country'        => 'unknown',
    'zip_code'       => '504231',
    'bank_recipient' => 'unknown',
    'iban'           => 'unknown',
    'bic'            => 'unknown',
    'password'       => 'demo1234',
    'password2'       => 'demo1234',
    'accept_agbs'          => '1',
    'accept_valid_country' => '1',
  ];

  public function testSubPromoterBonusSpreading() {
    // Deprecated
    $this->assertTrue(true);
    return;
    /**
     *       PM
     *       1$
     *
     * VL -  S_PM - Neues Mitglied
     * 1$ -  1$  - 5$
     * ------------------------------------------*/

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);
    $marketingLeader = DbEntityHelper::createMember(null, [
      'Type' => Member::TYPE_MARKETINGLEADER,
      'FundsLevel' => Member::FUNDS_LEVEL2
    ]);
    $marketingLeader->reload(self::$propelCon);
    $mLeaderTransfer = new TransactionTotalsAssertions($marketingLeader, $this);

    $promoter = DbEntityHelper::createSignupMember($marketingLeader);
    $mLeaderTransfer->add(Transaction::REASON_ADVERTISED_LVL2);
    $mLeaderTransfer->add(Transaction::REASON_VL_BONUS);
    $mLeaderTransfer->add(Transaction::REASON_OL_BONUS);
    $mLeaderTransfer->add(Transaction::REASON_PM_BONUS);

    /* Create invitation
    ---------------------------------------------*/
    $invitation = Invitation::create(
      $marketingLeader, [
        'type' => Member::TYPE_SUB_PROMOTER,
        'free_signup' => 1,
        'promoter_num' => $promoter->getNum(),
        'promoter_id' => $promoter->getId(),
      ],
      self::$propelCon
    );

    /* Create member with created invitation code
    ---------------------------------------------*/
    list($valid, $data, $referralMember, $invitation)
      = \Member::validateSignupForm(array_merge(self::$singupFormData, [
        'referral_member_num' => $marketingLeader->getNum(),
        'invitation_code' => $invitation->getHash(),
      ]));

    $subPromoter = \Member::createFromSignup($data, $marketingLeader, $invitation, self::$propelCon);
    $subPromoter->reload(self::$propelCon);

    /* Sub promoter recruits anyone
    ---------------------------------------------*/
    DbEntityHelper::createSignupMember($subPromoter);

    $subPromoterTransfer = new TransactionTotalsAssertions($subPromoter, $this);
    $promoterTransfer = new TransactionTotalsAssertions($promoter, $this);

    $mLeaderTransfer->add(Transaction::REASON_VL_BONUS);
    $mLeaderTransfer->add(Transaction::REASON_ADVERTISED_INDIRECT);

    $promoterTransfer->add(Transaction::REASON_SUB_PM_REF_BONUS);
    $subPromoterTransfer->add(Transaction::REASON_SUB_PM_BONUS);
    $subPromoterTransfer->add(Transaction::REASON_ADVERTISED_LVL1);

    $mLeaderTransfer->assertTotals();
    $promoterTransfer->assertTotals();
    $subPromoterTransfer->assertTotals();
  }

  private function assertTransferTotal($total, Member $member) {
    $transfer = DbEntityHelper::getCurrentTransferBundle($member);
    $this->assertEquals($total, $transfer->getAmountSum(), 'Incorrect transfer total');
    $outTot = $member->getOutstandingTotal();
    $outTot = isset($outTot[DbEntityHelper::$currency]) ? $outTot[DbEntityHelper::$currency] : 0;
    $this->assertEquals($total, $outTot, 'Incorrect outstanding total');
  }

  public function testSylvhelmBonuses() {
    $sylvheim = Member::getByNum(\SystemStats::ACCOUNT_SYLVHEIM);
    $sylvheim_total = new TransactionTotalsAssertions($sylvheim, $this);

    $any = DbEntityHelper::createSignupMember($sylvheim);

    $sylvheim_total->add(Transaction::REASON_ADVERTISED_LVL2, 1);
    $sylvheim_total->add(Transaction::REASON_VL_BONUS, 1);
    $sylvheim_total->add(Transaction::REASON_OL_BONUS, 1);
    $sylvheim_total->add(Transaction::REASON_PM_BONUS, 1);
    $sylvheim_total->add(Transaction::REASON_SYLVHEIM, 1);

    $sylvheim_total->assertTotals();

  }

  public function testCeoBonuses() {
    $ceo = Member::getByNum(\SystemStats::ACCOUNT_NUM_CEO1);
    $ceo_total = new TransactionTotalsAssertions($ceo, $this);

    $any = DbEntityHelper::createSignupMember($ceo);

    $ceo_total->add(Transaction::REASON_ADVERTISED_LVL2, 1);
    $ceo_total->add(Transaction::REASON_VL_BONUS, 1);
    $ceo_total->add(Transaction::REASON_OL_BONUS, 1);
    $ceo_total->add(Transaction::REASON_PM_BONUS, 1);
    $ceo_total->add(Transaction::REASON_SYLVHEIM, 1);
    $ceo_total->add(Transaction::REASON_CEO1_BONUS, 1);

    $ceo_total->assertTotals();

  }

  public function testTopLevelBonusSpreading() {
    $ceo = Member::getByNum(\SystemStats::ACCOUNT_NUM_CEO1);
    $it = Member::getByNum(\SystemStats::ACCOUNT_NUM_IT);
    $sylvheim = Member::getByNum(\SystemStats::ACCOUNT_SYLVHEIM);

    $ceo_total = new TransactionTotalsAssertions($ceo, $this);
    $it_total = new TransactionTotalsAssertions($it, $this);
    $sylvheim_total = new TransactionTotalsAssertions($sylvheim, $this);

    // Any advertise any
    $any = DbEntityHelper::createSignupMember($it);

    $ceo_total->add(Transaction::REASON_CEO1_BONUS, 1);
    $ceo_total->add(Transaction::REASON_VL_BONUS, 1);
    $ceo_total->add(Transaction::REASON_OL_BONUS, 1);
    $ceo_total->add(Transaction::REASON_PM_BONUS, 1);
    $ceo_total->add(Transaction::REASON_SYLVHEIM, 1);

    $it_total->add(Transaction::REASON_IT_BONUS, 1);
    $it_total->add(Transaction::REASON_ADVERTISED_LVL2, 1);
    // Sylvhelm gets nothing here!

    $ceo_total->assertTotals();
    $it_total->assertTotals();
    $sylvheim_total->assertTotals();

  }


    /**
     *
     */
  public function testCanReadCorrectTreeDependantBonusAmount() {
    $sylvheim = Member::getByNum(\SystemStats::ACCOUNT_SYLVHEIM);

    $marketingLeader = DbEntityHelper::createMemberWithInvitation(
      $sylvheim,
      Member::TYPE_MARKETINGLEADER,
      array_merge(self::$singupFormData, [
        'email'          => 'unknowninvitationMarkt5@un5.de'
      ])
    );
    $marketingLeader->reload(self::$propelCon);

    $any1 = DbEntityHelper::createSignupMember($marketingLeader);
    $any2 = DbEntityHelper::createSignupMember($marketingLeader);
    $any3 = DbEntityHelper::createSignupMember($marketingLeader);

    // marketing leader gets 1 + orgleader + promoter = 3 euro
    $this->assertEquals(3, $any3->calculateBonusAmountForPremiumParent($marketingLeader, self::$propelCon));


    // add a promoter
    $promoter = DbEntityHelper::createMemberWithInvitation(
      $marketingLeader,
      Member::TYPE_PROMOTER,
      array_merge(self::$singupFormData, [
        'email'          => 'unknowninvitationProm5@un5.de'
      ])
    );

    $any4 = DbEntityHelper::createSignupMember($promoter);
    $any5 = DbEntityHelper::createSignupMember($promoter);
    $any6 = DbEntityHelper::createSignupMember($promoter);

    $this->assertEquals(2, $any6->calculateBonusAmountForPremiumParent($marketingLeader, self::$propelCon));
  }

  public function testLvl2InvitationBonusSpreading() {
    $ceo = Member::getByNum(\SystemStats::ACCOUNT_NUM_CEO1);
    $ceo_total = new TransactionTotalsAssertions($ceo, $this);

    // director mit freier lvl2 einladung von ceo
    $director = DbEntityHelper::createMemberWithInvitation($ceo, [
      'type' => Member::TYPE_MARKETINGLEADER,
      'lvl2_signup' => true,
      'free_signup' => true,
    ], [
      'lastName' => 'director'
    ]);
    $director_total = new TransactionTotalsAssertions($director, $this);

    $this->assertEquals(Member::FUNDS_LEVEL2, $director->getFundsLevel());
    $ceo_total->assertTotals();

    //
    // director wirbt 1 promoter
    //
    $prom1 = DbEntityHelper::createMemberWithInvitation($director, Member::TYPE_PROMOTER, [
      'lastName' => 'promoter'
    ]);
    $prom1_total = new TransactionTotalsAssertions($prom1, $this);

    $director_total->add(Transaction::REASON_VL_BONUS, 1);
    $director_total->add(Transaction::REASON_OL_BONUS, 1);
    $director_total->add(Transaction::REASON_PM_BONUS, 1);
    $director_total->add(Transaction::REASON_ADVERTISED_LVL2, 1);

    $ceo_total->add(Transaction::REASON_SYLVHEIM);
    $ceo_total->add(Transaction::REASON_CEO1_BONUS, 1);

    $director_total->assertTotals();
    $ceo_total->assertTotals();

    //
    // promoter wirbt 1 customer
    //
    $customer1 = DbEntityHelper::createSignupMember($prom1);
    $customer1_total = new TransactionTotalsAssertions($customer1, $this);

    $director_total->add(Transaction::REASON_VL_BONUS, 1);
    $director_total->add(Transaction::REASON_OL_BONUS, 1);
    $director_total->add(Transaction::REASON_ADVERTISED_INDIRECT, 1);

    $prom1_total->add(Transaction::REASON_PM_BONUS, 1);
    $prom1_total->add(Transaction::REASON_ADVERTISED_LVL1, 1);

    $ceo_total->add(Transaction::REASON_SYLVHEIM, 1);
    $ceo_total->add(Transaction::REASON_CEO1_BONUS, 1);

    $director_total->assertTotals();
    $ceo_total->assertTotals();
    $prom1_total->assertTotals();

    //
    // customer 1 wirbt 2 customer
    //
    DbEntityHelper::createSignupMember($customer1);
    DbEntityHelper::createSignupMember($customer1);

    $director_total->add(Transaction::REASON_VL_BONUS, 2);
    $director_total->add(Transaction::REASON_OL_BONUS, 2);
    $director_total->add(Transaction::REASON_ADVERTISED_INDIRECT, 2);

    $prom1_total->add(Transaction::REASON_PM_BONUS, 2);

    $customer1_total->add(Transaction::REASON_ADVERTISED_LVL1, 2);

    $ceo_total->add(Transaction::REASON_SYLVHEIM, 2);
    $ceo_total->add(Transaction::REASON_CEO1_BONUS, 2);

    $director_total->assertTotals();
    $ceo_total->assertTotals();
    $prom1_total->assertTotals();
    $customer1_total->assertTotals();

    //
    // customer 1 wirbt seinen dritten
    //
    $customer4 = DbEntityHelper::createSignupMember($customer1);
    $customer4_total = new TransactionTotalsAssertions($customer4, $this);

    $director_total->add(Transaction::REASON_VL_BONUS, 1);
    $director_total->add(Transaction::REASON_OL_BONUS, 1);

    $prom1_total->add(Transaction::REASON_PM_BONUS, 1);

    $customer1_total->add(Transaction::REASON_ADVERTISED_LVL2, 1);

    $ceo_total->add(Transaction::REASON_SYLVHEIM, 1);
    $ceo_total->add(Transaction::REASON_CEO1_BONUS, 1);

    $director_total->assertTotals();
    $ceo_total->assertTotals();
    $prom1_total->assertTotals();
    $customer1_total->assertTotals();
    $customer4_total->assertTotals();


    //
    // customer 4 wirbt 2 customer
    //
    DbEntityHelper::createSignupMember($customer4);
    DbEntityHelper::createSignupMember($customer4);

    $ceo_total->add(Transaction::REASON_SYLVHEIM, 2);
    $ceo_total->add(Transaction::REASON_CEO1_BONUS, 2);

    $director_total->add(Transaction::REASON_VL_BONUS, 2);
    $director_total->add(Transaction::REASON_OL_BONUS, 2);

    $prom1_total->add(Transaction::REASON_PM_BONUS, 2);

    $customer4_total->add(Transaction::REASON_ADVERTISED_LVL1, 2);
    $customer1_total->add(Transaction::REASON_ADVERTISED_INDIRECT, 2);

    $director_total->assertTotals();
    $ceo_total->assertTotals();
    $prom1_total->assertTotals();
    $customer1_total->assertTotals();
    $customer4_total->assertTotals();

  }
}