<?php

class BonusSpreadingTest extends Tbmt_Tests_DatabaseTestCase {

  static public function setUpBeforeClass() {
    $con = Propel::getConnection();
    DbEntityHelper::truncateDatabase($con);
  }

  public function setUp() {
    parent::setUp();
    DbEntityHelper::resetBonusMembers();
  }

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

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);

    list(
        list($IT, $VL, $OL, $PM, $VS2, $VS1),
        list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers();

    $new = DbEntityHelper::createSignupMember($VS1);

    $trfIT += 1;
    $trfVL += 1;
    $trfOL += 1;
    $trfPM += 1;
    $trfVS2 += 15;
    $trfVS1 += 5;

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);

    // Setting up another tree should NOT change the bonuses for
    // the previous tree except of special types member like it specialist.
    list(,list($newTrfIT)) = DbEntityHelper::setUpBonusMembers(false);

    $this->assertTransferTotal($newTrfIT, $IT);
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

    $trfIT += 1;
    $trfVL += 1;
    $trfOL += 1;
    $trfPM += 1;
    $trfVS2 += 20;

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

    $trfIT += 1;
    $trfVL += 2;
    // $trfOL += 1;
    $trfPM += 1;
    $trfVS2 += 15;
    $trfVS1 += 5;

    $this->assertTransferTotal($trfIT, $IT);
    $this->assertTransferTotal($trfVL, $VL);
    // $this->assertTransferTotal($trfOL, $OL);
    $this->assertTransferTotal($trfPM, $PM);
    $this->assertTransferTotal($trfVS2, $VS2);
    $this->assertTransferTotal($trfVS1, $VS1);
  }

  public function _testBonusGetSpread() {
    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);
    $itSpecialist = DbEntityHelper::createMember();
    $itSpecialist->setType(Member::TYPE_ITSPECIALIST);
    $itSpecialist->save(self::$propelCon);

    $MYSELF_total = 0;
    $MYSELF = DbEntityHelper::createSignupMember($promoter1);
    $MYSELF_transfer = $MYSELF->getCurrentTransferBundle(self::$propelCon);
    $this->assertEquals($MYSELF->getFundsLevel(), Member::FUNDS_LEVEL1);

  }

  private function assertTransferTotal($total, Member $member) {
    $transfer = $member->getCurrentTransferBundle(self::$propelCon);
    $this->assertEquals($total, $transfer->getAmount());
    $this->assertEquals($total, $member->getOutstandingTotal());
  }
}