<?php

namespace Tbmt;

use \OpenPayU_Configuration;

class Payu {

  static public getInstance() {
    //set Sandbox Environment
    OpenPayU_Configuration::setEnvironment(Config::get('payu_environment'));

    //set POS ID and Second MD5 Key (from merchant admin panel)
    OpenPayU_Configuration::setMerchantPosId('300046');
    OpenPayU_Configuration::setSignatureKey('0c017495773278c50c7b35434017b2ca');
    
    //set Oauth Client Id and Oauth Client Secret (from merchant admin panel)
    OpenPayU_Configuration::setOauthClientId('300046');
    OpenPayU_Configuration::setOauthClientSecret('c8d4b7ac61758704f38ed5564d8c0ae0');
  }

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

  public function validateResponse(array $data, \Member $member) {
    $result = Arr::initMulti($data, self::$RETURN_FIELDS);
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

  public function preparePayment(\Member $member, \PropelPDO $con) {
    $payment = \PaymentQuery::create()
      ->filterByMember($member)
      ->filterByStatus(\Payment::STATUS_CREATED)
      ->findOne($con);

    if ( !$payment ) {
      $payment = \Payment::create($member, $con);
    }

    return $payment;
  }

  public function prepareFormData(\Member $member, \PropelPDO $con) {
    $order = [];
    $order['notifyUrl'] = Config::get('payu_merchant_salt'); //customer will be redirected to this page after successfull payment
    $order['continueUrl'] = \Tbmt\Router::toModule('guide', 'fhandle');
    $order['customerIp'] = $_SERVER['REMOTE_ADDR'];
    $order['merchantPosId'] = OpenPayU_Configuration::getMerchantPosId();
    $order['description'] = 'New order';
    $order['currencyCode'] = 'PLN';
    $order['totalAmount'] = 3200;
    $order['extOrderId'] = '1342'; //must be unique!

    $order['products'][0]['name'] = 'Product1';
    $order['products'][0]['unitPrice'] = 1000;
    $order['products'][0]['quantity'] = 1;

    $order['products'][1]['name'] = 'Product2';
    $order['products'][1]['unitPrice'] = 2200;
    $order['products'][1]['quantity'] = 1;

    //optional section buyer
    $order['buyer']['email'] = 'dd@ddd.pl';
    $order['buyer']['phone'] = '123123123';
    $order['buyer']['firstName'] = 'Jan';
    $order['buyer']['lastName'] = 'Kowalski';

    $response = OpenPayU_Order::create($order);

    return $response->getResponse()->redirectUri;

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

  public function buildPayuHash(array $data, $sequence) {
    $hashSequence = explode('|', $sequence);
    $hash = [];
    foreach ($hashSequence as $key) {
      $hash[] = (isset($data[$key]) ? $data[$key] : '');
    }

    return strtolower(hash('sha512', implode('|', $hash)));
  }

  public function getMemberPhone(\Member $member) {
    $phone = $member->getPhone();
    if ( empty($phone) )
      return '9123456789';

    return $phone;
  }
}
