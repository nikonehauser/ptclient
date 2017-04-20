<?php

require dirname(__FILE__).DIRECTORY_SEPARATOR.'bootstrap.php';
\Tbmt\Cron::run('email_reminder');

?>