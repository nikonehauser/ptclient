<?php

namespace Tbmt\view;

class AccountIndexTab extends Base {

  protected $varsDef = [
    'tabContent' => \Tbmt\TYPE_STRING
  ];

  protected function init() {
    $this->i18nView = \Tbmt\Localizer::get('view.account.tabs.index');
  }

  public function render(array $params = array()) {
    if ( !isset($params['member']) && !($params['member'] instanceof \Member) )
      throw new \Exception('Invalid param member for account index view.');

    $this->member = $params['member'];

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'tab.index.account.html',
      $params
    );
  }

}