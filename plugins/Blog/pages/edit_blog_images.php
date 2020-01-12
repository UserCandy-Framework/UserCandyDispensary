<?php
/**
* UserApplePie v4 Blog View Plugin Edit Images
*
* UserApplePie - Blog Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 1.0.0 for UAP v.4.3.0
*/

use Core\Language, Helpers\Form;
?>

<div class="col-lg-9 col-md-8 col-sm-12">
	<div class="card mb-3">
		<div class="card-header h4">
			<?=$title;?>
      <?php echo " <a class='btn btn-sm btn-success float-right' href='".SITE_URL."CreateBlog/".$data['blog_data'][0]->id."/'>Edit Blog</a> "; ?>
		</div>
		<div class="card-body">
        <div class="col-xs-12">
            <form role="form" method="post" enctype="multipart/form-data">
								<div class="input-group mb-3">
								  <div class="input-group-prepend">
								    <span class="input-group-text" id="inputGroupFileAddon01"><?=Language::show('members_profile_new_photo', 'Members'); ?></span>
								  </div>
								  <div class="custom-file">
										<input type="file" class="custom-file-input" accept="image/jpeg, image/gif, image/x-png" id="blogPic" name="blogPic[]" aria-describedby="inputGroupFileAddon01" multiple="multiple">
								    <label class="custom-file-label" for="inputGroupFile01">Select Image Files</label>
								  </div>
								</div>

                <input type="hidden" name="token_blog" value="<?=$csrf_token;?>" />
                <input type="submit" name="submit" class="btn btn-primary" value="<?=Language::show('edit_profile_images_button', 'Members'); ?>">
            </form>
        </div>
    </div>
  </div>

	<?php if($data['main_image'] != ""){ ?>
		<input id="oldImg" name="oldImg" type="hidden" value="<?php echo $data['main_image']; ?>"">
		<div class="card mb-3">
			<div class="card-header">
				<?=Language::show('members_profile_cur_photo', 'Members'); ?>
			</div>
			<div class="card-body text-center">
				<img alt="Blog Pic" src="<?php echo SITE_URL.IMG_DIR_BLOG.$data['main_image']; ?>" class="rounded img-fluid">
			</div>
		</div>
	<?php } ?>

	<div class="card mb-3">
		<div class="card-header h4">
			Images for: <?php echo $data['blog_data'][0]->blog_title; ?>
		</div>
		<div class="card-body">
				<div class='row'>
					<?php
						if(isset($data['blog_images'])){
							foreach ($data['blog_images'] as $row) {
								echo "<div class='col-lg-2 col-md-3 col-sm-4 col-xs-6' style='padding-bottom: 6px'>";
									echo "<a href='".SITE_URL."EditBlogImages/".$data['blog_data'][0]->id."/$row->id'><img src='".SITE_URL.IMG_DIR_BLOG.$row->blogImage."' class='img-thumbnail'></a>";
								echo "</div>";
							}
						}
					?>
				</div>
		</div>
		<?php
			// Check to see if there is more than one page
			if($data['pageLinks'] > "1"){
				echo "<div class='card-footer text-muted' style='text-align: center'>";
				echo $data['pageLinks'];
				echo "</div>";
			}
		?>
	</div>
</div>
