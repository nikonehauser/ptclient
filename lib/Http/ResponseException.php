<?php

namespace Http;

class ResponseException extends \Exception {
  private $response;

  public function __construct(Response $response, $message = '') {
    $this->response = $response;
    parent::__construct($message);
  }

  public function getResponse() {
    return $this->response;
  }

}

?>