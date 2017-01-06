<?php

namespace Tbmt\view;

class Login extends Base {

  protected function init() {
  }

  public function render(array $params = array()) {

    $this->resetMessage = isset($params['resetMessage']) ? $params['resetMessage'] : '';
    $this->resetMailMessage = isset($params['resetMailMessage']) ? $params['resetMailMessage'] : '';
    $this->loginMessage = isset($params['loginMessage']) ? $params['loginMessage'] : '';

    return parent::render(
      $params
    );
  }

}