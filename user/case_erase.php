<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_UserMenu')) {
    if($userid) {
        $_SESSION['lastvisit'] = data("time");
        db("UPDATE ".$db['userstats']."
            SET `lastvisit` = '".intval($_SESSION['lastvisit'])."'
            WHERE user = '".$userid."'");
    }

    header("Location: ?action=userlobby");
}