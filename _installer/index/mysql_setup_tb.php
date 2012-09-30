<?php
if (!defined('IN_DZCP'))
    exit();

if($_SESSION['agb'] =! true)
    $index = show("/msg/agb_error",array());
else
{
	if($_SESSION['type'] == 0)
	{
		unset($_SESSION['mysql_password']);
		unset($_SESSION['mysql_user']);
		unset($_SESSION['mysql_prefix']);
		unset($_SESSION['mysql_database']);
		unset($_SESSION['mysql_host']);
		sql_installer();
		unset($_SESSION['mysql_dbengine']);
		header('Location: index.php?action=mysql_setup_users');
	}
	else
	{
		$msg = '';
		if(isset($_POST['update']) && !empty($_POST['version']))
		    sql_installer(false,$_POST['version'],false);
		else if(isset($_POST['update']) && empty($_POST['version']))
			$msg = writemsg(no_db_update_selected,true);
			
	    $settings_tb = db("SELECT * FROM `".$db['settings']."` WHERE `id` = 1 LIMIT 0 , 1",false,true);
	    $version = versions((array_key_exists('db_version',$settings_tb) ? $settings_tb['db_version'] : false));
		$index = show("update_version",array("versions" => $version['version'], "msg" => (!empty($msg) ? $msg : $version['msg']) ,"disabled" => $version['disabled']));
	}
}
?>