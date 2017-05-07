<?php

namespace Tbmt;

class MasspayExcel {

  private $transferByMemberVersion = [];

  public function __construct() {
    $this->sheet = new \PHPExcel();
    $this->sheet->getProperties()->setCreator("Betterliving")
       ->setLastModifiedBy("Betterliving")
       ->setTitle("Betterliving Mass Payouts")
       ->setSubject("Betterliving Mass Payouts")
       // ->setKeywords("office PHPExcel php")
       // ->setCategory("Test result file")
       ->setDescription("Betterliving Mass Payouts");

    $this->aTab = $this->sheet->setActiveSheetIndex(0);
    $this->currentRow = 1;

    $this->addHeader();
  }

  public function addHeader() {
    $row = 1;

    $columns = [
      'Row number',
      'Beneficiary`s name',
      'Beneficiary`s address',
      'IBAN/Account number',
      'SWIFT/BIC Bank',
      'Bank address/Country',
      'Bank address',
      'Amount',
      'Currency',
      'Reason for payment',
      'Costs',
      'Correspondent bank',
      'Correspondent bank SWIFT/BIC',
      'Correspondent bank address'
    ];

    $columnName = 'A';
    foreach ( $columns as $index => $column ) {
      $this->aTab->setCellValue($columnName.$row, $column);
      $columnName++;
    }
  }

  public function addTransfer(\Transfer $transfer, $currency, $configReference) {
    $this->currentRow++;

    $member = $transfer->getMember();
    $memberReference = $member->getNum();
    $memberReference = "$configReference $memberReference";

    $memberProfileVersion = $member->getId().'-'.$member->getProfileVersion();

    if ( isset($this->transferByMemberVersion[$memberProfileVersion]) ) {
      // there is an entry for the member already, just update the amount
      $rowValues = $this->transferByMemberVersion[$memberProfileVersion];
      $rowNumber = $rowValues[0];
      $amount = $rowValues[1];

      $amount += $transfer->getAmountSum();

      $this->aTab->setCellValue('H'.$rowNumber, $this->formatPrice($amount));

      // update amount
      $this->transferByMemberVersion[$memberProfileVersion] = [
        $rowNumber,
        $amount
      ];

      // no new row filled
      $this->currentRow--;
      return;
    }

    $this->transferByMemberVersion[$memberProfileVersion] = [
      $this->currentRow,
      $transfer->getAmountSum()
    ];

    $columns = [
      $this->currentRow-1, # 'Row number',
      $member->getBankRecipient(), # 'Beneficiary`s name',
      $this->buildAddress($member), # 'Beneficiary`s address',
      $member->getIban(), # 'IBAN/Account number',
      $member->getBic(), # 'SWIFT/BIC Bank',
      '', # 'Bank address/Country',
      '', # 'Bank address',
      $this->formatPrice($transfer->getAmountSum()), # 'Amount',
      $currency, # 'Currency',
      $memberReference, # 'Reason for payment',
      'SHA', # 'Costs',
      '', # 'Correspondent bank',
      '', # 'Correspondent bank SWIFT/BIC',
      '', # 'Correspondent bank address'
    ];

    $columnName = 'A';
    foreach ( $columns as $index => $column ) {
      $this->aTab->setCellValue($columnName.$this->currentRow, $column);
      $columnName++;
    }
  }

  public function save() {
    $objWriter = \PHPExcel_IOFactory::createWriter($this->sheet, 'Excel2007');

    $filename = (new \DateTime())->format('Y-m-d_H-i-s').'_'.uniqid().'.xlsx';
    $objWriter->save(
      Config::get('payout.files.dir').$filename
    );

    return $filename;
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
