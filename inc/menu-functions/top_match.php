<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 * Menu: Top Match
 */
function top_match() {
    global $db,$allowHover,$llwars,$picformat;

    $qry = db("SELECT s1.datum,s1.gegner,s1.id,s1.bericht,s1.xonx,s1.clantag,s1.punkte,s1.gpunkte,s1.squad_id,s2.icon,s2.name FROM ".$db['cw']." AS s1
               LEFT JOIN ".$db['squads']." AS s2 ON s1.squad_id = s2.id
               WHERE `top` = '1'
               ORDER BY RAND()");

    $topmatch = ''; $hover = '';
    if(_rows($qry)) {
        if($get = _fetch($qry)) {
            $squad = '_defaultlogo.jpg'; $gegner = '_defaultlogo.jpg';
            foreach($picformat AS $end) {
                if(file_exists(basePath.'/inc/images/clanwars/'.$get['id'].'_logo.'.$end))
                    $gegner = $get['id'].'_logo.'.$end;

                if(file_exists(basePath.'/inc/images/squads/'.$get['squad_id'].'_logo.'.$end))
                    $squad = $get['squad_id'].'_logo.'.$end;
            }

            if($allowHover == 1 || $allowHover == 2)
                $hover = 'onmouseover="DZCP.showInfo(\''.jsconvert(re($get['name'])).' vs. '.jsconvert(re($get['gegner'])).'\', \''._played_at.';'._cw_xonx.';'._result.';'._comments_head.'\', \''.date("d.m.Y H:i", $get['datum'])._uhr.';'.jsconvert(re($get['xonx'])).';'.cw_result_nopic_nocolor($get['punkte'],$get['gpunkte']).';'.cnt($db['cw_comments'], "WHERE cw = '".$get['id']."'").'\')" onmouseout="DZCP.hideInfo()"';

            $topmatch .= show("menu/top_match", array("id" => $get['id'],
                                                      "clantag" => re(cut($get['clantag'],$llwars)),
                                                      "team" => re(cut($get['name'],$llwars)),
                                                      "game" => substr(strtoupper(str_replace('.'.re($get['icon']), '', re($get['icon']))), 0, 5),
                                                      "id" => $get['id'],
                                                      "gegner" => $gegner,
                                                      "squad" => $squad,
                                                      "hover" => $hover,
                                                      "info" => ($get['datum'] > time() ? date("d.m.Y", $get['datum']) : cw_result_nopic($get['punkte'],$get['gpunkte']))));
        }
    }

    return empty($topmatch) ? '<center style="margin:3px 0">'._no_top_match.'</center>' : '<table class="navContent" cellspacing="0">'.$topmatch.'</table>';
}