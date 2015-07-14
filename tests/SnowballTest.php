<?php

class SnowballTest extends Tbmt_Tests_DatabaseTestCase {

  static public function setUpBeforeClass() {
    $con = Propel::getConnection();
    DbEntityHelper::truncateDatabase($con);

  }

  public function __testSnowballModel() {
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

    // ---- assert - ME
    $MYSELF_transfer->reload(self::$propelCon);
    $this->assertEquals($MYSELF_transfer->getAmount(), 120);
    $this->assertEquals($MYSELF->getOutstandingTotal(), 120);

    /* if chris advertised more than 2 user i dont anything from the 3th on
    ---------------------------------------------*/
    DbEntityHelper::createSignupMember($CHRIS_l1_3);
    DbEntityHelper::createSignupMember($CHRIS_l1_3);
    DbEntityHelper::createSignupMember($CHRIS_l1_3);
    // ---- assert - ME
    $MYSELF_transfer->reload(self::$propelCon);
    $this->assertEquals($MYSELF_transfer->getAmount(), 120);

// $sql = "SELECT sum(amount) FROM tbmt_transfer";
// $stmt = self::$propelCon->prepare($sql);
// $stmt->execute();
//     print_r($stmt->fetch());
  }



  public function testSnowballModelLazyIncommingFee1() {
    /* Setup
    ---------------------------------------------*/
    $now = time();

    DbEntityHelper::setCon(self::$propelCon);
    $promoter1 = DbEntityHelper::createMember();

    $MYSELF = DbEntityHelper::createSignupMember($promoter1, false);
    $this->assertEquals($MYSELF->getFundsLevel(), Member::FUNDS_LEVEL1);

    /* Advertise 2 users
    ---------------------------------------------*/
    $far_l1_1 = DbEntityHelper::createSignupMember($MYSELF, false);
    $bea_l1_2 = DbEntityHelper::createSignupMember($MYSELF, false);

    DbEntityHelper::createSignupMember($bea_l1_2);
    $far_l1_1->onReceivedMemberFee($now, self::$propelCon);
    $bea_l1_2->onReceivedMemberFee($now, self::$propelCon);

    $MYSELF->onReceivedMemberFee($now, self::$propelCon);

    // ---- assert - ME
    $MYSELF_transfer = $MYSELF->getCurrentTransferBundle(self::$propelCon);
    $this->assertEquals($MYSELF_transfer->getAmount(), 25);
  }



  public function ___testSnowballModelLazyIncommingFee2() {
    /* Setup
    ---------------------------------------------*/
    $now = time();

    DbEntityHelper::setCon(self::$propelCon);
    $promoter1 = DbEntityHelper::createMember();

    $MYSELF = DbEntityHelper::createSignupMember($promoter1);
    $this->assertEquals($MYSELF->getFundsLevel(), Member::FUNDS_LEVEL1);

    /* Advertise 2 users
    ---------------------------------------------*/
    $far_l1_1 = DbEntityHelper::createSignupMember($MYSELF, false);
    $bea_l1_2 = DbEntityHelper::createSignupMember($MYSELF, false);


    /* Advertise 1 more user - chris
    ---------------------------------------------*/
    $CHRIS_l1_3 = DbEntityHelper::createSignupMember($MYSELF, false);


    /* chris advertise 1 user - dean
    ---------------------------------------------*/
    $DEAN_l2_1 = DbEntityHelper::createSignupMember($CHRIS_l1_3, false);

    /* chris advertise 1 user - emi
    ---------------------------------------------*/
    $EMI_l2_2 = DbEntityHelper::createSignupMember($CHRIS_l1_3, false);

    /* dean advertise 1 user - franz
    ---------------------------------------------*/
    $FRANZ_l3_1 = DbEntityHelper::createSignupMember($DEAN_l2_1, false);

    $anonym1 = DbEntityHelper::createSignupMember($DEAN_l2_1, false);
    $anonym2 = DbEntityHelper::createSignupMember($EMI_l2_2, false);
    $anonym3 = DbEntityHelper::createSignupMember($EMI_l2_2, false);


    $ALF_l2_3 = DbEntityHelper::createSignupMember($CHRIS_l1_3, false);
    $GUST_l3_1 = DbEntityHelper::createSignupMember($ALF_l2_3, false);
    $anonym4 = DbEntityHelper::createSignupMember($ALF_l2_3, false);
    $anonym5 = DbEntityHelper::createSignupMember($GUST_l3_1, false);
    $anonym6 = DbEntityHelper::createSignupMember($GUST_l3_1, false);


    /* fee incomming in different order
    ---------------------------------------------*/
    $anonym6->onReceivedMemberFee($now, self::$propelCon);
    $anonym5->onReceivedMemberFee($now, self::$propelCon);
    $anonym4->onReceivedMemberFee($now, self::$propelCon);
    $GUST_l3_1->onReceivedMemberFee($now, self::$propelCon);
    $ALF_l2_3->onReceivedMemberFee($now, self::$propelCon);
    $anonym3->onReceivedMemberFee($now, self::$propelCon);
    $anonym2->onReceivedMemberFee($now, self::$propelCon);
    $anonym1->onReceivedMemberFee($now, self::$propelCon);
    $FRANZ_l3_1->onReceivedMemberFee($now, self::$propelCon);
    $EMI_l2_2->onReceivedMemberFee($now, self::$propelCon);
    $DEAN_l2_1->onReceivedMemberFee($now, self::$propelCon);
    $CHRIS_l1_3->onReceivedMemberFee($now, self::$propelCon);
    $bea_l1_2->onReceivedMemberFee($now, self::$propelCon);
    $far_l1_1->onReceivedMemberFee($now, self::$propelCon);

    // TODO: Undetermined how this should look like

    $this->assertEquals(ReservedPaidEventQuery::create()->count(), 0);

    // ---- assert - CHRIS
    $CHRIS_transfer = $CHRIS_l1_3->getCurrentTransferBundle(self::$propelCon);
    $this->assertEquals($CHRIS_transfer->getAmount(), 90);

    // ---- assert - ME
    $MYSELF_transfer = $MYSELF->getCurrentTransferBundle(self::$propelCon);
    $this->assertEquals($MYSELF_transfer->getAmount(), 120);

  }

}