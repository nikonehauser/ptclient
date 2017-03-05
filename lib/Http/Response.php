<?php

namespace Http;

class Response {
  private $method;
  private $content;
  private $body;
  private $headers;
  private $requestHeaders;
  private $statusCode;
  private $url;
  public function __construct($method, $url, $body, $statusCode, $responseHeaders, $requestHeaders, $content) {
    $this->method = $method;
    $this->content = $content;
    $this->body = $body;
    $this->headers = $responseHeaders;
    $this->requestHeaders = $requestHeaders;
    $this->url = $url;
    $this->statusCode = $statusCode;
  }

  public function getStatusCode() {
    return $this->statusCode;
  }

  // public function getStatus() {
  //   return $this->headers[0];
  // }

  public function getParams() {
    return $this->url;
  }

  public function getUrl() {
    return $this->url;
  }

  public function isSuccess() {
    $code = (int) $this->getStatusCode();
    return $code >= 200 && $code < 300;
  }

  public function getContent() {
    return $this->content;
  }

  public function getJSONContent() {
    $res = json_decode($this->content, true);
    $err = json_last_error();
    if ( $err === JSON_ERROR_NONE )
      return $res;

    throw new ResponseException($this, 'Error decoding json: '.json_last_error_msg());
  }

  public function getHeaders() {
    return $this->headers;
  }

  public function toString() {
    return print_r([
      'URL' => strtoupper($this->method).' '.$this->url,
      'requestHeaders' => $this->requestHeaders,
      'body' => $this->body,
      'responseHeaders' => $this->headers,
      'response' => $this->content
    ], true);
  }
}

?>
