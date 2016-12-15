<?php



/**
 * Skeleton subclass for representing a row from the 'tbmt_nonce' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.miltype
 */
class Nonce extends BaseNonce
{

  static public function create(\Member $member) {
    $nonce = new Nonce();

    $now = time() + 60;
    $nonce
      ->setNonce($member->getId().$member->getHash().$now.uniqid())
      ->setMember($member)
      ->setDate($now)
      ->save();

    return $nonce;
  }
}
