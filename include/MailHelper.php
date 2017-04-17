<?php

namespace Tbmt;

require VENDOR_DIR.'phpmailer'.DIRECTORY_SEPARATOR.'phpmailer'.DIRECTORY_SEPARATOR.'PHPMailerAutoload.php';

class MailHelper {

  static public $MAILS_DISABLED =  false;
  static public $DEBUG_PRINT =  false;

  static public function getLocalizedTRA($transactinoReason) {
    return Localizer::numFormat(\Transaction::getAmountForReason($transactinoReason), 0);
  }

  static public function getLocalizedTRACurency($transactinoReason, $decimals = false, $space = ' ') {
    return self::getLocalizedAmount(
      \Transaction::getAmountForReason($transactinoReason),
      $decimals,
      $space
    );
  }

  static public function getLocalizedAmount($amount, $decimals = false, $space = ' ') {
    return Localizer::currencyFormat(
      $amount,
      Localizer::get('currency_symbol.'.\Transaction::$BASE_CURRENCY),
      $decimals,
      $space
    );
  }

  static public function sendException(\Exception $e, $text = '') {
    $body = "Exception: \n\r".$e->getMessage()."\n\r\n\r".
      "Stack: \n\r".$e->getTraceAsString()."\n\r\n\r".
      "Request: \n\r".json_encode($_REQUEST, JSON_PRETTY_PRINT)."\n\r\n\r".
      "Server: \n\r".json_encode($_SERVER, JSON_PRETTY_PRINT)."\n\r\n\r";

    if ( isset($_SESSION) )
      $body .= "Session: \n\r".json_encode($_SESSION, JSON_PRETTY_PRINT)."\n\r\n\r";

    if ( class_exists('Activity') && count(\Activity::$_ActivityExceptions) > 0 ) {
      $body .= "ActivityExceptions: \n\r".json_encode(\Activity::$_ActivityExceptions, JSON_PRETTY_PRINT)."\n\r\n\r";
    }

    if ( $text ) {
      $body .= "MESSAGE: \n\r".$text."\n\r\n\r";
    }

    return self::send(
      Config::get('error_mail_recipient'),
      null,
      ' - Exception - '.$e->getMessage(),
      $body
    );
  }

  /**
   * #1
   *
   * @param  [type]           $recipientEmail
   * @param  [type]           $recipientFullName
   * @param  \EmailValidation $emailValidation
   * @return [type]
   */
  static public function sendEmailValidation($recipientEmail, $recipientFullName, \EmailValidation $emailValidation) {
    $locale = Localizer::get('mail.email_validation');

    $href = RouterToMarketing::toModule('member', 'confirm_email_registration', [
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
   * #2
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
   * #3
   *
   * @param  \Member $member
   * @return [type]
   */
  static public function sendFeeIncome(\Member $member) {
    $email = $member->getEmail();
    $locale = Localizer::get(
      $member->isExtended()
      ? 'mail.fee_income'
      : 'mail.fee_income_tbmt_product'
    );

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
        'fmt_member_fee' => \Tbmt\view\Factory::buildFmtMemberFeeStr(),

        'member_id' => $num,
        'referrer_fullname' => $referrer_fullname,
        'video_link' => \Tbmt\RouterToMarketing::toVideo($member),
        'signup_link' => \Tbmt\RouterToMarketing::toSignup($member),
        'after6weeksamount' => self::getLocalizedAmount(10000, 0)
      ], false)
    );
  }

  /**
   * #4
   * #5
   * #6
   *
   * @param  \Member $member
   * @return [type]
   */
  static public function sendSignupConfirmInvitation(\Member $member, $wasFreeInvitation) {
    $email = $member->getEmail();
    $locale = Localizer::get('mail.signup_confirm_invitation');

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
        'fmt_member_fee' => \Tbmt\view\Factory::buildFmtMemberFeeStr(),

        'member_id' => $num,
        'referrer_fullname' => $referrer_fullname,
        'video_link' => \Tbmt\RouterToMarketing::toVideo($member),
        'signup_link' => \Tbmt\RouterToMarketing::toSignup($member),
        'after6weeksamount' => self::getLocalizedAmount(10000, 0),

        'free_invitation' => ($wasFreeInvitation
          ? Localizer::get('mails.free_invitation')
          : ''),

        'lvl2text' => ($member->getFundsLevel() === \Member::FUNDS_LEVEL2
          ? Localizer::getInsert('mails.lvl2invitation', [
              'lvl2bonus' => self::getLocalizedTRACurency(\Transaction::REASON_ADVERTISED_LVL2),
              'lvl1bonus' => self::getLocalizedTRACurency(\Transaction::REASON_ADVERTISED_LVL1)
            ])
          : Localizer::get('mails.standardinvitation')),

        'member_type_name' => Localizer::get('common.member_types.'.$member->getType()),
        'member_type_bonus' => self::getLocalizedTRACurency($member->getTransactionReasonByType($member->getType()))
      ], false)
    );
  }


  /**
   * #7
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
        'video_link' => \Tbmt\RouterToMarketing::toVideo($referrer),
        'duedate' => \Tbmt\Localizer::dateLong($recruited->getFirstDueDate()),
        'signup_link' => \Tbmt\RouterToMarketing::toSignup($referrer)
      ], false)
    );
  }

  /**
   * #8
   * #9
   * #10
   * @param  \Member $member
   * @return [type]
   */
  static public function sendFeeIncomeReferrer(\Member $referrer, \Member $recruited) {
    $count = $referrer->getAdvertisedCount();
    if ( $count == 1 ) {
      return self::sendFirstFeeIncomeReferrer($referrer, $recruited);
    } else if ( $count == 2 ) {
      return self::sendSecondFeeIncomeReferrer($referrer, $recruited);
    }

    return self::sendPremiumFeeIncomeReferrer($referrer, $recruited);
  }

  /**
   * #8
   * @param  \Member $member
   * @return [type]
   */
  static private function sendFirstFeeIncomeReferrer(\Member $referrer, \Member $recruited) {
    $email = $referrer->getEmail();
    $locale = Localizer::get('mail.fee_income_referrer_first');

    $fullName = \Tbmt\view\Factory::buildMemberFullNameString($referrer);
    $recruited_fullname = \Tbmt\view\Factory::buildMemberFullNameString($recruited);

    if ( $referrer->getFundsLevel() == \Member::FUNDS_LEVEL2 )
      $provision = self::getLocalizedTRACurency(\Transaction::REASON_ADVERTISED_LVL2);
    else
      $provision = self::getLocalizedTRACurency(\Transaction::REASON_ADVERTISED_LVL1);

    $body = $locale['body'];

    return self::send(
      $email,
      $fullName,
      $locale['subject'],
      Localizer::insert($body, [
        'fullname' => $fullName,
        'recruited_fullname' => $recruited_fullname,
        'recruited_firstname' => $recruited->getFirstName(),
        'video_link' => \Tbmt\RouterToMarketing::toVideo($referrer),
        'provision_amount' => $provision,
        'adv2amount' => self::getLocalizedTRACurency(\Transaction::REASON_ADVERTISED_LVL2),
        'min_payout_amount' => self::getLocalizedAmount(Config::get('payout.execute.payouts.min.amount')),
        'paid_recommendation_count' => \Tbmt\Localizer::countInWords($referrer->getAdvertisedCount()),
        'profile_url' => \Tbmt\Router::toModule('account'),
      ], false)
    );
  }

  /**
   * #9
 *   fullname,
 *   adv2amount,
 *   adv1amount,
 *   advindirectamount,
 *   recruited_firstname,
 *   provision_amount,
 *   recruited_fullname,
 *   video_link,
 *   after6weeksamount,
 *   min_payout_amount
 *
   * @param  \Member $member
   * @return [type]
   */
  static private function sendSecondFeeIncomeReferrer(\Member $referrer, \Member $recruited) {
    $email = $referrer->getEmail();
    $locale = Localizer::get('mail.fee_income_referrer_second');

    $fullName = \Tbmt\view\Factory::buildMemberFullNameString($referrer);
    $recruited_fullname = \Tbmt\view\Factory::buildMemberFullNameString($recruited);

    if ( $referrer->getFundsLevel() == \Member::FUNDS_LEVEL2 )
      $provision = self::getLocalizedTRACurency(\Transaction::REASON_ADVERTISED_LVL2);
    else
      $provision = self::getLocalizedTRACurency(\Transaction::REASON_ADVERTISED_LVL1);

    $body = $locale['body'];

    return self::send(
      $email,
      $fullName,
      $locale['subject'],
      Localizer::insert($body, [
        'fullname' => $fullName,
        'recruited_fullname' => $recruited_fullname,
        'recruited_firstname' => $recruited->getFirstName(),
        'video_link' => \Tbmt\RouterToMarketing::toVideo($referrer),
        'provision_amount' => $provision,
        'adv2amount' => self::getLocalizedTRACurency(\Transaction::REASON_ADVERTISED_LVL2),
        'adv1amount' => self::getLocalizedTRACurency(\Transaction::REASON_ADVERTISED_LVL1),
        'advindirectamount' => self::getLocalizedTRACurency(\Transaction::REASON_ADVERTISED_INDIRECT),
        'after6weeksamount' => self::getLocalizedAmount(10000, 0),
        'min_payout_amount' => self::getLocalizedAmount(Config::get('payout.execute.payouts.min.amount')),

      ], false)
    );
  }

  /**
   * #10
 *   fullname,
 *   paid_recommendation_count,
 *   recruited_fullname,
 *   recruited_firstname,
 *   provision_amount,
 *   min_payout_amount
 *
   * @param  \Member $member
   * @return [type]
   */
  static private function sendPremiumFeeIncomeReferrer(\Member $referrer, \Member $recruited) {
    $email = $referrer->getEmail();
    $locale = Localizer::get('mail.fee_income_referrer_premium');

    $fullName = \Tbmt\view\Factory::buildMemberFullNameString($referrer);
    $recruited_fullname = \Tbmt\view\Factory::buildMemberFullNameString($recruited);

    if ( $referrer->getFundsLevel() == \Member::FUNDS_LEVEL2 )
      $provision = self::getLocalizedTRACurency(\Transaction::REASON_ADVERTISED_LVL2);
    else
      $provision = self::getLocalizedTRACurency(\Transaction::REASON_ADVERTISED_LVL1);

    $body = $locale['body'];

    return self::send(
      $email,
      $fullName,
      Localizer::insert($locale['subject'], ['paid_recommendation_count' => \Tbmt\Localizer::countInWords($referrer->getAdvertisedCount())]),
      Localizer::insert($body, [
        'fullname' => $fullName,
        'recruited_fullname' => $recruited_fullname,
        'recruited_firstname' => $recruited->getFirstName(),
        'video_link' => \Tbmt\RouterToMarketing::toVideo($referrer),
        'provision_amount' => $provision,
        'min_payout_amount' => self::getLocalizedAmount(Config::get('payout.execute.payouts.min.amount')),
        'paid_recommendation_count' => \Tbmt\Localizer::countInWords($referrer->getAdvertisedCount()),

      ], false)
    );
  }

  /**
   * #11
 *   fullname,
 *   hg_count,
 *   member_id,
 *
   * @param  \Member $member
   * @return [type]
   */
  static public function sendHgAvailable(\Member $member) {
    $email = $member->getEmail();
    $locale = Localizer::get('mail.hg_available');

    $fullName = \Tbmt\view\Factory::buildMemberFullNameString($member);
    $body = $locale['body'];

    return self::send(
      $email,
      $fullName,
      Localizer::insert($locale['subject'], ['hg_count' => $member->getHgWeek()]),
      Localizer::insert($body, [
        'fullname' => $fullName,
        'member_id' => $member->getNum(),
        'hg_count' => $member->getHgWeek()

      ], false)
    );
  }



/*****************************************************************
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
###################################################################
*******************************************************************/

  /**
   * @deprecated
   *
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
        'video_link' => \Tbmt\RouterToMarketing::toVideo($member),
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
        'video_link' => \Tbmt\RouterToMarketing::toVideo($member),
        'advindirectamount' => self::getLocalizedTRACurency(\Transaction::REASON_ADVERTISED_INDIRECT)
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


  static public function sendContactFormMail($mailRecipient, $fromMail, $fromPhone, $fromName, $subject, $body) {
    $body = "From mail: $fromMail\n\r".
      "From phone: $fromPhone\n\r".
      "From name: $fromName\n\r\n\r".
      "Body:\n\r$body\n\r";

    return self::send(
      $mailRecipient,
      null,
      ' - Contact Form - '.$subject,
      $body,
      $fromMail,
      $fromName
    );
  }

  static public function sendPublicPasswordResetLink(\Member $member) {
    $email = $member->getEmail();
    $locale = Localizer::get('mail.password_reset');

    $num = $member->getNum();
    $now = time();
    $email = $member->getEmail();

    $href = RouterToMarketing::toModule('manage', 'do_reset_password', [
      '3591f374b308cb3932260b45d5709a4c' => 'true',
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

  static public function sendPasswordResetLink(\Member $member) {
    $email = $member->getEmail();
    $locale = Localizer::get('mail.password_reset');

    $num = $member->getNum();
    $now = time();
    $email = $member->getEmail();

    $href = RouterToMarketing::toModule('manage', 'do_reset_password', [
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

    $debugLevel = Config::get('mail.debug_level');
    if ( $debugLevel != '' )
      $mail->SMTPDebug = $debugLevel;

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

    $body .= "\n\r<br>".Config::get('mail.signature')."\n";

    $htmlBody = (new \Parsedown())->text($body);

    $mail->setFrom($fromMail, $fromName);
    $mail->addReplyTo(Config::get('mail.reply_mail'), 'Do not Reply');
    $mail->addAddress($address, $name);

    $mail->Subject = Config::get('mail.subject_prefix').' '.$subject;
    $mail->Body = self::bodyToHtml($htmlBody);
    $mail->AltBody = $body;

    if ( self::$DEBUG_PRINT === true )
      return [$address, $name, $mail->Subject, $mail->Body, $mail->AltBody];

    $boolResult = $mail->send();
    if(!$boolResult)
      throw new Exception('Mailer Error: '.$mail->ErrorInfo);

    return $boolResult;
  }

  static private function bodyToHtml($body) {
    // $body = str_replace("\n\r", "<br>", $body);
    // $body = str_replace("\n", "<br>", $body);

    $homeUrl = RouterToMarketing::toBase();

    return <<<EOL
<html>
<head>
  <style type="text/css">
    * {
      color: inherit;
      font-family: inherit;
      margin: 0;
      padding: 0;
    }
    #body {
      background-color: #ededed;
      width:100%;
      min-height: 500px;
      color: #666;
      font-family: 'Open Sans', Helvetica, Arial;
    }
    #content {
      background-color: white;
      box-shadow: 0px 0px 4px rgba(0,0,0,.3);
    }
    p, ol {
      margin-bottom: .8em;
    }
    a {
      color: blue;
    }

    ol {
      padding-left: 40px;
    }
  </style>
</head>
<body>

  <table id="body">
    <tr><td>&nbsp;</td></tr>
    <tr><td align="center" style="width:100%;">

  <table id="content" style="max-width: 700px;">
    <tr><td style=" padding: 15px;">
                <a href="$homeUrl" style="color:#7E7E7E; text-decoration: none; font-size: 26px; font-family: Courier New">
                    <svg style="vertical-align:middle;" class="headlogo" xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="180 95 123 75" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="none">
  <g id="surface2" transform="matrix(0.7235366885919756,0,0,0.7235366885919756,68.07473142651779,49.96389087769876)">
    <path style="stroke:none;fill-rule:evenodd;fill:rgb(14.901961%,16.078431%,31.372549%);fill-opacity:1" d="M 288.949219 117.199219 C 287.617188 121.066406 285.832031 124.351562 283.601562 127.050781 C 279.667969 131.750000 274.898438 135.464844 269.300781 138.199219 C 263.699219 140.933594 257.066406 142.765625 249.398438 143.699219 L 245.898438 144.148438 L 248.851562 146.101562 C 253.785156 149.398438 258.566406 151.867188 263.199219 153.500000 C 267.832031 155.132812 272.382812 155.949219 276.851562 155.949219 C 279.082031 155.949219 281.351562 155.750000 283.648438 155.351562 C 287.015625 154.714844 290.183594 153.433594 293.148438 151.500000 C 296.148438 149.566406 299.000000 147.250000 301.699219 144.550781 C 304.398438 141.882812 307.035156 139.101562 309.601562 136.199219 L 309.750000 136.050781 C 311.683594 133.917969 313.566406 131.898438 315.398438 130.000000 L 316.699219 128.699219 L 315.050781 127.898438 C 312.785156 126.800781 310.464844 125.617188 308.101562 124.351562 L 307.898438 124.250000 C 305.066406 122.750000 302.199219 121.300781 299.300781 119.898438 C 296.398438 118.500000 293.500000 117.332031 290.601562 116.398438 L 289.351562 116.000000 L 288.949219 117.199219 "></path>
    <path style="stroke:none;fill-rule:evenodd;fill:rgb(14.117647%,25.882353%,39.607843%);fill-opacity:1" d="M 282.000000 115.550781 C 280.632812 115.550781 279.316406 115.667969 278.050781 115.898438 C 276.117188 116.199219 274.000000 116.714844 271.699219 117.449219 C 269.398438 118.214844 266.949219 119.367188 264.351562 120.898438 C 261.785156 122.433594 259.101562 124.550781 256.300781 127.250000 C 253.167969 130.250000 250.101562 133.933594 247.101562 138.300781 L 245.500000 140.648438 L 248.699219 140.300781 C 256.199219 139.535156 262.632812 137.898438 268.000000 135.398438 C 273.398438 132.898438 277.949219 129.433594 281.648438 125.000000 C 283.382812 122.933594 284.800781 120.417969 285.898438 117.449219 L 286.500000 115.851562 L 284.800781 115.699219 C 283.832031 115.601562 282.898438 115.550781 282.000000 115.550781 "></path>
    <path style="stroke:none;fill-rule:evenodd;fill:rgb(14.117647%,55.294118%,70.588235%);fill-opacity:1" d="M 287.699219 83.648438 C 285.265625 84.25 282.75 84.832031 280.148438 85.398438 L 279.898438 85.449219 C 277.101562 86.050781 274.117188 86.75 270.949219 87.550781 C 267.785156 88.351562 264.714844 89.332031 261.75 90.5 L 260.601562 91 L 261.050781 92.199219 C 262.382812 95.832031 263.050781 99.351562 263.050781 102.75 C 263.050781 106.785156 262.550781 110.683594 261.550781 114.449219 L 260.75 117.398438 L 263.449219 115.949219 C 267.550781 113.714844 272.035156 112.167969 276.898438 111.300781 C 278.398438 111.066406 279.964844 110.949219 281.601562 110.949219 C 283 110.917969 284.464844 111.015625 286 111.25 L 287.25 111.398438 L 287.5 110.148438 C 287.964844 107.449219 288.285156 104.699219 288.449219 101.898438 C 288.648438 99.066406 288.800781 96.265625 288.898438 93.5 C 289 90.632812 289.148438 87.800781 289.351562 85 L 289.5 83.199219 L 287.699219 83.648438 "></path>
    <path style="stroke:none;fill-rule:evenodd;fill:rgb(14.509804%,47.450980%,63.529412%);fill-opacity:1" d="M 257.648438 95.050781 L 256.250000 96.000000 C 254.082031 97.433594 252.332031 98.949219 251.000000 100.550781 C 249.601562 102.183594 248.214844 104.148438 246.851562 106.449219 C 245.484375 108.714844 244.300781 111.484375 243.300781 114.750000 C 242.332031 118.015625 241.699219 121.898438 241.398438 126.398438 C 241.332031 127.300781 241.316406 128.449219 241.351562 129.851562 C 241.382812 131.285156 241.464844 132.851562 241.601562 134.550781 C 241.734375 136.250000 241.933594 137.984375 242.199219 139.750000 L 243.949219 138.000000 C 247.617188 134.199219 250.617188 130.449219 252.949219 126.750000 C 255.183594 123.214844 256.867188 119.617188 258.000000 115.949219 C 259.101562 112.316406 259.648438 108.582031 259.648438 104.750000 C 259.648438 102.183594 259.167969 99.500000 258.199219 96.699219 L 257.648438 95.050781 "></path>
    <path style="stroke:none;fill-rule:evenodd;fill:rgb(21.568627%,68.627451%,85.098039%);fill-opacity:1" d="M 238.949219 70.500000 C 238.183594 71.566406 237.382812 72.632812 236.550781 73.699219 C 235.714844 74.800781 234.816406 75.984375 233.851562 77.250000 C 232.082031 79.515625 230.351562 81.816406 228.648438 84.148438 C 226.949219 86.484375 225.398438 88.867188 224.000000 91.300781 L 223.398438 92.398438 L 224.449219 93.050781 C 227.316406 94.816406 229.683594 96.832031 231.550781 99.101562 C 234.648438 102.765625 237.050781 106.683594 238.750000 110.851562 L 239.949219 113.851562 L 241.199219 110.851562 C 242.898438 106.683594 245.285156 102.765625 248.351562 99.101562 C 250.250000 96.832031 252.632812 94.800781 255.500000 93.000000 L 256.550781 92.351562 L 255.949219 91.250000 C 254.550781 88.785156 252.984375 86.351562 251.250000 83.949219 C 249.515625 81.550781 247.734375 79.199219 245.898438 76.898438 C 244.167969 74.632812 242.566406 72.484375 241.101562 70.449219 L 240.000000 69.000000 L 238.949219 70.500000 "></path>
    <path style="stroke:none;fill-rule:evenodd;fill:rgb(14.509804%,47.450980%,63.529412%);fill-opacity:1" d="M 221.800781 96.699219 C 220.832031 99.500000 220.351562 102.183594 220.351562 104.750000 C 220.351562 108.582031 220.898438 112.316406 222.000000 115.949219 C 223.101562 119.617188 224.765625 123.214844 227.000000 126.750000 C 229.332031 130.449219 232.351562 134.199219 236.050781 138.000000 L 237.750000 139.750000 C 238.015625 137.984375 238.214844 136.250000 238.351562 134.550781 C 238.515625 132.851562 238.601562 131.285156 238.601562 129.851562 C 238.632812 128.449219 238.632812 127.300781 238.601562 126.398438 C 238.300781 121.898438 237.648438 118.015625 236.648438 114.750000 C 235.683594 111.515625 234.515625 108.750000 233.148438 106.449219 C 231.785156 104.148438 230.398438 102.183594 229.000000 100.550781 C 227.632812 98.949219 225.882812 97.433594 223.750000 96.000000 L 222.351562 95.050781 L 221.800781 96.699219 "></path>
    <path style="stroke:none;fill-rule:evenodd;fill:rgb(14.117647%,55.294118%,70.588235%);fill-opacity:1" d="M 198.398438 112.851562 C 200.000000 112.882812 201.550781 113.015625 203.050781 113.250000 C 207.949219 114.117188 212.484375 115.683594 216.648438 117.949219 L 219.351562 119.449219 L 218.550781 116.449219 C 217.449219 112.617188 216.898438 108.699219 216.898438 104.699219 C 216.898438 101.300781 217.582031 97.785156 218.949219 94.148438 L 219.398438 92.949219 L 218.199219 92.449219 C 215.265625 91.285156 212.214844 90.300781 209.050781 89.500000 C 205.882812 88.699219 202.898438 88.000000 200.101562 87.398438 L 199.851562 87.351562 C 197.214844 86.785156 194.683594 86.199219 192.250000 85.601562 L 190.500000 85.148438 L 190.648438 86.949219 C 190.851562 89.750000 191.000000 92.582031 191.101562 95.449219 C 191.199219 98.250000 191.332031 101.050781 191.500000 103.851562 C 191.699219 106.683594 192.035156 109.433594 192.500000 112.101562 L 192.750000 113.351562 L 193.949219 113.199219 C 195.515625 112.964844 197.000000 112.851562 198.398438 112.851562 "></path>
    <path style="stroke:none;fill-rule:evenodd;fill:rgb(14.117647%,25.882353%,39.607843%);fill-opacity:1" d="M 197.949219 115.550781 C 197.050781 115.550781 196.117188 115.601562 195.148438 115.699219 L 193.449219 115.851562 L 194.050781 117.449219 C 195.148438 120.417969 196.566406 122.933594 198.300781 125.000000 C 202.000000 129.433594 206.566406 132.898438 212.000000 135.398438 C 217.398438 137.933594 223.867188 139.582031 231.398438 140.351562 L 234.199219 140.648438 L 232.851562 138.300781 C 230.015625 134.234375 227.132812 130.714844 224.199219 127.750000 C 221.332031 124.917969 218.566406 122.699219 215.898438 121.101562 C 213.234375 119.500000 210.734375 118.300781 208.398438 117.500000 C 206.035156 116.734375 203.867188 116.199219 201.898438 115.898438 C 200.632812 115.667969 199.316406 115.550781 197.949219 115.550781 "></path>
    <path style="stroke:none;fill-rule:evenodd;fill:rgb(14.901961%,16.078431%,31.372549%);fill-opacity:1" d="M 189.351562 116.398438 C 186.449219 117.332031 183.550781 118.500000 180.648438 119.898438 C 177.750000 121.300781 174.882812 122.750000 172.050781 124.250000 L 171.851562 124.351562 C 169.449219 125.617188 167.132812 126.800781 164.898438 127.898438 L 163.250000 128.699219 L 164.500000 130.000000 C 166.367188 131.898438 168.250000 133.917969 170.148438 136.050781 L 170.300781 136.199219 C 172.898438 139.101562 175.550781 141.882812 178.250000 144.550781 C 180.949219 147.250000 183.800781 149.566406 186.800781 151.500000 C 189.765625 153.433594 192.933594 154.714844 196.300781 155.351562 C 198.566406 155.750000 200.832031 155.949219 203.101562 155.949219 C 207.566406 155.949219 212.117188 155.132812 216.750000 153.500000 C 221.382812 151.867188 226.167969 149.398438 231.101562 146.101562 L 234.050781 144.148438 L 230.550781 143.699219 C 222.882812 142.765625 216.250000 140.933594 210.648438 138.199219 C 205.050781 135.464844 200.285156 131.750000 196.351562 127.050781 C 194.082031 124.351562 192.300781 121.066406 191.000000 117.199219 L 190.601562 116.000000 L 189.351562 116.398438 "></path>
  </g>
</svg>                    <span style="vertical-align:middle;">Betterliving for everyone Ltd.</span>

                </a>
    </td></tr>
    <tr>
      <td style=" padding: 15px;">
        $body
      </td>
    </tr>
  </table>



      </td>
    </tr>
    <tr><td>&nbsp;</td></tr>
  </table>
</body>
</html>


EOL;
    return $body;
  }
}

?>