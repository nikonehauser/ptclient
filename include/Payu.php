<?php

namespace Tbmt;

class Payu {

  // test credit card for test server
  // 5123456789012346
  //               05/2020
  //                123

  static private $FROM_HASH_SEQUENCE = 'merchant_key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10|SALT';
  static private $RETURN_HASH_SEQUENCE = 'SALT|status|udf10|udf9|udf8|udf7|udf6|udf5|udf4|udf3|udf2|udf1|email|firstname|productinfo|amount|txnid|merchant_key';

  static private $RETURN_FIELDS = [
    'mihpayid'  => \Tbmt\TYPE_STRING,
    'status'  => \Tbmt\TYPE_STRING,
    'mode'  => \Tbmt\TYPE_STRING,
    'key'  => \Tbmt\TYPE_STRING,
    'txnid'  => \Tbmt\TYPE_STRING,
    'amount'  => \Tbmt\TYPE_STRING,
    'email'  => \Tbmt\TYPE_STRING,
    'firstname'  => \Tbmt\TYPE_STRING,
    'productinfo'  => \Tbmt\TYPE_STRING,
    'error'  => \Tbmt\TYPE_STRING,
    'hash'  => \Tbmt\TYPE_STRING,

  ];

  static public function validateResponse(array $data, \Member $member) {
    $result = Arr::initMulti($data, self:$RETURN_FIELDS);
    $result['SALT'] = Config::get('payu_merchant_salt');

    $hash = self::buildPayuHash($result, self::$RETURN_HASH_SEQUENCE);
    if ( empty($result['hash']) || $result['hash'] !== $hash )
      throw new InvalidDataException('Tampered transaction results');

    if ( !empty($result['error']) )
      throw new InvalidDataException($result['error']);

    if ( empty($result['key']) || $result['key'] !== Config::get('payu_merchant_key') )
      throw new InvalidDataException('Invalid response data');

    // TODO - handle key 'status'
    // TODO - handle key 'mode'
    
    $payment = \PaymentQuery::create()
      ->filterByMember($member)
      ->filterByStatus(\Payment::STATUS_CREATED)
      ->filterByInvoiceNumber($result['txnid'])
      ->findOne($con);

    if ( !$payment )
      throw new InvalidDataException('Unknown payment id');

    $payment->setGatewayPaymentId($result['mihpayid']);
    
    $payment['payment'] = $payment;

    return $result;
  }

  static public function preparePayment(\Member $member, \PropelPDO $con) {
    $payment = \PaymentQuery::create()
      ->filterByMember($member)
      ->filterByStatus(\Payment::STATUS_CREATED)
      ->findOne($con);

    if ( !$payment ) {
      $payment = \Payment::create($member, $con);
    }

    return $payment;
  }

  static public function prepareFormData(\Member $member, \PropelPDO $con) {
    $payment = self::preparePayment($member, $con);

    $merchant_key = Config::get('payu_merchant_key');

    $data = [
      'txnid' => $payment->getInvoiceNumber(),
      'amount' => Config::get('member_fee', TYPE_FLOAT),
      'productinfo' => Localizer::get('payment.payu_product_description'),
      'firstname' => $member->getFirstname(),
      'email' => $member->getEmail(),
      'phone' => self::getMemberPhone($member),
      'action' => Config::get('payu_base_url').'/_payment',
      'merchant_key' => $merchant_key,
      'surl' => \Tbmt\Router::toModule('guide', 'shandle'),
      'furl' => \Tbmt\Router::toModule('guide', 'fhandle'),
      'SALT' => Config::get('payu_merchant_salt'),
    ];

    $data['hash'] = self::buildPayuHash($data, self::$FROM_HASH_SEQUENCE);

    return $data;
  }

  static public function buildPayuHash(array $data, $sequence) {
    $hashSequence = explode('|', $sequence);
    $hash = [];
    foreach ($hashSequence as $key) {
      $hash[] = (isset($data[$key]) ? $data[$key] : '');
    }

    return strtolower(hash('sha512', implode('|', $hash)));
  }

  static public function getMemberPhone(\Member $member) {
    $phone = $member->getPhone();
    if ( empty($phone) )
      return '9123456789';

    return $phone;
  }
}
