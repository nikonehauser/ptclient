<?php

namespace Tbmt;

class Logger {
  private $messages = [];

  public function debug($group = '', ...$args) {
    $group = $group ? "<$group> " : '';

    $msgs = [];
    foreach ( $args as $arg ) {
      if ( method_exists($arg, 'toArray'))
        $arg = $arg->toArray();
      else if ( method_exists($arg, 'toString'))
        $arg = $arg->toString();
      else if ( method_exists($arg, '__toString'))
        $arg = $arg->__toString();

      $msgs[] = print_r($arg, true);
    }

    $this->messages[] = date('Y-m-d H:i:s').': '.$group.implode("\n", $msgs);
  }

  public function out() {
    return implode("\n", array_reverse($this->messages));
  }
}
