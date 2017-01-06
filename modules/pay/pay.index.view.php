<?php

namespace Tbmt\view;

class PayIndex extends Base {

  public function render(array $params = array()) {

    $this->data = isset($params['data']) ? $params['data'] : '';
    if ( !is_string($this->data) )
      $this->data = print_r($this->data, true);

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.pay.html',
      $params
    );
  }

}