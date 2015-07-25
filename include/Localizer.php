<?php

namespace Tbmt;

class Localizer {

  const FALLBACK = 'en';

  const PARSE_ACCEPT_LANGUAGE = '/^([a-z]{1,8}(?:-[a-z]{1,8})*)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i';

  static private $instance = null;

  static private $arrData = array();

  static private $arrAccepted = [
    'en' => 'US'
  ];

  static private $loadedLang;

  private function __construct() {}

  static public function encodeHtml($string) {
    return \str_replace(
      ["\r\n", "\r", "\n", "\t", '  ', '  '],
      ['<br />', '<br />', '<br />', ' ', '&nbsp; ', ' &nbsp;'],
      \htmlspecialchars($string, \ENT_HTML401 | \ENT_COMPAT | \ENT_SUBSTITUTE)
    );
  }

  static public function load($localesPath, $lang = null) {
    if ( $lang === null || !isset(self::$arrAccepted[$lang]) )
      $lang = self::askBrowser(self::$arrAccepted);

    $lang = self::$loadedLang = $lang ? $lang[0] : self::FALLBACK;

    self::$arrData = include $localesPath.$lang.'-'.self::$arrAccepted[$lang].'.php';
  }

  static public function plain($strKey) {
    if (isset(self::$arrData[$strKey]))
      return self::$arrData[$strKey];

    return '%'.strtoupper($strKey);
  }

  /**
   * Gets a variable value
   *
   * @param string $strKey The path for the language variable (e.g. "messages.error.single")
   * @param bool $bolReturnPath If set the function will return the variable path, instead of FALSE
   * @return mixed
   */
  static public function get($strKey) {
    $data = self::$arrData;
    foreach (explode('.', $strKey) as $p) {
      if (isset($data[$p]))
        $data = $data[$p];
      else
        return '%'.strtoupper($strKey);
    }

    return $data;
  }

  /**
   * Gets a variable value and replaces placeholders contained in it
   *
   * @param string $strKey The path for the language variable (e.g. "messages.error.single")
   * @param array $arrReplace Associative array containing the placeholder variables
   * @return string If the path could not be resolved, the path will be returned instead
   */
  static public function insert($locale, $arrReplace, $encode = true, $encloseMarkup = null) {
    $arrSearch = array();
    $arrValues = array();
    foreach ($arrReplace as $key => $value) {
      if ( $encode )
        $value = self::encodeHtml($value);

      if ( $encloseMarkup ) {
        $value = str_replace('{_val_}', $value, $encloseMarkup);
      }

      $arrSearch[] = '{'.$key.'}';
      $arrValues[] = $value;
    }

    return str_replace($arrSearch, $arrValues, $locale);
  }

  static public function getInsert($strKey, $arrReplace, $encode = true) {
    $locale = self::get($strKey);
    return self::insert($locale, $arrReplace, $encode);
  }

  public static function askBrowser($arrAccepted) {
    $lang_variable = (
      isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])
      ? $_SERVER['HTTP_ACCEPT_LANGUAGE']
      : null
    );

    if ( empty($lang_variable) )
      return null;

    $accepted_languages = preg_split('/,\s*/', $lang_variable);
    $currentQuality = 0;
    $currentLang = null;

    $other = array();
    foreach ($accepted_languages as $accepted_language) {
      $res = preg_match(self::PARSE_ACCEPT_LANGUAGE, $accepted_language, $matches);

      if (!$res)
        continue;

      list($langCode, $countryCode) = array_pad(explode('-', $matches[1]), 2, null);
      if (isset($matches[2]))
        $lang_quality = (float) $matches[2];
      else
        $lang_quality = 1.0;

      if ( isset($arrAccepted[$langCode]) && $currentQuality > $lang_quality ) {
        $currentLang = [$langCode, $countryCode];
        $currentQuality = $lang_quality;
      }
    }

    return $currentLang;
  }

}

?>
