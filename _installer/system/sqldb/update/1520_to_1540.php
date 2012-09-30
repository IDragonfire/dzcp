<?php
$versions[1] = array('update_id' => 1, 1 => '1.5.2.x', "version_list" => 'V1.5.2.x', 'call' => '1520_1540', 'dbv' => false); //Update Info

//Update von V1.5.2 auf V1.5.4
function install_1520_1540_update()
{
	global $db;
	db("INSERT INTO ".$db['partners']." (`link`, `banner`) VALUES ('http://www.modsbar.de', 'mb_88x32.gif');",false,false,true);
  	db("INSERT INTO ".$db['partners']." (`link`, `banner`) VALUES ('http://www.templatebar.de', 'tb_88x32.gif');",false,false,true);
	return true;
}
?>