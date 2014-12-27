<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

if(defined('_UserMenu')) {
    $where = _site_user_logout;
    if($chkMe && $userid) {
        db("UPDATE ".$db['users']." SET online = '0', sessid = '' WHERE id = '".$userid."'");
        db("DELETE FROM `".$db['autologin']."` WHERE `ssid` = '".session_id()."';");
        setIpcheck("logout(".$userid.")");
        dzcp_session_destroy();
    }

    header("Location: ../news/");
}