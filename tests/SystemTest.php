<?php

class SystemTest extends Tbmt_Tests_DatabaseTestCase {

  static public function setUpBeforeClass() {
    $con = Propel::getConnection();
    DbEntityHelper::truncateDatabase($con);

  }

  public function testHandleInvalidEmailRecipient() {
    \Tbmt\MailHelper::send(
      'non@efesus.de',
      null,
      'test',
      'test'
    );
  }

}