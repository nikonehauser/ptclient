<?php

namespace Tbmt;

class MailQueue {
  static public function put(
      $froms,
      $recipients,
      $replyTo,
      $subject,
      $body,
      $memberId = null,
      $attachContentAsZip = false
    ) {

    $mail = new \Mail();
    $mail
      ->setFroms(json_encode($froms))
      ->setRecipients(json_encode($recipients))
      ->setReplyTos(json_encode($replyTo))
      ->setSubject($subject)
      ->setBody($body)
      ->setRecipientId($memberId)
      ->setAttachContentAsZip($attachContentAsZip ? 1 : 0)
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

    $incidents = [];
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

        $incidents[] = $result;
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
      "errors_count: $errors",
      "errors:\n". implode("\n", $incidents)
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

    $mailer->setFrom($from[0], $from[1]);
    $mailer->addReplyTo($replyTo[0], $replyTo[1]);
    $mailer->addAddress($recipient[0], $recipient[1]);

    $mailer->Subject = $subject;

    $tmp = null;
    if ( $mail->getAttachContentAsZip() ) {
      $mailer->Body = 'content attached';
      $mailer->AltBody = 'content attached';

      $tmp = Config::get('tmp.path').uniqid();
      $zip = new \ZipArchive();
      if ( $zip->open($tmp, \ZipArchive::CREATE) !== TRUE)
          throw new \Exception("cannot open <$tmp>\n");

      if ( $zip->addFromString('content.txt', $body) === false )
        throw new \Exception("zip: cannot add string content");

      $zip->setPassword('test1234');
      if ( $zip->close() === false )
        throw new \Exception("zip: could not create zip <$tmp>");

      $mailer->addAttachment($tmp, 'content.zip', 'base64', 'application/zip');
    } else {
      $htmlBody = (new \Parsedown())->text($body);
      $mailer->Body = MailHelper::bodyToHtml($htmlBody);
      $mailer->AltBody = $body;
    }

    $result = true;
    try {
      if ( !$mailer->send() )
        $result = 'ErrorInfo: '.$mailer->ErrorInfo;
    } catch (\Exception $e) {
      $result = 'ErrorInfo: '.$mailer->ErrorInfo."\n\nException:\n".$e->__toString();
    }

    if ( $tmp)
      unlink($tmp);

    return $result;
  }
}


?>
