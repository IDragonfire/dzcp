<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
include(basePath."/user/helper.php");

## SETTINGS ##
$dir = "user";
$where = _site_user;
define('_UserMenu', true);

if(file_exists(basePath."/user/case_".$action.".php"))
    require_once(basePath."/user/case_".$action.".php");

## INDEX OUTPUT ##
$whereami = preg_replace_callback("#autor_(.*?)$#",create_function('$id', 'return data("nick","$id[1]");'),$where);
$title = $pagetitle." - ".$whereami."";
page($index, $title, $where);

## OUTPUT BUFFER END ##
gz_output();