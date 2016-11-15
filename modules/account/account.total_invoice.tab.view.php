<?php

namespace Tbmt\view;

class AccountTotal_invoiceTab extends Base {

  protected $varsDef = [
  ];

  protected function init() {
    $this->i18nView = \Tbmt\Localizer::get('view.account.tabs.invoice');
  }

  public function render(array $params = array()) {
    if ( !isset($params['member']) && !($params['member'] instanceof \Member) )
      throw new \Exception('Invalid param "member" for account index view.');

    $this->totalMemberCount = \MemberQuery::create()->count();
    $this->totalPaidMemberCount = \MemberQuery::create()
      ->filterByPaidDate(null, \Criteria::ISNOTNULL)
      ->count();

    $this->absoluteTransferredTotal = '';
    // $this->absoluteTransferredTotal = \MemberQuery::create()
    //   ->withColumn('count(*)', 'nbComments')
    //   ->count();

    $this->member = $params['member'];
    $this->members = \MemberQuery::create()
      ->filterByType(-1, \Criteria::NOT_EQUAL)
      ->orderBy(\MemberPeer::ID, \Criteria::ASC)
      ->limit(300)
      ->find();

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'tab.total_invoice.account.html',
      $params
    );
  }

}