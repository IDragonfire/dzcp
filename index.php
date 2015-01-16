<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

ob_start();
    define('basePath', dirname(__FILE__));
    $sql_prefix = ''; $sql_host = ''; $sql_user =  ''; $sql_pass = ''; $sql_db = '';

    if(file_exists(basePath."/inc/mysql.php"))
        require_once(basePath."/inc/mysql.php");

    if(empty($sql_user) && empty($sql_pass) && empty($sql_db)) {
        header('Location: _installer/index.php');
    }    else {
        $global_index = true;
        include(basePath."/inc/common.php");
        header('Location: '.($chkMe ? startpage() : 'news/'));
    }
ob_end_flush();