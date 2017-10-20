<?php

namespace Tbmt\view;

class AdminMembers extends Base {


  static public function initBonusLevelForm(array $data = array()) {
    return \Tbmt\Arr::initMulti($data, self::$BONUS_LEVEL_FORM_FIELDS);
  }

  public function render(array $params = array()) {
    $this->formVal = isset($params['formVal']) ? $params['formVal'] : [];
    $this->formErrors = isset($params['formErrors']) ? $params['formErrors'] : [];

    $this->successmsg = false;

    // Base query
    $membersQuery = \MemberQuery::create()
      ->joinWith('Member.MemberRelatedByReferrerId Referrer', \Criteria::LEFT_JOIN)
      ->limit($this->formVal['limitBy']);

    // handle sorting and grouping
    $orderDirection = $this->formVal['orderBy'][0] == '+' ? \Criteria::ASC : \Criteria::DESC;
    switch (substr($this->formVal['orderBy'], 1)) {

      case 'signupdate':
        $membersQuery->orderBy(\MemberPeer::SIGNUP_DATE, $orderDirection);
        $groupBy = 'getSignupDate';
      break;

      case 'name':
        $membersQuery->orderBy(\MemberPeer::FIRST_NAME, $orderDirection);
        $groupBy = false;
      break;

      case 'paiddate':
        $membersQuery->orderBy(\MemberPeer::PAID_DATE, $orderDirection);
        $groupBy = 'getPaidDate';
      break;

    }
    $this->groupBy = $groupBy;

    // handle filters
    switch ($this->formVal['filterBy']) {
      case '10recruitmentswithoutbonus':
        $membersQuery->filterByAdvertisedCount(10, \Criteria::GREATER_EQUAL);
        $membersQuery->filterByBonusLevel(0);
      break;
    }

    // handle search
    if ( !empty($this->formVal['search_member']) ) {
      $vals = explode(' ', strtolower($this->formVal['search_member']));

      foreach ( $vals as $i => $val ) {
        if ( $i != 0 )
          $membersQuery->_or();

        $membersQuery
          ->where('LOWER(Member.FirstName) like ?', '%'.$val.'%')
          ->_or()
          ->where('LOWER(Member.LastName) like ?', '%'.$val.'%')
          ->_or()
          ->where('CAST(Member.Num as TEXT) like ?', '%'.$val.'%');
      }
    }

    $this->members = $membersQuery->find();

    $this->count = count($this->members);
    if ( $this->count == $this->formVal['limitBy'] ) {
      $this->count = ' >= '.$this->count;
    }

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'members.admin.html',
      $params
    );
  }

}