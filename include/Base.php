<?php

namespace Tbmt;

class Base {
  static public function encodeHtml($string) {
    return \str_replace(
      ["\r\n", "\r", "\n", "\t", '  ', '  '],
      ['<br />', '<br />', '<br />', ' ', '&nbsp; ', ' &nbsp;'],
      \htmlspecialchars($string, \ENT_HTML401 | \ENT_COMPAT | \ENT_SUBSTITUTE)
    );
  }
}

?>
