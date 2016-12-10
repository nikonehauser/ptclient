<?php

namespace Http;

class Response {
  private $content;
  private $headers;
  private $status;
  private $url;
  public function __construct($url, $statusCode, $headers, $content) {
    $this->content = $content;
    $this->headers = $headers;
    $this->url = $url;
    $this->status = $statusCode;
  }

  public function getStatusCode() {
    return $this->status[1];
  }

  public function getStatusText() {
    return $this->status[2];
  }

  // public function getStatus() {
  //   return $this->headers[0];
  // }

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

  public function getHeaders() {
    return $this->headers;
  }

  public function toString() {
    return print_r([$this->headers, $this->content], true);
  }
}

?>