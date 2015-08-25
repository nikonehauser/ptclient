<?php

class BonusLevelTest extends Tbmt_Tests_DatabaseTestCase {

  static public function setUpBeforeClass() {
    $con = Propel::getConnection();
    DbEntityHelper::setCon($con);
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

  public function testMemberGetBonusLevelIdGetsApplied() {
    $marketingLeader = DbEntityHelper::createMember(null, [
      'type' => Member::TYPE_MARKETINGLEADER,
      'LastName' => 'vl'
    ]);
    $marketingLeader->reload(self::$propelCon);

    /* $m1 gets bonus level applied after recruiting several members
    ---------------------------------------------*/
    $m1 = DbEntityHelper::createSignupMember($marketingLeader, true, ['lastName' => 'm1']);

    $m1_1 = DbEntityHelper::createSignupMember($m1, true, ['lastName' => 'm1_1']);
    $m1_2 = DbEntityHelper::createSignupMember($m1, true, ['lastName' => 'm1_2']);
    $m1_2_1 = DbEntityHelper::createSignupMember($m1_2, true, ['lastName' => 'm1_2_1']);
    $m1_2_2 = DbEntityHelper::createSignupMember($m1_2, true, ['lastName' => 'm1_2_2']);
    $m1_3_1 = DbEntityHelper::createSignupMember($m1_2, true, ['lastName' => 'm1_3_1']);

    /* $m1 gets bonus level 3 now
    ---------------------------------------------*/
    $m1->activity_setBonusLevel(3, self::$propelCon);

    /* further recruiting requires the bonus of the member applied
    ---------------------------------------------*/
    $m1_3 = DbEntityHelper::createSignupMember($m1, true, ['lastName' => 'm1_3']);
    $m1_2_3 = DbEntityHelper::createSignupMember($m1_2_1, true, ['lastName' => 'm1_2_3']);
    $m1_3_1_1 = DbEntityHelper::createSignupMember($m1_3_1, true, ['lastName' => 'm1_3_1_1']);

    $bonusId = $m1->getId();
    $bonusIdsApplyed = [$m1_1, $m1_2, $m1_2_1, $m1_2_2, $m1_3_1, $m1_3, $m1_2_3, $m1_3_1_1];
    foreach ($bonusIdsApplyed as $m) {
      $m->reload();
      $ids = \MemberBonusIds::toArray($m->getBonusIds());
      $this->assertTrue(is_array($ids), 'Invalid bonus ids "'.json_encode($ids).'" for member: '.$m->getlastName());
      $this->assertArrayHasKey($bonusId, $ids, 'Invalid bonus ids for member: '.$m->getlastName());
    }
  }

  public function testMemberGetBonusLevelPaymentsApplied() {
    $marketingLeader = DbEntityHelper::createMember(null, [
      'type' => Member::TYPE_MARKETINGLEADER,
      'LastName' => 'vl'
    ]);
    $marketingLeader->reload(self::$propelCon);

    /* $m1 gets bonus level applied after recruiting several members
    ---------------------------------------------*/
    $m1 = DbEntityHelper::createSignupMember($marketingLeader, true, ['lastName' => 'm1']);
    $m1_total = new TransactionTotalsAssertions($m1, $this);

    $m1_1 = DbEntityHelper::createSignupMember($m1, true, ['lastName' => 'm1_1']);
    $m1_2 = DbEntityHelper::createSignupMember($m1, true, ['lastName' => 'm1_2']);
    $m1_2_1 = DbEntityHelper::createSignupMember($m1_2, true, ['lastName' => 'm1_2_1']);
    $m1_2_2 = DbEntityHelper::createSignupMember($m1_2, true, ['lastName' => 'm1_2_2']);
    $m1_3_1 = DbEntityHelper::createSignupMember($m1_2, true, ['lastName' => 'm1_3_1']);

    $m1_total->add(Transaction::REASON_ADVERTISED_LVL1, 2);

    /* $m1 gets bonus level 3 now
    ---------------------------------------------*/
    $m1->activity_setBonusLevel(3.29, self::$propelCon);

    /* further recruiting requires the bonus of the member applied
    ---------------------------------------------*/
    $m1_3 = DbEntityHelper::createSignupMember($m1, true, ['lastName' => 'm1_3']);
    $m1_2_3 = DbEntityHelper::createSignupMember($m1_2_1, true, ['lastName' => 'm1_2_3']);
    $m1_3_1_1 = DbEntityHelper::createSignupMember($m1_3_1, true, ['lastName' => 'm1_3_1_1']);

    $m1_3_1 = DbEntityHelper::createSignupMember($m1_3, true, ['lastName' => 'm1_3_1']);
    $m1_3_2 = DbEntityHelper::createSignupMember($m1_3, true, ['lastName' => 'm1_3_2']);

    $m1_total->add(Transaction::REASON_ADVERTISED_LVL2);
    $m1_total->add(Transaction::REASON_ADVERTISED_INDIRECT, 2);
    $m1_total->addBonusLevelPayment(5);
    $m1_total->assertTotals();

    /* remove the bonus level afterwards
    ---------------------------------------------*/
    $m1->activity_setBonusLevel(0, self::$propelCon);

    $m1_4 = DbEntityHelper::createSignupMember($m1, true, ['lastName' => 'm1_4']);
    $m1_3_3 = DbEntityHelper::createSignupMember($m1_3, true, ['lastName' => 'm1_3_3']);

    $m1_total->add(Transaction::REASON_ADVERTISED_LVL2);
    $m1_total->assertTotals();

  }

    /**
     * @expectedException \Tbmt\ProvisionExceedMemberFeeException
     */
  public function testProvisionExceedMemberFreeException() {
    $marketingLeader = DbEntityHelper::createMember(null, [
      'type' => Member::TYPE_MARKETINGLEADER,
      'LastName' => 'vl'
    ]);
    $marketingLeader->reload(self::$propelCon);

    $m1 = DbEntityHelper::createSignupMember($marketingLeader, true, ['lastName' => 'm1']);
    $m1->activity_setBonusLevel(\Tbmt\Config::get('member_fee', \Tbmt\TYPE_FLOAT, 100) + 10, self::$propelCon);

    $m1_3 = DbEntityHelper::createSignupMember($m1, true, ['lastName' => 'm1_3']);
  }

}