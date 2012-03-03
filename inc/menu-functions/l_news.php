<?php
//-> Last News
function l_news()
{
  global $db,$maxlnews,$lnews,$allowHover;
    if(!permission("intnews")) $int = "AND intern = 0";
    $qry = db("SELECT id,titel,autor,datum,kat,public,timeshift FROM ".$db['news']."
               WHERE public = 1
							 AND datum <= ".time()."
			         ".$int."
               ORDER BY id DESC
               LIMIT ".$maxlnews."");

    while($get = _fetch($qry))
    {
      $qrykat = db("SELECT kategorie FROM ".$db['newskat']."
                    WHERE id = '".$get['kat']."'");
      $getkat = _fetch($qrykat);

      if($allowHover == 1)
      $info = 'onmouseover="DZCP.showInfo(\'<tr><td colspan=2 align=center padding=3 class=infoTop>'.jsconvert(re($get['titel'])).'</td></tr><tr><td><b>'._datum.':</b></td><td>'.date("d.m.Y H:i", $get['datum'])._uhr.'</td></tr><tr><td><b>'._autor.':</b></td><td>'.rawautor($get['autor']).'</td></tr><tr><td><b>'._news_admin_kat.':</b></td><td>'.jsconvert(re($getkat['kategorie'])).'</td></tr><tr><td><b>'._comments_head.':</b></td><td>'.cnt($db['newscomments'],"WHERE news = '".$get['id']."'").'</td></tr>\')" onmouseout="DZCP.hideInfo()"';

      $l_news .= show("menu/last_news", array("id" => $get['id'],
                                              "titel" => re(cut($get['titel'],$lnews)),
                                              "datum" => date("d.m.Y", $get['datum']),
                                              "info" => $info));
    }
  return empty($l_news) ? '' : '<table class="navContent" cellspacing="0">'.$l_news.'</table>';
}

?>
