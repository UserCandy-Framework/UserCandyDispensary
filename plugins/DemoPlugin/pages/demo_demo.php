<?php
/**
* Demo Plugin Home Page
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 1.0.0
*/


/** Collect Data page for view **/
$data['title'] = "Demo Demo";
$data['welcomeMessage'] = "Welcome to the Demo Demo Page";

/** Setup Breadcrumbs **/
$data['breadcrumbs'] = "<li class='breadcrumb-item'><a href='".SITE_URL."Demo'>Demo</a></li><li class='breadcrumb-item active'>Demo</li>";

/** Get data from Demo Function within Demo Class **/
$demo_output = $DemoModel->Demo();

?>

<div class="col-lg-6 col-md-4 col-sm-12">
	<div class="card mb-3">
		<div class="card-header h4">
			<?=$data['title']?>
		</div>
		<div class="card-body">
			<?=$data['welcomeMessage']?>
			<hr>
			<?=$demo_output?>
		</div>
  </div>
</div>
