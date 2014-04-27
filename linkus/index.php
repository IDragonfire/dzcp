<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$dir = "linkus";
$where = _linkus;
$title = $pagetitle." - ".$where."";

## SECTIONS ##
switch ($action):
    default:
        $qry = db("SELECT * FROM ".$db['linkus']." ORDER BY banner DESC");
        if(_rows($qry)) {
            while($get = _fetch($qry)) {
                $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                $banner = show(_linkus_bannerlink, array("id" => $get['id'],
                                                         "banner" => re($get['text'])));
                $edit = ""; $delete = "";
                if(permission("links")) {
                    $edit = show("page/button_edit", array("id" => $get['id'],
                                                           "action" => "action=admin&amp;do=edit",
                                                           "title" => _button_title_edit));

                    $delete = show("page/button_delete", array("id" => $get['id'],
                                                               "action" => "action=admin&amp;do=delete",
                                                               "title" => _button_title_del));
                }

                $show .= show($dir."/linkus_show", array("class" => $class,
                                                         "beschreibung" => re($get['beschreibung']),
                                                         "cnt" => $color,
                                                         "banner" => $banner,
                                                         "besch" => re($get['beschreibung']),
                                                         "url" => $get['url']));
            }
      }

      if(empty($show))
        $show = _no_entrys_yet;

      $index = show($dir."/linkus", array("head" => _linkus_head,
                                          "show" => $show));
    break;
    case 'link';
        $get = db("SELECT `url` FROM ".$db['linkus']." WHERE `id` = '".intval($_GET['id'])."'",false,true);
        header("Location: ".$get['url']);
    break;
endswitch;

## INDEX OUTPUT ##
$title = $pagetitle." - ".$where."";
page($index, $title, $where);