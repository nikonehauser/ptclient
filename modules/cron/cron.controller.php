<?php

namespace Tbmt;

class CronController extends BaseController {

  const MODULE_NAME = 'cron';

  protected $actions = [
    'firstreminder' => true,
    'removeunpaid' => true
  ];

  public function dispatchAction($action, $params) {
    if ( !\Tbmt\Config::get('devmode', \Tbmt\TYPE_BOOL, false) )
      throw new \PageNotFoundException();

    return parent::dispatchAction($action, $params);
  }

  public function action_firstreminder() {

    return '<pre style="display:block; min-height: 500px;">'.Cron::run('email_reminder', [time(), 0]).'</pre>';
  }

  public function action_removeUnpaid() {
    return '<pre style="display:block; min-height: 500px;">'.Cron::run('remove_unpaid', [time(), 0]).'</pre>';
  }
}

?>
