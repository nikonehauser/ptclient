<?php

namespace Tbmt;

class UrgentException extends \Exception {}

class PublicException extends \Exception {
  protected $name;
  public function getName() {
    return $this->name ? $this->name : get_class();
  }
}

class InvalidDataException extends PublicException {
  protected $name = 'Invalid Data';
  public function __construct($msg = '') {
    parent::__construct($msg);
  }
}

class PermissionDeniedException extends PublicException {
  protected $name = 'Permission denied';
  public function __construct($msg = '') {
    parent::__construct($msg != '' ? $msg : 'Sorry, you have no permissions for this operation.');
  }
}

class PageNotFoundException extends PublicException {
  protected $name = 'Page not found';
  public function __construct($msg = '') {
    parent::__construct($msg != '' ? $msg : 'Sorry, but the page you were trying to view does not exist.');
  }
}

class ProvisionExceedMemberFeeException extends \Exception {}