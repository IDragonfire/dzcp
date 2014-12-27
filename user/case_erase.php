<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

if(defined('_UserMenu')) {
    if($userid) {
        $_SESSION['lastvisit'] = time();
        db("UPDATE `".$db['userstats']."`
            SET `lastvisit` = ".intval($_SESSION['lastvisit'])."
            WHERE `user` = ".intval($userid).";");
    }

    header("Location: ?action=userlobby");
}