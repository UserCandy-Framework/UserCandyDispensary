<?php
/**
* UserCandy Wall Forum Plugin Sidebar
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 4.2.0
*/

use Helpers\{CurrentUserData,TimeDiff};

  if(empty($data['forum_recent_posts'])){ $data['forum_recent_posts'] = ForumStats::forum_recent_posts();}

?>
<script>
function process()
  {
  var url="<?php echo SITE_URL; ?>SearchForum/" + document.getElementById("forumSearch").value;
  location.href=url;
  return false;
  }
</script>

<div class='col-lg-3 col-md-4'>
  <div class='card mb-3'>
      <div class='card-header h4'>
          Users Status
      </div>
      <ul class="list-group list-group-flush">
          <li class="list-group-item"><a href="<?php echo SITE_URL; ?>Members">Members: <?php echo CurrentUserData::getMembers(); ?></a></li>
          <li class="list-group-item"><a href="<?php echo SITE_URL; ?>Online-Members">Members Online: <?php echo CurrentUserData::getOnlineMembers(); ?></a></li>
      </ul>
  </div>

  <div class='card mb-3'>
    <form onSubmit="return process();" class="form" method="post">
    <div class='card-header h4'>
      Search Forums
    </div>
    <div class='card-body'>
      <div class="form-group">
        <input type="forumSearch" class="form-control" id="forumSearch" placeholder="Search Forums" value="<?php if(isset($data['search_text'])){ echo $data['search_text']; } ?>">
      </div>
      <button type="submit" class="btn btn-secondary">Submit</button>
    </div>
    </form>
  </div>

  <div class='card mb-3'>
    <div class='card-header h4'>
      Forum Recent Posts
    </div>
    <ul class='list-group list-group-flush'>
      <?php
      foreach($data['forum_recent_posts'] as $row_rp)
      {
        $f_p_id = $row_rp->forum_post_id;
        $f_p_id_cat = $row_rp->forum_id;
        $f_p_title = $row_rp->forum_title;
        $f_p_url = $row_rp->forum_url;
        $f_p_timestamp = $row_rp->forum_timestamp;
        $f_p_user_id = $row_rp->forum_user_id;
        $f_p_user_name = CurrentUserData::getUserName($f_p_user_id);
        $online_check = CurrentUserData::getUserStatusDot($f_p_user_id);
        $f_p_title = stripslashes($f_p_title);

        /** Check to see if topic has url set **/
        if(isset($f_p_url)){
          $url_link = $f_p_url;
        }else{
          $url_link = $f_p_id;
        }

        //Reply information
        $rp_user_id2 = $row_rp->fpr_user_id;
        $rp_timestamp2 = $row_rp->fpr_timestamp;

        // Set the incrament of each post
        if(isset($vm_id_a_rp)){ $vm_id_a_rp++; }else{ $vm_id_a_rp = "1"; };

        $f_p_title = strlen($f_p_title) > 30 ? substr($f_p_title, 0, 34) . ".." : $f_p_title;

        //If no reply show created by
        if(!isset($rp_timestamp2)){
          echo "<ul class='list-group-item'>";
          echo " $online_check <a href='".SITE_URL."Profile/$f_p_user_id'>$f_p_user_name</a> created.. <br>";
          echo "<strong>";
          echo "<a href='".SITE_URL."Forum/Topic/$url_link/' title='$f_p_title' ALT='$f_p_title'>$f_p_title</a>";
          echo "</strong>";
          echo "<br>";
          //Display how long ago this was posted
          $timestart = $f_p_timestamp;  //Time of post
          echo " <font color=green> " . TimeDiff::dateDiff("now", "$timestart", 1) . " ago</font> ";
          //echo "($f_p_timestamp)"; // Test timestamp
          unset($timestart, $f_p_timestamp);
          echo "</ul>";
        }else{
          $rp_user_name2 = CurrentUserData::getUserName($rp_user_id2);
          $online_check2 = CurrentUserData::getUserStatusDot($rp_user_id2);
          //If reply show the following
          echo "<ul class='list-group-item'>";
          echo " $online_check2 <a href='".SITE_URL."Profile/$rp_user_id2'>$rp_user_name2</a> posted on.. <br>";
          echo "<strong>";
          echo "<a href='".SITE_URL."Forum/Topic/$url_link/' title='$f_p_title' ALT='$f_p_title'>$f_p_title</a>";
          echo "</strong>";
          //Display how long ago this was posted
          $timestart = $rp_timestamp2;  //Time of post
          echo "<br><font color=green> " . TimeDiff::dateDiff("now", "$timestart", 1) . " ago</font> ";
          unset($timestart, $rp_timestamp2);
          echo "</ul>";
        }// End reply check
      } // End query
       ?>
    </ul>
  </div>
</div>
