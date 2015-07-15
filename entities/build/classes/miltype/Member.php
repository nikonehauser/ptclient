<?php



/**
 * Skeleton subclass for representing a row from the 'tbmt_member' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.miltype
 */
class Member extends BaseMember
{

  const TYPE_MEMBER = 0;
  const TYPE_PROMOTER = 1;
  const TYPE_ORGLEADER = 2;

  const FUNDS_LEVEL1 = 1;
  const FUNDS_LEVEL2 = 2;

  static public $SIGNUP_FORM_FIELDS = [
    'referral_member_num'  => [\Tbmt\TYPE_INT, ''],
    'title'                => \Tbmt\TYPE_STRING,
    'lastName'             => \Tbmt\TYPE_STRING,
    'firstName'            => \Tbmt\TYPE_STRING,
    'age'                  => \Tbmt\TYPE_STRING,
    'email'                => \Tbmt\TYPE_STRING,
    'city'                 => \Tbmt\TYPE_STRING,
    'country'              => \Tbmt\TYPE_STRING,
    'bank_recipient'       => \Tbmt\TYPE_STRING,
    'iban'                 => \Tbmt\TYPE_STRING,
    'bic'                  => \Tbmt\TYPE_STRING,
    'accept_agbs'          => \Tbmt\TYPE_STRING,
    'accept_valid_country' => \Tbmt\TYPE_STRING,
    'password'             => \Tbmt\TYPE_STRING,
    'password2'            => \Tbmt\TYPE_STRING,
  ];

  static public $SIGNUP_FORM_FILTERS = [
    'referral_member_num'  => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'email'                => \FILTER_VALIDATE_EMAIL,
    'lastName'             => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'firstName'            => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'age'                  => [
      'filter' => \FILTER_VALIDATE_INT,
      'options' => [
        'min_range' => 18,
        'max_range' => 110
      ],
      'errorLabel' => 'error.age_of_18'
    ],
    'firstName'            => \Tbmt\Validator::FILTER_NOT_EMPTY,

    'city'                 => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'country'              => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'bank_recipient'       => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'iban'                 => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'bic'                  => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'accept_agbs'          => \FILTER_VALIDATE_BOOLEAN,
    'accept_valid_country' => \FILTER_VALIDATE_BOOLEAN,
    'password'             => \Tbmt\Validator::FILTER_PASSWORD,
  ];

  static function initSignupForm(array $data = array()) {
    return \Tbmt\Arr::initMulti($data, self::$SIGNUP_FORM_FIELDS);
  }

  static function validateSignupForm(array $data = array()) {
    $data = self::initSignupForm($data);

    // Email is not required
    if ( $data['email'] === '' )
      unset($data['email']);

    if ( $data['password'] !== $data['password2'] )
      return [false, ['password' => \Tbmt\Localizer::get('error.password_unequal')], null];

    $res = \Tbmt\Validator::getErrors($data, self::$SIGNUP_FORM_FILTERS);
    if ( $res !== false )
      return [false, $res, null];

    // Validate member number exists
    $parentMember = \MemberQuery::create()
      ->findOneByNum($data['referral_member_num']);
    if ( $parentMember == null ) {
      return [false, ['referral_member_num' => \Tbmt\Localizer::get('error.referral_member_num')], null];

    }
    // else if ( $parentMember->hadPaid() ) {
    //   return [false, ['referral_member_num' => \Tbmt\Localizer::get('error.referer_paiment_outstanding')], null];
    // }

    if ( !isset($data['email']) )
      $data['email'] = '';

    return [true, $data, $parentMember];
  }

  static public function createFromSignup($data, $refererMember, PropelPDO $con) {
    // This functions expects this parameter to be valid!
    // E.g. the result from self::validateSignupForm()

    if ( !$con->beginTransaction() )
      throw new Exception('Could not begin transaction');

    $now = time();

    try {
      $member = new Member();
      $member
        ->setFirstName($data['firstName'])
        ->setLastName($data['lastName'])
        // ->setNum() autoincrement
        ->setEmail($data['email'])
        ->setTitle($data['title'])
        ->setCity($data['city'])
        ->setCountry($data['country'])
        ->setAge($data['age'])
        // ->setRefererNum($data['referral_member_num'])
        ->setBankRecipient($data['bank_recipient'])
        ->setIban($data['iban'])
        ->setBic($data['bic'])
        ->setPassword($data['password'])
        ->setSignupDate($now);

      $member->setRefererMember($refererMember, $con);
      $member->save($con);

      if ( !$con->commit() )
        throw new Exception('Could not commit transaction');

    } catch (Exception $e) {
        $con->rollBack();
        throw $e;
    }

    return $member;
  }

  public function hadPaid() {
    return $this->getPaidDate() !== null;
  }

  /**
   * Sets the user's password
   *
   * @param string $password
   * @return User
   */
  public function setPassword($password) {
    parent::setPassword(\Tbmt\Cryption::getPasswordHash($password));
    return $this;
  }

  /**
   *
   * @param Member    $referer
   * @param PropelPDO $con
   */
  public function setRefererMember(Member $referer, PropelPDO $con) {
    $refererId = $referer->getId();
    $this->setRefererId($refererId);
    $this->setParentId($refererId);

    $referer->addOutstandingAdvertisedCount(1);
    $referer->save($con);
  }

  public function addOutstandingAdvertisedCount($int) {
    $this->setOutstandingAdvertisedCount($this->getOutstandingAdvertisedCount() + $int);
  }

  public function convertOutstandingAdvertisedCount($int) {
    $this->setOutstandingAdvertisedCount($this->getOutstandingAdvertisedCount() - $int);
    $newAdvertisedCount = $this->getAdvertisedCount() + $int;
    $this->setAdvertisedCount($newAdvertisedCount);

    return $newAdvertisedCount;
  }

  /**
   * Adds the given amount to this transfer.
   * @param [type] $intAmount
   */
  public function addOutstandingTotal($intAmount) {
    $this->setOutstandingTotal($this->getOutstandingTotal() + $intAmount);
  }

  public function convertOutstandingTotal($double) {
    $this->setOutstandingTotal($this->getOutstandingTotal() - $double);
    $newTransferedTotal = $this->getTransferedTotal() + $double;
    $this->setTransferedTotal($newTransferedTotal);

    return $newTransferedTotal;
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
  public function payAdvertisingFor(Member $advertisedMember, $when, PropelPDO $con) {
    $advertisedMemberId = $advertisedMember->getId();
    $transfer = $this->getCurrentTransferBundle($con);
    if ( $this->getFundsLevel() === Member::FUNDS_LEVEL1 ) {

      // @see resources/snowball.txt - processes - P1

      $this->addOutstandingTotal(Transaction::AMOUNT_ADVERTISED_LVL1);
      $transaction = $transfer->addAmount(Transaction::AMOUNT_ADVERTISED_LVL1);
      $transaction->setReason(Transaction::REASON_ADVERTISED_LVL1);
      $transaction->setRelatedId($advertisedMemberId);
      $transaction->setDate($when);
      $transaction->save($con);

      $parent = $this->getMemberRelatedByParentId($con);
      if ( $parent ) {
        $parentTransfer = $parent->getCurrentTransferBundle($con);

        $parent->addOutstandingTotal(Transaction::AMOUNT_ADVERTISED_INDIRECT);
        $parentTransaction = $parentTransfer->addAmount(Transaction::AMOUNT_ADVERTISED_INDIRECT);
        $parentTransaction->setReason(Transaction::REASON_ADVERTISED_INDIRECT);
        $parentTransaction->setRelatedId($advertisedMemberId);
        $parentTransaction->setDate($when);
        $parentTransaction->save($con);

        // As long as i am level 1 i wont receive more from them than just
        // the 5 euro. All further advertised members etc. will go on to the
        // account of my referer
        $advertisedMember->setMemberRelatedByParentId($parent);
        $parentTransfer->save($con);
        $parent->save($con);
      }

    } else { // if ( $this->getFundsLevel() === Member::FUNDS_LEVEL2 ) {

      // @see resources/snowball.txt - processes - P3

      $advertisedMember->setMemberRelatedByParentId($this);

      $this->addOutstandingTotal(Transaction::AMOUNT_ADVERTISED_LVL2);
      $transaction = $transfer->addAmount(Transaction::AMOUNT_ADVERTISED_LVL2);
      $transaction->setReason(Transaction::REASON_ADVERTISED_LVL2);
      $transaction->setRelatedId($advertisedMemberId);
      $transaction->setDate($when);
      $transaction->save($con);

    }

    $transfer->save($con);
  }

  /**
   * Get one Transfer::STATE_COLLECT transfer to bundle. If none exists one
   * will be created. If the users state is NOT paid the state will set
   * to Transfer::STATE_RESERVED. This transfer wont get saved here!
   *
   * @param  PropelPDO $con
   * @return [type]
   */
  public function getCurrentTransferBundle(PropelPDO $con) {
    $transfer = TransferQuery::create()
      ->filterByState([Transfer::STATE_COLLECT, Transfer::STATE_RESERVED])
      ->filterByMember($this)
      ->joinWith('Member')
      ->orderBy(TransferPeer::STATE, Criteria::DESC)
      ->findOne($con);

    if ( !$transfer ) {
      $transfer = new Transfer();
      $transfer->setMember($this);

      if ( $this->hadPaid() ) {
        $transfer->setState(Transfer::STATE_RESERVED);
      }
    }

    return $transfer;
  }

  /**
   * Set user as paid and spread provisions.
   *
   * Update all current Transfers with state Transfer::STATE_RESERVED to
   * Transfer::STATE_COLLECT making them ready for processing.
   *
   */
  public function onReceivedMemberFee($when, PropelPDO $con) {
    $referer = $this->getMemberRelatedByParentId($con);

    if ( $referer && !$referer->hadPaid() ) {
      // if the parent hasnt paid yet. reserve this event until his fee is
      // comming in or we kick him from the list.
      $referer->reserveReceivedMemberFeeEvent($this, $when, $con);
      return;
    }

    $this->setPaidDate($when);
    TransferQuery::create()
      ->filterByState(Transfer::STATE_RESERVED)
      ->filterByMember($this)
      ->update(['State' => Transfer::STATE_COLLECT], $con);

    // @see resources/snowball.txt - processes - P2
    if ( $referer ) {

      $referer->payAdvertisingFor($this, $when, $con);

      $newAdvertisedCount = $referer->convertOutstandingAdvertisedCount(1);
      if ( $newAdvertisedCount == 2 ) {
        $referer->setFundsLevel(Member::FUNDS_LEVEL2);
        $referer->setMemberRelatedByParentId(null);
      }

      $referer->save($con);
    }

    $this->fireReservedReceivedMemberFeeEvents($con);

    $this->save($con);
  }

  public function reserveReceivedMemberFeeEvent($paidMember, $when, PropelPDO $con) {
    // $this = the yet unpaid parent of $paidMember
    $reservedPaidEvent = new ReservedPaidEvent();
    $reservedPaidEvent->setMemberRelatedByPaidId($paidMember);
    $reservedPaidEvent->setMemberRelatedByUnpaidId($this);
    $reservedPaidEvent->setDate($when);
    $reservedPaidEvent->save($con);
  }

  /**
   * Does not save $this member.
   * @param  PropelPDO $con
   */
  public function fireReservedReceivedMemberFeeEvents(PropelPDO $con) {
    $idsStack = [$this->getId()];
    while ( count($idsStack) > 0 ) {
      $reservedEvents = ReservedPaidEventQuery::create()
        // ->joinWith()
        ->filterByUnpaidId(array_pop($idsStack))
        ->find($con);

      foreach ( $reservedEvents as $event) {
        $paidMember = $event->getMemberRelatedByPaidId($con);
        $paidMember->onReceivedMemberFee($event->getDate('U'), $con);
        $idsStack[] = $paidMember->getId();

        $event->delete($con);
      }
    }
  }

  /**
   * Delete this member and adopt his children to his referer.
   * Calling onReceivedMemberFee.
   *
   * @param  PropelPDO $con
   * @return
   */
  public function deleteAndUpdateTree(PropelPDO $con) {
    $children = MemberQuery::create()
      ->filterByRefererId($this->getId())
      ->find($con);

    $thisReferer = $this->getMemberRelatedByRefererId();
    $thisRefererHadPaid = $thisReferer->hadPaid();

    $updateCount = ReservedPaidEventQuery::create()
      ->filterByUnpaidId($this->getId())
      ->update(['UnpaidId' => $thisReferer->getId()], $con);

    foreach ($children as $child) {
      $child->setRefererMember($thisReferer, $con);
      $child->save($con);
      // if ( $thisRefererHadPaid && !$child->hadPaid() )
      //   $child->fireReservedReceivedMemberFeeEvents($con);
    }

    if ( $updateCount > 0 ) {
      $thisReferer->fireReservedReceivedMemberFeeEvents($con);
      $thisReferer->save($con);
    }

    $this->delete($con);
  }
}
