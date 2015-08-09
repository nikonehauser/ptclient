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

  const ARR_RESULT_RETURN_KEY = '__return';

  const MK_BONUS_PAYMENT_AMOUNT = 'amount';

  const TYPE_SUCCESS = 1;
  const TYPE_FAILURE = 2;

  static public function exec($callable, $arrArgs, $action, $creator = null, $related = null, PropelPDO $con) {
    if ( !$con->beginTransaction() )
      throw new Exception('Could not begin transaction');

    try {
      $resIsArray = $res = false;
      $res = $return = call_user_func_array($callable, $arrArgs);
      $resIsArray = is_array($res);

      if ( $resIsArray && isset($res[self::ARR_RESULT_RETURN_KEY]) ) {
        $return = $res[self::ARR_RESULT_RETURN_KEY];
        unset($res[self::ARR_RESULT_RETURN_KEY]);
      }

      self::insert($action, self::TYPE_SUCCESS,
        $creator,
        $related,
        $resIsArray ? $res : [$res],
        null,
        $con
      );

      if ( !$con->commit() )
        throw new Exception('Could not commit transaction');

      return $return;
    } catch (Exception $e) {
        $con->rollBack();

        self::insert($action, self::TYPE_FAILURE,
          $creator,
          $related,
          $resIsArray ? $res : [$res],
          $e,
          $con
        );

        throw $e;
    }
  }

  static public function insert($action, $type, $creator = null, $related = null, array $metaData = array(), Exception $exception = null, PropelPDO $con) {
    $activity = new Activity();

    if ( $creator instanceof Member )
      $activity->setMemberId($creator->getId());
    else if ( is_numeric($creator) )
      $activity->setMemberId($creator);

    if ( $related instanceof Member ) {
      $activity->setRelatedId($related->getId());
    } else if ( is_numeric($related) )
      $activity->setRelatedId($related);

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
  }

  public function getMeta() {
    return json_decode(parent::getMeta(), true);
  }

  public function setMeta($v) {
    parent::setMeta(json_encode($v));
    return $this;
  }
}
