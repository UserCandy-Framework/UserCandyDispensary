<?php
/**
* UserApplePie v4 Blog View Plugin Home
*
* UserApplePie - Blog Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 1.0.0 for UAP v.4.3.0
*/

/** Blog Display Page View **/

use Core\Language;
use Helpers\{ErrorMessages,SuccessMessages,Form,Request,CurrentUserData,BBCode};

?>

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .blog-post-title{
        font-weight: bold;
      }

      .blog-post-meta{
        font-style: italic;
      }
    </style>
<div class='col-lg-9 col-md-8'>

    <div class="row justify-content-md-center">
      <div class="col-md-12 blog-main">

        <?php
          if(!empty($data['blogs_data'])){
            foreach ($data['blogs_data'] as $key => $value) {
              $get_userName = CurrentUserData::getUserName($value->blog_owner_id);
              $get_userImage = CurrentUserData::getUserImage($value->blog_owner_id);
        ?>
              <div class="blog-post border rounded mb-4 shadow-sm p-4">
                <h2 class="blog-post-title"><?php echo $value->blog_title; ?></h2>
                <p class="blog-post-meta">
                  <?php echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$get_userImage." class='rounded' style='height: 25px'>"; ?>
                  Posted by
                  <a href="<?php echo SITE_URL."Profile/$get_userName/"; ?>"><?php echo $get_userName; ?></a>
                  on <?php echo date("F d, Y", strtotime($value->timestamp)); ?></span>
                </p>
                  <p class="mb-auto"><?=$value->blog_description?></p>
                  <a href="<?php echo SITE_URL."Blog/".$value->id."/" ?>" class="">Continue reading...</a>
                  <?php
                    /** Check to see if Current User Owns and Publish status **/
                    if($data['current_url'] == 'MyBlogs'){
                      echo "<div class='float-right'>";
                      echo " <a class='btn btn-sm btn-success' href='".SITE_URL."Blog/EditBlogImages/".$value->id."/'>Edit Images</a> ";
                      if($value->blog_publish == 0){
                        echo " <a class='btn btn-sm btn-danger' href='".SITE_URL."Blog/CreateBlog/".$value->id."/'>Not Published</a> ";
                      }else{
                        echo " <a class='btn btn-sm btn-info' href='".SITE_URL."Blog/CreateBlog/".$value->id."/'>Published</a> ";
                      }
                      echo "</div>";
                    }
                  ?>
              </div>
        <?php
            }
          }else{
        ?>
          <div class="blog-post border rounded mb-4 shadow-sm p-4">Sorry, no blogs found.  <Br><Br>
          <a href="<?=SITE_URL?>Blog">Blogs Home</a></div>
        <?php
          }
        ?>

        <?php
        /** Display Paginator Links **/
        /** Check to see if there is more than one page **/
        if($data['pageLinks'] > "1"){
        echo "<div class='panel panel-info'>";
          echo "<div class='card-header h4 text-center'>";
            echo $data['pageLinks'];
          echo "</div>";
        echo "</div>";
        }
        ?>

      </div>



    </div>

</div>
