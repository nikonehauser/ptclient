<?php

namespace Http;

class Config {

  private $headers = [];
  private $contentType = 'application/x-www-form-urlencoded';
  private $httpAuthorization;
  private $SSLVerification = true;
  private $timeout = 100;

  public function __construct() {}

  public function setContentType($contentType) {
    $this->contentType = $contentType;
    return $this;
  }

  public function getContentType() {
    return $this->contentType;
  }

  public function setBasicAuthentication($user, $password) {
    $this->httpAuthorization = 'Basic '.base64_encode($user.':'.$password);
    return $this;
  }

  public function setBearerAuthentication($token) {
    $this->httpAuthorization = 'Bearer '.$token;
    return $this;
  }

  public function getAuthentication() {
    return $this->httpAuthorization;
  }

  public function setHeaders(array $headers) {
    $this->headers = $headers;
    return $this;
  }

  public function getHeaders() {
    return $this->headers;
  }

  public function setTimeout(/*int seconds*/$timeout) {
    $this->timeout = $timeout;
    return $this;
  }

  public function getTimeout() {
    return $this->timeout;
  }

  public function setSSLVerification($SSLVerification) {
    $this->SSLVerification = $SSLVerification;
    return $this;
  }

  public function getSSLVerification() {
    return $this->SSLVerification;
  }
}