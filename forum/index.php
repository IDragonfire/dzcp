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

## SETTINGS ##
$where = _site_forum;
$title = $pagetitle." - ".$where."";
$dir = "forum";
define('_Forum', true);

## SECTIONS
$action = empty($action) ? 'default' : $action;
if(file_exists(basePath."/forum/case_".$action.".php"))
    require_once(basePath."/forum/case_".$action.".php");

## INDEX OUTPUT ##
page($index, $title, $where);

## OUTPUT BUFFER END ##
gz_output();