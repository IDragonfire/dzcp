<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 * Menu: Top Downloads
 */
function top_dl() {
    global $db;

    $qry = db("SELECT `id`,`kat`,`download`,`date`,`hits` FROM ".$db['downloads']." ".(permission('dlintern') ? "" : " WHERE `intern` = '0'")." ORDER BY hits DESC LIMIT ".config('m_topdl'));
    $top_dl = '';
    if(_rows($qry)) {
        while($get = _fetch($qry)) {
            if(config('allowhover') == 1) {
                $getkat = db("SELECT name FROM ".$db['dl_kat']." WHERE id = '".$get['kat']."'",false,true);
                $info = 'onmouseover="DZCP.showInfo(\''.jsconvert(re($get['download'])).'\', \''._datum.';'._dl_dlkat.';'._hits.'\', \''.date("d.m.Y H:i", $get['date'])._uhr.';'.jsconvert(re($getkat['name'])).';'.$get['hits'].'\')" onmouseout="DZCP.hideInfo()"';
            }

            $top_dl .= show("menu/top_dl", array("id" => $get['id'],
                                                 "titel" => cut(re($get['download']),config('l_topdl')),
                                                 "info" => $info,
                                                 "hits" => $get['hits']));
        }
    }

    return empty($top_dl) ? '<center style="margin:2px 0">'._no_entrys.'</center>' : '<table class="navContent" cellspacing="0">'.$top_dl.'</table>';
}