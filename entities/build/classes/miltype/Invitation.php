<?php



/**
 * Skeleton subclass for representing a row from the 'tbmt_invitation' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.miltype
 */
class Invitation extends BaseInvitation
{

  static public $INVITATION_FORM_FIELDS = [
    'type'        => \Tbmt\TYPE_INT,
    'free_signup' => \Tbmt\TYPE_BOOL,
  ];

  static public function initInvitationForm(array $data = array()) {
    return \Tbmt\Arr::initMulti($data, self::$INVITATION_FORM_FIELDS);
  }

  static public function create(Member $login, array $data, PropelPDO $con) {
    $formData = \Invitation::initInvitationForm($data);

    $invitationsCount = InvitationQuery::create()->count();
    $hash = \Tbmt\Cryption::getInvitationHash(
      $login,
      $formData['type'],
      $invitationsCount.time()
    );

    $invitation = new Invitation();
    $invitation
      ->setHash($hash)
      ->setMemberId($login->getId())
      ->setType($formData['type'])
      ->setFreeSignup($formData['free_signup'] ? 1 : 0)
      ->setCreationDate(time())
      ->save($con);

    return $invitation;
  }
}
