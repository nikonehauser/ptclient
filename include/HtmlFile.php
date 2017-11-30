<?php

namespace Tbmt;

class HtmlFile {
  private $options;
  private $filename;
  private $filekey;

  public function __construct($filekey, array $options) {
    $this->filekey = $filekey;
    $this->options = array_merge([
      'filesize' => 50000000, // 50 mb
      'mimetypes' => [],
      'path' => '',
      'prefixUnique' => false,
      'required' => true
    ], $options);
  }

  public function getFilekey() {
    return $this->filekey;
  }

  public function validate($strict = false) {
    try {
      $this->validateInternal();
    } catch (\Exception $e) {
      if ( $strict )
        throw $e;

      return $e->getMessage();
    }

    return true;
  }

  private function validateInternal() {
    $filekey = $this->filekey;

    if ( empty($_FILES[$filekey]) ) {
      if ( !$this->options['required'] )
        return [true, true];

      throw new \Tbmt\PublicException('No file sent.');
    }

    $file = $_FILES[$filekey];
    if ( !isset($file['error']) || is_array($file['error']) ) {
      throw new \Tbmt\PublicException('Unknown errors.');
    }

    switch ($file['error']) {
      case UPLOAD_ERR_OK:
          break;
      case UPLOAD_ERR_NO_FILE:
        if ( !$this->options['required'] )
          return [true, true];

        throw new \Tbmt\PublicException('No file sent.');
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        throw new \Tbmt\PublicException('Exceeded filesize limit.');
      default:
        throw new \Tbmt\PublicException('Unknown errors.');
    }

    // You should also check filesize here.
    if ($file['size'] > $this->options['filesize'] ) {
      throw new \Tbmt\PublicException('Exceeded filesize limit.');
    }

    // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
    // Check MIME Type by yourself.
    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    $ext = array_search($finfo->file($file['tmp_name']), $this->options['mimetypes'], true);
    if (false === $ext) {
        throw new \Tbmt\PublicException('Invalid file format.');
    }

    return [$file, $ext];
  }

  public function save($filename = false) {
    list($file, $ext) = $this->validateInternal();
    if ( $file === true )
      return true;

    $filename = sprintf('%s.%s', !empty($filename) ? $filename : $file['name'], $ext);
    if ( $this->options['prefixUnique'] === true )
      $filename = (new \DateTime())->format('Y-m-d_H-i-s').'_'.uniqid().'_'.$filename;

    // You should name it uniquely.
    // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
    if (!move_uploaded_file($file['tmp_name'], $this->options['path'].$filename)) {
        throw new \RuntimeException('Failed to move uploaded file.');
    }

    return $filename;
  }
}