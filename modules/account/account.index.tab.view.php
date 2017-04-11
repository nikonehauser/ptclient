<?php

namespace Tbmt\view;

class AccountIndexTab extends Base {

  protected $varsDef = [
    'tabContent' => \Tbmt\TYPE_STRING
  ];

  protected function init() {
    $this->i18nView = \Tbmt\Localizer::get('view.account.tabs.index');
    $this->i18nCommon = \Tbmt\Localizer::plain('common');
  }

  public function render(array $params = array()) {
    if ( !isset($params['member']) && !($params['member'] instanceof \Member) )
      throw new \Exception('Invalid param member for account index view.');

    $this->member = $params['member'];
    $this->signupmsg = \Tbmt\Session::get(\Tbmt\Session::KEY_SIGNUP_MSG);
    if ( $this->signupmsg )
      \Tbmt\Session::delete(\Tbmt\Session::KEY_SIGNUP_MSG);

    $this->payoutFailed = false;
    $query = \PayoutQuery::create()
      ->useTransferQuery()
        ->filterByMember($this->member)
        ->orderBy(\TransferPeer::EXECUTION_DATE, \Criteria::DESC)
      ->endUse()
      ->orderBy(\PayoutPeer::CREATION_DATE, \Criteria::DESC);

    $lastPayout = $query->findOne();
    if ( $lastPayout && $lastPayout->isCustomerFailure() ) {
      $this->payoutFailed = $lastPayout;
    }

    $this->guidesCount = 0;
    if ( $this->member->hadPaid() )
      $this->guidesCount = $this->member->getHgWeek();

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'tab.index.account.html',
      $params
    );
  }

}