<?php
/**
* Downloads Helper Class
*
* UserCandy
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version uc 1.0.4
*/

use Helpers\Database;

class Downloads
{
  private static $db;

	// Get user data for requested user's profile
	public static function getDownloadCount($file){
		self::$db = Database::get();
		$data = self::$db->select("
				SELECT
					total
				FROM
					".PREFIX."helper_downloads
				WHERE
					file = :file
				",
			array(':file' => $file));
		return $data[0]->total;
	}

	// Check to see if user is a bot
	public static function is_bot()
	{
		/* This function will check whether the visitor is a search engine robot */
		$botlist = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi",
		"looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory",
		"Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot",
		"crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp",
		"msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz",
		"Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot",
		"Mediapartners-Google", "Sogou web spider", "WebAlta Crawler","TweetmemeBot",
		"Butterfly","Twitturls","Me.dium","Twiceler");
		foreach($botlist as $bot)
		{
			if(strpos($_SERVER['HTTP_USER_AGENT'],$bot)!==false)
			return true;	// Is a bot
		}

		return false;	// Not a bot
	}

	// Get selected file's size and format it
	public static function getFileSize($file_name, $file_location = ROOTDIR."assets/downloads/")
	{
		$file_bytes = filesize($file_location.$file_name);
		$label = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
		for( $i = 0; $file_bytes >= 1024 && $i < ( count( $label ) -1 ); $file_bytes /= 1024, $i++ );
		return( round( $file_bytes, 2 ) . " " . $label[$i] );
	}

  /**
   * Get total number of downloads for selected file
   * @param file
   * @return array
   */
  public function getDownloadTotal($file)
  {
    self::$db = Database::get();
    $data = self::$db->select('SELECT * FROM '.PREFIX.'helper_downloads WHERE file = :file', array(':file' => $file));
    return $data[0]->total;
  }

  /**
   * Update Download Count
   * @param file
   * @return array
   */
  public function addDownload($file, $count)
  {
    self::$db = Database::get();
    return self::$db->update(PREFIX.'helper_downloads', array('total' => $count), array('file' => $file));
  }

  /**
   * Add file to downloads table
   * @param file
   */
  public function addFile($file)
  {
    self::$db = Database::get();
    self::$db->insert(PREFIX."helper_downloads",array('file' => $file, 'total' => '1'));
  }

}


?>
