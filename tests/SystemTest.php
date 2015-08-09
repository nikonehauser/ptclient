<?php

class SystemTest extends Tbmt_Tests_DatabaseTestCase {

  static public function setUpBeforeClass() {
    $con = Propel::getConnection();
    DbEntityHelper::truncateDatabase($con);

  }

  public function testCanUsePaidDateForDifferentStates() {
    $member = new Member();
    $member->setPaidDate(1);
    print_r('<pre>');
    print_r([$member->getPaidDate()]);
    print_r('</pre>');

  }

}