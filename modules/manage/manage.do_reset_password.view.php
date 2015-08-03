<?php

namespace Tbmt\view;

class ManageDo_reset_password extends Base {

  public function render(array $params = array()) {
    $this->resetMsg = isset($params['resetMsg']) ? $params['resetMsg'] : '';
    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'do_reset_password.manage.html',
      $params
    );
  }

}