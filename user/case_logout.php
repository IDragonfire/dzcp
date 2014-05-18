<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_UserMenu')) {
    $where = _site_user_logout;
    if($chkMe && $userid) {
        db("UPDATE ".$db['users']." SET online = '0', pkey = '', sessid = '' WHERE id = '".$userid."'");
        setIpcheck("logout(".$userid.")");
        dzcp_session_destroy();
    }

    header("Location: ../news/");
}