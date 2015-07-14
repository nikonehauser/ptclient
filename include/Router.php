<?php

namespace Tbmt;

class Router {

  const KEY_MODULE = 'mod';
  const KEY_ACTION = 'act';

  static private $url;
  static private $assetsBase;
  static private $imagesBase;

  static public function init($url, $basePath = '') {
    // TODO
    // For $url to either end to valid file like:
    //   http://localhost/pt/index.php
    // or to be valid direcotry WITH trailing slash
    // "http://localhost/pt/
    //
    // @see http://serverfault.com/questions/587002/apache2-301-redirect-when-missing-at-the-end-of-directory-in-the-url
    self::$url = $url;
    self::$assetsBase = $basePath.'assets/';
    self::$imagesBase = $basePath.'assets/images/';
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

  static public function toAccountTab($tabName) {
    $params = [
      self::KEY_MODULE => 'account',
      self::KEY_ACTION => $tabName
    ];

    return self::$url.'?'.http_build_query($params, null, '&');

  }

  static public function toImage($path) {
    return self::$imagesBase.$path;
  }
}