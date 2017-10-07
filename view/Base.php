<?php

namespace Tbmt\view;

class Base {

  protected $var;
  protected $varsDef = [];

  protected $i18nCommon;
  protected $i18nView;

  protected $moduleName;
  protected $actionName;

  public function __construct($moduleName = null, $actioName = null) {
    $this->moduleName = $moduleName;
    $this->actioName = $actioName;
    $this->init();
  }

  protected function init() {
    $this->i18nCommon = \Tbmt\Localizer::get('common');
    if ( $this->moduleName && $this->actioName )
      $this->i18nView = \Tbmt\Localizer::get("view.$this->moduleName.$this->actioName");
  }

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
    $filePath = str_replace('\\', DIRECTORY_SEPARATOR, $filePath);

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
