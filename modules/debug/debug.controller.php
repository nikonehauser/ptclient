<?php

namespace Tbmt;

class DebugController extends BaseController {

  const MODULE_NAME = 'debug';

  protected $actions = [
    'index' => true,
    'allinvoices' => true,
    'activities' => true,
    'printmail' => true,
    'loadtest' => true,
  ];

  public function dispatchAction($action, $params) {
    if ( !\Tbmt\Config::get('devmode', \Tbmt\TYPE_BOOL, false) )
      throw new \PageNotFoundException();

    if ( $action === 'loadtest' )
      return parent::dispatchAction($action, $params);

    $login = Session::getLogin();
    if ( $action !== 'loadtest' && !$login || $login->getType() < \Member::TYPE_SALES_MANAGER )
      throw new PermissionDeniedException();

    return parent::dispatchAction($action, $params);
  }

  public function action_index() {
    return $this->action_activities();
  }

  private function getMailParams() {
    $con = \Propel::getConnection();

    $member102 = \Member::getByNum('102');
    $member105 = \Member::getByNum('105');
    $member102->setReferrerId($member105->getId());

    $member_lvl1 = $member102->copy();
    $member_lvl1->setFundsLevel(\Member::FUNDS_LEVEL1);

    $member_paidCount1 = $member102->copy();
    $member_paidCount1->setAdvertisedCount(1);

    $member_paidCount2 = $member102->copy();
    $member_paidCount2->setAdvertisedCount(2);

    $member_paidCount3 = $member102->copy();
    $member_paidCount3->setAdvertisedCount(3);

    $now = time();

    $emailValidation = \EmailValidation::create($now, 'efesus133@gmail.com', [], $con);
    $emailValidation->delete();

    return [
      'EmailValidation_#1' => ['efesus133@gmail.com', 'efesus133', $emailValidation],
      'SignupConfirm_#2' => [$member102],
      'FeeIncome_#3' => [$member102],
      'SignupConfirmInvitation_bonus_#4' => [$member_lvl1, false],
      'SignupConfirmInvitation_freeAndlvl2_#5' => [$member102, true],
      'SignupConfirmInvitation_free_#6' => [$member_lvl1, true],
      'NewRecruitmentCongrats_#7' => [$member102, $member105],
      'FeeIncomeReferrer_count1_#8' => [$member_paidCount1, $member105],
      'FeeIncomeReferrer_count2_#9' => [$member_paidCount2, $member105],
      'FeeIncomeReferrer_premium_#10' => [$member_paidCount3, $member105, false],
      'HgAvailable_#11' => [$member102],

    ];
  }

  public function action_printmail() {
    $mails = $this->getMailParams();
    $mailsLinksHtml = '<h1>Mails</h1><div class="bottom-30"><ul>';
    foreach ($mails as $name => $arrParams) {
      $mailsLinksHtml .= '<li><a href="'.Router::toModule('debug', 'printmail', [
        'mail' => $name
      ]).'">'.$name.'</a></li>';
    }

    $mailsLinksHtml .= '</ul></div>';

    if ( empty($_REQUEST['mail']) )
      return '<div class="container">'.$mailsLinksHtml.'</div>';

    $embed = !empty($_REQUEST['embed']);
    $mail = $_REQUEST['mail'];

    $embedUrl = Router::toModule('debug', 'printmail', [
      'mail' => $mail,
      'embed' => 1
    ]);

    MailHelper::$DEBUG_PRINT = true;
    $mail = $this->getMail($mail, $mails[$mail]);

    if ( $embed ) {
      echo $mail[3];
      exit;
    }

    return '<div class="container">'.$mailsLinksHtml.'<h1>Html</h1>'.
      '<iframe src="'.$embedUrl.'" style="height: 600px; width: 100%;"></iframe>'.
      '<h1>Plain Text</h1>'.
      '<pre>'.
      $mail[2]."\n\n".$mail[4].
      '</pre>'.
      '</div>';
  }

  private function getMail($name, $arrParams) {
    $name = explode('_', $name);
    if ( is_array($name) )
      $name = $name[0];

    return call_user_func_array(['Tbmt\\MailHelper', 'send'.$name], $arrParams);
  }

  public function action_allinvoices() {
    $members = \MemberQuery::create()
      ->filterByDeletionDate(null, \Criteria::ISNULL)
      ->filterByNum('101', \Criteria::NOT_EQUAL)
      ->orderBy('num')
      ->find();

    $result = '<div class="container"><div class="row sheet">
      <table class="table2Debug">
        <tbody>
            <tr>
              <th>Member</th>
              <th>Total</th>
              <th>Reasons Total</th>
              <th>Quantity</th>
              <th>Reasons</th>
            </tr>';

    $reasons = \Tbmt\Localizer::get('view.account.tabs.invoice.transaction_reasons');

    foreach ($members as $member) {
      $result .= '<tr>';
      $result .= '<td>'.$member->getNum().' - '.$member->getFirstName().' - '.$member->getLastName().'</td>';
      $result .= '<td>'.\Tbmt\view\Factory::currencyArrToString($member->getOutstandingTotal()).'</td>';
      $result .= '<td colspan="3"></td>';
      $result .= '</tr>';

      $transactions = \TransactionQuery::create()
        ->join('Transfer')
        ->useTransferQuery()
          ->filterByMember($member)
        ->endUse()
        ->select(['Reason'])
        ->withColumn('count(*)', 'Quantity')
        ->withColumn('sum(Transaction.Amount)', 'Total')
        ->groupBy('Transfer.Currency')
        ->groupBy('Transaction.Reason')
        ->find();

      foreach ($transactions as $transaction) {
        $result .= '<tr>';
        $result .= '<td colspan="2"></td>';
        $result .= '<td>'.\Tbmt\Localizer::numFormat($transaction['Total']).'</td>';
        $result .= '<td>'.$transaction['Quantity'].'</td>';
        $result .= '<td>'.$reasons[$transaction['Reason']].'</td>';
        $result .= '</tr>';

      }
    }

    $result .= '</tbody></table></div></div>';

    return $result;
  }

  public function action_activities() {
    $activities = \ActivityQuery::create()->limit(100)->orderBy(\ActivityPeer::DATE, \Criteria::DESC)->find();

    $result = '<div class="container"><div class="row sheet">
      <table class="table2Activities table" id="table2Activities">
        <tbody>
            <tr>
              <th>ID</th>
              <th>Type</th>
              <th>Action</th>
              <th>Member</th>
              <th>Member</th>
              <th>Related</th>
              <th>Date</th>
            </tr>';

    $types = [
      \Activity::TYPE_SUCCESS => 'success',
      \Activity::TYPE_FAILURE => 'failure',
    ];

    $actions = [
      \Activity::ACT_ACCOUNT_BONUS_LEVEL => 'account_bonus_level',
      \Activity::ACT_ACCOUNT_BONUS_PAYMENT => 'account_bonus_payment',

      \Activity::ACT_MEMBER_SIGNUP => 'signup',

      \Activity::ACT_MEMBER_PAYMENT_CREATE => 'payment_create',
      \Activity::ACT_MEMBER_PAYMENT_EXEC => 'payment_exec',
      \Activity::ACT_MEMBER_PAYMENT_CANCEL => 'payment_cancel',
      \Activity::ACT_MEMBER_PAYMENT_CANCEL_BY_USER => 'payment_cancel_by_user',
      \Activity::ACT_MEMBER_PAYMENT_CANCEL_UNKNOWN => 'payment_cancel_unknown',
    ];

    $reasons = \Tbmt\Localizer::get('view.account.tabs.invoice.transaction_reasons');

    foreach ($activities as $activity) {
      $result .= '<tr class="js-togglemeta '.($activity->getType() == 2 ? 'danger' : '').'">';
      $result .= '<td><b>'.$activity->getId().'</b></td>';
      $result .= '<td>'.$types[$activity->getType()].'</td>';
      $result .= '<td>'.$actions[$activity->getAction()].'</td>';
      $result .= '<td>'.$activity->getMemberId().'</td>';
      $result .= '<td>'.$activity->getRelatedId().'</td>';
      $result .= '<td>'.date('r', $activity->getDate()).'</td>';
      $result .= '</tr>';

      $result .= '<tr class="togglemeta '.($activity->getType() == 2 ? 'danger' : '').'">';
      $result .= '<td colspan="6"><pre>'.print_r($activity->getMeta(), true).'</pre></td>';
      $result .= '</tr>';
    }

    $result .= '</tbody></table></div></div>';

    $result .= <<<END
<script>
  var table = jQuery('#table2Activities');
  table.click(function(event) {
    var row = jQuery(event.target).parents('tr');
    if ( row.hasClass('js-togglemeta') ) {
      row.toggleClass('open');
    } else if ( row.hasClass('togglemeta') ) {
      row.prev().toggleClass('open');
    }
  });
</script>
END;

    return $result;
  }

  public function action_loadtest() {
    require dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'helper.php';

    $count = \MemberQuery::create()->count();

    // $parentId = rand(1, $count-1);

    // $parent = \MemberQuery::create()
    //   ->limit(1)
    //   ->offset($parentId)
    //   ->findOne();

    $parent = \MemberQuery::create()->findOneById(3);

    $con = \Propel::getConnection();
    \DbEntityHelper::setCon($con);
    \DbEntityHelper::setCurrency(\Transaction::$BASE_CURRENCY);

    \DbEntityHelper::createSignupMemberInActivity($parent);

    return new \Tbmt\ControllerActionAjax('k23l45hkj2hasdn');
  }
}

?>
