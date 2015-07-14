<?php

namespace Tbmt\view;

class AccountLogin extends Base {

  public function render(array $params = array()) {
    $this->formLabels = $this->i18nView['form_labels'];

    $formParams = isset($params['formVal']) ? $params['formVal'] : $_REQUEST;
    $loginTry = isset($formParams['num']) || isset($formParams['pwd']);

    $this->formVal =  \Tbmt\Arr::initMulti($formParams, [
      'num' => \Tbmt\TYPE_STRING
    ]);

    $this->formVal['pwd'] = 'demo1234';

    $this->formErrors = [];
    $this->loginError = $loginTry ? \Tbmt\Localizer::get('error.login').'.' : null;

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'login.account.html',
      $params
    );
  }

}