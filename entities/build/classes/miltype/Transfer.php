<?php



/**
 * Skeleton subclass for representing a row from the 'tbmt_transfer' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.miltype
 */
class Transfer extends BaseTransfer {
  /**
   * This transfer is open in the way that we collect all transactions
   * in this transfer. Once the transfer is or get executed this transfer
   * is locked an can not be changed again. In this case you have to create
   * a new Transfer object with state STATE_COLLECT.
   */
  const STATE_COLLECT = 0;

  const STATE_RESERVED = 1;

  const STATE_IN_EXECUTION = 2;
  const STATE_DONE = 3;
  const STATE_FAILED = 4;

  public function getAmountSum(PropelPDO $con = null) {
    if ( $con == null )
      $con = \Propel::getConnection();

    $sum = $con->query(
      'SELECT SUM(amount) FROM '.\TransactionPeer::TABLE_NAME
      .' WHERE transfer_id = '.$this->getId()
      .';'
    )->fetch(PDO::FETCH_NUM)[0];

    if ( !$sum ) {
      return 0;
    }

    return $sum;
  }

  /**
   * Adds the given amount to this transfer.
   * @param [type] $intAmount
   */
  public function addAmount($intAmount, PropelPDO $con) {
    // required for atomic concurrent update
    // $con->exec('UPDATE '.\TransferPeer::TABLE_NAME.
    //   ' SET amount = amount + '.((float)$intAmount)
    //   .' WHERE id = '.$this->getId()
    //   .';');

    // $currentAmount = $con->query(
    //   'SELECT amount FROM '.\TransferPeer::TABLE_NAME
    //   .' WHERE id = '.$this->getId()
    //   .';'
    // )->fetch(PDO::FETCH_NUM)[0];

    // $this->setAmount($currentAmount);
    $transaction = new Transaction();
    $transaction->setTransfer($this);
    $transaction->setAmount($intAmount);
    return $transaction;
  }

  public function createTransaction(Member $transferOwner, $amount, $reason, $relatedId, $when, PropelPDO $con) {
    $transaction = $this->addAmount($amount, $con);
    $transaction->setReason($reason);
    $transaction->setRelatedId($relatedId);
    $transaction->setDate($when);
    $transaction->save($con);
    return $transaction;
  }

  public function createTransactionForReason(Member $transferOwner, $reason, $advertisedMemberId, $when, PropelPDO $con) {
    if ( $reason === Transaction::REASON_CUSTOM_BONUS_LEVEL )
      $amount = $transferOwner->getBonusLevel();
    else
      $amount = Transaction::getAmountForReason($reason);

    $transaction = $this->addAmount($amount, $con);
    $transaction->setReason($reason);
    $transaction->setRelatedId($advertisedMemberId);
    $transaction->setDate($when);
    $transaction->save($con);
    return $transaction;
  }

  public function setState($v) {
    $history = $this->getStateHistory();
    if ( !$history )
      $history = [];
    else
      $history = json_decode($history, true);

    $history[] = date('Y-m-d H:i:s').' ## '.$v;

    $this->setStateHistory(json_encode($history));

    return parent::setState($v);
  }

  public function setExecutionDate($v) {
    $history = $this->getExecutionDateHistory();
    if ( !$history )
      $history = [];
    else
      $history = json_decode($history, true);

    $history[] = $v;

    $this->setExecutionDateHistory(json_encode($history));

    return parent::setExecutionDate($v);
  }
}
