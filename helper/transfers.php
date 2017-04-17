<?php

include dirname(__FILE__).'/bootstrap.php';

$now = time();

$currency = \Tbmt\Config::get('payout.target.currency');

$con = Propel::getConnection();
DbEntityHelper::setCon($con);

\Tbmt\MailHelper::$MAILS_DISABLED = true;


$sylvheim = Member::getByNum(\SystemStats::ACCOUNT_SYLVHEIM);

$a1 = DbEntityHelper::createSignupMember($sylvheim, true, [
  'title'          => 'unknown',
  'lastName'       => 'Chandhok',
  'firstName'      => 'Mayank',
  'city'           => 'unknown',
  'country'        => 'unknown',
  'zip_code'       => '504231',
  'bank_recipient' => 'Chandhok Mayank',
  'iban'           => '02050030035996',
  'bic'            => 'KKBK0000205',
]);

$a2 = DbEntityHelper::createSignupMember($sylvheim, true, [
  'title'          => 'unknown',
  'lastName'       => 'Pahwa',
  'firstName'      => 'Manoj',
  'city'           => 'unknown',
  'country'        => 'unknown',
  'zip_code'       => '504231',
  'bank_recipient' => 'Manoj Pahwa and Associates',
  'iban'           => '40100200000167',
  'bic'            => 'BARB0MKCHOW',
]);

$a3 = DbEntityHelper::createSignupMember($sylvheim, true, [
  'title'          => 'unknown',
  'lastName'       => 'Roedl',
  'firstName'      => 'Unknown',
  'city'           => 'unknown',
  'country'        => 'unknown',
  'zip_code'       => '504231',
  'bank_recipient' => 'Roedl und Partner India Pvt. Ltd.',
  'iban'           => '000005268750019',
  'bic'            => 'DEUT0279PBC',
]);

DbEntityHelper::createSignupMember($a1, true);
DbEntityHelper::createSignupMember($a2, true);
DbEntityHelper::createSignupMember($a3, true);

$t1 = $a1->getCurrentTransferBundle($currency, $con);
$t1->setAmount(10);
$t1->save($con);

$t2 = $a2->getCurrentTransferBundle($currency, $con);
$t2->setAmount(10);
$t2->save($con);

$t3 = $a3->getCurrentTransferBundle($currency, $con);
$t3->setAmount(10);
$t3->save($con);

?>