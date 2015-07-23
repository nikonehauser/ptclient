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
      'type' => Member::TYPE_MARKETINGLEADER
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
      'type' => Member::TYPE_MARKETINGLEADER
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

}