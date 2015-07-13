<?php

namespace Tbmt\view;

class AccountIndex extends Base {

  protected $varsDef = [
  ];

  protected function init() {
    $this->locales = \Tbmt\Localizer::get('view.account.index');
  }

  public function render(array $params = array()) {
    if ( !isset($params['member']) && !($params['member'] instanceof \Member) )
      throw new \Exception('Invalid param member for account index view.');

    $this->member = $params['member'];

    $linkNames = $this->locales['navigation_links'];
    $this->navigationLinks = [];
    foreach (['index', 'invoice', 'tree'] as $linkName) {
      $locale = $linkNames[$linkName];

      array_push($this->navigationLinks, [
        \Tbmt\Router::toAccountTab($linkName),
        $locale,
        $linkName === CURRENT_MODULE_ACTION ? true : false
      ]);
    }

    $name = \Tbmt\AccountController::MODULE_NAME;
    require MODULES_DIR.$name.DIRECTORY_SEPARATOR.$name.'.'.CURRENT_MODULE_ACTION.'.tab.view.php';
    $name = NS_ROOT_PART.'view\\'.ucfirst($name).ucfirst(CURRENT_MODULE_ACTION).'Tab';
    $contentView = new $name();
    $this->tabContent = $contentView->render(
      ['member' => \Tbmt\Session::getLogin()]
    );

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.account.html',
      $params
    );
  }

}