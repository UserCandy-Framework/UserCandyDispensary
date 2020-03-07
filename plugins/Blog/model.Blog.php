<?php
/**
* UserCandy Blog Models Plugin
*
* UserCandy - Blog Plugin
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version 1.0.0
*/

use Core\Models;
use Helpers\Url;

/** Blog model **/
class Blog extends Models {

  /**
  * Get Blog Post by id
  * @param int $where_id
  * @return array dataset
  */
  public function getBlogData($where_id){
    $data = $this->db->select("
      SELECT
        *
      FROM
        ".PREFIX."plugin_blog
      WHERE
        id = :id
      AND
        blog_publish = 1
      ORDER BY
        id
      DESC LIMIT 1
    ", array(':id' => $where_id));
    return $data;
  }

  /**
  * Get Blog Post by id for editor
  * @param int $where_id
  * @return array dataset
  */
  public function getBlogDataEdit($where_id){
    $data = $this->db->select("
      SELECT
        *
      FROM
        ".PREFIX."plugin_blog
      WHERE
        id = :id
      ORDER BY
        id
      DESC LIMIT 1
    ", array(':id' => $where_id));
    return $data;
  }

  /**
  * Get Blog Post by featured status
  * @param int $featured
  * @return array dataset
  */
  public function getBlogsData($featured = 0, $set_limit = null){
    if($featured == 0){
      $limit = $set_limit;
    }else{
      $limit = "LIMIT 1";
    }
    $data = $this->db->select("
      SELECT
        *
      FROM
        ".PREFIX."plugin_blog
      WHERE
        blog_featured = :featured
      AND
        blog_publish = 1
      ORDER BY
        id
      DESC $limit
    ", array(':featured' => $featured));
    return $data;
  }

  /**
  * Get total of all published blogs
  * @return int count
  */
  public function getBlogsDataCount(){
    $data = $this->db->selectCount("
      SELECT
        *
      FROM
        ".PREFIX."plugin_blog
      WHERE
        blog_featured = 0
      AND
        blog_publish = 1
      ORDER BY
        id
    ");
    return $data;
  }

  /**
  * Get Blog Post by Category ID
  * @param int $where_id
  * @return array dataset
  */
  public function getBlogsDataCat($where_id, $limit = 'LIMIT 10'){
    $data = $this->db->select("
      SELECT
        *
      FROM
        ".PREFIX."plugin_blog
      WHERE
        blog_category = :id
      AND
        blog_publish = 1
      ORDER BY
        id
      DESC $limit
    ", array(':id' => $where_id));
    return $data;
  }

  /**
  * Get Blog Post count by Category ID
  * @param int $where_id
  * @return array dataset
  */
  public function getBlogsDataCatCount($where_id){
    $data = $this->db->selectcount("
      SELECT
        *
      FROM
        ".PREFIX."plugin_blog
      WHERE
        blog_category = :id
      AND
        blog_publish = 1
      ORDER BY
        id
      DESC
    ", array(':id' => $where_id));
    return $data;
  }

  /**
  * Get Blog Post by Date
  * @param int $month
  * @param int $year
  * @return array dataset
  */
  public function getBlogsDataDate($year, $month, $limit = 'LIMIT 10'){
    $data = $this->db->select("
      SELECT
        *
      FROM
        ".PREFIX."plugin_blog
      WHERE
        YEAR(timestamp) = :year
      AND
        MONTH(timestamp) = :month
      AND
        blog_publish = 1
      ORDER BY
        id
      DESC $limit
    ", array(':year' => $year, ':month' => $month));
    return $data;
  }

  /**
  * Get total Blog Posts by Date
  * @param int $month
  * @param int $year
  * @return array dataset
  */
  public function getBlogsDataDateCount($year, $month){
    $data = $this->db->selectCount("
      SELECT
        *
      FROM
        ".PREFIX."plugin_blog
      WHERE
        YEAR(timestamp) = :year
      AND
        MONTH(timestamp) = :month
      AND
        blog_publish = 1
      ORDER BY
        id
      DESC
    ", array(':year' => $year, ':month' => $month));
    return $data;
  }

  /**
  * Get Blog Post by User ID
  * @param int $user_id
  * @return array dataset
  */
  public function getBlogsDataUser($user_id, $limit = 'LIMIT 10'){
    $data = $this->db->select("
      SELECT
        *
      FROM
        ".PREFIX."plugin_blog
      WHERE
        blog_owner_id = :blog_owner_id
      ORDER BY
        id
      DESC $limit
    ", array(':blog_owner_id' => $user_id));
    return $data;
  }

  /**
  * Get Blog Post by User ID
  * @param int $user_id
  * @return array dataset
  */
  public function getBlogsDataUserCount($user_id){
    $data = $this->db->selectCount("
      SELECT
        *
      FROM
        ".PREFIX."plugin_blog
      WHERE
        blog_owner_id = :blog_owner_id
      ORDER BY
        id
      DESC
    ", array(':blog_owner_id' => $user_id));
    return $data;
  }

  /**
  * Get Blog Category by id
  * @param int $where_id
  * @return array dataset
  */
  public function getBlogCategory($where_id){
    $data = $this->db->select("
      SELECT
        *
      FROM
        ".PREFIX."plugin_blog_categories
      WHERE
        id = :id
      ORDER BY
        id
      DESC LIMIT 1
    ", array(':id' => $where_id));
    return $data;
  }

  /**
  * Get Blog Categories
  * @return array dataset
  */
  public function getBlogCategories(){
    $data = $this->db->select("
      SELECT
        *
      FROM
        ".PREFIX."plugin_blog_categories
      ORDER BY
        title
      ASC
    ");
    return $data;
  }

  /**
  * Get Blog Category Title by id
  * @param int $where_id
  * @return string data
  */
  public function getBlogCategoryTitle($where_id){
    $data = $this->db->select("
      SELECT
        title
      FROM
        ".PREFIX."plugin_blog_categories
      WHERE
        id = :id
      ORDER BY
        id
      DESC LIMIT 1
    ", array(':id' => $where_id));
    return $data[0]->title;
  }

  /**
  * Get Blog Category Title id
  * @param int $where_id
  * @return string data
  */
  public function getBlogCategoryID($where){
    $data = $this->db->select("
      SELECT
        id
      FROM
        ".PREFIX."plugin_blog_categories
      WHERE
        title = :title
      ORDER BY
        title
      DESC LIMIT 1
    ", array(':title' => $where));
    return $data[0]->id;
  }

  /**
  * Get Blog Category Title id
  * @param int $where_id
  * @return string data
  */
  public function getBlogArchives(){
    $data = $this->db->select("
      SELECT
        Year(timestamp) as Year, Month(timestamp) as Month
      FROM
        ".PREFIX."plugin_blog
      GROUP BY Year, Month
      ORDER BY timestamp DESC
    ");
    return $data;
  }

  /**
   * sendTopic
   *
   * create new topic
   *
   * @param int $blog_user_id Current user's ID
   * @param int $blog_id Current Category's ID
   * @param string $blog_title New topic's title
   * @param string $blog_content New topic's content
   *
   * @return booleen true/false
   */
  public function sendBlog($blog_owner_id, $blog_title, $blog_content, $blog_description, $blog_category, $blog_keywords, $blog_publish = "0"){
    /** Generate URL based on title **/
    $blog_url = URL::generateSafeSlug($blog_title);
    /** Check to see if URL already exsist **/
    if(SELF::get_blog_url_id($blog_url)){
      $rand_string = mt_rand(1, 99);
      $blog_url = $blog_url."-rn".$rand_string;
    }
    /** Add to Blog Posts **/
    $query = $this->db->insert(PREFIX.'plugin_blog', array('blog_owner_id' => $blog_owner_id, 'blog_title' => $blog_title, 'blog_url' => $blog_url, 'blog_content' => $blog_content, 'blog_description' => $blog_description, 'blog_category' => $blog_category, 'blog_keywords' => $blog_keywords, 'blog_publish' => $blog_publish));
    $last_insert_id = $this->db->lastInsertId('id');
    // Check to make sure Topic was Created
    if($query > 0){
      return $last_insert_id;
    }else{
      return false;
    }
  }

  /**
   * updateBlog
   *
   * edit/update Blog
   */
  public function updateBlog($id, $blog_title, $blog_content, $blog_description, $blog_category, $blog_keywords){
    /** Generate URL based on title **/
    $blog_url = URL::generateSafeSlug($blog_title);
    /** Check to see if URL already exsist **/
    if(SELF::get_blog_url($blog_url, $id)){
      $blog_url = $blog_url."-".$id;
    }
    /** Update the Blog Post **/
    $query = $this->db->update(PREFIX.'plugin_blog', array('blog_title' => $blog_title, 'blog_content' => $blog_content, 'blog_description' => $blog_description, 'blog_category' => $blog_category, 'blog_keywords' => $blog_keywords, 'blog_url' => $blog_url, 'blog_publish' => '1', 'edit_timestamp' => date('Y-m-d H:i:s')), array('id' => $id));
    // Check to make sure Blog was Created
    if($query > 0){
      return true;
    }else{
      return false;
    }
  }

  /**
   * updateSavedBlog
   *
   * edit/update saved Blog
   */
  public function updateSavedBlog($id, $blog_title, $blog_content, $blog_description, $blog_category, $blog_keywords, $blog_publish = "0"){
    /** Generate URL based on title **/
    $blog_url = URL::generateSafeSlug($blog_title);
    /** Check to see if URL already exsist **/
    if(SELF::get_blog_url($blog_url, $id)){
      $rand_string = mt_rand(1, 99);
      $blog_url = $blog_url."-rn".$rand_string;
    }
    // Update blog table
    $query = $this->db->update(PREFIX.'plugin_blog', array('blog_title' => $blog_title, 'blog_content' => $blog_content, 'blog_description' => $blog_description, 'blog_category' => $blog_category, 'blog_keywords' => $blog_keywords, 'blog_url' => $blog_url, 'blog_publish' => $blog_publish, 'timestamp' => date('Y-m-d H:i:s')), array('id' => $id));
    // Check to make sure Blog was Created
    if($query > 0){
      return true;
    }else{
      return false;
    }
  }

  /**
   * topic_reply_is_published
   *
   * check if blog reply is published
   *
   * @param int $where_id = id
   *
   * @return string returns blog reply data (blog_publish)
   */
  public function blog_is_published($blog_id){
    $data = $this->db->select("
      SELECT blog_publish
      FROM ".PREFIX."plugin_blog
      WHERE id = :blog_id
      LIMIT 1
    ",
    array(':blog_id' => $blog_id));
    return $data[0]->blog_publish;
  }

  /**
   * get Topic ID based on URL request
   * @param string $blog_url
   * @param int $id
   * @return int data
   */
  public function get_blog_url($blog_url, $id){
    $data = $this->db->select("
      SELECT id
      FROM ".PREFIX."plugin_blog
      WHERE blog_url = :blog_url
      AND NOT id = :id
      LIMIT 1
    ",
    array(':blog_url' => $blog_url, ':id' => $id));
    return $data[0]->id;
  }

  /**
   * get Topic ID based on URL request
   * @param string $blog_url
   * @return int data
   */
  public function get_blog_url_id($blog_url){
    $data = $this->db->select("
      SELECT id
      FROM ".PREFIX."plugin_blog
      WHERE blog_url = :blog_url
      LIMIT 1
    ",
    array(':blog_url' => $blog_url));
    return $data[0]->id;
  }

  /**
   * create new blog category
   * @param string $title
   * @param string $description
   * @return booleen true/false
   */
  public function createBlogCat($title, $description){
    /** Add to Blog Posts **/
    $data = $this->db->insert(PREFIX.'blog_categories', array('title' => $title, 'description' => $description));
    $last_insert_id = $this->db->lastInsertId('id');
    /** Check to make sure Topic was Created **/
    if($data > 0){
      return $last_insert_id;
    }else{
      return false;
    }
  }

  /**
   * update blog category
   * @param int $id
   * @param string $title
   * @param string $description
   * @return booleen true/false
   */
  public function updateBlogCat($id, $title, $description){
    /** Update blog category **/
    $query = $this->db->update(PREFIX.'blog_categories', array('title' => $title, 'description' => $description), array('id' => $id));
    /** Check to make sure category was Created **/
    if($query > 0){
      return true;
    }else{
      return false;
    }
  }

  /**
   * checkBlogGroup
   *
   * get forum group data.
   *
   * @param string $blog_group Name of Blog Group
   * @param int $groupID ID of User Group
   *
   * @return boolean returns true/false
   */
  public function checkGroupBlog($blog_group, $groupID){
    $data = $this->db->selectCount("
        SELECT
          blog_group,
          groupID
        FROM
          ".PREFIX."plugin_blog_groups
        WHERE
          blog_group = :blog_group
          AND
          groupID = :groupID
        ORDER BY
          groupID DESC
        ",
        array(':blog_group' => $blog_group, ':groupID' => $groupID));
      if($data > 0){
        return true;
      }else{
        return false;
      }
  }

  /**
   * editBlogGroup
   *
   * create or delete forum group.
   *
   * @param string $groupName Name of Blog Group
   * @param string $action Add/Remove
   * @param int $groupID ID of User Group
   *
   * @return boolean returns true/false
   */
  public function editBlogGroup($groupName, $action, $groupID){
    if($action == "add"){
      // Add Blog Group to Group
      $data = $this->db->insert(PREFIX.'blog_groups', array('blog_group' => $groupName, 'groupID' => $groupID));
      if($data > 0){
        return true;
      }else{
        return false;
      }
    }else if($action == "remove"){
      // Remove Blog Group from Group
      $data = $this->db->delete(PREFIX.'blog_groups', array('blog_group' => $groupName, 'groupID' => $groupID));
      if($data > 0){
        return true;
      }else{
        return false;
      }
    }
  }

  /**
  * Add user image to database when uploaded.
  * If no default image then first image is default.
  * @param int $u_id
  * @param string $blogImage
  * @return int count
  */
  public function addBlogImage($u_id, $blog_id, $blogImage)
  {
      /* Check if image is set as default */
      $data = $this->db->select("SELECT blogImage FROM ".PREFIX."plugin_blog_images WHERE userID=:id AND blog_id=:blog_id AND defaultImage = '1' ",array(":id"=>$u_id, ":blog_id"=>$blog_id));
      if(!empty($data[0]->blogImage)){
        return $this->db->insert(PREFIX.'blog_images', array('userID' => $u_id, 'blog_id' => $blog_id, 'blogImage' => $blogImage, 'defaultImage' => '0'));
      }else{
        return $this->db->insert(PREFIX.'blog_images', array('userID' => $u_id, 'blog_id' => $blog_id, 'blogImage' => $blogImage, 'defaultImage' => '1'));
      }
  }

  /**
  * Get User Main Profile Image by userID
  * @param int $id
  * @return string data
  */
  public function getBlogImageMain($id)
  {
      $data = $this->db->select("SELECT blogImage FROM ".PREFIX."plugin_blog_images WHERE blog_id=:id AND defaultImage = '1' ",array(":id"=>$id));
      return $data[0]->blogImage;
  }

  /**
  * Get User Profile Images by userID
  * @param int $id
  * @param string $limit
  * @return array dataset
  */
  public function getBlogImages($id, $limit = 'LIMIT 20')
  {
      return $this->db->select("SELECT id, blogImage FROM ".PREFIX."plugin_blog_images WHERE blog_id=:id ORDER BY timestamp DESC $limit",array(":id"=>$id));
  }

  /**
  * Get User Main Profile Image by ID
  * @param int $userID
  * @param int $imageID
  * @return string data
  */
  public function getBlogImage($userID, $imageID)
  {
      $data = $this->db->select("SELECT blogImage FROM ".PREFIX."plugin_blog_images WHERE id = :imageID AND userID = :userID ",array(":imageID"=>$imageID, ":userID"=>$userID));
      return $data[0]->blogImage;
  }

  /**
  * Get User Main Profile Image ID
  * @param int $id
  * @return int data
  */
  public function getBlogImageMainID($id)
  {
      $data = $this->db->select("SELECT id FROM ".PREFIX."plugin_blog_images WHERE blog_id=:id AND defaultImage = '1' ",array(":id"=>$id));
      return $data[0]->id;
  }

  /**
  * Update Photo in database
  * @param int $userID
  * @param int $imageID
  * @param string $action
  * @return boolean true/false
  */
  public function updateBlogImage($blog_id, $imageID, $action)
  {
      $data = $this->db->update(PREFIX.'blog_images', array('defaultImage' => $action), array('id' => $imageID, 'blog_id' => $blog_id));
      if($data > 0){
        return true;
      }else{
        return false;
      }
  }

  /**
  * Delete Photo from database
  * @param int $userID
  * @param int $imageID
  * @return boolean true/false
  */
  public function deleteBlogImage($blog_id, $imageID)
  {
      $data = $this->db->delete(PREFIX.'blog_images', array('id' => $imageID, 'blog_id' => $blog_id));
      if($data > 0){
        return true;
      }else{
        return false;
      }
  }

  /**
  * Gets total count of images that belong to user
  * @param int $userID
  * @return int count
  */
  public function getTotalImages($blog_id){
    $data = $this->db->selectCount("
        SELECT
          *
        FROM
          ".PREFIX."plugin_blog_images
        WHERE
          blog_id = :blog_id
        ", array(':blog_id' => $blog_id));
    return $data;
  }

}
