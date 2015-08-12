<?php

namespace Tbmt\view;

class AccountInvitationTab extends Base {

  protected $varsDef = [
  ];

  protected function init() {
    $this->i18nView = \Tbmt\Localizer::get('view.account.tabs.invitation');
    $this->i18nCommon = \Tbmt\Localizer::plain('common');
  }

  public function render(array $params = array()) {
    if ( !isset($params['member']) && !($params['member'] instanceof \Member) )
      throw new \Exception('Invalid param member for account index view.');

    $this->member = $params['member'];
    $this->recipient = isset($params['recipient']) ? $params['recipient'] : null;

    $this->formVal = \Invitation::initInvitationForm(
      isset($params['formVal']) ? $params['formVal'] : $_REQUEST
    );

    $this->formErrors = isset($params['formErrors']) ? $params['formErrors'] : [];
    $this->successmsg = isset($params['successmsg']) ? true : false;

    $this->invitations = \InvitationQuery::create()
      ->filterByMemberId($this->member->getId())
      ->orderBy('AcceptedDate', \Criteria::DESC)
      ->orderBy('CreationDate', \Criteria::DESC)
      ->limit(100)
      ->find();

    $this->invitationDateFormat = \Tbmt\Localizer::get('datetime_format_php.long');

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'tab.invitation.account.html',
      $params
    );
  }

}