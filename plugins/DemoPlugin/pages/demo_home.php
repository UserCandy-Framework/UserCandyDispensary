<?php
/**
* Demo Plugin Home Page
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.3
*/


/** Collect Data page for view **/
$data['title'] = "Demo Demo";
$data['welcomeMessage'] = "Welcome to the Demo Home Page";

/** Setup Breadcrumbs **/
$data['breadcrumbs'] = "<li class='breadcrumb-item active'>Demo Home</li>";

/** Get data from Demo Function within Demo Class **/
$demo_output = $DemoModel->Demo();

?>

<div class="col">
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
