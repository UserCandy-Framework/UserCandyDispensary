<?php
/**
* UserApplePie v4 Blog View Plugin Home
*
* UserApplePie - Blog Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 1.0.0
*/

/** Blog Display Page View **/

use Core\Language;
use Helpers\{ErrorMessages,SuccessMessages,Form,Request,CurrentUserData,BBCode,Csrf,Url};

/** Check to see if blog is selected for edit **/
if(!empty($blog_id) || !empty($_POST['id'])){
  $check_blog_id = empty($_POST['id']) ? $blog_id : Request::post('id');
  $blog_data = $BlogModel->getBlogDataEdit($check_blog_id);
  /** Check to see if user owns selected blog **/
  if($blog_data[0]->blog_owner_id != $auth->currentSessionInfo()['uid']){
    /** Blog does not belong to current user - Kick them out **/
    ErrorMessages::push('You do not own the selected Blog!', 'Blog');
  }
}

  $get_userName = CurrentUserData::getUserName($blog_data[0]->blog_owner_id);
  $get_userImage = CurrentUserData::getUserImage($blog_data[0]->blog_owner_id);
  /** Update and Get Views Data **/
  $PageViews = PageViews::views('true', $blog_data[0]->id, 'Blog', $auth->currentSessionInfo()['uid']);

/** Collect Data for view **/
if(!empty($blog_id)){
  $data['title'] = 'Edit Blog';
  $data['blog_id'] = $blog_id;
}else{
  $data['title'] = 'Create New Blog';
}
$data['site_description'] = 'Welcome to your new Blog editor.';

// Check for blog autosave
if(isset($_POST['blog_autosave'])){
  if($_POST['blog_autosave'] == "blog_autosave"){
    /** Blog Auto Save **/
    // Check to make sure the csrf token is good
    if (Csrf::isTokenValid('blog')) {
      /** Token Good **/
      $data['blog_title'] = Request::post('blog_title');
      $data['blog_content'] = Request::post('blog_content');
      $data['blog_category'] = Request::post('blog_category');
      $data['blog_description'] = Request::post('blog_description');
      $data['blog_keywords'] = Request::post('blog_keywords');
      if(!empty($blog_id)){
        $data['id'] = $blog_id;
      }else{
        $data['id'] = Request::post('id');
      }
      if(!empty($data['id'])){
        $update_blog = $BlogModel->updateSavedBlog($data['id'], $data['blog_title'], $data['blog_content'], $data['blog_description'], $data['blog_category'], $data['blog_keywords']);
        echo $data['id'];
      }else{
        /** New Forum Post - Create new post **/
        $new_topic = $BlogModel->sendBlog($auth->currentSessionInfo()['uid'], $data['blog_title'], $data['blog_content'], $data['blog_description'], $data['blog_category'], $data['blog_keywords'], "0");
        echo $new_topic;
      }
    }
  }
}else{

  /** Check to see if user is submitting a new blog post **/
  if(isset($_POST['submit'])){
    /** Check to make sure the csrf token is good **/
    if (Csrf::isTokenValid('blog')) {
      /** Token Good - Send Data to the Database **/
      $data['blog_title'] = Request::post('blog_title');
      $data['blog_content'] = Request::post('blog_content');
      $data['blog_category'] = Request::post('blog_category');
      $data['blog_description'] = Request::post('blog_description');
      $data['blog_keywords'] = Request::post('blog_keywords');
      $data['id'] = Request::post('id');
      /** Update the Selected Blog Data **/
      if($BlogModel->updateBlog($data['id'], $data['blog_title'], $data['blog_content'], $data['blog_description'], $data['blog_category'], $data['blog_keywords'], $auth->currentSessionInfo()['uid'])){
        /** Success Message **/
        SuccessMessages::push('You Have Successfully Published/Updated Your Blog', 'Blog/'.$data['id']);
      }
    }
  }

  $data['id'] = empty($data['id']) ? $data['blog_id'] : $data['id'];

  /** Create Token **/
  $data['csrf_token'] = Csrf::makeToken('blog');

  /** Setup Breadcrumbs **/
  $data['breadcrumbs'] = "
    <li class='breadcrumb-item'><a href='".SITE_URL."Blog'>".$data['blog_title']."</a></li>
    <li class='breadcrumb-item active'>".$data['title']."</li>
  ";

  /* Add Java Stuffs */
  $js .= "<script src='".Url::customPath('plugins', 'Blog')."js/blog_autosave.js'></script>";

?>

        <div class="col-lg-9 col-md-8">
          <div class="blog-post border rounded mb-4 shadow-sm p-2">
            <!-- Start Form -->
            <?php echo Form::open(array('method' => 'post', 'files' => '')); ?>

            <!-- Blog Title -->
            <div class='input-group' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
                <span class='input-group-text'><i class='fas fa-fw fa-book'></i> Title</span>
              </div>
              <?php echo Form::input(array('type' => 'text', 'id' => 'blog_title', 'name' => 'blog_title', 'class' => 'form-control', 'value' => $blog_data[0]->blog_title, 'placeholder' => 'Blog Title', 'maxlength' => '255')); ?>
            </div>

            <!-- Blog Description -->
            <div class='input-group' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
                <span class='input-group-text'><i class='fas fa-fw fa-book'></i> Description</span>
              </div>
              <?php echo Form::input(array('type' => 'text', 'id' => 'blog_description', 'name' => 'blog_description', 'class' => 'form-control', 'value' => $blog_data[0]->blog_description, 'placeholder' => 'Blog Description', 'maxlength' => '255')); ?>
            </div>

            <!-- Blog Category -->
            <div class='input-group' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
                <span class='input-group-text'><i class='fas fa-fw fa-book'></i> Category</span>
              </div>
              <select class='form-control' id='blog_category' name='blog_category'>
                <?php
                  if(!empty($data['blog_categories'])){
                    foreach ($data['blog_categories'] as $key => $value) {
                      echo "<option value='".$value->id."'";
                      if($blog_data[0]->blog_category == $value->id){echo " SELECTED ";}
                      echo ">".$value->title."</option>";
                    }
                  }
                ?>
              </select>
            </div>


            <!-- Blog Content -->
            <div class='input-group' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
                <span class='input-group-text'>
                  <!-- BBCode Buttons -->
                  <div class='btn-group-vertical'>
                    <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[b]','[/b]');"><i class='fas fa-bold'></i></button>
                    <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[i]','[/i]');"><i class='fas fa-italic'></i></button>
                    <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[u]','[/u]');"><i class='fas fa-underline'></i></button>
                    <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[youtube]','[/youtube]');"><i class='fab fa-youtube'></i></button>
                    <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[quote]','[/quote]');"><i class='fas fa-quote-right'></i></button>
                    <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[code]','[/code]');"><i class='fas fa-code'></i></button>
                    <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[url href=]','[/url]');"><i class='fas fa-link'></i></button>
                    <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[img]','[/img]');"><i class='fas fa-image'></i></button>
                  </div>
                </span>
              </div>
              <?php echo Form::textBox(array('type' => 'text', 'id' => 'blog_content', 'name' => 'blog_content', 'class' => 'form-control', 'value' => $blog_data[0]->blog_content, 'placeholder' => 'Blog Content', 'rows' => '20')); ?>
            </div>

            <!-- Blog Keywords -->
            <div class='input-group' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
                <span class='input-group-text'><i class='fas fa-fw fa-book'></i> Keywords</span>
              </div>
              <?php echo Form::input(array('type' => 'text', 'id' => 'blog_keywords', 'name' => 'blog_keywords', 'class' => 'form-control', 'value' => $blog_data[0]->blog_keywords, 'placeholder' => 'Blog Keywords', 'maxlength' => '255')); ?>
            </div>

            <!-- CSRF Token -->
            <input type="hidden" id="token_blog" name="token_blog" value="<?php echo $data['csrf_token']; ?>" />
            <input type="hidden" id="id" name="id" value="<?php echo $data['id']; ?>" />
            <button class="btn btn-md btn-success" name="submit" type="submit" id="submit">
              <?php
                if($blog_data[0]->blog_publish > 0){
                  echo "Update Blog";
                }else{
                  echo "Publish Blog";
                }
              ?>
            </button>
            <?php
              if(!empty($data['blog_id'])){
                echo " <a class='btn btn-sm btn-success float-right' href='".SITE_URL."Blog/EditBlogImages/".$data['blog_id']."/'>Edit Images</a> ";
              }
            ?>
            <!-- Close Form -->
            <?php echo Form::close(); ?>
            <div id="autoSave"></div>
            <div id="id"></div>

          </div>
        </div>
<?php
}
?>
