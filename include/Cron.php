<?php

namespace Tbmt;

class Cron {
  public static function removeUnpaid() {
    $con = Propel::getConnection();

    if ( !$con->beginTransaction() )
      throw new Exception('Could not begin transaction');

    $now = time();

    // - 2 weeks (3600 * 24 * 14)
    $twoWeeksAgo -= 1209600;

    try {
      $unpaidMembers = MemberQuery::create()
        ->filterByPaidDate(null, Criteria::ISNULL)
        ->filterBySignupDate($twoWeeksAgo, Criteria::LESS_THAN)
        ->filterByDeletionDate(null, Criteria::ISNULL)
        ->find($con);

      foreach ( $unpaidMembers as $member ) {
        $member->deleteAndUpdateTree($con);
      }

      if ( !$con->commit() )
        throw new Exception('Could not commit transaction');

    } catch (Exception $e) {
        $con->rollBack();
        throw $e;
    }
  }
}


?>
