<?php
	if(!defined('AJAX_INIT_DONE'))
	{
		die('Permission denied');
	}
/**
 * the purpose I added this class is to make the file system much flexible 
 * for customization.
 * Actually,  this is a kind of interface and you should modify it to fit your system
 * @author Logan Cai (cailongqun [at] yahoo [dot] com [dot] cn)
 * @link www.phpletter.com
 * @since 4/August/2007
 */
	class Auth
	{
		/**
		 * check if the user has logged
		 *
		 * @return boolean
		 */
		function isLoggedIn()
		{
                    global $userid,$chkMe;
                    return ($chkMe >= 1 && $userid >= 1 ? true:false);
		    #return (!empty($_SESSION[$this->__loginIndexInSession])?true:false);
		}
		/**
		 * validate the username & password
		 * @return boolean
		 *
		 */
		function login()
		{
			if($_POST['username'] == CONFIG_LOGIN_USERNAME && $_POST['password'] == CONFIG_LOGIN_PASSWORD)
			{
				$_SESSION[$this->__loginIndexInSession] = true;
				return true;
			}else 
			{
				return false;
			}
		}
	}
?>