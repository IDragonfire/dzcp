<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$dir = "clanwars";
$where = _site_clanwars;
define('_Clanwars', true);

## SECTIONS ##
$action = empty($action) ? 'default' : $action;
if(file_exists(basePath."/clanwars/case_".$action.".php"))
    require_once(basePath."/clanwars/case_".$action.".php");

## INDEX OUTPUT ##
$title = $pagetitle." - ".$where;
page($index, $title, $where);