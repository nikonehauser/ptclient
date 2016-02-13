<?php

return [
  "baseurl" => "http://localhost/ptclient/index.php",
  "basepath" => "C:/xampp/htdocs/ptclient",

  "baseAssetsPath" => "",

  "devmode" => true,
  "send_email_on_error" => false,

  "base_currency" => "INR",
  "member_fee" => 75.3,

  "amounts" => [
    // const REASON_ADVERTISED_LVL1     = 0;
    "0" => 300,
    //const REASON_ADVERTISED_LVL2     = 1;
    "1" => 1500,
    // const REASON_ADVERTISED_INDIRECT = 2;
    "2" => 1200,

    // const REASON_VL_BONUS = 3;
    "3" => 70,
    // const REASON_OL_BONUS = 4;
    "4" => 70,
    // const REASON_PM_BONUS = 5;
    "5" => 70,
    // const REASON_IT_BONUS = 6;
    "6" => 70,
    // const REASON_CEO1_BONUS = 7;
    "7" => 2780,
    // const REASON_CEO2_BONUS = 8;
    // "8" => 6.2, removed
    // const REASON_LAWYER_BONUS = 9;
    // "9" => 7.1, removed
    // const REASON_SUB_PM_BONUS = 10;
    "10" => 70,
    // const REASON_SUB_PM_REF_BONUS = 11;
    "11" => 70,
    // const REASON_SYLVHEIM = 12;
    "12" => 210,
    // const REASON_EXECUTIVE = 13;
    "13" => 50,
    // const REASON_TARIC_WANI = 14;
    "14" => 50,
    // const REASON_NGO_PROJECTS = 15;
    "15" => 550
  ],

  "contact_mail_recipient" => "niko.neuhauser@gmail.com",
  "error_mail_recipient" => "niko.neuhauser@gmail.com",

  "main_system_url" => "http://localhost/ptmain/",

  "client_name" => "india",

  "secret_salt" => "APc7a5vhCiyNfYn6KvkLfs0oMDwLZtatdIlfFE7ObcP4quxevQrVq0vLXeKPalKz",


  "brand.name" => "Help Yourself Association",
  "brand.short" => "HYA",
  "brand.mail" => "info@hya.net",


  "mail.smtp_secure" => "ssl",
  "mail.smtp_host" => "smtp.googlemail.com",
  "mail.smtp_port" => 465,
  "mail.username" => "ssl",
  "mail.password" => "ssl",
  "mail.timeout" => 10,

  "mail.subject_prefix" => "[Help Yourself Association]",
  "mail.sender_mail" => "demo@hya.net",
  "mail.sender_name" => "Help Yourself Association",
  "mail.reply_mail" => "demo@hya.net"
];

?>