<?php
/** Get Current Page **/
$cp = $_SERVER['REQUEST_URI'];
/** Block adds on Auth Pages and Account Pages **/
$hideonpages = array('/Login','/Register','/Logout','/Activate','/Forgot-Password','/Reset-Password','/Resend-Activation-Email',
                '/Change-Email','/Change-Password','/Edit-Profile','/Edit-Profile-Images','/Privacy-Settings','/Account-Settings','/Devices');
if(!in_array($cp,$hideonpages)){
  $AdminPanelModel = new Models\AdminPanelModel();
  $data['adds_top'] = $AdminPanelModel->getSettings('adds_top');
}
if(!empty($data['adds_top'])){
?>
<?=$data['adds_top']?>
<?php } ?>
