<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_News')) {

        header("Content-type: text/html; charset=utf-8");
        $getkat = db("SELECT katimg FROM ".$db['newskat']." WHERE id = '".intval($_POST['kat'])."'",false,true);

        $klapp = "";
        if($_POST['klapptitel']) {
            $klapp = show(_news_klapplink, array("klapplink" => re($_POST['klapptitel']),
                                                 "which" => "collapse",
                                                 "id" => 0));
        }

        $links1 = ""; $rel = "";
        if(!empty($_POST['url1'])) {
            $rel = _related_links;
            $links1 = show(_news_link, array("link" => re($_POST['link1']),
                                             "url" => links($_POST['url1'])));
        }

        $links2 = "";
        if(!empty($_POST['url2'])) {
            $rel = _related_links;
            $links2 = show(_news_link, array("link" => re($_POST['link2']),
                                             "url" => links($_POST['url2'])));
        }

        $links3 = "";
        if(!empty($_POST['url3'])) {
            $rel = _related_links;
            $links3 = show(_news_link, array("link" => re($_POST['link3']),
                                             "url" => links($_POST['url3'])));
        }

        $links = '';
        if(!empty($links1) || !empty($links2) || !empty($links3)) {
            $links = show(_news_links, array("link1" => $links1,
                                             "link2" => $links2,
                                             "link3" => $links3,
                                             "rel" => $rel));
        }

        $intern = ''; $sticky = '';
        if(isset($_POST['intern']) && $_POST['intern'] == 1) $intern = _votes_intern;
        if(isset($_POST['sticky']) && $_POST['sticky'] == 1) $sticky = _news_sticky;

        $newsimage = '../inc/images/newskat/'.re($getkat['katimg']);
        $viewed = show(_news_viewed, array("viewed" => '0'));
        $index = show($dir."/news_show_full", array("titel" => $_POST['titel'],
                                               "kat" => $newsimage,
                                               "id" => '_prev',
                                               "comments" => _news_comments_prev,
                                               "showmore" => "",
                                               "dp" => "",
                                               "dir" => $designpath,
                                               "nautor" => _autor,
                                               "intern" => $intern,
                                               "sticky" => $sticky,
                                               "ndatum" => _datum,
                                               "ncomments" => _news_kommentare.":",
                                               "klapp" => $klapp,
                                               "more" => bbcode($_POST['morenews'],true),
                                               "viewed" => $viewed,
                                               "text" => bbcode($_POST['newstext'],true),
                                               "datum" => date("d.m.y H:i", time())._uhr,
                                               "links" => $links,
                                               "autor" => autor($_SESSION['id'])));

        echo utf8_encode('<table class="mainContent" cellspacing="1">'.$index.'</table>');

        if(!mysqli_persistconns)
            $mysql->close(); //MySQL

        exit();
}