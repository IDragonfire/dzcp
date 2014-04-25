<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
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
        include(basePath."/inc/debugger.php");
        include(basePath."/inc/config.php");
        include(basePath."/inc/bbcode.php");
        header('Location: '.($userid && $chkMe ? 'user/?action=userlobby' : 'news/'));
    }
ob_end_flush();