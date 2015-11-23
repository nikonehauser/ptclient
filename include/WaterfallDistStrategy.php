<?php

namespace Tbmt;

class WaterfallDistStrategy extends DistributionStrategy {

  public function onReceivedMemberFee(\Member $member, \Member $referrer, $currency, $when, \PropelPDO $con) {
    // TODO - replace config value with real received value from bank transaction
    $memberFee = new \Tbmt\MemberFee(\Tbmt\Config::get('member_fee'), $member, $currency);

    // @see resources/snowball.txt - processes - P2
    if ( $referrer ) {

      $this->payAdvertisingFor($referrer, $memberFee, $member, $currency, $when, $con);

      $newAdvertisedCount = $referrer->convertOutstandingAdvertisedCount(1);
      if ( $newAdvertisedCount == 2 ) {
        $referrer->setFundsLevel(\Member::FUNDS_LEVEL2);
        $referrer->setMemberRelatedByParentId(null);
      }

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
  public function payAdvertisingFor(\Member $referrer, \Tbmt\MemberFee $memberFee, \Member $advertisedMember, $currency, $when, \PropelPDO $con) {
    $advertisedMemberId = $advertisedMember->getId();
    $transfer = $referrer->getCurrentTransferBundle($currency, $con);
    if ( $referrer->getFundsLevel() === \Member::FUNDS_LEVEL1 ) {

      // @see resources/snowball.txt - processes - P1
      $transaction = $transfer->createTransactionForReason(
        $referrer,
        \Transaction::REASON_ADVERTISED_LVL1,
        $advertisedMemberId,
        $when,
        $con
      );
      $memberFee->subtract($transaction->getAmount(), \Transaction::REASON_ADVERTISED_LVL1);

      $parent = $referrer->getMemberRelatedByParentId($con);
      if ( $parent ) {
        $parentTransfer = $parent->getCurrentTransferBundle($currency, $con);

        $parentTransaction = $parentTransfer->createTransactionForReason(
          $parent,
          \Transaction::REASON_ADVERTISED_INDIRECT,
          $advertisedMemberId,
          $when,
          $con
        );
        $memberFee->subtract($parentTransaction->getAmount(), \Transaction::REASON_ADVERTISED_INDIRECT);

        // As long as i am level 1 i wont receive more from them than just
        // the 5 euro. All further advertised members etc. will go on to the
        // account of my referrer
        $advertisedMember->setMemberRelatedByParentId($parent);
        $parentTransfer->save($con);
        $parent->save($con);
      }

    } else { // if ( $this->getFundsLevel() === Member::FUNDS_LEVEL2 ) {

      // @see resources/snowball.txt - processes - P3

      $advertisedMember->setMemberRelatedByParentId($referrer);

      $transaction = $transfer->createTransactionForReason(
        $referrer,
        \Transaction::REASON_ADVERTISED_LVL2,
        $advertisedMemberId,
        $when,
        $con
      );
      $memberFee->subtract($transaction->getAmount(), \Transaction::REASON_ADVERTISED_LVL2);
    }

    $transfer->save($con);

    \MemberBonusIds::payBonuses($memberFee, $advertisedMember, $currency, $when, $con);
  }

}