<?php

namespace Tbmt;

final class Session {

  const KEY_USER_ID = 'user_id';

  static private $user;

  static public function start() {
    \session_cache_limiter('');
    \session_name(PROJECT_NAME);
    \session_start();

    \session_regenerate_id(true);
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
    $member = \MemberQuery::create()->findOneByNum($num);
    if ( !$member )
      return false;

    if ( !Cryption::verifyPassword($pwd, $member->getPassword()) )
      return false;

    self::$user = $member;
    self::set(self::KEY_USER_ID, $member->getId());

    return $member;
  }

  static public function getLogin() {
    if ( !self::$user && isset($_SESSION[self::KEY_USER_ID]) ) {
      self::$user = \MemberQuery::create()->findOneById($_SESSION[self::KEY_USER_ID]);
    }

    return self::$user;
  }

  static public function get($key, $type = TYPE_STRING, $default = false) {
    return Arr::init($_SESSION, $key, $type, $default);
  }

  static public function set($key, $value) {
    $_SESSION[$key] = $value;
  }
}