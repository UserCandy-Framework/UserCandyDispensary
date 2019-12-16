<?php
/**
* UserApplePie v4 Forum View Plugin Sidebar
*
* UserApplePie - Forum Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.2 for UAP v.4.3.0
*/

  use Helpers\{Language,TimeDiff,CurrentUserData};

  if(empty($data['forum_recent_posts'])){ $data['forum_recent_posts'] = ForumStats::forum_recent_posts(5);}

?>

  <div class='card mb-3'>
    <div class='card-header h4 bg-primary text-center'>
      Recent Forum Posts
    </div>
    <ul class='list-group list-group-flush'>
      <?php
      foreach($data['forum_recent_posts'] as $row_rp)
      {
        $f_p_id = $row_rp->forum_post_id;
        $f_p_id_cat = $row_rp->forum_id;
        $f_p_title = $row_rp->forum_title;
        $f_p_timestamp = $row_rp->forum_timestamp;
        $f_p_user_id = $row_rp->forum_user_id;
        $f_p_user_name = CurrentUserData::getUserName($f_p_user_id);

        $f_p_title = stripslashes($f_p_title);

        //Reply information
        $rp_user_id2 = $row_rp->fpr_user_id;
        $rp_timestamp2 = $row_rp->fpr_timestamp;

        // Set the incrament of each post
        if(isset($vm_id_a_rp)){ $vm_id_a_rp++; }else{ $vm_id_a_rp = "1"; };

        $f_p_title = strlen($f_p_title) > 30 ? substr($f_p_title, 0, 34) . ".." : $f_p_title;

        //If no reply show created by
        if(!isset($rp_timestamp2)){
          echo "<ul class='list-group-item'>";
          echo "<a href='".SITE_URL."Profile/$f_p_user_id'>$f_p_user_name</a> created.. ";
          //Display how long ago this was posted
          $timestart = $f_p_timestamp;  //Time of post
          echo " <font color=green class='float-right'> " . TimeDiff::dateDiff("now", "$timestart", 1) . " ago</font><Br> ";
          echo "<strong>";
          echo "<a href='".SITE_URL."Forum/Topic/$f_p_id/' title='$f_p_title' ALT='$f_p_title'>$f_p_title</a>";
          echo "</strong>";
          //echo "($f_p_timestamp)"; // Test timestamp
          unset($timestart, $f_p_timestamp);
          echo "</ul>";
        }else{
          $rp_user_name2 = CurrentUserData::getUserName($rp_user_id2);
          //If reply show the following
          echo "<ul class='list-group-item'>";
          echo "<a href='".SITE_URL."Profile/$rp_user_id2'>$rp_user_name2</a> posted on.. ";
          //Display how long ago this was posted
          $timestart = $rp_timestamp2;  //Time of post
          echo "<font color=green class='float-right'> " . TimeDiff::dateDiff("now", "$timestart", 1) . " ago</font> <Br>";
          echo "<strong>";
          echo "<a href='".SITE_URL."Forum/Topic/$f_p_id/' title='$f_p_title' ALT='$f_p_title'>$f_p_title</a>";
          echo "</strong>";
          unset($timestart, $rp_timestamp2);
          echo "</ul>";
        }// End reply check




      } // End query
       ?>
    </ul>
  </div>
