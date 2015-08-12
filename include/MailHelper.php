<?php

namespace Tbmt;

require VENDOR_DIR.'phpmailer'.DIRECTORY_SEPARATOR.'phpmailer'.DIRECTORY_SEPARATOR.'PHPMailerAutoload.php';

class MailHelper {

  static public function sendException(\Exception $e) {
    $body = "Exception: \n\r".$e->getMessage()."\n\r\n\r".
      "Stack: \n\r".$e->getTraceAsString()."\n\r\n\r".
      "Request: \n\r".json_encode($_REQUEST, JSON_PRETTY_PRINT)."\n\r\n\r".
      "Server: \n\r".json_encode($_SERVER, JSON_PRETTY_PRINT)."\n\r\n\r".
      "Session: \n\r".json_encode($_SESSION, JSON_PRETTY_PRINT)."\n\r\n\r";

    if ( count(\Activity::$_ActivityExceptions) > 0 ) {
      $body .= "ActivityExceptions: \n\r".json_encode(\Activity::$_ActivityExceptions, JSON_PRETTY_PRINT)."\n\r\n\r";
    }

    return self::send(
      Config::get('error_mail_recipient'),
      null,
      ' - Exception - '.$e->getMessage(),
      $body
    );
  }

  static public function sendContactFormMail($fromMail, $fromName, $subject, $body) {
    $body = "From mail: $fromMail\n\r".
      "From name: $fromName\n\r\n\r".
      "Body:\n\r$body\n\r";

    return self::send(
      Config::get('contact_mail_recipient'),
      null,
      ' - Contact Form - '.$subject,
      $body,
      $fromMail,
      $fromName
    );
  }

  static public function sendPasswordResetLink(\Member $member) {
    $email = $member->getEmail();
    $locale = Localizer::get('mail.password_reset');

    $num = $member->getNum();
    $now = time();
    $email = $member->getEmail();

    $href = Router::toModule('manage', 'do_reset_password', [
      'num' => $num,
      'exp' => time(),
      'hash' => Cryption::getPasswordResetToken($num, $now, $email)
    ]);

    return self::send(
      $email,
      \Tbmt\view\Factory::buildMemberFullNameString($member),
      $locale['subject'],
      Localizer::insert($locale['body'], [
        'link' => $href
      ], false)
    );
  }

  static function send($address, $name, $subject, $body, $fromMail = null, $fromName = null) {
    $mail = new \PHPMailer(true);
    $mail->SMTPSecure = Config::get('mail.smtp_secure');
    $mail->isSMTP();

    $mail->Host = Config::get('mail.smtp_host');
    $mail->Port = Config::get('mail.smtp_port');
    $mail->SMTPAuth = true;
    $mail->Username = Config::get('mail.username');
    $mail->Password = Config::get('mail.password');
    $mail->Timeout = Config::get('mail.timeout');
    $mail->CharSet = Config::get('mail.charset', TYPE_STRING, 'utf-8');

    if ( !$fromMail )
      $fromMail = Config::get('mail.sender_mail');

    if ( !$fromName )
      $fromName = Config::get('mail.sender_name');

    $mail->setFrom($fromMail, $fromName);
    $mail->addReplyTo(Config::get('mail.reply_mail'), 'Do not Reply');
    $mail->addAddress($address, $name);

    $mail->Subject = Config::get('mail.subject_prefix').' '.$subject;
    $mail->Body = $body;

    $boolResult = $mail->send();
    if(!$boolResult)
      throw new Exception('Mailer Error: '.$mail->ErrorInfo);

    return $boolResult;
  }
}

?>