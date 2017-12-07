<?php

namespace Tbmt\view;

class GuideHandleresult extends Base {

  protected $varsDef = [
    'member' => \Tbmt\TYPE_STRING,
    'resultmessage' => \Tbmt\TYPE_STRING,
    'resultdesc' => \Tbmt\TYPE_STRING,
    'resultstack' => \Tbmt\TYPE_STRING,
  ];

  public function render(array $params = array()) {
    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'handleresult.guide.html',
      $params
    );
  }

}