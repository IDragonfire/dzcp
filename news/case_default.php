<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

if(defined('_News')) {
    if(!($kat = isset($_GET['kat']) ? intval($_GET['kat']) : 0)) {
        $navKat = 'lazy';
        $n_kat = '';
        $navWhere = "WHERE public = 1 ".(!permission("intnews") ? "AND `intern` = '0'" : '')."";
    } else {
        $n_kat = "AND kat = '".$kat."'";
        $navKat = $kat;
        $navWhere = "WHERE kat = '".$kat."' AND public = 1 ".(!permission("intnews") ? "AND `intern` = '0'" : '')."";
    }

    //Sticky News
    $qry = db("SELECT * FROM ".$db['news']."
               WHERE sticky >= ".time()."
               AND datum <= ".time()."
               AND public = 1 ".(permission("intnews") ? "" : "AND `intern` = '0'")."
               ".$n_kat."
               ORDER BY datum DESC
               LIMIT ".($page - 1)*config('m_news').",".config('m_news')."");

    $show_sticky = '';
    if(_rows($qry)) {
        while($get = _fetch($qry)) {
            $getkat = db("SELECT katimg FROM ".$db['newskat']." WHERE id = '".$get['kat']."'",false,true);
            $count = cnt($db['newscomments'], " WHERE news = '".$get['id']."'");

            $comments = show(_news_comments, array("comments" => '0', "id" => $get['id']));
            if($count >= 2)
                $comments = show(_news_comments, array("comments" => $count, "id" => $get['id']));
            else if($count == 1)
                $comments = show(_news_comment, array("comments" => "1", "id" => $get['id']));

            $klapp = "";
            if($get['klapptext'])
                $klapp = show(_news_klapplink, array("klapplink" => re($get['klapplink']),
                                                     "which" => "expand",
                                                     "id" => $get['id']));

            $viewed = show(_news_viewed, array("viewed" => $get['viewed']));

            $links1 = "";
            if(!empty($get['url1'])) {
                $rel = _related_links;
                $links1 = show(_news_link, array("link" => re($get['link1']),
                                                 "url" => $get['url1']));
            }

            $links2 = "";
            if(!empty($get['url2'])) {
              $rel = _related_links;
              $links2 = show(_news_link, array("link" => re($get['link2']),
                                               "url" => $get['url2']));
            }

            $links3 = "";
            if(!empty($get['url3'])) {
                $rel = _related_links;
                $links3 = show(_news_link, array("link" => re($get['link3']),
                                                 "url" => $get['url3']));
            }

            $links = "";
            if(!empty($links1) || !empty($links2) || !empty($links3))
                $links = show(_news_links, array("link1" => $links1,
                                                 "link2" => $links2,
                                                 "link3" => $links3,
                                                 "rel" => $rel));

            $intern = $get['intern'] ? _votes_intern : "";
            $newsimage = '../inc/images/newskat/'.$getkat['katimg'];
            foreach($picformat as $tmpendung) {
                if(file_exists(basePath."/inc/images/uploads/news/".$get['id'].".".$tmpendung)) {
                    $newsimage = '../inc/images/uploads/news/'.$get['id'].'.'.$tmpendung;
                    break;
                }
            }

            $show_sticky .= show($dir."/news_show", array("titel" => re($get['titel']),
                                                          "kat" => $newsimage,
                                                          "id" => $get['id'],
                                                          "comments" => $comments,
                                                          "showmore" => "",
                                                          "dp" => "none",
                                                          "dir" => $designpath,
                                                          "nautor" => _autor,
                                                          "intern" => $intern,
                                                          "sticky" => _news_sticky,
                                                          "ndatum" => _datum,
                                                          "ncomments" => _news_kommentare.":",
                                                          "klapp" => $klapp,
                                                          "more" => bbcode($get['klapptext']),
                                                          "viewed" => $viewed,
                                                          "text" => bbcode(re($get['text'])),
                                                          "datum" => date("d.m.y H:i", $get['datum'])._uhr,
                                                          "links" => $links,
                                                          "autor" => autor($get['autor'])));
        }
    }

    //News
    $qry = db("SELECT * FROM ".$db['news']."
               WHERE sticky < ".time()." AND datum <= ".time()." AND public = 1 ".(permission("intnews") ? "" : "AND `intern` = '0'")."
               ".$n_kat."
               ORDER BY datum DESC
               LIMIT ".($page - 1)*config('m_news').",".config('m_news')."");

    if(_rows($qry)) {
        while($get = _fetch($qry)) {
            $getkat = db("SELECT katimg FROM ".$db['newskat']." WHERE id = '".$get['kat']."'",false,true);
            $count = cnt($db['newscomments'], " WHERE news = '".$get['id']."'");

            $comments = show(_news_comments, array("comments" => '0', "id" => $get['id']));
            if($count >= 2)
                $comments = show(_news_comments, array("comments" => $count, "id" => $get['id']));
            else if($count == 1)
                $comments = show(_news_comment, array("comments" => "1", "id" => $get['id']));

            $klapp = "";
            if($get['klapptext'])
                $klapp = show(_news_klapplink, array("klapplink" => re($get['klapplink']),
                                                     "which" => "expand",
                                                     "id" => $get['id']));

            $viewed = show(_news_viewed, array("viewed" => $get['viewed']));

            $links1 = "";
            if(!empty($get['url1'])) {
                $rel = _related_links;
                $links1 = show(_news_link, array("link" => re($get['link1']),
                                                 "url" => $get['url1']));
            }

            $links2 = "";
            if(!empty($get['url2'])) {
              $rel = _related_links;
              $links2 = show(_news_link, array("link" => re($get['link2']),
                                               "url" => $get['url2']));
            }

            $links3 = "";
            if(!empty($get['url3'])) {
                $rel = _related_links;
                $links3 = show(_news_link, array("link" => re($get['link3']),
                                                 "url" => $get['url3']));
            }

            $links = "";
            if(!empty($links1) || !empty($links2) || !empty($links3))
                $links = show(_news_links, array("link1" => $links1,
                                                 "link2" => $links2,
                                                 "link3" => $links3,
                                                 "rel" => $rel));

            $intern = $get['intern'] ? _votes_intern : "";
            $newsimage = '../inc/images/newskat/'.$getkat['katimg'];
            foreach($picformat as $tmpendung) {
                if(file_exists(basePath."/inc/images/uploads/news/".$get['id'].".".$tmpendung)) {
                    $newsimage = '../inc/images/uploads/news/'.$get['id'].'.'.$tmpendung;
                    break;
                }
            }

            $show .= show($dir."/news_show", array("titel" => re($get['titel']),
                                                   "kat" => $newsimage,
                                                   "id" => $get['id'],
                                                   "comments" => $comments,
                                                   "showmore" => "",
                                                   "dp" => "none",
                                                   "nautor" => _autor,
                                                   "dir" => $designpath,
                                                   "intern" => $intern,
                                                   "sticky" => "",
                                                   "ndatum" => _datum,
                                                   "ncomments" => _news_kommentare.":",
                                                   "klapp" => $klapp,
                                                   "more" => bbcode($get['klapptext']),
                                                   "viewed" => $viewed,
                                                   "text" => bbcode($get['text']),
                                                   "datum" => date("d.m.y H:i", $get['datum'])._uhr,
                                                   "links" => $links,
                                                   "autor" => autor($get['autor'])));
        }
    }

    $qrykat = db("SELECT * FROM ".$db['newskat']."");
    $kategorien = '';
    if(_rows($qrykat)) {
        while($getkat = _fetch($qrykat)) {
            $sel = (isset($_GET['kat']) && $_GET['kat'] == $getkat['id'] ? 'selected' : '');
            $kategorien .= "<option value='".$getkat['id']."' ".$sel.">".$getkat['kategorie']."</option>";
        }
    }

    $index = show($dir."/news", array("show" => $show,
                                      "show_sticky" => $show_sticky,
                                      "nav" => nav(cnt($db['news'],$navWhere),config('m_news'),"?kat=".$navKat),
                                      "kategorien" => $kategorien,
                                      "choose" => _news_kat_choose,
                                      "archiv" => _news_archiv));
}