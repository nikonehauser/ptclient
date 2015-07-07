<?php

namespace Tbmt;

class HomeController extends BaseController {

  protected $actions = [
    'index' => true
  ];

  public function action_index() {
    return 'test';
  }
}

?>