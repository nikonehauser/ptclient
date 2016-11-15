<?php

namespace Tbmt\view;

class ProjectsIndex extends Base {

  public function render(array $params = array()) {
    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.projects.html',
      $params
    );
  }

}