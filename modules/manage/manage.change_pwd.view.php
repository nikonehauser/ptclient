<?php

namespace Tbmt\view;

class ManageChange_pwd extends Base {

  public function render(array $params = array()) {
    $this->formLabels = $this->i18nView['form_labels'];

    $formParams = isset($params['formVal']) ? $params['formVal'] : $_REQUEST;
    $this->formVal =  \Tbmt\ManageController::initChangePasswordForm($formParams);

    $this->formErrors = isset($params['formErrors']) ? $params['formErrors'] : [];
    $this->successmsg = !isset($params['successmsg']) ? '' : [
      $this->i18nView['success_msg'],
      $this->i18nView['success'],
      'success'
    ];

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'change_pwd.manage.html',
      $params
    );
  }

}