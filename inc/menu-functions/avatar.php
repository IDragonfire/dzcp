<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 * Menu: Avatar
 */
function avatar() {
    return show("menu/avatars", array("avatar_show" => useravatar(0,70,70)));
}