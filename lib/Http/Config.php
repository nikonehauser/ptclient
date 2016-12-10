<?php

namespace Http;

class Config {

  private $headers = [];
  private $contentType = 'application/x-www-form-urlencoded';
  private $basicAuthorization;
  private $SSLVerification = true;
  private $timeout = 10;

  public function __construct() {}

  public function setContentType($contentType) {
    $this->contentType = $contentType;
    return $this;
  }

  public function getContentType() {
    return $this->contentType;
  }

  public function setBasicAuthentication($user, $password) {
    $this->basicAuthorization = 'Basic '.base64_encode($user.':'.$password);
    return $this;
  }

  public function getBasicAuthentication() {
    return $this->basicAuthorization;
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