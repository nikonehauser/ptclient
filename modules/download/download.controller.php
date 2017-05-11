<?php

namespace Tbmt;

class DownloadController extends BaseController {

  const MODULE_NAME = 'download';

  protected $actions = [
    'illustration' => true,
    'hgillustration' => true,
    'guide' => true,
    'payout' => true
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
      throw new Tbmt\PermissionDeniedException();
    }

    $number = $_REQUEST['number'];
    if ( !in_array($number, [1, 2, 3, 4, 5, 6, 7, 8]) )
      throw new Tbmt\PermissionDeniedException();

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
    if ( !$login || $login->getType() !== \Member::TYPE_ITSPECIALIST )
      throw new PermissionDeniedException();

    $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : null;
    if ( !$id )
      throw new Tbmt\InvalidDataException('Missing id');

    $payout = \PayoutQuery::create()->findOneById($id);
    if ( !$payout )
      throw new Tbmt\InvalidDataException('Unknown id');

    $filename = $payout->getMasspayFile();
    $payout->setDownloadCount($payout->getDownloadCount() + 1);
    $payout->save();

    return new ControllerActionDownload([
      'name' => "$filename",
      'contentType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      'path' => Config::get('payout.files.dir').$filename
    ]);
  }
}

?>
