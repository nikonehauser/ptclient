<?php

namespace Tbmt;

class BaseController {

  protected $actionPrefix = 'action_';

  protected $actions = [];

  public function dispatchAction($action, $params) {
    if ( !isset($this->actions[$action]) )
      throw new PageNotFoundException();

    if ( !is_callable([$this, "$this->actionPrefix$action"]) ) {
      return ControllerDispatcher::renderModuleView(
        static::MODULE_NAME,
        $action
      );
    }

    return call_user_func_array([$this, "$this->actionPrefix$action"], $params);
  }
}

?>