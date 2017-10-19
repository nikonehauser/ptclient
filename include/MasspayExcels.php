<?php

namespace Tbmt;

class MasspayExcels {

  private $transferByMemberVersion = [];

  public function __construct() {
    $this->sheet1 = $this->createSheet();
    $this->sheet2 = $this->createSheet();

    $this->aTab1 = $this->sheet1->setActiveSheetIndex(0);
    $this->aTab2 = $this->sheet2->setActiveSheetIndex(0);
    $this->currentRow = 1;

    $this->addHeaderFor1($this->aTab1);
    $this->addHeaderFor2($this->aTab2);

    $this->ourCustomerId = Config::get('bank_customer_id');
    $this->ourBankAccountNumber = Config::get('bank_account_number');
  }

  private function createSheet() {
    $sheet = new \PHPExcel();
    $sheet->getProperties()->setCreator("Betterliving")
       ->setLastModifiedBy("Betterliving")
       ->setTitle("Betterliving Mass Payouts")
       ->setSubject("Betterliving Mass Payouts")
       // ->setKeywords("office PHPExcel php")
       // ->setCategory("Test result file")
       ->setDescription("Betterliving Mass Payouts");

    return $sheet;
  }

  private function addHeaderFor1($aTab) {
    $this->neftColumnNameSheet1 = 'U';
    $this->rtgsColumnNameSheet1 = 'V';

    $columns = [
      'BenCode', // A
      'BenName', // B
      'Address1', // C
      'Address2', // D
      'City', // E
      'State', // F
      'Zip_Code', // G
      'Phone', // H
      'Email', // I
      'BeneficiaryAccountNo.', // J
      'InputOnlyInternalFundTransferAccountno.', // K
      'Delivery_Address1', // L
      'Delivery_Address2', // M
      'Delivery_City', // N
      'Delivery_State', // O
      'Delivery_Zip_Code', // P
      'PrintLocation', // Q
      'CustomerID', // R
      'IFSC', // S
      'MailTo', // T
      'NEFT', // U
      'RTGS', // V
      'CHQ', // W
      'DD', // X
      'IFTO', // Y
      'FirstLinePrint' // Z
    ];

    $row = 1;
    $columnName = 'A';
    foreach ( $columns as $index => $column ) {
      $aTab->setCellValue($columnName.$row, $column);
      $columnName++;
    }
  }

  private function addHeaderFor2($aTab) {
    $this->amountColumnNameSheet2 = 'H';
    $this->paymentModeColumnNameSheet2 = 'A';

    $columns = [
      'Payment Mode',
      'Bene Code',
      'Bene A/c No.',
      'Amount',
      'Bene Name',
      'Drawee Location',
      'Print Location',
      'Bene Addr 1',
      'Bene Addr 2',
      'City - Pincode',
      'State',
      'Zipcode',
      'Instrument Ref No.',
      'Customer Ref No.',
      'Payment Detail 1',
      'Payment Detail 2',
      'Payment Detail 3',
      'Payment Detail 4',
      'Payment Detail 5',
      'Payment Detail 6',
      'PaymentDetail7',
      'Instrument No',
      'Inst. Date',
      'MICR No',
      'IFSC code',
      'Bene Bank Name',
      'Bene Bank Branch',
      'Bene Email ID',
      'Source Current  Account Number',
      'Remarks 1',
      'Remarks 2'
    ];

    $columnName = 'A';
    $row = 1;
    foreach ( $columns as $index => $column ) {
      $aTab->setCellValue($columnName.$row, $column);
      $columnName++;
    }
  }

  public function addTransfer(\Transfer $transfer, $currency, $configReference) {
    $this->currentRow++;

    $member = $transfer->getMember();
    $memberReference = $member->getNum();
    $memberReference = "$configReference $memberReference";

    $memberProfileVersion = $member->getId().'-'.$member->getProfileVersion();

    $summedAmount = $transfer->getAmountSum();

    if ( isset($this->transferByMemberVersion[$memberProfileVersion]) ) {
      $this->updateExistingMemberRow($memberProfileVersion, $summedAmount);
      // no new row filled
      $this->currentRow--;
      return;
    }

    $this->transferByMemberVersion[$memberProfileVersion] = [
      $this->currentRow,
      $summedAmount
    ];

    if ( $this->mightBeIndusDirectBank($member) ) {
      $indusAccNo = $member->getIban();
      $otherAccNo = '';
    } else {
      $indusAccNo = '';
      $otherAccNo = $member->getIban();
    }

    list($columnNeft, $columnRtgs, $paymentMode) = $this->getColumnsContentByAmount($summedAmount);

    // sheet 1 columns
    $this->addRow($this->aTab1, [
      $member->getFirstName().' '.$member->getLastName(), // A
      $member->getLastName(), // B
      $member->getStreet(), // C
      $member->getStreetAdd(), // D
      $member->getCity(), // E
      '', // F
      $member->getZipCode(), // G
      '', // H
      '', // I
      $otherAccNo, // J
      $indusAccNo, // K
      '', // L
      '', // M
      '', // N
      '', // O
      '', // P
      '', // Q
      $member->getNum(), // R
      $this->ourCustomerId, // R
      '', // T
      $columnNeft, // U
      $columnRtgs, // V
      '', // W
      '', // X
      '', // Y
      '', // Z
    ]);

    // sheet 2 columns
    $this->addRow($this->aTab2, [
      $paymentMode, // A
      $member->getFirstName().' '.$member->getLastName(), // B
      '', // C
      $summedAmount, // D
      '', // E
      '', // F
      '', // G
      '', // H
      '', // I
      '', // J
      '', // K
      '', // L
      '', // M
      $member->getNum(), // N
      $this->getInvoiceNumber($member), // O
      '', // P
      '', // Q
      '', // R
      '', // R
      '', // T
      '', // U
      '', // V
      '', // W
      '', // X
      '', // Y
      '', // Z
      '', // AA
      '', // AB
      $this->ourBankAccountNumber, // AC
      '', // AD
      '', // AE
    ]);
  }

  private function addRow($aTab, $columns) {
    $prefix = '';
    $columnName = 'A';
    foreach ( $columns as $index => $column ) {
      if ( $columnName == 'Z' ) {
        $prefix = 'A';
        $columnName = 'A';
      }

      $aTab->setCellValue($prefix.$columnName.$this->currentRow, $column);
      $columnName++;
    }
  }

  /**
   * Columns sheet1 U and V and sheet 2 A
   *              NEFT   RTGS         payment mode
   *
   * returned as array in this very order.
   *
   * @param  [type] $transfer
   * @return [type]
   */
  private function getColumnsContentByAmount($amount) {
    if ( $amount > 200000 ) {
      return ['', 'Y', 'R'];
    }

    return ['Y', '', 'N'];
  }

  private function updateExistingMemberRow($memberProfileVersion, $additionalAmount) {
    // there already is an entry for the member in this sheet, just update the amount
    $rowValues = $this->transferByMemberVersion[$memberProfileVersion];
    $rowNumber = $rowValues[0];
    $amount = $rowValues[1];

    $amount += $additionalAmount;

    $this->aTab2->setCellValue($this->amountColumnNameSheet2.$rowNumber, $this->formatPrice($amount));

    list($columnNeft, $columnRtgs, $paymentMode) = $this->getColumnsContentByAmount($amount);

    $this->aTab1->setCellValue($this->neftColumnNameSheet1.$rowNumber, $columnNeft);
    $this->aTab1->setCellValue($this->rtgsColumnNameSheet1.$rowNumber, $columnRtgs);

    $this->aTab2->setCellValue($this->paymentModeColumnNameSheet2.$rowNumber, $paymentMode);

    // update amount
    $this->transferByMemberVersion[$memberProfileVersion] = [
      $rowNumber,
      $amount
    ];
  }

  private function mightBeIndusDirectBank($member) {
    return preg_match(
      '/(indus\s*direct)|indusind|indusups/',
      preg_replace('/[\s]+/', '', strtolower($member->getBankName()))
    ) !== 0;
  }

  public function save() {
    $filesPath = Config::get('payout.files.dir');
    $uniqidName = (new \DateTime())->format('Y-m-d_H-i-s').'_'.uniqid();

    $zipFile = $uniqidName.'.zip';
    $zipFileWithPath = $filesPath.$zipFile;
    $zip = new \ZipArchive();
    if ( $zip->open($zipFileWithPath, \ZipArchive::CREATE) !== TRUE) {
        throw new \Exception("cannot open <$zipFileWithPath>\n");
    }

    $objWriter1 = \PHPExcel_IOFactory::createWriter($this->sheet1, 'Excel5');
    $filename1 = $uniqidName.'_excel_#1.xls';
    $fileName1WithPath = $filesPath.$filename1;
    $objWriter1->save($fileName1WithPath);

    if ( $zip->addFile($fileName1WithPath, $filename1) === false )
      throw new \Exception("zip: cannot add file <$fileName1WithPath>\n");

    $objWriter2 = \PHPExcel_IOFactory::createWriter($this->sheet2, 'Excel5');
    $filename2 = $uniqidName.'_excel_#2.xls';
    $fileName2WithPath = $filesPath.$filename2;
    $objWriter2->save($fileName2WithPath);

    if ( $zip->addFile($fileName2WithPath, $filename2) === false )
      throw new \Exception("zip: cannot add file <$fileName2WithPath>\n");

    $zip->close();
    return $zipFile;
  }

  private function getInvoiceNumber($member) {
    if ( $member->getFreeInvitation() )
      return '-free-invite-';

    $payment = \PaymentQuery::create()
      ->filterByMember($member)
      ->filterByStatus(\Payment::STATUS_EXECUTED)
      ->orderBy(\PaymentPeer::DATE, \Criteria::DESC)
      ->findOne();

    if ( !$payment )
      return '-no-invoice-';

    return $payment->getInvoiceNumber();
  }

  private function formatPrice($amount) {
    return number_format(
      $amount,
      2,
      ',',
      ''
    );
  }

  private function buildAddress(\Member $member) {
    return $member->getCountry().', '
      .$member->getZipCode().', '
      .$member->getCity().', '
      .$member->getStreet();

  }

}
