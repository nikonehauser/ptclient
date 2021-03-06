<?php

class DbEntityHelper {
  /**
   * @var PropelPDO
   */
  static public $con = null;

  static private $it_member = null;

  static private $emailCounter = 0;

  static public $currency = 'EUR';

  static public function setCurrency($currency) {
    self::$currency = $currency;
  }

  static public function setCon(PropelPDO $con) {
    self::$con = $con;
  }

  static public function truncateDatabase(PropelPDO $con = null) {
    self::truncateTables([
      MemberPeer::TABLE_NAME,
      MemberDataPeer::TABLE_NAME,
      InvitationPeer::TABLE_NAME,
      ActivityPeer::TABLE_NAME,
      EmailValidationPeer::TABLE_NAME,
      ReservedPaidEventPeer::TABLE_NAME,
      TransactionPeer::TABLE_NAME,
      TransferPeer::TABLE_NAME,
    ], $con ? $con : self::$con);
  }

  static public function truncateTables(array $tables, PropelPDO $con = null) {
    if ( !$con )
      $con = self::$con;

    $con->exec('TRUNCATE TABLE '.implode(',', $tables).' CASCADE;');
  }

  static private $memberDefaults = [
    'Title'         => 'unknown',
    'LastName'      => 'unknown',
    'FirstName'     => 'unknown',
    'Age'           => 99,
    'City'          => 'unknown',
    'Country'       => 'unknown',
    'ZipCode'       => '504231',
    'BankRecipient' => 'unknown',
    'Iban'          => 'unknown',
    'Bic'           => 'unknown',
    'Password'      => 'demo1234',
    'IsExtended'    => 1,
    'BonusIds'      => '{}',
    'Hash'           => '{}',
    'Email'           => '{}',
    'Street'           => '{}',
    'StreetAdd'     => '',
    'BankName'     => '',
    'BankCity'      => '',
    'BankStreet'    => '',
    'BankZipCode'   => '',
    'BankCountry'   => '',
    'Passportfile'   => '',
    'Panfile'        => '',
  ];

  static public $memberSignup = [
    'title'          => 'unknown',
    'lastName'       => 'unknown',
    'firstName'      => 'unknown',
    'age'            => 99,
    'city'           => 'unknown',
    'country'        => 'unknown',
    'zip_code'       => '504231',
    'bank_recipient' => 'unknown',
    'iban'           => 'unknown',
    'bic'            => 'unknown',
    'password'       => 'demo1234',
    'email'           => 'some@mail',
    'street'           => 'unknown',
    'street_add'     => 'unknown',
    'bank_name'     => 'unknown',
    'bank_city'      => 'unknown',
    'bank_street'    => 'unknown',
    'bank_zip_code'   => 'unknown',
    'bank_country'   => 'unknown',
    'phone' => 'unknown',
    'passportfile'   => '',
    'panfile'        => '',
  ];

  static private $memberInvitation = [
    'title'          => 'unknown',
    'lastName'       => 'unknown',
    'firstName'      => 'unknown',
    'age'            => 99,
    'city'           => 'unknown',
    'country'        => 'unknown',
    'zip_code'       => '504231',
    'bank_recipient' => 'unknown',
    'iban'           => 'unknown',
    'bic'            => 'unknown',
    'password'       => 'demo1234',
    'password2'       => 'demo1234',
    'accept_agbs'          => '1',
    'accept_valid_country' => '1',
    'is_extended'    => 1,
    'bonus_ids'      => '{}',
    'hash'           => '{}',
    'email'           => '{}',
    'street'           => 'unknown',
    'street_add'     => 'unknown',
    'bank_name'     => 'unknown',
    'bank_city'      => 'unknown',
    'bank_street'    => 'unknown',
    'bank_zip_code'   => 'unknown',
    'bank_country'   => 'unknown',
    'phone' => 'unknown',
    'passportfile'   => '',
    'panfile'        => '',
  ];

  static public function createMember(Member $referralMember = null, array $data = array()) {
    $member = new Member();

    self::$emailCounter++;

    $member->fromArray(array_merge(self::$memberDefaults, $data));
    if ( $referralMember )
      $member->setReferrerMember($referralMember, self::$con);

    $now = time();
    $member->setSignupDate($now)
      ->setPaidDate($now + 100000)
      ->save(self::$con);

    $member->setHash(\Member::calcHash($member));
    $member->setNum($member->getId() + 1000000);
    $member->setEmail(self::$emailCounter.$member->getId().uniqid().'@un.de');
    $member->save(self::$con);

    if ( $referralMember )
      $referralMember->save(self::$con);

    return $member;
  }

  static public function createBonusMember($accountNumber, array $data = array()) {
    $member = self::createMember(null, array_merge([
      'Num'=> $accountNumber,
      'FundsLevel' => Member::FUNDS_LEVEL2
    ], $data));

    $member->setBonusIds(json_encode([
      $member->getId() => 1,
    ]));

    return $member;
  }

  static public function createSignupMember(Member $referralMember, $receivedPaiment = true, array $data = array()) {
    self::$emailCounter++;
    $data = array_merge(self::$memberSignup, $data);
    $data['email'] = self::$emailCounter.uniqid().'@un.de';

    // if ( !self::$con->beginTransaction() )
    //   throw new \Exception('Could not begin transaction');

    $member = Member::createFromSignup($data, $referralMember, null, self::$con);

    // if ( !self::$con->commit() )
    //   throw new \Exception('Could not commit transaction');

    if ( $receivedPaiment ) {
      $member->onReceivedMemberFee(self::$currency, time(), false, self::$con);
      $member->setPhotosExist(1);
      $member->save(self::$con);
    }

    return $member;
  }

  static public function createSignupMemberInActivity(Member $referralMember, $receivedPaiment = true, array $data = array()) {
    self::$emailCounter++;
    $data = array_merge(self::$memberSignup, $data);
    $data['email'] = self::$emailCounter.uniqid('', true).microtime().'@un.de';

    $con = self::$con;
    $member = \Activity::exec(
      /*callable*/['\\Member', 'activity_createFromSignup'],
      /*func args*/[
        $data,
        $referralMember,
        null,
        $con
      ],
      /*activity.action*/\Activity::ACT_MEMBER_SIGNUP,
      /*activity.member*/null,
      /*activity.related*/$referralMember,
      $con
    );

    if ( $receivedPaiment ) {
      $member->reload(false, $con);

      if ( !$con->beginTransaction() )
        throw new \Exception('Could not begin transaction');

      $member->onReceivedMemberFee(self::$currency, time(), false, self::$con);
      $member->save(self::$con);

      if ( !$con->commit() )
        throw new \Exception('Could not commit transaction');

    }

    return $member;
  }



  static public function createMemberWithInvitation($referrer, $invitationData, $data = []) {
    if ( is_numeric($invitationData) )
      $invitationData = ['type' => $invitationData];

    self::$emailCounter++;
    $data = array_merge(self::$memberInvitation, $data);
    $data['email'] = self::$emailCounter.uniqid().'@un.de';

    /* Create invitation
    ---------------------------------------------*/
    $invitation = Invitation::create(
      $referrer,
      $invitationData,
      self::$con
    );

    /* Create member with created invitation code
    ---------------------------------------------*/
    list($valid, $data, $referralMember, $invitation)
      = \Member::validateSignupForm(array_merge($data, [
        'referral_member_num' => $referrer->getHash(),
        'invitation_code' => $invitation->getHash(),
      ]));

    $member = \Member::createFromSignup($data, $referrer, $invitation, self::$con);
    $member->reload(self::$con);

    if ( !isset($invitationData['free_signup']) || !$invitationData['free_signup'] ) {
      $member->onReceivedMemberFee(self::$currency, time(), false, self::$con);
      $member->save(self::$con);
    }

    return $member;
  }

  static public function getCurrentTransferBundle(Member $member) {
    return $member->getCurrentTransferBundle(self::$currency, self::$con);
  }

  static public function fireReceivedMemberFee(Member $member, $now) {
    $member->onReceivedMemberFee(self::$currency, $now, false, self::$con);
    $member->save(self::$con);
  }

  static public function resetBonusMembers() {
    self::$it_member = null;
  }

  static public function printBonusIds(Member $member) {
    $ids = json_decode($member->getBonusIds(), true);
    $arr = [];
    foreach ($ids as $memberId => $type) {
      $bonusMember = MemberQuery::create()->findOneById($memberId);
      $arr[$bonusMember->getId().'-'.$bonusMember->getLastname()] = \Tbmt\Localizer::get('common.member_types.'.$type);
    }

    print_r('<pre>');
    print_r([$arr]);
    print_r('</pre>');

  }

  static public function setUpBonusMembers($doReset = true, $options = false) {
    if ( !\MemberQuery::create()->findOneByNum(SystemStats::ACCOUNT_NUM_SYSTEM) ) {
      \Tbmt\SystemSetup::setCon(self::$con);
      \Tbmt\SystemSetup::doSetup();
    }

    $options = array_merge([
      'IT' => true,
      'VL' => true,
      'OL' => true,
      'PM' => true,
      'VS2' => true,
      'VS1' => true,
    ], $options ? $options : []);

    $currentParent = null;
    $currentBonusIds = '[]';

    /* it specialists
    ---------------------------------------------*/
    $IT_t = 0;
    $IT = null;
    if ( $options['IT'] ) {
      if ( $doReset === true || self::$it_member === null ) {
        $IT_t = 0;
        $IT = self::$it_member = DbEntityHelper::createMember($currentParent, [
          'Type' => Member::TYPE_ITSPECIALIST,
          'FundsLevel' => Member::FUNDS_LEVEL2,
          // 'Num' => SystemStats::ACCOUNT_NUM_IT
        ]);

      } else {
        $IT = self::$it_member;
        $IT_t = self::$it_member->getOutstandingTotal()[DbEntityHelper::$currency];
      }

      $currentBonusIds = MemberBonusIds::populate($IT, '[]');
    }


    /* marketing leader
    ---------------------------------------------*/
    $VL_t = 0;
    $VL = null;
    if ( $options['VL'] ) {
      $VL = DbEntityHelper::createMember($currentParent, [
        'Type' => Member::TYPE_MARKETINGLEADER,
        'FundsLevel' => Member::FUNDS_LEVEL2,
        'BonusIds' => $currentBonusIds
      ]);

      $currentBonusIds = MemberBonusIds::populate($VL, $VL->getBonusIds());
      $currentParent = $VL;
    }


    /* org leader
    ---------------------------------------------*/
    $OL_t = 0;
    $OL = null;
    if ( $options['OL'] ) {
      $OL = DbEntityHelper::createMember($currentParent, [
        'Type' => Member::TYPE_ORGLEADER,
        'FundsLevel' => Member::FUNDS_LEVEL2,
        'BonusIds' => $currentBonusIds
      ]);

      $currentBonusIds = MemberBonusIds::populate($OL, $OL->getBonusIds());
      $currentParent = $OL;
    }


    /* promoter
    ---------------------------------------------*/
    $PM_t = 0;
    $PM = null;
    if ( $options['PM'] ) {
      $IT_t += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
      $VL_t += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);

      if ( !$OL ) $OL_t = &$VL_t;
      $OL_t += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL2) +
        Transaction::getAmountForReason(Transaction::REASON_OL_BONUS) +
        Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);

      // TODO question:
      // kriegt der ol in diesem fall 22 oder 21 euro ?
      // 22 weil der ja den bonus der promoters kriegt wenn er jemand
      // wirbt ohne das ein promoter dazwischen ist?

      if ( $currentParent ) {
        $PM = DbEntityHelper::createSignupMember($currentParent);

      } else {
        $PM = DbEntityHelper::createMember($currentParent, [
          'BonusIds' => $currentBonusIds
        ]);
        $IT_t -= Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
        $VL_t -= Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);
        $OL_t -= Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL2) +
          Transaction::getAmountForReason(Transaction::REASON_OL_BONUS) +
          Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);
      }

      $PM->setType(Member::TYPE_PROMOTER)
        ->setFundsLevel(Member::FUNDS_LEVEL2)
        ->save(self::$con);

      $currentParent = $PM;
    }


    /* funds level 2
    ---------------------------------------------*/
    $VS2_t = 0;
    $VS2 = null;
    if ( $options['VS2'] ) {
      $IT_t += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS) * 3;
      $VL_t += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS) * 3;

      if ( !$OL ) $OL_t = &$VL_t;
      $OL_t += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS) * 3;

      if ( !$PM ) $PM_t = &$OL_t;
      $PM_t += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL2) +
        Transaction::getAmountForReason(Transaction::REASON_PM_BONUS) +
        2 * (
          Transaction::getAmountForReason(Transaction::REASON_PM_BONUS) + Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_INDIRECT)
        );

      $VS2_t += 2 * Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL1);
      $VS2 = DbEntityHelper::createSignupMember($currentParent);
      DbEntityHelper::createSignupMember($VS2);
      DbEntityHelper::createSignupMember($VS2);

      $currentParent = $VS2;
    }


    /* funds level 1
    ---------------------------------------------*/
    $VS1_t = 0;
    $VS1 = null;
    if ( $options['VS1'] ) {
      $IT_t += Transaction::getAmountForReason(Transaction::REASON_IT_BONUS);
      $VL_t += Transaction::getAmountForReason(Transaction::REASON_VL_BONUS);

      if ( !$OL ) $OL_t = &$VL_t;
      $OL_t += Transaction::getAmountForReason(Transaction::REASON_OL_BONUS);

      if ( !$PM ) $PM_t = &$OL_t;
      $PM_t += Transaction::getAmountForReason(Transaction::REASON_PM_BONUS);

      if ( !$VS2 ) $VS2_t = &$PM_t;
      $VS2_t += Transaction::getAmountForReason(Transaction::REASON_ADVERTISED_LVL2);

      $VS1 = DbEntityHelper::createSignupMember($currentParent);
    }

    return [
      [$IT, $VL, $OL, $PM, $VS2, $VS1],
      [$IT_t, $VL_t, $OL_t, $PM_t, $VS2_t, $VS1_t],
    ];
  }
}

class TransactionTotalsAssertions {
  private $member;
  private $testCase;
  private $total = 0;
  public function __construct(Member $member, PHPUnit_Framework_TestCase $testCase) {
    $this->member = $member;
    $this->testCase = $testCase;
    $this->transfer = DbEntityHelper::getCurrentTransferBundle($member);
    if ( $this->transfer->isNew() )
      $this->transfer->save(DbEntityHelper::$con);

  }

  public function add($reason, $quantity = 1) {
    $this->total += Transaction::getAmountForReason($reason) * $quantity;
    return $this;
  }

  public function addBonusLevelPayment($quantity = 1) {
    $this->total += $this->member->getBonusLevel() * $quantity;
    return $this;
  }

  public function out() {
    if ( !$this->transfer->isNew() )
      $this->transfer->reload(DbEntityHelper::$con);

    if ( !$this->member->isNew() )
      $this->member->reload(DbEntityHelper::$con);

    $memberTotal = $this->member->getOutstandingTotal();
    print_r('<pre>');
    print_r([
      'memberId' => $this->member->getId(),
      'manual_total' => $this->total,
      'transfer_total' => $this->transfer->getAmountSum(),
      'member_total' =>  isset($memberTotal[DbEntityHelper::$currency]) ? $memberTotal[DbEntityHelper::$currency] : null,
      'member_transactions' => $this->transferTransactionsToArray($this->transfer)
    ]);
    print_r('</pre>');

  }

  public function transferTransactionsToArray($transfer) {
    $i18nView = \Tbmt\Localizer::get('view.account.tabs.invoice');
    $reasons = $i18nView['transaction_reasons'];

    $result = [];
    $transactions = $transfer->getTransactions(TransactionQuery::create()->orderBy(TransactionPeer::DATE, Criteria::ASC));
    foreach ( $transactions as $trans ) {
      $result[] = [
        'id'     => $trans->getId(),
        'amount' => $trans->getAmountSum(),
        'reason' => $reasons[$trans->getReason()],
        'related_id' => $trans->getRelatedId()
      ];
    }

    return $result;
  }

  public function assertTotals() {
    $this->transfer->reload(DbEntityHelper::$con);
    $this->member->reload(DbEntityHelper::$con);
    $this->testCase->assertEquals($this->total, $this->transfer->getAmountSum(), 'Invalid totals of: '.$this->member->getFirstName().' '.$this->member->getLastName());

    $amount = $this->member->getOutstandingTotal();
    $this->testCase->assertEquals($this->total, isset($amount[DbEntityHelper::$currency]) ? $amount[DbEntityHelper::$currency] : 0,
      'Invalid totals of: '.$this->member->getFirstName().' '.$this->member->getLastName());
  }
}