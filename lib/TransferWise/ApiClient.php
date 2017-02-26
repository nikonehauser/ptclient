<?php

namespace TransferWise;

use Http\Client;
use Http\Config;
use Http\ResponseException;

class ApiClient {

  private $client;
  private $clienturl;
  private $apiurl;
  private $clientid;
  private $clientsecret;
  private $redirectUrl;
  private $authcode;
  private $access_token;
  private $refresh_token;

  public function __construct($clienturl, $clientid, $clientsecret, $redirectUrl) {
    $config = new Config();
    $config->setSSLVerification(false);

    $config->setHeaders([
      'accept' => 'application/json',
      'Content-Type' => 'application/json'
    ]);

    $config->setBasicAuthentication($clientid, $clientsecret);

    $this->client = new Client($config);

    $this->clienturl = $clienturl;
    if ( !preg_match('/\/$/', $this->clienturl) )
      $this->clienturl = $this->clienturl.'/';

    $this->apiurl = $this->clienturl.'v1/';

    $this->clientid = $clientid;
    $this->clientsecret = $clientsecret;
    $this->redirectUrl = $redirectUrl;
  }

  public function setLogger($logger) {
    $this->logger = $logger;
    // $this->client->setLogger($logger);
  }

  public function getOauthUrl() {
    return $this->clienturl.'oauth/authorize?'.http_build_query([
      'response_type' => 'code',
      'client_id' => $this->clientid,
      'redirect_uri' => $this->redirectUrl,
    ], null, '&');
  }

  public function manageAuthorization($authcode, $access_token, $refresh_token, $expirationTime) {
    // #1 get authorization code
    //
    // https://test-restgw.transferwise.com/oauth/authorize?response_type=code&client_id=f09420ea-3ede-406e-ada8-3baad070d5a3&redirect_uri=http://www.betterliving.social/
    //
    // #1 Response:
    // http://www.betterliving.social/?code=pVgE4z
    //

    // curl \
    // -u 'client_id:client_secret' \
    // -d 'grant_type=authorization_code' \
    // -d 'client_id=sampleid' \
    // -d 'code=[CODE]' \
    // -d 'redirect_uri=https://example.com' \
    // 'https://test-restgw.transferwise.com/oauth/token'

    //   "transferwise.clientid" => "f09420ea-3ede-406e-ada8-3baad070d5a3",
    //   "transferwise.clientsecret" => "52952974-6223-44ae-83bb-4f88610782469",
    //   "transferwise.authcode" => "pVgE4z"

    // curl -u 'f09420ea-3ede-406e-ada8-3baad070d5a3:52952974-6223-44ae-83bb-4f88610782469' -d 'grant_type=authorization_code' -d 'client_id=f09420ea-3ede-406e-ada8-3baad070d5a3' -d 'code=2jxeg7' -d 'redirect_uri=http%3A%2F%2Fwww.betterliving.social%3Fmod%3Dpay%26act%3Dindex' 'https://test-restgw.transferwise.com/oauth/token'


    //
    // {"access_token":"8e71e583-3e88-485b-8fc7-d650b204b41a","token_type":"bearer","refresh_token":"b71f78b8-38e8-49f2-b24f-601e6fc4ef96","expires_in":630719999,"scope":"transfers"}


    // Examples #1 - get account requirements
    // curl -X GET -H "Authorization: Bearer 8e71e583-3e88-485b-8fc7-d650b204b41a" "https://test-restgw.transferwise.com/v1/account-requirements?source=EUR&target=GBP&targetAmount=100"
    //
    //
    // Examples #2 - get profiles
    // curl -X GET -H "Authorization: Bearer 8e71e583-3e88-485b-8fc7-d650b204b41a" "https://test-restgw.transferwise.com/v1/profiles"

    if ( !$access_token && $authcode ) {
      $response = $this->client->request([
        'method' => 'POST',
        'url' => $this->clienturl.'oauth/token',
        'headers' => [
          'Content-Type' => 'application/x-www-form-urlencoded'
        ],
        'body' => [
          'grant_type' => 'authorization_code',
          'client_id' => $this->clientid,
          'redirect_uri' => $this->redirectUrl,
          'code' => $authcode
        ]
      ]);

      $json = $this->validateJsonResponse($response);
      if ( isset($json['access_token'], $json['refresh_token']) ) {
        $access_token = $json['access_token'];
        $refresh_token = $json['refresh_token'];
        $expirationTime = time() + intval($json['expires_in']);
      } else {
        throw new \Exception('Unexpected response retrieving transferwise access_token.');
      }

    } else if ( $refresh_token && $expirationTime < time() ) {
      $response = $this->client->request([
        'method' => 'POST',
        'url' => $this->clienturl.'oauth/token',
        'headers' => [
          'Content-Type' => 'application/x-www-form-urlencoded'
        ],
        'body' => [
          'grant_type' => 'refresh_token',
          'refresh_token' => $refresh_token
        ]
      ]);

      $json = $this->validateJsonResponse($response);
      if ( isset($json['access_token'], $json['refresh_token']) ) {
        $access_token = $json['access_token'];
        $refresh_token = $json['refresh_token'];
        $expirationTime = time() + intval($json['expires_in']);
      } else {
        throw new \Exception('Unexpected response refreshing transferwise access_token.');
      }

    }

    $this->access_token = $access_token;
    $this->refresh_token = $refresh_token;
    $this->client->getConfig()->setBearerAuthentication($access_token);
    return [$access_token, $refresh_token, $expirationTime];
  }

  public function ensureRequiredProfiles() {
    list($personal, $business) = $this->getProfiles();

    $personal = $this->setProfile(
      $personal ? $personal['id'] : null,
      'personal',
      \Tbmt\Config::get('transferwise.profile.personal.details', \Tbmt\TYPE_ARRAY),
      \Tbmt\Config::get('transferwise.profile.personal.address', \Tbmt\TYPE_ARRAY)
    );

    $business = $this->setProfile(
      $business ? $business['id'] : null,
      'business',
      \Tbmt\Config::get('transferwise.profile.business.details', \Tbmt\TYPE_ARRAY),
      \Tbmt\Config::get('transferwise.profile.business.address', \Tbmt\TYPE_ARRAY)
    );

    return [$personal, $business];
  }

  private function setProfile($id, $type, $data, array $address) {
    $body = [
      'type' => $type,
      'details' => $data
    ];

    if ( $id ) {
      $method = 'put';
      $body['id'] = $id;
    } else {
      $method = 'post';
    }

    $profile = $this->validateJsonResponse($this->client->request([
      'method' => $method,
      'url' => $this->apiurl.'profiles',
      'body' => $body
    ]));

    $this->log('SET PROFILE BODY:', $body, 'SET PROFILE RESULT:', $profile);

    $this->setProfileAddress($profile['id'], $address);
    return $profile;
  }

  private function setProfileAddress($profileId, array $address) {
    $body = [
      'profile' => $profileId,
      'details' => $address
    ];

    $address = $this->validateJsonResponse($this->client->request([
      'method' => 'post',
      'url' => $this->apiurl.'addresses',
      'body' => $body
    ]));

    $this->log(
      'SET PROFILE ADDRESS BODY:',
      $body,
      'SET PROFILE ADDRESS RESULT:',
      $address
    );

    return $address;
  }

  private function getProfiles() {
    $response = $this->client->request([
      'method' => 'get',
      'url' => $this->apiurl.'profiles'
    ]);

    $profiles = $this->validateJsonResponse($response);

    $this->log(
      'GET PROFILES:',
      $profiles
    );

    $personal = null;
    $business = null;
    if ( empty($profiles) ) {

    } else if ( count($profiles) > 2 ) {
      throw new \Exception('Received more than 2 profiles!');
    }

    foreach ( $profiles as $key => $data ) {
      if ( strtolower($data['type']) === 'business' )
        $business = $data;
      else if ( strtolower($data['type']) === 'personal' )
        $personal = $data;
      else
        throw new \Exception('Unknown profile type: '.$data['type']);
    }

    return [$personal, $business];
  }

  public function ensureQuoteAccount(\Member $member, $profileId, $quote, $targetCountry) {
    $accountId = $member->getTransferwiseId();
    $sync = $member->getTransferwiseSync();

    $body = [
      'profile' => $profileId,
      'accountHolderName' => $member->getBankRecipient(),
      'currency' => $quote['target'],
      'country' => $targetCountry,
      'type' => 'indian',
      // 'business': '589170615283802112',
      'details' => [
        'legalType' => 'PRIVATE',
        'ifscCode' => $member->getBic(),
        'accountNumber' => $member->getIban()
      ]
    ];

    $errorFetchSynchronized = false;

    if ( $accountId && $sync ) {
      try {
        $account = $this->validateJsonResponse($this->client->request([
          'method' => 'post',
          'url' => $this->apiurl.'accounts/'.$accountId,
        ]));

        $this->log('RETRIEVE ACCOUNT:', $body);

        $body['fetchSynchronized'] = true;

        $this->log('FETCHED ACCOUNT:', $account);

        return [$body, $account, null];
      } catch (\Exception $e) {
        $errorFetchSynchronized = $e->__toString();
      }
    }

    // We do not dynamically receive account requirements all the time
    // $requirements = $this->getAccountRequirements($quote);

    $this->log('CREATE ACCOUNT:', $body);

    $account = null;
    $exception = null;
    try {
      $account = $this->validateJsonResponse($this->client->request([
        'method' => 'post',
        'url' => $this->apiurl.'accounts',
        'body' => $body
      ]));
    } catch (\Exception $e) {
      $exception = $e;
    }

    $member->setTransferwiseSync(1);
    $member->save();

    if ( $errorFetchSynchronized !== false )
      $body['fetchSynchronized'] = $errorFetchSynchronized;

    return [$body, $account, $exception];
  }

  private function getAccountRequirements($quote) {
    $body = [
      'quote' => $quote['id']
      // 'source' => $sourceCurrency,
      // 'target' => $targetCurrency,
    ];

    $this->log('GET ACCOUNT REQUIREMENTS:', $body);

    $requirements = $this->validateJsonResponse($this->client->request([
      'method' => 'get',
      'url' => $this->apiurl.'account-requirements',
      'body' => $body
    ]));

    $this->log('GET ACCOUNT REQUIREMENTS RESULT:', $requirements);

    return $requirements;
  }

  public function createQuote($profileId, $sourceCurrency, $targetCurrency, $amount) {
    $body = [
      'profile' => $profileId,
      'source' => $sourceCurrency,
      'target' => $targetCurrency,
      'sourceAmount' => $amount,
      'rateType' => 'FIXED'
    ];

    $quote = null;
    $exception = null;
    try {
      $quote = $this->validateJsonResponse($this->client->request([
        'method' => 'post',
        'url' => $this->apiurl.'quotes',
        'body' => $body
      ]));
    } catch (\Exception $e) {
      $exception = $e;
    }

    $this->log('CREATE QUOTE:', $body, $quote, $exception);

    return [$body, $quote, $exception];
  }

  public function createTransfer($targetAccountId, $quoteId) {
    $body = [
      'targetAccount' => $targetAccountId,
      'quote' => $quoteId,
      'details' => [
        'reference' => 'bla'
      ]
    ];

    $transfer = null;
    $exception = null;
    try {
      $transfer = $this->validateJsonResponse($this->client->request([
        'method' => 'post',
        'url' => $this->apiurl.'transfers',
        'body' => $body
      ]));
    } catch (\Exception $e) {
      $exception = $e;
    }

    $this->log('CREATE TRANFER:', $body, $transfer, $exception);

    return [$body, $transfer, $exception];
  }

  private function validateJsonResponse($response) {
    if ( $response->isSuccess() )
      return $response->getJSONContent();

    // $this->log($response->toString());

    throw new ResponseException($response, 'Invalid TransferWise Response: '.$response->toString());
  }

  private function log() {
    if ( !$this->logger )
      return;

    $this->logger->debug(null, ...func_get_args());
  }
}
