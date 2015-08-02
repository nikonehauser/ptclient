<?php

namespace Tbmt\view;

class AccountIndex extends Base {

  protected $varsDef = [
  ];

  public function render(array $params = array()) {
    if ( !isset($params['member']) && !($params['member'] instanceof \Member) )
      throw new \Exception('Invalid param member for account index view.');

    $this->member = $params['member'];

    $tabName = isset($params['tab']) ? $params['tab'] : CURRENT_MODULE_ACTION;

    $linkNames = $this->i18nView['navigation_links'];
    $this->navigationLinks = [];
    $arr = ['index', 'invoice', 'rtree', 'htree'];

    $memberType = $this->member->getType();
    if ( $memberType > \Member::TYPE_MEMBER )
      $arr[] = 'invitation';

    if ( $memberType >= \Member::TYPE_CEO ) {
      $arr[] = 'bonus_payments';
      $arr[] = 'bonus_levels';

    }

    foreach ($arr as $linkName) {
      $locale = $linkNames[$linkName];

      array_push($this->navigationLinks, [
        \Tbmt\Router::toAccountTab($linkName),
        $locale,
        $linkName === $tabName ? true : false
      ]);
    }

    $name = \Tbmt\AccountController::MODULE_NAME;

    require MODULES_DIR.$name.DIRECTORY_SEPARATOR.$name.'.'.$tabName.'.tab.view.php';
    $name = NS_ROOT_PART.'view\\'.ucfirst($name).ucfirst($tabName).'Tab';
    $contentView = new $name();
    $this->tabContent = $contentView->render($params);

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.account.html',
      $params
    );
  }

}