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

  /**
   * The order of these number does matter!
   * Greater type number means more permissions.
   */
  const TYPE_SYSTEM = -1;
  const TYPE_MEMBER = 0;
  const TYPE_SUB_PROMOTER = 1;
  const TYPE_PROMOTER = 2;
  const TYPE_ORGLEADER = 3;
  const TYPE_MARKETINGLEADER = 4;
  const TYPE_SALES_MANAGER = 5;
  const TYPE_CEO = 6;
  const TYPE_ITSPECIALIST = 7;

  const INVITE_MARKETINGLEADER = 'ml880d914385a632784ce6b3a220ce5364';
  const INVITE_ORGLEADER = 'ol23bfe2e3a018ec8a833d7a1e6c562162';
  const INVITE_PROMOTER = 'pmc16758bfb94b6cfa38e8f9c30a6802ef';
  const INVITE_SUB_PROMOTER = 'subpmc1675d9d2fb94b8a8a38e8a2a662dcf5';
  const INVITE_MEMBER = 'me562dcf56bd9d2730c02d0e211e029201';

  const FUNDS_LEVEL1 = 1;
  const FUNDS_LEVEL2 = 2;

  static public $TYPE_TO_BONUS_REASON = [
    self::TYPE_SUB_PROMOTER => Transaction::REASON_SUB_PM_BONUS,
    self::TYPE_PROMOTER => Transaction::REASON_PM_BONUS,
    self::TYPE_ORGLEADER => Transaction::REASON_OL_BONUS,
    self::TYPE_MARKETINGLEADER => Transaction::REASON_VL_BONUS,
    self::TYPE_ITSPECIALIST => Transaction::REASON_IT_BONUS,
    self::TYPE_CEO => Transaction::REASON_CEO1_BONUS,
    self::TYPE_SALES_MANAGER => Transaction::REASON_SYLVHEIM,
  ];

  static public $NUM_TO_BONUS_REASON = [
    SystemStats::ACCOUNT_EXECUTIVE => Transaction::REASON_EXECUTIVE,
    SystemStats::ACCOUNT_NGO_PROJECTS => Transaction::REASON_NGO_PROJECTS,
    SystemStats::ACCOUNT_TARIC_WANI => Transaction::REASON_TARIC_WANI,
  ];

  static public $SIGNUP_FORM_FIELDS = [
    'referral_member_num'  => [\Tbmt\TYPE_INT, ''],
    'title'                => \Tbmt\TYPE_STRING,
    'invitation_code'      => \Tbmt\TYPE_STRING,
    'lastName'             => \Tbmt\TYPE_STRING,
    'firstName'            => \Tbmt\TYPE_STRING,
    'age'                  => \Tbmt\TYPE_STRING,
    'email'                => \Tbmt\TYPE_STRING,
    'city'                 => \Tbmt\TYPE_STRING,
    'zip_code'             => \Tbmt\TYPE_STRING,
    'country'              => [\Tbmt\TYPE_STRING, 'India'],
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
    'city'                 => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'zip_code'             => \Tbmt\Validator::FILTER_INDIA_PINCODE,
    // 'country'              => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'bank_recipient'       => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'iban'                 => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'bic'                  => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'accept_agbs'          => \FILTER_VALIDATE_BOOLEAN,
    'accept_valid_country' => \FILTER_VALIDATE_BOOLEAN,
    'password'             => \Tbmt\Validator::FILTER_PASSWORD,
  ];

  static public $BONUS_LEVEL_FORM_FIELDS = [
    'recipient_id'  => [\Tbmt\TYPE_INT, ''],
    'recipient_num' => [\Tbmt\TYPE_INT, ''],
    'amount'        => [\Tbmt\TYPE_INT, ''],
  ];

  static public $BONUS_LEVEL_FORM_FILTERS = [
    'recipient_num'  => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'amount' => [
      'filter' => \FILTER_VALIDATE_INT,
      'options' => [
        'min_range' => 0
      ],
      'errorLabel' => 'error.money_numeric'
    ]
  ];

  static public function initSignupForm(array $data = array()) {
    return \Tbmt\Arr::initMulti($data, self::$SIGNUP_FORM_FIELDS);
  }

  static public function validateSignupForm(array $data = array()) {
    $data = self::initSignupForm($data);

    if ( $data['password'] !== $data['password2'] )
      return [false, ['password' => \Tbmt\Localizer::get('error.password_unequal')], null, null];

    $res = \Tbmt\Validator::getErrors($data, self::$SIGNUP_FORM_FILTERS);
    if ( $res !== false )
      return [false, $res, null, null];

    // Validate member number exists
    $parentMember = \MemberQuery::create()
      ->filterByDeletionDate(null, Criteria::ISNULL)
      ->filterByType(self::TYPE_SYSTEM, Criteria::NOT_EQUAL)
      ->findOneByNum($data['referral_member_num']);
    if ( $parentMember == null ) {
      return [false, ['referral_member_num' => \Tbmt\Localizer::get('error.referral_member_num')], null, null];

    }
    // else if ( $parentMember->hadPaid() ) {
    //   return [false, ['referral_member_num' => \Tbmt\Localizer::get('error.referrer_paiment_outstanding')], null];
    // }

    $invitation = null;
    if ( $data['invitation_code'] !== '' ) {
      $invitation = \InvitationQuery::create()->findOneByHash($data['invitation_code']);
      if ( $parentMember == null )
        return [false, ['invitation_code' => \Tbmt\Localizer::get('error.invitation_code_inexisting')], null, null];

      if ( $invitation->getMemberId() != $parentMember->getId() )
        return [false, ['invitation_code' => \Tbmt\Localizer::get('error.invitation_code_invalid')], null, null];

      if ( $invitation->getAcceptedMemberId() )
        return [false, ['invitation_code' => \Tbmt\Localizer::get('error.invitation_code_used')], null, null];
    }

    if ( !isset($data['email']) )
      $data['email'] = '';

    return [true, $data, $parentMember, $invitation];
  }

  static public function createFromSignup($data, $referrerMember, Invitation $invitation = null, PropelPDO $con) {
    // This functions expects this parameter to be valid!
    // E.g. the result from self::validateSignupForm()

    $now = time();

    if ( !$con->beginTransaction() )
      throw new Exception('Could not begin transaction');

    try {
      $member = new Member();
      $member
        ->setFirstName($data['firstName'])
        ->setLastName($data['lastName'])
        // ->setNum() autoincrement
        ->setEmail($data['email'])
        ->setTitle($data['title'])
        ->setCity($data['city'])
        ->setZipCode($data['zip_code'])
        ->setCountry('India')
        ->setAge($data['age'])
        // ->setReferrerNum($data['referral_member_num'])
        ->setBankRecipient($data['bank_recipient'])
        ->setIban($data['iban'])
        ->setBic($data['bic'])
        ->setPassword($data['password'])
        ->setSignupDate($now)
        ->setPaidDate(null);

      $wasFreeInvitation = false;

      if ( $invitation ) {
        $invitationType = $invitation->getType();
        $member->setType($invitationType);

        // Special case if e.g. director invites another director.
        // The referrer of the referrer will be the referrer.
        // This is necessary because the same type can not be on same line vertical
        // but horizontal. E.g. Director can have more marketing leader under him
        // but only Directors next to him (NOT under him)
        if ( $invitationType > \Member::TYPE_MEMBER && $referrerMember->getType() == $invitationType )
          $referrerMember = $referrerMember->getReferrerMember();

        $invitation->setAcceptedDate($now);

        if ( $invitation->getFreeSignup() ) {
          $member->setFreeInvitation(1);
          $wasFreeInvitation = true;
        }

        // Deprecated code
        // if ( $invitation->getType() === self::TYPE_SUB_PROMOTER ) {
        //   $member->setSubPromoterReferral($invitation->getMeta()['promoter_id']);
        // }
      }

      $member->setReferrerMember($referrerMember, $con);
      $member->save($con);

      if ( $invitation ) {
        $invitation->setAcceptedMemberId($member->getId());
        $invitation->save($con);

        if ( $wasFreeInvitation )
          $member->onReceivedMemberFee(\Transaction::$BASE_CURRENCY, $now, true, $con);
      }

      if ( $wasFreeInvitation ) {
        \Tbmt\MailHelper::sendFreeSignupConfirm($member);
        \Tbmt\MailHelper::sendNewFreeRecruitmentCongrats($referrerMember, $member);

      } else {
        \Tbmt\MailHelper::sendSignupConfirm($member);
        \Tbmt\MailHelper::sendNewRecruitmentCongrats($referrerMember, $member);
      }

      if ( !$con->commit() )
        throw new Exception('Could not commit transaction');

    } catch (Exception $e) {
        $con->rollBack();
        throw $e;
    }

    return $member;
  }


  static public function activity_createFromSignup($data, $referrerMember, Invitation $invitation = null, PropelPDO $con) {
    $member = self::createFromSignup($data, $referrerMember, $invitation, $con);
    return [
      'data' => $data,
      'member' => $member->toArray(),
      'referer' => $referrerMember->toArray(),
      'invitation' => $invitation ? $invitation->toArray() : null,
      Activity::ARR_RESULT_RETURN_KEY => $member
    ];
  }


  static public function initBonusLevelForm(array $data = array()) {
    return \Tbmt\Arr::initMulti($data, self::$BONUS_LEVEL_FORM_FIELDS);
  }


  static public function validateBonusLevelForm(array $data = array())  {
    $data = self::initBonusLevelForm($data);
    $res = \Tbmt\Validator::getErrors($data, self::$BONUS_LEVEL_FORM_FILTERS);
    if ( $res !== false )
      return [false, $res, null];

    $recipient = \MemberQuery::create()
      ->filterByDeletionDate(null, Criteria::ISNULL)
      ->findOneByNum($data['recipient_num']);
    if ( $recipient == null ) {
      return [false, ['recipient_num' => \Tbmt\Localizer::get('error.member_num')], null];
    }

    if ( !$recipient->hadPaid() )
      return [false, ['recipient_num' => \Tbmt\Localizer::get('error.member_num_unpaid')], null];

    return [true, $data, $recipient];

  }

  static public function getByNum($num) {
    $member = MemberQuery::create()
      ->filterByDeletionDate(null, Criteria::ISNULL)
      ->findOneByNum($num);

    if ( !$member )
      throw new Exception('Coud not find member: '.$num);

    return $member;
  }

  static public function getTransactionReasonByType($type) {
    if ( !isset(self::$TYPE_TO_BONUS_REASON[$type]) ) {
      return null;
    }

    return self::$TYPE_TO_BONUS_REASON[$type];
  }

  public function getTransactionReasonByMemberType() {
    return self::getTransactionReasonByType($this->getType());
  }

  public function getTransactionReasonByMemberNum() {
    $num = $this->getNum();
    if ( isset(self::$NUM_TO_BONUS_REASON[$num]) )
      return self::$NUM_TO_BONUS_REASON[$num];

    return null;
  }

  public function hadPaid() {
    return $this->getPaidDate() > 1;
  }

  public function isMarkedAsPaid() {
    return $this->getPaidDate() >= 1;
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

  public function getAdvertisedCountTotal() {
    return $this->getAdvertisedCount() + $this->getOutstandingAdvertisedCount();
  }

  public function getOutstandingTotal() {
    return json_decode(parent::getOutstandingTotal(), true);
  }

  public function setOutstandingTotal($v) {
    parent::setOutstandingTotal(json_encode($v));
  }

  public function getTransferredTotal() {
    return json_decode(parent::getTransferredTotal(), true);
  }

  public function setTransferredTotal($v) {
    parent::setTransferredTotal(json_encode($v));
    return $this;
  }

  public function getFirstDueDate() {
    return strtotime(\Tbmt\Config::get('duedate_first'), $this->getSignupDate());
  }

  public function getSecondDueDate() {
    return strtotime(\Tbmt\Config::get('duedate_second'), $this->getSignupDate());
  }

  public function activity_setBonusLevel($amount, PropelPDO $con) {
    $this->setBonusLevel($amount);

    $bonusIds = $this->getBonusIds();
    $bonusIds = MemberBonusIds::populate($this, $bonusIds);
    if ( $bonusIds !== false )
      $this->setBonusIds($bonusIds);

    $children = $this->applyToAllChildren(
      function($parent, $child) use ($amount, $con) {
        $bonusIds = $child->getBonusIds();
        $bonusIds = MemberBonusIds::populate($parent, $bonusIds);
        if ( $bonusIds !== false ) {
          $child->setBonusIds($bonusIds);
          $child->save($con);
        }
      },
      $con
    );

    $this->save($con);

    return [\Activity::MK_BONUS_PAYMENT_AMOUNT => $amount];
  }

  /**
   * This is a dangerous method cause the tree of this member might exceed
   * over thousands/millions of members !?
   *
   */
  public function applyToAllChildren($callable, PropelPDO $con, $byColumn = 'ReferrerId') {
    $ids = [$this->getId()];
    $filterByColumn = "filterBy$byColumn";

    $rows = [];
    do {
      $members = \MemberQuery::create()
        ->$filterByColumn($ids, \Criteria::IN)
        ->find($con);

      $newIds = [];
      foreach ($members as $member) {
        $newIds[] = $member->getId();

        call_user_func_array($callable, [$this, $member]);
      }
      $ids = $newIds;
    } while( count($members) > 0 );
  }

  /**
   *
   * @param Member    $referrer
   * @param PropelPDO $con
   */
  public function setReferrerMember(Member $referrer, PropelPDO $con) {
    $referrerId = $referrer->getId();
    $this->setReferrerId($referrerId);
    $this->setParentId($referrerId);

    $bonusIds = $referrer->getBonusIds();
    $referrerType = $referrer->getType();
    if ( $referrerType > self::TYPE_MEMBER ) {
      $newBonusIds = MemberBonusIds::populate($referrer, $bonusIds);
      if ( $newBonusIds !== false )
        $bonusIds = $newBonusIds;
    }

    if ( $bonusIds )
      $this->setBonusIds($bonusIds);

    $referrer->addOutstandingAdvertisedCount(1);
    $referrer->save($con);
  }

  public function getReferrerMember(PropelPDO $con = null) {
    return $this->getMemberRelatedByReferrerId($con);
  }

  public function getParentMember(PropelPDO $con = null) {
    return $this->getMemberRelatedByParentId($con);
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
  public function addOutstandingTotal($doubleAmount, $currency) {
    $v = $this->getOutstandingTotal();
    if ( !isset($v[$currency]) )
      $v[$currency] = 0;

    $v[$currency] += $doubleAmount;
    $this->setOutstandingTotal($v);
  }

  public function transferOutstandingTotal($doubleAmount, $currency) {
    $v = $this->getOutstandingTotal();
    if ( !isset($v[$currency]) || $v[$currency] < $doubleAmount )
      throw new \Exception('Can not transfer non existing amount.');

    $v[$currency] -= $doubleAmount;
    $this->setOutstandingTotal($v);

    $v = $this->getTransferredTotal();
    if ( !isset($v[$currency]) )
      $v[$currency] = 0;

    $newTransferredTotal = $v[$currency] += $doubleAmount;
    $this->setTransferredTotal($v);

    return $newTransferredTotal;
  }

  /**
   * Get one Transfer::STATE_COLLECT transfer to bundle. If none exists one
   * will be created. If the users state is NOT paid the state will set
   * to Transfer::STATE_RESERVED.
   *
   * NOTE: This transfer wont get saved by this method!
   *
   * @param  PropelPDO $con
   * @return [type]
   */
  public function getCurrentTransferBundle($currency, PropelPDO $con) {
    // $transfer = TransferQuery::create()
    //   ->filterByState([Transfer::STATE_COLLECT, Transfer::STATE_RESERVED])
    //   ->filterByMember($this)
    //   ->orderBy(TransferPeer::STATE, Criteria::DESC)
    //   ->findOne($con);

    // SELECT * FROM ... FOR UPDATE
    // to ensure consistency through table row lock
    $sql = "SELECT * FROM ".TransferPeer::TABLE_NAME." WHERE"
            ." member_id = :member_id AND"
            ." currency = :currency AND"
            ." state in (".Transfer::STATE_COLLECT.", ".Transfer::STATE_RESERVED.")"
            ." ORDER BY state desc"
            ." FOR UPDATE";
    $stmt = $con->prepare($sql);
    $stmt->execute(array(
      ':member_id' => $this->getId(),
      ':currency' => $currency
    ));

    $formatter = new PropelObjectFormatter();
    $formatter->setClass('Transfer');
    $transfer = $formatter->format($stmt);
    if ( count($transfer) > 0 )
      $transfer = $transfer[0];
    else
      $transfer = null;

    if ( !$transfer ) {
      $transfer = new Transfer();
      $transfer->setMember($this);
      $transfer->setCurrency($currency);

      if ( !$this->hadPaid() ) {
        $transfer->setState(Transfer::STATE_RESERVED);
      }
    }

    return $transfer;
  }

  public function getOpenCollectingTransfers(PropelPDO $con) {
    $sql = "SELECT * FROM ".TransferPeer::TABLE_NAME." WHERE"
            ." member_id = :member_id AND"
            ." state = :state"
            ." FOR UPDATE";
    $stmt = $con->prepare($sql);
    $stmt->execute(array(
      ':member_id' => $this->getId(),
      ':state' => Transfer::STATE_COLLECT
    ));

    $formatter = new PropelObjectFormatter();
    $formatter->setClass('Transfer');
    return $formatter->format($stmt);
  }

  /**
   * Set user as paid and spread provisions.
   *
   * Update all current Transfers with state Transfer::STATE_RESERVED to
   * Transfer::STATE_COLLECT making them ready for processing.
   *
   * NOTE: Caller is responsible for transactional processing.
   *
   */
  public function onReceivedMemberFee($currency, $when, $freeFromInvitation, PropelPDO $con) {
    if ( $this->hadPaid() )
      throw new \Exception('Paid member receiving fee again!');

    $referrer = $this->getReferrerMember();
    if ( !$referrer ) {
      throw new Exception('Member ('.$this->getId().') has no referrer!');
    }

    if ( !$this->isMarkedAsPaid() ) {
      // Prevent multiple income of the same message. Because this situation
      // can ocure more often because of the removal of not paying members
      // {@see $this->fireReservedReceivedMemberFeeEvents}
      if ( !$freeFromInvitation )
        \Tbmt\MailHelper::sendFeeIncome($this);
    }

    if ( !$freeFromInvitation )
      \Tbmt\MailHelper::sendFeeIncomeReferrer($referrer, $this);

    if ( $referrer && !$referrer->hadPaid() ) {
      // if the parent hasnt paid yet. reserve this event until his fee is
      // comming in or we kick him from the list.
      $referrer->reserveReceivedMemberFeeEvent($this, $currency, $when, $freeFromInvitation, $con);
      return;
    }

    $this->setPaidDate($when);
    TransferQuery::create()
      ->filterByState(Transfer::STATE_RESERVED)
      ->filterByMember($this)
      ->update(['State' => Transfer::STATE_COLLECT], $con);

    \Tbmt\DistributionStrategy::getInstance()->onReceivedMemberFee(
      $this,
      $referrer,
      $currency,
      $when,
      $freeFromInvitation,
      $con
    );

    $this->fireReservedReceivedMemberFeeEvents($con);
    $this->save($con);
  }

  public function reserveReceivedMemberFeeEvent($paidMember, $currency, $when, $freeFromInvitation, PropelPDO $con) {
    // $this = the yet unpaid parent of $paidMember
    $reservedPaidEvent = new ReservedPaidEvent();
    $reservedPaidEvent->setMemberRelatedByPaidId($paidMember);
    $reservedPaidEvent->setMemberRelatedByUnpaidId($this);
    $reservedPaidEvent->setCurrency($currency);
    $reservedPaidEvent->setIsFreeInvitation($freeFromInvitation);
    $reservedPaidEvent->setDate($when);
    $reservedPaidEvent->save($con);

    // This will result in payment received message in account.index view
    $paidMember->setPaidDate(1);
    $paidMember->save($con);
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
        $paidMember->onReceivedMemberFee($event->getCurrency(), $event->getDate('U'), $event->getIsFreeInvitation(), $con);
        $idsStack[] = $paidMember->getId();

        $event->delete($con);
      }
    }
  }

  /**
   * Delete this member and adopt his children to his referrer.
   * Calling onReceivedMemberFee.
   *
   * @param  PropelPDO $con
   * @return
   */
  public function deleteAndUpdateTree(PropelPDO $con) {
    $children = MemberQuery::create()
      ->filterByReferrerId($this->getId())
      ->find($con);

    $thisReferrer = $this->getMemberRelatedByReferrerId();
    $thisReferrerHadPaid = $thisReferrer->hadPaid();

    $updateCount = ReservedPaidEventQuery::create()
      ->filterByUnpaidId($this->getId())
      ->update(['UnpaidId' => $thisReferrer->getId()], $con);

    foreach ($children as $child) {
      $child->setReferrerMember($thisReferrer, $con);
      $child->save($con);
    }

    if ( $updateCount > 0 ) {
      $thisReferrer->fireReservedReceivedMemberFeeEvents($con);
      $thisReferrer->save($con);
    }

    $this->setDeletionDate(time());
    $this->save($con);
  }
}

class MemberBonusIds {
  static public function toArray($bonusIds) {
    return json_decode($bonusIds, true);
  }

  static public function toString($bonusIds) {
    return json_encode($bonusIds);
  }

  /**
   * NOTE: Function returns {bolean} false if the ids were not changed.
   *
   */
  static public function populate(Member $referrerMember, $bonusIds) {
    $arr = self::toArray($bonusIds);
    $memberId = $referrerMember->getId();
    $type = $referrerMember->getType();
    if ( isset($arr[$memberId]) && $arr[$memberId] === $type )
      return false;

    // These are singleton bonuses and have to exist once only on each
    // member
    if ( in_array($type, Member::$TYPE_TO_BONUS_REASON) && in_array($type, $arr) )
      throw new Exception('InvalidSituationException: Tryed to set bonus type: "'.$type.'" twice.');

    $arr[$memberId] = $type;
    return self::toString($arr);
  }

  static public function payBonuses(\Tbmt\MemberFee $memberFee, Member $payingMember, $currency, $when, PropelPDO $con) {
    $bonusByIds = self::toArray($payingMember->getBonusIds());
    if ( !is_array($bonusByIds) )
      return;

    // Each member carries all members which receive bonus for his signup
    $bonusIds = array_keys($bonusByIds);
    if ( count($bonusIds) === 0 )
      return;

    $relatedId = $payingMember->getId();

    // Select all bonus members
    $bonusMembers = MemberQuery::create()
      ->filterByDeletionDate(null, Criteria::ISNULL)
      ->filterById($bonusIds, Criteria::IN)
      ->find($con);

    $spreadBonuses = [];
    $toBeSaved = [];
    foreach ( $bonusMembers as $member ) {
      $type = $member->getType();
      $transfer = null;

      // Even so the system still supporting this, it was declarated deprecated by marcus sheffold
      if ( $member->getBonusLevel() > 0 ) {
        // Pay the individual bonus set for this member
        $transfer = self::doPay(
          $memberFee,
          $transfer,
          $member,
          \Transaction::REASON_CUSTOM_BONUS_LEVEL,
          $relatedId,
          $currency,
          $when,
          $con
        );
      }

      $reasonByType = $member->getTransactionReasonByType($type);
      if ( $reasonByType !== null ) {
        $transfer = self::doPay(
          $memberFee,
          $transfer,
          $member,
          $reasonByType,
          $relatedId,
          $currency,
          $when,
          $con
        );
      }

      $reasonByMemberNum = $member->getTransactionReasonByMemberNum();
      if ( $reasonByMemberNum !== null ) {
        $transfer = self::doPay(
          $memberFee,
          $transfer,
          $member,
          $reasonByMemberNum,
          $relatedId,
          $currency,
          $when,
          $con
        );
      }

      // lazy save all these objects later to prevent multiple
      // database update's cause there might get more bonuses spread.
      if ( $transfer ) {
        $toBeSaved[] = $member;
        $toBeSaved[] = $transfer;

        // Save the payed bonuses by type
        $spreadBonuses[$type] = [$transfer, $member, $reasonByType];
      }
    }

    $propagateBonuses = [
      Member::TYPE_PROMOTER,
      Member::TYPE_ORGLEADER,
      Member::TYPE_MARKETINGLEADER,
      Member::TYPE_SALES_MANAGER,
      Member::TYPE_CEO,
    ];

    $collectUnspreadBonusus = [];
    foreach ( $propagateBonuses as $memberType ) {
      if ( !isset($spreadBonuses[$memberType]) ) {
        // This bonus is unspread, so collect it to give it to the next higher
        // existing member type
        $collectUnspreadBonusus[] = $memberType;
      } else {
        $memberObjects = $spreadBonuses[$memberType];

        foreach ($collectUnspreadBonusus as $memberType) {
          self::doPay(
            $memberFee,
            $memberObjects[0], // member {object}
            $memberObjects[1], // member transfer {object}
            \Member::getTransactionReasonByType($memberType), // reason by type {integer}
            $relatedId,
            $currency,
            $when,
            $con
          );
        }
        // Reset
        $collectUnspreadBonusus = [];
      }
    }

    foreach ( $toBeSaved as $row ) {
      $row->save($con);
    }
  }

  static private function doPay(\Tbmt\MemberFee $memberFee, Transfer $transfer = null, Member $member, $reason, $relatedId, $currency, $when, PropelPDO $con) {
    if ( $transfer === null )
      $transfer = $member->getCurrentTransferBundle($currency, $con);

    $transaction = $transfer->createTransactionForReason(
      $member,
      $reason,
      $relatedId,
      $when,
      $con
    );
    $memberFee->subtract($transaction->getAmount(), $reason);

    return $transfer;
  }
}
