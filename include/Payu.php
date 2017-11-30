<?php

namespace Tbmt;

class Payu {

  static public function preparePayment(\Member $member, \PropelPDO $con) {
    $payment = \PaymentQuery::create()
      ->filterByMember($member)
      ->filterByStatus(\Payment::STATUS_CREATED)
      ->find($con);

    if ( !$payment ) {
      $payment = \Payment::create($member);
    }

    return $payment;
  }

  static public function prepareFormData(\Member $member, \PropelPDO $con) {
    $payment = self::preparePayment($member, \Propel::getConnection());

    $merchant_key = Config::get('payu_merchant_key');

    $data = [
      'key' => $merchant_key,
      'txnid' => $payment->getInvoiceNumber(),
      'amount' => Config::get('member_fee', TYPE_FLOAT),
      'productinfo' => Config::get('product_description'),
      'firstname' => $member->getFirstname(),
      'email' => $member->getEmail(),
      'action' => Config::get('payu_base_url').'/_payment',
      'merchant_key' => $merchant_key,
      'surl' => '',
      'furl' => '',
    ];

    $data['hash'] = self::buildPayuHash($data);

    return $data;
  }

  static public function buildPayuHash(array $data) {
    $hashSequence = explode('|', 'key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10');
    $hash = '';
    foreach ($hashSequence as $key) {
      $hash .= (isset($data[$key]) ? $data[$key] : '').'|';
    }
    $hash .= Config::get('payu_merchant_salt');

    return strtolower(hash('sha512', $hash));
  }
}