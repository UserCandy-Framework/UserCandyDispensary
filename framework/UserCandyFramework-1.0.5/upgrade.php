<?php

use Core\Dispenser;

/** Update List **/
$total_updates = "6";

/** Update 1 **/
if($update_num == "1"){
  $file_name = "class.ErrorLogger.php";
  $obj['details'] = "Copy {$file_name}";
  $dl_location = CUSTOMDIR."framework/$folder_location/new/"; // Temp Download Location
  $cp_location = ROOTDIR."system/core/"; // Where to copy the file to
  $bu_location = CUSTOMDIR."framework/$folder_location/backup/".$_SESSION['cur_datetime']."/files/"; // Backup Location
  /** Backup and Update File **/
  $run_file_update = Dispenser::updateFrameworkFile($dl_location,$cp_location,$bu_location,$file_name);
  if($run_file_update && file_exists($cp_location.$file_name)){
    $obj['update_num'] = "1";
    $obj['message'] = "Update {$file_name} Successful";
    $obj['status'] = "success";
  }else{
    $obj['update_num'] = "1";
    $obj['message'] = "Update {$file_name} Failed";
    $obj['status'] = "fail";
  }
}

/** Update 2 **/
if($update_num == "2"){
  $file_name = "helper.BBCode.php";
  $obj['details'] = "Copy {$file_name}";
  $dl_location = CUSTOMDIR."framework/$folder_location/new/"; // Temp Download Location
  $cp_location = ROOTDIR."system/helpers/"; // Where to copy the file to
  $bu_location = CUSTOMDIR."framework/$folder_location/backup/".$_SESSION['cur_datetime']."/files/"; // Backup Location
  /** Backup and Update File **/
  $run_file_update = Dispenser::updateFrameworkFile($dl_location,$cp_location,$bu_location,$file_name);
  if($run_file_update && file_exists($cp_location.$file_name)){
    $obj['update_num'] = "2";
    $obj['message'] = "Update {$file_name} Successful";
    $obj['status'] = "success";
  }else{
    $obj['update_num'] = "2";
    $obj['message'] = "Update {$file_name} Failed";
    $obj['status'] = "fail";
  }
}

/** Update 3 **/
if($update_num == "3"){
  $file_name = "helper.Csrf.php";
  $obj['details'] = "Copy {$file_name}";
  $dl_location = CUSTOMDIR."framework/$folder_location/new/"; // Temp Download Location
  $cp_location = ROOTDIR."system/helpers/"; // Where to copy the file to
  $bu_location = CUSTOMDIR."framework/$folder_location/backup/".$_SESSION['cur_datetime']."/files/"; // Backup Location
  /** Backup and Update File **/
  $run_file_update = Dispenser::updateFrameworkFile($dl_location,$cp_location,$bu_location,$file_name);
  if($run_file_update && file_exists($cp_location.$file_name)){
    $obj['update_num'] = "3";
    $obj['message'] = "Update {$file_name} Successful";
    $obj['status'] = "success";
  }else{
    $obj['update_num'] = "3";
    $obj['message'] = "Update {$file_name} Failed";
    $obj['status'] = "fail";
  }
}

/** Update 4 **/
if($update_num == "4"){
  $file_name = "helper.Url.php";
  $obj['details'] = "Copy {$file_name}";
  $dl_location = CUSTOMDIR."framework/$folder_location/new/"; // Temp Download Location
  $cp_location = ROOTDIR."system/helpers/"; // Where to copy the file to
  $bu_location = CUSTOMDIR."framework/$folder_location/backup/".$_SESSION['cur_datetime']."/files/"; // Backup Location
  /** Backup and Update File **/
  $run_file_update = Dispenser::updateFrameworkFile($dl_location,$cp_location,$bu_location,$file_name);
  if($run_file_update && file_exists($cp_location.$file_name)){
    $obj['update_num'] = "4";
    $obj['message'] = "Update {$file_name} Successful";
    $obj['status'] = "success";
  }else{
    $obj['update_num'] = "4";
    $obj['message'] = "Update {$file_name} Failed";
    $obj['status'] = "fail";
  }
}

/** Update 5 **/
if($update_num == "5"){
  $file_name = "model.AdminPanelModel.php";
  $obj['details'] = "Copy {$file_name}";
  $dl_location = CUSTOMDIR."framework/$folder_location/new/"; // Temp Download Location
  $cp_location = ROOTDIR."system/models/"; // Where to copy the file to
  $bu_location = CUSTOMDIR."framework/$folder_location/backup/".$_SESSION['cur_datetime']."/files/"; // Backup Location
  /** Backup and Update File **/
  $run_file_update = Dispenser::updateFrameworkFile($dl_location,$cp_location,$bu_location,$file_name);
  if($run_file_update && file_exists($cp_location.$file_name)){
    $obj['update_num'] = "5";
    $obj['message'] = "Update {$file_name} Successful";
    $obj['status'] = "success";
  }else{
    $obj['update_num'] = "5";
    $obj['message'] = "Update {$file_name} Failed";
    $obj['status'] = "fail";
  }
}

/** Update 6 **/
if($update_num == "6"){
  $obj['details'] = "Update Framework Version in Database to 1.0.5";
  $DBChain = new Core\DBChain();
  $update_data = $DBChain->start()
    ->update()
    ->table('settings')
    ->columns('setting_data')
    ->values('1.0.5')
  	->whereColumns('setting_title')
  	->whereValues('uc_version')
    ->run();
  if($update_data > 0){
    $obj['update_num'] = "6";
    $obj['message'] = "Update Framework Version in DB Successful";
    $obj['status'] = "success";
  }else{
    $obj['update_num'] = "6";
    $obj['message'] = "Update Framework Version in DB Failed";
    $obj['status'] = "fail";
  }
}





?>
