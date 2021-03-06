<?php



/**
 * Skeleton subclass for representing a row from the 'tbmt_activity' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.miltype
 */
class Activity extends BaseActivity
{
  const ACT_ACCOUNT_BONUS_LEVEL = 1;
  const ACT_ACCOUNT_BONUS_PAYMENT = 2;

  const ACT_MEMBER_SIGNUP = 3;
  const ACT_MEMBER_PAYMENT_EXEC = 5;
  const ACT_MEMBER_PAYMENT_CANCEL = 6;
  const ACT_MEMBER_PAYMENT_CANCEL_BY_USER = 7;
  const ACT_MEMBER_PAYMENT_CANCEL_UNKNOWN = 8;
  const ACT_MEMBER_PAYMENT_FINALIZE = 9;

  const ACT_ADMIN_IMPORT_PAYMENTS = 10;

  const ARR_RESULT_RETURN_KEY = '__return';
  const ARR_RELATED_RETURN_KEY = '__related';
  const ARR_EXCEPTION_RETURN_KEY = '__exception';

  const MK_BONUS_PAYMENT_AMOUNT = 'amount';
  const MK_BONUS_PAYMENT_CUSTOMER = 'customer';

  const TYPE_SUCCESS = 1;
  const TYPE_FAILURE = 2;

  static public $_ActivityExceptions = [];

  static public function execAjax($callable, $arrArgs, $action, $creator = null, $related = null, PropelPDO $con) {
    try {
      return new \Tbmt\ControllerActionAjax(self::exec($callable, $arrArgs, $action, $creator, $related, $con));
    } catch(Exception $e) {
      return new \Tbmt\ControllerActionAjax(['error' => $e->getMessage()]);
    }
  }

  static public function exec($callable, $arrArgs, $action, $creator = null, $related = null, PropelPDO $con, $useDbTransaction = true) {
    $resIsArray = $res = false;
    $exception = null;
    $type = self::TYPE_SUCCESS;
    $related = null;

    if ( $useDbTransaction )
      if ( !$con->beginTransaction() )
        throw new Exception('Could not begin transaction');

    try {
      $res = $return = call_user_func_array($callable, $arrArgs);
      $resIsArray = is_array($res);

      if ( $resIsArray && isset($res[self::ARR_EXCEPTION_RETURN_KEY]) ) {
        $exception = $res[self::ARR_EXCEPTION_RETURN_KEY];
        unset($res[self::ARR_EXCEPTION_RETURN_KEY]);
        throw $exception;
      }

      if ( $useDbTransaction )
        if ( !$con->commit() )
          throw new Exception('Could not commit transaction');

    } catch (Exception $e) {
      if ( $useDbTransaction )
        $con->rollBack();

      $exception = $e;
      $type = self::TYPE_FAILURE;
    }

    $resIsArray = is_array($res);

    if ( $resIsArray ) {
      if ( isset($res[self::ARR_RESULT_RETURN_KEY]) ) {
        $return = $res[self::ARR_RESULT_RETURN_KEY];
        unset($res[self::ARR_RESULT_RETURN_KEY]);
      }

      if ( !$related && isset($res[self::ARR_RELATED_RETURN_KEY]) ) {
        $related = $res[self::ARR_RELATED_RETURN_KEY];
        unset($res[self::ARR_RELATED_RETURN_KEY]);
      }
    }

    $activity = self::insert(
      $action,
      $type,
      $creator,
      $related,
      $resIsArray ? $res : [$res],
      $exception,
      $con
    );

    if ( $exception != null ) {
      self::$_ActivityExceptions[] = $activity;
      throw $exception;
    }

    return $return;
  }

  static public function insert($action, $type, $creator = null, $related = null, array $metaData = array(), Exception $exception = null, PropelPDO $con) {
    $activity = new Activity();

    if ( $creator instanceof Member )
      $activity->setMemberId($creator->getId());
    else if ( is_numeric($creator) )
      $activity->setMemberId($creator);

    if ( $related ) {
      if ( is_numeric($related) )
        $activity->setRelatedId($related);
      else if ( method_exists($related, 'getId') )
        $activity->setRelatedId($related->getId());
    }

    $activity
      ->setAction($action)
      ->setType($type)
      ->setDate(time());

    try {
      if ( $exception != null && !isset($metaData['exception']) ) {
        $metaData['exception'] = array(
          'message' => $exception->getMessage(),
          'trace'   => $exception->getTraceAsString(),
        );
      }

      $activity
        ->setMeta($metaData)
        ->save($con);

    } catch (Exception $e) {
      error_log(__METHOD__.': '.$e->__toString().': '.var_export($metaData, true));
      throw $e;
    }

    return $activity;
  }

  public function getMeta() {
    return json_decode(parent::getMeta(), true);
  }

  public function setMeta($v) {
    parent::setMeta(json_encode($v));
    return $this;
  }
}
