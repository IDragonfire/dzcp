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
$dir = "links";
$where = _site_links;

## SECTIONS ##
switch ($action):
    default:
        $qry = db("SELECT * FROM ".$db['links']." ORDER BY banner DESC");
        if(_rows($qry)) {
            while($get = _fetch($qry)) {
                if($get['banner']) {
                    $banner = show(_links_bannerlink, array("id" => $get['id'],
                                                            "banner" => re($get['text'])));
                } else {
                    $banner = show(_links_textlink, array("id" => $get['id'],
                                                          "text" => str_replace('http://','',re($get['url']))));
                }

                $show .= show($dir."/links_show", array("beschreibung" => bbcode($get['beschreibung']),
                                                        "hits" => $get['hits'],
                                                        "hit" => _hits,
                                                        "banner" => $banner));
            }
        }

        if(empty($show))
            $show = _no_entrys_yet;

        $index = show($dir."/links", array("head" => _links_head, "show" => $show));
    break;
    case 'link';
        db("UPDATE ".$db['links']." SET `hits` = hits+1 WHERE `id` = '".intval($_GET['id'])."'");
        $get = db("SELECT `url` FROM ".$db['links']." WHERE `id` = '".intval($_GET['id'])."'",false,true);
        header("Location: ".$get['url']);
    break;
endswitch;

## INDEX OUTPUT ##
$title = $pagetitle." - ".$where."";
page($index, $title, $where);