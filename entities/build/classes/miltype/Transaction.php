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

  const REASON_ADVERTISED_LVL1     = 0;
  const REASON_ADVERTISED_LVL2     = 1;
  const REASON_ADVERTISED_INDIRECT = 2;
  const REASON_VL_BONUS = 3;
  const REASON_OL_BONUS = 4;
  const REASON_PM_BONUS = 5;
  const REASON_IT_BONUS = 6;


  const AMOUNT_ADVERTISED_LVL1     = 5;
  const AMOUNT_ADVERTISED_LVL2     = 20;
  const AMOUNT_ADVERTISED_INDIRECT = 15;

  const AMOUNT_VL_BONUS = 1;
  const AMOUNT_OL_BONUS = 1;
  const AMOUNT_PM_BONUS = 1;
  const AMOUNT_IT_BONUS = 1;

  static public $REASON_TO_AMOUNT = [
    self::REASON_ADVERTISED_LVL1 => self::AMOUNT_ADVERTISED_LVL1,
    self::REASON_ADVERTISED_LVL2 => self::AMOUNT_ADVERTISED_LVL2,
    self::REASON_ADVERTISED_INDIRECT => self::AMOUNT_ADVERTISED_INDIRECT,
    self::REASON_VL_BONUS => self::AMOUNT_VL_BONUS,
    self::REASON_OL_BONUS => self::AMOUNT_OL_BONUS,
    self::REASON_PM_BONUS => self::AMOUNT_PM_BONUS,
    self::REASON_IT_BONUS => self::AMOUNT_IT_BONUS,
  ];
}
