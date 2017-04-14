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

  static public function canInviteWithFundsLvl2(\Member $member) {
    return $member->getType() >= \Member::TYPE_SALES_MANAGER;
  }

  static public $INVITATION_FORM_FIELDS = [
    'type'        => \Tbmt\TYPE_INT,
    'free_signup' => \Tbmt\TYPE_BOOL,
    'lvl2_signup' => \Tbmt\TYPE_BOOL,
    'promoter_num' => \Tbmt\TYPE_STRING,
    'promoter_id' => \Tbmt\TYPE_STRING,
  ];

  static public function initInvitationForm(array $data = array()) {
    return \Tbmt\Arr::initMulti($data, self::$INVITATION_FORM_FIELDS);
  }

  static public function validateInvitationForm(array $data = array()) {
    $data = self::initInvitationForm($data);

    if ( $data['type'] !== \Member::TYPE_SUB_PROMOTER )
      $data['promoter_num'] = '';

    if ( $data['type'] === \Member::TYPE_SUB_PROMOTER && !$data['promoter_num'] ) {
      return [false, [
        'promoter_num' => \Tbmt\Localizer::get('error.empty')
      ], null];
    }

    $recipient = \MemberQuery::create()
      ->filterByDeletionDate(null, Criteria::ISNULL)
      ->findOneByNum($data['promoter_num']);
    if ( $recipient == null ) {
      return [false, ['promoter_num' => \Tbmt\Localizer::get('error.member_num')], null];
    }

    if ( $recipient->getType() !== \Member::TYPE_PROMOTER )
      return [false, ['promoter_num' => \Tbmt\Localizer::get('error.sub_promoter_to_promoter')], null];

    if ( !$recipient->hadPaid() )
      return [false, ['promoter_num' => \Tbmt\Localizer::get('error.member_num_unpaid')], null];

    return [true, $data, $recipient];
  }

  static public function create(Member $login, array $data, PropelPDO $con) {
    $type = $data['type'];

    $hash = SystemStats::getIncreasedInvitationIncrementer($con);

    $invitation = new Invitation();

    if ( $type === \Member::TYPE_SUB_PROMOTER ) {
      $invitation->setMeta([
        'promoter_num' => $data['promoter_num'],
        'promoter_id' => $data['promoter_id']
      ]);
    }

    if ( !self::canInviteWithFundsLvl2($login) ) {
      // only CEOs can
      $data['lvl2_signup'] = 0;
    }

    $invitation
      ->setHash($hash)
      ->setMemberId($login->getId())
      ->setType($type)
      ->setFreeSignup(isset($data['free_signup']) && $data['free_signup'] ? 1 : 0)
      ->setLvl2Signup(isset($data['lvl2_signup']) && $data['lvl2_signup'] ? 1 : 0)
      ->setCreationDate(time())
      ->save($con);

    return $invitation;
  }

  public function getMeta() {
    return json_decode(parent::getMeta(), true);
  }

  public function setMeta($v) {
    parent::setMeta(json_encode($v));
    return $this;
  }
}
