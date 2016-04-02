<?php

namespace Tbmt\view;

class MemberSignupSuccess extends Base {

  public function render(array $params = array()) {
    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'signupSuccess.member.html',
      $params
    );
  }

}