<?php

namespace Tbmt;

class PayController extends BaseController {

  const MODULE_NAME = 'pay';

  protected $actions = [
    'index' => true,
    'payouts' => true,
  ];

  public function dispatchAction($action, $params) {
    $login = Session::getLogin();
    if ( !$login || $login->getType() !== \Member::TYPE_ITSPECIALIST )
      throw new PermissionDeniedException();

    return parent::dispatchAction($action, $params);
  }

  public function action_index() {
    $data = Arr::initMulti($_REQUEST, [
      'doexec' => \Tbmt\TYPE_BOOL,
    ]);

    $data = Masspay::payouts(
      $data['doexec']
    );

    $data['exec_payments_url'] = \Tbmt\Router::toModule('pay', 'index', ['doexec' => 1]);

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      $data
    );
  }
}

?>
