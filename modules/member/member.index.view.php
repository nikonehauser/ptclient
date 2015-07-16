<?php

namespace Tbmt\view;

class MemberIndex extends Base {

  public function render(array $params = array()) {
    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.member.html',
      $params
    );
  }

}