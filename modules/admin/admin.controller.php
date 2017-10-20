<?php

namespace Tbmt;

class AdminController extends BaseController {

  const MODULE_NAME = 'admin';

  protected $actions = [
    'index' => true,
    'members' => true,
  ];

  public function dispatchAction($action, $params) {
    $login = Session::getLogin();
    if ( !$login || !$login->isAdminModulePermitted() )
      throw new PageNotFoundException();

    return parent::dispatchAction($action, $params);
  }

  public function action_index() {
    $data = \Tbmt\Arr::initMulti($_REQUEST, [
      'set_paid_num' => \Tbmt\TYPE_INT,
      'recipient_num' => [\Tbmt\TYPE_STRING, ''],
    ]);

    $setPaidMember = false;
    if ( !empty($data['set_paid_num']) ) {
      $member = \Member::getByNum($data['set_paid_num']);

      if ( $member->isMarkedAsPaid() )
        throw new InvalidDataException('Member had paid already.');

      $con = \Propel::getConnection();
      if ( !$con->beginTransaction() )
        throw new Exception('Could not begin transaction');

      try {
        $member->setHadPaid(\Payment::TYPE_SETBYADMIN, $con);

        if ( !$con->commit() )
          throw new Exception('Could not commit transaction');

        $setPaidMember = $member;
      } catch (Exception $e) {
          $con->rollBack();
          throw $e;
      }
    }

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'index',
      ['formVal' => $data, 'setPaidMember' => $setPaidMember]
    );
  }

  public function action_members() {
    $data = \Tbmt\Arr::initMulti($_REQUEST, [
      'search_member' => [\Tbmt\TYPE_STRING, ''],
      'orderBy' => [\Tbmt\TYPE_STRING, '-signupdate'],
      'limitBy' => [\Tbmt\TYPE_INT, 200],
      'filterBy' => \Tbmt\TYPE_STRING
    ]);

    return ControllerDispatcher::renderModuleView(
      self::MODULE_NAME,
      'members',
      ['formVal' => $data]
    );
  }
}

?>
