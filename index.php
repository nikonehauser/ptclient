<?php

namespace Tbmt;

define('PROJECT_NAME', 'miltype');

define('BASE_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);
require BASE_DIR.'/include/bootstrap.php';

session_name(PROJECT_NAME);
session_start();

try {
  /* Dispatch controller
  ---------------------------------------------*/
  $controllerDispatcher = new ControllerDispatcher(MODULES_DIR, $_REQUEST);

  list(
    $controllerName,
    $controllerAction
  ) = Arr::initList($_REQUEST, [
    ['con', 'home'],
    ['act', 'index']
  ]);

  $controllerBody = $controllerDispatcher->dispatchAction($controllerName, $controllerAction);

  // TODO we may want to check the controller result to handle specials types like
  // ControllerResultCommand::REDIRECT etc.
  //
  // at the moment a controller action has to return a html string

  echo (new view\Index())->render([
    'basePath'    => '',
    'windowtitle' => 'TostiMiltype',
    'controllerBody' => $controllerBody
  ]);

} catch (PublicException $e) {
  echo view\PublicError::fromPublicException($e);
} catch (\Exception $e) {
  echo view\Error::fromException($e);
}


?>
