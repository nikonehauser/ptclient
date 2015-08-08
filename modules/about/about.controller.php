<?php

namespace Tbmt;

class AboutController extends BaseController {

  const MODULE_NAME = 'about';

  static private $CONTACT_FORM_FIELDS = [
    'name'    => \Tbmt\TYPE_STRING,
    'email'   => \Tbmt\TYPE_STRING,
    'subject' => \Tbmt\TYPE_STRING,
    'message' => \Tbmt\TYPE_STRING,
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
    'faq' => true,
    'contact_submit' => true
  ];

  public function action_contact_submit() {
    list($valid, $data) = self::validateContactForm($_REQUEST);
    if ( $valid !== true ) {
      return ControllerDispatcher::renderModuleView(
        self::MODULE_NAME,
        'index',
        ['formErrors' => $data]
      );
    }

    MailHelper::sendContactFormMail(
      $data['email'],
      $data['name'],
      $data['subject'],
      $data['message']
    );

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['successmsg' => true, 'formVal' => []]
    );
  }
}

?>
