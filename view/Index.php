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
    foreach (['projects', 'member', 'about', 'impressum', 'account'] as $linkName) {
      $locale = $linkNames[$linkName];

      $sublinks = null;
      if ( isset($subLinkNames[$linkName]) ) {
        $sublinks = [];
        foreach ($subLinkNames[$linkName] as $action => $name) {
          $anchor = '';
          if ( is_array($name) ) {
            $anchor = '#'.$name[2];
            $action = $name[0];
            $name = $name[1];
          }

          array_push($sublinks, [
            \Tbmt\Router::toModule($linkName, $action).$anchor,
            $name,
            defined('CURRENT_MODULE_ACTION') && $action === CURRENT_MODULE_ACTION ? true : false
          ]);
        }
      }

      array_push($this->navigationLinks, [
        \Tbmt\Router::toModule($linkName),
        $locale,
        defined('CURRENT_MODULE') && $linkName === CURRENT_MODULE ? true : false,
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
      'legal',
      'user',
    ];

    $this->baseUrl = \Tbmt\Router::toBase();
    $this->i18nView = $this->i18nView;
  }

  protected $varsDef = [
    'basePath'       => \Tbmt\TYPE_STRING,
    'windowtitle'    => [\Tbmt\TYPE_STRING, 'TostiMiltype'],
    'controllerBody' => \Tbmt\TYPE_STRING,
    'contentWrapClass' => \Tbmt\TYPE_STRING
  ];
}