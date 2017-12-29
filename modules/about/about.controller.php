<?php

namespace Tbmt;

class AboutController extends BaseController {

  const MODULE_NAME = 'about';

  static private $CONTACT_FORM_FIELDS = [
    'name'    => \Tbmt\TYPE_STRING,
    'email'   => \Tbmt\TYPE_STRING,
    'subject' => \Tbmt\TYPE_STRING,
    'message' => \Tbmt\TYPE_STRING,
    'phone' => \Tbmt\TYPE_STRING,
  ];

  static private $CONTACT_FORM_FILTERS = [
    'name'    => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'message' => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'subject' => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'email'   => \FILTER_VALIDATE_EMAIL,
  ];

  static public function initContactForm(array $data = array()) {
    return \Tbmt\Arr::initMulti($data, self::$CONTACT_FORM_FIELDS);
  }

  static public function validateContactForm(array $data = array())  {
    $data = self::initContactForm($data);

    $res = \Tbmt\Validator::getErrors($data, self::$CONTACT_FORM_FILTERS);
    if ( $res !== false )
      return [false, $res];

    return [true, $data];
  }

  protected $actions = [
    'index' => true,
    'contact' => true,
    'faq' => true,
    'terms' => true,
    'impressum' => true,
    'contact_submit' => true
  ];

  public function action_contact_submit() {
    list($valid, $data) = self::validateContactForm($_REQUEST);
    if ( $valid !== true ) {
      return ControllerDispatcher::renderModuleView(
        self::MODULE_NAME,
        'contact',
        ['formErrors' => $data]
      );
    }

    $fullName = $data['name'];

    $recipient = null;
    $login = Session::getLogin();
    if ( $login ) {
      $fullName = \Tbmt\view\Factory::buildMemberFullNameString($login);
      $referrer = $login->getMemberRelatedByReferrerId();
      if ( $referrer ) {
        $recipient = $referrer->getEmail();
      }
    } else {
      $token = Session::hasValidToken();
      if ( !empty($token) ) {
        $referrer = \Member::getValidReferrerByHash($token);
        if ( $referrer ) {
          $recipient = $referrer->getEmail();
        }
      }
    }

    if ( !$recipient )
      $recipient = Config::get('contact_mail_recipient');

    $bodyPrefix = "Betterliving member \"$fullName\" has a question:\n\r\n\r";

    MailHelper::sendContactFormMail(
      $recipient,
      $data['email'],
      $data['phone'],
      $data['name'],
      $data['subject'],
      $bodyPrefix.$data['message']
    );

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'contact',
      ['successmsg' => true, 'formVal' => []]
    );
  }
}

?>
