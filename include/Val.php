<?php

namespace Tbmt;

const TYPE_STRING    = 0;
const TYPE_STRING_NE = 1;
const TYPE_TRIM      = 2;
const TYPE_TRIM_NE   = 3;
const TYPE_BOOL      = 4;
const TYPE_INT       = 5;
const TYPE_FLOAT     = 6;
const TYPE_KEY       = 7;
const TYPE_ARRAY     = 8;
const TYPE_INDEX     = 9;
const TYPE_UNIQUE    = 10;
const TYPE_JSON      = 11;

class Val {

  static public function init($value, $type = TYPE_STRING, $default = false) {
    switch ($type) { // Prioritized
      case TYPE_STRING:
        break;

      case TYPE_KEY:
        return \is_numeric($value) ? (int) +$value : ($default === false ? null : $default);

      case TYPE_INT:
        return \is_numeric($value) ? (int) +$value : ($default === false ? 0 : $default);

      case TYPE_ARRAY:
        return \is_array($value) ? $value : ($default === false ? [] : $default);

      case TYPE_FLOAT:
        return \is_numeric($value) ? (float) $value : ($default === false ? 0 : $default);

      case TYPE_BOOL:
        return $value === null ? $default : (bool) $value;

      case TYPE_STRING_NE:
        return ( $value = (string) $value ) === '' ? ($default === false ? '' : $default) : $value;

      case TYPE_JSON:
        return \is_array( $value = \json_decode($value, true) ) ? $value : ($default === false ? [] : $default);

      case TYPE_INDEX:
        if (\is_array($value)) {
          $index = [];

          foreach ($value as $array_value)
            if (\is_numeric($array_value))
              $index[] = (int) +$array_value;

          return $index;
        }

        return $default === false ? [] : $default;

      case TYPE_UNIQUE:
        if (\is_array($value)) {
          $unique = [];

          foreach ($value as $array_value)
            $unique[\trim($array_value)] = true;

          unset($unique['']);
          return \array_keys($unique);
        }

        return $default === false ? [] : $default;

      case TYPE_TRIM_NE:
        return ( $value = \trim($value) ) === '' ? ($default === false ? '' : $default) : $value;
    }

    return $value === null ? ($default === false ? '' : $default) : \trim($value);
  }
}

class Arr {
  static public function init($array, $key, $type = TYPE_STRING, $default = false) {
    return Val::init(isset($array[$key]) ? $array[$key] : null, $type, $default);
  }

  static public function initList($array, $keys) {
    $list = [];

    foreach ($keys as $key => $definition) {
      if (isset($definition[0]))
        list($type, $default) = $definition;
      else {
        $type = $definition;
        $default = false;
      }

      $list[] = Val::init(isset($array[$key]) ? $array[$key] : null, $type, $default);
    }

    return $list;
  }

  static public function initMulti($array, $keys) {
    foreach ($keys as $key => &$definition) {
      if (isset($definition[0]))
        list($type, $default) = $definition;
      else {
        $type = $definition;
        $default = false;
      }

      $definition = Val::init(isset($array[$key]) ? $array[$key] : null, $type, $default);
    }

    return $keys;
  }
}

?>