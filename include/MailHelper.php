<?php

namespace Tbmt;

require VENDOR_DIR.'phpmailer'.DIRECTORY_SEPARATOR.'phpmailer'.DIRECTORY_SEPARATOR.'PHPMailerAutoload.php';

class MailHelper {

  static public $MAILS_DISABLED =  false;
  static public $DEBUG_PRINT =  false;

  static public function getLocalizedTRA($transactinoReason) {
    return Localizer::numFormat(\Transaction::getAmountForReason($transactinoReason), 0);
  }

  static public function sendException(\Exception $e) {
    $body = "Exception: \n\r".$e->getMessage()."\n\r\n\r".
      "Stack: \n\r".$e->getTraceAsString()."\n\r\n\r".
      "Request: \n\r".json_encode($_REQUEST, JSON_PRETTY_PRINT)."\n\r\n\r".
      "Server: \n\r".json_encode($_SERVER, JSON_PRETTY_PRINT)."\n\r\n\r";

    if ( isset($_SESSION) )
      $body .= "Session: \n\r".json_encode($_SESSION, JSON_PRETTY_PRINT)."\n\r\n\r";

    if ( class_exists('Activity') && count(\Activity::$_ActivityExceptions) > 0 ) {
      $body .= "ActivityExceptions: \n\r".json_encode(\Activity::$_ActivityExceptions, JSON_PRETTY_PRINT)."\n\r\n\r";
    }

    return self::send(
      Config::get('error_mail_recipient'),
      null,
      ' - Exception - '.$e->getMessage(),
      $body
    );
  }

  static public function sendEmailValidation($recipientEmail, $recipientFullName, \EmailValidation $emailValidation) {
    $locale = Localizer::get('mail.email_validation');

    $href = Router::toModule('member', 'confirm_email_registration', [
      'hash' => $emailValidation->getHash()
    ]);

    return self::send(
      $recipientEmail,
      $recipientFullName,
      $locale['subject'],
      Localizer::insert($locale['body'], [
        'fullname' => $recipientFullName,
        'link' => $href
      ], false)
    );
  }


  /**
   * #1_1
   *
   * @param  \Member        $member
   * @param  PropelPDO|null $con
   * @return [type]
   */
  static public function sendFreeSignupConfirm(\Member $member, PropelPDO $con = null) {
    $email = $member->getEmail();
    $locale = Localizer::get('mail.free_signup_confirm');

    $num = $member->getNum();
    $fullName = \Tbmt\view\Factory::buildMemberFullNameString($member);

    $referrer = $member->getReferrerMember($con);
    $referrerFullName = \Tbmt\view\Factory::buildMemberFullNameString($referrer);

    return self::send(
      $email,
      $fullName,
      $locale['subject'],
      Localizer::insert($locale['body'], [
        'fullname' => $fullName,
        'member_id' => $num,
        'member_type' => Localizer::get('common.member_types')[$member->getType()],
        'referrer_fullname' => $referrerFullName,
        'video_link' => \Tbmt\Router::toVideo(),
        'signup_link' => \Tbmt\Router::toSignup($member),
        'after6weeksamount' => Localizer::numFormat(300000, 0)
      ], false)
    );
  }

  /**
   * #1
   *
   * @param  \Member        $member
   * @param  PropelPDO|null $con
   * @return [type]
   */
  static public function sendSignupConfirm(\Member $member, PropelPDO $con = null) {
    $email = $member->getEmail();
    $locale = Localizer::get('mail.signup_confirm');

    $num = $member->getNum();
    $fullName = \Tbmt\view\Factory::buildMemberFullNameString($member);

    $referrer = $member->getReferrerMember($con);
    $referrerFullName = \Tbmt\view\Factory::buildMemberFullNameString($referrer);

    return self::send(
      $email,
      $fullName,
      $locale['subject'],
      Localizer::insert($locale['body'], [
        'fullname' => $fullName,
        'member_id' => $num,
        'recruiter' => $referrerFullName,
        'fmt_member_fee' => \Tbmt\view\Factory::buildFmtMemberFeeStr(),
        'bankaccount' => \Tbmt\view\Factory::buildBankAccountStr(),
        'duedate' => \Tbmt\Localizer::dateLong($member->getFirstDueDate())
      ], false)
    );
  }


  /**
   * #2_1
   * @param  \Member $referrer
   * @param  \Member $recruited
   * @return [type]
   */
  static public function sendNewFreeRecruitmentCongrats(\Member $referrer, \Member $recruited) {
    $email = $referrer->getEmail();
    $locale = Localizer::get('mail.new_free_recruitment_congrats');

    $num = $referrer->getNum();
    $fullName = \Tbmt\view\Factory::buildMemberFullNameString($referrer);

    $recruitedFullName = \Tbmt\view\Factory::buildMemberFullNameString($recruited);

    return self::send(
      $email,
      $fullName,
      $locale['subject'],
      Localizer::insert($locale['body'], [
        'fullname' => $fullName,
        'member_id' => $num,
        'recruited_fullname' => $recruitedFullName,
        'video_link' => \Tbmt\Router::toVideo(),
      ], false)
    );
  }


  /**
   * #2
   * @param  \Member $referrer
   * @param  \Member $recruited
   * @return [type]
   */
  static public function sendNewRecruitmentCongrats(\Member $referrer, \Member $recruited) {
    $email = $referrer->getEmail();
    $locale = Localizer::get('mail.new_recruitment_congrats');

    $num = $referrer->getNum();
    $fullName = \Tbmt\view\Factory::buildMemberFullNameString($referrer);

    $recruitedFullName = \Tbmt\view\Factory::buildMemberFullNameString($recruited);

    return self::send(
      $email,
      $fullName,
      $locale['subject'],
      Localizer::insert($locale['body'], [
        'fullname' => $fullName,
        'member_id' => $num,
        'recommendation_count' => \Tbmt\Localizer::countInWords($referrer->getAdvertisedCountTotal()),
        'recruited_fullname' => $recruitedFullName,
        'video_link' => \Tbmt\Router::toVideo(),
        'duedate' => \Tbmt\Localizer::dateLong($recruited->getFirstDueDate())
      ], false)
    );
  }

  /**
   * #3
   * @param  \Member $member
   * @return [type]
   */
  static public function sendFeeReminder(\Member $member) {
    $email = $member->getEmail();
    $locale = Localizer::get('mail.fee_reminder');

    $num = $member->getNum();
    $fullName = \Tbmt\view\Factory::buildMemberFullNameString($member);

    return self::send(
      $email,
      $fullName,
      $locale['subject'],
      Localizer::insert($locale['body'], [
        'fullname' => $fullName,
        'member_id' => $num,
        'signup_date' => \Tbmt\Localizer::dateLong($member->getSignupDate()),
        'video_link' => \Tbmt\Router::toVideo(),
        'bankaccount' => \Tbmt\view\Factory::buildBankAccountStr(),
        'duedate_second' => \Tbmt\Localizer::dateLong($member->getSecondDueDate())
      ], false)
    );
  }

  /**
   * #4
   * @param  \Member $referrer
   * @return [type]
   */
  static public function sendFeeReminderReferrer(\Member $referrer, \Member $recruited) {
    $email = $referrer->getEmail();
    $locale = Localizer::get('mail.fee_reminder_referrer');

    $num = $referrer->getNum();
    $fullName = \Tbmt\view\Factory::buildMemberFullNameString($referrer);
    $recruited_fullname = \Tbmt\view\Factory::buildMemberFullNameString($recruited);

    return self::send(
      $email,
      $fullName,
      Localizer::insert($locale['subject'], ['recruited_fullname' => $recruited_fullname]),
      Localizer::insert($locale['body'], [
        'fullname' => $fullName,
        'member_id' => $num,
        'recruited_fullname' => $recruited_fullname,
        'recruited_firstname' => $recruited->getFirstName(),
        'recruited_signup_date' => \Tbmt\Localizer::dateLong($recruited->getSignupDate()),
        'bankaccount' => \Tbmt\view\Factory::buildBankAccountStr(),
      ], false)
    );
  }

  /**
   * #5
   * @param  \Member $referrer
   * @return [type]
   */
  static public function sendFeeReminderWithAdvertisings(\Member $member) {
    $email = $member->getEmail();
    $locale = Localizer::get('mail.fee_reminder_with_advertisings');

    $num = $member->getNum();
    $fullName = \Tbmt\view\Factory::buildMemberFullNameString($member);

    return self::send(
      $email,
      $fullName,
      $locale['subject'],
      Localizer::insert($locale['body'], [
        'fullname' => $fullName,
        'member_id' => $num,
        'signup_date' => \Tbmt\Localizer::dateLong($member->getSignupDate()),
        'duedate_second' => \Tbmt\Localizer::dateLong($member->getSecondDueDate()),
        'bankaccount' => \Tbmt\view\Factory::buildBankAccountStr(),
        'video_link' => \Tbmt\Router::toVideo(),
        'advindirectamount' => self::getLocalizedTRA(\Transaction::REASON_ADVERTISED_INDIRECT)
      ], false)
    );
  }

  /**
   * #6
   * @param  \Member $referrer
   * @return [type]
   */
  static public function sendFeeReminderWithAdvertisingsReferrer(\Member $referrer, \Member $recruited) {
    $email = $referrer->getEmail();
    $locale = Localizer::get('mail.fee_reminder_referrer_with_advertisings');

    $num = $referrer->getNum();
    $fullName = \Tbmt\view\Factory::buildMemberFullNameString($referrer);
    $recruited_fullname = \Tbmt\view\Factory::buildMemberFullNameString($recruited);

    return self::send(
      $email,
      $fullName,
      Localizer::insert($locale['subject'], ['recruited_fullname' => $recruited_fullname]),
      Localizer::insert($locale['body'], [
        'fullname' => $fullName,
        'member_id' => $num,
        'recruited_fullname' => $recruited_fullname,
        'recruited_firstname' => $recruited->getFirstName(),
        'recruited_signup_date' => \Tbmt\Localizer::dateLong($recruited->getSignupDate()),
        'bankaccount' => \Tbmt\view\Factory::buildBankAccountStr(),
      ], false)
    );
  }

  /**
   * #7
   * @param  \Member $member
   * @return [type]
   */
  static public function sendFeeIncome(\Member $member) {
    $email = $member->getEmail();
    $locale = Localizer::get('mail.fee_income');

    $referrer = $member->getReferrerMember();

    $num = $member->getNum();
    $fullName = \Tbmt\view\Factory::buildMemberFullNameString($member);
    $referrer_fullname = \Tbmt\view\Factory::buildMemberFullNameString($referrer);

    return self::send(
      $email,
      $fullName,
      $locale['subject'],
      Localizer::insert($locale['body'], [
        'fullname' => $fullName,
        'member_id' => $num,
        'referrer_fullname' => $referrer_fullname,
        'video_link' => \Tbmt\Router::toVideo(),
        'signup_link' => \Tbmt\Router::toSignup($member),
        'after6weeksamount' => Localizer::numFormat(300000, 0)
      ], false)
    );
  }

  /**
   * #8
   * @param  \Member $member
   * @return [type]
   */
  static public function sendFeeIncomeReferrer(\Member $referrer, \Member $recruited) {
    $email = $referrer->getEmail();
    $locale = Localizer::get('mail.fee_income_referrer');

    $fullName = \Tbmt\view\Factory::buildMemberFullNameString($referrer);
    $recruited_fullname = \Tbmt\view\Factory::buildMemberFullNameString($recruited);

    if ( $referrer->getFundsLevel() == \Member::FUNDS_LEVEL2 )
      $provision = self::getLocalizedTRA(\Transaction::REASON_ADVERTISED_LVL2);
    else
      $provision = self::getLocalizedTRA(\Transaction::REASON_ADVERTISED_LVL1);

    $body = $locale['body'];
    if ( $referrer->getFundsLevel() == \Member::FUNDS_LEVEL1 )
      $body .= "\n".$locale['level1_addition'];

    return self::send(
      $email,
      $fullName,
      $locale['subject'],
      Localizer::insert($body, [
        'fullname' => $fullName,
        'recruited_fullname' => $recruited_fullname,
        'recruited_firstname' => $recruited->getFirstName(),
        'video_link' => \Tbmt\Router::toVideo(),
        'provision_amount' => $provision,
        'adv2amount' => self::getLocalizedTRA(\Transaction::REASON_ADVERTISED_LVL2),
        'memberfee_amount' => Localizer::numFormat(\Transaction::$MEMBER_FEE, 0),
        'member_id' => $referrer->getNum(),
      ], false)
    );
  }

  /**
   * #9
   * @param  \Member $member
   * @return [type]
   */
  static public function sendFundsLevelUpgrade(\Member $referrer, \Member $recruited) {
    $email = $referrer->getEmail();
    $locale = Localizer::get('mail.funds_level_upgrade');

    $fullName = \Tbmt\view\Factory::buildMemberFullNameString($referrer);
    $recruited_fullname = \Tbmt\view\Factory::buildMemberFullNameString($recruited);

    return self::send(
      $email,
      $fullName,
      $locale['subject'],
      Localizer::insert($locale['body'], [
        'fullname' => $fullName,
        'recruited_fullname' => $recruited_fullname,
        'video_link' => \Tbmt\Router::toVideo(),
        'adv1amount' => self::getLocalizedTRA(\Transaction::REASON_ADVERTISED_LVL1),
        'adv2amount' => self::getLocalizedTRA(\Transaction::REASON_ADVERTISED_LVL2),
        'advindirectamount' => self::getLocalizedTRA(\Transaction::REASON_ADVERTISED_INDIRECT),
        'after6weeksamount' => Localizer::numFormat(300000, 0)
      ], false)
    );
  }


  static public function sendContactFormMail($fromMail, $fromPhone, $fromName, $subject, $body) {
    $body = "From mail: $fromMail\n\r".
      "From phone: $fromPhone\n\r".
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

  static public function send($address, $name, $subject, $body, $fromMail = null, $fromName = null) {
    if ( self::$MAILS_DISABLED === true )
      return true;

    $mail = new \PHPMailer(true);
    $mail->SMTPSecure = Config::get('mail.smtp_secure');
    $mail->isSMTP();

$mail->SMTPDebug = 3;

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

    $body .= "\n\n".Config::get('mail.signature')."\n";

    $mail->setFrom($fromMail, $fromName);
    $mail->addReplyTo(Config::get('mail.reply_mail'), 'Do not Reply');
    $mail->addAddress($address, $name);

    $mail->Subject = Config::get('mail.subject_prefix').' '.$subject;
    $mail->Body = $body;

    if ( self::$DEBUG_PRINT === true )
      return [$address, $name, $mail->Subject, $mail->Body];

    $boolResult = $mail->send();
    if(!$boolResult)
      throw new Exception('Mailer Error: '.$mail->ErrorInfo);

    return $boolResult;
  }
}

?>