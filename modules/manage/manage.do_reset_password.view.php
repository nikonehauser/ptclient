<?php

namespace Tbmt\view;

class ManageDo_reset_password extends Base {

  public function render(array $params = array()) {
    $this->resetMsg = isset($params['newPassword']) && $params['newPassword'] ? [
      $this->i18nView['success_msg'],
      $this->i18nView['success'],
      'success',
      '<p><strong>'.$params['newPassword'].'</strong></p>'
    ] : [
      $this->i18nView['error_msg'],
      $this->i18nView['error'],
      'error'
    ];

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'do_reset_password.manage.html',
      $params
    );
  }

}