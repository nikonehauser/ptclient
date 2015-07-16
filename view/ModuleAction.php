<?php

namespace Tbmt\view;

class ModuleAction extends Base {
  public function render(array $params = array()) {
    return $this->renderFile(MODULES_DIR.$this->moduleName.DIRECTORY_SEPARATOR."$this->actioName.$this->moduleName".$this->getFileExtension(), $params);
  }
}

?>
