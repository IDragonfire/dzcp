<?php
//-> Top Downloads
function top_dl()
{
  global $db,$maxtopdl,$ltopdl,$allowHover;
  $qry = db("SELECT * FROM ".$db['downloads']." ORDER BY hits DESC
             LIMIT ".$maxtopdl."");
  while($get = _fetch($qry))
  {
    if($allowHover == 1)
    {
      $getkat = _fetch(db("SELECT name FROM ".$db['dl_kat']." WHERE id = '".$get['kat']."'"));
      $info = 'onmouseover="DZCP.showInfo(\'<tr><td colspan=2 align=center padding=3 class=infoTop>'.jsconvert(re($get['download'])).'</td></tr><tr><td><b>'._datum.':</b></td><td>'.date("d.m.Y H:i", $get['date'])._uhr.'</td></tr><tr><td><b>'._dl_dlkat.':</b></td><td>'.jsconvert(re($getkat['name'])).'</td></tr><tr><td><b>'._hits.':</b></td><td>'.$get['hits'].'</td></tr>\')" onmouseout="DZCP.hideInfo()"';
    }

    $top_dl .= show("menu/top_dl", array("id" => $get['id'],
                                         "titel" => cut(re($get['download']),$ltopdl),
                                         "info" => $info,
                                         "hits" => $get['hits']));
  }

  return empty($top_dl) ? '' : '<table class="navContent" cellspacing="0">'.$top_dl.'</table>';
}
//-> Last Forumtopics

?>
