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
$where = _site_upload;
define('_Upload', true);
$dir = "upload";
$index = error(_error_wrong_permissions, 1);

## SECTIONS
$action = empty($action) ? 'default' : $action;
if(file_exists(basePath."/upload/case_".$action.".php"))
    require_once(basePath."/upload/case_".$action.".php");

## INDEX OUTPUT ##
$title = $pagetitle." - ".$where;
page($index, $title, $where);