<?php
if (!defined('IN_DZCP'))
    exit();

if($_SESSION['agb'] =! true)
    $index = show("/msg/agb_error",array());
else
{
	$index = show("done",array());
	$_SESSION['db_install'] = false;
	
	//Unset Installer Sessions
	unset($_SESSION['mysql_prefix']);
	unset($_SESSION['mysql_host']);
	unset($_SESSION['mysql_user']);
	unset($_SESSION['mysql_password']);
	unset($_SESSION['mysql_database']);
    unset($_SESSION['mysql_dbengine']);
}
?>