<?php
//-> Menu: Avatar
function avatars() {
    global $db, $userid;

    $qry = db ( 'SELECT id,nick' . ' FROM ' . $db[ 'users' ] . ' WHERE id =' .$userid. ';' );
    $avatars = '';
    while($get = _fetch($qry)) {
        $avatars .= show ( "menu/avatars", array ("avatar_show" => useravatar ($get['id'],70,70 ),) );
    }

    return empty($avatars) ? '' : $avatars;
}