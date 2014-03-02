<?php
//-> Menu: last Wars
function l_wars() {
    global $db,$maxlwars,$llwars,$allowHover;
    $qry = db("SELECT s1.datum,s1.gegner,s1.id,s1.bericht,s1.xonx,s1.clantag,s1.punkte,s1.gpunkte,s1.squad_id,s2.icon,s2.name FROM ".$db['cw']." AS s1
               LEFT JOIN ".$db['squads']." AS s2 ON s1.squad_id = s2.id
               WHERE datum < ".time()."
               ORDER BY datum DESC
               LIMIT ".$maxlwars."");

    $lwars = '';
    while($get = _fetch($qry)) {
        if($allowHover == 1 || $allowHover == 2)
            $info = 'onmouseover="DZCP.showInfo(\''.jsconvert(re($get['name'])).' vs. '.jsconvert(re($get['gegner'])).'\', \''._played_at.';'._cw_xonx.';'._result.';'._comments_head.'\', \''.date("d.m.Y H:i", $get['datum'])._uhr.';'.jsconvert(re($get['xonx'])).';'.cw_result_nopic_nocolor($get['punkte'],$get['gpunkte']).';'.cnt($db['cw_comments'], "WHERE cw = '".$get['id']."'").'\')" onmouseout="DZCP.hideInfo()"';

        $lwars .= show("menu/last_wars", array("id" => $get['id'],
                                               "clantag" => re(cut($get['clantag'],$llwars)),
                                               "icon" => re($get['icon']),
                                               "info" => $info,
                                               "result" => cw_result_pic($get['punkte'],$get['gpunkte'])));
    }

    return empty($lwars) ? '' : '<table class="navContent" cellspacing="0">'.$lwars.'</table>';
}