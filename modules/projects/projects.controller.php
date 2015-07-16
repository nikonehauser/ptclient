<?php

namespace Tbmt;

class ProjectsController extends BaseController {

  const MODULE_NAME = 'projects';

  protected $actions = [
    'index' => true,
    'girls_schools' => true
  ];

  public function action_index() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index'
    );
  }

  public function action_girls_schools() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'girls_schools'
    );
  }
}

?>
