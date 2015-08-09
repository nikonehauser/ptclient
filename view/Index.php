<?php

namespace Tbmt\view;

class Index extends Base {

  protected function init() {
    $this->i18nView = \Tbmt\Localizer::get('view.common');
    $this->textBrandName = $this->i18nView['brand_name'];
    $this->textBrandMail = \Tbmt\Config::get('brand.mail');

    $linkNames = $this->i18nView['navigation_links'];
    $subLinkNames = $this->i18nView['navigation_sublinks'];
    $this->navigationLinks = [];
    foreach (['projects', 'member', 'about', 'account'] as $linkName) {
      $locale = $linkNames[$linkName];

      $sublinks = null;
      if ( isset($subLinkNames[$linkName]) ) {
        $sublinks = [];
        foreach ($subLinkNames[$linkName] as $action => $name) {
          array_push($sublinks, [
            \Tbmt\Router::toModule($linkName, $action),
            $name,
            $action === CURRENT_MODULE_ACTION ? true : false
          ]);
        }
      }

      array_push($this->navigationLinks, [
        \Tbmt\Router::toModule($linkName),
        $locale,
        $linkName === CURRENT_MODULE ? true : false,
        $sublinks
      ]);
    }


    $this->isLoggedIn = \Tbmt\Session::isLoggedIn();
    if ( !$this->isLoggedIn ) {
      $accountLinks = &$this->navigationLinks[count($this->navigationLinks)-1];
      $accountLinks[1] = $this->i18nView['member_login'];
      unset($accountLinks[3]);
    }

    $this->navigationIcons = [
      'road',
      'lightbulb-o',
      'envelope',
      'user',
    ];

    $this->baseUrl = \Tbmt\Router::toBase();
    $this->i18nView = $this->i18nView;
  }

  protected $varsDef = [
    'basePath'       => \Tbmt\TYPE_STRING,
    'windowtitle'    => [\Tbmt\TYPE_STRING, 'TostiMiltype'],
    'controllerBody' => \Tbmt\TYPE_STRING
  ];
}