<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

define('basePath', '../../../..');

## INCLUDES ##
$ajaxJob = true;
include(basePath."/inc/common.php");

## SETTINGS ##
if(!permission("downloads") && !permission("news") && !permission('artikel')) {
    die('Permission denied');
}