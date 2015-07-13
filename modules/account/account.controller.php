<?php

namespace Tbmt;

class AccountController extends BaseController {

  const MODULE_NAME = 'account';

  protected $actions = [
    'index' => true,
    'logout' => true,
    'info' => true,
    'invoice' => true,
    'tree' => true,
  ];

  public function dispatchAction($action, $params) {
    if ( $action === 'logout' ) {
      Session::terminate();
      return new ControllerActionRedirect(Router::toBase());
    }

    if ( !Session::isLoggedIn() ) {
      list($num, $pwd) = Arr::initList($_REQUEST, [
        'num' => TYPE_KEY,
        'pwd' => TYPE_STRING
      ]);

      if (!$num || !$pwd || !Session::login($num, $pwd)) {
        return ControllerDispatcher::renderModuleView(
          self::MODULE_NAME,
          'login',
          ['formVal' => ['num' => $num]]
        );
      }
    }

    return parent::dispatchAction($action, $params);
  }

  public function action_index() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => Session::getLogin()]
    );
  }

  public function action_invoice() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => Session::getLogin()]
    );
  }

  public function action_tree() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => Session::getLogin()]
    );
  }
}

?>
