<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$dir = "sponsors";
$where = _site_sponsor;
## SECTIONS ##
if(!isset($_GET['action'])) $action = "";
else $action = $_GET['action'];

switch ($action):
default:

  $qry = db("SELECT * FROM ".$db['sponsoren']."
  			 WHERE site = 1
             ORDER BY pos");
  while($get = _fetch($qry))
  {
    if(empty($get['slink']))
    {  
  	  $banner = show(_sponsors_bannerlink, array("id" => $get['id'],
                                                 "title" => str_replace('http://', '', re($get['link'])),
                                                 "banner" => "../banner/sponsors/site_".$get['id'].".".re($get['send'])));
    } else {
      $banner = show(_sponsors_bannerlink, array("id" => $get['id'],
                                                 "title" => str_replace('http://', '', re($get['link'])),
                                                 "banner" => $get['slink']));
    }

     $show .= show($dir."/sponsors_show", array("class" => $class,
												"beschreibung" => bbcode($get['beschreibung']),
												"hits" => $get['hits'],
												"hit" => _hits,
												"banner" => $banner));
  }

  $index = show($dir."/sponsors", array("head" => _sponsor_head,
                                        "show" => $show));
break;
case 'link';

  $qry = db("SELECT link FROM ".$db['sponsoren']."
             WHERE id = '".intval($_GET['id'])."'");
  $get = _fetch($qry);

  $upd = db("UPDATE ".$db['sponsoren']."
             SET `hits` = hits+1
             WHERE id = '".intval($_GET['id'])."'");

  header("Location: ".$get['link']);
break;
endswitch;
## SETTINGS ##
$title = $pagetitle." - ".$where."";
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where,$time);
## OUTPUT BUFFER END ##
gz_output();
?>
