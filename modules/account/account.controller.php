<?php

namespace Tbmt;

class AccountController extends BaseController {

  const MODULE_NAME = 'account';

  protected $actions = [
    'index' => true,
    'logout' => true,
  ];

  public function dispatchAction($action, $params) {
    if ( $action === 'logout' ) {
      Session::terminate();
      return new ControllerActionRedirect(Router::toBase());
    }

    if ( !Session::isLoggedIn() ) {
      list($num, $pwd) = Arr::initList($_REQUEST, [
        'num' => TYPE_STRING,
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

    $member = Session::getLogin();
    if ( !$member ) {
      Session::terminate();
      return new ControllerActionRedirect(Router::toBase());
    }

    if ( Config::get('extended.marketing.member', TYPE_BOOL, false) ) {
      // is extended system
      //
      if ( $member->isExtended() ) {
        // fine, nothing todo
        //
      } else {
        // no access for standard user to extended system
        return ControllerDispatcher::renderModuleView(
          self::MODULE_NAME,
          'login',
          ['formVal' => ['num' => $num]]
        );
      }

    } else {
      // is product system
      //
      if ( $member->isExtended() ) {
        // redirect to marketing system
        //
        $nonce = \Nonce::create($member);
        $url = Config::get('extended.system.url')."?mod=account&nonce=".$nonce->getNonce();
        return new ControllerActionRedirect($url);

      } else {
        // fine, nothing todo

      }

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

  public function action_invitation_create() {
    $login = Session::getLogin();
    $type = Arr::init($_REQUEST, 'type', TYPE_INT);
    if ( $login->getType() < $type ||
      $type < \Member::TYPE_MEMBER ||
      $type > $login->getType() ||
      $login->getFundsLevel() != \Member::FUNDS_LEVEL2 )
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
}

?>
