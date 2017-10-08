<?php

namespace Tbmt;

class MemberStrategy {
  static public function get($extended) {
    return new ExtendedMemberStrategy();

    // deprecated
    // return $extended === true
    //   ? new ExtendedMemberStrategy()
    //   : new SimpleMemberStrategy();
  }
}

/**
  *
  * extended strategy
  *
  ************************************
 */
class ExtendedMemberStrategy extends MemberStrategy {

  public $SIGNUP_FORM_FIELDS = [
    'referral_member_num'  => \Tbmt\TYPE_STRING,
    'title'                => \Tbmt\TYPE_STRING,
    'invitation_code'      => \Tbmt\TYPE_STRING,
    'lastName'             => \Tbmt\TYPE_STRING,
    'firstName'            => \Tbmt\TYPE_STRING,
    'age'                  => \Tbmt\TYPE_STRING,
    'phone'                => \Tbmt\TYPE_STRING,
    'email'                => \Tbmt\TYPE_STRING,
    'city'                 => \Tbmt\TYPE_STRING,
    'street'               => \Tbmt\TYPE_STRING,
    'street_add'           => \Tbmt\TYPE_STRING,
    'zip_code'             => \Tbmt\TYPE_STRING,
    'country'              => [\Tbmt\TYPE_STRING, 'India'],
    'bank_recipient'       => \Tbmt\TYPE_STRING,
    'iban'                 => \Tbmt\TYPE_STRING,
    'bic'                  => \Tbmt\TYPE_STRING,
    'accept_agbs'          => \Tbmt\TYPE_STRING,
    'accept_valid_country' => \Tbmt\TYPE_STRING,
    'password'             => \Tbmt\TYPE_STRING,
    'password2'            => \Tbmt\TYPE_STRING,
    'bank_name'            => \Tbmt\TYPE_STRING,
    'bank_zip_code'        => \Tbmt\TYPE_STRING,
    'bank_city'            => \Tbmt\TYPE_STRING,
    'bank_street'          => \Tbmt\TYPE_STRING,
    'bank_country'         => \Tbmt\TYPE_STRING,
    'correct_bank'         => \Tbmt\TYPE_STRING,
  ];

  public $SIGNUP_FORM_FILTERS = [
    'referral_member_num'  => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'email'                => \FILTER_VALIDATE_EMAIL,
    'lastName'             => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'firstName'            => \Tbmt\Validator::FILTER_NOT_EMPTY,
    // 'phone'                => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'age'                  => [
      'filter' => \FILTER_VALIDATE_INT,
      'options' => [
        'min_range' => 18,
        'max_range' => 110
      ],
      'errorLabel' => 'error.age_of_18'
    ],
    'city'                 => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'street'               => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'zip_code'             => \Tbmt\Validator::FILTER_INDIA_PINCODE,
    // 'country'           => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'bank_recipient'       => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'iban'                 => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'bic'                  => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'bank_name'            => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'bank_zip_code'        => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'bank_city'            => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'bank_street'          => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'bank_country'         => \Tbmt\Validator::FILTER_NOT_EMPTY,
    'accept_agbs'          => \FILTER_VALIDATE_BOOLEAN,
    'accept_valid_country' => \FILTER_VALIDATE_BOOLEAN,
    'password'             => \Tbmt\Validator::FILTER_PASSWORD,
    'correct_bank'         => \FILTER_VALIDATE_BOOLEAN,
  ];

  public function initSignupForm(array $data = array()) {
    return \Tbmt\Arr::initMulti($data, $this->SIGNUP_FORM_FIELDS);
  }

  public function getValidReferrerByHash($hash) {
    $member = \MemberQuery::create()
      ->filterByDeletionDate(null, \Criteria::ISNULL)
      ->filterByType(\Member::TYPE_SYSTEM, \Criteria::NOT_EQUAL)
      ->filterByHash($hash)
      ->findOneByIsExtended(1);

    if ( $member && $member->getNum() != 0 )
      return $member;

    return null;
  }

  public function validateSignupForm(array $data = array()) {
    $data = $this->initSignupForm($data);

    if ( $data['password'] !== $data['password2'] )
      return [false, ['password' => \Tbmt\Localizer::get('error.password_unequal')], null, null];

    $res = \Tbmt\Validator::getErrors($data, $this->SIGNUP_FORM_FILTERS);
    if ( $res !== false )
      return [false, $res, null, null];

    // Validate member number exists
    $parentMember = $this->getValidReferrerByHash($data['referral_member_num']);
    if ( !$parentMember ) {
      return [false, ['referral_member_num' => \Tbmt\Localizer::get('error.referral_member_num')], null, null];

    }

    // Validate member email does not exist
    $emailExistsMember = \MemberQuery::create()
      ->filterByDeletionDate(null, \Criteria::ISNULL)
      ->findOneByEmail($data['email']);
    if ( $emailExistsMember ) {
      return [false, ['email' => \Tbmt\Localizer::get('error.email_exists')], null, null];
    }

    // else if ( $parentMember->hadPaid() ) {
    //   return [false, ['referral_member_num' => \Tbmt\Localizer::get('error.referrer_paiment_outstanding')], null];
    // }

    $invitation = null;
    if ( $data['invitation_code'] !== '' ) {
      $invitation = \InvitationQuery::create()->findOneByHash($data['invitation_code']);
      if ( $parentMember == null )
        return [false, ['invitation_code' => \Tbmt\Localizer::get('error.invitation_code_inexisting')], null, null];

      if ( $invitation->getMemberId() != $parentMember->getId() )
        return [false, ['invitation_code' => \Tbmt\Localizer::get('error.invitation_code_invalid')], null, null];

      if ( $invitation->getAcceptedMemberId() )
        return [false, ['invitation_code' => \Tbmt\Localizer::get('error.invitation_code_used')], null, null];
    }

    if ( !isset($data['email']) )
      $data['email'] = '';

    return [true, $data, $parentMember, $invitation];
  }

  public function createFromSignup($data, $referrerMember, \Invitation $invitation = null, \PropelPDO $con) {
    // This functions expects this parameter to be valid!
    // E.g. the result from $this->validateSignupForm()

    $now = time();

    if ( !$con->beginTransaction() )
      throw new \Exception('Could not begin transaction');

    try {
      $member = new \Member();
      $member
        ->setFirstName($data['firstName'])
        ->setLastName($data['lastName'])
        ->setEmail($data['email'])
        ->setTitle($data['title'])
        ->setCity($data['city'])
        ->setStreet($data['street'])
        ->setStreetAdd($data['street_add'])
        ->setZipCode($data['zip_code'])
        ->setCountry('India')
        ->setAge($data['age'])
        ->setPhone($data['phone'])

        ->setBankName($data['bank_name'])
        ->setBankStreet($data['bank_street'])
        ->setBankCity($data['bank_city'])
        ->setBankZipCode($data['bank_zip_code'])
        ->setBankCountry($data['bank_country'])

        // ->setReferrerNum($data['referral_member_num'])
        ->setBankRecipient($data['bank_recipient'])
        ->setIban($data['iban'])
        ->setBic($data['bic'])
        ->setPassword($data['password'])
        ->setSignupDate($now)
        ->setBonusIds('{}')
        ->setPaidDate(null)
        ->setIsExtended(1)
        ->setHash(\Member::calcHash($member));

      // $con->query('SELECT id FROM tbmt_member WHERE id = '.$member->getId().' FOR UPDATE;')->fetchAll();
      // $con->query('SELECT id FROM tbmt_member WHERE id = '.$referrerMember->getId().' FOR UPDATE;')->fetchAll();

      $wasFreeInvitation = false;

      if ( $invitation ) {
        $invitationType = $invitation->getType();
        $member->setType($invitationType);

        // Special case if e.g. director invites another director.
        // The referrer of the referrer will be the referrer.
        // This is necessary because the same type can not be on same line vertical
        // but horizontal. E.g. Director can have more marketing leader under him
        // but only Directors next to him (NOT under him)
        if ( $invitationType > \Member::TYPE_MEMBER && $referrerMember->getType() == $invitationType )
          $referrerMember = $referrerMember->getReferrerMember();

        $invitation->setAcceptedDate($now);

        if ( $invitation->getFreeSignup() ) {
          $member->setFreeInvitation(1);
          $wasFreeInvitation = true;
        }

        // Deprecated code
        // if ( $invitation->getType() === \Member::TYPE_SUB_PROMOTER ) {
        //   $member->setSubPromoterReferral($invitation->getMeta()['promoter_id']);
        // }
      }

      $member->setReferrerMember($referrerMember, $con);
      $member->save($con);
      $member->reload(false, $con);

      if ( $invitation ) {
        $member->save($con);
        $invitation->setAcceptedMemberId($member->getId());
        $invitation->save($con);

        if ( $wasFreeInvitation )
          $member->onReceivedMemberFee(\Transaction::$BASE_CURRENCY, $now, true, $con);
      }

      if ( $invitation && $invitation->getLvl2Signup() ) {
        \Tbmt\DistributionStrategy::getInstance()->raiseFundsLevel($member);
      }

      $referrerMember->save($con);
      $member->save($con);

      \Tbmt\MailHelper::sendNewRecruitmentCongrats($referrerMember, $member);

      if ( !$invitation || !$wasFreeInvitation )
        \Tbmt\MailHelper::sendSignupConfirm($member);
      else
        \Tbmt\MailHelper::sendInvitationFeeIncome($member, $wasFreeInvitation);

      if ( !$con->commit() )
        throw new \Exception('Could not commit transaction');

      return $member;

    } catch (\Exception $e) {
        $con->rollBack();
        throw $e;
    }
  }


}
