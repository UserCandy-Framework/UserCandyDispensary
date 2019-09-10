<?php
/**
* Helper Updater
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 1.0.0
*/

/** Check to make sure there is an update **/
if($xmlupdate->VERSION > $dispenser_db_data[0]->version){
  /** No DB Changes for this Widget - Update Version in DB only **/
  if($DispenserModel->updateDispenserVersion($dispenser_db_data[0]->id, $xmlupdate->VERSION)){
    $update_status = 'Success';
  }
}
