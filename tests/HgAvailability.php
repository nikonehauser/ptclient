<?php

class HgAvailabilityTest extends Tbmt_Tests_DatabaseTestCase {

  static public function setUpBeforeClass() {
    $con = Propel::getConnection();
    DbEntityHelper::truncateDatabase($con);

  }

  public function test_hgAvailabilitySelect() {
    $paid = strtotime('2014/04/03');
    $now = strtotime('+11 days', $paid);

    $secondsPerGuide = 60*60*24*5; // 5 days

    DbEntityHelper::setCon(self::$propelCon);
    $member = DbEntityHelper::createMember();
    $member->setHgWeek(3); //
    $member->setPaidDate($paid);
    $member->save();

    $member = \MemberPeer::getMemberToNotifyNewHappinessGuide($secondsPerGuide, 8, $now);
    $this->assertEquals(count($member), 0);

    $now = strtotime('+15 days', $paid); // 3 weeks, give him the 4th guide
    $member = \MemberPeer::getMemberToNotifyNewHappinessGuide($secondsPerGuide, 8, $now);
    $this->assertEquals(count($member), 1);
  }

  public function test_hgAvailabilityUpgrade() {
    $paid = strtotime('2014/04/03');
    $now = strtotime('+11 days', $paid);

    $secondsPerGuide = 60*60*24*5; // 5 days

    DbEntityHelper::setCon(self::$propelCon);
    $member = DbEntityHelper::createMember();
    $member->setHgWeek(3); //
    $member->setPaidDate($paid);
    $member->save();

    $member->notifyNewHappinessGuide($secondsPerGuide, $now);
    $this->assertEquals($member->getHgWeek(), 3); // unchanged

    $now = strtotime('+15 days', $paid); // 3 weeks, give him the 4th guide
    $member->notifyNewHappinessGuide($secondsPerGuide, $now);
    $this->assertEquals($member->getHgWeek(), 4);

    $now = strtotime('+26 days', $paid); // 3 weeks, give him the 4th guide
    $member->notifyNewHappinessGuide($secondsPerGuide, $now);
    $this->assertEquals($member->getHgWeek(), 6);
  }

}
