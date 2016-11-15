<?php

namespace Tbmt;

class StatsAPI extends RestServer {

  public $actions = array(
    'read' => array('GET', 'read', array(
    ))
  );


  public function do_read() {
    echo 'test';
  }
}

?>