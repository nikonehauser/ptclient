<?php

namespace Http;

use Http\Response;
use Http\Config;

use Exception;

class Client {

  static private $bodyLessMethods = ['GET', 'HEAD'];

  /**
   * Expecting a monolog logger instance.
   *
   * @var Monolog/Logger
   */
  private $logger;

  private $config;

  public function __construct(Config $config) {
    $this->config = $config;
  }

  /* --------------- Setting functions --------------- */

  public function setLogger($logger) {
    $this->logger = $logger;
  }

  /* --------------- Request functions --------------- */

  public function request($data) {
    if ( is_string($data) ) {
      $url = $data;
      $data = null;
    } else {
      $url = isset($data['url']) ? $data['url'] : null;
    }

    if ( !$url )
      throw new Exception('InvalidArgumentException: $url is required!');

    $params = isset($data['body']) ? $data['body'] : null;
    $method = isset($data['method']) ? strtoupper((string)$data['method']) : 'GET';
    $headers = isset($data['headers']) ? $data['headers'] : null;
    $query = parse_url($url, PHP_URL_QUERY);

    // merge headers with config headers
    $headers = $headers
      ? array_merge($this->config->getHeaders(), $headers)
      : $this->config->getHeaders();

    // apply basic http authentication from config
    $auth = $this->config->getBasicAuthentication();
    if ( $auth && !isset($headers['Authorization']) )
      $headers['Authorization'] = $auth;

    // handle content type
    if ( isset($headers['Content-Type']) ) {
      $contentType = $headers['Content-Type'];
    } else {
      // omit for GET, DELETE?
      $headers['Content-Type'] = $contentType = $this->config->getContentType();
    }

    if ( in_array($method, self::$bodyLessMethods, true) ) {
      $url .= ( $query ? '&' : '?' ).(
        is_array($params)
        ? http_build_query($params, null, '&')
        : $params
      );

      unset($headers['Content-Type']);
      $params = null;

    } else if ( !$params ) {

    } elseif ( strpos($contentType, 'application/json') !== false ) {
      $params = json_encode($params);

    } elseif ( strpos($contentType, 'text/plain') !== false ) {
      $params = (string)$params;

    } else {
      $params = http_build_query($params, null, '&');
    }

    if ( $params )
      $headers['Content-Length'] = strlen($params);

    $readHeaders = true;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_TIMEOUT, $this->config->getTimeout());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    if ( $readHeaders === true ) {
      curl_setopt($ch, CURLOPT_HEADER, true);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    }

    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getCurlHeaders($headers));

    // disable SSL verification, if needed
    if ($this->config->getSSLVerification() === false) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    }

    //Execute Curl Request
    $result = curl_exec($ch);
    if ( $result === false )
      throw new Exception(curl_error($ch));
    //Retrieve Response Status
    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ( $readHeaders === true ) {
      // Get Request and Response Headers
      $requestHeaders = curl_getinfo($ch, CURLINFO_HEADER_OUT);
      //Using alternative solution to CURLINFO_HEADER_SIZE as it throws invalid number when called using PROXY.
      if (function_exists('mb_strlen')) {
          $responseHeaderSize = mb_strlen($result, '8bit') - curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
          $responseHeaders = mb_substr($result, 0, $responseHeaderSize, '8bit');
          $result = mb_substr($result, $responseHeaderSize, mb_strlen($result), '8bit');
      } else {
          $responseHeaderSize = strlen($result) - curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
          $responseHeaders = substr($result, 0, $responseHeaderSize);
          $result = substr($result, $responseHeaderSize);
      }
    } else {
      $responseHeaders = '<response headers disabled>';
    }

    // $this->logger->debug("Request Headers \t: " . str_replace("\r\n", ", ", $requestHeaders));
    // $this->logger->debug(($data && $data != '' ? "Request Data\t\t: " . $data : "No Request Payload") . "\n" . str_repeat('-', 128) . "\n");
    // $this->logger->info("Response Status \t: " . $httpStatus);
    // $this->logger->debug("Response Headers\t: " . str_replace("\r\n", ", ", $responseHeaders));

    //Close the curl request
    curl_close($ch);

    // if ($httpStatus < 200 || $httpStatus >= 300) {
    //     $ex = new PayPalConnectionException(
    //         $this->httpConfig->getUrl(),
    //         "Got Http response code $httpStatus when accessing {$this->httpConfig->getUrl()}.",
    //         $httpStatus
    //     );
    //     $ex->setData($result);
    //     $this->logger->error("Got Http response code $httpStatus when accessing {$this->httpConfig->getUrl()}. " . $result);
    //     $this->logger->debug("\n\n" . str_repeat('=', 128) . "\n");
    //     throw $ex;
    // }

    $response = new Response(
      $url,
      $httpStatus,
      $responseHeaders,
      $result
    );

    if ( $this->logger )
      $this->logger->debug('request -> '.print_r(['url' => $strUrl, 'ctx' => $arrContentOptions, 'response' => $response->toString()], true));

    return $response;
  }

  private function getCurlHeaders($headers) {
    $flat = [];

    foreach ($headers as $key => $val) {
        $flat[] = "$key: $val";
    }

    return $flat;
  }
}

?>