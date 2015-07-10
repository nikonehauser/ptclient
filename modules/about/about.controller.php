<?php

namespace Tbmt;

class AboutController extends BaseController {

  const MODULE_NAME = 'about';

  protected $actions = [
    'index' => true,

    'changeLong' => true,
    'changeShort' => true,
  ];

  public function action_index() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index'
    );
  }

  public function action_changeLong() {
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
