<form method="post" enctype="multipart/form-data">
  <input type="file" name="file" />
  <p><button type="submit" name="submit">Submit</button></p>
</form>

<?php
require_once 'vendor/autoload.php';
require_once 'config.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if (isset($_POST['submit'])) {

  $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel');

  if (isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {

    $arr_file = explode('.', $_FILES['file']['name']);
    $extension = end($arr_file);

    if ('csv' == $extension) {
      $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    } else {
      $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    }

    $spreadsheet = $reader->load($_FILES['file']['tmp_name']);

    $sheetData = $spreadsheet->getActiveSheet()->toArray();

    if (!empty($sheetData)) {
      for ($i = 1; $i < count($sheetData); $i++) {
        $external_product_name = $sheetData[$i][0];
        $external_product_variant = $sheetData[$i][1];
        $external_product_id = $sheetData[$i][2];
        $external_variant_id = $sheetData[$i][3];
        $quantity = $sheetData[$i][4];
        $recurring_price = $sheetData[$i][5];
        $charge_interval_unit_type = $sheetData[$i][6];
        $charge_interval_frequency = $sheetData[$i][7];
        $shipping_interval_unit_type = $sheetData[$i][8];
        $is_prepaid = $sheetData[$i][9];

        $db->query("INSERT INTO USERS(external_product_name, external_product_variant, external_product_id, external_variant_id, quantity, recurring_price, charge_interval_unit_type, charge_interval_frequency, shipping_interval_unit_type, is_prespaid ) 
                    VALUES('$external_product_name', '$external_product_variant', '$external_product_id', '$external_variant_id', '$quantity', '$recurring_price', ' $charge_interval_unit_type', '$charge_interval_frequency', ' $shipping_interval_unit_type', '$is_prepaid')");
      }
    }
    echo "Records inserted successfully.";
  } else {
    echo "Upload only CSV or Excel file.";
  }
}
?>