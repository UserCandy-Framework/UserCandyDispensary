<?php

/** Get data from URL **/
(empty($viewVars[0])) ? $file = "" : $file = $viewVars[0];

$title = "Downloads";
$welcomeMessage = "Welcome to UserCandy Framework Downloads";

/** Setup Breadcrumbs **/
$data['breadcrumbs'] = "
  <li class='breadcrumb-item active'>".$title."</li>
";

if(!empty($file)){
  /** Check to make sure that the file exist **/
  $file_loc = "../assets/downloads/usercandy/".$file;
  if(file_exists($file_loc)){
    $downloadfilename = $file;
    $filesuccesserror = "success";
    $filestatusmessage = "Thank You For Downloading $file!";
    /** Update The Downloads Count **/
    $download = new Downloads();
    $botchecker = Downloads::is_bot();
    $count = $download->getDownloadTotal($file);
    if($count > 0 && $botchecker == false){
      $count = $count + 1;
      $download->addDownload($file, $count);
    }else{
      $download->addFile($file);
    }
    // TODO add a bot checker
  }else{
    $downloadfilename = $file;
    $filesuccesserror = "danger";
    $filestatusmessage = "<b>Error!</b> The Requested File: $file Does Not Exist!";
  }
?>

<div class="col">
	<div class="card mb-3">
		<div class="card-header h4">
			<h1><?=$title;?></h1>
		</div>
		<div class="card-body">
			<p><?=$welcomeMessage;?></p>
				<div class="alert alert-<?=$filesuccesserror?>" role="alert"><?=$filestatusmessage?></div>

				<?php
					if($filesuccesserror == "success"){
						echo "<iframe width='1' height='1' frameborder='0' src='".SITE_URL."assets/downloads/usercandy/".$downloadfilename."'></iframe>";
						echo "If the file does not download automatically, <a href='".SITE_URL."assets/downloads/usercandy/".$downloadfilename."' target='_blank'>Click Here To Download</a>";
					}
				 ?>
		</div>
	</div>
</div>


<?php
}else{
?>

<style type="text/css">
.title {
	font-size: 16px;
	font-weight: bold;
}
pre {
	font-size: 14px;
	font-weight: normal;
  padding: 4px;
}
.code {
	font-size: 12px;
	color: #790000;
	padding: 2px;
	border-radius: 5px;
	background-color: #F90;
}
</style>
<div class="col">
	<div class="card mb-3">
		<div class="card-header h4">
			<h1><?=$title;?></h1>
		</div>
		<div class="card-body">
			<p><?=$welcomeMessage;?></p>
			<?php
				echo "<h3>UserCandy 1 Versions</h3>";
        echo "<hr>";

				echo "<pre> <b class='title'>UserCandy Framework v1.0.3</b> (12-19-19) ";
				echo "<Br>&emsp;<a href='".SITE_URL."Downloads/UserCandy-1.0.3.zip' class='btn btn-primary btn-xs'>Download</a>";
				echo " <span class='label label-info'>";
				echo Downloads::getDownloadCount('UserCandy-1.0.3.zip');
				echo " Downloads</span>";
				echo " <span class='label label-info'>";
				echo Downloads::getFileSize('UserCandy-1.0.3.zip', ROOTDIR."assets/downloads/usercandy/");
				echo " </span></pre>";

        echo "<hr>";

        echo "<pre> <b class='title'>UserCandy Framework v1.0.2</b> (12-15-19) ";
				echo "<Br>&emsp;<a href='".SITE_URL."Downloads/UserCandy-1.0.2.zip' class='btn btn-primary btn-xs'>Download</a>";
				echo " <span class='label label-info'>";
				echo Downloads::getDownloadCount('UserCandy-1.0.2.zip');
				echo " Downloads</span>";
				echo " <span class='label label-info'>";
				echo Downloads::getFileSize('UserCandy-1.0.2.zip', ROOTDIR."assets/downloads/usercandy/");
				echo " </span></pre>";

			?>
		</div>
	</div>
</div>


<?php
}
?>
