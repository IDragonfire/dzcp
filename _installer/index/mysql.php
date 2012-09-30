<?php
if(!defined('IN_DZCP'))
    exit();

if($_SESSION['agb'] =! true)
    $index = show("/msg/agb_error",array());
else
{
    //Updater
    $use_mysql_config = (file_exists(basePath."/inc/mysql.php") && $_SESSION['type'] == 1 && !empty($db['host']) && !empty($db['user']) && !empty($db['pass']) && !empty($db['db'])) ? true : false;
    //End
	
    $mysql_host = isset($_POST['host']) ? $_POST['host'] : ($use_mysql_config ? $sql_host : '');
	$mysql_database = isset($_POST['database']) ? $_POST['database'] : ($use_mysql_config ? $sql_db : '');
	$mysql_prefix = isset($_POST['prefix']) ? $_POST['prefix'] : ($use_mysql_config ? $sql_prefix : '');
	$mysql_user = isset($_POST['user']) ? $_POST['user'] : ($use_mysql_config ? $sql_user : '');
	$mysql_pwd = isset($_POST['pwd']) ? $_POST['pwd'] : ($use_mysql_config ? $sql_pass : '');
	$mysql_dbengine = isset($_POST['dbEngine']) ? $_POST['dbEngine'] : 0;
	$msg=''; $nextlink=''; $dbe_selected0 = ''; $dbe_selected1 = ''; $dbe_selected2 = ''; $dbe_selected3 = ''; $disabled = '';
	
	if(isset($_GET['do']) || $use_mysql_config)
	{
		if(isset($_GET['do']) ? ($_GET['do'] == 'test_mysql') : false || $use_mysql_config)
		{
			//Set Config in Sessions
			$_SESSION['mysql_password'] = $mysql_pwd;
			$_SESSION['mysql_user'] = $mysql_user;
			$_SESSION['mysql_prefix'] = $mysql_prefix;
			$_SESSION['mysql_database'] = $mysql_database;
			$_SESSION['mysql_host'] = $mysql_host;
			$_SESSION['mysql_dbengine'] = $mysql_dbengine;
			#########################
		
			if($_SESSION['mysql_dbengine'] == 0)
				$dbe_selected0 = 'selected="selected"';
			else if($_SESSION['mysql_dbengine'] == 1)
				$dbe_selected1 = 'selected="selected"';
			else if($_SESSION['mysql_dbengine'] == 2)
				$dbe_selected2 = 'selected="selected"';
			else
			    $dbe_selected3 = 'selected="selected"';
					
			if($mysql_prefix != NULL)
			{
			    $posi = (strpos($_SESSION['mysql_host'], ':') !== false ? true : false);
			    $exp = ($posi ? explode(':',$_SESSION['mysql_host']) : $_SESSION['mysql_host']);
				if(@ping_port(($posi ? $exp[0] : $exp), ($posi ? $exp[1] : 3306)))
				{
					if(($con = @mysql_connect($_SESSION['mysql_host'], $_SESSION['mysql_user'], $_SESSION['mysql_password']))) //Zur Datenbank Verbinden
					{
						if(@mysql_select_db($_SESSION['mysql_database'],$con)) //Gehe in Datenbank
						{
						    //Updater
						    if($_SESSION['type'] == 1)
						    {
    						    $_SESSION['mysql_dbengine'] = get_db_engine(mysqlTableEngine($con, $_SESSION['mysql_database'], $db['settings']),true);
    						    
    						    if($_SESSION['mysql_dbengine'] == 0)
    						    {
    						        $dbe_selected0 = 'selected="selected"';
    						        $dbe_selected1 = '';
    						        $dbe_selected2 = '';
    						        $dbe_selected3 = '';
    						    }
    						    else if($_SESSION['mysql_dbengine'] == 1)
    						    {
    						        $dbe_selected0 = '';
    						        $dbe_selected1 = 'selected="selected"';
    						        $dbe_selected2 = '';
    						        $dbe_selected3 = '';
    						    }
    						    else if($_SESSION['mysql_dbengine'] == 2)
    						    {
    						        $dbe_selected0 = '';
    						        $dbe_selected1 = '';
    						        $dbe_selected2 = 'selected="selected"';
    						        $dbe_selected3 = '';
    						    }
    						    else
    						    {
    						        $dbe_selected0 = '';
    						        $dbe_selected1 = '';
    						        $dbe_selected2 = '';
    						        $dbe_selected3 = 'selected="selected"';
    						    }
						    }
						    //End
						    
						    if($_SESSION['mysql_dbengine'] != 3)
						    {
						        $msg = writemsg(mysql_ok,false);
						        $nextlink = show("/msg/nextlink",array("ac" => 'action=mysql_setup'));
						        $disabled = 'disabled="disabled"';
						    }
						    else
						    {
    						    //NDB
    						    if(!check_db_ndb($con))
    						        $msg = writemsg(mysql_no_ndb,true);
    						    else
    						    {
    						        $msg = writemsg(mysql_ok,false);
    						        $nextlink = show("/msg/nextlink",array("ac" => 'action=mysql_setup'));
    						        $disabled = 'disabled="disabled"';
    						    }
						    }
						    
						    @mysql_close($con);
						}
						else
						{
							$msg = writemsg(mysql_no_db,true);
							@mysql_close($con);
						}
					}
					else
						$msg = writemsg(mysql_no_login,true);
				}
				else
					$msg = writemsg(mysql_no_con_server,true);
			}
			else
				$msg = writemsg(mysql_no_prefix,true);
		}
	}
	
	$index = show("mysql",array("disabled" => $disabled, "mysql_host" => $mysql_host, "mysql_database" => $mysql_database, "mysql_prefix" => $mysql_prefix, "mysql_user" => $mysql_user, 
	"mysql_pwd" => $mysql_pwd, "msg" => $msg, "next" => $nextlink, "dbe_selected0" => $dbe_selected0, "dbe_selected1" => $dbe_selected1, "dbe_selected2" => $dbe_selected2, "dbe_selected3" => $dbe_selected3));
}
?>