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

  const ACCOUNT_NUM_CEO1   = 102;
  const ACCOUNT_NUM_IT     = 104;
  const ACCOUNT_SYLVHEIM   = 105;
  const ACCOUNT_NGO_PROJECTS = 106;
  const ACCOUNT_EXECUTIVE = 108;

  static public function getIncreasedInvitationIncrementer(PropelPDO $con) {
    $systemStats = self::getStats();

    $inc = hexdec($systemStats->getInvitationIncrementer());
    $inc += 106121;
    $inc = dechex($inc);

    $systemStats->setInvitationIncrementer($inc);
    $systemStats->save($con);

    return $inc;
  }

  static public function getIncreasedInvoiceNumber(PropelPDO $con) {
    $systemStats = self::getStats();

    $inc = (int)$systemStats->getInvoiceNumber();
    $inc += 1;

    $systemStats->setInvoiceNumber($inc);
    $systemStats->save($con);

    return 'INV_' + (1000000 + $inc);
  }

  static private function getStats() {
    $systemStats = SystemStatsQuery::create()->findOneById(1);
    if ( !$systemStats )
      throw new Exception('IllegalSystemState: Did not initialize application');

    return $systemStats;
  }

  static private $systemAccount;
  static public function getSystemAccount() {
    if ( !self::$systemAccount )
      self::$systemAccount = Member::getByNum(self::ACCOUNT_NUM_SYSTEM);

    return self::$systemAccount;
  }

  static public function _refreshForUnitTests() {
    self::$systemAccount = null;
  }
}
