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
class Demo extends Models {

	public function Demo(){
		$demo = "Hello World from Demo";
		return $demo;
    }

}
