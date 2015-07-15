<?php

class SnowballTest extends Tbmt_Tests_DatabaseTestCase {

  static public function setUpBeforeClass() {
    $con = Propel::getConnection();
    DbEntityHelper::truncateDatabase($con);

  }

  public function testSnowballModel() {
    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);
    $promoter1 = DbEntityHelper::createMember();

    $MYSELF = DbEntityHelper::createSignupMember($promoter1);
    $this->assertEquals($MYSELF->getFundsLevel(), Member::FUNDS_LEVEL1);

    /* Advertise 2 users
    ---------------------------------------------*/
    $far_l1_1 = DbEntityHelper::createSignupMember($MYSELF);
    $bea_l1_2 = DbEntityHelper::createSignupMember($MYSELF);

    $this->assertEquals($MYSELF->getFundsLevel(), Member::FUNDS_LEVEL2);
    $MYSELF_transfer = $MYSELF->getCurrentTransferBundle(self::$propelCon);
    $this->assertEquals($MYSELF_transfer->getAmount(), 10);


    /* Advertise 1 more user - chris
    ---------------------------------------------*/
    $CHRIS_l1_3 = DbEntityHelper::createSignupMember($MYSELF);

    // ---- assert - ME
    $MYSELF_transfer->reload(self::$propelCon);
    $this->assertEquals($MYSELF_transfer->getAmount(), 30);


    /* chris advertise 1 user - dean
    ---------------------------------------------*/
    $DEAN_l2_1 = DbEntityHelper::createSignupMember($CHRIS_l1_3);

    // ---- assert - CHRIS
    $CHRIS_transfer = $CHRIS_l1_3->getCurrentTransferBundle(self::$propelCon);
    $this->assertEquals($CHRIS_transfer->getAmount(), 5);

    // ---- assert - ME
    $MYSELF_transfer->reload(self::$propelCon);
    $this->assertEquals($MYSELF_transfer->getAmount(), 45);


    /* chris advertise 1 user - emi
    ---------------------------------------------*/
    $EMI_l2_2 = DbEntityHelper::createSignupMember($CHRIS_l1_3);

    // ---- assert - CHRIS
    $CHRIS_transfer->reload(self::$propelCon);
    $this->assertEquals($CHRIS_transfer->getAmount(), 10);
    $this->assertEquals($CHRIS_l1_3->getFundsLevel(), Member::FUNDS_LEVEL2);

    // ---- assert - ME
    $MYSELF_transfer->reload(self::$propelCon);
    $this->assertEquals($MYSELF_transfer->getAmount(), 60);

    /* dean advertise 1 user - franz
    ---------------------------------------------*/
    $FRANZ_l3_1 = DbEntityHelper::createSignupMember($DEAN_l2_1);

    // ---- assert - CHRIS
    $CHRIS_transfer->reload(self::$propelCon);
    $this->assertEquals($CHRIS_transfer->getAmount(), 10);

    // ---- assert - ME
    $MYSELF_transfer->reload(self::$propelCon);
    $this->assertEquals($MYSELF_transfer->getAmount(), 75);


    DbEntityHelper::createSignupMember($DEAN_l2_1);
    DbEntityHelper::createSignupMember($EMI_l2_2);
    DbEntityHelper::createSignupMember($EMI_l2_2);


    $ALF_l2_3 = DbEntityHelper::createSignupMember($CHRIS_l1_3);
    $GUST_l3_1 = DbEntityHelper::createSignupMember($ALF_l2_3);
    DbEntityHelper::createSignupMember($ALF_l2_3);


    // ---- assert - CHRIS
    $CHRIS_transfer->reload(self::$propelCon);
    $this->assertEquals($CHRIS_transfer->getAmount(), 60);

    // ---- assert - ME
    $MYSELF_transfer->reload(self::$propelCon);
    $this->assertEquals($MYSELF_transfer->getAmount(), 120);


    DbEntityHelper::createSignupMember($GUST_l3_1);
    DbEntityHelper::createSignupMember($GUST_l3_1);


    // ---- assert - CHRIS
    $CHRIS_transfer->reload(self::$propelCon);
    $this->assertEquals($CHRIS_transfer->getAmount(), 90);
    $this->assertEquals($CHRIS_l1_3->getOutstandingTotal(), 90);


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
    $MYSELF_transfer->reload(self::$propelCon);
    $MYSELF->reload(self::$propelCon);
    $this->assertEquals($MYSELF_transfer->getAmount(), 120);
    $this->assertEquals($MYSELF->getOutstandingTotal(), 120);
  }



  public function testSnowballModelLazyIncommingFee() {
    /* Setup
    ---------------------------------------------*/
    $now = time();

    DbEntityHelper::setCon(self::$propelCon);
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
    $mart_l1_1->onReceivedMemberFee($now, self::$propelCon);
    $bea_l1_2->onReceivedMemberFee($now, self::$propelCon);
    // The order does matter in this case! If franz is the thrid one we receive
    // his indirect advertisings,
    $franz_l1_3->onReceivedMemberFee($now, self::$propelCon);
    $this->assertEquals(ReservedPaidEventQuery::create()->count(), 4);

    // If my member fee is incomming now this should trigger all current
    // existing events
    $MYSELF->onReceivedMemberFee($now, self::$propelCon);
    $this->assertEquals(ReservedPaidEventQuery::create()->count(), 0);

    // ---- assert - MYSELF - get
    // lvl1 - mart - 5
    // lvl1 - bea  - 5
    // lvl2 - franz - 20
    // lvl2 - indirect franz - 15
    $MYSELF_transfer = $MYSELF->getCurrentTransferBundle(self::$propelCon);
    $this->assertEquals($MYSELF_transfer->getAmount(), 45);
  }



  public function testSnowballModelLazyIncommingFeeExtensiv() {
    // NOTE: this is basically the exact same scenario as @see $this->testSnowballModel
    // with the only difference that the incomming of the member fee
    // happens lazy and in different order. Later advertised members does
    // pay earlier so that ReservedPaidEvents get created and triggered.


    /* Setup
    ---------------------------------------------*/
    $now = time();

    DbEntityHelper::setCon(self::$propelCon);
    $promoter1 = DbEntityHelper::createMember();

    $MYSELF_total = 0;
    $MYSELF = DbEntityHelper::createSignupMember($promoter1);
    $this->assertEquals($MYSELF->getFundsLevel(), Member::FUNDS_LEVEL1);
    $MYSELF_transfer = $MYSELF->getCurrentTransferBundle(self::$propelCon);

    /* Advertise 2 users
    ---------------------------------------------*/
    $MYSELF_total += (2 * Transaction::AMOUNT_ADVERTISED_LVL1);
    $far_l1_1 = DbEntityHelper::createSignupMember($MYSELF, false);
    $bea_l1_2 = DbEntityHelper::createSignupMember($MYSELF, false);


    /* Advertise 1 more user - chris
    ---------------------------------------------*/
    $MYSELF_total += Transaction::AMOUNT_ADVERTISED_LVL2;
    $CHRIS_l1_3 = DbEntityHelper::createSignupMember($MYSELF, false);


    /* chris advertise 1 user - dean
    ---------------------------------------------*/
    $MYSELF_total += Transaction::AMOUNT_ADVERTISED_INDIRECT;
    $DEAN_l2_1 = DbEntityHelper::createSignupMember($CHRIS_l1_3, false);

    /* chris advertise 1 user - emi
    ---------------------------------------------*/
    $MYSELF_total += Transaction::AMOUNT_ADVERTISED_INDIRECT;
    $EMI_l2_2 = DbEntityHelper::createSignupMember($CHRIS_l1_3, false);

    /* dean advertise 1 user - franz
    ---------------------------------------------*/
    $MYSELF_total += Transaction::AMOUNT_ADVERTISED_INDIRECT;
    $FRANZ_l3_1 = DbEntityHelper::createSignupMember($DEAN_l2_1, false);

    $MYSELF_total += (3 * Transaction::AMOUNT_ADVERTISED_INDIRECT);
    $anonym1 = DbEntityHelper::createSignupMember($DEAN_l2_1, false);
    $anonym2 = DbEntityHelper::createSignupMember($EMI_l2_2, false);
    $anonym3 = DbEntityHelper::createSignupMember($EMI_l2_2, false);


    $ALF_l2_3 = DbEntityHelper::createSignupMember($CHRIS_l1_3, false);
    $GUST_l3_1 = DbEntityHelper::createSignupMember($ALF_l2_3, false);
    $anonym4 = DbEntityHelper::createSignupMember($ALF_l2_3, false);
    $anonym5 = DbEntityHelper::createSignupMember($GUST_l3_1, false);
    $anonym6 = DbEntityHelper::createSignupMember($GUST_l3_1, false);

    /* fee incomming in different order for all users
    ---------------------------------------------*/
    $anonym6->onReceivedMemberFee($now, self::$propelCon);
    $anonym5->onReceivedMemberFee($now, self::$propelCon);
    $anonym4->onReceivedMemberFee($now, self::$propelCon);
    $GUST_l3_1->onReceivedMemberFee($now, self::$propelCon);
    $anonym3->onReceivedMemberFee($now, self::$propelCon);
    $anonym2->onReceivedMemberFee($now, self::$propelCon);
    $anonym1->onReceivedMemberFee($now, self::$propelCon);
    $FRANZ_l3_1->onReceivedMemberFee($now, self::$propelCon);
    // the following order does matter. ALF has to be the third so that
    // MYSELF does not receive anynthing from his advertisings
    $DEAN_l2_1->onReceivedMemberFee($now, self::$propelCon); // chris first
    $EMI_l2_2->onReceivedMemberFee($now, self::$propelCon); // chris second
    $ALF_l2_3->onReceivedMemberFee($now, self::$propelCon); // chris third
    $bea_l1_2->onReceivedMemberFee($now, self::$propelCon);
    $far_l1_1->onReceivedMemberFee($now, self::$propelCon);

    // ---- assert - correct reserved paid events count
    $this->assertEquals(ReservedPaidEventQuery::create()->count(), 11);

    // ---- assert - ME got 10 since chris has not paid yet
    $MYSELF_transfer->reload(self::$propelCon);
    $this->assertEquals($MYSELF_transfer->getAmount(), 10);

    // ---- assert - chris pais now as least. this trigger all remaining
    // reserved paid events
    $CHRIS_l1_3->onReceivedMemberFee($now, self::$propelCon);
    $this->assertEquals($MYSELF->getFundsLevel(), Member::FUNDS_LEVEL2);
    $this->assertEquals(ReservedPaidEventQuery::create()->count(), 0);

    // ---- assert - CHRIS
    $CHRIS_transfer = $CHRIS_l1_3->getCurrentTransferBundle(self::$propelCon);
    $CHRIS_l1_3->reload(self::$propelCon);
    $this->assertEquals($CHRIS_transfer->getAmount(), 90);
    $this->assertEquals($CHRIS_l1_3->getOutstandingTotal(), 90);

    // ---- assert - ME
    $MYSELF_transfer->reload(self::$propelCon);
    $MYSELF->reload(self::$propelCon);
    $this->assertEquals($MYSELF_transfer->getAmount(), $MYSELF_total);
    $this->assertEquals($MYSELF->getOutstandingTotal(), $MYSELF_total);

  }

}