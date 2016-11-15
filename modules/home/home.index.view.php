<?php

namespace Tbmt\view;

class HomeIndex extends Base {

  protected function init() {
    $locales = \Tbmt\Localizer::get('view.home');
    $this->textBlocks = $locales['text'];
  }

  public function render(array $params = array()) {
    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.home.html',
      $params
    );
  }

}