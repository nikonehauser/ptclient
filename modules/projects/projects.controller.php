<?php

namespace Tbmt;

class ProjectsController extends BaseController {

  const MODULE_NAME = 'projects';

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
