<?php

function e($str) {
  return htmlentities($str, ENT_COMPAT | ENT_QUOTES | ENT_HTML401);
}

function ee($str) {
  echo htmlentities($str, ENT_COMPAT | ENT_QUOTES | ENT_HTML401);
}

?>