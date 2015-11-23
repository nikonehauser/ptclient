<?php

class SingleDistributionTest extends Tbmt_Tests_DatabaseTestCase {

  static public function setUpBeforeClass() {
    parent::setUpBeforeClass();

    \Tbmt\DistributionStrategy::resetInstance();
    \Tbmt\Config::set('distribution.strategy', 'Single');
  }

  public function testSingleDistributionModel() {
    /* Setup
    ---------------------------------------------*/
    $systemAccount = SystemStats::getSystemAccount();
    $systemTransfer = new TransactionTotalsAssertions($systemAccount, $this);
    $promoter1 = DbEntityHelper::createMember();

    $MYSELF = DbEntityHelper::createSignupMember($promoter1);
    $this->assertEquals($MYSELF->getFundsLevel(), Member::FUNDS_LEVEL1);

    /* Advertise 2 users
    ---------------------------------------------*/
    $far_l1_1 = DbEntityHelper::createSignupMember($MYSELF);
    $bea_l1_2 = DbEntityHelper::createSignupMember($MYSELF);

    // Funds level never changes
    $this->assertEquals($MYSELF->getFundsLevel(), Member::FUNDS_LEVEL1);
    $MYSELF_total = new TransactionTotalsAssertions($MYSELF, $this);
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_LVL1, 2);
    $MYSELF_total->assertTotals();


    /* Advertise 1 more user - chris
    ---------------------------------------------*/
    $CHRIS_l1_3 = DbEntityHelper::createSignupMember($MYSELF);

    // ---- assert - ME
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_LVL1);
    $MYSELF_total->assertTotals();

    /* chris advertise 1 user - dean
    ---------------------------------------------*/
    $DEAN_l2_1 = DbEntityHelper::createSignupMember($CHRIS_l1_3);

    // ---- assert - CHRIS
    $CHRIS_transfer = DbEntityHelper::getCurrentTransferBundle($CHRIS_l1_3);
    $CHRIS_total = new TransactionTotalsAssertions($CHRIS_l1_3, $this);
    $CHRIS_total->add(Transaction::REASON_ADVERTISED_LVL1);
    $CHRIS_total->assertTotals();

    // ---- assert - ME
    // unchanged
    $MYSELF_total->assertTotals();


    /* chris advertise 1 user - emi
    ---------------------------------------------*/
    $EMI_l2_2 = DbEntityHelper::createSignupMember($CHRIS_l1_3);

    // ---- assert - CHRIS
    $CHRIS_total->add(Transaction::REASON_ADVERTISED_LVL1);
    $CHRIS_total->assertTotals();
    $this->assertEquals($CHRIS_l1_3->getFundsLevel(), Member::FUNDS_LEVEL1);

    // ---- assert - ME
    // unchanged
    $MYSELF_total->assertTotals();

    /* dean advertise 1 user - franz
    ---------------------------------------------*/
    $FRANZ_l3_1 = DbEntityHelper::createSignupMember($DEAN_l2_1);

    // ---- assert - CHRIS - remain at 10 euro
    $CHRIS_total->assertTotals();

    // ---- assert - ME
    // unchanged
    $MYSELF_total->assertTotals();


    DbEntityHelper::createSignupMember($DEAN_l2_1);
    DbEntityHelper::createSignupMember($EMI_l2_2);
    DbEntityHelper::createSignupMember($EMI_l2_2);


    $CHRIS_total->add(Transaction::REASON_ADVERTISED_LVL1);
    $ALF_l2_3 = DbEntityHelper::createSignupMember($CHRIS_l1_3);
    $GUST_l3_1 = DbEntityHelper::createSignupMember($ALF_l2_3);
    DbEntityHelper::createSignupMember($ALF_l2_3);


    // ---- assert - CHRIS - at 60 euro
    $CHRIS_total->assertTotals();

    // ---- assert - ME
    // unchanged
    $MYSELF_total->assertTotals();


    DbEntityHelper::createSignupMember($GUST_l3_1);
    DbEntityHelper::createSignupMember($GUST_l3_1);


    // ---- assert - CHRIS - at 90 euro
    // unchanged
    $CHRIS_total->assertTotals();


    /* if anyone advertised more than 2 user i dont get anything from the third on
    ---------------------------------------------*/
    DbEntityHelper::createSignupMember($CHRIS_l1_3);
    DbEntityHelper::createSignupMember($CHRIS_l1_3);
    DbEntityHelper::createSignupMember($CHRIS_l1_3);
    DbEntityHelper::createSignupMember($GUST_l3_1);
    DbEntityHelper::createSignupMember($GUST_l3_1);
    DbEntityHelper::createSignupMember($GUST_l3_1);
    DbEntityHelper::createSignupMember($ALF_l2_3);
    DbEntityHelper::createSignupMember($ALF_l2_3);
    DbEntityHelper::createSignupMember($ALF_l2_3);
    DbEntityHelper::createSignupMember($EMI_l2_2);
    DbEntityHelper::createSignupMember($EMI_l2_2);


    /* as well, i dont get anything but 5euro from my first advertisings
    ---------------------------------------------*/
    DbEntityHelper::createSignupMember($bea_l1_2);
    DbEntityHelper::createSignupMember($bea_l1_2);
    DbEntityHelper::createSignupMember($bea_l1_2);


    // ---- assert - MYSELF
    $MYSELF_total->assertTotals();
  }

}