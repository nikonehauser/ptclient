<?php

namespace Tbmt;

class WeightController extends BaseController {

  const MODULE_NAME = 'weight';

  protected $actions = [
    'changeLong' => true,
    'changeShort' => true,
  ];

  public function action_changeLong() {
    echo 'test1';
    sleep(5);
    return;

    $member = \MemberQuery::create()->findOneById(1);

    $con = \Propel::getConnection();
    if ( !$con->beginTransaction() )
      throw new \Exception('Could not begin transaction');

    try {

      $transfer = \TransferQuery::create()->findOneById(1);
      if ( !$transfer ) {
        $transfer = new \Transfer();
        $transfer->setMemberId($member->getId());
        $transfer->save($con);
        $transfer = \TransferQuery::create()->findOneById(1);
      }

      $transfer->setAmount($transfer->getAmount() + 2);


      $transfer->save($con);

      if ( !$con->commit() )
        throw new \Exception('Could not commit transaction');

      sleep(5);
    } catch (\Exception $e) {
        $con->rollBack();
        throw $e;
    }

    print_r('<pre>');
    print_r($transfer->toArray());
    print_r('</pre>');

  }

  public function action_changeShort() {
    echo 'test2';
    return;

    $member = \MemberQuery::create()->findOneById(1);

    $con = \Propel::getConnection();
    if ( !$con->beginTransaction() )
      throw new \Exception('Could not begin transaction');

    try {

      $transfer = \TransferQuery::create()->findOneById(1);
      if ( !$transfer ) {
        $transfer = new \Transfer();
        $transfer->setMemberId($member->getId());
        $transfer->save($con);
        $transfer = \TransferQuery::create()->findOneById(1);
      }

      $transfer->setAmount($transfer->getAmount() + 2);

      $transfer->save($con);

      if ( !$con->commit() )
        throw new \Exception('Could not commit transaction');

    } catch (\Exception $e) {
        $con->rollBack();
        throw $e;
    }

    print_r('<pre>');
    print_r($transfer->toArray());
    print_r('</pre>');
  }
}

?>
