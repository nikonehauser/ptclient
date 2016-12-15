<?php

namespace Tbmt;

class GuideController extends BaseController {

  const MODULE_NAME = 'guide';

  protected $actions = [
    'index' => true,
  ];

  public function action_index() {
    $login = Session::getLogin();
    if ( !$login ) {
      throw new PermissionDeniedException();
    }

    $nonce = \Nonce::create($login);
    $url = Config::get('simple.system.url')."?mod=guide&nonce=".$nonce->getNonce();
    return new ControllerActionRedirect($url);
  }

}

?>
