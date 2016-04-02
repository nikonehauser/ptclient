<?php



/**
 * Skeleton subclass for representing a row from the 'tbmt_email_validation' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.miltype
 */
class EmailValidation extends BaseEmailValidation
{

  static public function create($time, $email, $data, PropelPDO $con) {
    $emailValidation = new EmailValidation();

    $hash = md5($time.$email.'arbitaryKey:EmailValidation').$time;

    $emailValidation
      ->setHash($hash)
      ->setCreationdate($time)
      ->setMeta(json_encode($data))
      ->save($con);

    return $emailValidation;
  }

  static public function validateHash($hash) {
    return EmailValidationQuery::create()->filterByHash($hash)->findOne();
  }
}
