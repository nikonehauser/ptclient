<?php

namespace Tbmt\view;

class MemberSignup extends Base {

  public function render(array $params = array()) {
    $this->formLabels = $this->i18nView['form_labels'];

    $data = isset($params['formVal']) ? $params['formVal'] : $_REQUEST;

    if ( DEVELOPER_MODE === true ) {
      $data = array_merge([
        // 'referral_member_num'  => '102',
        'title'                => '',
        'invitation_code'      => '',
        'lastName'             => 'Member ',
        'firstName'            => 'Member ',
        'age'                  => '25',
        'email'                => '',
        'city'                 => 'Test',
        'zip_code'             => '504299',
        'country'              => 'India',
        'street'               => 'India',
        'street_add'           => '',
        'bank_recipient'       => 'Test',
        'iban'                 => 'Test',
        'bic'                  => 'Test',
        'bank_name'            => 'Test',
        'bank_zip_code'        => 'Test',
        'bank_city'            => 'Test',
        'bank_street'          => 'Test',
        'bank_country'         => 'Test',
        'correct_bank'         => '1',
        'accept_agbs'          => '1',
        'accept_valid_country' => '1',
        'password'             => 'demo1234',
        'password2'            => 'demo1234',
      ], $data);
    }

    $data['referral_member_num'] = \Tbmt\Session::hasValidToken();
    $this->formVal = \Member::initSignupForm($data);
    $this->referrerMember = \Member::getValidReferrerByHash($data['referral_member_num']);

    $this->referralNumDisabled = true;

    $this->formErrors = isset($params['formErrors']) ? $params['formErrors'] : [];

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'signup.member.html',
      $params
    );
  }

}