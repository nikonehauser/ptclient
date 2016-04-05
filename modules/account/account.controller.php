<?php

namespace Tbmt;

class AccountController extends BaseController {

  const MODULE_NAME = 'account';

  protected $actions = [
    'index' => true,
    'logout' => true,
    'invoice' => true,
    'rtree' => true,
    'htree' => true,
    'btree' => true,
    'invitation' => true,
    'invitation_create' => true,
    'bonus_payments' => true,
    'bonus_payments_signup' => true,
    'bonus_levels' => true,
    'bonus_levels_signup' => true,
    'ajax_tree' => true,

    'dev_paying' => true,
    'do_dev_paying' => true,
  ];

  public function dispatchAction($action, $params) {
    if ( $action === 'logout' ) {
      Session::terminate();
      return new ControllerActionRedirect(Router::toBase());
    }

    if ( !Session::isLoggedIn() ) {
      list($num, $pwd) = Arr::initList($_REQUEST, [
        'num' => TYPE_KEY,
        'pwd' => TYPE_STRING
      ]);

      if (!$num || !$pwd || !Session::login($num, $pwd)) {
        return ControllerDispatcher::renderModuleView(
          self::MODULE_NAME,
          'login',
          ['formVal' => ['num' => $num]]
        );
      }
    }

    if ( !Session::getLogin() ) {
      Session::terminate();
      return new ControllerActionRedirect(Router::toBase());
    }

    return parent::dispatchAction($action, $params);
  }

  public function action_index() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => Session::getLogin()]
    );
  }

  public function action_invoice() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => Session::getLogin()]
    );
  }

  public function action_rtree() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => Session::getLogin()]
    );
  }

  public function action_htree() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => Session::getLogin()]
    );
  }

  public function action_btree() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => Session::getLogin()]
    );
  }

  public function action_invitation() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => Session::getLogin()]
    );
  }

  public function action_invitation_create() {
    $login = Session::getLogin();
    $type = Arr::init($_REQUEST, 'type', TYPE_INT);
    if ( $login->getType() <= $type || $type < \Member::TYPE_MEMBER || $type > $login->getType() )
      throw new PermissionDeniedException();

    if ( $type === \Member::TYPE_SUB_PROMOTER ) {
      list($valid, $data, $recipient) = \Invitation::validateInvitationForm($_REQUEST);
      if ( $valid !== true ) {
        return ControllerDispatcher::renderModuleView(
          self::MODULE_NAME,
          'index',
          ['member' => $login, 'tab' => 'invitation', 'formErrors' => $data, 'recipient' => $recipient, 'formVal' => $_REQUEST]
        );
      }

      if ( $data['promoter_id'] === '' ) {
        $data['promoter_id'] = $recipient->getId();
        return ControllerDispatcher::renderModuleView(
          self::MODULE_NAME,
          'index',
          ['member' => $login, 'tab' => 'invitation', 'formVal' => $data, 'recipient' => $recipient]
        );
      }
    } else
      $data = \Invitation::initInvitationForm($_REQUEST);


    \Invitation::create(
      $login,
      $data,
      \Propel::getConnection()
    );

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => $login, 'tab' => 'invitation', 'successmsg' => true, 'formVal' => []]
    );
  }

  public function action_bonus_payments() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => Session::getLogin()]
    );
  }

  public function action_bonus_payments_signup() {
    $login = Session::getLogin();
    if ( $login->getType() !== \Member::TYPE_CEO )
      throw new PermissionDeniedException();

    list($valid, $data, $recipient) = \Transaction::validateBonusTransactionForm($_REQUEST);
    if ( $valid !== true ) {
      return ControllerDispatcher::renderModuleView(
        self::MODULE_NAME,
        'index',
        ['member' => $login, 'tab' => 'bonus_payments', 'formErrors' => $data, 'recipient' => $recipient]
      );
    }

    if ( $data['recipient_id'] === '' ) {
      $data['recipient_id'] = $recipient->getId();
      return ControllerDispatcher::renderModuleView(
        self::MODULE_NAME,
        'index',
        ['member' => $login, 'tab' => 'bonus_payments', 'formVal' => $data, 'recipient' => $recipient]
      );
    }

    $con = \Propel::getConnection();
    \Activity::exec(
      /*callable*/['\\Transaction', 'activity_createBonusTransaction'],
      /*func args*/[
        $login,
        $recipient,
        $data,
        $con
      ],
      /*activity.action*/\Activity::ACT_ACCOUNT_BONUS_PAYMENT,
      /*activity.member*/$login,
      /*activity.related*/$recipient,
      $con
    );

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => $login, 'tab' => 'bonus_payments', 'formVal' => [], 'successmsg' => true]
    );
  }

  public function action_bonus_levels() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => Session::getLogin()]
    );
  }

  public function action_bonus_levels_signup() {
    $login = Session::getLogin();
    if ( $login->getType() !== \Member::TYPE_CEO )
      throw new PermissionDeniedException();

    list($valid, $data, $recipient) = \Member::validateBonusLevelForm($_REQUEST);
    if ( $valid !== true ) {
      return ControllerDispatcher::renderModuleView(
        self::MODULE_NAME,
        'index',
        ['member' => $login, 'tab' => 'bonus_levels', 'formErrors' => $data, 'recipient' => $recipient]
      );
    }

    if ( $data['recipient_id'] === '' ) {
      $data['recipient_id'] = $recipient->getId();
      return ControllerDispatcher::renderModuleView(
        self::MODULE_NAME,
        'index',
        ['member' => $login, 'tab' => 'bonus_levels', 'formVal' => $data, 'recipient' => $recipient]
      );
    }

    $con = \Propel::getConnection();
    \Activity::exec(
      /*callable*/[$recipient, 'activity_setBonusLevel'],
      /*func args*/[
        $data['amount'],
        $con
      ],
      /*activity.action*/\Activity::ACT_ACCOUNT_BONUS_LEVEL,
      /*activity.member*/$login,
      /*activity.related*/$recipient,
      $con
    );

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => $login, 'tab' => 'bonus_levels', 'formVal' => [], 'successmsg' => true]
    );
  }

  public function action_ajax_tree(array $params = array()) {
    $ids = Arr::init($_REQUEST, 'ids', TYPE_ARRAY);
    $bonusOnly = Arr::init($_REQUEST, 'bonusOnly', TYPE_BOOL);
    $rowCount = Arr::init($_REQUEST, 'count', TYPE_INT, 5);
    $byColumn = Arr::init($_REQUEST, 'column', TYPE_STRING, 'ParentId');

    $filterByColumn = "filterBy$byColumn";
    $comparisonOperator = \Criteria::IN;

    $memberTypes = Localizer::get('common.member_types');

    $rows = [];
    for ( $i = 0; $i < $rowCount; $i++ ) {
      $members = \MemberQuery::create()
        ->$filterByColumn($ids, $comparisonOperator);

      if ( $bonusOnly )
        $members->filterByType(\Member::TYPE_MEMBER, \Criteria::GREATER_THAN);

      $members = $members->find();

      if ( count($members) === 0 )
        break;

      $row = $members->toArray();
      $newIds = [];
      foreach ($members as $i => $member) {
        $row[$i]['TypeTranslated'] = $memberTypes[$member->getType()];
        $newIds[] = $member->getId();
      }
      $rows[] = $row;
      $ids = $newIds;
    }

    return new ControllerActionAjax($rows);
  }

  public function action_dev_paying() {
    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => Session::getLogin()]
    );
  }

  public function action_do_dev_paying() {
    if ( !\Tbmt\Config::get('devmode', \Tbmt\TYPE_BOOL, false) || !isset($_REQUEST['fake_income_num']) )
      throw new PageNotFoundException();

    $member = \Member::getByNum($_REQUEST['fake_income_num']);

    $con = \Propel::getConnection();
    if ( !$con->beginTransaction() )
      throw new Exception('Could not begin transaction');

    try {
      $member->onReceivedMemberFee(
        \Transaction::$BASE_CURRENCY,
        time(),
        false,
        $con
      );

      if ( !$con->commit() )
        throw new Exception('Could not commit transaction');

    } catch (Exception $e) {
        $con->rollBack();
        throw $e;
    }

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => Session::getLogin(), 'tab' => 'dev_paying']
    );
  }

}

?>
