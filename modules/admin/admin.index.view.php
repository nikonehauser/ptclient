<?php

namespace Tbmt\view;

class AdminIndex extends Base {


  static public function initBonusLevelForm(array $data = array()) {
    return \Tbmt\Arr::initMulti($data, self::$BONUS_LEVEL_FORM_FIELDS);
  }

  public function render(array $params = array()) {
    $this->formVal = isset($params['formVal']) ? $params['formVal'] : [];
    $this->formErrors = isset($params['formErrors']) ? $params['formErrors'] : [];

    $this->successmsg = false;
    $unpaidMembers = [];

    if ( !empty($params['setPaidMember']) ) {
      $members = [$params['setPaidMember']];
      $this->successmsg = true;

    } else if ( !empty($this->formVal['recipient_num']) ) {
      $members = \MemberQuery::create()
        ->filterByNum(\Tbmt\Val::init($this->formVal['recipient_num'], \Tbmt\TYPE_INT), \Criteria::EQUAL)
        ->find();

    } else {
      $members = \MemberQuery::create()
        ->filterByPaidDate(null, \Criteria::ISNULL)
        ->orderBy(\MemberPeer::SIGNUP_DATE, \Criteria::ASC)
        ->limit(100)
        ->find();
    }

    foreach ($members as $member) {
      $paidUrl = \Tbmt\Router::toModule('admin', 'index', ['set_paid_num' => $member->getNum()]);

      $unpaidMembers[] = [
        \Tbmt\view\Factory::buildMemberFullNameString($member),
        $member->getNum(),
        $member->isMarkedAsPaid() ? '<i class="fa fa-check"></i> '.\Tbmt\Localizer::dateLong($member->getPaidDate()) : '',
        \Tbmt\Localizer::dateLong($member->getSignupDate()),

        $member->isMarkedAsPaid() ? '' : Factory::buildButton('Set paid', $paidUrl, '', 'cart', 'onclick="return confirm(\'Really mark paid?\');"')
      ];
    }

    $this->unpaidMembers = $unpaidMembers;

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'index.admin.html',
      $params
    );
  }

}