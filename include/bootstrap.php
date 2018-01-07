<?php

namespace Tbmt;

spl_autoload_register(function ($name) {
  if (\substr($name, 0, 5) === NS_ROOT_PART) {
    $name = substr($name, 5);
    $pos = strpos($name, '\\');
    if ( $pos !== false ) {
      list($base, $name) = explode('\\', $name);
      $base .= DIRECTORY_SEPARATOR;
    } else
      $base = INC_DIR;

    require $base.$name.'.php';
  }
});

\set_error_handler(function($errno, $errstr, $errfile, $errline) {
  throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
});

define('PROJECT_NAME', 'miltype');
define('NS_ROOT_NAME', 'Tbmt');
define('NS_ROOT_PART', 'Tbmt\\');

define('DOWNLOADS_DIR', BASE_DIR.'resources'.DIRECTORY_SEPARATOR.'downloads'.DIRECTORY_SEPARATOR);
define('CONFIG_DIR', BASE_DIR.'config'.DIRECTORY_SEPARATOR);
define('LIB_DIR', BASE_DIR.'lib'.DIRECTORY_SEPARATOR);
define('ENTITIES_DIR', BASE_DIR.'entities'.DIRECTORY_SEPARATOR);
define('ENTITIES_CLASSES_DIR', ENTITIES_DIR.'build'.DIRECTORY_SEPARATOR.'classes'.PATH_SEPARATOR);
define('VENDOR_DIR', BASE_DIR.'vendor'.DIRECTORY_SEPARATOR);
define('ASSETS_DIR', BASE_DIR.'assets'.DIRECTORY_SEPARATOR);
define('INC_DIR', BASE_DIR.'include'.DIRECTORY_SEPARATOR);
define('API_DIR', BASE_DIR.'api'.DIRECTORY_SEPARATOR);
define('MODULES_DIR', BASE_DIR.'modules'.DIRECTORY_SEPARATOR);
define('VIEWS_DIR', BASE_DIR.'views'.DIRECTORY_SEPARATOR);
define('LOCALES_DIR', BASE_DIR.'locales'.DIRECTORY_SEPARATOR);

require LIB_DIR.'functions.php';

require INC_DIR.'Exceptions.php';
require INC_DIR.'Val.php';
require INC_DIR.'Config.php';
require INC_DIR.'Localizer.php';
require INC_DIR.'Router.php';
require INC_DIR.'ControllerDispatcher.php';

Config::load(defined('CONFIG_FILE_PATH') ? CONFIG_FILE_PATH : CONFIG_DIR.'cfg.php');
$baseUrl = Config::get('baseurl');
if ( !$baseUrl )
  throw new \Exception('Invalid configuration. Missing "baseurl" definition.');

// $basePath = Config::get('basepath');
// if ( !$basePath )
//   throw new \Exception('Invalid configuration. Missing "basepath" definition.');

/* Setup propel
---------------------------------------------*/
set_include_path(
  get_include_path().PATH_SEPARATOR.
  ENTITIES_CLASSES_DIR.
  LIB_DIR.PATH_SEPARATOR.
  BASE_DIR.PATH_SEPARATOR
);

require_once LIB_DIR.'/propel/runtime/lib/Propel.php';

try {
  \Propel::init(ENTITIES_DIR.'build'.DIRECTORY_SEPARATOR.'conf'.DIRECTORY_SEPARATOR.PROJECT_NAME.'-conf.php');
  \Propel::getDB()->setCharset(\Propel::getConnection(), 'UTF8');

  if ( false ) {
    require_once 'Log.php';
    \Propel::getConnection()->useDebug(true);
    \Propel::setLogger(
      \Log::singleton($type = 'file', $name = BASE_DIR.'/propel.log', $ident = 'propel', $conf = array(), $level = PEAR_LOG_DEBUG)
    );
    $config = \Propel::getConfiguration(\PropelConfiguration::TYPE_OBJECT);
    $config->setParameter('debugpdo.logging.details.method.enabled', true);
    $config->setParameter('debugpdo.logging.details.time.enabled', true);
    $config->setParameter('debugpdo.logging.details.mem.enabled', true);
  }

  \Transaction::initAmounts(
    Config::get('amounts', TYPE_ARRAY),
    Config::get('member_fee', TYPE_FLOAT),
    Config::get('base_currency')
  );

} catch (\Exception $e) {
  // Do NOT output stacktrace because it holds the plain pg password.
  echo $e->getMessage();
  error_log($e->__toString());
  exit();
}

Session::start();

Router::init($baseUrl, ''); // LOAD router BEFORE locales
RouterToMarketing::init(Config::get('extended.system.url'), '');
Localizer::load(LOCALES_DIR, !empty($_REQUEST['lang']) ? $_REQUEST['lang'] : null);

define('BOOTSTRAP_DONE', true);
define('DEVELOPER_MODE', Config::get('devmode', TYPE_BOOL, false));
define('EXTEND_MARKTING_SYSTEM', Config::get('extended.marketing.member', TYPE_BOOL, false));

\Member::loadStrategy(EXTEND_MARKTING_SYSTEM);

MailHelper::$MAILS_DISABLED = Config::get('disable_email_distribution', TYPE_BOOL, false);

require VENDOR_DIR.'autoload.php';

?>