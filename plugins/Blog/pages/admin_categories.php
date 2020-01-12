<?php
/**
* UserApplePie v4 Blog View Plugin Admin Categories
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

  <div class="jumbotron p-4 p-md-5 text-white rounded bg-dark">
    <div class="col-md-6 px-0">
      <h1 class="display-4 font-italic">Blog Categories</h1>
      <p class="lead my-3">Create and Edit Blog Categories.</p>
    </div>
    <a class='btn btn-sm btn-info float-right' href='<?=SITE_URL?>BlogAdminCategories/New/'>Create Category</a>
  </div>

  <div class="row justify-content-md-center">
    <div class="col-md-12 blog-main">

      <?php
        if(!empty($blog_categories)){
          foreach ($blog_categories as $key => $value) {
      ?>
            <div class="blog-post border rounded mb-4 shadow-sm p-4">
              <h2 class="blog-post-title"><?php echo $value->title; ?></h2>
                <p class="blog-post-meta"><?=$value->description?>
                <?php
                  echo "<a class='btn btn-sm btn-info float-right' href='".SITE_URL."BlogAdminCategories/".$value->id."/'>Edit Category</a>";
                ?>
                </p>
            </div>
      <?php
          }
        }
      ?>

    </div>
  </div>
</div>
