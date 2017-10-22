<?php

namespace Tbmt\view;

class AdminImptrans extends Base {

  public function render(array $params = array()) {
    $this->formVal = isset($params['formVal']) ? $params['formVal'] : [];
    $this->formErrors = isset($params['formErrors']) ? $params['formErrors'] : [];

    $this->processLocked = false;
    $lock = new \Tbmt\Flock(\Tbmt\Config::get('lock.payment.import.path'));

    if ( $lock->acquire() ) {
      try {
        if ( !empty($this->formVal['execfile']) ) {
          $this->execImport();

        } else if ( $this->formVal['formsubmit'] == '1' && empty($_FILES['importfile']['tmp_name']) ) {
          $this->formErrors['importfile'] = 'Can not be empty!';

        } else if ( $this->formVal['formsubmit'] == '1' ) {
          $this->previewImport();
        }
      } finally {
        $lock->release();
      }
    } else {
      $this->processLocked = true;
    }

    $this->paymentImports = \PaymentImportQuery::create()
      ->joinWith('Member')
      ->orderBy('PaymentImport.CreationDate', \Criteria::DESC)
      ->find();

    return $this->renderFile(
      dirname(__FILE__).DIRECTORY_SEPARATOR.'imptrans.admin.html',
      $params
    );
  }

  private function previewImport() {
    $paymentsDir = \Tbmt\Config::get('payment_import.files.dir');

    $file = $_FILES['importfile'];
    if ( empty($file['error']) || is_array($file['error']) ) {
      switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new \Tbmt\PublicException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new \Tbmt\PublicException('Exceeded filesize limit.');
        default:
            throw new \Tbmt\PublicException('Unknown errors.');
      }

    }

    // You should also check filesize here.
    if ($file['size'] > 50000000) { // 50 mb
      throw new \Tbmt\PublicException('Exceeded filesize limit.');
    }

    // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
    // Check MIME Type by yourself.
    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($file['tmp_name']),
        array(
            'xls' => 'application/vnd.ms-excel',
        ),
        true
    )) {
        throw new \Tbmt\PublicException('Invalid file format.');
    }

    $filename = (new \DateTime())->format('Y-m-d_H-i-s').'_'.uniqid().'_'.
      sprintf('%s.%s', $file['name'], $ext);

    // You should name it uniquely.
    // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
    if (!move_uploaded_file($file['tmp_name'], $paymentsDir.$filename)) {
        throw new RuntimeException('Failed to move uploaded file.');
    }

    $this->data = \Tbmt\Payments::processData($paymentsDir.$filename, null, false);
    $this->data['filename'] = $filename;
  }

  private function execImport() {
    $paymentsDir = \Tbmt\Config::get('payment_import.files.dir');

    $this->data = \Activity::exec(
      /*callable*/function ($filename, \Member $member) {
        return \Tbmt\Payments::processData($filename, $member, true);
      },
      /*func args*/[
        $paymentsDir.$this->formVal['execfile'],
        \Tbmt\Session::getLogin()
      ],
      /*activity.action*/\Activity::ACT_ADMIN_IMPORT_PAYMENTS,
      /*activity.member*/\Tbmt\Session::getLogin(),
      /*activity.related*/null,
      \Propel::getConnection(),
      /*inside db transaction?*/false
    );

    $this->data['didExecImport'] = true;
    $this->data['filename'] = false;

  }

}