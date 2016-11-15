<?php

namespace Tbmt;

class ControllerDispatcher {

  private $rootPath;

  public function __construct($path) {
    $this->rootPath = $path;
  }

  static protected function loadController($name) {
    $file = MODULES_DIR.$name.DIRECTORY_SEPARATOR.$name.'.controller.php';
    if ( file_exists($file) ) {
      require $file;
      return true;
    }

    return false;
  }

  static protected function loadModuleView($name, $action) {
    $file = MODULES_DIR.$name.DIRECTORY_SEPARATOR.$name.'.'.$action.'.view.php';
    if ( file_exists($file) ) {
      require $file;
      return true;
    }

    return false;
  }

  static public function renderModuleView($name, $action, array $params = array()) {
    if ( self::loadModuleView($name, $action) ) {
      $className = NS_ROOT_PART.'view\\'.ucfirst($name).ucfirst($action);
      $view = new $className($name, $action);
    } else
      $view = new \Tbmt\view\ModuleAction($name, $action);

    return $view->render($params);
  }

  static public function dispatchAction($name, $action, array $params = array()) {
    if ( !self::loadController($name) )
      throw new PageNotFoundException();

    $name = NS_ROOT_PART.ucfirst($name).'Controller';

    $controller = new $name();
    return $controller->dispatchAction($action, $params);
  }
}


abstract class ControllerActionResult {
  private $data;
  public function __construct($data) {
    $this->data = $data;
  }

  protected function getData() {
    return $this->data;
  }

  abstract public function execute();
}

class ControllerActionRedirect extends ControllerActionResult {

  public function execute() {
    header('Location: '.$this->getData());
  }
}

class ControllerActionAjax extends ControllerActionResult {

  public function execute() {
    header('Content-Type: application/json');
    echo json_encode($this->getData());
  }
}

class ControllerActionDownload extends ControllerActionResult {

  public function execute() {
    $data = $this->getData();
    $name = $data['name'];
    $contentType = $data['contentType'];
    $path = $data['path'];

    header("Content-Type: $contentType");
    header("Content-Disposition: attachment; filename=\"$name\"");
    readfile($path);
  }
}

?>