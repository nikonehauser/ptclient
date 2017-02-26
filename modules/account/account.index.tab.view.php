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

    $paidDate = $this->member->getPaidDate();
    if ( !$paidDate || $paidDate <= 0 ) {
      $guidesCount = 0;
    } else {
      $period = \Tbmt\Config::get('guides_available_period');
      $diff = time() - $paidDate;

      $guidesCount = (int)(($diff / $period) + 1);
      $maxCount = \Tbmt\Config::get('guides_count');
      if ( $guidesCount > $maxCount )
        $guidesCount = $maxCount;
    }

    $this->payoutFailed = false;
    $lastPayout = \PayoutQuery::create()
      ->useTransferQuery()
        ->filterByMember($this->member)
      ->endUse()
      ->orderBy(\PayoutPeer::CREATION_DATE, \Criteria::DESC)
      ->limit(1)
      ->findOne();

    if ( $lastPayout && $lastPayout->isCustomerFailure() ) {
      $this->payoutFailed = $lastPayout;
    }

    $this->guidesCount = $guidesCount;

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'tab.index.account.html',
      $params
    );
  }

}