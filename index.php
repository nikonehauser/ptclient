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
    Router::KEY_MODULE => [\Tbmt\TYPE_STRING, 'projects'],
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
    if ( !is_array($actionResult) ) {
      $actionResult = [
        'controllerBody' => $actionResult
      ];
    }

    $params = array_merge([
      'basePath'    => '',
      'windowtitle' => 'TostiMiltype'
    ], $actionResult);

    echo (new view\Index())->render($params);
  }

} catch (PublicException $e) {
  echo (new view\Index())->render([
    'basePath'    => '',
    'windowtitle' => 'TostiMiltype',
    'controllerBody' => view\PublicError::fromPublicException($e)
  ]);
} catch (\Exception $e) {
  error_log($e->__toString());
  echo (new view\Index())->render([
    'basePath'    => '',
    'windowtitle' => 'TostiMiltype',
    'controllerBody' => view\Error::fromException($e)
  ]);
}


?>
