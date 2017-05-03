<?php

namespace Tbmt;

use TransferWise\ApiClient;

class Payouts {



  static public function transfer() {
    self::getClient()->createTransfer();
  }

  //
  // Private
  //
  // *****************************************

  static private $client;

  static private function getClient() {
    if ( self::$client )
      return self::$client;

    self::$client = new ApiClient();
    return self::$client;
  }
}
