<?php
/**
* UserApplePie v4 Blog View Plugin Sidebar
*
* UserApplePie - Blog Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 1.0.0 for UAP v.4.3.0
*/

?>
<div class="col-lg-3 col-md-3 col-sm-12">
  <aside class="blog-sidebar border rounded mb-4 shadow-sm p-4">

    <div class="p-4">
      <h4 class="font-italic">Categories</h4>
      <ol class="list-unstyled mb-0">
        <?php
          if(!empty($data['blog_categories'])){
            foreach ($data['blog_categories'] as $key => $value) {
              echo "<li><a href='".SITE_URL."Blog/Categories/1/1/$value->title/'>$value->title</a></li>";
            }
          }
        ?>
      </ol>
    </div>

    <div class="p-4">
      <h4 class="font-italic">Archives</h4>
      <ol class="list-unstyled mb-0">
        <?php
          if(!empty($data['blog_archives'])){
            foreach ($data['blog_archives'] as $key => $value) {
              $monthName = date("F", mktime(0, 0, 0, $value->Month, 10));
              echo "<li><a href='".SITE_URL."Blog/Archives/1/1/$value->Year/$value->Month/'>$monthName $value->Year</a></li>";
            }
          }
        ?>
      </ol>
    </div>

    <!-- Check to see if User is Allowed to create/edit their blogs -->
    <?php if($data['currentUserData'][0]->userID > 0){ ?>
      <div class="p-4">
        <h4 class="font-italic">Manage</h4>
        <ol class="list-unstyled mb-0">
          <li><a href='<?=SITE_URL?>Blog/MyBlogs'>My Blogs</a></li>
          <li><a href='<?=SITE_URL?>Blog/CreateBlog'>Create Blog</a></li>
        </ol>
      </div>
      <?php
        /** Check to see if user is Admin **/
        if($data['isAdmin'] == 'true'){
      ?>
        <div class="p-4">
          <h4 class="font-italic">Admin</h4>
          <ol class="list-unstyled mb-0">
            <li><a href='<?=SITE_URL?>Blog/BlogAdminSettings'>Blog Settings</a></li>
            <li><a href='<?=SITE_URL?>Blog/BlogAdminCategories'>Blog Categories</a></li>
            <li><a href='<?=SITE_URL?>Blog/BlogAdminBlogs'>All Blogs</a></li>
          </ol>
        </div>
      <?php } ?>
    <?php } ?>

  </aside>
</div>
