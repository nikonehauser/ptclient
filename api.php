<?php

namespace Tbmt;

define('BASE_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);

try {
  require BASE_DIR.'include'.DIRECTORY_SEPARATOR.'bootstrap.php';

  $apiName = Arr::init($_REQUEST, 'api');
  $apiDo = Arr::init($_REQUEST, 'do');
  $apiFile = API_DIR.$apiName.'.php';
  if ( !$apiName || !$apiDo || !file_exists($apiFile) ) {
    throw new PageNotFoundException();
  }

  $apiClassName = 'Tbmt\\'.ucfirst($apiName).'API';
  require_once($apiFile);

  (new $apiClassName())->run($apiDo);

} catch (PageNotFoundException $e) {
  header("HTTP/1.0 404 Not Found");
} catch (\Exception $e) {
  error_log($e->__toString());
  $trace = '';
  if ( Config::get('devmode', TYPE_BOOL, false) )
    $trace = $e->getTraceAsString();

  header('Content-Type: application/json');
  echo json_encode([
    'error' => $e->getMessage(),
    'trace' => $trace,
  ]);
}
?>
