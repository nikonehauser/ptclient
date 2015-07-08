<?php

namespace Tbmt\view;

class MemberIndex extends Base {

  protected function init() {
    $locales = \Tbmt\Localizer::get('view.member');
    $this->textBlocks = $locales['text'];
  }

  public function render(array $params = array()) {
    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.member.html',
      $params
    );
  }

}