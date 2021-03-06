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
    if ( !$login || !$login->isAdminModulePermitted() )
      throw new PermissionDeniedException();

    return parent::dispatchAction($action, $params);
  }

  public function action_index() {
    $data = Arr::initMulti($_REQUEST, [
      'doexec' => \Tbmt\TYPE_BOOL,
      'type' => \Tbmt\TYPE_STRING,
    ]);

    $data = Masspay::payouts(
      $data['doexec'],
      $data['type']
    );

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      $data
    );
  }
}

?>
