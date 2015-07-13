<?php

namespace Tbmt;

class AboutController extends BaseController {

  const MODULE_NAME = 'about';

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
