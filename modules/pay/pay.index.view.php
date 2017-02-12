<?php

namespace Tbmt\view;

class PayIndex extends Base {

  public function render(array $params = array()) {
    $this->data = $params;

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.pay.html',
      $params
    );
  }

  protected function renderMemberName(\Member $member) {
    return $member->getNum().' - '.$member->getFirstName().' - '.$member->getLastName();
  }

}