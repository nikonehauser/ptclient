<?php

require dirname(__FILE__).DIRECTORY_SEPARATOR.'bootstrap.php';
\Tbmt\Cron::run('remove_unpaid', [false]);


?>