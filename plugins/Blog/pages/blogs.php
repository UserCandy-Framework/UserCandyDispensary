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

/** Collect Data for view **/
$data['title'] = $data['blog_title'];
$data['site_description'] = $data['blog_description'];

/** Get Blog Data **/
$data['blog_featured_1'] = $BlogModel->getBlogsData(1);
$data['blog_featured_1main_image'] = $BlogModel->getBlogImageMain($data['blog_featured_1'][0]->id);
$data['blog_featured_2'] = $BlogModel->getBlogsData(2);
$data['blog_featured_2cat'] = $BlogModel->getBlogCategoryTitle($data['blog_featured_2'][0]->blog_category);
$data['blog_featured_2main_image'] = $BlogModel->getBlogImageMain($data['blog_featured_2'][0]->id);
$data['blog_featured_3'] = $BlogModel->getBlogsData(3);
$data['blog_featured_3cat'] = $BlogModel->getBlogCategoryTitle($data['blog_featured_3'][0]->blog_category);
$data['blog_featured_3main_image'] = $BlogModel->getBlogImageMain($data['blog_featured_3'][0]->id);
$data['blogs_data'] = $BlogModel->getBlogsData(0, $pages->getLimit($current_page, BLOG_PAGES_LIMIT));

/** Get Pages Totals **/
$total_num_items = $BlogModel->getBlogsDataCount();
$pages->setTotal($total_num_items);
$pageFormat = SITE_URL."Blog/All/"; // URL page where pages are
$data['pageLinks'] = $pages->pageLinks($pageFormat, null, $current_page);

/** Get Blog Categories **/
$data['blog_categories'] = $BlogModel->getBlogCategories();

/** Get Blog Archives **/
$data['blog_archives'] = $BlogModel->getBlogArchives();

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

      .featured_img_wrap {
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden
        width: 200px;
        height: 250px;
      }

      .featured_img {
        object-fit: cover;
        width: 200px;
        height: 250px;
      }

      .main_featured_img {
        background: no-repeat center center;
        background-color: #868e96;
        background-attachment: scroll;
        position: relative;
        background-size: cover;
        width: 100%;
      }

    </style>
<div class='col'>


    <div class="jumbotron p-4 p-md-5 border text-white rounded bg-dark main_featured_img" style="background-image: url('<?php echo SITE_URL.IMG_DIR_BLOG.$data['blog_featured_1main_image'];?>')">
      <div class="col-md-6 px-0">
        <h1 class="display-4 font-italic"><?=$data['blog_featured_1'][0]->blog_title?></h1>
        <p class="lead my-3"><?=$data['blog_featured_1'][0]->blog_description?></p>
        <p class="lead mb-0"><a href="<?php echo SITE_URL."Blog/".$data['blog_featured_1'][0]->id."/" ?>" class="text-white font-weight-bold">Continue reading...</a></p>
      </div>
    </div>

    <div class="row mb-2">
      <div class="col-md-6">
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
          <div class="col p-4 d-flex flex-column position-static">
            <strong class="d-inline-block mb-2 text-primary"><?=$data['blog_featured_2cat']?></strong>
            <h3 class="mb-0"><?=$data['blog_featured_2'][0]->blog_title?></h3>
            <div class="mb-1 text-muted"><?php echo date("F d", strtotime($data['blog_featured_2'] [0]->timestamp)); ?></div>
            <p class="card-text mb-auto"><?=$data['blog_featured_2'] [0]->blog_description?></p>
            <a href="<?php echo SITE_URL."Blog/".$data['blog_featured_2'] [0]->id."/" ?>" class="stretched-link">Continue reading...</a>
          </div>
          <div class="col-auto d-none d-lg-block">
            <div class="bd-placeholder-img featured-image-wrap">
              <img class="featured_img" src="<?php echo SITE_URL.IMG_DIR_BLOG.$data['blog_featured_2main_image'];?>">
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
          <div class="col p-4 d-flex flex-column position-static">
            <strong class="d-inline-block mb-2 text-success"><?=$data['blog_featured_3cat'] ?></strong>
            <h3 class="mb-0"><?=$data['blog_featured_3'] [0]->blog_title?></h3>
            <div class="mb-1 text-muted"><?php echo date("F d", strtotime($data['blog_featured_3'] [0]->timestamp)); ?></div>
            <p class="mb-auto"><?=$data['blog_featured_3'] [0]->blog_description?></p>
            <a href="<?php echo SITE_URL."Blog/".$data['blog_featured_3'] [0]->id."/" ?>" class="stretched-link">Continue reading...</a>
          </div>
          <div class="col-auto d-none d-lg-block">
            <div class="bd-placeholder-img featured-image-wrap">
              <img class="featured_img" src="<?php echo SITE_URL.IMG_DIR_BLOG.$data['blog_featured_3main_image'];?>">
            </div>
          </div>
        </div>
      </div>
    </div>

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
              </div>
        <?php
            }
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
