<?php
$_SESSION['installer'] = true;
$host = str_replace('www.','',$_SERVER['HTTP_HOST']);

if((isset($_GET['action']) ? $_GET['action'] : '') == 'mysql_setup_tb')
    $_SESSION['db_install'] = true;

require_once(basePath.'/inc/_version.php');
require_once(basePath.'/inc/config.php');
require_once(basePath.'/inc/kernel.php');
require_once(basePath.'/_installer/system/global.php');

//-> Sichert die ausgelagerten Dateien gegen directe Ausführung
define('IN_DZCP', true);

//-> Generiert die installations Schritte
function steps()
{
	$lizenz = ''; $type = ''; $prepare = ''; $mysql = ''; 
	$db = ''; $update = ''; $adminacc = ''; $done = '';
	
	switch (!isset($_GET['action']) ? "" : $_GET['action']):
	default:
		$lizenz = show(_step,array("text" => _link_start));
		$type = show(_step,array("text" => _link_type_1));
	break;
	case 'installtype'; //Auswahl: Installation / Update
		$lizenz = show(_step,array("text" => _link_start_1));
		$type = show(_step,array("text" => _link_type));
	break;
	case'prepare'; //Schreibrechte PrÃ¼fen
		$lizenz = show(_step,array("text" => _link_start_1));
		$type = show(_step,array("text" => _link_type_1));
		$prepare = show(_step,array("text" => _link_prepare));
		$mysql = show(_step,array("text" => _link_mysql_1));
		$db = show(_step,array("text" => _link_db_1));
		
		if($_SESSION['type'] == 1)
			$update = show(_step,array("text" => _link_update_1));
		else
			$adminacc = show(_step,array("text" => _link_adminacc_1));
		
		$done = show(_step,array("text" => _link_done_1));
	break;
	case'mysql'; //MySQL Verbindung abfragen & herstellen
		$lizenz = show(_step,array("text" => _link_start_1));
		$type = show(_step,array("text" => _link_type_1));
		$prepare = show(_step,array("text" => _link_prepare_1));
		$mysql = show(_step,array("text" => _link_mysql));
		$db = show(_step,array("text" => _link_db_1));
		
		if($_SESSION['type'] == 1)
			$update = show(_step,array("text" => _link_update_1));
		else
			$adminacc = show(_step,array("text" => _link_adminacc_1));
		
		$done = show(_step,array("text" => _link_done_1));
	break;
	case'mysql_setup'; //MySQL Config schreiben
		$lizenz = show(_step,array("text" => _link_start_1));
		$type = show(_step,array("text" => _link_type_1));
		$prepare = show(_step,array("text" => _link_prepare_1));
		$mysql = show(_step,array("text" => _link_mysql_1));
		$db = show(_step,array("text" => _link_db));

		if($_SESSION['type'] == 1)
			$update = show(_step,array("text" => _link_update_1));
		else
			$adminacc = show(_step,array("text" => _link_adminacc_1));
		
		$done = show(_step,array("text" => _link_done_1));
	break; //Tabellen anlegen
	case 'mysql_setup_tb';
		$lizenz = show(_step,array("text" => _link_start_1));
		$type = show(_step,array("text" => _link_type_1));
		$prepare = show(_step,array("text" => _link_prepare_1));
		$mysql = show(_step,array("text" => _link_mysql_1));
		$db = show(_step,array("text" => _link_db_1));
		
		if($_SESSION['type'] == 1)
			$update = show(_step,array("text" => _link_update));
		else
			$adminacc = show(_step,array("text" => _link_adminacc_1));
		
		$done = show(_step,array("text" => _link_done_1));
	break;
	case 'mysql_setup_users'; //Administrator anlegen,Erste Konfiguration etc.
		$lizenz = show(_step,array("text" => _link_start_1));
		$type = show(_step,array("text" => _link_type_1));
		$prepare = show(_step,array("text" => _link_prepare_1));
		$mysql = show(_step,array("text" => _link_mysql_1));
		$db = show(_step,array("text" => _link_db_1));
		$adminacc = show(_step,array("text" => _link_adminacc));
		$done = show(_step,array("text" => _link_done_1));
	break;
	case 'done';
		$lizenz = show(_step,array("text" => _link_start_1));
		$type = show(_step,array("text" => _link_type_1));
		$prepare = show(_step,array("text" => _link_prepare_1));
		$mysql = show(_step,array("text" => _link_mysql_1));
		$db = show(_step,array("text" => _link_db_1));
		
		if($_SESSION['type'] == 1)
			$update = show(_step,array("text" => _link_update_1));
		else
			$adminacc = show(_step,array("text" => _link_adminacc_1));
		
		$done = show(_step,array("text" => _link_done));
	break;
	endswitch;
	
	return $lizenz.$type.$prepare.$mysql.$db.$update.$adminacc.$done;
}

//-> Welche Datenbank Engine soll verwedet werden
function get_db_engine($db_engine=0,$reverse=false)
{
    if(!$reverse)
    {
        switch ($db_engine)
        {
            case 1:
                return 'TYPE=MyISAM'; //MySQL MyISAM
            break;
            case 2:
                return 'TYPE=InnoDB'; //MySQL InnoDB
            break;
            case 3:
                return 'TYPE=ndbcluster'; //MySQL NDB Cluster
            break;
            default:
                return ''; //Server Default
            break;
        }
    }
    else
    {
        switch ($db_engine)
        {
            case 'MyISAM':
                return 1; //MySQL MyISAM
                break;
            case 'InnoDB':
                return 2; //MySQL InnoDB
                break;
            case 'ndbcluster':
                return 3; //MySQL NDB Cluster
                break;
            default:
                return 0; //Server Default
            break;
        }
    }
}

//-> Erkennt welche Datenbank Engine verwendet wird
function mysqlTableEngine($con, $db, $table) 
{
    $mysqlVersion = mysqlVersion($con);
    $engineValue = '';

    if ($mysqlVersion['int'] < 40102)
        $engineValue = 'Type';
    else 
        $engineValue = 'Engine';

    $sql = "SHOW TABLE STATUS FROM " . $db . " LIKE '" . $table . "'";
    $result = @mysql_query($sql,$con);
    $row = @mysql_fetch_array( $result, MYSQL_ASSOC );
    return $row[$engineValue];
}

function mysqlVersion($con) 
{
    $vers = array();
    $sql = 'SELECT VERSION( ) AS versionsinfo';
    $result = @mysql_query($sql,$con);
    $version = @mysql_result( $result, 0, "versionsinfo" );
    $match = explode( '.', $version );
    $vers['txt'] = $version;
    $vers['int'] = sprintf( '%d%02d%02d', $match[0], $match[1], intval( $match[2] ) );
    return $vers;
}

//-> Prüft MySQL Server auf ndb Erweiterung
function check_db_ndb($con)
{
    $db = mysql_get_server_info($con);
    return (stristr($db, "ndb") !== false && stristr($db, "cluster") !== false);
}

//-> Nachrichten ausgeben
function writemsg($stg='',$error=false, $warn=false)
{
	if($error)
		return show("/msg/msg_error",array("error" => _error, "msg" => $stg));
	else if($warn)
	    return show("/msg/msg_warn",array("warn" => _warn, "msg" => $stg));
	else
		return show("/msg/msg_successful",array("successful" => _successful, "msg" => $stg));
}

//-> Schreibe Datenbank
function sql_installer($insert=false,$db_infos=array(),$newinstall=true)
{
	if($newinstall)
	{
		require_once(basePath.'/_installer/system/sqldb/newinstall/mysql_create_tb.php');
		require_once(basePath.'/_installer/system/sqldb/newinstall/mysql_insert_db.php');
		
		if($insert)
			install_mysql_insert($db_infos);
		else
			install_mysql_create();
	}
	else
	{
		$versions = array();
		if($files = get_files(basePath.'/_installer/system/sqldb/update/',false,true,array('php')))
		{ 
			$updates_array=array();
			foreach($files as $update) 
			{ require_once(basePath.'/_installer/system/sqldb/update/'.$update); }
		}
		
		//-> Updates Sortieren
		foreach($versions as $res)
		$sort[] = $res['update_id'];
		array_multisort($sort, SORT_ASC, $versions);
	
		if($db_infos!=0)
		{
			foreach($versions as $version_array)
			{
				$result = array_search($db_infos, $version_array, true); //Suche
				if($result!='')
				break;
			}
			
			for($i = ($result-1); $i < count($versions); $i++)
			{
			    
				if($versions[$i]['call'] != false && function_exists($func=('install_'.$versions[$i]['call'].'_update')))
				    @call_user_func($func);
			}
		}
		
		header('Location: index.php?action=done');	
	}
}

//-> Eine Liste der Versionen anzeigen
function versions($detect=false)
{
	$versions = array(); $show='';
	if($files = get_files(basePath.'/_installer/system/sqldb/update/',false,true,array('php')))
	{ 
		foreach($files as $update) 
		{ require_once(basePath.'/_installer/system/sqldb/update/'.$update); }
	}
	
	//-> Liste ausgeben
	$count = count($versions);
	foreach($versions as $id => $version)
	{
	    $checked = ''; $disabled = '';
	    if($detect)
	    {
    	    if($version['dbv'] == $detect && $version['dbv'] != false)
    	        $checked = 'checked="checked"';
    	    else
    	    {
    	        $count--;
    	        $disabled = 'disabled="disabled"';
    	    }
	    }
	    
		$show .= show(version_input,array("version_num" => $version[$version['update_id']], "version_num_view" => $version['version_list'], "checked" => $checked, "disabled" => $disabled));
	}
	
	
	return array('version' => $show, 'msg' => (!$count ? writemsg(no_db_update,false,true) : ''), 'disabled' => (!$count ? 'disabled="disabled"' : ''));
}

//-> Schreibe Inhalt in die "mysql.php"
function write_sql_config()
{
	$stream = file_get_contents(basePath.'/_installer/system/sql_vorlage.txt');
	$var = array("{prefix}", "{host}", "{user}" ,"{pass}" ,"{db}");
	$data = array($_SESSION['mysql_prefix'], $_SESSION['mysql_host'], $_SESSION['mysql_user'], $_SESSION['mysql_password'], $_SESSION['mysql_database']);
	$stream = str_replace($var, $data, $stream);
	return file_put_contents(basePath.'/inc/mysql.php', $stream);
}

//-> Setzt über FTP die chmod
function set_chmod_ftp($array,$ftp_host,$ftp_pfad,$ftp_user,$ftp_pwd,$connect_test=false,$login_test=false)
{
	if($connect_test)
	{
		if(@fsockopen($ftp_host, 21, $errno, $errstr, 1))
		{
			if(!@ftp_connect($ftp_host))
			return false;
			else
			return true;
		}
		else
		return false;
	}
	else if($login_test)
	{
		if(!@ftp_login($conn, $ftp_user, $ftp_pwd))
		return false;
		else
		return true;
	}
	else
	{
		foreach($array as $file)
		{
			$conn = @ftp_connect($ftp_host);
			@ftp_login($conn, $ftp_user, $ftp_pwd);
			ftp_site($conn, 'CHMOD 0775 '.$ftp_pfad.'/'.$file);
		}
		
		return true;
	}
}

//-> Prüft ob Datei existiert und ob auf ihr geschrieben werden kann
function is_writable_array($array)
{
	$i=0;
	$data=array();
	$status=true;
	  
	foreach($array as $file) 
	{
		if(is_dir('../'.$file)) 
			$what = "Dir:&nbsp;";
		else
			$what = "File:";
			
		$_file = preg_replace("#\.\.#Uis", "", '../'.$file);

		if(is_writable('../'.$file))
			$data[$i] = _true."<font color='green'><b>".$what."</b>&nbsp;&nbsp;&nbsp; ".$_file."</font><br />";
		else
		{
			$data[$i] = _false."<font color='red'><b>".$what."</b>&nbsp;&nbsp;&nbsp; ".$_file."</font><br />";
			$status=false;
		}
		
		$i++;
	}
		
	return array('return' => $data, 'status' => $status);
}

//-> Funktion um sauber in die DB einzutragen
function up($txt)
{
    $txt = str_replace("& ","&amp; ",$txt);
    $txt = str_replace("\"","&#34;",$txt);
    return trim(spChars(nl2br(htmlentities(html_entity_decode($txt), ENT_QUOTES, 'iso-8859-1'))));
}
?>