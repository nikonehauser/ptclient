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

    $fileValidator = new \Tbmt\HtmlFile('importfile', [
      'path' => $paymentsDir,
      'prefixUnique' => true,
      'mimetypes' => array(
          'xls' => 'application/vnd.ms-excel',
      ),
    ]);

    $fileValidator->validate(true);
    $filename = $fileValidator->save();
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