<?php

require './bootstrap.php';
\Tbmt\Cron::run('email_reminder');

?>