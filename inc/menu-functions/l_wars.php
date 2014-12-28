<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 * Menu: last Wars
 */
function l_wars() {
    global $db,$picformat;
    $qry = db("SELECT s1.`datum`,s1.`gegner`,s1.`id`,s1.`bericht`,s1.`xonx`,s1.`clantag`,s1.`punkte`,s1.`gpunkte`,s1.`squad_id`,s2.`icon`,s2.`name` FROM `".$db['cw']."` AS `s1`
               LEFT JOIN `".$db['squads']."` AS `s2` ON s1.`squad_id` = s2.`id`
               WHERE `datum` < ".time()."
               ORDER BY `datum` DESC
               LIMIT ".config('m_lwars').";");

    $lwars = '';
    if(_rows($qry)) {
        while($get = _fetch($qry)) {
            if(config('allowhover') == 1 || config('allowhover') == 2)
                $info = 'onmouseover="DZCP.showInfo(\''.jsconvert(re($get['name'])).' vs. '.jsconvert(re($get['gegner'])).'\', \''._played_at.';'._cw_xonx.';'._result.';'._comments_head.'\', \''.date("d.m.Y H:i", $get['datum'])._uhr.';'.jsconvert(re($get['xonx'])).';'.cw_result_nopic_nocolor($get['punkte'],$get['gpunkte']).';'.cnt($db['cw_comments'], "WHERE cw = '".$get['id']."'").'\')" onmouseout="DZCP.hideInfo()"';

            $lwars .= show("menu/last_wars", array("id" => $get['id'],
                                                   "clantag" => re(cut($get['clantag'],config('l_lwars'))),
                                                   "icon" => re($get['icon']),
                                                   "info" => $info,
                                                   "result" => cw_result_pic($get['punkte'],$get['gpunkte'])));
        }
    }

    return empty($lwars) ? '<center style="margin:2px 0">'._no_entrys.'</center>' : '<table class="navContent" cellspacing="0">'.$lwars.'</table>';
}