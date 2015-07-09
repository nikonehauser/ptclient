<?php

namespace Tbmt;

class Validator {

  const FILTER_NOT_EMPTY = 'filter_not_empty';

  static private $FILTER_ERROR_KEYS = [
    self::FILTER_NOT_EMPTY   => 'error.empty',
    \FILTER_VALIDATE_EMAIL   => 'error.email',
    \FILTER_VALIDATE_INT     => 'error.int',
    \FILTER_VALIDATE_BOOLEAN => 'error.accept'
  ];

  static public function getErrors($data, $filters) {
    $results = self::validateData($data, $filters);
    return self::localizeErrors($results, $filters);
  }

  static public function validateData($data, $filters) {
    $converted = [];
    foreach ( $filters as $key => $filter ) {
      $filterCode = $filter;
      if ( isset($filter['filter']) ) {
        $filterCode = $filter['filter'];
      }

      if ( is_string($filterCode) ) {
        $converted[$key] = [
          'filter' => FILTER_CALLBACK,
          'options' => 'self::'.$filterCode
        ];

      } else
        $converted[$key] = $filter;
    }

    return filter_var_array($data, $converted);
  }

  static public function localizeErrors($results, $filters) {
    $locales = [];
    $invalid = false;
    foreach ($results as $key => $valid) {
      if ( $valid === false ) {
        $invalid = true;

        $filter = $filters[$key];
        if ( isset($filter['errorLabel']) )
          $locales[$key] = Localizer::get($filter['errorLabel']);
        else {
          $filterCode = $filter;
          if ( isset($filter['filter']) )
            $filterCode = $filter['filter'];

          $locales[$key] = Localizer::get(self::$FILTER_ERROR_KEYS[$filterCode]);
        }
      } else
        $locales[$key] = '';
    }

    return $invalid === false ? false : $locales;
  }

  static public function filter_not_empty($v) {
    if ( $v == '' )
      return false;

    return $v;
  }

}

?>