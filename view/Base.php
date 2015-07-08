<?php

namespace Tbmt\view;

class Base {

  protected $var;
  protected $varsDef = [];

  public function __construct() {
    $this->init();
  }

  protected function init() {}

  protected function getFileExtension() {
    return '.html';
  }

  protected function getViewName() {
    return str_replace('Tbmt\\', '', get_class($this));
  }

  public function render(array $params = array()) {
    return $this->renderFile(BASE_DIR.$this->getViewName().$this->getFileExtension(), $params);
  }

  public function renderFile($filePath, array $params = array()) {
    $this->var = \Tbmt\Arr::initMulti($params, $this->varsDef);

    ob_start();
    try {
      include $filePath;
    } finally {
      $viewContent = ob_get_clean();
    }

    return $viewContent;
  }
}

?>
