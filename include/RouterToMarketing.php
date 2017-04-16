<?php

namespace Tbmt;

class RouterToMarketing {

  const KEY_MODULE = 'mod';
  const KEY_ACTION = 'act';

  static private $url;
  static private $assetsBase;
  static private $imagesBase;
  static private $jsBase;
  static private $cssBase;
  static private $publicResourceBase;
  static private $configsPath;

  static public function init($url, $basePath = '') {
    // if ( $basePath[strlen($basePath)-1] !== '/' )
    //   $basePath .= '/';

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
    self::$jsBase = $basePath.'assets/js/';
    self::$cssBase = $basePath.'assets/css/';
    self::$publicResourceBase = $basePath.'resources/public/';
    self::$configsPath = $basePath.'config/';
  }

  static public function toBase() {
    return self::$url;
  }

  static public function toModule($moduleName, $action = null, array $arrParams = null) {
    $params = [self::KEY_MODULE => $moduleName];
    if ( $action !== null )
      $params[self::KEY_ACTION] = $action;

    if ( $arrParams )
      $params = array_merge($params, $arrParams);

    return self::$url.'?'.http_build_query($params, null, '&');
  }

  static public function toAccountTab($tabName) {
    $params = [
      self::KEY_MODULE => 'account',
      self::KEY_ACTION => $tabName
    ];

    return self::$url.'?'.http_build_query($params, null, '&');

  }

  static public function toAsset($path) {
    return self::$assetsBase.$path;
  }

  static public function toImage($path) {
    return self::$imagesBase.$path;
  }

  static public function toJs($path) {
    return self::$jsBase.$path;
  }

  static public function toCss($path) {
    return self::$cssBase.$path;
  }

  static public function toPublicResource($path) {
    return self::$publicResourceBase.$path;
  }

  static public function toConfig($path) {
    return self::$configsPath.$path;
  }

  static public function toVideo(\Member $member = null) {
    $params = [];
    if ( $member )
      $params['tkn'] = $member->getHash();

    return self::toModule(
      Config::get('video.module'),
      Config::get('video.action'),
      $params
    ).'#'.Config::get('video.anchor');
  }

  static public function toSignup(\Member $referrer = null) {
    $arrParams = [];
    if ( $referrer )
      $arrParams['tkn'] = $referrer->getHash();

    return self::toModule('member', 'signup', $arrParams);
  }
}