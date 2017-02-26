<?php



/**
 * Skeleton subclass for representing a row from the 'tbmt_payout' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.miltype
 */
class Payout extends BasePayout
{
  const RESULT_UNKNOWN = 1;
  const RESULT_SUCCESS = 2;
  const RESULT_FAILED = 3;
  const RESULT_REJECTED = 4;

  public function isCustomerFailure() {
    $result = $this->getResult();
    return $result == self::RESULT_FAILED || $result == self::RESULT_REJECTED;
  }

  public function getBankAccountText() {
    $externMeta = json_decode($this->getExternMeta(), true);

    if ( !empty($externMeta['account']) ) {
      $account = $externMeta['account'];
    } else {
      $internMeta = json_decode($this->getInternMeta(), true);
      if ( !empty($internMeta['account']) ) {
        $account = $internMeta['account'];
      }
    }

    if ( !$account || empty($account['details']) ) {
      return '_NOT_AVAILABLE_';
    }

    $result = [];
    foreach ( $account['details'] as $key => $val ) {
      if ( $key === 'legalType' )
        continue;

      $result[] = "$key: $val";
    }

    return implode("\r", $result);
  }

  public function getFailedReaonsText() {
    try {
      $data = json_decode($this->getFailedReason(), true);
      if ( !empty($data['errors']) ) {
        $message = '';
        foreach ( $data['errors'] as $value ) {
          if ( !empty($value['code']) )
            $message .= "\rCode: ".$value['code'];

          if ( !empty($value['message']) )
            $message .= "\rMessage: ".$value['message'];
        }

        if ( $message )
          return $message;
      }

    } catch (\Exception $e) {}

    return $this->getFailedReason();
  }
}
