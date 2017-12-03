<?php

namespace Tbmt\view;

class GuideIndex extends Base {

  public function render(array $params = array()) {
    $this->member = isset($params['member']) && ($params['member'] instanceof \Member)
      ? $params['member']
      : null;

    $this->formData = null;
    if ( $this->member ) {
      $this->formData = \Tbmt\Payu::prepareFormData($this->member, \Propel::getConnection());
    }

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.guide.html',
      $params
    );
  }

}