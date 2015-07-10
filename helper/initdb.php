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
      ->setTitle('email'.$i)
      ->setEmail('email'.$i)
      ->setCity('email'.$i)
      ->setCountry('email'.$i)
      ->setAge(20)
      ->setBankRecipient('email'.$i)
      ->setBic('email'.$i)
      ->setIban('email'.$i)
      ->setPassword('demo1234')
      ->setSignupDate($now)
      ->save();
  }
}

?>