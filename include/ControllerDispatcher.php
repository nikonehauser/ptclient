<?php

namespace Tbmt;

class ControllerDispatcher {

  private $rootPath;

  public function __construct($path) {
    $this->rootPath = $path;
  }

  protected function loadController($name) {
    require $this->rootPath.$name.DIRECTORY_SEPARATOR.$name.'.controller.php';
  }

  public function dispatchAction($name, $action, $params = []) {
    $this->loadController($name);
    $name = 'Tbmt\\'.ucfirst($name).'Controller';

    $controller = new $name();
    return $controller->dispatchAction($action, $params);
  }
}

?>