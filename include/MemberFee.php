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

    if ( !\Member::isRootAccountBonusReason($reason) )
      $this->toRootAccount -= $floatAmount;

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

    $rootAccount = \SystemStats::getRootAccount();
    $transfer = $rootAccount->getCurrentTransferBundle($this->currency, $con);
    $transaction = $transfer->createTransaction(
      $rootAccount,
      $this->toRootAccount,
      \Transaction::REASON_TRANSFER_TO_ROOT,
      $this->member->getId(),
      $when,
      $con
    );

    $rootAccount->save($con);
  }
}

?>