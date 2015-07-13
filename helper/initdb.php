<?php

define('CREATE_MEMBER', 15);


include './bootstrap.php';

$now = time();

if ( CREATE_MEMBER ) {
  for ( $i = 0; $i < CREATE_MEMBER; $i++ ) {
    $member = new Member();
    $member
      ->setFirstName('firstname'.$i)
      ->setLastName('lastname'.$i)
      // ->setNum() autoincrement
      ->setTitle('title'.$i)
      ->setEmail('email'.$i)
      ->setCity('city'.$i)
      ->setCountry('country'.$i)
      ->setAge(20)
      ->setBankRecipient('bank_recipient'.$i)
      ->setBic('bic'.$i)
      ->setIban('iban'.$i)
      ->setPassword('demo1234')
      ->setSignupDate($now)
      ->save();
  }
}

?>