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
    'referer_num'          => [\Tbmt\TYPE_INT, ''],
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
  ];

  static public $SIGNUP_FORM_FILTERS = [
    'referer_num'          => \Tbmt\Validator::FILTER_NOT_EMPTY,
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
  ];

  static function initSignupForm(array $data = array()) {
    return \Tbmt\Arr::initMulti($data, self::$SIGNUP_FORM_FIELDS);
  }

  static function validateSignupForm(array $data = array()) {
    $data = self::initSignupForm($data);

    // Email is not required
    if ( $data['email'] === '' )
      unset($data['email']);

    $res = \Tbmt\Validator::getErrors($data, self::$SIGNUP_FORM_FILTERS);
    if ( $res !== false )
      return [false, $res, null];

    // Validate member number exists
    $parentMember = \MemberQuery::create()
      ->joinWith('Transfer')
      ->findOneByNum($data['referer_num']);
    if ( $parentMember == null ) {
      return [false, ['referer_num' => \Tbmt\Localizer::get('error.referer_num')], null];

    }
    // else if ( $parentMember->getPaid() == 0 ) {
    //   return [false, ['referer_num' => \Tbmt\Localizer::get('error.referer_paiment_outstanding')], null];
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
        // ->setRefererNum($data['referer_num'])
        ->setBankRecipient($data['bank_recipient'])
        ->setIban($data['iban'])
        ->setBic($data['bic'])
        ->setSignupDate($now);

      $member->setRefererMember($refererMember, $now, $con);
      $member->save($con);

      if ( !$con->commit() )
        throw new Exception('Could not commit transaction');

    } catch (Exception $e) {
        $con->rollBack();
        throw $e;
    }

    return $member;
  }

  /**
   *
   * @param Member    $referer
   * @param PropelPDO $con
   */
  public function setRefererMember(Member $referer, $when, PropelPDO $con) {
    $this->setRefererId($referer->getId());

    $referer->payAdvertisingFor($this, $when, $con);

    // @see resources/snowball.txt - processes - P2
    $advertisedCount = $referer->getAdvertisedCount();
    $advertisedCount++;
    if ( $advertisedCount == 2 ) {
      $referer->setFundsLevel(Member::FUNDS_LEVEL2);
      $referer->setMemberRelatedByParentId(null);
    }

    $referer->setAdvertisedCount($advertisedCount);
    $referer->save($con);
  }

  /**
   * Distribute provisions for member signup.
   *
   * @param  Member    $advertisedMember
   * @param  PropelPDO $con
   */
  public function payAdvertisingFor(Member $advertisedMember, $when, PropelPDO $con) {
    $transfer = $this->getCurrentTransferBundle($con);
    if ( $this->getFundsLevel() === Member::FUNDS_LEVEL1 ) {

      // @see resources/snowball.txt - processes - P1

      $transaction = $transfer->addAmount(Transaction::AMOUNT_ADVERTISED_LVL1);
      $transaction->setReason(Transaction::REASON_ADVERTISED_LVL1);
      $transaction->setDate($when);
      $transaction->save($con);

      $parent = $this->getMemberRelatedByParentId($con);
      if ( $parent ) {
        $parentTransfer = $parent->getCurrentTransferBundle($con);
        $parentTransaction = $parentTransfer->addAmount(Transaction::AMOUNT_ADVERTISED_INDIRECT);
        $parentTransaction->setReason(Transaction::REASON_ADVERTISED_INDIRECT);
        $parentTransaction->setDate($when);
        $parentTransaction->save($con);

        // As long as i am level 1 i wont receive more from them than just
        // the 5 euro. All further advertised members etc. will go on to the
        // account of my referer
        $advertisedMember->setMemberRelatedByParentId($parent);
        $parentTransfer->save($con);
      }

    } else { // if ( $this->getFundsLevel() === Member::FUNDS_LEVEL2 ) {

      // @see resources/snowball.txt - processes - P3

      $advertisedMember->setMemberRelatedByParentId($this);

      $transaction = $transfer->addAmount(Transaction::AMOUNT_ADVERTISED_LVL2);
      $transaction->setReason(Transaction::REASON_ADVERTISED_LVL2);
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
      ->orderBy(TransferPeer::STATE, Criteria::DESC)
      ->findOne($con);

    if ( !$transfer ) {
      $transfer = new Transfer();
      $transfer->setMember($this);

      if ( $this->getPaid() == 0 ) {
        $transfer->setState(Transfer::STATE_RESERVED);
      }
    }

    return $transfer;
  }

  /**
   * Set user as paid.
   * Update all current Transfers with state Transfer::STATE_RESERVED to
   * Transfer::STATE_COLLECT making them ready for processing.
   *
   */
  public function onReceivedMemberFee() {
    $this->setPaid(1);
    TransferQuery::create()
      ->filterByState(Transfer::STATE_RESERVED)
      ->filterByMember($this)
      ->update([TransferPeer::STATE => Transfer::STATE_COLLECT], $con);
  }
}
