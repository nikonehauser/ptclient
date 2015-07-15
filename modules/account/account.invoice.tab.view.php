<?php

namespace Tbmt\view;

class AccountInvoiceTab extends Base {

  protected $varsDef = [
  ];

  protected function init() {
    $this->i18nView = \Tbmt\Localizer::get('view.account.tabs.invoice');
  }

  public function render(array $params = array()) {
    if ( !isset($params['member']) && !($params['member'] instanceof \Member) )
      throw new \Exception('Invalid param "member" for account index view.');

    $this->member = $params['member'];
    $this->transactions = \TransactionQuery::create()
      ->join('Transfer')
      ->useTransferQuery()
        ->filterByMember($this->member)
      ->endUse()
      ->orderBy('date', \Criteria::DESC)
      ->limit(20)
      ->find();

    $this->transDateForm = \Tbmt\Localizer::get('date_format_php.long');

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'tab.invoice.account.html',
      $params
    );
  }

}