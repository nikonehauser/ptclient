<?php

namespace Tbmt\view;

class AccountLogin extends Base {

  protected function init() {
    $this->locales = \Tbmt\Localizer::get('view.account.login');
    $this->formLabels = $this->locales['form_labels'];
  }

  public function render(array $params = array()) {
    $this->formVal =  \Tbmt\Arr::initMulti(isset($params['formVal']) ? $params['formVal'] : $_REQUEST, [
      'num' => \Tbmt\TYPE_KEY
    ]);

    $this->formVal['pwd'] = 'demo1234';

    $this->formErrors = $this->formVal['num'] ? ['pwd' => \Tbmt\Localizer::get('error.login')] : [];

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'login.account.html',
      $params
    );
  }

}