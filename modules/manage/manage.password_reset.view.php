<?php

namespace Tbmt\view;

class ManagePassword_reset extends Base {

  public function render(array $params = array()) {
    $this->formLabels = $this->i18nView['form_labels'];

    $formParams = isset($params['formVal']) ? $params['formVal'] : $_REQUEST;
    $this->formVal =  \Tbmt\ManageController::initPasswordResetForm($formParams);

    $this->formErrors = isset($params['formErrors']) ? $params['formErrors'] : [];
    $this->resetMsg = !isset($params['resetmsg']) ? '' : [
      $this->i18nView['success_msg'],
      $this->i18nView['success'],
      'success'
    ];

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'password_reset.manage.html',
      $params
    );
  }

}