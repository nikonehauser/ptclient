<?php

class DeleteUnpaidTest extends Tbmt_Tests_DatabaseTestCase {

  static public function setUpBeforeClass() {
    $con = Propel::getConnection();
    DbEntityHelper::truncateDatabase($con);

  }

  public function testReceivingChildrenOfDeletedThirdMember() {
    // NOTE: This tests what happen if one of my third+ advertisings gets
    // deleted due to not paiing fee.

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);
    $promoter1 = DbEntityHelper::createMember();

    $MYSELF_total = 0;
    $MYSELF = DbEntityHelper::createSignupMember($promoter1);
    $MYSELF_transfer = $MYSELF->getCurrentTransferBundle(self::$propelCon);
    $this->assertEquals($MYSELF->getFundsLevel(), Member::FUNDS_LEVEL1);

    /* Advertise 2 users
    ---------------------------------------------*/
    $MYSELF_total += (2 * Transaction::AMOUNT_ADVERTISED_LVL1);
    $franz = DbEntityHelper::createSignupMember($MYSELF);
    $bea = DbEntityHelper::createSignupMember($MYSELF);

    /* Advertise 1 more user - chris
    ---------------------------------------------*/
    // i do not get this because he wont pay
    // $MYSELF_total += Transaction::AMOUNT_ADVERTISED_LVL2;
    $WILL_NOT_PAY = DbEntityHelper::createSignupMember($MYSELF, false);

    // myself would get 30euro for these 2 advertisings but
    // since $WILL_NOT_PAY will not pay, he gets deleted and those two will
    // be my new advertisings for which i will get 20euro each
    $MYSELF_total += (2 * Transaction::AMOUNT_ADVERTISED_LVL2);
    $WILL_NOT_PAY__1 = DbEntityHelper::createSignupMember($WILL_NOT_PAY);
    $WILL_NOT_PAY__2 = DbEntityHelper::createSignupMember($WILL_NOT_PAY);

    // myself would get 0euro for this but get 20 cause $WILL_NOT_PAY will not pay.
    $MYSELF_total += Transaction::AMOUNT_ADVERTISED_LVL2;
    $WILL_NOT_PAY__3 = DbEntityHelper::createSignupMember($WILL_NOT_PAY, false);

    $WILL_NOT_PAY->deleteAndUpdateTree(self::$propelCon);

    $WILL_NOT_PAY__3->onReceivedMemberFee(time(), self::$propelCon);

    // ---- assert - ME
    $MYSELF_transfer->reload(self::$propelCon);
    $MYSELF->reload(self::$propelCon);
    $this->assertEquals($MYSELF_total, $MYSELF_transfer->getAmount());
    $this->assertEquals($MYSELF_total, $MYSELF->getOutstandingTotal());
  }

  public function __testReceivingChildrenOfDeletedFirstMember() {
    // NOTE: This tests what happen if my first advertising gets
    // deleted due to not paiing fee.
  }

}