<?php

namespace Tbmt\view;

class ManageChange_photos extends Base {

  public function render(array $params = array()) {
    $this->formLabels = $this->i18nView['form_labels'];

    $this->formErrors = isset($params['formErrors']) ? $params['formErrors'] : [];
    $this->successmsg = empty($params['successmsg']) ? '' : [
      $this->i18nView['success_msg'],
      $this->i18nView['success'],
      'success'
    ];

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'change_photos.manage.html',
      $params
    );
  }

}