<?php

namespace Tbmt\view;

class PayPayouts extends Base {

  public function render(array $params = array()) {
    $payouts = \PayoutQuery::create()
      ->orderBy(\PayoutPeer::CREATION_DATE, \Criteria::DESC)
      ->limit(100)
      ->find();

    $result = '<div class="container">
      '.\Tbmt\view\Factory::buildHeadingArea('List Payouts (last 100)').'
      <div class="row sheet">
      <table class="table2Activities table" id="table2Activities">
        <tbody>
            <tr>
              <th>Date</th>
              <th>Type</th>
              <th>Download Count</th>
              <th>Excel Files (Downloadable)</th>
            </tr>';

    foreach ($payouts as $payout) {
      # $member = $payout->getTransfer()->getMember();

      $result .= '<tr>';
      # $result .= '<td>'.$member->getNum().' - '.$member->getFirstName().' - '.$member->getLastName().'</td>';
      $result .= '<td>'.date('r', $payout->getCreationDate()).'</td>';
      $result .= '<td>'.strtoupper($payout->getType()).'</td>';
      $result .= '<td>'.$payout->getDownloadCount().'</td>';
      $result .= '<td><a href="'.\Tbmt\Router::toModule('download', 'payout', ['id' => $payout->getId()]).'">'.$payout->getMasspayFile().'</a></td>';
      $result .= '</tr>';

      # $result .= '<tr><td colspan="3">------------------------------------<br>------------------------------------</td></tr>';
    }

    $result .= '</tbody></table></div></div>';

    return $result;
  }

}