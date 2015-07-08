<?php

namespace Tbmt;

class MemberController extends BaseController {

  const MODULE_NAME = 'member';

  protected $actions = [
    'index' => true
  ];

  public function action_index() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index'
    );
  }
}

?>
