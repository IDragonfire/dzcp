<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1 Final
 * http://www.dzcp.de
 */

define('basePath', '../../../..');

## INCLUDES ##
$ajaxJob = true;
include_once(basePath."/inc/debugger.php");
include_once(basePath."/inc/config.php");
include_once(basePath."/inc/bbcode.php");

## SETTINGS ##
if(!permission("downloads") && !permission("news") && !permission('artikel')) {
    die('Permission denied');
}