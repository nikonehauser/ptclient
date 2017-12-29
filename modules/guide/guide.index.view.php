<?php

namespace Tbmt\view;

class GuideIndex extends Base {

  public function render(array $params = array()) {
    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.guide.html',
      $params
    );
  }

}