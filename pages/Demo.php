<?php 
/** Set Meta Data **/
$meta['title'] = "Demo Page Title";
$meta['description'] = "This is the Demo Page Description";
$meta['keywords'] = "Demo, Page, Test, UserCandy";
$meta['image'] = "https://www.usercandy.com/UserCandyLogo.png";
/** Set Page Breadcrumbs **/
$data['breadcrumbs'] = "<li class='breadcrumb-item active'>".$meta['title']."</li>";
/** Get data from URL **/
(empty($viewVars[0])) ? $argument1 = "" : $argument1 = $viewVars[0];
?>

<div class="col-12">
	<div class="card mb3">
		<div class="card-header h4">
			<?=$meta['title']?>
		</div>
		<div class="card-body">
			<?=$argument1?>
		</div>
	</div>
</div>