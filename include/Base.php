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

class Base {
  function encodeHtml($string) {
    return \str_replace(
      ["\r\n", "\r", "\n", "\t", '  ', '  '],
      ['<br />', '<br />', '<br />', ' ', '&nbsp; ', ' &nbsp;'],
      \htmlspecialchars($string, \ENT_HTML401 | \ENT_COMPAT | \ENT_SUBSTITUTE)
    );
  }
}

?>
