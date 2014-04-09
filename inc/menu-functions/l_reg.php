<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 * Menu: zuletzt registrierte User
 */
function l_reg() {
    global $db;

    $qry = db("SELECT `id`,`nick`,`country`,`regdatum` FROM ".$db['users']."
               ORDER BY `regdatum` DESC
               LIMIT ".config('m_lreg'));

    $lreg = '';
    if(_rows($qry)) {
        while($get = _fetch($qry)) {
          $lreg .= show("menu/last_reg", array("nick" => re(cut($get['nick'], config('l_lreg'))),
                                               "country" => flag($get['country']),
                                               "reg" => date("d.m.", $get['regdatum']),
                                               "id" => $get['id']));
        }
    }

    return empty($lreg) ? '' : '<table class="navContent" cellspacing="0">'.$lreg.'</table>';
}