<?php

namespace Tbmt;

class AccountsApi extends RestServer {

  public $actions = array(
    'pushRootAccounts' => array('POST', 'pushRootAccounts', array(
      array('accounts', 'json' )
    ))
  );


  public function do_pushRootAccounts($arrAccounts) {
    if ( !is_array($arrAccounts) ) {
      throw new \Exception('Invalid data param.');
    }
    $con = \Propel::getConnection();

    if ( !$con->beginTransaction() )
      throw new \Exception('Could not begin transaction');

    $now = time();

    try {

      foreach ($arrAccounts as $account) {
        $objAccount = \Member::getByNum($account['Num']);

        $transfers = isset($account['Transfers']) ? $account['Transfers'] : [];
        foreach ( $transfers as $transfer ) {
          $objTransfer = $objAccount->getCurrentTransferBundle($transfer['Currency'], $con);

          $objTransfer->createTransaction(
            $objAccount,
            $transfer['Amount'],
            \Transaction::REASON_TRANSFER_TO_ROOT,
            null,
            $now,
            $con
          );
        }
      }

      throw new \Exception('test');

      if ( !$con->commit() )
        throw new \Exception('Could not commit transaction');

    } catch (\Exception $e) {
        $con->rollBack();
        throw $e;
    }

    return true;
  }
}

?>