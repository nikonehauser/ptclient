<?php

namespace Tbmt;

/**
 * WARNING: This implementation suffer from race conditions.
 *
 * DO NOT USE IN SERVER CLIENT ARCHITECTURE.
 *
 * We use it for cronjobs only here.
 *
 */
class Flock {

  private $path;
  private $handle;

  public function __construct($path) {
    $this->path = $path;
  }

  public function acquire() {
    if ( file_exists($this->path) )
      throw new \Exception('Already locked.');

    $this->handle = fopen($this->path, 'x');
    if ( $this->handle === false )
      throw new \Exception('Could not obtain lock file.');

    if ( flock($this->handle, LOCK_EX | LOCK_NB) === false )
      throw new \Exception('Could not obtain lock.');
  }

  public function release() {
    if ( $this->handle ) {
      flock($this->handle, LOCK_UN);
      fclose($this->handle);
      $this->handle = false;
    }

    unlink($this->path);
  }
}
