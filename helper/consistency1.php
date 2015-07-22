<?php
    echo 'test1';

    include dirname(__FILE__).'/bootstrap.php';

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

    echo "\n CONSISTENCY 1\n";

?>