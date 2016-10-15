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

    $MYSELF = DbEntityHelper::createSignupMember($promoter1);
    $MYSELF_total = new TransactionTotalsAssertions($MYSELF, $this);
    $this->assertEquals($MYSELF->getFundsLevel(), Member::FUNDS_LEVEL1);

    /* Advertise 2 users
    ---------------------------------------------*/
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_LVL1, 2);
    $franz = DbEntityHelper::createSignupMember($MYSELF);
    $bea = DbEntityHelper::createSignupMember($MYSELF);

    /* Advertise 1 more user - chris
    ---------------------------------------------*/
    // i do not get this because he wont pay
    // $MYSELF_total += Transaction::AMOUNT_ADVERTISED_LVL2;
    $WILL_NOT_PAY = DbEntityHelper::createSignupMember($MYSELF, false);
    $WILL_NOT_PAY_total = new TransactionTotalsAssertions($WILL_NOT_PAY, $this);

    // myself would get 30euro for these 2 advertisings but
    // since $WILL_NOT_PAY will not pay, he gets deleted and those two will
    // be my new advertisings for which i will get 20euro each
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_LVL2, 2);
    $WILL_NOT_PAY__1 = DbEntityHelper::createSignupMember($WILL_NOT_PAY);
    $WILL_NOT_PAY__2 = DbEntityHelper::createSignupMember($WILL_NOT_PAY);

    // myself would get 0euro but through deleting get 15 for this one
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    DbEntityHelper::createSignupMember($WILL_NOT_PAY__2);

    // myself would get 0euro for this but get 20 cause $WILL_NOT_PAY will not pay.
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_LVL2);
    $WILL_NOT_PAY__3 = DbEntityHelper::createSignupMember($WILL_NOT_PAY, false);

    $WILL_NOT_PAY->deleteAndUpdateTree(self::$propelCon);

    DbEntityHelper::fireReceivedMemberFee($WILL_NOT_PAY__3, time());

    // ---- assert - ME
    $MYSELF_total->assertTotals();
  }

  public function testReceivingChildrenOfDeletedFirstMemberWithDeepTree() {
    // NOTE: This tests what happen if my first advertising gets
    // deleted due to not paying fee.

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);
    $promoter1 = DbEntityHelper::createMember();

    $MYSELF = DbEntityHelper::createSignupMember($promoter1);
    $MYSELF_total = new TransactionTotalsAssertions($MYSELF, $this);
    $this->assertEquals($MYSELF->getFundsLevel(), Member::FUNDS_LEVEL1);

    /* Advertise 2 users
    ---------------------------------------------*/
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_LVL1);
    $franz = DbEntityHelper::createSignupMember($MYSELF);
    $WILL_NOT_PAY = DbEntityHelper::createSignupMember($MYSELF, false);

    /* WILL_NOT_PAY advertises 3 and these does either advertise a view
    ---------------------------------------------*/
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_LVL1);
    $MYSELF_total->add(Transaction::REASON_ADVERTISED_LVL2, 4);
    DbEntityHelper::createSignupMember($WILL_NOT_PAY);
    DbEntityHelper::createSignupMember($WILL_NOT_PAY);
    DbEntityHelper::createSignupMember($WILL_NOT_PAY);
    $deep1 = DbEntityHelper::createSignupMember($WILL_NOT_PAY);
    $deep1_lazy = DbEntityHelper::createSignupMember($WILL_NOT_PAY, false);

    $MYSELF_total->add(Transaction::REASON_ADVERTISED_INDIRECT, 2);
    $deep2 = DbEntityHelper::createSignupMember($deep1);
    DbEntityHelper::createSignupMember($deep1);
    DbEntityHelper::createSignupMember($deep1);
    DbEntityHelper::createSignupMember($deep1);
    $deep2_lazy = DbEntityHelper::createSignupMember($deep1, false);

    $MYSELF_total->add(Transaction::REASON_ADVERTISED_INDIRECT, 2);
    $deep3 = DbEntityHelper::createSignupMember($deep2, false);
    DbEntityHelper::createSignupMember($deep2);

    $WILL_NOT_PAY->deleteAndUpdateTree(self::$propelCon);

    DbEntityHelper::fireReceivedMemberFee($deep1_lazy, time());
    DbEntityHelper::fireReceivedMemberFee($deep2_lazy, time());
    DbEntityHelper::fireReceivedMemberFee($deep3, time());

    // ---- assert - ME
    $MYSELF_total->assertTotals();
  }

}