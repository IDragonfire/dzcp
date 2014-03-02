<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$dir = "linkus";
$where = _linkus;
$title = $pagetitle." - ".$where."";
## SECTIONS ##
switch ($action):
default:
  $qry = db("SELECT * FROM ".$db['linkus']."
             ORDER BY banner DESC");
  if(_rows($qry))
  {
    $cnt = 1;
    while($get = _fetch($qry))
    {
      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

      $banner = show(_linkus_bannerlink, array("id" => $get['id'],
                                               "banner" => re($get['text'])));

      if(permission("links"))
      {
        $edit = show("page/button_edit", array("id" => $get['id'],
                                               "action" => "action=admin&amp;do=edit",
                                               "title" => _button_title_edit));
        $delete = show("page/button_delete", array("id" => $get['id'],
                                                   "action" => "action=admin&amp;do=delete",
                                                   "title" => _button_title_del));
      } else {
        $edit = "";
        $delete = "";
      }

      $show .= show($dir."/linkus_show", array("class" => $class,
                                               "beschreibung" => re($get['beschreibung']),
                                               "cnt" => $cnt,
                                               "banner" => $banner,
                                               "besch" => re($get['beschreibung']),
                                                                     "url" => $get['url']));
      $cnt++;
    }
  } else {
    $show = _no_entrys_yet;
  }

  $index = show($dir."/linkus", array("head" => _linkus_head,
                                      "show" => $show));
break;
case 'link';
  $qry = db("SELECT url FROM ".$db['linkus']."
             WHERE id = '".intval($_GET['id'])."'");
  $get = _fetch($qry);

  header("Location: ".$get['url']);
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
