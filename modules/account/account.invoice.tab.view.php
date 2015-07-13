<?php

namespace Tbmt\view;

class AccountInvoiceTab extends Base {

  protected $varsDef = [
  ];

  protected function init() {
    $this->locales = \Tbmt\Localizer::get('view.account.tabs.invoice');
  }

  public function render(array $params = array()) {
    if ( !isset($params['member']) && !($params['member'] instanceof \Member) )
      throw new \Exception('Invalid param member for account index view.');

    $this->member = $params['member'];


    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'tab.invoice.account.html',
      $params
    );
  }

}