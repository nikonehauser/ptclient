<?php

class SnowballTest extends Tbmt_Tests_DatabaseTestCase {

  public function testSnowballModel() {
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

    $this->assertEquals($MYSELF->getFundsLevel(), Member::FUNDS_LEVEL2);
    $MYSELF_total = new TransactionTotalsAssertions($MYSELF, $this);
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_LVL1, 2);
    $MYSELF_total->assertTotals();


    /* Advertise 1 more user - chris
    ---------------------------------------------*/
    $CHRIS_l1_3 = DbEntityHelper::createSignupMember($MYSELF);

    // ---- assert - ME
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_LVL2);
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
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $MYSELF_total->assertTotals();


    /* chris advertise 1 user - emi
    ---------------------------------------------*/
    $EMI_l2_2 = DbEntityHelper::createSignupMember($CHRIS_l1_3);

    // ---- assert - CHRIS
    $CHRIS_total->add(Transaction::REASON_ADVERTISED_LVL1);
    $CHRIS_total->assertTotals();
    $this->assertEquals($CHRIS_l1_3->getFundsLevel(), Member::FUNDS_LEVEL2);

    // ---- assert - ME
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $MYSELF_total->assertTotals();

    /* dean advertise 1 user - franz
    ---------------------------------------------*/
    $FRANZ_l3_1 = DbEntityHelper::createSignupMember($DEAN_l2_1);

    // ---- assert - CHRIS - remain at 10 euro
    $CHRIS_total->assertTotals();

    // ---- assert - ME
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $MYSELF_total->assertTotals();


    DbEntityHelper::createSignupMember($DEAN_l2_1);
    DbEntityHelper::createSignupMember($EMI_l2_2);
    DbEntityHelper::createSignupMember($EMI_l2_2);


    $CHRIS_total->add(Transaction::REASON_ADVERTISED_LVL2);
    $CHRIS_total->add(Transaction::REASON_ADVERTISED_INDIRECT, 2);
    $ALF_l2_3 = DbEntityHelper::createSignupMember($CHRIS_l1_3);
    $GUST_l3_1 = DbEntityHelper::createSignupMember($ALF_l2_3);
    DbEntityHelper::createSignupMember($ALF_l2_3);


    // ---- assert - CHRIS - at 60 euro
    $CHRIS_total->assertTotals();

    // ---- assert - ME
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_INDIRECT, 3);
    $MYSELF_total->assertTotals();


    $CHRIS_total->add(Transaction::REASON_ADVERTISED_INDIRECT, 2);
    DbEntityHelper::createSignupMember($GUST_l3_1);
    DbEntityHelper::createSignupMember($GUST_l3_1);


    // ---- assert - CHRIS - at 90 euro
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



  public function testSnowballModelLazyIncommingFee() {
    /* Setup
    ---------------------------------------------*/
    $now = time();

    $promoter1 = DbEntityHelper::createMember();

    // i am get advertised and remaining in state unpaid
    $MYSELF = DbEntityHelper::createSignupMember($promoter1, false);
    $this->assertEquals($MYSELF->getFundsLevel(), Member::FUNDS_LEVEL1);

    /* MYSELF Advertise 3 users
    ---------------------------------------------*/
    $mart_l1_1 = DbEntityHelper::createSignupMember($MYSELF, false);
    $bea_l1_2 = DbEntityHelper::createSignupMember($MYSELF, false);
    $franz_l1_3 = DbEntityHelper::createSignupMember($MYSELF, false);

    /* FRANZ Advertise 1 user - which pay immediately
     * This should create a ReservedPaidEvent.
    ---------------------------------------------*/
    DbEntityHelper::createSignupMember($franz_l1_3);
    $this->assertEquals(ReservedPaidEventQuery::create()->count(), 1);


    /* This also creates a ReservedPaidEvent since had not paid yet
     * This will create more ReservedPaidEvent's.
    ---------------------------------------------*/
    DbEntityHelper::fireReceivedMemberFee($mart_l1_1, $now);
    DbEntityHelper::fireReceivedMemberFee($bea_l1_2, $now);
    // The order does matter in this case! If franz is the thrid one we receive
    // his indirect advertisings,
    DbEntityHelper::fireReceivedMemberFee($franz_l1_3, $now);
    $this->assertEquals(ReservedPaidEventQuery::create()->count(), 4);

    // If my member fee is incomming now this should trigger all current
    // existing events
    DbEntityHelper::fireReceivedMemberFee($MYSELF, $now);
    $this->assertEquals(ReservedPaidEventQuery::create()->count(), 0);

    // ---- assert - MYSELF - get
    // lvl1 - mart - 5
    // lvl1 - bea  - 5
    // lvl2 - franz - 20
    // lvl2 - indirect franz - 15
    $MYSELF_total = new TransactionTotalsAssertions($MYSELF, $this);
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_LVL1, 2);
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_LVL2);
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $MYSELF_total->assertTotals();
  }



  public function testSnowballModelLazyIncommingFeeExtensiv() {
    // NOTE: this is basically the exact same scenario as @see $this->testSnowballModel
    // with the only difference that the incomming of the member fee
    // happens lazy and in different order. Later advertised members does
    // pay earlier so that ReservedPaidEvents get created and triggered.


    /* Setup
    ---------------------------------------------*/
    $now = time();

    $promoter1 = DbEntityHelper::createMember();

    $MYSELF = DbEntityHelper::createSignupMember($promoter1);
    $MYSELF_total = new TransactionTotalsAssertions($MYSELF, $this);
    $this->assertEquals($MYSELF->getFundsLevel(), Member::FUNDS_LEVEL1);

    /* Advertise 2 users
    ---------------------------------------------*/
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_LVL1, 2);
    $far_l1_1 = DbEntityHelper::createSignupMember($MYSELF, false);
    $bea_l1_2 = DbEntityHelper::createSignupMember($MYSELF, false);


    /* Advertise 1 more user - chris
    ---------------------------------------------*/
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_LVL2);
    $CHRIS_l1_3 = DbEntityHelper::createSignupMember($MYSELF, false);
    $CHRIS_total = new TransactionTotalsAssertions($CHRIS_l1_3, $this);

    /* chris advertise 1 user - dean
    ---------------------------------------------*/
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $CHRIS_total->add(Transaction::REASON_ADVERTISED_LVL1);
    $DEAN_l2_1 = DbEntityHelper::createSignupMember($CHRIS_l1_3, false);

    /* chris advertise 1 user - emi
    ---------------------------------------------*/
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $CHRIS_total->add(Transaction::REASON_ADVERTISED_LVL1);
    $EMI_l2_2 = DbEntityHelper::createSignupMember($CHRIS_l1_3, false);

    /* dean advertise 1 user - franz
    ---------------------------------------------*/
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $FRANZ_l3_1 = DbEntityHelper::createSignupMember($DEAN_l2_1, false);

    $MYSELF_total->add(Transaction::REASON_ADVERTISED_INDIRECT, 3);
    $anonym1 = DbEntityHelper::createSignupMember($DEAN_l2_1, false);
    $anonym2 = DbEntityHelper::createSignupMember($EMI_l2_2, false);
    $anonym3 = DbEntityHelper::createSignupMember($EMI_l2_2, false);


    $CHRIS_total->add(Transaction::REASON_ADVERTISED_LVL2);
    $CHRIS_total->add(Transaction::REASON_ADVERTISED_INDIRECT, 4);
    $ALF_l2_3 = DbEntityHelper::createSignupMember($CHRIS_l1_3, false);
    $GUST_l3_1 = DbEntityHelper::createSignupMember($ALF_l2_3, false);
    $anonym4 = DbEntityHelper::createSignupMember($ALF_l2_3, false);
    $anonym5 = DbEntityHelper::createSignupMember($GUST_l3_1, false);
    $anonym6 = DbEntityHelper::createSignupMember($GUST_l3_1, false);

    /* fee incomming in different order for all users
    ---------------------------------------------*/
    DbEntityHelper::fireReceivedMemberFee($anonym6, $now);
    DbEntityHelper::fireReceivedMemberFee($anonym5, $now);
    DbEntityHelper::fireReceivedMemberFee($anonym4, $now);
    DbEntityHelper::fireReceivedMemberFee($GUST_l3_1, $now);
    DbEntityHelper::fireReceivedMemberFee($anonym3, $now);
    DbEntityHelper::fireReceivedMemberFee($anonym2, $now);
    DbEntityHelper::fireReceivedMemberFee($anonym1, $now);
    DbEntityHelper::fireReceivedMemberFee($FRANZ_l3_1, $now);
    // the following order does matter. ALF has to be the third so that
    // MYSELF does not receive anynthing from his advertisings
    DbEntityHelper::fireReceivedMemberFee($DEAN_l2_1, $now);
    DbEntityHelper::fireReceivedMemberFee($EMI_l2_2, $now);
    DbEntityHelper::fireReceivedMemberFee($ALF_l2_3, $now);
    DbEntityHelper::fireReceivedMemberFee($bea_l1_2, $now);
    DbEntityHelper::fireReceivedMemberFee($far_l1_1, $now);

    // ---- assert - correct reserved paid events count
    $this->assertEquals(ReservedPaidEventQuery::create()->count(), 11);

    // ---- assert - ME got 10 since chris has not paid yet
    $MYSELF_total_before = new TransactionTotalsAssertions($MYSELF, $this);
    $MYSELF_total_before->add(Transaction::REASON_ADVERTISED_LVL1, 2);
    $MYSELF_total_before->assertTotals();

    // ---- assert - chris pais now as least. this trigger all remaining
    // reserved paid events
    DbEntityHelper::fireReceivedMemberFee($CHRIS_l1_3, $now);
    $this->assertEquals($MYSELF->getFundsLevel(), Member::FUNDS_LEVEL2);
    $this->assertEquals(ReservedPaidEventQuery::create()->count(), 0);

    // ---- assert - CHRIS - should be 90 euro
    $CHRIS_total->assertTotals();

    // ---- assert - ME
    $MYSELF_total->assertTotals();
  }

}