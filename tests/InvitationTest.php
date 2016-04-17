<?php

class InvitationTest extends Tbmt_Tests_DatabaseTestCase {

  static public function setUpBeforeClass() {
    $con = Propel::getConnection();
    DbEntityHelper::truncateDatabase($con);

  }

  static private $singupFormData = [
    'title'          => 'unknown',
    'lastName'       => 'unknown',
    'firstName'      => 'unknown',
    'age'            => 99,
    'email'          => 'unknown@un.de',
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
  ];

  public function testCorrectInvitationApplyment() {
    // NOTE: This tests what happen if one of my third+ advertisings gets
    // deleted due to not paiing fee.

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);
    $marketingLeader = DbEntityHelper::createMember(null, [
      'Type' => Member::TYPE_MARKETINGLEADER
    ]);
    $marketingLeader->reload(self::$propelCon);

    /* Create invitation
    ---------------------------------------------*/
    $invitation = Invitation::create(
      $marketingLeader,
      ['type' => Member::TYPE_PROMOTER],
      self::$propelCon
    );

    /* Create member with created invitation code
    ---------------------------------------------*/
    list($valid, $data, $referralMember, $invitation)
      = \Member::validateSignupForm(array_merge(self::$singupFormData, [
        'referral_member_num' => $marketingLeader->getNum(),
        'invitation_code' => $invitation->getHash(),
      ]));

    $member = \Member::createFromSignup($data, $marketingLeader, $invitation, self::$propelCon);
    $member->reload(self::$propelCon);

    /* Assert proper member type
    ---------------------------------------------*/
    $this->assertEquals(Member::TYPE_PROMOTER, $member->getType());
    $this->assertEquals(null, $member->getPaidDate());

    /* Try to use invitation code twice
    ---------------------------------------------*/
    list($valid, $data, $referralMember, $invitation)
      = \Member::validateSignupForm(array_merge(self::$singupFormData, [
        'referral_member_num' => $marketingLeader->getNum(),
        'invitation_code' => $invitation->getHash(),
      ]));

    $this->assertFalse($valid);
    $this->assertArrayHasKey('invitation_code', $data);
    $this->assertNotEquals($data['invitation_code'], '');
  }

  public function testInvitationCanFreeFromFee() {
    // NOTE: This tests what happen if one of my third+ advertisings gets
    // deleted due to not paiing fee.

    /* Setup
    ---------------------------------------------*/
    DbEntityHelper::setCon(self::$propelCon);
    $marketingLeader = DbEntityHelper::createMember(null, [
      'Type' => Member::TYPE_MARKETINGLEADER
    ]);
    $marketingLeader->reload(self::$propelCon);

    /* Create invitation
    ---------------------------------------------*/
    $invitation = Invitation::create(
      $marketingLeader,
      ['type' => Member::TYPE_ORGLEADER, 'free_signup' => 1],
      self::$propelCon
    );

    /* Create member with created invitation code
    ---------------------------------------------*/
    list($valid, $data, $referralMember, $invitation)
      = \Member::validateSignupForm(array_merge(self::$singupFormData, [
        'referral_member_num' => $marketingLeader->getNum(),
        'invitation_code' => $invitation->getHash(),
      ]));

    $member = \Member::createFromSignup($data, $marketingLeader, $invitation, self::$propelCon);
    $member->reload(self::$propelCon);

    /* Assert proper member type
    ---------------------------------------------*/
    $this->assertEquals(Member::TYPE_ORGLEADER, $member->getType());
    $this->assertNotNull($member->getPaidDate());
  }

  public function testLevelApplyingWithFreeRegistration() {
    $sylvheim = Member::getByNum(\SystemStats::ACCOUNT_SYLVHEIM);
    $sylvheim_total = new TransactionTotalsAssertions($sylvheim, $this);

    /* Setup - This member is set as paid but no money is spread.
     * Because this one is just for testing purpose
    ---------------------------------------------*/
    $marketingLeader = DbEntityHelper::createMember($sylvheim, [
      'Type' => Member::TYPE_MARKETINGLEADER
    ]);
    $marketingLeader->reload(self::$propelCon);
    $marketingLeader_total = new TransactionTotalsAssertions($marketingLeader, $this);

    /* Create invitation
    ---------------------------------------------*/
    $invitation = Invitation::create(
      $marketingLeader,
      ['type' => Member::TYPE_ORGLEADER, 'free_signup' => 1],
      self::$propelCon
    );

    /* Create member with created invitation code
    ---------------------------------------------*/
    list($valid, $data, $referralMember, $invitation)
      = \Member::validateSignupForm(array_merge(self::$singupFormData, [
        'referral_member_num' => $marketingLeader->getNum(),
        'invitation_code' => $invitation->getHash(),
      ]));

    // Because the marketing leader is level 1 this org leader will be
    // transfered to be child of sylvheim
    $orgLeader = \Member::createFromSignup($data, $marketingLeader, $invitation, self::$propelCon);
    $orgLeader->reload(self::$propelCon);

    // Because orgleaders parent has to be sylvhelm, advertising someone first time
    // needs to bonus sylvheim
    DbEntityHelper::createSignupMember($orgLeader);

    // Spread money for creating this last member
    $sylvheim_total->add(Transaction::REASON_SYLVHEIM, 1);
    $sylvheim_total->add(Transaction::REASON_ADVERTISED_INDIRECT, 1);
    // VL_BONUS - because, orgleader was VL first member and got transferred to
    // sylvheimm, therefore there is no VL between sylvheim and ol when ol
    // inviteds member
    $sylvheim_total->add(Transaction::REASON_VL_BONUS, 1);

    // marketing leader gets nothing from ol's advertising.

    $sylvheim_total->assertTotals();
    $marketingLeader_total->assertTotals();
  }

  public function testDirectorInviteDirectorWillReceiveSameParent() {
    $sylvheim = Member::getByNum(\SystemStats::ACCOUNT_SYLVHEIM);

    $marketingLeader = DbEntityHelper::createMember($sylvheim, [
      'Type' => Member::TYPE_MARKETINGLEADER
    ]);
    $marketingLeader->reload(self::$propelCon);

    $marketingLeader2 = DbEntityHelper::createMemberWithInvitation(
      $marketingLeader,
      Member::TYPE_MARKETINGLEADER,
      self::$singupFormData
    );

    /* Assert proper member type
    ---------------------------------------------*/
    $this->assertEquals(Member::TYPE_MARKETINGLEADER, $marketingLeader->getType());
    $this->assertEquals(Member::TYPE_MARKETINGLEADER, $marketingLeader2->getType());

    $this->assertEquals($marketingLeader->getReferrerId(), $marketingLeader2->getReferrerId());
  }

}