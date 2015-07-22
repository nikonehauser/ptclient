<?php

namespace Tbmt\view;

class AccountInvitationTab extends Base {

  protected $varsDef = [
  ];

  protected function init() {
    $this->i18nView = \Tbmt\Localizer::get('view.account.tabs.invitation');
  }

  public function render(array $params = array()) {
    if ( !isset($params['member']) && !($params['member'] instanceof \Member) )
      throw new \Exception('Invalid param member for account index view.');

    $this->member = $params['member'];

    $this->formVal = \Invitation::initInvitationForm(
      isset($params['formVal']) ? $params['formVal'] : $_REQUEST
    );

    $this->formErrors = isset($params['formErrors']) ? $params['formErrors'] : [];

    $this->invitations = \InvitationQuery::create()
      ->filterByMemberId($this->member->getId())
      ->find();

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'tab.invitation.account.html',
      $params
    );
  }

}