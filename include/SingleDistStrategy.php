<?php

namespace Tbmt;

class SingleDistStrategy extends DistributionStrategy {


  public function onReceivedMemberFee(\Member $member, \Member $referrer, $currency, $when, \PropelPDO $con) {
    // TODO - replace config value with real received value from bank transaction
    $memberFee = new \Tbmt\MemberFee(\Tbmt\Config::get('member_fee'), $member, $currency);

    // @see resources/snowball.txt - processes - P2
    if ( $referrer ) {

      $this->payAdvertisingFor($referrer, $memberFee, $member, $currency, $when, $con);
      $referrer->save($con);
    }

    $memberFee->checkRemainGreaterZero();
    $memberFee->addRemainingToAccounts($when, $con);
  }

  /**
   * Distribute provisions for member signup.
   *
   * ATTENTION: This method does NOT save changes to $advertisedMember. The
   * caller is required to save this object!
   *
   * @param  Member    $advertisedMember
   * @param  PropelPDO $con
   */
  public function payAdvertisingFor(\Member $referrer, \Tbmt\MemberFee $memberFee, \Member $advertisedMember, $currency, $when, $freeFromInvitation, \PropelPDO $con) {
    $advertisedMemberId = $advertisedMember->getId();
    $transfer = $referrer->getCurrentTransferBundle($currency, $con);

    $transaction = $transfer->createTransactionForReason(
      $referrer,
      \Transaction::REASON_ADVERTISED_LVL1,
      $advertisedMemberId,
      $when,
      $con
    );
    $memberFee->subtract($transaction->getAmount(), \Transaction::REASON_ADVERTISED_LVL1);
    $transfer->save($con);

    \MemberBonusIds::payBonuses($memberFee, $advertisedMember, $currency, $when, $con);
  }

}