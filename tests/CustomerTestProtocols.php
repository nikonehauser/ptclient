<?php

class CustomerTestProtocolsTest extends Tbmt_Tests_DatabaseTestCase {

  // static public function setUpBeforeClass() {
  //   $con = Propel::getConnection();
  //   DbEntityHelper::truncateDatabase($con);

  // }

  public function test_105_director_marketingleader_promoter() {
    DbEntityHelper::setCon(self::$propelCon);

    $sylvheim = Member::getByNum(\SystemStats::ACCOUNT_SYLVHEIM);
    $sylvheim_total = new TransactionTotalsAssertions($sylvheim, $this);
    $this->assertEquals(Member::FUNDS_LEVEL2, $sylvheim->getFundsLevel());

    /* advertise director
    ---------------------------------------------*/
    $director = DbEntityHelper::createMemberWithInvitation(
      $sylvheim,
      Member::TYPE_MARKETINGLEADER
    );
    $director_total = new TransactionTotalsAssertions($director, $this);
    $this->assertEquals(0, $director->getAdvertisedCount());

    $sylvheim_total->add(Transaction::REASON_ADVERTISED_LVL2);
    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);
    $sylvheim_total->add(Transaction::REASON_VL_BONUS);
    $sylvheim_total->add(Transaction::REASON_OL_BONUS);
    $sylvheim_total->add(Transaction::REASON_PM_BONUS);

    $sylvheim_total->assertTotals();

    /* director advertise orgleader
    ---------------------------------------------*/
    $orgleader = DbEntityHelper::createMemberWithInvitation(
      $director,
      Member::TYPE_ORGLEADER
    );
    $orgleader_total = new TransactionTotalsAssertions($orgleader, $this);

    $sylvheim_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $director_total->add(Transaction::REASON_ADVERTISED_LVL1);
    $director_total->add(Transaction::REASON_VL_BONUS);
    $director_total->add(Transaction::REASON_OL_BONUS);
    $director_total->add(Transaction::REASON_PM_BONUS);

    // $orgleader wird sylvheim nachgestellt
    $this->assertEquals($orgleader->getParentId(), $sylvheim->getId());
    $sylvheim_total->assertTotals();
    $director_total->assertTotals();

    $this->assertEquals(1, $director->getAdvertisedCount());
    $this->assertEquals(Member::FUNDS_LEVEL1, $director->getFundsLevel());

    /* orgleader advertise promoter
    ---------------------------------------------*/
    $promoter = DbEntityHelper::createMemberWithInvitation(
      $orgleader,
      Member::TYPE_PROMOTER
    );
    $promoter_total = new TransactionTotalsAssertions($promoter, $this);

    $this->assertEquals(1, $director->getAdvertisedCount());
    $this->assertEquals(Member::FUNDS_LEVEL1, $director->getFundsLevel());

    $sylvheim_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $director_total->add(Transaction::REASON_VL_BONUS);

    $orgleader_total->add(Transaction::REASON_ADVERTISED_LVL1);
    $orgleader_total->add(Transaction::REASON_OL_BONUS);
    $orgleader_total->add(Transaction::REASON_PM_BONUS);

    // $promoter wird sylvheim nachgestellt
    $this->assertEquals($promoter->getParentId(), $sylvheim->getId());
    $sylvheim_total->assertTotals();
    $director_total->assertTotals();
    $orgleader_total->assertTotals();

    /* promoter advertise member
    ---------------------------------------------*/
    $donator = DbEntityHelper::createSignupMember($promoter);

    $sylvheim_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $director_total->add(Transaction::REASON_VL_BONUS);

    $orgleader_total->add(Transaction::REASON_OL_BONUS);

    $promoter_total->add(Transaction::REASON_ADVERTISED_LVL1);
    $promoter_total->add(Transaction::REASON_PM_BONUS);

    $this->assertEquals($donator->getParentId(), $sylvheim->getId());

    $sylvheim_total->assertTotals();
    $director_total->assertTotals();
    $orgleader_total->assertTotals();
    $promoter_total->assertTotals();
  }

}