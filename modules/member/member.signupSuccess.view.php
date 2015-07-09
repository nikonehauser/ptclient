<?php

namespace Tbmt\view;

class MemberSignupSuccess extends Base {

  protected $varsDef = [
    'newMemberNum' => \Tbmt\TYPE_INT
  ];

  protected function init() {
    $this->locales = \Tbmt\Localizer::get('view.member.signup_success');
  }

  public function render(array $params = array()) {
    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'signupSuccess.member.html',
      $params
    );
  }

}