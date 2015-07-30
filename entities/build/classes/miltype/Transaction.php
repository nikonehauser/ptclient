<?php



/**
 * Skeleton subclass for representing a row from the 'tbmt_transaction' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.miltype
 */
class Transaction extends BaseTransaction {

  /* TRANSACTION REASONS NUMBERS
  ---------------------------------------------*/
  const REASON_ADVERTISED_LVL1     = 0;
  const REASON_ADVERTISED_LVL2     = 1;
  const REASON_ADVERTISED_INDIRECT = 2;
  const REASON_VL_BONUS = 3;
  const REASON_OL_BONUS = 4;
  const REASON_PM_BONUS = 5;

  const REASON_IT_BONUS = 6;
  const REASON_CEO1_BONUS = 7;
  const REASON_CEO2_BONUS = 8;
  const REASON_LAWYER_BONUS = 9;

  const REASON_CUSTOM_BONUS = 1001;
  const REASON_REMAINING_MEMBER_FEE = 1002;
  const REASON_TRANSFER_TO_ROOT = 1003;

  static public $MEMBER_FEE;
  static public $BASE_CURRENCY;
  static public $REASON_TO_AMOUNT = [];

  static public function getAmountForReason($reason) {
    return self::$REASON_TO_AMOUNT[$reason];
  }

  static public function initAmounts(array $amountsByReasons, $memberFee, $baseCurrency) {
    self::$REASON_TO_AMOUNT = $amountsByReasons;
    self::$MEMBER_FEE = $memberFee;
    self::$BASE_CURRENCY = $baseCurrency;
  }
}
