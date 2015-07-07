<?php

namespace Tbmt\view;

class Index extends Base {
  protected $varsDef = [
    'basePath'       => \Tbmt\TYPE_STRING,
    'windowtitle'    => [\Tbmt\TYPE_STRING, 'TostiMiltype'],
    'controllerBody' => \Tbmt\TYPE_STRING
  ];
}