<?php

require dirname(__FILE__).DIRECTORY_SEPARATOR.'bootstrap.php';
\Tbmt\Cron::run('notify_new_guide');

?>