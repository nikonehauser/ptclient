<?php

namespace Tbmt;

class MemberFee {

  private $toSystemAccount;
  private $toRootAccount;
  private $currency;
  private $member;

  public function __construct($memberFee, \Member $member, $currency) {
    $this->toSystemAccount = $memberFee;
    $this->toRootAccount = $memberFee;
    $this->currency = $currency;
    $this->member = $member;
  }

  public function subtract($floatAmount, $reason) {
    $this->toSystemAccount -= $floatAmount;
  }

  public function checkRemainGreaterZero() {
    if ( $this->toSystemAccount < 0 )
      throw new ProvisionExceedMemberFeeException();
  }

  public function addRemainingToAccounts($when, \PropelPDO $con) {
    $systemAccount = \SystemStats::getSystemAccount();
    $transfer = $systemAccount->getCurrentTransferBundle($this->currency, $con);
    $transaction = $transfer->createTransaction(
      $systemAccount,
      $this->toSystemAccount,
      \Transaction::REASON_REMAINING_MEMBER_FEE,
      $this->member->getId(),
      $when,
      $con
    );

    $systemAccount->save($con);
  }
}

?>