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
      ->select(['Reason', 'Transfer.Currency', 'Transfer.State'])
      ->withColumn('count(*)', 'Quantity')
      ->withColumn('sum(Transaction.Amount)', 'Total')
      ->groupBy('Transfer.Currency')
      ->groupBy('Transaction.Reason')
      ->groupBy('Transfer.State')
      // ->orderBy('Transfer.CreationDate', \Criteria::DESC)
      ->limit(200);

    $this->transactions = $query->find();

    $this->paidout = \TransactionQuery::create()
      ->join('Transfer')
      ->useTransferQuery()
        ->filterByMember($this->member)
        ->filterByState(\Transfer::STATE_DONE)
      ->endUse()
      ->select(['Transfer.Currency'])
      ->withColumn('sum(Transaction.Amount)', 'Total')
      ->groupBy('Transfer.Currency')
      ->findOne();

    $this->paidout = $this->paidout['Total'];

    $this->totalEarned = $this->member->getOutstandingTotal();
    $this->totalTaxWithheld = [];
    $this->totalEarnedGrossAmount = [];

    $taxPercent = \Tbmt\Config::get('tds_tax_percent', \Tbmt\TYPE_INT, 0);
    $taxPercent /= 100;
    foreach ( $this->totalEarned as $currency => $amount) {
      $this->totalEarnedGrossAmount[$currency] = $amount;
      $taxAmount = round($amount * $taxPercent, 0, \PHP_ROUND_HALF_UP);
      $this->totalEarned[$currency] = $amount - $taxAmount;
      $this->totalTaxWithheld[$currency] = $taxAmount;
    }

    $this->transDateForm = \Tbmt\Localizer::get('datetime_format_php.long');
    $this->allowTotalInvoice = $this->member->getType() >= \Member::TYPE_SALES_MANAGER;

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'tab.invoice.account.html',
      $params
    );
  }

}