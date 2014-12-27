<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

if(defined('_News')) {
        if(permission("intnews")) {
            $intern = "WHERE `public` = 1";
            $intern2 = "WHERE `intern` = 1 OR `intern` = 0 AND `datum` <= ".time()." AND `public` = 1";
        } else {
            $intern = "AND `intern` = 0 AND `public` = 1";
            $intern2 = "WHERE `intern` = 0 AND `datum` <= ".time()." AND `public` = 1";
        }

        if(isset($_GET['page'])) {
            $psearch = isset($_GET['search']) ? $_GET['search'] : '';
            $pyear = isset($_GET['year']) ? $_GET['year'] : '';
            $pmonth = isset($_GET['month']) ? $_GET['month'] : '';
        } else {
            $psearch = isset($_POST['search']) ? $_POST['search'] : '';
            $pyear = isset($_POST['year']) ? $_POST['year'] : '';
            $pmonth = isset($_POST['month']) ? $_POST['month'] : '';
        }

        $kat = isset($_GET['kat']) ? intval($_GET['kat']) : 0;
        $n_kat = !$kat ? "" : "AND kat = '".$kat."'";

        if(($search = isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : false)) {
            $qry = db("SELECT `id`,`titel`,`autor`,`datum`,`kat`,`text`
                      FROM ".$db['news']."
                      WHERE `text` LIKE '%".$search."%'
                      ".$intern."
                      AND `datum` <= ".time()."
                      OR `klapptext` LIKE '%".$search."%'
                      ".$intern."
                      AND `datum` <= ".time()."
                      ORDER BY `datum` DESC
                      LIMIT ".($page - 1)*config('m_archivnews').",".config('m_archivnews')."");

            $entrys = cnt($db['news'], " WHERE text LIKE '%".$search."%' OR klapptext LIKE '%".$search."%' ".$intern."");

        } else if($pyear) {
            $from = mktime(0,0,0,$pmonth,1,$pyear);
            $til = mktime(0,0,0,$pmonth+1,1,$pyear);

            $qry = db("SELECT id,titel,autor,datum,kat,text FROM ".$db['news']."
                       WHERE datum BETWEEN ".$from ." AND ".$til."
                       ".$intern."
                       ORDER BY datum DESC
                       LIMIT ".($page - 1)*config('m_archivnews').",".config('m_archivnews')."");
            $entrys = cnt($db['news'], " WHERE datum BETWEEN ".$from." AND ".$til." ".$intern."");
        } else {
            $qry = db("SELECT id,titel,autor,datum,kat,text
                       FROM ".$db['news']."
                       ".$intern2."
                       ".$n_kat."
                       ".orderby_sql(array("datum","autor","titel","kat"), 'ORDER BY datum DESC')."
                       LIMIT ".($page - 1)*config('m_archivnews').",".config('m_archivnews')."");
            $entrys = cnt($db['news'], " ".$intern2." ".$n_kat);
        }

        while($get = _fetch($qry)) {
            $getk = db("SELECT kategorie FROM ".$db['newskat']." WHERE id = '".$get['kat']."'",false,true);
            $comments = cnt($db['newscomments'], " WHERE news = ".$get['id']."");
            $titel = show(_news_show_link, array("titel" => cut(re($get['titel']),config('l_newsarchiv')), "id" => $get['id']));
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/archiv_show", array("autor" => autor($get['autor']),
                                                     "date" => date("d.m.y", $get['datum']),
                                                     "titel" => $titel,
                                                     "class" => $class,
                                                     "kat" => re($getk['kategorie']),
                                                     "comments" => $comments));
        }

        $y = db("SELECT datum FROM ".$db['news']." ".$intern2." ORDER BY datum LIMIT 1");
        $sy = _fetch($y);
        $min = date("Y",$sy['datum']);
        $ty = date("Y", time());

        $years = '';
        for($x=$min;$x<=$ty-1;$x++) {
            $sel = ($x == date("Y", time()) ? 'selected="selected"' : "");
            $years .= show(_select_field, array("value" => $x,
                                                "sel" => $sel,
                                                "what" => $x));
        }

        $endc = $language == "deutsch" ? 'n' : '';
        $ccount = cnt($db['newscomments']);
        $com = ($ccount == "1" ? _news_kommentar : _news_kommentare.$endc);

        $stats = show(_news_stats, array("news" => $entrys,
                                         "comments" => cnt($db['newscomments']),
                                         "com" => $com));

        $qrykat = db("SELECT `id`,`kategorie` FROM ".$db['newskat'].""); $kategorien = '';
        while($getkat = _fetch($qrykat)) {
            $kategorien .= '<option value="'.$getkat['id'].'">-> '.$getkat['kategorie'].'</option>';
        }

        for($i=1;$i<=12;$i++) {
            if(!$pyear) {
                  if($i == date("n", time())) $sel[$i] = 'selected="selected"';
                  else $sel[$i] = "";
            } else {
                  if($i == nonum($pmonth)) $sel[$i] = 'selected="selected"';
                  else $sel[$i] = "";
            }
        }

        $nav = nav($entrys,config('m_archivnews'),"?action=archiv&year=".$pyear."&month=".$pmonth."&search=".$psearch.orderby_nav());
        $index = show($dir."/archiv", array("head" => _news_archiv_head,
                                            "head_sort" => _news_archiv_sort,
                                            "date" => _datum,
                                            "titel" => _titel,
                                            "years" => $years,
                                            "nav" => $nav,
                                            "or" => _or,
                                            "kategorien" => $kategorien,
                                            "choose" => _news_kat_choose,
                                            "search" => re($search),
                                            "btn_search" => _button_value_search,
                                            "thisyear" => $ty,
                                            "kat" => _news_admin_kat,
                                            "order_date" => orderby('datum'),
                                            "order_titel" => orderby('titel'),
                                            "order_autor" => orderby('autor'),
                                            "order_kat" => orderby('kat'),
                                            "show" => $show,
                                            "stats" => $stats,
                                            "stichwort" => _stichwort,
                                            "autor" => _autor,
                                            "com" => _news_com,
                                            "jan" => _jan,
                                            "feb" => _feb,
                                            "mar" => _mar,
                                            "apr" => _apr,
                                            "mai" => _mai,
                                            "jun" => _jun,
                                            "jul" => _jul,
                                            "aug" => _aug,
                                            "sep" => _sep,
                                            "okt" => _okt,
                                            "nov" => _nov,
                                            "dez" => _dez,
                                            "sel01" => $sel[1],
                                            "sel02" => $sel[2],
                                            "sel03" => $sel[3],
                                            "sel04" => $sel[4],
                                            "sel05" => $sel[5],
                                            "sel06" => $sel[6],
                                            "sel07" => $sel[7],
                                            "sel08" => $sel[8],
                                            "sel09" => $sel[9],
                                            "sel10" => $sel[10],
                                            "sel11" => $sel[11],
                                            "sel12" => $sel[12]));
}