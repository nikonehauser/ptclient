<?php

namespace Tbmt;

class Cron {

  static private $allowed = [
    'mailfailedactivities',
    'mails',
    'clearnonces',
    'remove_unpaid',
    'remove_member',
    'notify_new_guide'
  ];

  public static function run($job, array $arrParams = []) {
    $start = time();
    $log = '';

    if ( !in_array($job, self::$allowed) )
      throw new \Exception('Unknown cronjob: '.$job);

    $lock = new Flock(Config::get('lock.cron.path').'cron.'.$job.'.lock');
    if ( !$lock->acquire() ) {
      $log .= '--locked--';
    } else {
      try {
        $log .= call_user_func_array(['Tbmt\\Cron', "job_$job"], $arrParams);
      } catch (\Exception $e) {
        // MailHelper::sendException($e, "Cron job \"$job\" failed");
        $log .= $e->__toString();
      } finally {
        $lock->release();

      }
    }

    $content = (new \DateTime())->format('Y-m-d H-i-s').' :: '.(time() - $start).'s :: ['.$job.'] : '.$log."\n";
    if (php_sapi_name() == "cli") {
      echo $content;
    }


    file_put_contents(
      Config::get('logs.path').$job.'.cron.logs',
      $content,
      FILE_APPEND
    );
  }

  private static function job_mailfailedactivities() {
    $activity = \ActivityQuery::create()
      ->filterByNotified(0)
      ->filterByType(\Activity::TYPE_FAILURE)
      ->find();

    $count = count($activity);
    if ( $count > 0 )
      MailHelper::sendFailedActivities($activity);

    return 'Send failed activity: '.$count;
  }

  private static function job_mails() {
    return MailQueue::run();
  }

  private static function job_clearnonces() {
    \NonceQuery::create()->filterByDate(time(), \Criteria::GREATER_EQUAL)->delete();
  }

  private static function job_remove_unpaid($now = null, $allowedSeconds = 1209600) {
    $con = \Propel::getConnection();

    if ( !$con->beginTransaction() )
      throw new \Exception('Could not begin transaction');

    if ( $now === null )
      $now = time();

    if ( !$allowedSeconds )
      $allowedSeconds = \Tbmt\Config::get('remove_unpaid_after_seconds');

    $twoWeeksAgo = $now - $allowedSeconds;

    $result = '';
    try {
      $unpaidMembers = \MemberQuery::create()
        ->filterByPaidDate(null, \Criteria::ISNULL)
        ->filterBySignupDate($twoWeeksAgo, \Criteria::LESS_THAN)
        ->filterByDeletionDate(null, \Criteria::ISNULL)
        ->filterByIsExtended(1)
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

  private static function job_notify_new_guide() {
    $con = \Propel::getConnection();
    $secondsPerGuide = \Tbmt\Config::get('guides_available_period', \Tbmt\TYPE_INT);
    $guidesCount = \Tbmt\Config::get('guides_count', \Tbmt\TYPE_INT);
    $now = time();

    $members = \MemberPeer::getMemberToNotifyNewHappinessGuide($secondsPerGuide, $guidesCount, $now, $con);
    foreach ($members as $member) {
      $member->notifyNewHappinessGuide($secondsPerGuide, $now, $con);
    }
  }

  /**
   * Remind all unpaid members after 7 days.
   *
   * @deprecated
   *
   * @return
   */
  private static function job_email_reminder($now = null, $allowedDays = '-7 days') {
    $con = \Propel::getConnection();

    if ( !$con->beginTransaction() )
      throw new \Exception('Could not begin transaction');

    if ( $now === null )
      $now = time();

    $before7Days = strtotime($allowedDays, $now);

    $result = '';

    try {
      $unpaidMembers = \MemberQuery::create()
        ->filterByIsExtended(1)
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

  private static function job_remove_member($num) {
    $con = \Propel::getConnection();

    if ( !$con->beginTransaction() )
      throw new \Exception('Could not begin transaction');

    $now = time();
    $result = '';
    try {
      $member = \Member::getByNum($num);
      $member->deleteAndUpdateTree($con);

      if ( !$con->commit() )
        throw new \Exception('Could not commit transaction');

      $result = 'remove - '.$num;

    } catch (\Exception $e) {
        $con->rollBack();
        throw $e;
    }

    return $result;
  }

  private static function job_rotate() {
    return 'check rotation';
  }

}


?>
