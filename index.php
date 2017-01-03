<?php

namespace Tbmt;

define('BASE_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);

try {

  require BASE_DIR.'include'.DIRECTORY_SEPARATOR.'bootstrap.php';

  require BASE_DIR.'auth.php';


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
    $actionResult->dispatch();
  } else {
    if ( !is_array($actionResult) ) {
      $actionResult = [
        'controllerBody' => $actionResult
      ];
    }

    $params = array_merge([
      'basePath'    => '',
      'windowtitle' => ''
    ], $actionResult);

    echo (new view\Index())->render($params);
  }

} catch (PublicException $e) {
  echo (new view\Index())->render([
    'basePath'    => '',
    'windowtitle' => '',
    'controllerBody' => view\PublicError::fromPublicException($e)
  ]);
} catch (\Exception $e) {
  $sendMail = defined('BOOTSTRAP_DONE');

  if ( $sendMail && Config::get('send_email_on_error', TYPE_BOOL, true) )
    MailHelper::sendException($e);

  error_log($e->__toString());
  if ( !$sendMail ) {
    echo '<pre>'.$e->__toString().'</pre>';
  } else {
    echo (new view\Index())->render([
      'basePath'    => '',
      'windowtitle' => '',
      'controllerBody' => view\Error::fromException($e)
    ]);
  }
}


?>
