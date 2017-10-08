<?php

namespace Tbmt;

class GuideController extends BaseController {

  const MODULE_NAME = 'guide';

  protected $actions = [
    'index' => true,
    'howtopay' => true,
  ];

  public function action_index() {
    $login = Session::getLogin();

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index', [
        'member' => $login
      ]
    );
  }
}

?>
