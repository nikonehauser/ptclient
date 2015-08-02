<?php

namespace Tbmt\view;

class AccountBonus_levelsTab extends Base {

  protected $varsDef = [
  ];

  protected function init() {
    $this->i18nView = \Tbmt\Localizer::get('view.account.tabs.bonus_levels');
    $this->i18nCommon = \Tbmt\Localizer::plain('common');
  }

  public function render(array $params = array()) {
    if ( !isset($params['member']) && !($params['member'] instanceof \Member) )
      throw new \Exception('Invalid param member for account index view.');

    $this->member = $params['member'];
    $this->recipient = isset($params['recipient']) ? $params['recipient'] : null;
    $this->successmsg = isset($params['successmsg']) ? true : false;

    $this->formVal = \Transaction::initBonusTransactionForm(
      isset($params['formVal']) ? $params['formVal'] : $_REQUEST
    );

    $this->formErrors = isset($params['formErrors']) ? $params['formErrors'] : [];

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'tab.bonus_levels.account.html',
      $params
    );
  }

}