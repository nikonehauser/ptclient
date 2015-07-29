<?php

include dirname(__FILE__).'/bootstrap.php';

$con = Propel::getConnection();
SystemSetup::setCon($con);
SystemSetup::doSetup();

?>