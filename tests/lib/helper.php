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
  ];

  static public function createMember(Member $parent = null, array $data = array()) {
    $member = new Member();

    $member->fromArray(array_merge(self::$memberDefaults, $data));
    if ( $parent )
      $member->setRefererNum($parent->getNum());

    $member->setSignupDate(time())
      ->save(self::$con);

    return $member;
  }

  static public function createSignupMember(Member $parent) {
    return Member::createFromSignup(self::$memberSignup, $parent, self::$con);
  }
}