<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START #
define('basePath', dirname(dirname(__FILE__).'../'));
ob_start();

## INCLUDES ##
require(basePath."/inc/debugger.php");
require(basePath."/inc/config.php");
require(basePath."/inc/bbcode.php");

##  * Bot Trap * Google und co. werden auf Grund der robots.txt nicht gesperrt ##
if(!db("SELECT `id` FROM `".$db['ipban']."` WHERE `ip` = '".$userip."' LIMIT 1",true)) {
    $data_array = array();
    $data_array['confidence'] = ''; $data_array['frequency'] = ''; $data_array['lastseen'] = '';
    $data_array['banned_msg'] = up('SpamBot detected by System * Bot Trap *');
    db("INSERT INTO `".$db['ipban']."` SET `time` = ".time().", `ip` = '".$userip."', `data` = '".serialize($data_array)."', `typ` = 3;");
    check_ip(); // IP Prufung * No IPV6 Support *
}
ob_end_flush();