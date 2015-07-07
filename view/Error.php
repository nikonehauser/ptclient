<?php

namespace Tbmt\view;

class Error extends Base {
  protected $varsDef = [
    'name'    => \Tbmt\TYPE_STRING,
    'message' => \Tbmt\TYPE_STRING,
    'stack'   => \Tbmt\TYPE_STRING
  ];

  static public function fromException(\Exception $e) {
    $code = $e->getCode();
    return (new self())->render([
      'name' => get_class($e).( $code ? ' - '.$code : '' ),
      'message' => $e->getMessage(),
      'stack' => \Tbmt\Config::get('devmode', \Tbmt\TYPE_BOOL, false) ?
        $e->getTraceAsString() :
        ''
    ]);
  }
}