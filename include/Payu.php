<?php

namespace Tbmt;

use \OpenPayU_Configuration;

class Payu {

  static private $client;
  static private function getClient() {
    if ( !self::$client )
      self::$client = new RestClient();

    return self::$client;
  }

  // test credit card for test server
  // 5123456789012346
  //               05/2020
  //                123


  // invalid return parameters:
  // http://localsystem.social/index.php?mod=guide&act=fhandle&mihpayid=403993715516968447&mode=&status=failure&unmappedstatus=userCancelled&key=gtKFFx&txnid=INV_100000220171206220703&amount=3950.00&discount=0.00&net_amount_debit=0.00&addedon=2017-12-07+02%3A40%3A21&productinfo=Our+Happiness+Guide+series.+A+digital+download+about+getting+happier+in+life.+You+will+get+8+pieces+&firstname=Marcus&lastname=&address1=&address2=&city=&state=&country=&zipcode=&email=bonus%40betterliving.social&phone=9123456789&udf1=&udf2=&udf3=&udf4=&udf5=&udf6=&udf7=&udf8=&udf9=&udf10=&hash=1131be3743aabd89ed76f5d4ccc5129a96c7fdefc539e88aa13c47ace1f5f10c438a99e873d3491c20cbb34e4790d6c0c5a58f763eb377d18b210fa3ed82924a&field1=&field2=&field3=&field4=&field5=&field6=&field7=&field8=&field9=Cancelled+by+user&payment_source=payu&PG_TYPE=&bank_ref_num=&bankcode=&error=E1605&error_Message=Transaction+failed+due+to+customer+pressing+cancel+button.

  // successful return parameters:
  // http://localsystem.social/index.php?mod=guide&act=shandle&mihpayid=403993715516974502&mode=CC&status=success&unmappedstatus=captured&key=gtKFFx&txnid=INV_100000220171206220703&amount=3950.00&cardCategory=domestic&discount=0.00&net_amount_debit=3950&addedon=2017-12-07+22%3A53%3A57&productinfo=Our+Happiness+Guide+series.+A+digital+download+about+getting+happier+in+life.+You+will+get+8+pieces+&firstname=Marcus&lastname=&address1=&address2=&city=&state=&country=&zipcode=&email=bonus%40betterliving.social&phone=9123456789&udf1=&udf2=&udf3=&udf4=&udf5=&udf6=&udf7=&udf8=&udf9=&udf10=&hash=688836a3a56285ed4e29b0ee6480f657f582e880302e6c094d703e4d9f8219b5ad896b1d38e0882f6df5989822676d0cc026f84ca11e08730977301bc85d78ce&field1=853327&field2=470450&field3=202907&field4=MC&field5=366434216538&field6=00&field7=0&field8=3DS&field9=+Verification+of+Secure+Hash+Failed%3A+E700+--+Approved+--+Transaction+Successful+--+Unable+to+be+determined--E000&payment_source=payu&PG_TYPE=AXISPG&bank_ref_num=853327&bankcode=CC&error=E000&error_Message=No+Error&name_on_card=egal&cardnum=512345XXXXXX2346&cardhash=This+field+is+no+longer+supported+in+postback+params.&issuing_bank=HDFC&card_type=MAST

  static private $FROM_HASH_SEQUENCE = 'merchant_key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10|SALT';
  static private $RETURN_HASH_SEQUENCE = 'SALT|status||||||udf5|udf4|udf3|udf2|udf1|email|firstname|productinfo|amount|txnid|key';
  static private $API_HASH_SEQUENCE = 'key|command|var1|salt';

  static private $RETURN_FIELDS = [
    'mihpayid'  => \Tbmt\TYPE_STRING_NE,
    'status'  => \Tbmt\TYPE_STRING_NE,
    'mode'  => \Tbmt\TYPE_STRING_NE,
    'key'  => \Tbmt\TYPE_STRING_NE,
    'txnid'  => \Tbmt\TYPE_STRING_NE,
    'amount'  => \Tbmt\TYPE_STRING_NE,
    'email'  => \Tbmt\TYPE_STRING_NE,
    'firstname'  => \Tbmt\TYPE_STRING_NE,
    'productinfo'  => \Tbmt\TYPE_STRING_NE,
    'error'  => \Tbmt\TYPE_STRING_NE,
    'error_Message'  => \Tbmt\TYPE_STRING_NE,
    'hash'  => \Tbmt\TYPE_STRING_NE,

    'discount'  => \Tbmt\TYPE_STRING_NE,
    'offer'  => \Tbmt\TYPE_STRING_NE,
    'lastname'  => \Tbmt\TYPE_STRING_NE,
    'address1'  => \Tbmt\TYPE_STRING_NE,
    'address2'  => \Tbmt\TYPE_STRING_NE,
    'udf1'  => \Tbmt\TYPE_STRING_NE,
    'udf2'  => \Tbmt\TYPE_STRING_NE,
    'udf3'  => \Tbmt\TYPE_STRING_NE,
    'udf4'  => \Tbmt\TYPE_STRING_NE,
    'udf5'  => \Tbmt\TYPE_STRING_NE,
    'bankcode'  => \Tbmt\TYPE_STRING_NE,
    'PG_TYPE'  => \Tbmt\TYPE_STRING_NE,
    'bank_ref_num'  => \Tbmt\TYPE_STRING_NE,
    'unmappedstatus'  => \Tbmt\TYPE_STRING_NE,

  ];

  static public function processResponse(array $data, \Member $member, \PropelPDO $con) {
    $result = Arr::initMulti($data, self::$RETURN_FIELDS);

    if ( !empty($result['error']) && $result['error'] != 'E000' )
      throw new InvalidDataException($result['error'].(!empty($result['error_Message']) ? ' '.$result['error_Message'] : ''));

    if ( empty($result['key']) || $result['key'] !== Config::get('payu_merchant_key') )
      throw new InvalidDataException('Invalid response data');

    $result['SALT'] = Config::get('payu_merchant_salt');
    $hash = self::buildPayuHash($result, self::$RETURN_HASH_SEQUENCE);
    unset($result['SALT']);
    if ( empty($result['hash']) || $result['hash'] != $hash )
      throw new InvalidDataException('Tampered transaction results');

    $payuOrder = self::getOrderByMihpayid($result['mihpayid']);
    $status = $payuOrder['status'];
    $result['payuObject'] = $payuOrder;

    $payment = \PaymentQuery::create()
      ->filterByMember($member)
      ->filterByInvoiceNumber($result['txnid'])
      ->findOne();

    if ( !$payment )
      throw new InvalidDataException('Unknown payment id');

    if ( $payment->getStatus() != \Payment::STATUS_CREATED )
      throw new InvalidDataException('Payment already processed!');

    $payment->setGatewayPaymentId($result['mihpayid']);
    $payment->setMeta($result);

    if ( strtolower($status) !== 'success' ) {
      $payment->setStatus(\Payment::STATUS_FAILED);
    } else {
      $payment->setStatus(\Payment::STATUS_EXECUTED);
    }
    $payment->save($con);

    if ( strtolower($status) === 'success' )
      $member->setHadPaidWithPayment($payment, $con);

    return $payment;
  }

  static public function preparePayment(\Member $member, \PropelPDO $con) {
    $payment = \PaymentQuery::create()
      ->filterByMember($member)
      ->orderByDate(\Criteria::DESC)
      ->findOne($con);

    if ( !$payment || $payment->getStatus() == \Payment::STATUS_FAILED ) {
      $payment = \Payment::create($member, $con);
    } else if ( $payment->getStatus() == \Payment::STATUS_EXECUTED ) {
      throw new InvalidDataException('Member has executed payment');
    } else if ( $payment->getStatus() == \Payment::STATUS_CREATED ) {

      $payuOrder = self::getOrderByOrderId($payment->getInvoiceNumber());
      if ( $payuOrder && $payuOrder['status'] === 'success' ) {
        \Activity::exec(
          /*callable*/['\\Tbmt\\Payu', 'activity_setMemberPaid'],
          /*func args*/[
            $login,
            $payuOrder,
            $payment,
            $con
          ],
          /*activity.action*/\Activity::ACT_MEMBER_PAYMENT_EXEC,
          /*activity.member*/$login,
          /*activity.related*/null,
          $con,
          false
        );
      }
    }

    return $payment;
  }

  static public function activity_setMemberPaid($member, $payuOrder, $payment, $con) {
    $payment->setGatewayPaymentId($payuOrder['mihpayid']);
    $payment->setMeta($payuOrder);
    $payment->setStatus(\Payment::STATUS_EXECUTED);
    $payment->save($con);
    $member->setHadPaidWithPayment($payment, $con);

    return [
      'data' => $payment->toArray(),
      \Activity::ARR_RELATED_RETURN_KEY => $payment
    ];
  }

  static public function prepareFormData(\Member $member, \PropelPDO $con) {
    $payment = self::preparePayment($member, $con);
    if ( $payment->getStatus() === \Payment::STATUS_EXECUTED )
      return $payment;

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

    return hash('sha512', implode('|', $hash));
  }

  static public function getMemberPhone(\Member $member) {
    $phone = $member->getPhone();
    if ( empty($phone) )
      return '9123456789';

    return $phone;
  }

  static public function getOrderByOrderId($orderId) {
    $result = self::requestPayu([
      'command' => 'check_payment',
      'var1' => $orderId
    ]);

    if ( !isset($result['status']) || $result['status'] != 1 || empty($result['transaction_details'][$orderId]) ) {
      return null;
    }

    return $result['transaction_details'][$orderId];
  }

  static public function getOrderByMihpayid($orderId) {
    $result = self::requestPayu([
      'command' => 'check_payment',
      'var1' => $orderId
    ]);

    if ( !isset($result['status']) || $result['status'] != 1 || empty($result['transaction_details']) ) {
      throw new InvalidDataException('Order does not exist external');
    }

    return $result['transaction_details'];
  }

  static private function requestPayu(array $params) {
    $params = array_merge([
      'key' => Config::get('payu_merchant_key'),
      'salt' => Config::get('payu_merchant_salt'),
    ], $params);

    $params['hash'] = self::buildPayuHash($params, self::$API_HASH_SEQUENCE);

    // $c = curl_init();
    // curl_setopt($c, CURLOPT_URL, $wsUrl);
    // curl_setopt($c, CURLOPT_POST, 1);
    // curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($params));
    // curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 30);
    // curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
    // curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
    // return curl_exec($c);

    return self::getClient()->post(
      $params,
      Config::get('payu_base_url').'/merchant/postservice.php?form=2'
    )->openResultAsJson();
  }
}
