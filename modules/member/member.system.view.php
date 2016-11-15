<?php

namespace Tbmt\view;

class MemberSystem extends Base {

  public function render(array $params = array()) {
    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'system.member.html',
      $params
    );
  }

}