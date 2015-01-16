<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 * Menu: next Wars
 */
function n_wars() {
    global $db;

    $qry = db("SELECT s1.`id`,s1.`datum`,s1.`clantag`,s1.`maps`,s1.`gegner`,s1.`squad_id`,s2.`icon`,s1.`xonx`,s2.`name` FROM `".$db['cw']."` AS `s1`
               LEFT JOIN `".$db['squads']."` AS `s2` ON s1.`squad_id` = s2.`id`
               WHERE s1.`datum` > ".time()."
               ORDER BY s1.`datum`
               LIMIT ".config('m_nwars').";");

    $nwars = '';
    if(_rows($qry)) {
        while($get = _fetch($qry)) {
            if(config('allowhover') == 1 || config('allowhover') == 2)
                $info = 'onmouseover="DZCP.showInfo(\''.jsconvert(re($get['name'])).' vs. '.jsconvert(re($get['gegner'])).'\', \''._datum.';'._cw_xonx.';'._cw_maps.';'._comments_head.'\', \''.date("d.m.Y H:i", $get['datum'])._uhr.';'.jsconvert(re($get['xonx'])).';'.jsconvert(re($get['maps'])).';'.cnt($db['cw_comments'],"WHERE cw = '".$get['id']."'").'\')" onmouseout="DZCP.hideInfo()"';

            $nwars .= show("menu/next_wars", array("id" => $get['id'],
                                                   "clantag" => re(cut($get['clantag'],config('l_nwars'))),
                                                   "icon" => re($get['icon']),
                                                   "info" => $info,
                                                   "datum" => date("d.m.:", $get['datum'])));
        }
    }

    return empty($nwars) ? '<center style="margin:2px 0">'._no_entrys.'</center>' : '<table class="navContent" cellspacing="0">'.$nwars.'</table>';
}