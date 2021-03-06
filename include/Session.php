<?php

namespace Tbmt;

final class Session {

  const KEY_SECRET_TOKEN = '__KEY_SECRET_TOKEN';
  const KEY_USER_ID = 'user_id';
  const KEY_SIGNUP_MSG = 'show_signup_msg';
  const KEY_PAYMENT_MSG = 'show_payment_msg';
  const KEY_LANG = 'lang';

  static private $user;

  static public function start() {
    \session_cache_limiter('nocache');
    \session_name(PROJECT_NAME);
    \session_set_cookie_params(
      0 // lifetime
    );
    \session_start();

    if ( \session_regenerate_id(false) === false ) {
      throw new \Exception('session_regenerate_id failed');
    }
  }

  static public function commit() {
    \session_write_close();
  }

  static public function terminate() {
    $_SESSION = [];
    \session_destroy();
  }

  static public function isLoggedIn() {
    return isset($_SESSION[self::KEY_USER_ID]) && self::getLogin() !== null;
  }

  static public function login($num, $pwd) {
    $query = \MemberQuery::create()
      ->filterByDeletionDate(null, \Criteria::ISNULL);

    if ( is_numeric($num) ) {
      $query->filterByNum($num);
    } else {
      $query->filterByEmail($num);
    }

    $member = $query->findOne();
    if ( !$member ) {
      $member = \MemberQuery::create()
        ->filterByDeletionDate(null, \Criteria::ISNULL)
        ->filterByEmail($num)
        ->findOne();
    }

    if ( !$member )
      return false;

    if ( !Cryption::verifyPassword($pwd, $member->getPassword()) )
      return false;

    self::$user = $member;
    self::set(self::KEY_USER_ID, $member->getId());

    return $member;
  }

  static public function setLogin(\Member $member) {
    self::set(self::KEY_USER_ID, $member->getId());
  }

  static public function getLogin() {
    if ( !self::$user && isset($_SESSION[self::KEY_USER_ID]) ) {
      self::$user = \MemberQuery::create()->findOneById($_SESSION[self::KEY_USER_ID]);
    }

    return self::$user;
  }

  static public function hasValidToken() {
    return isset($_SESSION[self::KEY_SECRET_TOKEN]) ? $_SESSION[self::KEY_SECRET_TOKEN] : false;
  }

  static public function setValidToken($tkn) {
    self::set(self::KEY_SECRET_TOKEN, $tkn);
  }

  static public function get($key, $type = TYPE_STRING, $default = false) {
    return Arr::init($_SESSION, $key, $type, $default);
  }

  static public function set($key, $value) {
    $_SESSION[$key] = $value;
  }

  static public function delete($key) {
    unset($_SESSION[$key]);
  }
}