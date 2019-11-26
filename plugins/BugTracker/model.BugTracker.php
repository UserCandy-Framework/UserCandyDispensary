<?php
/**
* UserCandy Demo Models Plugin
*
* UserCandy - Demo Plugin
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 1.0.0
*/

use Core\Models;

/** Demo model **/
class BugTracker extends Models {

	public function getBugs($limit, $list_status = ""){
		if(!empty($list_status)){
			$data = $this->db->select("
				SELECT *
				FROM ".PREFIX."plugin_bugtracker
				WHERE status = :status
				ORDER BY id DESC
				$limit
			", array('status' => $list_status));
			return $data;
		}else{
			$data = $this->db->select("
				SELECT *
				FROM ".PREFIX."plugin_bugtracker
				ORDER BY id DESC
				$limit
			");
			return $data;
		}
  }

	public function getTotalBugs($list_status){
		if(!empty($list_status)){
			return $this->db->selectCount("SELECT * FROM ".PREFIX."plugin_bugtracker WHERE status = :status", array('status' => $list_status));
		}else{
			return $this->db->selectCount("SELECT * FROM ".PREFIX."plugin_bugtracker");
		}
  }

	public function getBugsStatusCount($list_status = ""){
		return $this->db->selectCount("SELECT *	FROM ".PREFIX."plugin_bugtracker	WHERE status = :status", array('status' => $list_status));
	}

	public function getBug($id){
		return $this->db->select("SELECT * FROM ".PREFIX."plugin_bugtracker WHERE id = :id ORDER BY id DESC LIMIT 1", array('id' => $id));
  }

	public function addBug($summary = null, $package = null, $version = null, $server = null, $type = null, $folder = null, $description = null, $creator_userID = null){
    return $this->db->insert(PREFIX.'plugin_bugtracker', array('summary' => $summary, 'package' => $package, 'version' => $version, 'server' => $server, 'type' => $type, 'folder' => $folder, 'description' => $description, 'creator_userID' => $creator_userID));
  }

	public function editBug($id = null, $summary = null, $package = null, $version = null, $server = null, $type = null, $folder = null, $description = null, $modifi_userID = null, $status = null, $priority = null, $assigned_userID = null){
    return $this->db->update(PREFIX.'plugin_bugtracker', array('summary' => $summary, 'package' => $package, 'version' => $version, 'server' => $server, 'type' => $type, 'folder' => $folder, 'description' => $description, 'modifi_userID' => $modifi_userID, 'status' => $status, 'priority' => $priority, 'assigned_userID' => $assigned_userID), array('id' => $id));
  }

	public function newBugEmail($bug_id){
		/* Email the user and let them know that they have a friend request */
		/** Get All Mods and Admins Emails **/
		$email_mods_admins = $this->db->select("
			SELECT userID
			FROM ".PREFIX."users_groups
			WHERE groupID = :where_id1
			OR groupID = :where_id2
		",
		array(':where_id1' => '3', ':where_id2' => '4'));
		foreach ($email_mods_admins as $row) {
			$email_mods_admins_addy[] = $this->db->select("
				SELECT email
				FROM ".PREFIX."users
				WHERE userID = :where_id
				LIMIT 1
			",
			array(':where_id' => $row->userID));
		}
		/** Get Bug Information **/
		$bug_data = self::getBug($bug_id);
		/** Get Bug Type for display **/
		$type = $bug_data[0]->type;
		if($type == 'Bug'){
			$bug_type = 'Bug';
		}else if($type == 'Doc'){
			$bug_type = 'Documentation';
		}else if($type == 'Dup'){
			$bug_type = 'Duplicate';
		}else if($type == 'Enh'){
			$bug_type = 'Enhancement';
		}else if($type == 'Hel'){
			$bug_type = 'Help Wanted';
		}else if($type == 'Inv'){
			$bug_type = 'Invalid';
		}else if($type == 'Que'){
			$bug_type = 'Question';
		}else if($type == 'Won'){
			$bug_type = "Won't Fix";
		}
		/** Get Submitter User Name **/
		$creator = $this->db->select("
			SELECT username
			FROM ".PREFIX."users
			WHERE userID = :where_id
			LIMIT 1
		",
		array(':where_id' => $bug_data[0]->creator_userID));
		/** EMAIL MESSAGE USING PHPMAILER **/
		$mail = new Helpers\Mail();
		$mail->setFrom(SITEEMAIL, EMAIL_FROM_NAME);
		$mail->addBCC($email_user[0]->email);
		foreach($email_mods_admins_addy as $row){
			$mail->addBCC($row[0]->email);
		}
		$mail_subject = SITE_TITLE . " - BugTracker New - ".$bug_data[0]->summary;
		$mail->subject($mail_subject);
		$body = "<b>".SITE_TITLE." - BugTracker Notification </b>
													<hr/>
													<b>New Bug submitted by ".$creator[0]->username." to BugTracker on ".SITE_TITLE."</b>
													<hr/>
													<b>Summary</b>: ".$bug_data[0]->summary."<br/>
													<b>Package</b>: ".$bug_data[0]->version."<br/>
													<b>Version</b>: ".$bug_data[0]->version."<br/>
													<b>Server Information</b>: ".$bug_data[0]->server."<br/>
													<b>Type</b>: ".$bug_type."<br/>
													<b>Folder Location</b>: ".$bug_data[0]->folder."<br/>
													<hr/>";
		$body .= "You may view bug details at: <a href=\"".SITE_URL."BugTracker/View/".$bug_id."\">View ".$bug_data[0]->id."</a>";
		$mail->body($body);
		$mail->send();

		return true;
	}

	public function updateBugEmail($bug_id){
		/* Email the user and let them know that they have a friend request */
		/** Get All Mods and Admins Emails **/
		$email_mods_admins = $this->db->select("
			SELECT userID
			FROM ".PREFIX."users_groups
			WHERE groupID = :where_id1
			OR groupID = :where_id2
		",
		array(':where_id1' => '3', ':where_id2' => '4'));
		foreach ($email_mods_admins as $row) {
			$email_mods_admins_addy[] = $this->db->select("
				SELECT email
				FROM ".PREFIX."users
				WHERE userID = :where_id
				LIMIT 1
			",
			array(':where_id' => $row->userID));
		}
		/** Get Bug Information **/
		$bug_data = self::getBug($bug_id);
		/** Get Bug Type for display **/
		$type = $bug_data[0]->type;
		if($type == 'Bug'){
			$bug_type = 'Bug';
		}else if($type == 'Doc'){
			$bug_type = 'Documentation';
		}else if($type == 'Dup'){
			$bug_type = 'Duplicate';
		}else if($type == 'Enh'){
			$bug_type = 'Enhancement';
		}else if($type == 'Hel'){
			$bug_type = 'Help Wanted';
		}else if($type == 'Inv'){
			$bug_type = 'Invalid';
		}else if($type == 'Que'){
			$bug_type = 'Question';
		}else if($type == 'Won'){
			$bug_type = "Won't Fix";
		}
		/** Get Submitter User Name **/
		$updater = $this->db->select("
			SELECT username
			FROM ".PREFIX."users
			WHERE userID = :where_id
			LIMIT 1
		",
		array(':where_id' => $bug_data[0]->modifi_userID));
		/** Get Assigned User Name **/
		$assigned = $this->db->select("
			SELECT username, email
			FROM ".PREFIX."users
			WHERE userID = :where_id
			LIMIT 1
		",
		array(':where_id' => $bug_data[0]->assigned_userID));
		/** EMAIL MESSAGE USING PHPMAILER **/
		$mail = new Helpers\Mail();
		$mail->setFrom(SITEEMAIL, EMAIL_FROM_NAME);
		$mail->addBCC($email_user[0]->email);
		foreach($email_mods_admins_addy as $row){
			$mail->addBCC($row[0]->email);
		}
		$mail->addBCC($assigned[0]->email);
		$mail_subject = SITE_TITLE . " - BugTracker Update - ".$bug_data[0]->summary;
		$mail->subject($mail_subject);
		$body = "<b>".SITE_TITLE." - BugTracker Notification </b>
													<hr/>
													<b>Bug update submitted by ".$updater[0]->username." to BugTracker on ".SITE_TITLE."</b>
													<hr/>
													<b>Summary</b>: ".$bug_data[0]->summary."<br/>
													<b>Package</b>: ".$bug_data[0]->version."<br/>
													<b>Version</b>: ".$bug_data[0]->version."<br/>
													<b>Server Information</b>: ".$bug_data[0]->server."<br/>
													<b>Type</b>: ".$bug_type."<br/>
													<b>Folder Location</b>: ".$bug_data[0]->folder."<hr/>
													<b>Status</b>: ".$bug_data[0]->status."<br/>
													<b>Priority</b>: ".$bug_data[0]->priority."<br/>
													<b>Assigned User</b>: ".$assigned[0]->username."<br/>
													<hr/>";
		$body .= "You may view bug details at: <a href=\"".SITE_URL."BugTracker/View/".$bug_id."\">View ".$bug_data[0]->id."</a>";
		$mail->body($body);
		$mail->send();

		return true;
	}

}
