<?php

// aasdf
namespace Tbmt;

define('BASE_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);

try {

  require BASE_DIR.'include'.DIRECTORY_SEPARATOR.'bootstrap.php';

  function renderSimpleLogin() {
    $params = array_merge([
      'basePath'    => '',
      'windowtitle' => ''
    ]);

    echo (new view\Login())->render($params);
    exit();
  }

  Session::start();
  $isAllowed = false;
  $login = Session::getLogin();
  $nonce = isset($_REQUEST['nonce']) ? $_REQUEST['nonce'] : null;

  if ( $nonce ) {
    $nonce = \NonceQuery::create()->findOneByNonce($nonce);
    if ( !$nonce ) {
      renderSimpleLogin();
      exit();
    }

    $now = time();
    if ( $now > $nonce->getDate() ) {
      renderSimpleLogin();
      exit();
    }

    $login = Session::getLogin();
    if ( $login ) {
      Session::terminate();
      Session::start();
    }

    Session::setLogin($nonce->getMember());
    $nonce->delete();
    header('Location: '.Router::toModule('account'));

  } else if ( $login ) {
    $isAllowed = true;

  }  else if ( isset($_REQUEST['CoPOEStGHS0EAJP5ijX7'])
              && $_REQUEST['CoPOEStGHS0EAJP5ijX7'] === 'true'
              && !empty($_REQUEST['name'])
              && !empty($_REQUEST['password']) ) {
    $member = Session::login($_REQUEST['name'], $_REQUEST['password']);
    if ( $member && $member->isExtended() )
      $isAllowed = true;

  } else if ( Session::hasValidToken() ) {
    $isAllowed = true;
  }

  $token = isset($_REQUEST['tkn']) ? $_REQUEST['tkn'] : null;
  if ( $token ) {
    $res = \Member::getByHash($token, false);
    if ( $res && $res instanceof \Member && $res->isExtended() ) {
      $isAllowed = true;
      Session::setValidToken($token);
    }
  }

  if ( $isAllowed !== true )  {
    renderSimpleLogin();
    exit();
  }

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
