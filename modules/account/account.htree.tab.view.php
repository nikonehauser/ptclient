<?php

namespace Tbmt\view;

class AccountHtreeTab extends Base {

  protected $varsDef = [
  ];

  protected function init() {
    $this->locales = \Tbmt\Localizer::get('view.account.tabs.tree');
  }

  public function render(array $params = array()) {
    if ( !isset($params['member']) && !($params['member'] instanceof \Member) )
      throw new \Exception('Invalid param member for account index view.');

    $this->member = $params['member']->toArray();

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'tab.htree.account.html',
      $params
    );
  }

}