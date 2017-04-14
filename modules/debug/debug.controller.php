<?php

namespace Tbmt;

class DebugController extends BaseController {

  const MODULE_NAME = 'debug';

  protected $actions = [
    'index' => true,
    'allinvoices' => true,
    'activities' => true,
    'printmail' => true,
  ];

  public function dispatchAction($action, $params) {
    if ( !\Tbmt\Config::get('devmode', \Tbmt\TYPE_BOOL, false) )
      throw new \PageNotFoundException();

    $login = Session::getLogin();
    if ( !$login || $login->getType() !== \Member::TYPE_ITSPECIALIST )
      throw new PermissionDeniedException();

    return parent::dispatchAction($action, $params);
  }

  public function action_index() {
    return $this->action_activities();
  }

  public function action_printmail() {
    if ( empty($_REQUEST['mail']) )
      throw new \Exception('Missing param "mail"');

    $embed = !empty($_REQUEST['embed']);
    $mail = $_REQUEST['mail'];

    $embedUrl = Router::toModule('debug', 'printmail', [
      'mail' => $mail,
      'embed' => 1
    ]);

    MailHelper::$DEBUG_PRINT = true;
    $mail = $this->getMail($mail);

    if ( $embed ) {
      echo $mail[3];
      exit;
    }

    return '<div class="container"><h1>Html</h1>'.
      '<iframe src="'.$embedUrl.'" style="height: 600px; width: 100%;"></iframe>'.
      '<h1>Plain Text</h1>'.
      '<pre>'.
      $mail[2]."\n\n".$mail[4].
      '</pre>'.
      '</div>';
  }

  private function getMail($name) {
    switch ($name) {
      case 'FundsLevelUpgrade':
        return MailHelper::sendFundsLevelUpgrade(
          \Member::getByNum('102'),
          \Member::getByNum('105')
        );

      case 'FeeIncomeReferrer':
        return MailHelper::sendFeeIncomeReferrer(
          \Member::getByNum('102'),
          \Member::getByNum('105')
        );

      case 'FeeIncome':
        $member102 = \Member::getByNum('102');
        $member102->setReferrerId(\Member::getByNum('105')->getId());
        // Do not save!!
        return MailHelper::sendFeeIncome(
          $member102
        );
      case 'FreeSignupConfirm':
        $member102 = \Member::getByNum('102');
        $member102->setReferrerId(\Member::getByNum('105')->getId());
        // Do not save!!
        return MailHelper::sendFreeSignupConfirm(
          $member102
        );
      case 'NewFreeRecruitmentCongrats':
        return MailHelper::sendNewFreeRecruitmentCongrats(
          \Member::getByNum('102'),
          \Member::getByNum('105')
        );
    }
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
      \Activity::ACT_MEMBER_PAYMENT_FINALIZE => 'payment_finalze',

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
}

?>
