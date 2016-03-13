<?php

namespace Tbmt\view;

class MemberSignup extends Base {

  public function render(array $params = array()) {
    $this->formLabels = $this->i18nView['form_labels'];

    $data = isset($params['formVal']) ? $params['formVal'] : $_REQUEST;

    if ( DEVELOPER_MODE === true && !isset($data['referral_member_num']) ) {
      $data = array_merge([
        'referral_member_num'  => '102',
        'title'                => '',
        'invitation_code'      => '',
        'lastName'             => 'Spender ',
        'firstName'            => 'Spender ',
        'age'                  => '25',
        'email'                => 'test1@gmx.de',
        'city'                 => 'Test',
        'zip_code'             => '504299',
        'country'              => 'India',
        'bank_recipient'       => 'Test',
        'iban'                 => 'Test',
        'bic'                  => 'Test',
        'accept_agbs'          => '1',
        'accept_valid_country' => '1',
        'password'             => 'demo1234',
        'password2'            => 'demo1234',
      ], $data);
    }

    $this->formVal = \Member::initSignupForm($data);

    $this->formErrors = isset($params['formErrors']) ? $params['formErrors'] : [];

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'signup.member.html',
      $params
    );
  }

}