<?php

namespace Tbmt;

class Logger {
  private $messages = [];

  public function debug($group = '', ...$args) {
    $group = $group ? "<$group> " : '';

    $msgs = [];
    foreach ( $args as $arg ) {
      if ( is_callable([$arg, 'toArray']))
        $arg = $arg->toArray();

      $msgs[] = print_r($arg, true);
    }

    $this->messages[] = date('Y-m-d H:i:sr').': '.$group.implode("\n", $msgs);
  }

  public function out() {
    return implode("\n", array_reverse($this->messages));
  }
}
