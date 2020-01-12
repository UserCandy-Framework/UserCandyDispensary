<?php
/**
* UserApplePie v4 Blog View Plugin Admin Category
*
* UserApplePie - Blog Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 1.0.0 for UAP v.4.3.0
*/

/** Blog Display Page View **/

use Core\Language;
use Helpers\{ErrorMessages,SuccessMessages,Form,Request,CurrentUserData,BBCode};

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
              <?php echo Form::input(array('type' => 'text', 'id' => 'title', 'name' => 'title', 'class' => 'form-control', 'value' => $data['blog_category'][0]->title, 'placeholder' => 'Blog Category Title', 'maxlength' => '255')); ?>
            </div>

            <!-- Blog Description -->
            <div class='input-group' style='margin-bottom: 25px'>
              <div class="input-group-prepend">
                <span class='input-group-text'><i class='fas fa-fw fa-book'></i> Description</span>
              </div>
              <?php echo Form::input(array('type' => 'text', 'id' => 'description', 'name' => 'description', 'class' => 'form-control', 'value' => $data['blog_category'][0]->description, 'placeholder' => 'Blog Category Description', 'maxlength' => '255')); ?>
            </div>

            <!-- CSRF Token -->
            <input type="hidden" id="token_blog" name="token_blog" value="<?php echo $data['csrf_token']; ?>" />
            <input type="hidden" id="id" name="id" value="<?php echo $data['blog_category'][0]->id; ?>" />
            <button class="btn btn-md btn-success" name="submit" type="submit" id="submit">
              <?php
                if(!empty($data['blog_category'][0]->id)){
                  echo "Update Category";
                }else{
                  echo "Create Category";
                }
              ?>
            </button>
            <!-- Close Form -->
            <?php echo Form::close(); ?>

          </div>
        </div>
