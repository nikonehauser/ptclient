<?php



/**
 * Skeleton subclass for performing query and update operations on the 'tbmt_member' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.miltype
 */
class MemberPeer extends BaseMemberPeer
{
  static public function getMemberToNotifyNewHappinessGuide($secondsPerGuide, $maxHgCount, $now = null, PropelPDO $con = null) {
    if ( !$now )
      $now = time();

    if ( !$con )
      $con = \Propel::getConnection();

    $sql = "SELECT * FROM ".MemberPeer::TABLE_NAME." WHERE"
            ." hg_week <= :max_hg_count"
            ." AND (TIMESTAMPDIFF(SECOND, paid_date, :date_now)) >= (:seconds_per_guide * hg_week)";

    $stmt = $con->prepare($sql);
    $stmt->execute(array(
      ':date_now' => date('Y-m-d H:i:s', $now),
      ':seconds_per_guide' => $secondsPerGuide,
      ':max_hg_count' => $maxHgCount
    ));

    $formatter = new PropelObjectFormatter();
    $formatter->setClass('Transfer');
    return $formatter->format($stmt);
  }
}
