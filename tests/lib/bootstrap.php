<?php

  define('TESTS_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
  define('TESTS_LIB_DIR', TESTS_DIR.'lib'.DIRECTORY_SEPARATOR);

  define('BASE_DIR', TESTS_DIR.'..'.DIRECTORY_SEPARATOR);

  require BASE_DIR.'include'.DIRECTORY_SEPARATOR.'bootstrap.php';
  require TESTS_LIB_DIR.'helper.php';
  require TESTS_LIB_DIR.'Tbmt_Tests_DatabaseTestCase.php';

  // DbEntityHelper::$currency = \Tbmt\Config::get('base_currency');

  // Disable mails for unit tests
  \Tbmt\MailHelper::$MAILS_DISABLED = true;

?>