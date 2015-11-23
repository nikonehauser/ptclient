<?php

namespace Tbmt;

abstract class DistributionStrategy {

  static private $instance;
  static public function getInstance() {
    if ( self::$instance )
      return self::$instance;

    $strategyName = NS_ROOT_PART.Config::get('distribution.strategy').'DistStrategy';
    self::$instance = new $strategyName();

    return self::$instance;
  }

  static public function resetInstance() {
    // This is for unit testing purpose only!
    self::$instance = null;
  }

  abstract public function onReceivedMemberFee(\Member $member, \Member $referrer, $currency, $when, \PropelPDO $con);

}

?>