<?php

namespace Tbmt\view;

class AboutTerms extends Base {

  public function render(array $params = array()) {
    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'terms.about.html',
      $params
    );
  }

}