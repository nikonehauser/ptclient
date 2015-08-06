<?php

namespace Tbmt\view;

class Index extends Base {

  protected function init() {
    $locales = \Tbmt\Localizer::plain('common');
    $this->textBrandName = $locales['brand_name'];

    $viewCommon = \Tbmt\Localizer::get('view.common');
    $linkNames = $viewCommon['navigation_links'];
    $subLinkNames = $viewCommon['navigation_sublinks'];
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
      $accountLinks[1] = $viewCommon['member_login'];
      unset($accountLinks[3]);
    }

    $this->navigationIcons = [
      'road',
      'lightbulb-o',
      'envelope',
      'user',
    ];

    $this->baseUrl = \Tbmt\Router::toBase();
    $this->locales = $viewCommon;
  }

  protected $varsDef = [
    'basePath'       => \Tbmt\TYPE_STRING,
    'windowtitle'    => [\Tbmt\TYPE_STRING, 'TostiMiltype'],
    'controllerBody' => \Tbmt\TYPE_STRING
  ];
}