<?php
//-> Last Articles
function l_artikel()
{
  global $db,$maxlartikel,$lartikel,$allowHover;
    $qry = db("SELECT id,titel,text,autor,datum,kat,public FROM ".$db['artikel']."
			   WHERE public = 1
               ORDER BY id DESC
               LIMIT ".$maxlartikel."");
    if(_rows($qry))
    {
      while ($get = _fetch($qry))
      {
        $qrykat = db("SELECT kategorie FROM ".$db['newskat']."
                      WHERE id = '".$get['kat']."'");
        $getkat = _fetch($qrykat);
        $text = strip_tags($get['text']);

        if($allowHover == 1)
        $info = 'onmouseover="DZCP.showInfo(\'<tr><td colspan=2 align=center padding=3 class=infoTop>'.jsconvert(re($get['titel'])).'</td></tr><tr><td><b>'._datum.':</b></td><td>'.date("d.m.Y H:i", $get['datum'])._uhr.'</td></tr><tr><td><b>'._autor.':</b></td><td>'.rawautor($get['autor']).'</td></tr><tr><td><b>'._news_admin_kat.':</b></td><td>'.jsconvert(re($getkat['kategorie'])).'</td></tr><tr><td><b>'._comments_head.':</b></td><td>'.cnt($db['acomments'],"WHERE artikel = '".$get['id']."'").'</td></tr>\')" onmouseout="DZCP.hideInfo()"';

        $l_articles .= show("menu/last_artikel", array("id" => $get['id'],
                                                       "titel" => re(cut($get['titel'],$lartikel)),
                                                       "text" => cut(bbcode($text),260),
                                                       "datum" => date("d.m.Y", $get['datum']),
                                                       "info" => $info));
      }
    }

  return empty($l_articles) ? '' : '<table class="navContent" cellspacing="0">'.$l_articles.'</table>';
}

?>
