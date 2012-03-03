<?php
function newsticker()
{
  global $db, $allowHover;

  if(!permission("intnews")) $int = "AND intern = 0";
  $qry = db("SELECT id,titel,autor,datum,kat FROM ".$db['news']." WHERE public = '1'AND datum <= '".time()."' ".$int." ORDER BY id DESC LIMIT 20");
  while($get = _fetch($qry))
  {
    if($allowHover == 1)
    {
      $getkat = _fetch(db("SELECT kategorie FROM ".$db['newskat']." WHERE id = '".$get['kat']."'"));

      $info = 'onmouseover="DZCP.showInfo(\'<tr><td colspan=2 align=center padding=3 class=infoTop>'.jsconvert(re($get['titel'])).'</td></tr><tr><td><b>'._datum.':</b></td><td>'.date("d.m.Y H:i", $get['datum'])._uhr.'</td></tr><tr><td><b>'._autor.':</b></td><td>'.rawautor($get['autor']).'</td></tr><tr><td><b>'._news_admin_kat.':</b></td><td>'.jsconvert(re($getkat['kategorie'])).'</td></tr><tr><td><b>'._comments_head.':</b></td><td>'.cnt($db['newscomments'],"WHERE news = '".$get['id']."'").'</td></tr>\')" onmouseout="DZCP.hideInfo()"';
    }

    $news .= '<a href="../news/?action=show&amp;id='.$get['id'].'" '.$info.'>'.re($get['titel']).'</a> | ';
  }


  return show("menu/newsticker", array("news" => $news));
}
?>
