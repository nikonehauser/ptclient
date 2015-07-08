<?php

namespace Tbmt;

class Router {

  const KEY_MODULE = 'mod';
  const KEY_ACTION = 'act';

  static private $url;

  static public function init($url) {
    self::$url = $url;
  }

  static public function toBase() {
    return self::$url;
  }

  static public function toModule($moduleName, $action = null) {
    $params = [self::KEY_MODULE => $moduleName];
    if ( $action !== null )
      $params[self::KEY_ACTION] = $action;

    return self::$url.'?'.http_build_query($params, null, '&');
  }
}