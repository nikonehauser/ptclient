<?php



/**
 * Skeleton subclass for representing a row from the 'tbmt_bonus_transaction' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.miltype
 */
class BonusTransaction extends BaseBonusTransaction
{

  static public $BONUS_TRANSACTION_FORM_FIELDS = [
    'recipient_id'  => [\Tbmt\TYPE_INT, ''],
    'recipient_num' => [\Tbmt\TYPE_INT, ''],
    'amount'       => \Tbmt\TYPE_INT,
    'purpose'      => \Tbmt\TYPE_STRING,
  ];

  static public $BONUS_TRANSACTION_FORM_FILTERS = [
    'recipient_num'  => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'amount' => [
      'filter' => \FILTER_VALIDATE_INT,
      'options' => [
        'min_range' => 1
      ],
      'errorLabel' => 'error.greater_zero'
    ],
    'purpose' => \Tbmt\Validator::FILTER_NOT_EMPTY,
  ];

  static public function initForm(array $data = array()) {
    return \Tbmt\Arr::initMulti($data, self::$BONUS_TRANSACTION_FORM_FIELDS);
  }

  static public function validateForm(array $data = array()) {
    $data = self::initForm($data);

    $res = \Tbmt\Validator::getErrors($data, self::$BONUS_TRANSACTION_FORM_FILTERS);
    if ( $res !== false )
      return [false, $res, null];

    $recipient = \MemberQuery::create()
      ->filterByDeletionDate(null, Criteria::ISNULL)
      ->findOneByNum($data['recipient_num']);
    if ( $recipient == null ) {
      return [false, ['recipient_num' => \Tbmt\Localizer::get('error.member_num')], null];
    }

    if ( !$recipient->hadPaid() )
      return [false, ['recipient_num' => \Tbmt\Localizer::get('error.member_num_unpaid')], null];

    return [true, $data, $recipient];
  }

  static public function create(Member $login, Member $recipient, array $data, PropelPDO $con) {
    $currentTransfer = $recipient->getCurrentTransferBundle($con);
    $when = time();

    if ( !$con->beginTransaction() )
      throw new Exception('Could not begin transaction');

    try {

      $amount = $data['amount'];
      $recipient->addOutstandingTotal($amount);
      $transaction = $currentTransfer->addAmount($amount);
      $transaction->setReason(Transaction::REASON_CUSTOM_BONUS);
      $transaction->setRelatedId($recipient->getId());
      $transaction->setDate($when);
      $transaction->save($con);

      $bonusTransaction = new BonusTransaction();
      $bonusTransaction
        ->setMemberId($login->getId())
        ->setTransactionId($transaction->getId())
        ->setPurpose($data['purpose'])
        ->save($con);

      $currentTransfer->save($con);
      $recipient->save($con);

      if ( !$con->commit() )
        throw new Exception('Could not commit transaction');

    } catch (Exception $e) {
        $con->rollBack();
        throw $e;
    }
  }
}
