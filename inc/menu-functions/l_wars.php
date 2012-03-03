<?php
//-> last Wars Menu
function l_wars()
{
  global $db,$maxlwars,$llwars,$allowHover;
  $qry = db("SELECT s1.datum,s1.gegner,s1.id,s1.bericht,s1.xonx,s1.clantag,s1.punkte,s1.gpunkte,s1.squad_id,s2.icon,s2.name FROM ".$db['cw']." AS s1
             LEFT JOIN ".$db['squads']." AS s2 ON s1.squad_id = s2.id
             WHERE datum < ".time()."
             ORDER BY datum DESC
             LIMIT ".$maxlwars."");
    while($get = _fetch($qry))
    {
      if($allowHover == 1 || $allowHover == 2)
        $info = 'onmouseover="DZCP.showInfo(\'<tr><td colspan=2 align=center padding=3 class=infoTop>'.jsconvert(re($get['name'])).'<br/>vs.<br/> '.jsconvert(re($get['gegner'])).'</td></tr><tr><td><b>'._played_at.':</b></td><td>'.date("d.m.Y H:i", $get['datum'])._uhr.'</td></tr><tr><td><b>'._cw_xonx.':</b></td><td>'.jsconvert(re($get['xonx'])).'</td></tr><tr><td><b>'._result.':</b></td><td>'.cw_result_nopic_raw($get['punkte'],$get['gpunkte']).'</td></tr><tr><td><b>'._comments_head.':</b></td><td>'.cnt($db['cw_comments'], "WHERE cw = '".$get['id']."'").'</td></tr>\')" onmouseout="DZCP.hideInfo()"';

      $lwars .= show("menu/last_wars", array("id" => $get['id'],
                                             "clantag" => re(cut($get['clantag'],$llwars)),
                                             "icon" => re($get['icon']),
                                             "info" => $info,
                                             "result" => cw_result_pic($get['punkte'],$get['gpunkte'])));
    }

  return empty($lwars) ? '' : '<table class="navContent" cellspacing="0">'.$lwars.'</table>';
}
?>
