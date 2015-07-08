<?php

namespace Tbmt\view;

class AboutIndex extends Base {

  protected function init() {
    $locales = \Tbmt\Localizer::get('view.about');
    $this->textBlocks = $locales['text'];
  }

  public function render(array $params = array()) {
    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.about.html',
      $params
    );
  }

}