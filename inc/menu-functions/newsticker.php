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

      $info = 'onmouseover="DZCP.showInfo(\''.jsconvert(re($get['titel'])).'\', \''._datum.';'._autor.';'._news_admin_kat.';'._comments_head.'\', \''.date("d.m.Y H:i", $get['datum'])._uhr.';'.fabo_autor($get['autor']).';'.jsconvert(re($getkat['kategorie'])).';'.cnt($db['newscomments'],"WHERE news = '".$get['id']."'").'\')" onmouseout="DZCP.hideInfo()"';
    }

    $news .= '<a href="../news/?action=show&amp;id='.$get['id'].'" '.$info.'>'.re($get['titel']).'</a> | ';
  }


  return show("menu/newsticker", array("news" => $news));
}
?>
