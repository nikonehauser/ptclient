<?php

namespace Tbmt\view;

class AboutIndex extends Base {

  public function render(array $params = array()) {
    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.about.html',
      $params
    );
  }

}