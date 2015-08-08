<?php

namespace Tbmt\view;

class AboutIndex extends Base {

  public function render(array $params = array()) {
    $this->formLabels = $this->i18nView['form_labels'];

    $this->formVal = \Tbmt\AboutController::initContactForm(
      isset($params['formVal']) ? $params['formVal'] : $_REQUEST
    );

    $this->formErrors = isset($params['formErrors']) ? $params['formErrors'] : [];
    $this->successmsg = isset($params['successmsg']) ? true : false;

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.about.html',
      $params
    );
  }

}