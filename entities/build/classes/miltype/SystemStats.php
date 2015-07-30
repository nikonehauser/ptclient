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
  /**
   * The System account holds the total income which remains after all
   * members and bonuses of this system got paid (promoter, orgleader etc.)
   *
   */
  const ACCOUNT_NUM_SYSTEM  = 100;

  /**
   * The Root account holds the total income which should be transferred to
   * the root/main system. This excludes provisions and bonuses for promoter
   * but includes "special" bonuses for eg. lawyer, ceo1, it etc.
   */
  const ACCOUNT_NUM_ROOT = 101;

  const ACCOUNT_NUM_CEO1   = 102;
  const ACCOUNT_NUM_CEO2   = 103;
  const ACCOUNT_NUM_IT     = 104;
  const ACCOUNT_NUM_LAWYER = 105;

  static public $ROOT_ACCOUNTS_NUM = [
    self::ACCOUNT_NUM_ROOT,
    self::ACCOUNT_NUM_CEO1,
    self::ACCOUNT_NUM_CEO2,
    self::ACCOUNT_NUM_IT,
    self::ACCOUNT_NUM_LAWYER,
  ];

  static private $systemAccount;
  static public function getSystemAccount() {
    if ( !self::$systemAccount )
      self::$systemAccount = Member::getByNum(self::ACCOUNT_NUM_SYSTEM);

    return self::$systemAccount;
  }

  static private $rootAccount;
  static public function getRootAccount() {
    if ( !self::$rootAccount )
      self::$rootAccount = Member::getByNum(self::ACCOUNT_NUM_ROOT);

    return self::$rootAccount;
  }

  static public function _refreshForUnitTests() {
    self::$systemAccount = null;
    self::$rootAccount = null;
  }
}
