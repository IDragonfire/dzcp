<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 * Menu: Avatar
 */
function avatars() {
    global $db, $userid;

    $qry = db('SELECT `id`,`nick` FROM '. $db[ 'users' ].' WHERE `id` ='.intval($userid).';');
    $avatars = '';
    if(_rows($qry)) {
        while($get = _fetch($qry)) {
            $avatars .= show ( "menu/avatars", array ("avatar_show" => useravatar($get['id'],70,70 ),) );
        }
    }

    return empty($avatars) ? '' : $avatars;
}