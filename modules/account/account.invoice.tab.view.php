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
    $query = \TransactionQuery::create()
      ->join('Transfer')
      ->useTransferQuery()
        ->filterByMember($this->member)
      ->endUse()
      ->select(['Reason', 'Transfer.Currency'])
      ->withColumn('count(*)', 'Quantity')
      ->withColumn('sum(Transaction.Amount)', 'Total')
      ->groupBy('Transfer.Currency')
      ->groupBy('Transaction.Reason')
      ->limit(100);

    $this->transactions = $query->find();

    $this->transDateForm = \Tbmt\Localizer::get('datetime_format_php.long');

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'tab.invoice.account.html',
      $params
    );
  }

}