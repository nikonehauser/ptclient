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
  const STATUS_FAILED = 3;

  const TYPE_SETBYADMIN = 1;
  const TYPE_SETBYADMINIMPORT = 2;
  const TYPE_BY_PAYU = 3;

  static public function create(\Member $member, \PropelPDO $con) {
    $invoiceNumber = \SystemStats::getIncreasedInvoiceNumber($con);

    $payment = new \Payment();
    $payment
      ->setStatus(\Payment::STATUS_CREATED)
      ->setType('payu')
      ->setDate(time())
      ->setMember($member)
      ->setInvoiceNumber($invoiceNumber)
      // ->setGatewayPaymentId()
      ->setMeta([])
      ->save($con);

    return $payment;
  }

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


  /**
   * Code to be run before persisting the object
   *
   * @param PropelPDO $con
   *
   * @return boolean
   */
  public function preSave(PropelPDO $con = null) {
    $this->ensureInvoiceFile();
    return true;
  }

  public function ensureInvoiceFile() {
    $filename = $this->buildInvoiceFilename();
    $file = \Tbmt\Config::get('invoice.files.dir').$filename;

    if ( $this->getStatus() === self::STATUS_EXECUTED ) {
      if ( !file_exists($file) ) {
        $this->buildInvoiceFile($file);
      }
    }

    return $filename;
  }

  private function buildInvoiceFilename() {
    return $this->getMemberId().'-'.$this->getInvoiceNumber().'-'.date('Y-m-d', $this->getDate()).'.txt';
  }

  private function buildInvoiceFile($file) {
    $content = \Tbmt\MailHelper::buildInvoiceContent($this->getMember(), $this, false);
    file_put_contents($file, $content);
  }
}
