<?php

namespace Tbmt;

class WaterfallDistStrategy extends DistributionStrategy {

  const FUNDS_LEVEL_UPDATE_WITH = 2;

  public function onReceivedMemberFee(\Member $member, \Member $referrer, $currency, $when, $freeFromInvitation, \PropelPDO $con) {
    // @see resources/snowball.txt - processes - P2

    if ( !$freeFromInvitation )
      $this->payAdvertisingFor($referrer, $member, $currency, $when, $con);

    $this->updateTreeByFundsLevel($referrer, $member);

    $newAdvertisedCount = $referrer->convertOutstandingAdvertisedCount(1, $con);

    // before raising funds level! but after applying the advertised count
    MailHelper::sendFeeIncomeReferrer($referrer, $member, $freeFromInvitation);

    if ( $newAdvertisedCount >= self::FUNDS_LEVEL_UPDATE_WITH ) {
      $this->raiseFundsLevel($referrer);
    }

    $referrer->save($con);

    // It is the callers responsibility to save this member
    // $member->save();
  }

  /**
   * NOTE: Caller is supposed to save the changes to the user!
   *
   * @param  \Member $referrer
   * @return [type]
   */
  public function raiseFundsLevel(\Member $referrer) {
    $referrer->setFundsLevel(\Member::FUNDS_LEVEL2);
    $referrer->setMemberRelatedByParentId(null);
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
  public function payAdvertisingFor(\Member $referrer, \Member $advertisedMember, $currency, $when, \PropelPDO $con) {
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

        $parentTransfer->save($con);
        $parent->save($con);
      }

    } else { // if ( $this->getFundsLevel() >= Member::FUNDS_LEVEL2 ) {

      // @see resources/snowball.txt - processes - P3

      $transaction = $transfer->createTransactionForReason(
        $referrer,
        \Transaction::REASON_ADVERTISED_LVL2,
        $advertisedMemberId,
        $when,
        $con
      );
    }

    $transfer->save($con);

    \MemberBonusIds::payBonuses($advertisedMember, $currency, $when, $con);
  }

  public function updateTreeByFundsLevel(\Member $referrer, \Member $advertisedMember) {
    if ( $referrer->getFundsLevel() === \Member::FUNDS_LEVEL1 ) {
      // As long as i am level 1 i wont receive more from them than just
      // the 5 euro. All further advertised members etc. will go on to the
      // account of my !parent!
      $advertisedMember->setParentId($referrer->getParentId());

      // We have transferred the advertised member, therefore adjust the
      // bonus ids of this member either.
      // Since he is now "beyond the tree of another member" just apply
      // his new parent bonus ids.
      //
      // NACHTRAG: I dont remember why this was here before, but for know
      // it seems totaly right, not to rewrite the bonus ids?!!!
      //
      // if ( $referrerParent )
      //   $advertisedMember->setBonusIds($referrerParent->getBonusIds());


    } else { // if ( $this->getFundsLevel() >= Member::FUNDS_LEVEL2 ) {
      $advertisedMember->setMemberRelatedByParentId($referrer);
    }

  }

}