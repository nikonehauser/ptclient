<?php

namespace Tbmt;

define('BASE_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);

try {
  require BASE_DIR.'include'.DIRECTORY_SEPARATOR.'bootstrap.php';

  Session::start();

  /* Dispatch controller
  ---------------------------------------------*/
  list(
    $controllerName,
    $controllerAction
  ) = Arr::initList($_REQUEST, [
    Router::KEY_MODULE => [\Tbmt\TYPE_STRING, 'home'],
    Router::KEY_ACTION => [\Tbmt\TYPE_STRING, 'index'],
  ]);

  define('CURRENT_MODULE', $controllerName);
  define('CURRENT_MODULE_ACTION', $controllerAction);

  $actionResult = ControllerDispatcher::dispatchAction(
    $controllerName,
    $controllerAction
  );

  Session::commit();

  if ( $actionResult instanceof ControllerActionResult ) {
    $actionResult->execute();
  } else {
    echo (new view\Index())->render([
      'basePath'    => '',
      'windowtitle' => 'TostiMiltype',
      'controllerBody' => $actionResult
    ]);
  }

} catch (PublicException $e) {
  echo view\PublicError::fromPublicException($e);
} catch (\Exception $e) {
  error_log($e->__toString());
  echo view\Error::fromException($e);
}


?>
