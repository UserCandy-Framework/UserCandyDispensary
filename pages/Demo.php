<?php

use Helpers\SiteStats;
use Helpers\Url;

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

<?php

$forum_title = "UserCandy Framework 1.0.4 - Security Improvements";

$forum_url = Url::generateSafeSlug($forum_title);

echo "<hr>";
echo "$forum_title <br>";
echo "$forum_url";
echo "<hr>";

?>

		</div>
		<div class="card-footer d-flex justify-content-center">
			<div class="col-sm-12 col-md-10 col-lg-8">
				<?=CommentsHelper::displayComments()?>
			</div>
		</div>
	</div>
</div>
