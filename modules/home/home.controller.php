<?php

namespace Tbmt;

class HomeController extends BaseController {

  const MODULE_NAME = 'home';

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
