<?php

namespace Tbmt;

class Cron {
  public static function removeUnpaid() {
    $con = \Propel::getConnection();

    if ( !$con->beginTransaction() )
      throw new \Exception('Could not begin transaction');

    $now = time();

    // - 2 weeks (3600 * 24 * 14)
    $twoWeeksAgo -= 1209600;

    try {
      $unpaidMembers = \MemberQuery::create()
        ->filterByPaidDate(null, \Criteria::ISNULL)
        ->filterBySignupDate($twoWeeksAgo, \Criteria::LESS_THAN)
        ->filterByDeletionDate(null, \Criteria::ISNULL)
        ->find($con);

      foreach ( $unpaidMembers as $member ) {
        $member->deleteAndUpdateTree($con);
      }

      if ( !$con->commit() )
        throw new \Exception('Could not commit transaction');

    } catch (\Exception $e) {
        $con->rollBack();
        throw $e;
    }
  }

  public static function pushRootAccounts() {
    $con = \Propel::getConnection();

    if ( !$con->beginTransaction() )
      throw new \Exception('Could not begin transaction');

    $now = time();

    try {
      $rootAccountNums = \SystemStats::$ROOT_ACCOUNTS_NUM;
      $rootAccounts = \MemberQuery::create()
        ->filterByNum($rootAccountNums, \Criteria::IN)
        ->filterByDeletionDate(null, \Criteria::ISNULL)
        ->find();

      $arrRootAccounts = [];
      foreach ($rootAccounts as $account) {
        $transfers = $account->getOpenCollectingTransfers($con);

        $arrTransfers = [];
        foreach ($transfers as $transfer) {
          $transfer->executeTransfer($account);
          $transfer->save($con);
          $arrTransfers[] = $transfer->toArray();
        }

        $account->save($con);

        $arrRootAccounts[] = $account->toArray() + [
          'Transfers' => $arrTransfers
        ];
      }

      $client = new ApiClient();

      print_r('<pre>');
      print_r([$client->pushRootAccounts($arrRootAccounts)]);
      print_r('</pre>');


      throw new \Exception('test');

      if ( !$con->commit() )
        throw new \Exception('Could not commit transaction');

    } catch (\Exception $e) {
        $con->rollBack();
        throw $e;
    }

  }
}


?>
