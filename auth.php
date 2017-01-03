<?php

namespace Tbmt;

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

