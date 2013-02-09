<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath . "/inc/config.php");
include(basePath . "/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$dir   = "links";
$where = _site_links;
## SECTIONS ##
if (!isset($_GET['action']))
    $action = "";
else
    $action = $_GET['action'];

switch ($action):
    default:
        if (permission("links"))
            $admin = _links_admin;
        else
            $admin = "";
        
        $qry = db("SELECT * FROM " . $db['links'] . "
             ORDER BY banner DESC");
        while ($get = _fetch($qry)) {
            if ($get['banner'] == "1") {
                $banner = show(_links_bannerlink, array(
                    "id" => $get['id'],
                    "banner" => re($get['text'])
                ));
            } else {
                $banner = show(_links_textlink, array(
                    "id" => $get['id'],
                    "text" => str_replace('http://', '', re($get['url']))
                ));
            }
            
            $show .= show($dir . "/links_show", array(
                "class" => $class,
                "beschreibung" => bbcode($get['beschreibung']),
                "hits" => $get['hits'],
                "hit" => _hits,
                "banner" => $banner
            ));
        }
        
        $index = show($dir . "/links", array(
            "head" => _links_head,
            "show" => $show
        ));
        break;
    case 'link';
        
        $qry = db("SELECT url FROM " . $db['links'] . "
             WHERE id = '" . intval($_GET['id']) . "'");
        $get = _fetch($qry);
        
        $upd = db("UPDATE " . $db['links'] . "
             SET `hits` = hits+1
             WHERE id = '" . intval($_GET['id']) . "'");
        
        header("Location: " . $get['url']);
        break;
endswitch;
## SETTINGS ##
$title    = $pagetitle . " - " . $where . "";
$time_end = generatetime();
$time     = round($time_end - $time_start, 4);
page($index, $title, $where, $time);
## OUTPUT BUFFER END ##
gz_output();
?>
