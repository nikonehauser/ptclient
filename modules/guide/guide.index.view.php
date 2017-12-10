<?php

namespace Tbmt\view;

class GuideIndex extends Base {

  public function render(array $params = array()) {
    $this->member = isset($params['member']) && ($params['member'] instanceof \Member)
      ? $params['member']
      : null;

    $this->formData = $params['formData'];
    
    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.guide.html',
      $params
    );
  }

}