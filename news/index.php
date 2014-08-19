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
$where = _site_news;
$title = $pagetitle." - ".$where."";
$dir = "news";
define('_News', true);

## SECTIONS ##
//RSS News Feed erzeugen
function convert_feed($txt) {
    global $charset;
    $txt = stripslashes($txt);
    $txt = str_replace("&Auml;","Ae",$txt);
    $txt = str_replace("&auml;","ae",$txt);
    $txt = str_replace("&Uuml;","Ue",$txt);
    $txt = str_replace("&uuml;","ue",$txt);
    $txt = str_replace("&Ouml;","Oe",$txt);
    $txt = str_replace("&ouml;","oe",$txt);
    $txt = htmlentities($txt, ENT_QUOTES, $charset);
    $txt = str_replace("&amp;","&",$txt);
    $txt = str_replace("&lt;","<",$txt);
    $txt = str_replace("&gt;",">",$txt);
    $txt = str_replace("&#60;","<",$txt);
    $txt = str_replace("&#62;",">",$txt);
    $txt = str_replace("&#34;","\"",$txt);
    $txt = str_replace("&nbsp;"," ",$txt);
    $txt = str_replace("&szlig;","ss",$txt);
    $txt = preg_replace("#&(.*?);#is","",$txt);
    $txt = str_replace("&","&amp;",$txt);
    $txt = str_replace("", "\"",$txt);
    $txt = str_replace("", "\"",$txt);
    return strip_tags($txt);
}

function feed() {
    global $db,$pagetitle,$charset;
    if(!file_exists(basePath.'/rss.xml') || time() - filemtime(basePath.'/rss.xml') > feed_update_time) {
        $host = $_SERVER['HTTP_HOST'];
        $pfad = preg_replace("#^(.*?)\/(.*?)#Uis","$1",dirname($_SERVER['PHP_SELF']));
        $feed = '<?xml version="1.0" encoding="'.$charset.'" ?>';
        $feed .= "\r\n";
        $feed .= '<rss version="0.91">';
        $feed .= "\r\n";
        $feed .= '<channel>';
        $feed .= "\r\n";
        $feed .= '  <title>'.convert_feed($pagetitle).'</title>';
        $feed .= "\r\n";
        $feed .= '  <link>http://'.$host.'</link>';
        $feed .= "\r\n";
        $feed .= '  <description>Clannews von '.convert_feed(settings('clanname')).'</description>';
        $feed .= "\r\n";
        $feed .= '  <language>de-de</language>';
        $feed .= "\r\n";
        $feed .= '  <copyright>'.date("Y", time()).' '.convert_feed(settings('clanname')).'</copyright>';
        $feed .= "\r\n";

        $data = @fopen("../rss.xml","w+");
        @fwrite($data, $feed);
        $qry = db("SELECT * FROM ".$db['news']." WHERE intern = 0 AND public = 1 ORDER BY datum DESC LIMIT 15");
        while($get = _fetch($qry)) {
            $get1 = _fetch(db("SELECT nick FROM ".$db['users']." WHERE id = '".$get['autor']."'"));
            $feed .= '  <item>';
            $feed .= "\r\n";
            $feed .= '    <pubDate>'.date("r", $get['datum']).'</pubDate>';
            $feed .= "\r\n";
            $feed .= '    <author>'.convert_feed($get1['nick']).'</author>';
            $feed .= "\r\n";
            $feed .= '    <title>'.convert_feed($get['titel']).'</title>';
            $feed .= "\r\n";
            $feed .= '    <description>';
            $feed .= convert_feed($get['text']);
            $feed .= '    </description>';
            $feed .= "\r\n";
            $feed .= '    <link>http://'.$host.$pfad.'/news/?action=show&amp;id='.$get['id'].'</link>';
            $feed .= "\r\n";
            $feed .= '  </item>';
            $feed .= "\r\n";
            $data = @fopen("../rss.xml","w+");
            @fwrite($data, $feed);
        }

        $feed .= '</channel>';
        $feed .= "\r\n";
        $feed .= '</rss>';
        $data = @fopen("../rss.xml","w+");
        @fwrite($data, $feed);
    }
}

feed(); //NewsFeed

$action = empty($action) ? 'default' : $action;
if(file_exists(basePath."/news/case_".$action.".php"))
    require_once(basePath."/news/case_".$action.".php");

## INDEX OUTPUT ##
$title = $pagetitle." - ".$where."";
page($index, $title, $where);