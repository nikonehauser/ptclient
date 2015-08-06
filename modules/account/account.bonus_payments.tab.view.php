<?php

namespace Tbmt\view;

class AccountBonus_paymentsTab extends Base {

  protected $varsDef = [
  ];

  protected function init() {
    $this->i18nView = \Tbmt\Localizer::get('view.account.tabs.bonus_payments');
    $this->i18nCommon = \Tbmt\Localizer::plain('common');
  }

  public function render(array $params = array()) {
    if ( !isset($params['member']) && !($params['member'] instanceof \Member) )
      throw new \Exception('Invalid param member for account index view.');

    $this->member = $params['member'];
    $this->recipient = isset($params['recipient']) ? $params['recipient'] : null;

    $this->formVal = \Transaction::initBonusTransactionForm(
      isset($params['formVal']) ? $params['formVal'] : $_REQUEST
    );

    $this->formErrors = isset($params['formErrors']) ? $params['formErrors'] : [];
    $this->successmsg = isset($params['successmsg']) ? true : false;

    $objBonusTransactions = \TransactionQuery::create()
      ->filterByRelatedId($this->member->getId())
      ->filterByReason(\Transaction::REASON_CUSTOM_BONUS)
      ->join('Transaction.Transfer')
      ->join('Transfer.Member')
      ->select([
          'Member.Num',
          'Transaction.Amount',
          'Purpose',
          'Transaction.Date'
        ])
      ->limit(100)
      ->find();

    $arrBonusTransactions = [];
    $dateFormat = \Tbmt\Localizer::get('datetime_format_php.long');
    foreach ( $objBonusTransactions as $bonusTransaction ) {
      $arrBonusTransactions[] = [
        $bonusTransaction['Member.Num'],
        $bonusTransaction['Transaction.Amount'],
        $bonusTransaction['Purpose'],
        (new \DateTime($bonusTransaction['Transaction.Date']))->format($dateFormat)
      ];
    }

    $this->bonusTransactions = $arrBonusTransactions;

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'tab.bonus_payments.account.html',
      $params
    );
  }

}