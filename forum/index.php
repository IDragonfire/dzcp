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
$where = _site_forum;
$dir = "forum";
define('_Forum', true);

## SECTIONS
$action = empty($action) ? 'default' : $action;
if(file_exists(basePath."/forum/case_".$action.".php"))
    require_once(basePath."/forum/case_".$action.".php");

## INDEX OUTPUT ##
$title = $pagetitle." - ".$where;
page($index, $title, $where);