<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1 Final
 * http://www.dzcp.de
 * Menu: Avatar
 */
function avatar() {
    global $chkMe;
    return $chkMe >= 1 ? show("menu/avatars", array("avatar_show" => useravatar(0,70,70))) : '';
}