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

    // ---- assert - ME
    $MYSELF_transfer->reload(self::$propelCon);
    $this->assertEquals($MYSELF_transfer->getAmount(), 120);
  }

}