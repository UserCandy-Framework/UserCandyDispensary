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

  /** Get Blog Data **/
  $data['blog_data'] = $BlogModel->getBlogData($blog_id);
  /** Collect Data for view **/
  $data['title'] = $data['blog_data'][0]->blog_title;
  $data['site_description'] = $data['blog_data'][0]->blog_description;
  $data['site_keywords'] = $data['blog_data'][0]->blog_keywords;
  $data['main_image'] = $BlogModel->getBlogImageMain($blog_id);

  $get_userName = CurrentUserData::getUserName($data['blog_data'][0]->blog_owner_id);
  $get_userImage = CurrentUserData::getUserImage($data['blog_data'][0]->blog_owner_id);
  /** Update and Get Views Data **/
  $PageViews = PageViews::views('true', $data['blog_data'][0]->id, 'Blog', $data['currentUserData'][0]->userID);

?>

    <style>
      body {
        padding-top: 0px !important;
      }
      .alert {
        margin-top: 78px !important;
      }

      header.masthead {
        margin-top: 0px;
        margin-bottom: 50px;
        background: no-repeat center center;
        background-color: #868e96;
        background-attachment: scroll;
        position: relative;
        background-size: cover;
        background-position: center;
        width: 100%;
        height: 100%;
      }

      header.masthead .overlay {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background-color: #212529;
        opacity: 0.5;
      }

      header.masthead .page-heading,
      header.masthead .post-heading,
      header.masthead .site-heading {
        padding: 200px 0 150px;
        color: white;
      }

      @media only screen and (min-width: 768px) {
        header.masthead .page-heading,
        header.masthead .post-heading,
        header.masthead .site-heading {
          padding: 200px 0;
        }
      }

      header.masthead .page-heading,
      header.masthead .site-heading {
        text-align: center;
      }

      header.masthead .page-heading h1,
      header.masthead .site-heading h1 {
        font-size: 50px;
        margin-top: 0;
      }

      header.masthead .page-heading .subheading,
      header.masthead .site-heading .subheading {
        font-size: 24px;
        font-weight: 300;
        line-height: 1.1;
        display: block;
        margin: 10px 0 0;
        font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
      }

      @media only screen and (min-width: 768px) {
        header.masthead .page-heading h1,
        header.masthead .site-heading h1 {
          font-size: 80px;
        }
      }

      header.masthead .post-heading h1 {
        font-size: 35px;
      }

      header.masthead .post-heading .meta,
      header.masthead .post-heading .subheading {
        line-height: 1.1;
        display: block;
      }

      header.masthead .post-heading .subheading {
        font-size: 24px;
        font-weight: 600;
        margin: 10px 0 30px;
        font-family: 'Open Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
      }

      header.masthead .post-heading .meta {
        font-size: 20px;
        font-weight: 300;
        font-style: italic;
        font-family: 'Lora', 'Times New Roman', serif;
      }

      header.masthead .post-heading .meta a {
        color: #fff;
      }

      @media only screen and (min-width: 768px) {
        header.masthead .post-heading h1 {
          font-size: 55px;
        }
        header.masthead .post-heading .subheading {
          font-size: 30px;
        }
      }
    </style>


  <!-- Page Header -->
  <header class="masthead bg-dark border-bottom" style="background-image: url('<?php echo SITE_URL.IMG_DIR_BLOG.$main_image;?>')">
    <div class="overlay"></div>
    <div class="container">
      <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
          <div class="post-heading blog-post-title">
            <h1><?php echo $data['blog_data'][0]->blog_title; ?></h1>
            <h2 class="subheading"><?php echo $data['blog_data'][0]->blog_description; ?></h2>
            <span class="blog-post-meta meta">
              <?php echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$get_userImage." class='rounded' style='height: 25px'>"; ?>
              Posted by
              <a href="<?php echo SITE_URL."Profile/$get_userName/"; ?>"><?php echo $get_userName; ?></a>
              on <?php echo date("F d, Y", strtotime($data['blog_data'][0]->timestamp)); ?>
            </span>
          </div>
        </div>
      </div>
    </div>
  </header>

  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-lg-10 col-md-10">

        <div class="blog-main col-lg-12 col-md-12">
          <div class="blog-post forum border rounded mb-4 shadow-sm p-2"><?php echo BBCode::getHtml($data['blog_data'][0]->blog_content); ?></div>
        </div>

        <div class="blog-main col-lg-12 col-md-12">
          <div class="blog-post border rounded mb-4 shadow-sm p-1">
            <?php
              /** Check to see if current user owns this blog **/
              if($data['currentUserData'][0]->userID == $data['blog_data'][0]->blog_owner_id){
                echo "<a class='btn btn-sm btn-warning' href='".SITE_URL."CreateBlog/".$data['blog_data'][0]->id."/'>Edit Blog</a> ";
              }
              if($DispenserModel->checkDispenserEnabled('Sweets')){
                /** Display Total Views **/
                echo "<div class='btn btn-sm btn-info'>Views <span class='badge badge-light'>".$PageViews."</span></div>";
              }
              if($DispenserModel->checkDispenserEnabled('Sweets')){
                /** Start Sweet **/
                echo Sweets::displaySweetsButton($data['blog_data'][0]->id, 'blog', $data['currentUserData'][0]->userID, $data['blog_data'][0]->blog_owner_id, 'Blog/'.$data['blog_data'][0]->id.'/');
                echo Sweets::getSweets($data['blog_data'][0]->id, 'blog', $data['blog_data'][0]->blog_owner_id);
              }
              if($DispenserModel->checkDispenserEnabled('CommentsHelper')){
                /** Start Comments **/
                echo CommentsHelper::getTotalComments($data['blog_data'][0]->id, 'blog', $data['blog_data'][0]->blog_owner_id);
                echo "<div class='col-12 p-0'>";
                  echo CommentsHelper::displayComments($data['blog_data'][0]->id, 'blog', $data['currentUserData'][0]->userID, 0, 'Blog/'.$data['blog_data'][0]->id.'/', null, 'view_comments');
                echo "</div>";
              }
            ?>
          </div>
        </div>

      </div>
    </div>
  </div>
