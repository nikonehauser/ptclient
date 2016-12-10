<?php



/**
 * Skeleton subclass for representing a row from the 'tbmt_payment' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.miltype
 */
class Payment extends BasePayment
{

  const STATUS_CREATED = 1;
  const STATUS_EXECUTED = 2;
  const STATUS_CANCELED = 3;
  const STATUS_USER_CANCELED = 4;

  public function setStatus($v) {
    $status = $this->getStatus();
    if ( $status && $status !== self::STATUS_CREATED )
      throw new Exception('InvalidStateException: Payment locked!');

    return parent::setStatus($v);
  }

  public function getMeta() {
    return json_decode(parent::getMeta(), true);
  }

  public function setMeta($v) {
    return parent::setMeta(json_encode($v));
  }

}
