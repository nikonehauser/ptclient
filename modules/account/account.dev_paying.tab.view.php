<?php

namespace Tbmt\view;

class AccountDev_payingTab extends Base {

  protected $varsDef = [
  ];

  protected function init() {
    $this->i18nView = \Tbmt\Localizer::get('view.account.tabs.dev_paying');
    $this->i18nCommon = \Tbmt\Localizer::plain('common');
  }

  public function render(array $params = array()) {
    if ( !isset($params['member']) && !($params['member'] instanceof \Member) )
      throw new \Exception('Invalid param member for account index view.');

    $this->members = \MemberQuery::create()
      ->filterByPaidDate(null, \Criteria::ISNULL)
      ->filterByDeletionDate(null, \Criteria::ISNULL)
      ->find();

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'tab.dev_paying.account.html',
      $params
    );
  }

}