<?php

class DbEntityHelper {
  /**
   * @var PropelPDO
   */
  static protected $con = null;

  static public function setCon(PropelPDO $con) {
    self::$con = $con;
  }

  static public function truncateDatabase(PropelPDO $con) {
    self::truncateTables([
      MemberPeer::TABLE_NAME
    ], $con);
  }

  static public function truncateTables(array $tables, PropelPDO $con) {
    $con->exec('TRUNCATE TABLE '.implode(',', $tables). ' RESTART IDENTITY CASCADE');
  }

  static private $memberDefaults = [
    'Title'         => 'title',
    'LastName'      => 'title',
    'FirstName'     => 'title',
    'Age'           => 25,
    'Email'         => 'title',
    'City'          => 'title',
    'Country'       => 'title',
    'BankRecipient' => 'title',
    'Iban'          => 'title',
    'Bic'           => 'title',
    'Password'      => 'demo1234',
  ];

  static private $memberSignup = [
    'title'          => 'test',
    'lastName'       => 'test',
    'firstName'      => 'test',
    'age'            => 25,
    'email'          => 'test',
    'city'           => 'test',
    'country'        => 'test',
    'bank_recipient' => 'test',
    'iban'           => 'test',
    'bic'            => 'test',
    'password'       => 'demo1234',
  ];

  static public function createMember(Member $referralMember = null, array $data = array()) {
    $member = new Member();

    $member->fromArray(array_merge(self::$memberDefaults, $data));
    if ( $referralMember )
      $member->setReferralMemberId($referralMember->getId());

    $now = time();
    $member->setSignupDate($now)
      ->setPaidDate($now + 100000)
      ->save(self::$con);

    return $member;
  }

  static public function createSignupMember(Member $referralMember, $receivedPaiment = true) {
    $member = Member::createFromSignup(self::$memberSignup, $referralMember, self::$con);
    if ( $receivedPaiment )
      $member->onReceivedMemberFee(time(), self::$con);

    return $member;
  }
}