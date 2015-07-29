<?php



/**
 * Skeleton subclass for representing a row from the 'tbmt_system_stats' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.miltype
 */
class SystemStats extends BaseSystemStats {
  const SYSTEM_ACCOUNT_NUM = 100;
  const ROOT_ACCOUNT_NUM = 101;

  static private $systemAccount;
  static public function getSystemAccount() {
    if ( !self::$systemAccount )
      self::$systemAccount = Member::getByNum(self::SYSTEM_ACCOUNT_NUM);

    return self::$systemAccount;
  }

  static private $rootAccount;
  static public function getRootAccount() {
    if ( !self::$rootAccount )
      self::$rootAccount = Member::getByNum(self::ROOT_ACCOUNT_NUM);

    return self::$rootAccount;
  }

  static public function _refreshForUnitTests() {
    self::$systemAccount = null;
    self::$rootAccount = null;
  }
}
