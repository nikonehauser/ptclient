<?php

namespace Tbmt;

class DownloadController extends BaseController {

  const MODULE_NAME = 'download';

  protected $actions = [
    'illustration' => true,
    'guide' => true
  ];

  public function action_illustration() {
    return new ControllerActionDownload([
      'name' => 'better.live.illustration.pdf',
      'contentType' => 'application/pdf',
      'path' => Router::toPublicResource('illustration.pdf')
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

    $namePrefix = 'hg_part_';
    $extension = 'pdf';
    $contentType = 'application/pdf';
    return new ControllerActionDownload([
      'name' => "hg_part$number.$extension",
      'contentType' => $contentType,
      'path' => Router::toPublicResource("$namePrefix$number.$extension")
    ]);
  }
}

?>
