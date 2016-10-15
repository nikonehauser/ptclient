<?php

namespace Tbmt\view;

class PublicError extends Base {

  protected $varsDef = [
    'name'    => \Tbmt\TYPE_STRING,
    'message' => \Tbmt\TYPE_STRING,
    'stack'   => \Tbmt\TYPE_STRING
  ];

  static public function fromPublicException(\Tbmt\PublicException $e) {
    return (new self())->render([
      'name' => $e->getName(),
      'message' => $e->getMessage()
    ]);
  }
}