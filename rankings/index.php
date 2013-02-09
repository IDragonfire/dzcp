<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath . "/inc/config.php");
include(basePath . "/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$where = _site_rankings;
$title = $pagetitle . " - " . $where . "";
$dir   = "rankings";
## SECTIONS ##
if (!isset($_GET['action']))
    $action = "";
else
    $action = $_GET['action'];

switch ($action):
    default:
        $qry = db("SELECT s1.id,s1.lastranking,s1.rank,s1.squad,s1.league,s1.url,s2.name
             FROM " . $db['rankings'] . " AS s1
             LEFT JOIN " . $db['squads'] . " AS s2
             ON s1.squad = s2.id
             ORDER BY s1.postdate DESC");
        if (_rows($qry)) {
            while ($get = _fetch($qry)) {
                $squad  = '<a href="../squads/?showsquad=' . $get['squad'] . '">' . re($get['name']) . '</a>';
                $league = '<a href="' . $get['url'] . '" target="_blank">' . $get['league'] . '</a>';
                $class  = ($color % 2) ? "contentMainSecond" : "contentMainFirst";
                $color++;
                
                $show .= show($dir . "/rankings_show", array(
                    "class" => $class,
                    "squad" => $squad,
                    "league" => $league,
                    "old" => $get['lastranking'],
                    "place" => $get['rank']
                ));
            }
        } else {
            $show = show(_no_entrys_yet, array(
                "colspan" => "5"
            ));
        }
        $index = show($dir . "/rankings", array(
            "head" => _rankings_head,
            "show" => $show,
            "squad" => _rankings_squad,
            "place" => _rankings_place,
            "league" => _rankings_league
        ));
        break;
endswitch;
## SETTINGS ##
$time_end = generatetime();
$time     = round($time_end - $time_start, 4);
page($index, $title, $where, $time);
## OUTPUT BUFFER END ##
gz_output();
?>