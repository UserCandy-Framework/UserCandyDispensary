<?php
/**
* UserApplePie v4 Blog View Plugin Edit Images
*
* UserApplePie - Blog Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 1.0.0 for UAP v.4.3.0
*/

use Core\Language;
use Helpers\{Form,SuccessMessages,ErrorMessages,Csrf,Request,SimpleImage};

/** Check to see if blog is selected for edit **/
      if(!$auth->isLogged()){
          /** Blog does not belong to current user - Kick them out **/
          ErrorMessages::push('You Must be Logged In!', 'Login');
      }

      /** Check is user is Admin **/
      $data['isAdmin'] = $usersModel->checkIsAdmin($auth->user_info());

      /** Get Blog Settings Data **/
      $data['blog_title'] = $AdminPanelModel->getSettings('blog_title');
      $data['blog_description'] = $AdminPanelModel->getSettings('blog_description');
      $data['blog_max_image_size'] = $AdminPanelModel->getSettings('blog_max_image_size');

      /** Collect Data for view **/
      if(empty($blog_id)){
        /** Blog does not belong to current user - Kick them out **/
        ErrorMessages::push('You do not own the selected Blog!', 'MyBlogs');
      }

      /** Check to see if blog is selected for edit **/
      if(!empty($blog_id)){
        $data['blog_data'] = $BlogModel->getBlogDataEdit($blog_id);
        /** Check to see if user owns selected blog **/
        if($data['blog_data'][0]->blog_owner_id != $auth->currentSessionInfo()['uid']){
          /** Blog does not belong to current user - Kick them out **/
          ErrorMessages::push('You do not own the selected Blog!', 'Blog');
        }else{
          $u_id = $auth->currentSessionInfo()['uid'];
        }
      }

      $data['title'] = 'Blog Images - '.$data['blog_data'][0]->blog_title;
      $data['site_description'] = 'Welcome to your Blog Images editor.';

      $main_image = $BlogModel->getBlogImageMain($blog_id);

      /** Get Users Images **/
      $data['blog_images'] = $BlogModel->getBlogImages($blog_id, $pages->getLimit($current_page, USERS_PAGEINATOR_LIMIT));

      // Set total number of rows for paginator
      $total_num_images = $BlogModel->getTotalImages($blog_id);
      $pages->setTotal($total_num_images);

      // Send page links to view
      $pageFormat = SITE_URL."EditBlogImages/$blog_id/"; // URL page where pages are
      $data['pageLinks'] = $pages->pageLinks($pageFormat, '', $current_page);
      $data['current_page_num'] = $current_page;

      /** Create Token **/
      $data['csrf_token'] = Csrf::makeToken('blog');

      /** Get Blog Categories **/
      $data['blog_categories'] = $BlogModel->getBlogCategories();

      /** Get Blog Archives **/
      $data['blog_archives'] = $BlogModel->getBlogArchives();

          if (isset($_POST['submit'])) {
						// Check to make sure the csrf token is good
						if (Csrf::isTokenValid('blog')) {
							// New Topic Successfully Created Now Check if User is Uploading Image
							// Check for image upload with this Blog
							/** Ready site to upload Files TOPIC **/
							if(!empty($_FILES['blogImage']['name'][0])){
								$countfiles = count($_FILES['blogImage']['name']);
								for($i=0;$i<$countfiles;$i++){
									// Check to see if an image is being uploaded
									if(!empty($_FILES['blogImage']['tmp_name'][$i])){
											$picture = file_exists($_FILES['blogImage']['tmp_name'][$i]) || is_uploaded_file($_FILES['blogImage']['tmp_name'][$i]) ? $_FILES ['blogImage']['tmp_name'][$i] : array ();
											if($picture != ""){
													// Get file size for db
													$check = getimagesize ( $picture );
													$img_dir_blog = IMG_DIR_BLOG;
													// Check to make sure image is good
													if($check['size'] < 5000000 && $check && ($check['mime'] == "image/jpeg" || $check['mime'] == "image/png" || $check['mime'] == "image/gif")){
															// Check to see if Img Upload Directory Exists, if not create it
															if(!file_exists(ROOTDIR.$img_dir_blog)){
																mkdir(ROOTDIR.$img_dir_blog,0777,true);
															}
															// Format new image and upload it to server
															$image = new SimpleImage($picture);
															$new_image_name = "blog-image-uid{$u_id}-bid{$blog_id}";
															$rand_string = substr(str_shuffle(md5(time())), 0, 10);
															$img_name = $new_image_name.'-'.$rand_string.'.gif';
															$img_max_size = explode(',', $blog_max_image_size);
															$image->best_fit($img_max_size[0],$img_max_size[1])->save(ROOTDIR.$img_dir_blog.$img_name);
															// Make sure image was Successfull
															if($img_name){
																// Add new image to database
																if($model->addBlogImage($u_id, $blog_id, $img_name, $img_dir_blog, $file_size)){
																	/** Success Message Display **/
                      						SuccessMessages::push('Successfully Uploaded Blog Images', 'Blog/EditBlogImages/'.$blog_id.'/');
																}else{
																	/** Error Message Display **/
										              ErrorMessages::push('Error Uploading Blog Images', 'Blog/EditBlogImages/'.$blog_id.'/');
																}
															}
													}else{
														/* Error Message Display */
						                ErrorMessages::push('Image was NOT uploaded because the file size was too large!', 'Blog/EditBlogImages/'.$blog_id.'/');
													}
											}else{
												/* Error Message Display */
				                ErrorMessages::push('No Image Selected to Be Uploaded', 'Blog/EditBlogImages/'.$blog_id.'/');
											}
									}else{
										/* Error Message Display */
										ErrorMessages::push('No Image Selected to Be Uploaded', 'Blog/EditBlogImages/'.$blog_id.'/');
									}
								}
							}else{
								/* Error Message Display */
                ErrorMessages::push('No Image Selected to Be Uploaded', 'Blog/EditBlogImages/'.$blog_id.'/');
							}
						}else{
              /** Error Message Display **/
              ErrorMessages::push('Error Uploading Blog Images - Token Error', 'Blog/EditBlogImages/'.$blog_id.'/');
            }

          }

          /** Get user data **/
          $data['main_image'] = $main_image;

          /** Setup Breadcrumbs **/
          $data['breadcrumbs'] = "
            <li class='breadcrumb-item'><a href='".SITE_URL."Blog'>".$data['blog_title']."</a></li>
            <li class='breadcrumb-item'><a href='".SITE_URL."Blog/MyBlogs'>MyBlogs</a></li>
            <li class='breadcrumb-item active'>".$data['title']."</li>
          ";

?>

<div class="col-lg-9 col-md-8 col-sm-12">
	<div class="card mb-3">
		<div class="card-header h4">
			<?=$title;?>
      <?php echo " <a class='btn btn-sm btn-success float-right' href='".SITE_URL."Blog/CreateBlog/".$data['blog_data'][0]->id."/'>Edit Blog</a> "; ?>
		</div>
		<div class="card-body">
        <div class="col-xs-12">
            <form role="form" method="post" enctype="multipart/form-data">
								<div class="input-group mb-3">
								  <div class="input-group-prepend">
								    <span class="input-group-text" id="inputGroupFileAddon01"><?=Language::show('members_profile_new_photo', 'Members'); ?></span>
								  </div>
								  <div class="custom-file">
										<input type="file" class="custom-file-input" accept="image/jpeg, image/gif, image/x-png" id="blogImage" name="blogImage[]" aria-describedby="inputGroupFileAddon01" multiple="multiple">
								    <label class="custom-file-label" for="inputGroupFile01">Select Image Files</label>
								  </div>
								</div>

                <input type="hidden" name="token_blog" value="<?=$data['csrf_token'];?>" />
                <input type="submit" name="submit" class="btn btn-primary" value="Upload Blog Images">
            </form>
        </div>
    </div>
  </div>

	<?php if($data['main_image'] != ""){ ?>
		<input id="main_image" name="main_image" type="hidden" value="<?php echo $data['main_image']; ?>"">
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
