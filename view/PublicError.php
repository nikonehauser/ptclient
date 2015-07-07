<?php

namespace Tbmt\view;

class PublicError extends Base {
  static public function fromPublicException(\Tbmt\PublicException $e) {
    return (new self())->render([
      '{name}' => $e->getName(),
      '{message}' => $e->getMessage()
    ]);
  }
}