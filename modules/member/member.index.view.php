<?php

namespace Tbmt\view;

class MemberIndex extends Base {

  public function render(array $params = array()) {
    return [
      // 'contentWrapClass' => 'content-sidebar',
      'controllerBody' => $this->renderFile(
        dirname(__FILE__).DIRECTORY_SEPARATOR.'index.member.html',
        $params
      )
    ];
  }

}