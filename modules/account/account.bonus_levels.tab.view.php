<?php

namespace Tbmt\view;

class AccountBonus_levelsTab extends Base {

  protected $varsDef = [
  ];

  protected function init() {
    $this->i18nView = \Tbmt\Localizer::get('view.account.tabs.bonus_levels');
    $this->i18nCommon = \Tbmt\Localizer::plain('common');
  }

  public function render(array $params = array()) {
    if ( !isset($params['member']) && !($params['member'] instanceof \Member) )
      throw new \Exception('Invalid param member for account index view.');

    $this->member = $params['member'];
    $this->recipient = isset($params['recipient']) ? $params['recipient'] : null;

    $this->formVal = \Member::initBonusLevelForm(
      isset($params['formVal']) ? $params['formVal'] : $_REQUEST
    );

    $this->formErrors = isset($params['formErrors']) ? $params['formErrors'] : [];
    $this->successmsg = isset($params['successmsg']) ? true : false;

    // $query = \MemberQuery::create()
    //   // ->filterByBonusLevel(0, \Criteria::GREATER_THAN)
    //   ->joinActivity()
    //   ->select([
    //       'BonusLevel',
    //       'Activity.Meta',
    //       'Activity.Date'
    //     ])
    //   ->where('Activity.MemberId = ?', $this->member->getId())
    //   ->orderBy('Activity.Date', \Criteria::DESC)
    //   ->limit(100);


    $objBonusMembers = \ActivityQuery::create()
      ->filterByType(\Activity::ACT_ACCOUNT_BONUS_LEVEL)
      ->filterByMemberId($this->member->getId())
      ->orderBy(\ActivityPeer::DATE, \Criteria::DESC)
      ->find();

    $arrBonusMembers = [];
    $currencySymbol = \Tbmt\Localizer::get('currency_symbol.'.\Transaction::$BASE_CURRENCY);
    $dateFormat = \Tbmt\Localizer::get('datetime_format_php.long');
    foreach ( $objBonusMembers as $bonusMembersActivity ) {
      $meta = $bonusMembersActivity->getMeta();
      $amount = isset($meta[\Activity::MK_BONUS_PAYMENT_AMOUNT]) ?
        \Tbmt\Localizer::currencyFormat($meta[\Activity::MK_BONUS_PAYMENT_AMOUNT], $currencySymbol) :
        ' - ';

      $memberNum = isset($meta[\Activity::MK_BONUS_PAYMENT_CUSTOMER]) ?
        $meta[\Activity::MK_BONUS_PAYMENT_CUSTOMER] :
        ' - ';

      $arrBonusMembers[] = [
        $memberNum,
        $amount,
        date($dateFormat, $bonusMembersActivity->getDate())
      ];
    }

    $this->bonusMembers = $arrBonusMembers;

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'tab.bonus_levels.account.html',
      $params
    );
  }

}