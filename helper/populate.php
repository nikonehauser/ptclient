<?php

include dirname(__FILE__).'/bootstrap.php';

$now = time();

define('VL_CREATE_NUM', 10);
define('PM_PER_VL_CREATE_NUM', 10);

define('PM_PER_RUN_SELECT_NUM', 10);
define('MEMBER_PER_PM_CREATE_NUM', 3);

$con = Propel::getConnection();
DbEntityHelper::setCon($con);

$count = MemberQuery::create()->count();
if ( !$count ) {
  // Setup base structure
  // 100 VL with one OL with 100 PMs each = 10 000 PMs.

  $totalCount = VL_CREATE_NUM * PM_PER_VL_CREATE_NUM;
  $currentCount = 0;
  $currentPercent = 0;
  $tempPercent = 0;
  echo "creating marketing/organization structure:\n\n";
  echo "total objects to create (about): $totalCount\n\n";

  for ( $i = 0; $i < VL_CREATE_NUM; $i++ ) {
    list(
      list($IT, $VL, $OL, $PM, $VS2, $VS1),
      list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
    ) = DbEntityHelper::setUpBonusMembers(false);

    for ( $y = 0; $y < PM_PER_VL_CREATE_NUM; $y++ ) {
      $PM = DbEntityHelper::createSignupMember($OL);
      $PM->setType(Member::TYPE_PROMOTER)
        ->setFundsLevel(Member::FUNDS_LEVEL2)
        ->save($con);

      $currentCount++;
      $tempPercent = intval((($currentCount * 100) / $totalCount));
      if ( $tempPercent != $currentPercent ) {
        $timeTaken = time() - $now;
        $minutesTaken = $timeTaken / 60;
        echo "$currentPercent % - done - $currentCount / $totalCount - $timeTaken seconds - $minutesTaken minutes\n";
        $currentPercent = $tempPercent;
      }
    }
  }
} else {
  // select PM_PER_RUN_SELECT_NUM pms and create MEMBER_PER_PM_CREATE_NUM members for each one

  $totalCount = PM_PER_RUN_SELECT_NUM * MEMBER_PER_PM_CREATE_NUM;
  $currentCount = 0;
  $currentPercent = 0;
  $tempPercent = 0;
  echo "creating more members:\n\n";
  echo "total objects to create (about): $totalCount\n\n";

  $minId = MemberQuery::create()
    ->filterByType([1, 2, 3, 4, 5], Criteria::IN)
    ->orderBy('Id', Criteria::ASC)
    ->findOne($con)
    ->getId();

  $maxId = MemberQuery::create()
    ->filterByType([1, 2, 3, 4, 5], Criteria::IN)
    ->orderBy('Id', Criteria::DESC)
    ->findOne($con)
    ->getId();

  $numbers = range($minId, $maxId);
  shuffle($numbers);
  $shuffledIds = array_slice($numbers, 0, PM_PER_RUN_SELECT_NUM);

  $pms = MemberQuery::create()
    ->filterByType([1, 2, 3, 4, 5], Criteria::IN)
    ->filterById($shuffledIds)
    ->orderBy('Id', Criteria::DESC)
    ->limit(PM_PER_RUN_SELECT_NUM)
    ->find($con);

  foreach ( $pms as $pm ) {
    for ( $i = 0; $i < MEMBER_PER_PM_CREATE_NUM; $i++ ) {
      if ( !$con->beginTransaction() )
        throw new Exception('Could not begin transaction');

      try {

        DbEntityHelper::createSignupMember($pm);

        if ( !$con->commit() )
          throw new Exception('Could not commit transaction');

      } catch (Exception $e) {
          $con->rollBack();
          throw $e;
      }

      $currentCount++;
      $tempPercent = intval((($currentCount * 100) / $totalCount));
      if ( $tempPercent != $currentPercent ) {
        $timeTaken = time() - $now;
        $minutesTaken = $timeTaken / 60;
        echo "$currentPercent % - done - $currentCount / $totalCount - $timeTaken seconds - $minutesTaken minutes\n";
        $currentPercent = $tempPercent;
      }
    }
  }
}

$count = MemberQuery::create()->count() - $count;
$timeTaken = time() - $now;
echo "done in $timeTaken seconds, populated - $count - new data.";

?>