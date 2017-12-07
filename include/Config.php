<?php

namespace Tbmt;

class Config {
  static protected $struct;

  static public function load($path) {
    if ( self::$struct )
      throw new \Exception('InvalidOperationException: You can not load config twice.');

    self::$struct = include $path;
  }

  static public function get($name, $type = TYPE_STRING, $default = false) {
    return Arr::init(self::$struct, $name, $type, $default);
  }

  static public function set($name, $value) {
    self::$struct[$name] = $value;
  }
}

?>