<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/common.php");

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