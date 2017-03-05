<?php

namespace Tbmt\view;

class PayPayouts extends Base {

  public function render(array $params = array()) {
    $payouts = \PayoutQuery::create()
      ->useTransferQuery()
        ->joinMember()
      ->endUse()
      ->orderBy(\PayoutPeer::CREATION_DATE, \Criteria::DESC)
      ->find();

    $result = '<div class="container"><div class="row sheet">
      <table class="table2Activities table" id="table2Activities">
        <tbody>
            <tr>
              <th>Member</th>
              <th>Result State</th>
              <th>Date</th>
            </tr>';

    $types = [
      \Payout::RESULT_UNKNOWN => 'unknown',
      \Payout::RESULT_SUCCESS => 'success',
      \Payout::RESULT_FAILED => 'failure',
      \Payout::RESULT_REJECTED => 'rejected',
    ];

    foreach ($payouts as $payout) {
      $member = $payout->getTransfer()->getMember();

      $result .= '<tr class="js-togglemeta '.($payout->getResult() == \Payout::RESULT_FAILED ? 'danger' : '').'">';
      $result .= '<td>'.$member->getNum().' - '.$member->getFirstName().' - '.$member->getLastName().'</td>';
      $result .= '<td>'.$types[$payout->getResult()].'</td>';
      $result .= '<td>'.date('r', $payout->getCreationDate()).'</td>';
      $result .= '</tr>';

      $result .= '<tr class="togglemeta '.($payout->getResult() == \Payout::RESULT_FAILED ? 'danger' : '').'">';
      $result .= '<td colspan="3">';
      $result .= '<b>Intern Meta</b>';
      $result .= '<pre>'.print_r(json_decode($payout->getInternMeta(), true), true).'</pre>';
      $result .= '<b>Extern Meta</b>';
      $result .= '<pre>'.print_r(json_decode($payout->getExternMeta(), true), true).'</pre>';
      $result .= '</td></tr>';

      $result .= '<tr><td colspan="3">------------------------------------<br>------------------------------------</td></tr>';
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