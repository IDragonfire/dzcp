<?php
if (!defined('IN_DZCP'))
    exit();

$login = isset($_POST['login']) ? $_POST['login'] : '';
$pwd = isset($_POST['pwd']) ? $_POST['pwd'] : '';
$nick = isset($_POST['nick']) ? $_POST['nick'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$clanname = isset($_POST['clanname']) ? $_POST['clanname'] : '';
$seitentitel = isset($_POST['seitentitel']) ? $_POST['seitentitel'] : '';
$emailweb = isset($_POST['emailweb']) ? $_POST['emailweb'] : '';
$loginsec = isset($_POST['loginsec']) ? $_POST['loginsec'] : '1';
$loginnow = isset($_POST['loginnow']) ? $_POST['loginnow'] : '1';
$nextlink = ''; $msg = ''; $disabled = '';

if(isset($_POST['save']))
{
	if($login != '')
	{
		if($pwd != '')
		{
			if($nick != '')
			{
				if($email != '')
				{
					if($clanname != '')
					{
						if($emailweb != '')
						{
							$db_infos = array("login" => $login, "pwd" => $pwd, "nick" => $nick, "email" => $email, "clanname" => $clanname, "seitentitel" => $seitentitel, "emailweb" => $emailweb, "loginsec" => $loginsec, "loginnow" => $loginnow );
							sql_installer(true,$db_infos);
							$nextlink = show("/msg/nextlink",array("ac" => 'action=done'));
							$msg = writemsg(saved_user,false);
							$disabled = 'disabled="disabled"';
						}
						else
							$msg = writemsg(no_webmail,true);
					}
					else
						$msg = writemsg(no_clanname,true);
				}
				else
					$msg = writemsg(no_email,true);
			}
			else
				$msg = writemsg(no_nick,true);
		}
		else
			$msg = writemsg(no_pwd,true);
	}
	else
		$msg = writemsg(no_username,true);
}

$index = show("mysql_conf",array("login" => $login, "pwd" => $pwd, "email" => $email, "nick" => $nick, "msg" => $msg, "disabled" => $disabled, "clanname" => $clanname, "seitentitel" => $seitentitel, "emailweb" => $emailweb, "loginsec" => ($loginsec ? 'checked="checked"' : ''), "loginnowsec" => ($loginnow ? 'checked="checked"' : ''), "next" => $nextlink));
?>