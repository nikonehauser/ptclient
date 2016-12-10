<?php

class CustomerTestProtocolsTest extends Tbmt_Tests_DatabaseTestCase {

  public function test_makePaypalPayment() {
    $payment = \Tbmt\Payments::forPayPal();
    print_r($payment);
  }

}
