<?php

namespace Tbmt;

class MailQueue {
  static public function put(
      $froms,
      $recipients,
      $replyTo,
      $subject,
      $body,
      $memberId = null
    ) {

    $mail = new \Mail();
    $mail
      ->setFroms(json_encode($froms))
      ->setRecipients(json_encode($recipients))
      ->setReplyTos(json_encode($replyTo))
      ->setSubject($subject)
      ->setBody($body)
      ->setRecipientId($memberId)
      ->save();
  }

  static public function run($limit = 100) {
    $removeSendMails = \Tbmt\Config::get('remove_send_mails', \Tbmt\TYPE_BOOL, false);

    $errors = 0;
    $success = 0;

    $maxAttempts = 5;

    $query = \MailQuery::create()
      ->filterByStatus([
          \Mail::STATUS_NONE,
          \Mail::STATUS_INCIDENT
      ], \Criteria::IN)
      ->filterByAttempts($maxAttempts, \Criteria::LESS_THAN)
      ->orderBy(\MailPeer::CREATION_DATE, \Criteria::ASC)
      ->orderBy(\MailPeer::STATUS, \Criteria::ASC)
      ->limit($limit);

    foreach ( $query->find() as $mail ) {
      $result = 'Unknown Incident';
      try {
        $result = self::send($mail);
      } catch (\Exception $e) {
        $result = $e->__toString();
        print_r($e->__toString());

      }

      if ( $result === true ) {
        $success++;

        if ( $mail->hasIncidents() || !$removeSendMails ) {
          $mail->setStatus(\Mail::STATUS_SEND);
          $mail->save();
        } else {
          $mail->delete();
        }

      } else {
        $errors++;

        $mail
          ->setAttempts($mail->getAttempts() + 1)
          ->addIncident($result)
          ->setStatus(
            $mail->getAttempts() > $maxAttempts
            ? \Mail::STATUS_ERROR
            : \Mail::STATUS_INCIDENT
          )
          ->save();

      }
    }

    return implode(', ', [
      "success: $success",
      "errors: $errors",
    ]);
  }

  static public function purge() {

  }

  static private function getMailer() {
    $mail = new \PHPMailer(true);
    $mail->SMTPSecure = Config::get('mail.smtp_secure');
    $mail->isSMTP();

    $debugLevel = Config::get('mail.debug_level', TYPE_INT);
    if ( $debugLevel != '' )
      $mail->SMTPDebug = $debugLevel;

    $mail->Host = Config::get('mail.smtp_host');
    $mail->Port = Config::get('mail.smtp_port');
    $mail->SMTPAuth = true;
    $mail->Username = Config::get('mail.username');
    $mail->Password = Config::get('mail.password');
    $mail->Timeout = Config::get('mail.timeout');
    $mail->CharSet = Config::get('mail.charset', TYPE_STRING, 'utf-8');

    return $mail;
  }

  static private function send(\Mail $mail) {
    $mailer = self::getMailer();

    $body = $mail->getBody();
    $subject = $mail->getSubject();

    $froms = json_decode($mail->getFroms(), true);
    if ( count($froms) > 1 )
      throw new \Exception('Not implemented');

    $replyTo = json_decode($mail->getReplyTos(), true);

    $recipients = json_decode($mail->getRecipients(), true);
    if ( count($recipients) > 1 )
      throw new \Exception('Not implemented');

    $from = $froms[0];
    $recipient = $recipients[0];

    $htmlBody = (new \Parsedown())->text($body);

    $mailer->setFrom($from[0], $from[1]);
    $mailer->addReplyTo($replyTo[0], $replyTo[1]);
    $mailer->addAddress($recipient[0], $recipient[1]);

    $mailer->Subject = $subject;
    $mailer->Body = MailHelper::bodyToHtml($htmlBody);
    $mailer->AltBody = $body;

    try {
      if ( !$mailer->send() )
        return 'ErrorInfo: '.$mailer->ErrorInfo;
    } catch (\Exception $e) {
      return 'ErrorInfo: '.$mailer->ErrorInfo."\n\nException:\n".$e->__toString();
    }

    return true;
  }
}


?>
