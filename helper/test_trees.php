<?php

include dirname(__FILE__).'/bootstrap.php';

$con = Propel::getConnection();
DbEntityHelper::setCon($con);
DbEntityHelper::truncateDatabase();

list(
  list($IT, $VL, $OL, $PM, $VS2, $VS1),
  list($trfIT, $trfVL, $trfOL, $trfPM, $trfVS2, $trfVS1),
) = DbEntityHelper::setUpBonusMembers(false);

$pm1 = DbEntityHelper::createSignupMember($PM);
$pm2 = DbEntityHelper::createSignupMember($PM);
$pm3 = DbEntityHelper::createSignupMember($PM);
$pm4 = DbEntityHelper::createSignupMember($PM);
$pm5 = DbEntityHelper::createSignupMember($PM);

$pm1_1 = DbEntityHelper::createSignupMember($pm1);
$pm1_2 = DbEntityHelper::createSignupMember($pm1);
$pm1_3 = DbEntityHelper::createSignupMember($pm1);
$pm1_4 = DbEntityHelper::createSignupMember($pm1);
$pm1_5 = DbEntityHelper::createSignupMember($pm1);
$pm1_6 = DbEntityHelper::createSignupMember($pm1);

$pm2_1 = DbEntityHelper::createSignupMember($pm2);
$pm2_1_1 = DbEntityHelper::createSignupMember($pm2_1);
$pm2_1_1_1 = DbEntityHelper::createSignupMember($pm2_1_1);
$pm2_1_1_1_1 = DbEntityHelper::createSignupMember($pm2_1_1_1);
$pm2_1_1_1_1_1 = DbEntityHelper::createSignupMember($pm2_1_1_1_1);
DbEntityHelper::createSignupMember($pm2_1_1_1_1_1);
DbEntityHelper::createSignupMember($pm2_1_1_1_1_1);
DbEntityHelper::createSignupMember($pm2_1_1_1_1_1);

DbEntityHelper::createSignupMember($pm4);
DbEntityHelper::createSignupMember($pm4);

?>