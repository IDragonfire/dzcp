<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1 Final
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$where = _site_votes;
$dir = "votes";
define('_Votes', true);

## SECTIONS
$action = empty($action) ? 'default' : $action;
if(file_exists(basePath."/votes/case_".$action.".php"))
    require_once(basePath."/votes/case_".$action.".php");

## INDEX OUTPUT ##
$title = $pagetitle." - ".$where;
page($index, $title, $where);