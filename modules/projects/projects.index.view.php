<?php

namespace Tbmt\view;

class ProjectsIndex extends Base {

  protected function init() {
    $locales = \Tbmt\Localizer::get('view.projects');
    $this->textBlocks = $locales['text'];
  }

  public function render(array $params = array()) {
    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.projects.html',
      $params
    );
  }

}