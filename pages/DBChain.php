<?php

$DBChain = new Core\DBChain();

$select_data = $DBChain->start()
	->select('u.userName, g.groupName')
	->table('users u')
	->join('LEFT JOIN','users_groups ug','u.userID = ug.userID')
	->join('LEFT JOIN','groups g','ug.groupID = g.groupID')
	->where('u.userID', '1')
	->whereOR('u.userID', '2', '=')
  ->whereOR('u.userID', '3')
  ->whereAND('u.isactive','1')
  ->whereAND('u.resetkey','0')
  ->groupby('u.userName')
  ->orderby('u.userName', 'DESC')
  ->limit('10')
	->run();

echo "<pre>";
var_dump($select_data);
echo "</pre><br><br>";

foreach ($select_data as $key => $value) {
  echo "<hr>";
  echo "$value->userName";
}

$insert_data = $DBChain->start()
  ->insert()
  ->table('plugin_demo')
  ->columns('demo_version', 'notes')
  ->values('test insert 1234', 'some notes 2')
  ->run();

echo "<pre>";
var_dump($insert_data);
echo "</pre><br><br>";



$update_data = $DBChain->start()
  ->update()
  ->table('plugin_demo')
  ->columns('demo_version', 'notes')
  ->values('updated demo_version 3', 'updated notes 3')
	->whereColumns('id')
	->whereValues('21')
  ->run();

echo "<pre>";
var_dump($update_data);
echo "</pre><br><br>";


?>
