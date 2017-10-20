<?php

namespace Tbmt;

class DownloadController extends BaseController {

  const MODULE_NAME = 'download';

  protected $actions = [
    'illustration' => true,
    'hgillustration' => true,
    'guide' => true,
    'payout' => true,
    'memberinvoice' => true
  ];

  public function action_illustration() {
    return new ControllerActionDownload([
      'name' => 'better.live.illustration.pdf',
      'contentType' => 'application/pdf',
      'path' => DOWNLOADS_DIR.'illustration.pdf'
    ]);
  }

  public function action_hgillustration() {
    return new ControllerActionDownload([
      'name' => 'happines.guide.illustration.pdf',
      'contentType' => 'application/pdf',
      'path' => DOWNLOADS_DIR.'hgillustration.pdf'
    ]);
  }

  public function action_guide() {
    $member = Session::getLogin();
    if ( !$member || !$member->hadPaid() ) {
      throw new PermissionDeniedException();
    }

    $number = $_REQUEST['number'];
    if ( !in_array($number, [1, 2, 3, 4, 5, 6, 7, 8]) )
      throw new PermissionDeniedException();

    if ( $number == 2 && $member->getAdvertisedCountTotal() == 0 ) {
      $number = '2_n';
    }

    $namePrefix = 'hg_part_';
    $extension = 'zip';
    $contentType = 'application/zip, application/octet-stream';
    return new ControllerActionDownload([
      'name' => "hg_part$number.$extension",
      'contentType' => $contentType,
      'path' => DOWNLOADS_DIR."$namePrefix$number.$extension"
    ]);
  }

  public function action_payout() {
    $login = Session::getLogin();
    if ( !$login || $login->getType() < \Member::TYPE_SALES_MANAGER )
      throw new PermissionDeniedException();

    $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : null;
    if ( !$id )
      throw new InvalidDataException('Missing id');

    $payout = \PayoutQuery::create()->findOneById($id);
    if ( !$payout )
      throw new InvalidDataException('Unknown id');

    $filename = $payout->getMasspayFile();
    $payout->setDownloadCount($payout->getDownloadCount() + 1);
    $payout->save();

    return new ControllerActionDownload([
      'name' => "$filename",
      'contentType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      'path' => Config::get('payout.files.dir').$filename
    ]);
  }

  public function action_memberinvoice() {
    $login = Session::getLogin();
    if ( !$login || $login->getType() < \Member::TYPE_SALES_MANAGER )
      throw new PermissionDeniedException();

    $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : null;
    if ( !$id )
      throw new InvalidDataException('Missing id');

    $payment = \PaymentQuery::create()
      ->filterByMemberId($id)
      ->filterByStatus(\Payment::STATUS_EXECUTED)
      ->orderBy(\PaymentPeer::DATE, \Criteria::DESC)
      ->findOne();

    if ( !$payment )
      throw new InvalidDataException('Member has no payment');

    $filename = $payment->ensureInvoiceFile();

    return new ControllerActionDownload([
      'name' => "$filename",
      'contentType' => 'text/plain',
      'path' => Config::get('invoice.files.dir').$filename
    ]);
  }
}

?>
