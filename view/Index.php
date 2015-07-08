<?php

namespace Tbmt\view;

class Index extends Base {

  protected function init() {
    $locales = \Tbmt\Localizer::plain('common');
    $this->textBrandName = $locales['brand_name'];

    $viewCommon = \Tbmt\Localizer::get('view.common');
    $linkNames = $viewCommon['navigation_links'];
    $this->navigationLinks = [];
    foreach (['member', 'projects', 'about'] as $linkName) {
      array_push($this->navigationLinks, [
        \Tbmt\Router::toModule($linkName),
        $linkNames[$linkName]
      ]);
    }

    $this->baseUrl = \Tbmt\Router::toBase();
  }

  protected $varsDef = [
    'basePath'       => \Tbmt\TYPE_STRING,
    'windowtitle'    => [\Tbmt\TYPE_STRING, 'TostiMiltype'],
    'controllerBody' => \Tbmt\TYPE_STRING
  ];
}