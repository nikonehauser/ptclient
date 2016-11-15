<?php

  $schema = file_get_contents('./schema.xml');
  $schema = str_replace([
    // table prefix
    'tbmt_',

    // phpname prefix
    'phpName="Tbmt'
  ], [
    // table prefix
    '',

    // phpname prefix
    'phpName="'
  ], $schema);

  file_put_contents('./schema.xml', $schema);
?>