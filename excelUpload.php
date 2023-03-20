<?php


require('db_config.php');
include 'vendor/autoload.php';


if(isset($_POST['Submit'])){

//print_r($_FILES["file"]);die;
$allowed_extension = array('xls', 'csv', 'xlsx');
 $file_array = explode(".", $_FILES["file"]["name"]);
 $file_extension = end($file_array);


  if(in_array($file_extension, $allowed_extension))
  {
   $file_name = time() . '.' . $file_extension;
   move_uploaded_file($_FILES['file']['tmp_name'], $file_name);
   $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file_name);
   $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);
 
   $spreadsheet = $reader->load($file_name);
 
   unlink($file_name);
 
   //$data = $spreadsheet->getActiveSheet()->toArray();
   $highestRow = $spreadsheet->getActiveSheet()->getHighestRow();
    $startingRow = 7;
    //$branches_array = $spreadsheet->getActiveSheet()->rangeToArray('L'.$startingRow.':L'.$highestRow);
    $fee_collection_type_array = array("Academic","Academic Misc","Hostel","Hostel Misc","Transport","Transport Misc");
    $branches_array = array();
    $fee_catagory_array = array();
    $admno_arr = array();
    $roll_no_arr = array();
    for($row = $startingRow; $row <= $highestRow; ++$row){ 
      $branch_data = $spreadsheet->getActiveSheet()->getCell('L' . $row)->getValue();
      $fee_catagory_data = $spreadsheet->getActiveSheet()->getCell('K' . $row)->getValue();
      $fee_head = $spreadsheet->getActiveSheet()->getCell('Q' . $row)->getValue();
      $trans_id = $spreadsheet->getActiveSheet()->getCell('G' . $row)->getValue();
      $admno = $spreadsheet->getActiveSheet()->getCell('I' . $row)->getValue();
      $amount = $spreadsheet->getActiveSheet()->getCell('S' . $row)->getValue();
      $voucher_type = $spreadsheet->getActiveSheet()->getCell('F' . $row)->getValue();
      $cell = $spreadsheet->getActiveSheet()->getCell('B' . $row);
      $trandate_ex= $cell->getValue();
      if (PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($cell)) {
      $trandate = date_format(PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($trandate_ex),'Y-m-d');
      }else{
        $trandate = $trandate_ex;
      }
      $acadyear = $spreadsheet->getActiveSheet()->getCell('C' . $row)->getValue();
      $roll_no = $spreadsheet->getActiveSheet()->getCell('H' . $row)->getValue();
      $receipt_no = $spreadsheet->getActiveSheet()->getCell('P' . $row)->getValue();
      $sr_no = $spreadsheet->getActiveSheet()->getCell('A' . $row)->getValue();
      $alloted_catagory = $spreadsheet->getActiveSheet()->getCell('E' . $row)->getValue();
      $status = $spreadsheet->getActiveSheet()->getCell('J' . $row)->getValue();
      $program = $spreadsheet->getActiveSheet()->getCell('M' . $row)->getValue();
      $department = $spreadsheet->getActiveSheet()->getCell('N' . $row)->getValue();
      $batch = $spreadsheet->getActiveSheet()->getCell('O' . $row)->getValue();


////// all_data table insert //////
      $temp_sql = "INSERT INTO `temp_all_data`(`sr_no`, `date`, `academic_year`, `session`, `alloted_catagory`, `voucher_type`, `voucher_no`, `roll_no`, `admno`, `status`, `fee_catagory`, `branch`, `program`, `department`, `batch`, `recipt_no`, `fee_head`, `amount`) VALUES ($sr_no,'$trandate','$acadyear','$acadyear','$alloted_catagory','$voucher_type',$trans_id,'$roll_no','$admno','$status','$fee_catagory_data','$branch_data','$program','$department','$batch','$receipt_no','$fee_head',$amount)";
      $mysqli->query($temp_sql);
      ////// branch table insert //////
      if(!in_array($branch_data,$branches_array)){
      $query = "insert into branches (branch_name) values('".$branch_data."')";
      if($mysqli->query($query)){
        $branch_id = $mysqli->insert_id;
        $branches_array[$branch_id] = $branch_data;
      }
      //////// feecollectiontype table insert ////////
      foreach($fee_collection_type_array as $fct_arrr){
        $fct_query = "insert into feecollectiontype (collectionhead,collectiondesc,br_id) values('".$fct_arrr."','".$fct_arrr."','".$branch_id."')";
        $mysqli->query($fct_query);
        $fct_id = $mysqli->insert_id;
        }
    }
    //////// feecategory table insert ////////
    $br_id = array_search($branch_data,$branches_array);
    $chk_fct = "select id from feecategory where fee_category = '".$fee_catagory_data."' and br_id = '".$br_id."'";
    $chk_rows = $mysqli->query($chk_fct);
    if($chk_rows->num_rows ==0){
        $fee_query = "insert into feecategory (fee_category,br_id) values('".$fee_catagory_data."','".$br_id."')";
        $mysqli->query($fee_query);
        $fc_array['id'] = $mysqli->insert_id;
        $fc_array['fee_category'] = $fee_catagory_data;
        $fc_array['br_id'] = $br_id;
        $fee_catagory_array[] = $fc_array;
    }

    //////// feecollectiontype table insert ////////
    if (strpos($fee_head,'fine') !== false) {
      $collectionhead = 'Academic Misc';
      $moduleid = 11;
  }elseif(strpos($fee_head,'Mess') !== false){
    $collectionhead = 'Hostel';
    $moduleid = 2;
  }else{
    $collectionhead = 'Academic';
    $moduleid = 1;
  }

  $chk_fct = "select id from feecategory where fee_category = '".$fee_catagory_data."' and br_id = '".$br_id."'";
  $chk_rows = $mysqli->query($chk_fct);
  $fctid_data = $chk_rows->fetch_assoc();
  $fct_id = $fctid_data['id'];

  $chk_fct = "select id from feecollectiontype where collectionhead = '".$collectionhead."' and br_id = '".$br_id."'";
  $chk_rows = $mysqli->query($chk_fct);
  $fctid_data = $chk_rows->fetch_assoc();
  $fee_collection_id = $fctid_data['id'];

  //////// feetypes table insert ////////
  $seq_arr = array();
  $i = 1;
  if(in_array($fee_head,$seq_arr)){
    $seq_id = array_search($fee_head,$seq_arr);
  }else{
    $seq_id = $i;
    $i++;
  }
    $feetypes_sql = "INSERT INTO `feetypes`(`fee_category`, `f_name`, `collection_id`, `br_id`, `seq_id`, `fee_type_ledger`, `fee_head_type`) VALUES ($fct_id,'$fee_head',$fee_collection_id,$br_id,$seq_id,'$fee_head',$moduleid)";
    $mysqli->query($feetypes_sql);
    $fee_types_id = $mysqli->insert_id;

    //////// financial_trans table insert ////////
    $csql = "select id,crdr,entrymodeno from entrymode where entry_modename = '".$voucher_type."'";
    $c_data = $mysqli->query($csql);
    $em_data = $c_data->fetch_assoc();
    $em_id = $em_data['id'];
    $crdr = $em_data['crdr'];
    $entrymodeno = $em_data['entrymodeno'];
    if($voucher_type == 'concession'){
      $concession_type = 1;
    }elseif($voucher_type == 'scholarship'){
      $concession_type = 2;
    }else{
      $concession_type = NULL;
    }

    if(!in_array($admno,$admno_arr)){
    $financial_trans_sql = "INSERT INTO `financial_trans`(`moduleid`, `tranid`, `admno`, `crdr`, `trandate`, `acadyear`, `entrymode`, `voucherno`, `brid`, `type_of_concession`) VALUES ($moduleid,'$trans_id','$admno','$crdr','$trandate','$acadyear',$entrymodeno,'$trans_id',$br_id,'$concession_type')";
    
    $mysqli->query($financial_trans_sql);
    $financial_trans_id = $mysqli->insert_id;
    $admno_arr[] = $admno;
    }else{
    $get_t_id_sql = "select id from financial_trans where admno = '$admno'";
    $tid_data = $mysqli->query($get_t_id_sql);
    $t_id_data = $tid_data->fetch_assoc();
    $financial_trans_id = $t_id_data['id'];
    }

    $trans_detail_sql = "INSERT INTO `financial_transdetail`(`financialtranid`, `moduleid`, `amount`, `headid`, `crdr`, `br_id`, `head_name`) VALUES ($financial_trans_id,$moduleid,$amount,$fee_types_id,'$crdr',$br_id,'$fee_head')";
    $mysqli->query($trans_detail_sql);
    $trans_detail_id = $mysqli->insert_id;

    $get_amt_sql = "select sum(amount) as tot_amt from financial_transdetail where financialtranid = $financial_trans_id";
    $amt_data = $mysqli->query($get_amt_sql);
    $amnt_data = $amt_data->fetch_assoc();
    $amnt = $amnt_data['tot_amt'];

    $updt_sql = "UPDATE `financial_trans` SET `amount`=$amnt WHERE id = $financial_trans_id";
    $mysqli->query($updt_sql);

    //////// common_fee_collection table insert ////////
    if($voucher_type == 'RCPT'){
      $inactive = 0;
    }elseif($voucher_type == 'REVRCPT'){
      $inactive = 1;
    }elseif($voucher_type == 'JV'){
      $inactive = 0;
    }elseif($voucher_type == 'RevJV'){
      $inactive = 1;
    }elseif($voucher_type == 'PMT'){
      $inactive = 0;
    }elseif($voucher_type == 'REVPMT'){
      $inactive = 1;
    }else{
      $inactive = NULL;
    }

    if(!in_array($roll_no,$roll_no_arr)){
    $cfc_sql = "INSERT INTO `common_fee_collection`(`moduleid`, `transid`, `admno`, `rollno`, `brid`, `acadamicyear`, `financialyear`, `displayreciptno`, `entrymode`, `paid_date`, `inactive`) VALUES ($moduleid,'$trans_id','$admno','$roll_no',$br_id,'$acadyear','$acadyear','$receipt_no',$entrymodeno,'$trandate','$inactive')";
    $mysqli->query($cfc_sql);
    $cfc_id = $mysqli->insert_id;
    $roll_no_arr[] = $roll_no;
    }else{
      $get_t_id_sql = "select id from common_fee_collection where rollno = '$roll_no'";
      $cid_data = $mysqli->query($get_t_id_sql);
      $t_id_data = $cid_data->fetch_assoc();
      $cfc_id = $t_id_data['id'];
      }
    $cfch_sql = "INSERT INTO `common_fee_collection_headwise`(`moduleid`, `receiptid`, `headid`, `headname`, `brid`, `amount`) VALUES ($moduleid,$cfc_id,$fee_types_id,'$fee_head',$br_id,$amount)";
    $mysqli->query($cfch_sql);

    $get_amt_sql = "select sum(amount) as tot_amt from common_fee_collection_headwise where receiptid = $cfc_id";
    $amt_data = $mysqli->query($get_amt_sql);
    $amnt_data = $amt_data->fetch_assoc();
    $amnt = $amnt_data['tot_amt'];

    $updt_sql = "UPDATE `common_fee_collection` SET `amount`=$amnt WHERE id = $cfc_id";
    $mysqli->query($updt_sql);
     
  }
    echo "Data uploaded successfully";
  }else{
  echo "File type is not accepted!";
  }

}


?>