<?php
ob_start();
  define('basePath', dirname(__FILE__));
  if(file_exists(basePath."/inc/mysql.php"))
      require_once(basePath."/inc/mysql.php");
  else
  { $sql_host = ''; $sql_user = ''; $sql_pass = ''; $sql_db = ''; $sql_prefix = ''; }
	
    if(empty($sql_user) && empty($sql_pass) && empty($sql_db)) 
        header('Location: _installer/index.php');
	else 
	{
		require_once(basePath."/inc/config.php");
		require_once(basePath."/inc/bbcode.php");
		header('Location: '.(empty($_COOKIE[$prev.'id']) && empty($_COOKIE[$prev.'pwd']) ? 'news/' : 'user/?action=userlobby'));
	}
ob_end_flush();
?>