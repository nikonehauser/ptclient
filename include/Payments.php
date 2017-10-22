<?php

namespace Tbmt;

class Payments {

  const ROW_DATA_BEGIN = 1;
  const COL_AMOUNT = 2;
  const COL_REFERENCE = 4;

  static public function excelToArr($filename) {
    $inputFileType = \PHPExcel_IOFactory::identify($filename);
    $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($filename);

    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();

    $data = $sheet->rangeToArray('A1:'.$highestColumn.$highestRow, null, true, false);

    return $data;
  }

  static public function processData($filename, $execMember, $doExecImport = false) {
    $data = self::excelToArr($filename);

    $memberFee = Config::get('member_fee', TYPE_FLOAT);

    $totalRows = count($data);
    $alreadyPaidRows = 0;
    $validRows = 0;
    $executedRows = [];
    $invalidRows = [];
    $exceptionRows = [];

    $con = \Propel::getConnection();

    for ( $i = self::ROW_DATA_BEGIN; $i < count($data); $i++ ) {
      $row = $data[$i];

      $amount = isset($row[self::COL_AMOUNT]) ? floatval($row[self::COL_AMOUNT]) : '<empty>';
      $reference = isset($row[self::COL_REFERENCE]) ? intval($row[self::COL_REFERENCE]) : '';

      if ( empty($reference) ) {
        $member = false;
        $reference = '&lt;empty&gt;';
      } else
        $member = \Member::getByNum($reference, false);

      $errors = [];
      if ( !$member )
        $errors[] = 'Can not find member: '.$reference;

      if ( $amount !== $memberFee ) {
        $errors[] = 'Invalid amount: '.$amount;
      }

      if ( !empty($errors) ) {
        $invalidRows[] = [
          'row' => $i+1,
          'errors' => $errors,
          'amount' => $amount,
          'reference' => $reference,
        ];

        continue;
      }

      if ( $member->isMarkedAsPaid() ) {
        $alreadyPaidRows++;
        continue;
      }

      $validRows++;
      if ( $doExecImport !== true )
        continue;

      if ( !$con->beginTransaction() )
        throw new Exception('Could not begin transaction');

      try {
        $member->setHadPaid(\Payment::TYPE_SETBYADMINIMPORT, $con);

        if ( !$con->commit() )
          throw new Exception('Could not commit transaction');

        $executedRows[] = [
          'row' => $i+1,
          'amount' => $amount,
          'reference' => $reference,
          'member' => $member->getId()
        ];

      } catch (Exception $e) {
          $con->rollBack();
          $exceptionRows[] = [
            'row' => $i+1,
            'errors' => $e->toString(),
            'amount' => $amount,
            'reference' => $reference,
          ];
      }
    }

    $results = [
      'totalRows' => $totalRows,
      'invalidRows' => $invalidRows,
      'alreadyPaidRows' => $alreadyPaidRows,
      'validRows' => $validRows,

      'executedRowsCount' => count($executedRows),
      'executedRows' => $executedRows,

      'exceptionRowsCount' => count($exceptionRows),
      'exceptionRows' => $exceptionRows,
    ];

    if ( $doExecImport === true && $validRows > 0 ) {
      $paymentImport = new \PaymentImport();
      $paymentImport
        ->setFilename(basename($filename))
        ->setMeta(json_encode($results))
        ->setCreationDate(time())
        ->setMember($execMember)
        ->save($con);

      $results[\Activity::ARR_RELATED_RETURN_KEY] = $paymentImport;
    }

    return $results;
  }
}
