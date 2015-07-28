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
    'invitation' => true,
    'invitation_create' => true,
    'bonus_payments' => true,
    'bonus_payments_signup' => true,
    'ajax_tree' => true,
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
    if ( $login->getType() <= $type || $type <= \Member::TYPE_MEMBER || $type >= \Member::TYPE_CEO )
      throw new PermissionDeniedException();

    \Invitation::create(
      $login,
      $_REQUEST,
      \Propel::getConnection()
    );

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => Session::getLogin(), 'tab' => 'invitation']
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

    list($valid, $data, $recipient) = \BonusTransaction::validateForm($_REQUEST);
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

    \BonusTransaction::create(
      $login,
      $recipient,
      $data,
      \Propel::getConnection()
    );

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['member' => $login, 'tab' => 'bonus_payments', 'formVal' => []]
    );
  }

  public function action_ajax_tree(array $params = array()) {
    $ids = Arr::init($_REQUEST, 'ids', TYPE_ARRAY);
    $rowCount = Arr::init($_REQUEST, 'count', TYPE_INT, 5);
    $byColumn = Arr::init($_REQUEST, 'column', TYPE_STRING, 'ParentId');

    $filterByColumn = "filterBy$byColumn";

    $rows = [];
    for ( $i = 0; $i < $rowCount; $i++ ) {
      $members = \MemberQuery::create()
        ->$filterByColumn($ids, \Criteria::IN)
        ->find();

      if ( count($members) === 0 )
        break;

      $rows[] = $members->toArray();
      $newIds = [];
      foreach ($members as $member) {
        $newIds[] = $member->getId();
      }
      $ids = $newIds;
    }

    return new ControllerActionAjax($rows);
  }

}

?>
