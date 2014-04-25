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
        cookie::clear();
        session_unset();
        session_destroy();
        session_regenerate_id();
    }

    header("Location: ../news/");
}