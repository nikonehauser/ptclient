<?php

namespace Tbmt\view;

class MemberSignup extends Base {

  protected function init() {
    $locales = \Tbmt\Localizer::get('view.member.signup');
    $this->formLabels = $locales['form_labels'];
  }

  public function render(array $params = array()) {
    $this->formVal = \Member::initSignupForm(
      isset($params['formVal']) ? $params['formVal'] : $_REQUEST
    );

    $this->formVal['password2'] = '';

    $this->formErrors = isset($params['formErrors']) ? $params['formErrors'] : [];

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'signup.member.html',
      $params
    );
  }

}