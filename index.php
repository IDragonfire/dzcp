<?php
ob_start();
  define('basePath', dirname(__FILE__));

    require_once(basePath."/inc/mysql.php");

    if(empty($sql_user) && empty($sql_pass) && empty($sql_db)) {
        header('Location: _installer/index.php');
    }	else {
        include(basePath."/inc/config.php");
        include(basePath."/inc/bbcode.php");

        header('Location: '.(empty($_COOKIE[$prev.'id']) && empty($_COOKIE[$prev.'pkey']) ? 'news/' : 'user/?action=userlobby'));
    }

ob_end_flush();
?>