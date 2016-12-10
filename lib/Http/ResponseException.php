<?php

namespace Zeyosinc\Http;

class ResponseException extends \Exception {
  private $response;

  public function __construct(Response $response, $message = '') {
    $this->response = $response;
    parent::__construct($message.$this->buildMessage($response));
  }

  public function buildMessage(Response $response) {
    $str = '';
    $str .= $response->getStatus();
    $str .= "\nURL:\n".$response->getUrl();
    $str .= "\nPARAMS:\n".var_export($response->getParams(), true);
    $str .= "\nRESPONSE HEADER:\n".var_export($response->getHeaders(), true);
    $str .= "\nRESPONSE CONTENT:\n".substr($response->getContent(), 0, 200);
    return $str;
  }

  public function getResponse() {
    return $this->response;
  }

}

?>