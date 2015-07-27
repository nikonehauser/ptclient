<?php

include dirname(__FILE__).'/bootstrap.php';

$con = Propel::getConnection();

$currencies = new SimpleXMLElement(file_get_contents(dirname(__FILE__).'/iso4217_currencies.xml'));

$tbl = $currencies->CcyTbl;
$entries = $tbl->CcyNtry;

$done = [];

foreach ( $entries as $entry ) {
  if ( isset($done[(string)$entry->Ccy]) )
    continue;

  $minorUnit = (string)$entry->CcyMnrUnts;
  if ( is_numeric($minorUnit) )
    $minorUnit = intval($minorUnit);
  else
    $minorUnit = 0;

  $currency = new Currency();
  $currency
    ->setName((string)$entry->CcyNm)
    ->setAlphabeticCode((string)$entry->Ccy)
    ->setNumericCode((string)$entry->CcyNbr)
    ->setMinorUnit($minorUnit)
    ->save($con);

  $done[(string)$entry->Ccy] = true;
}

?>
