<?php

namespace Tbmt;

class Cron {
  public static function removeUnpaid($now = null, $allowedSeconds = 1209600) {
    $con = \Propel::getConnection();

    if ( !$con->beginTransaction() )
      throw new \Exception('Could not begin transaction');

    if ( $now === null )
      $now = time();

    // - 2 weeks (3600 * 24 * 14)
    $twoWeeksAgo = $now - $allowedSeconds;

    $result = '';
    try {
      $unpaidMembers = \MemberQuery::create()
        ->filterByPaidDate(null, \Criteria::ISNULL)
        ->filterBySignupDate($twoWeeksAgo, \Criteria::LESS_THAN)
        ->filterByDeletionDate(null, \Criteria::ISNULL)
        ->find($con);

      $result .= "Remove unpaid user job:\n\n";

      if ( count($unpaidMembers) > 0 ) {
        $result .= "Found ".count($unpaidMembers)." members:\n\n";
      } else {
        $result .= "No unpaid members found.";
      }

      foreach ( $unpaidMembers as $member ) {
        $result .= "Remove member: ".$member->getNum()."\n";
        $member->deleteAndUpdateTree($con);
      }

      if ( !$con->commit() )
        throw new \Exception('Could not commit transaction');

    } catch (\Exception $e) {
        $con->rollBack();
        throw $e;
    }

    return $result;
  }

  /**
   * Remind all unpaid members after 7 days.
   *
   * @return
   */
  public static function emailReminder($now = null, $allowedDays = '-7 days') {
    $con = \Propel::getConnection();

    if ( !$con->beginTransaction() )
      throw new \Exception('Could not begin transaction');

    if ( $now === null )
      $now = time();

    $before7Days = strtotime($allowedDays, $now);

    $result = '';

    try {
      $unpaidMembers = \MemberQuery::create()
        // member has not paid
        ->filterByPaidDate(null, \Criteria::ISNULL)
        // signup is 7 days ago or later
        ->filterBySignupDate($before7Days, \Criteria::LESS_EQUAL)
        // Member was not deleted
        ->filterByDeletionDate(null, \Criteria::ISNULL)
        // Member paid but the income was not processed because his referrer
        // did not paid yet, exclude these members
        ->joinReservedPaidEventRelatedByPaidId(null, \Criteria::LEFT_JOIN)
        ->where(\ReservedPaidEventPeer::PAID_ID.' is null', null)

        // Reminder email was not send (prevent sending twice)
        ->joinMemberData(null, \Criteria::LEFT_JOIN)
        ->with('MemberData')
        ->condition('reminderMailIsNull', 'MemberData.FeeReminderEmail is null', null)
        ->condition('reminderMailEqualZero', 'MemberData.FeeReminderEmail = ?', 0)
        ->where(array('reminderMailIsNull', 'reminderMailEqualZero'), \Criteria::LOGICAL_OR)
        ->find();

      $result .= "7 days reminder email job:\n\n";

      if ( count($unpaidMembers) > 0 ) {
        $result .= "Found ".count($unpaidMembers)." members:\n\n";
      } else {
        $result .= "No member to remind found.";
      }

      foreach ($unpaidMembers as $member) {
        $totalAdvertised = $member->getAdvertisedCountTotal();
        $result .= "Member: ".$member->getNum()."\n";
        $result .= "Advertised count: ".$totalAdvertised."\n";

        if ( $totalAdvertised === 0 ) {
          MailHelper::sendFeeReminder($member);
          MailHelper::sendFeeReminderReferrer($member->getReferrerMember(), $member);
        } else {
          MailHelper::sendFeeReminderWithAdvertisings($member);
          MailHelper::sendFeeReminderWithAdvertisingsReferrer($member->getReferrerMember(), $member);
        }

        $data = $member->getMemberData();
        if ( !$data ) {
          $data = new \MemberData();
          $data->setMemberId($member->getId());
        }

        $data->setFeeReminderEmail(1);
        $data->save($con);

        $result .= "---------\n\n";
      }

      if ( !$con->commit() )
        throw new \Exception('Could not commit transaction');

    } catch (\Exception $e) {
        $con->rollBack();
        throw $e;
    }

    return $result;

  }
}


?>
