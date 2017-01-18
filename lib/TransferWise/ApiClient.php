<?php

namespace TransferWise;

use Http\Client;
use Http\Config;

class ApiClient {

  private $client;
  private $clientid;
  private $clientsecret;

  public function __construct($clientid, $clientsecret, $authcode) {
    $config = new Config();
    $config->setSSLVerification(false);
    $config->setBasicAuthentication($clientid, $clientsecret);

    $this->client = new Client($config);

    $this->clientid = $clientid;
    $this->clientsecret = $clientsecret;
    $this->authcode = $authcode;
  }

  public function authorize() {
    // https://api.transferwise.com/oauth/authorize?response_type=code&client_id=sampleid&redirect_uri=https://example.com
    //
    // https://test-restgw.transferwise.com/oauth/authorize?response_type=code&client_id=f09420ea-3ede-406e-ada8-3baad070d5a3&redirect_uri=http://efesus.de
    //

// curl \
// -u 'client_id:client_secret' \
// -d 'grant_type=authorization_code' \
// -d 'client_id=sampleid' \
// -d 'code=[CODE]' \
// -d 'redirect_uri=https://example.com' \
// 'https://test-restgw.transferwise.com/oauth/token/oauth/token'

//   "transferwise.clientid" => "f09420ea-3ede-406e-ada8-3baad070d5a3",
//   "transferwise.clientsecret" => "52952974-6223-44ae-83bb-4f88610782469",
//   "transferwise.authcode" => "kwrPmh"

// curl -u 'f09420ea-3ede-406e-ada8-3baad070d5a3:52952974-6223-44ae-83bb-4f88610782469' -d 'grant_type=authorization_code' -d 'client_id=f09420ea-3ede-406e-ada8-3baad070d5a3' -d 'code=kwrPmh' -d 'redirect_uri=http://efesus.de' 'https://test-restgw.transferwise.com/oauth/token'

    $response = $this->client->request([
      'method' => 'post',
      'url' => 'https://test-restgw.transferwise.com/oauth/token',
      'body' => [
        'grant_type' => 'authorization_code',
        'client_id' => $this->clientid,
        'redirect_uri' => 'http://efesus.de',
        'code' => $this->authcode
      ]
    ]);

    return $response;
  }

  public function createTransfer() {

    // $response = $client->request('http://google.de');
    // echo $response->getHeaders();
    // echo $response->getContent();

  //   curl --request GET \
  // --url  \
  // --header 'accept: application/json' \
  // --header 'authorization: Bearer YOUR ACCESS TOKEN HERE'
    $response = $this->client->request([
      'url' => 'https://test-restgw.transferwise.com/v1/me',
      'headers' => [
        'accept' => 'application/json',
        'authorization' => 'Bearer 1Y04pF'
      ]
    ]);

    return $response;
  }
}

// WobMZs

// 1Y04pF