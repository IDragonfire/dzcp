<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$where = _site_rankings;
$title = $pagetitle." - ".$where."";
$dir = "rankings";

## SECTIONS ##
switch ($action):
default:
   if(!empty($_GET['orderby']) && in_array($_GET['orderby'],array("name","rank","league"))) {
          $qry = db("SELECT s1.id,s1.lastranking,s1.rank,s1.squad,s1.league,s1.url,s2.name
             FROM ".$db['rankings']." AS s1
             LEFT JOIN ".$db['squads']." AS s2
             ON s1.squad = s2.id
             ORDER BY ".mysqli_real_escape_string($mysql, $_GET['orderby']." ".$_GET['order'])."");
   }
   else { $qry = db("SELECT s1.id,s1.lastranking,s1.rank,s1.squad,s1.league,s1.url,s2.name
             FROM ".$db['rankings']." AS s1
             LEFT JOIN ".$db['squads']." AS s2
             ON s1.squad = s2.id
             ORDER BY s1.postdate DESC");
  }
  if(_rows($qry))
  {
    while($get = _fetch($qry))
    {
      $squad = '<a href="../squads/?showsquad='.$get['squad'].'">'.re($get['name']).'</a>';
      $league = '<a href="'.$get['url'].'" target="_blank">'.$get['league'].'</a>';
      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

      $show .= show($dir."/rankings_show", array("class" => $class,
                                                 "squad" => $squad,
                                                 "league" => $league,
                                                 "old" => $get['lastranking'],
                                                 "place" => $get['rank']));
    }
  } else {
    $show = show(_no_entrys_yet, array("colspan" => "5"));
  }
    $index = show($dir."/rankings", array("head" => _rankings_head,
                                          "show" => $show,
                                          "squad" => _rankings_squad,
                                          "place" => _rankings_place,
                                          "order_squad" => orderby('name'),
                                          "order_place" => orderby('rank'),
                                          "order_league" => orderby('league'),
                                          "league" => _rankings_league));
break;
endswitch;

## INDEX OUTPUT ##
page($index, $title, $where);

## OUTPUT BUFFER END ##
gz_output();