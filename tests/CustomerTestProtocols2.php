<?php

class CustomerTestProtocols2Test extends Tbmt_Tests_DatabaseTestCase {

  // static public function setUpBeforeClass() {
  //   $con = Propel::getConnection();
  //   DbEntityHelper::truncateDatabase($con);

  // }
  //
  static public function setUpBeforeClass() {
    $con = Propel::getConnection();
    DbEntityHelper::setCon($con);
    DbEntityHelper::truncateDatabase($con);

  }

  private $totals = [];

  public function assertTotals() {
    foreach ( $this->totals as $name => $total ) {
        // echo $name."\n";
        $total->assertTotals();
    }
  }

  public function testBuildCurrentCompleteUseCase() {
    // @see resources/docs/Test 13.04.2016.docx
    //
    $CEO = Member::getByNum(\SystemStats::ACCOUNT_NUM_CEO1);
    $CEO_total = new TransactionTotalsAssertions($CEO, $this);
    $this->totals['CEO1'] = $CEO_total;

    $sylvheim = Member::getByNum(\SystemStats::ACCOUNT_SYLVHEIM);
    $sylvheim_total = new TransactionTotalsAssertions($sylvheim, $this);
    $this->totals['sylvheim'] = $sylvheim_total;

    /**
     * director 01
     *
     */
    $director1 = DbEntityHelper::createMemberWithInvitation($sylvheim, [
        'type' => \Member::TYPE_MARKETINGLEADER,
    ], [
        'firstName' => 'Director',
        'lastName' => '01'
    ]);
    $director1_total = new TransactionTotalsAssertions($director1, $this);
    $this->totals['director1'] = $director1_total;

    $sylvheim_total->add(Transaction::REASON_ADVERTISED_LVL2);
    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);
    $sylvheim_total->add(Transaction::REASON_PM_BONUS);
    $sylvheim_total->add(Transaction::REASON_VL_BONUS);
    $sylvheim_total->add(Transaction::REASON_OL_BONUS);

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $this->assertTotals();

    /**
     * Spender 01
     */
    $spender01 = DbEntityHelper::createSignupMember($director1, true, [
        'firstName' => 'spender',
        'lastName' => '01'
    ]);
    $spender01_total = new TransactionTotalsAssertions($spender01, $this);
    $this->totals['spender01'] = $spender01_total;

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $sylvheim_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $director1_total->add(Transaction::REASON_ADVERTISED_LVL1);
    $director1_total->add(Transaction::REASON_PM_BONUS);
    $director1_total->add(Transaction::REASON_VL_BONUS);
    $director1_total->add(Transaction::REASON_OL_BONUS);

    $this->assertTotals();

    /**
     * Spender 02
     *
     * director 1 gets level 2
     */
    $spender02 = DbEntityHelper::createSignupMember($director1, true, [
        'firstName' => 'spender',
        'lastName' => '02'
    ]);
    $spender02_total = new TransactionTotalsAssertions($spender02, $this);
    $this->totals['spender02'] = $spender02_total;

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $sylvheim_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $director1_total->add(Transaction::REASON_ADVERTISED_LVL1);
    $director1_total->add(Transaction::REASON_PM_BONUS);
    $director1_total->add(Transaction::REASON_VL_BONUS);
    $director1_total->add(Transaction::REASON_OL_BONUS);

    $this->assertTotals();

    /**
     * Spender 03
     */
    $spender03 = DbEntityHelper::createSignupMember($spender02, true, [
        'firstName' => 'spender',
        'lastName' => '03'
    ]);
    $spender03_total = new TransactionTotalsAssertions($spender03, $this);
    $this->totals['spender03'] = $spender03_total;

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $sylvheim_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $director1_total->add(Transaction::REASON_PM_BONUS);
    $director1_total->add(Transaction::REASON_VL_BONUS);
    $director1_total->add(Transaction::REASON_OL_BONUS);

    $spender02_total->add(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTotals();

    /**
     * Spender 04
     */
    $spender04 = DbEntityHelper::createSignupMember($spender03, true, [
        'firstName' => 'spender',
        'lastName' => '04'
    ]);
    $spender04_total = new TransactionTotalsAssertions($spender04, $this);
    $this->totals['spender04'] = $spender04_total;

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $sylvheim_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $director1_total->add(Transaction::REASON_PM_BONUS);
    $director1_total->add(Transaction::REASON_VL_BONUS);
    $director1_total->add(Transaction::REASON_OL_BONUS);

    $spender03_total->add(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTotals();

    /**
     * Spender 05
     */
    $spender05 = DbEntityHelper::createSignupMember($spender04, true, [
        'firstName' => 'spender',
        'lastName' => '05'
    ]);
    $spender05_total = new TransactionTotalsAssertions($spender05, $this);
    $this->totals['spender05'] = $spender05_total;

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $sylvheim_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $director1_total->add(Transaction::REASON_PM_BONUS);
    $director1_total->add(Transaction::REASON_VL_BONUS);
    $director1_total->add(Transaction::REASON_OL_BONUS);

    $spender04_total->add(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTotals();

    /**
     * Spender 06
     */
    $spender06 = DbEntityHelper::createSignupMember($spender05, true, [
        'firstName' => 'spender',
        'lastName' => '06'
    ]);
    $spender06_total = new TransactionTotalsAssertions($spender06, $this);
    $this->totals['spender06'] = $spender06_total;

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $sylvheim_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $director1_total->add(Transaction::REASON_PM_BONUS);
    $director1_total->add(Transaction::REASON_VL_BONUS);
    $director1_total->add(Transaction::REASON_OL_BONUS);

    $spender05_total->add(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTotals();

    /**
     * Spender 07
     */
    $spender07 = DbEntityHelper::createSignupMember($spender01, true, [
        'firstName' => 'spender',
        'lastName' => '07'
    ]);
    $spender07_total = new TransactionTotalsAssertions($spender07, $this);
    $this->totals['spender07'] = $spender07_total;

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $sylvheim_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $director1_total->add(Transaction::REASON_PM_BONUS);
    $director1_total->add(Transaction::REASON_VL_BONUS);
    $director1_total->add(Transaction::REASON_OL_BONUS);

    $spender01_total->add(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTotals();

    /**
     * Spender 08
     */
    $spender08 = DbEntityHelper::createSignupMember($spender07, true, [
        'firstName' => 'spender',
        'lastName' => '08'
    ]);
    $spender08_total = new TransactionTotalsAssertions($spender08, $this);
    $this->totals['spender08'] = $spender08_total;

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $sylvheim_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $director1_total->add(Transaction::REASON_PM_BONUS);
    $director1_total->add(Transaction::REASON_VL_BONUS);
    $director1_total->add(Transaction::REASON_OL_BONUS);

    $spender07_total->add(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTotals();

    /**
     * Spender 09
     */
    $spender09 = DbEntityHelper::createSignupMember($spender08, true, [
        'firstName' => 'spender',
        'lastName' => '09'
    ]);
    $spender09_total = new TransactionTotalsAssertions($spender09, $this);
    $this->totals['spender09'] = $spender09_total;

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $sylvheim_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $director1_total->add(Transaction::REASON_PM_BONUS);
    $director1_total->add(Transaction::REASON_VL_BONUS);
    $director1_total->add(Transaction::REASON_OL_BONUS);

    $spender08_total->add(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTotals();

    /**
     * Spender 10
     */
    $spender10 = DbEntityHelper::createSignupMember($spender09, true, [
        'firstName' => 'spender',
        'lastName' => '10'
    ]);
    $spender10_total = new TransactionTotalsAssertions($spender10, $this);
    $this->totals['spender10'] = $spender10_total;

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $sylvheim_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $director1_total->add(Transaction::REASON_PM_BONUS);
    $director1_total->add(Transaction::REASON_VL_BONUS);
    $director1_total->add(Transaction::REASON_OL_BONUS);

    $spender09_total->add(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTotals();

    /**
     * Spender 11
     */
    $spender11 = DbEntityHelper::createSignupMember($director1, true, [
        'firstName' => 'spender',
        'lastName' => '11'
    ]);
    $spender11_total = new TransactionTotalsAssertions($spender11, $this);
    $this->totals['spender11'] = $spender11_total;

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $director1_total->add(Transaction::REASON_ADVERTISED_LVL2);
    $director1_total->add(Transaction::REASON_PM_BONUS);
    $director1_total->add(Transaction::REASON_VL_BONUS);
    $director1_total->add(Transaction::REASON_OL_BONUS);

    $this->assertTotals();

    /**
     * promoter 01
     * free invitation, no bonuses
     */
    $promoter01 = DbEntityHelper::createMemberWithInvitation($director1, [
        'type' => \Member::TYPE_PROMOTER
    ], [
        'firstName' => 'promoter',
        'lastName' => '01'
    ]);
    $promoter01_total = new TransactionTotalsAssertions($promoter01, $this);
    $this->totals['promoter01'] = $promoter01_total;

    $director1_total->add(Transaction::REASON_ADVERTISED_LVL2);
    $director1_total->add(Transaction::REASON_PM_BONUS);
    $director1_total->add(Transaction::REASON_VL_BONUS);
    $director1_total->add(Transaction::REASON_OL_BONUS);

    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $this->assertTotals();

    /**
     * Spender 12
     */
    $spender12 = DbEntityHelper::createSignupMember($promoter01, true, [
        'firstName' => 'spender',
        'lastName' => '12'
    ]);
    $spender12_total = new TransactionTotalsAssertions($spender12, $this);
    $this->totals['spender12'] = $spender12_total;

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $director1_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $director1_total->add(Transaction::REASON_VL_BONUS);
    $director1_total->add(Transaction::REASON_OL_BONUS);

    $promoter01_total->add(Transaction::REASON_ADVERTISED_LVL1);
    $promoter01_total->add(Transaction::REASON_PM_BONUS);

    $this->assertTotals();

    /**
     * promoter 02
     */
    $promoter02 = DbEntityHelper::createMemberWithInvitation($director1, [
        'type' => \Member::TYPE_PROMOTER
    ], [
        'firstName' => 'promoter',
        'lastName' => '02'
    ]);
    $promoter02_total = new TransactionTotalsAssertions($promoter02, $this);
    $this->totals['promoter02'] = $promoter02_total;

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $director1_total->add(Transaction::REASON_ADVERTISED_LVL2);
    $director1_total->add(Transaction::REASON_VL_BONUS);
    $director1_total->add(Transaction::REASON_OL_BONUS);
    $director1_total->add(Transaction::REASON_PM_BONUS);

    $this->assertTotals();

    /**
     * Spender 13
     */
    $spender13 = DbEntityHelper::createSignupMember($promoter02, true, [
        'firstName' => 'spender',
        'lastName' => '13'
    ]);
    $spender13_total = new TransactionTotalsAssertions($spender13, $this);
    $this->totals['spender13'] = $spender13_total;

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $director1_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $director1_total->add(Transaction::REASON_VL_BONUS);
    $director1_total->add(Transaction::REASON_OL_BONUS);

    $promoter02_total->add(Transaction::REASON_ADVERTISED_LVL1);
    $promoter02_total->add(Transaction::REASON_PM_BONUS);

    $this->assertTotals();

    /**
     * ol 01
     * free invitation, no bonuses
     */
    $ol01 = DbEntityHelper::createMemberWithInvitation($director1, [
        'type' => \Member::TYPE_ORGLEADER
    ], [
        'firstName' => 'ol',
        'lastName' => '02'
    ]);
    $ol01_total = new TransactionTotalsAssertions($ol01, $this);
    $this->totals['ol01'] = $ol01_total;

    $director1_total->add(Transaction::REASON_ADVERTISED_LVL2);
    $director1_total->add(Transaction::REASON_PM_BONUS);
    $director1_total->add(Transaction::REASON_VL_BONUS);
    $director1_total->add(Transaction::REASON_OL_BONUS);

    $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $this->assertTotals();


    // We need to stop here because the ol is not allowed to invitate before
    // becomming level 2

    // return;
    // /**
    //  * promoter 03
    //  */
    // $promoter03 = DbEntityHelper::createMemberWithInvitation($ol01, [
    //     'type' => \Member::TYPE_PROMOTER,
    //     'free_signup' => 1
    // ], [
    //     'firstName' => 'promoter',
    //     'lastName' => '03'
    // ]);
    // $promoter03_total = new TransactionTotalsAssertions($promoter03, $this);
    // $this->totals['promoter03'] = $promoter03_total;

    // $this->assertTotals();

    // /**
    //  * Spender 14
    //  */
    // $spender14 = DbEntityHelper::createSignupMember($promoter03, true, [
    //     'firstName' => 'spender',
    //     'lastName' => '14'
    // ]);
    // $spender14_total = new TransactionTotalsAssertions($spender14, $this);
    // $this->totals['spender14'] = $spender14_total;

    // $sylvheim_total->add(Transaction::REASON_SYLVHEIM);

    // $director1_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    // $director1_total->add(Transaction::REASON_VL_BONUS);
    // $director1_total->add(Transaction::REASON_OL_BONUS);

    // $promoter03_total->add(Transaction::REASON_ADVERTISED_LVL1);
    // $promoter03_total->add(Transaction::REASON_PM_BONUS);

    // $this->assertTotals();

    /**
     * CEO Tree beginns here
     */

    /**
     * Spender I.
     */
    $spenderI = DbEntityHelper::createSignupMember($CEO, true, [
        'firstName' => 'spender',
        'lastName' => 'I.'
    ]);
    $spenderI_total = new TransactionTotalsAssertions($spenderI, $this);
    $this->totals['spenderI'] = $spenderI_total;

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);
    $CEO_total->add(Transaction::REASON_SYLVHEIM);
    $CEO_total->add(Transaction::REASON_VL_BONUS);
    $CEO_total->add(Transaction::REASON_OL_BONUS);
    $CEO_total->add(Transaction::REASON_PM_BONUS);
    $CEO_total->add(Transaction::REASON_ADVERTISED_LVL2);

    $this->assertTotals();

    /**
     * director I.
     *
     */
    $directorI = DbEntityHelper::createMemberWithInvitation($CEO, [
        'type' => \Member::TYPE_MARKETINGLEADER
    ], [
        'firstName' => 'Director',
        'lastName' => 'I'
    ]);
    $directorI_total = new TransactionTotalsAssertions($directorI, $this);
    $this->totals['directorI'] = $directorI_total;

    $CEO_total->add(Transaction::REASON_ADVERTISED_LVL2);
    $CEO_total->add(Transaction::REASON_PM_BONUS);
    $CEO_total->add(Transaction::REASON_VL_BONUS);
    $CEO_total->add(Transaction::REASON_OL_BONUS);
    $CEO_total->add(Transaction::REASON_SYLVHEIM);
    $CEO_total->add(Transaction::REASON_CEO1_BONUS);

    $this->assertTotals();

    /**
     * Spender II
     */
    $spenderII = DbEntityHelper::createSignupMember($directorI, true, [
        'firstName' => 'spender',
        'lastName' => 'II'
    ]);
    $spenderII_total = new TransactionTotalsAssertions($spenderII, $this);
    $this->totals['spenderII'] = $spenderII_total;

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);
    $CEO_total->add(Transaction::REASON_SYLVHEIM);
    $CEO_total->add(Transaction::REASON_ADVERTISED_INDIRECT);

    $directorI_total->add(Transaction::REASON_ADVERTISED_LVL1);
    $directorI_total->add(Transaction::REASON_VL_BONUS);
    $directorI_total->add(Transaction::REASON_OL_BONUS);
    $directorI_total->add(Transaction::REASON_PM_BONUS);

    $this->assertTotals();

    /**
     * Spender III
     */
    $spenderIII = DbEntityHelper::createSignupMember($spenderII, true, [
        'firstName' => 'spender',
        'lastName' => 'III'
    ]);
    $spenderIII_total = new TransactionTotalsAssertions($spenderIII, $this);
    $this->totals['spenderIII'] = $spenderIII_total;

    $CEO_total->add(Transaction::REASON_CEO1_BONUS);
    $CEO_total->add(Transaction::REASON_ADVERTISED_INDIRECT);
    $CEO_total->add(Transaction::REASON_SYLVHEIM);

    $directorI_total->add(Transaction::REASON_PM_BONUS);
    $directorI_total->add(Transaction::REASON_VL_BONUS);
    $directorI_total->add(Transaction::REASON_OL_BONUS);

    $spenderII_total->add(Transaction::REASON_ADVERTISED_LVL1);

    $this->assertTotals();

    /**
     * Spender IV.
     */
    $spenderIV = DbEntityHelper::createSignupMember($CEO, true, [
        'firstName' => 'spender',
        'lastName' => 'IV'
    ]);
    $spenderIV_total = new TransactionTotalsAssertions($spenderIV, $this);
    $this->totals['spenderIV'] = $spenderIV_total;

    $CEO_total->add(Transaction::REASON_ADVERTISED_LVL2);
    $CEO_total->add(Transaction::REASON_CEO1_BONUS);
    $CEO_total->add(Transaction::REASON_SYLVHEIM);
    $CEO_total->add(Transaction::REASON_VL_BONUS);
    $CEO_total->add(Transaction::REASON_OL_BONUS);
    $CEO_total->add(Transaction::REASON_PM_BONUS);

    $this->assertTotals();

  }

}