<?php

namespace Tbmt;

class PayController extends BaseController {

  const MODULE_NAME = 'pay';

  protected $actions = [
    'index' => true,
  ];

  public function action_index() {
    $transApi = new \TransferWise\ApiClient(
      Config::get('transferwise.clientid'),
      Config::get('transferwise.clientsecret'),
      Config::get('transferwise.authcode')
    );

    $data = $transApi->authorize();

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['data' => $data]
    );
  }
}

?>
