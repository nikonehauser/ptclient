<?php

class FormValidationTest extends PHPUnit_Framework_TestCase {

  public function testEmptyUrl() {
    $res = filter_var_array([
      'email' => ''
    ], [
      'email' => ['filter' => FILTER_VALIDATE_INT]
    ], false);

    var_dump($res);
  }
}