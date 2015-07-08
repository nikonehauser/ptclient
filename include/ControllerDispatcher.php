<?php

namespace Tbmt;

class ControllerDispatcher {

  private $rootPath;

  public function __construct($path) {
    $this->rootPath = $path;
  }

  static protected function loadController($name) {
    require MODULES_DIR.$name.DIRECTORY_SEPARATOR.$name.'.controller.php';
  }

  static protected function loadModuleView($name, $action) {
    require MODULES_DIR.$name.DIRECTORY_SEPARATOR.$name.'.'.$action.'.view.php';
  }

  static public function renderModuleView($name, $action, array $params = array()) {
    self::loadModuleView($name, $action);
    $name = NS_ROOT_PART.'view\\'.ucfirst($name).ucfirst($action);
    $view = new $name();
    return $view->render($params);
  }

  static public function dispatchAction($name, $action, array $params = array()) {
    self::loadController($name);
    $name = NS_ROOT_PART.ucfirst($name).'Controller';

    $controller = new $name();
    return $controller->dispatchAction($action, $params);
  }
}

?>