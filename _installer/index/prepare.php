<?php
if(!defined('IN_DZCP'))
    exit();

if($_SESSION['agb'] =! true)
    $index = show("/msg/agb_error",array());
else
{
	$set_chmod_ftp = false; $disabled = '';
	$ftp_host = isset($_POST['host']) ? $_POST['host'] : '';
	$ftp_pfad = isset($_POST['pfad']) ? $_POST['pfad'] : '';
	$ftp_user = isset($_POST['user']) ? $_POST['user'] : '';
	$ftp_pwd = isset($_POST['pwd']) ? $_POST['pwd'] : '';
		
	//-> Check Installfiles
	$prepare_array_install = is_writable_array(($array_install = array('_installer','_installer/index.php')));		
			
	//-> Check Scriptfiles
	$prepare_array_script = is_writable_array(($array_script = array(
	'inc/_cache',
	'rss.xml',
	'admin',
	'banner',
	'banner/partners',
	'gallery',
	'gallery/images',
	'server',
	'upload',
	'upload/index.php',
	'inc',
	'inc/images',
	'inc/images/clanwars',
	'inc/images/gameicons',
	'inc/images/maps',
	'inc/images/newskat',
	'inc/images/smileys',
	'inc/images/squads',
	'inc/images/uploads',
	'inc/images/uploads/taktiken',
	'inc/images/uploads/useravatare',
	'inc/images/uploads/usergallery',
	'inc/images/uploads/userpics',
	'inc/tinymce_files',
	'inc/config.php')));
	
	//Über FTP die Rechte setzen
	if(isset($_GET['do']))
	{ 
		if($_GET['do'] == 'set_chmods')
		{
			if(function_exists('ftp_connect') && function_exists('ftp_login') && function_exists('ftp_site'))
			{
				if(set_chmod_ftp(array(),$ftp_host,$ftp_pfad,$ftp_user,$ftp_pwd,true))
				{
					if(set_chmod_ftp(array(),$ftp_host,$ftp_pfad,$ftp_user,$ftp_pwd,false,true))
					{
						set_chmod_ftp($array_install,$ftp_host,$ftp_pfad,$ftp_user,$ftp_pwd); //CHMOD
						set_chmod_ftp($array_script,$ftp_host,$ftp_pfad,$ftp_user,$ftp_pwd); //CHMOD
					}
					else //Login Error
					{
						$set_chmod_ftp = true;
						$success_status = writemsg(prepare_no_ftp_login,true);
						$nextlink = '';					
					}
				}
				else //Connect Error
				{
					$set_chmod_ftp = true;
					$success_status = writemsg(prepare_no_ftp_connect,true);
					$nextlink = '';					
				}
			}
			else //No FTP Error
			{
				$set_chmod_ftp = true;
				$success_status = writemsg(prepare_no_ftp,true);
				$nextlink = '';
			}	
		}
	}
	
	$_SESSION['type'] = isset($_POST['type']) ? $_POST['type'] : $_SESSION['type'];
			
	//Schleife für Installationsdateien
	$install='';
	foreach($prepare_array_install['return'] as $get_check_result)
	{
		$install .= $get_check_result;
	}
		
	//Schleife für Scriptdateien
	$script='';
	foreach($prepare_array_script['return'] as $get_check_result)
	{
		$script .= $get_check_result;
	}
	
	if(!$set_chmod_ftp)
	{
		//Alle Dateien beschreibbar?
		if($prepare_array_script['status'] && $prepare_array_install['status'])
		{
		    $disabled = 'disabled="disabled"';
			$success_status = writemsg(prepare_files_success,false);
			$nextlink = show("/msg/nextlink",array("ac" => 'action=mysql'));
		}
		else
		{
			$success_status = writemsg(prepare_files_error,true);
			$nextlink = '';
		}
	}
		
	$index = show("prepare",array("script" => $script, "disabled" => $disabled, "install" => $install, "success_status" => $success_status, "next" => $nextlink, "ftp_host" => $ftp_host, "ftp_pfad" => $ftp_pfad, "ftp_user" => $ftp_user, "ftp_pwd" => $ftp_pwd));
}
?>