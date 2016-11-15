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
    $number = $_REQUEST['number'];
    if ( !in_array($number, [1, 2, 3, 4, 5, 6, 7, 8]) )
      throw new Tbmt\PermissionDeniedException();

    $extension = 'txt';
    return new ControllerActionDownload([
      'name' => "happy_guide$number.$extension",
      'contentType' => 'plain/text',
      'path' => Router::toPublicResource("happy_guide$number.$extension")
    ]);
  }
}

?>
