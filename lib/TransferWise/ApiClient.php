<?php

namespace TransferWise;

use Http\Client;
use Http\Config;

class ApiClient {

  public function __construct() {

  }

  public function createTransfer() {
    $config = new Config();
    $config->setSSLVerification(false);
    $client = new Client($config);

    // $response = $client->request('http://google.de');
    // echo $response->getHeaders();
    // echo $response->getContent();

  //   curl --request GET \
  // --url  \
  // --header 'accept: application/json' \
  // --header 'authorization: Bearer YOUR ACCESS TOKEN HERE'
    $response = $client->request([
      'url' => 'https://test-restgw.transferwise.com/v1/me',
      'headers' => [
        'accept' => 'application/json',
        'authorization' => 'Bearer 1Y04pF'
      ]
    ]);

    echo $response->getHeaders();
    echo $response->getContent();
  }
}

// WobMZs

// 1Y04pF