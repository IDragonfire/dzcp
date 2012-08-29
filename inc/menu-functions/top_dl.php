<?php
//-> Top Downloads
function top_dl()
{
  global $db,$maxtopdl,$ltopdl,$allowHover;
  $qry = db("SELECT * FROM ".$db['downloads']." ORDER BY hits DESC
             LIMIT ".$maxtopdl."");
	$top_dl = "";
  while($get = _fetch($qry))
  {
    if($allowHover == 1)
    {
      $getkat = _fetch(db("SELECT name FROM ".$db['dl_kat']." WHERE id = '".$get['kat']."'"));
      $info = 'onmouseover="DZCP.showInfo(\''.jsconvert(re($get['download'])).'\', \''._datum.';'._dl_dlkat.';'._hits.'\', \''.date("d.m.Y H:i", $get['date'])._uhr.';'.jsconvert(re($getkat['name'])).';'.$get['hits'].'\')" onmouseout="DZCP.hideInfo()"';
    }

    $top_dl .= show("menu/top_dl", array("id" => $get['id'],
                                         "titel" => cut(re($get['download']),$ltopdl),
                                         "info" => $info,
                                         "hits" => $get['hits']));
  }

  return empty($top_dl) ? '' : '<table class="navContent" cellspacing="0">'.$top_dl.'</table>';
}
?>
