<?php
/**
* UserApplePie v4 Forum View Plugin Search
*
* UserApplePie - Forum Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.2 for UAP v.4.3.0
*/

/** Forum Topics List View **/
(empty($get_var_2)) ? $search = null : $search = $get_var_2;
(empty($get_var_3)) ? $current_page = "1" : $current_page = $get_var_3;

// Collect Data for view
$data['title'] = "Search ".$forum_title;
$data['welcome_message'] = $forum_description;

// Display What user is searching for
$data['search_text'] = urldecode($search);

// Make sure search entry is not too short
if(strlen($data['search_text']) > 2){
  // Ready the search words for database
  $search_db = str_replace(' ', '%', $data['search_text']);

  // Get data related to search
  $data['forum_topics'] = $model->forum_search($search_db, $pagesTopic->getLimit($current_page, $forum_topic_limit));

  // Set total number of messages for paginator
  $total_num_topics = count($model->forum_search($search_db));
  $pagesTopic->setTotal($total_num_topics);

  // Send page links to view
  (isset($id)) ? $id = $id : $id = "";
  $pageFormat = SITE_URL."SearchForum/$search/$id/"; // URL page where pages are
  $data['pageLinks'] = $pagesTopic->pageLinks($pageFormat, null, $current_page);

  // Display How Many Results
  $data['results_count'] = $total_num_topics;
}else{
  $data['error'] = "Search context is too small.  Please try again!";
  $data['results_count'] = 0;
}

// Get Recent Posts List for Sidebar
$data['forum_recent_posts'] = $model->forum_recent_posts();

// Setup Breadcrumbs
$data['breadcrumbs'] = "<li class='breadcrumb-item'><a href='".SITE_URL."Forum'>".$forum_title."</a></li><li class='breadcrumb-item active'>".$data['title']."</li>";


  /* Hightlight Search Text Function */
  /**
   * Highlighting matching string
   * @param   string  $text           subject
   * @param   string  $words          search string
   * @return  string  highlighted text
   */
  function highlight_search_text($text, $words) {
    $keywords = implode('|',explode(' ',preg_quote($words)));
    //var_dump($keyword);
    $highlighted = preg_replace("/($keywords)/i","<mark>$0</mark>",$text);
    return $highlighted;
  }

?>
<div class='col-lg-9 col-md-8'>

	<div class='card mb-3'>
		<div class='card-header h4'>
			<?php echo $data['title'] ?>
		</div>
		<div class='card-body'>
			<p><?php echo $data['welcome_message'] ?></p>
      <div class="text-center">
        Search found <?php echo $data['results_count']; ?> matches: <?php echo $data['search_text']; ?>
      </div><br>

      <?php
      if(empty($data['error'])){
      // Display Paginator Links
      // Check to see if there is more than one page
      if($data['pageLinks'] > "1"){
        echo "<div class='card border-info mb-3'>";
          echo "<div class='card-header h4 text-center'>";
            echo $data['pageLinks'];
          echo "</div>";
        echo "</div>";
      }
      ?>

				<?php
        // Setup form list table stuff
        echo "<div class='row'>";
          echo "<div class='col-lg-12 col-md-12 col-sm-12'>";
        foreach($data['forum_topics'] as $row2)
        {
          /** Check to see if topic has url set **/
          if(isset($row2->forum_url)){
            $url_link = $row2->forum_url;
          }else{
            $url_link = $row2->forum_post_id;
          }
              echo "<hr>";
              echo "<div class='card mb-3'>";
                echo "<div class='card-header h4'>";
                  echo "<h4>";
                  $title = stripslashes($row2->title);
                  $title_output = highlight_search_text($title, $data['search_text']);
                  if($row2->post_type == "reply_post"){ echo "Reply to: "; }
                  echo "<a href='".SITE_URL."Forum/Topic/$url_link/' title='$title' ALT='$title'>$title_output</a>";
                  echo "</h4>";
                echo "</div>";
                echo "<div class='card-body'>";
                  echo "<div class='row'>";
                    echo "<div class='col-lg-12 col-md-12 col-sm-12'>";
                    if(!empty($row2->content)){
                      $bb_content = BBCode::getHtml($row2->content);
                      $countent_output = highlight_search_text($bb_content, $data['search_text']);
                      echo $countent_output;
                    }
                    echo "</div>";
                  echo "</div>";
                echo "</div>";
              echo "<div class='card-footer text-muted'>";
                echo "<div class='text small'>";
                  $poster_username = CurrentUserData::getUserName($row2->forum_user_id);
                  echo " Posted by <a href='".SITE_URL."Profile/$row2->forum_user_id' style='font-weight: bold'>$poster_username</a> - ";
                  //Display how long ago this was posted
                  $timestart = $row2->tstamp;  //Time of post
                  echo " " . TimeDiff::dateDiff("now", "$timestart", 1) . " ago ";
                  // Display Locked Message if Topic has been locked by admin
                  if($row2->forum_status == 2){
                    echo " <strong><font color='red'>Topic Locked</font></strong> ";
                  }
                echo "</div>";
              echo "</div>";
            echo "</div>";
          } // End query

            echo "</div>";
          echo "</div>";


            // Display Paginator Links
            // Check to see if there is more than one page
            if($data['pageLinks'] > "1"){
              echo "<div class='card border-info mb-3'>";
                echo "<div class='card-header h4 text-center'>";
                  echo $data['pageLinks'];
                echo "</div>";
              echo "</div>";
            }
          }
				?>
		</div>
	</div>
</div>
