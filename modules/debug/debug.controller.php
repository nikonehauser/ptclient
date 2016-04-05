<?php

namespace Tbmt;

class DebugController extends BaseController {

  const MODULE_NAME = 'debug';

  protected $actions = [
    'allinvoices' => true,
    'printmail' => true,
  ];

  public function dispatchAction($action, $params) {
    if ( !\Tbmt\Config::get('devmode', \Tbmt\TYPE_BOOL, false) )
      throw new \PageNotFoundException();

    return parent::dispatchAction($action, $params);
  }

  public function action_printmail() {
    if ( empty($_REQUEST['mail']) )
      throw new Exception('Missing param "mail"');

    $mail = $_REQUEST['mail'];

    MailHelper::$DEBUG_PRINT = true;
    $mail = $this->getMail($mail);
    return '<pre>'.
      $mail[2]."\n\n".$mail[3].
      '</pre>';
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
        $member105 = \Member::getByNum('105');
        $member105->setReferrerId(\Member::getByNum('105')->getId());
        // Do not save!!
        return MailHelper::sendFeeIncome(
          $member105
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

    $reasons = [
            0 => 'Advertised Lvl 1',
            1 => 'Advertised Lvl 2',
            2 => 'Bonus Lvl 2 Indirect',
            3 => 'Bonus marketing leader',
            4 => 'Bonus organization leader',
            5 => 'Bonus promoter',
            6 => 'Bonus IT',
            7 => 'Bonus CEO1',
            // 8 => 'Bonus CEO2',
            // 9 => 'Bonus lawyer',
            10 => 'Bonus sub promoter',
            11 => 'Bonus sub promoter referrer',

            12 => 'Bonus Sylvhelm',
            13 => 'Bonus Executive',
            14 => 'Bonus Taric Wani',
            15 => 'Bonus NGO Projects',

            1001 => 'Custom bonus payment',
            1002 => 'Remaining donation',
            1003 => 'Transfer to root system',

            2000 => 'Custom bonus level payment',
          ];

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
}

?>
