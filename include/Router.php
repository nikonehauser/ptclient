<?php

namespace Tbmt;

class Router {

  const KEY_MODULE = 'mod';
  const KEY_ACTION = 'act';

  static private $url;

  static public function init($url) {
    // TODO
    // For $url to either end to valid file like:
    //   http://localhost/pt/index.php
    // or to be valid direcotry WITH trailing slash
    // "http://localhost/pt/
    //
    // @see http://serverfault.com/questions/587002/apache2-301-redirect-when-missing-at-the-end-of-directory-in-the-url
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