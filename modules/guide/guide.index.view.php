<?php

namespace Tbmt\view;

class GuideIndex extends Base {

  public function render(array $params = array()) {

    $this->createPPPUrl = \Tbmt\Router::toModule('guide', 'create_ppp');
    $this->executePPPUrl = \Tbmt\Router::toModule('guide', 'exec_ppp');

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.guide.html',
      $params
    );
  }

}