<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$where = _site_gallery;
$title = $pagetitle." - ".$where."";
$dir = "gallery";
## SECTIONS ##
if(!isset($_GET['action'])) $action = "";
else $action = $_GET['action'];

switch ($action):
default:
  $qry = db("SELECT * FROM ".$db['gallery']."
             ORDER BY id DESC");
  if(_rows($qry))
  {
    while($get = _fetch($qry))
    {
      $files = get_files("images/");
  
      $cnt = 0;
      for($i=0; $i<count($files); $i++)
      {
        if(preg_match("#^".$get['id']."_(.*?).(gif|jpg|jpeg|png)#",strtolower($files[$i]))!=FALSE)
        {
          $cnt++;
        }
      }
      
      if($cnt == 1) $cntpics = _gallery_image;
      else $cntpics = _gallery_images;
      
      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
      $show .= show($dir."/gallery_show", array("link" => re($get['kat']),
                                                "class" => $class,
                                                "images" => $cntpics,
                                                "id" => $get['id'],
                                                "beschreibung" => bbcode($get['beschreibung']),
  	    												    	          "cnt" => $cnt));
      
    }
  } else $show = show(_no_entrys_yet, array("colspan" => "10"));

  $index = show($dir."/gallery",array("show" => $show,
                                      "head" => _gallery_head));
break;
case 'show';
  $files = get_files("images/");
  $t = 1;
  $cnt = 0;
  for($i=0; $i<count($files); $i++)
  {
    if(preg_match("#^".$_GET['id']."_(.*?).(gif|jpg|jpeg|png)#",strtolower($files[$i]))!=FALSE)
    {
      $tr1 = "";
      $tr2 = "";

      if($t == 0 || $t == 1) $tr1 = "<tr>";
      if($t == $gallery)
      {
        $tr2 = "</tr>";
        $t = 0;
      }

      if(permission("gallery"))
      {
        $del = show("page/button_delete_gallery", array("id" => "",
                                                      "action" => "admin=gallery&amp;do=delete&amp;pic=".$files[$i],
                                                      "title" => _button_title_del,
                                                      "del" => convSpace(_confirm_del_galpic)));
      } else {
        $del = "";
      }

      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
      $show .= show($dir."/show_gallery", array("img" => gallery_size($files[$i]),
                                                "tr1" => $tr1,
                                                "max" => $gallery,
                                                "width" => intval(round(100/$gallery)),
                                                "del" => $del,
                                                "tr2" => $tr2));
      $t++;
      $cnt++;
    }
  }

  if(is_float($cnt/$gallery))
  {
    for($e=$t; $e<=$gallery; $e++)
    {
      $end .= '<td class="contentMainFirst"></td>';
    }
    $end = $end."</tr>";
  }

  $qry = db("SELECT * FROM ".$db['gallery']."
             WHERE id = '".intval($_GET['id'])."'");
  $get = _fetch($qry);

  $index = show($dir."/show", array("gallery" => re($get['kat']),
                                    "show" => $show,
                                    "beschreibung" => bbcode($get['beschreibung']),
                                    "end" => $end,
                                    "back" => _gal_back,
                                    "head" => _subgallery_head));
break;
endswitch;
## SETTINGS ##
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where,$time);
## OUTPUT BUFFER END ##
gz_output();
?>