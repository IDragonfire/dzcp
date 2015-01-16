<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;
$where = $where.': '._news_admin_head;

switch ($do) {
    case 'add':
        $qryk = db("SELECT id,kategorie FROM ".$db['newskat'].""); $kat = '';
        while($getk = _fetch($qryk)) {
            $kat .= show(_select_field, array("value" => $getk['id'], "sel" => "", "what" => re($getk['kategorie'])));
        }

        $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d")),
                                                    "month" => dropdown("month",date("m")),
                                                    "year" => dropdown("year",date("Y"))));

        $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H")),
                                                    "minute" => dropdown("minute",date("i")),
                                                    "uhr" => _uhr));

        $timeshift_date = show(_dropdown_date_ts, array("nr" => "ts",
                                                        "day" => dropdown("day",date("d")),
                                                        "month" => dropdown("month",date("m")),
                                                        "year" => dropdown("year",date("Y"))));

        $timeshift_time = show(_dropdown_time_ts, array("nr" => "ts",
                                                        "hour" => dropdown("hour",date("H")),
                                                        "minute" => dropdown("minute",date("i")),
                                                        "uhr" => _uhr));

        $show = show($dir."/news_form", array("head" => _admin_news_head,
                                              "nautor" => _autor,
                                              "autor" => autor($userid),
                                              "nimage" => _news_userimage,
                                              "n_newspic" => "",
                                              "delnewspic" => "",
                                              "nkat" => _news_admin_kat,
                                              "kat" => $kat,
                                              "preview" => _preview,
                                              "ntitel" => _titel,
                                              "do" => "insert",
                                              "ntext" => _eintrag,
                                              "error" => "",
                                              "titel" => "",
                                              "newstext" => "",
                                              "morenews" => "",
                                              "link1" => "",
                                              "link2" => "",
                                              "link3" => "",
                                              "url1" => "",
                                              "url2" => "",
                                              "url3" => "",
                                              "klapplink" => "",
                                              "sticky" => "",
                                              "getsticky" => _news_get_sticky,
                                              "button" =>  _button_value_add,
                                              "nklapptitel" => _news_admin_klapptitel,
                                              "nmore" => _news_admin_more,
                                              "linkname" => _linkname,
                                              "interna" => _news_admin_intern,
                                              "intern" => "",
                                              "till" => _news_sticky_till,
                                              "dropdown_time" => $dropdown_time,
                                              "dropdown_date" => $dropdown_date,
                                              "gettimeshift" => _news_get_timeshift,
                                              "from" => _news_timeshift_from,
                                              "timeshift_date" => $timeshift_date,
                                              "timeshift_time" => $timeshift_time,
                                              "timeshift" => "",
                                              "nurl" => _url));
    break;
    case 'insert':
        if(empty($_POST['titel']) || empty($_POST['newstext'])) {
            $error = _empty_news;
            if(empty($_POST['titel']))
                $error = _empty_news_title;

            $qryk = db("SELECT id,kategorie FROM ".$db['newskat'].""); $kat = '';
            while($getk = _fetch($qryk)) {
                $sel = ($_POST['kat'] == $getk['id'] ? 'selected="selected"' : '');
                $kat .= show(_select_field, array("value" => $getk['id'],
                                                  "sel" => $sel,
                                                  "what" => re($getk['kategorie'])));
            }

            $int = isset($_POST['intern']) ? 'checked="checked"' : '';
            $sticky = isset($_POST['sticky']) ? 'checked="checked"' : '';
            $timeshift = isset($_POST['timeshift']) ? 'checked="checked"' : '';

            $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",$_POST['t']),
                                                        "month" => dropdown("month",$_POST['m']),
                                                        "year" => dropdown("year",$_POST['j'])));

            $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",$_POST['h']),
                                                        "minute" => dropdown("minute",$_POST['min']),
                                                        "uhr" => _uhr));

            $timeshift_date = show(_dropdown_date_ts, array("nr" => "ts",
                                                            "day" => dropdown("day",$_POST['t_ts']),
                                                            "month" => dropdown("month",$_POST['m_ts']),
                                                            "year" => dropdown("year",$_POST['j_ts'])));

            $timeshift_time = show(_dropdown_time_ts, array("nr" => "ts",
                                                            "hour" => dropdown("hour",$_POST['h_ts']),
                                                            "minute" => dropdown("minute",$_POST['min_ts']),
                                                            "uhr" => _uhr));

            $error = show("errors/errortable", array("error" => $error));
            $show = show($dir."/news_form", array("head" => _admin_news_head,
                                                  "nautor" => _autor,
                                                  "autor" => autor($userid),
                                                  "nimage" => _news_userimage,
                                                  "n_newspic" => "",
                                                  "delnewspic" => "",
                                                  "nkat" => _news_admin_kat,
                                                  "kat" => $kat,
                                                  "preview" => _preview,
                                                  "do" => "insert",
                                                  "ntitel" => _titel,
                                                  "titel" => re($_POST['titel']),
                                                  "newstext" => re_bbcode($_POST['newstext']),
                                                  "morenews" => re_bbcode($_POST['morenews']),
                                                  "link1" => re($_POST['link1']),
                                                  "link2" => re($_POST['link2']),
                                                  "link3" => re($_POST['link3']),
                                                  "url1" => $_POST['url1'],
                                                  "url2" => $_POST['url2'],
                                                  "url3" => $_POST['url3'],
                                                  "klapplink" => re($_POST['klapptitel']),
                                                  "ntext" => _eintrag,
                                                  "button" => _button_value_add,
                                                  "error" => $error,
                                                  "nklapptitel" => _news_admin_klapptitel,
                                                  "nmore" => _news_admin_more,
                                                  "linkname" => _linkname,
                                                  "intern" => $int,
                                                  "sticky" => $sticky,
                                                  "getsticky" => _news_get_sticky,
                                                  "till" => _news_sticky_till,
                                                  "dropdown_date" => $dropdown_date,
                                                  "dropdown_time" => $dropdown_time,
                                                  "interna" => _news_admin_intern,
                                                  "timeshift_date" => $timeshift_date,
                                                  "timeshift_time" => $timeshift_time,
                                                  "timeshift" => $timeshift,
                                                  "gettimeshift" => _news_get_timeshift,
                                                  "from" => _news_timeshift_from,
                                                  "nurl" => _url));
        }  else  {
            $stickytime = isset($_POST['sticky']) ? mktime($_POST['h'],$_POST['min'],0,$_POST['m'],$_POST['t'],$_POST['j']) : '0';
            $timeshift = ''; $public = ''; $datum = '';
            if(isset($_POST['timeshift'])) {
                $timeshifttime = mktime($_POST['h_ts'],$_POST['min_ts'],0,$_POST['m_ts'],$_POST['t_ts'],$_POST['j_ts']);
                $timeshift = "`timeshift` = '1',";
                $public = "`public` = '1',";
                $datum = "`datum` = '".intval($timeshifttime)."',";
            }

            db("INSERT INTO ".$db['news']."
                SET `autor`      = '".intval($userid)."',
                    `kat`        = '".intval($_POST['kat'])."',
                    `titel`      = '".up($_POST['titel'])."',
                    `text`       = '".up($_POST['newstext'])."',
                    `klapplink`  = '".up($_POST['klapptitel'])."',
                    `klapptext`  = '".up($_POST['morenews'])."',
                    `link1`      = '".up($_POST['link1'])."',
                    `link2`      = '".up($_POST['link2'])."',
                    `link3`      = '".up($_POST['link3'])."',
                    `url1`       = '".links($_POST['url1'])."',
                    `url2`       = '".links($_POST['url2'])."',
                    `url3`       = '".links($_POST['url3'])."',
                    `intern`     = '".intval($_POST['intern'])."',
                    ".$timeshift."
                    ".$public."
                    ".$datum."
                    `sticky`     = '".intval($stickytime)."'");

            if(isset($_FILES['newspic']['tmp_name']) && !empty($_FILES['newspic']['tmp_name'])) {
                $endung = explode(".", $_FILES['newspic']['name']);
                $endung = strtolower($endung[count($endung)-1]);
                move_uploaded_file($_FILES['newspic']['tmp_name'], basePath."/inc/images/uploads/news/"._insert_id().".".strtolower($endung));
            }

            $show = info(_news_sended, "?admin=newsadmin");
        }
    break;
    case 'edit':
        $get = db("SELECT * FROM ".$db['news']." WHERE id = '".intval($_GET['id'])."'",false,true);
        $qryk = db("SELECT id,kategorie FROM ".$db['newskat'].""); $kat = '';
        while($getk = _fetch($qryk)) {
            $sel = ($get['kat'] == $getk['id'] ? 'selected="selected"' : '');
            $kat .= show(_select_field, array("value" => $getk['id'],
                                              "sel" => $sel,
                                              "what" => re($getk['kategorie'])));
        }

        $do = show(_news_edit_link, array("id" => $_GET['id']));
        $int = ($get['intern'] ? 'checked="checked"' : '');
        $timeshift = ($get['timeshift'] ? 'checked="checked"' : '');
        $sticky = ($get['sticky'] ? 'checked="checked"' : '');

        $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d")), "month" => dropdown("month",date("m")), "year" => dropdown("year",date("Y"))));
        $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H")), "minute" => dropdown("minute",date("i")), "uhr" => _uhr));
        if($get['sticky']) {
            $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",$get['sticky'])),
                                                        "month" => dropdown("month",date("m",$get['sticky'])),
                                                        "year" => dropdown("year",date("Y",$get['sticky']))));

            $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",$get['sticky'])),
                                                        "minute" => dropdown("minute",date("i",$get['sticky'])),
                                                        "uhr" => _uhr));
        }

        $timeshift_date = show(_dropdown_date_ts, array("nr" => "ts", "day" => dropdown("day",date("d")), "month" => dropdown("month",date("m")), "year" => dropdown("year",date("Y"))));
        $timeshift_time = show(_dropdown_time_ts, array("nr" => "ts", "hour" => dropdown("hour",date("H")), "minute" => dropdown("minute",date("i")), "uhr" => _uhr));
        if($get['timeshift']) {
            $timeshift_date = show(_dropdown_date_ts, array("nr" => "ts",
                                                            "day" => dropdown("day",date("d",$get['datum'])),
                                                            "month" => dropdown("month",date("m",$get['datum'])),
                                                            "year" => dropdown("year",date("Y",$get['datum']))));

            $timeshift_time = show(_dropdown_time_ts, array("nr" => "ts",
                                                            "hour" => dropdown("hour",date("H",$get['datum'])),
                                                            "minute" => dropdown("minute",date("i",$get['datum'])),
                                                            "uhr" => _uhr));
        }

        $newsimage = ""; $delnewspic = "";
        foreach($picformat as $tmpendung) {
            if(file_exists(basePath."/inc/images/uploads/news/".intval($_GET['id']).".".$tmpendung)) {
                $newsimage = img_size('inc/images/uploads/news/'.intval($_GET['id']).'.'.$tmpendung)."<br /><br />";
                $delnewspic = '<a href="?admin=newsadmin&do=delnewspic&id='.$_GET['id'].'">'._newspic_del.'</a><br /><br />';
                break;
            }
        }

        $show = show($dir."/news_form", array("head" => _admin_news_edit_head,
                                              "nautor" => _autor,
                                              "autor" => autor($get['autor']),
                                              "nimage" => _news_userimage,
                                              "n_newspic" => $newsimage,
                                              "delnewspic" => $delnewspic,
                                              "nkat" => _news_admin_kat,
                                              "kat" => $kat,
                                              "do" => $do,
                                              "preview" => _preview,
                                              "ntitel" => _titel,
                                              "titel" => re($get['titel']),
                                              "newstext" => re_bbcode($get['text']),
                                              "morenews" => re_bbcode($get['klapptext']),
                                              "link1" => re($get['link1']),
                                              "link2" => re($get['link2']),
                                              "link3" => re($get['link3']),
                                              "url1" => $get['url1'],
                                              "url2" => $get['url2'],
                                              "url3" => $get['url3'],
                                              "klapplink" => re($get['klapplink']),
                                              "dropdown_date" => $dropdown_date,
                                              "dropdown_time" => $dropdown_time,
                                              "timeshift_date" => $timeshift_date,
                                              "timeshift_time" => $timeshift_time,
                                              "timeshift" => $timeshift,
                                              "ntext" => _eintrag,
                                              "error" => "",
                                              "button" => _button_value_edit,
                                              "nklapptitel" => _news_admin_klapptitel,
                                              "nmore" => _news_admin_more,
                                              "linkname" => _linkname,
                                              "intern" => $int,
                                              "sticky" => $sticky,
                                              "getsticky" => _news_get_sticky,
                                              "till" => _news_sticky_till,
                                              "gettimeshift" => _news_get_timeshift,
                                              "from" => _news_timeshift_from,
                                              "interna" => _news_admin_intern,
                                              "nurl" => _url));
    break;
    case 'editnews':
        if(isset($_POST)) {
            $stickytime = (isset($_POST['sticky']) ? mktime($_POST['h'],$_POST['min'],0,$_POST['m'],$_POST['t'],$_POST['j']) : '0');
            $timeshift = ''; $public = ''; $datum = '';
            if(isset($_POST['timeshift'])) {
                $timeshifttime = mktime($_POST['h_ts'],$_POST['min_ts'],0,$_POST['m_ts'],$_POST['t_ts'],$_POST['j_ts']);
                $timeshift = "`timeshift` = '1',";
                $public = "`public` = '1',";
                $datum = "`datum` = '".intval($timeshifttime)."',";
            }

            db("UPDATE ".$db['news']."
                SET `kat`        = '".intval($_POST['kat'])."',
                    `titel`      = '".up($_POST['titel'])."',
                    `text`       = '".up($_POST['newstext'])."',
                    `klapplink`  = '".up($_POST['klapptitel'])."',
                    `klapptext`  = '".up($_POST['morenews'])."',
                    `link1`      = '".up($_POST['link1'])."',
                    `url1`       = '".links($_POST['url1'])."',
                    `link2`      = '".up($_POST['link2'])."',
                    `url2`       = '".links($_POST['url2'])."',
                    `link3`      = '".up($_POST['link3'])."',
                    `intern`     = '".intval($_POST['intern'])."',
                    `url3`       = '".links($_POST['url3'])."',
                    ".$timeshift."
                    ".$public."
                    ".$datum."
                    `sticky`     = '".intval($stickytime)."'
                WHERE id = '".intval($_GET['id'])."'");

            if(isset($_FILES['newspic']['tmp_name']) && !empty($_FILES['newspic']['tmp_name'])) {
                foreach($picformat as $tmpendung) {
                    if(file_exists(basePath."/inc/images/uploads/news/".intval($_GET['id']).".".$tmpendung))
                        @unlink(basePath."/inc/images/uploads/news/".intval($_GET['id']).".".$tmpendung);
                }

                //Remove minimize
                $files = get_files(basePath."/inc/images/uploads/news/",false,true,$picformat);
                foreach ($files as $file) {
                    if(preg_match("#".intval($_GET['id'])."(.*?).(gif|jpg|jpeg|png)#",strtolower($file))!= FALSE) {
                        $res = preg_match("#".intval($_GET['id'])."_(.*)#",$file,$match);
                        if(file_exists(basePath."/inc/images/uploads/news/".intval($_GET['id'])."_".$match[1]))
                            @unlink(basePath."/inc/images/uploads/news/".intval($_GET['id'])."_".$match[1]);
                    }
                }

                $endung = explode(".", $_FILES['newspic']['name']);
                $endung = strtolower($endung[count($endung)-1]);
                move_uploaded_file($_FILES['newspic']['tmp_name'], basePath."/inc/images/uploads/news/".intval($_GET['id']).".".strtolower($endung));
            }

            $show = info(_news_edited, "?admin=newsadmin");
        }
    break;
    case 'public':
        if(isset($_GET['what']) && $_GET['what'] == 'set')
            db("UPDATE ".$db['news']." SET `public` = '1', `datum`  = '".time()."' WHERE id = '".intval($_GET['id'])."'");
        else
            db("UPDATE ".$db['news']." SET `public` = '0' WHERE id = '".intval($_GET['id'])."'");

        header("Location: ?admin=newsadmin");
    break;
    case 'delete':
        db("DELETE FROM ".$db['news']." WHERE id = '".intval($_GET['id'])."'");
        db("DELETE FROM ".$db['newscomments']." WHERE news = '".intval($_GET['id'])."'");

        //Remove Pic
        foreach($picformat as $tmpendung) {
            if(file_exists(basePath."/inc/images/uploads/news/".intval($_GET['id']).".".$tmpendung))
                @unlink(basePath."/inc/images/uploads/news/".intval($_GET['id']).".".$tmpendung);
        }

        //Remove minimize
        $files = get_files(basePath."/inc/images/uploads/news/",false,true,$picformat);
        if($files) {
            foreach ($files as $file) {
                if(preg_match("#".intval($_GET['id'])."(.*?).(gif|jpg|jpeg|png)#",strtolower($file))!= FALSE) {
                    $res = preg_match("#".intval($_GET['id'])."_(.*)#",$file,$match);
                    if(file_exists(basePath."/inc/images/uploads/news/".intval($_GET['id'])."_".$match[1]))
                        @unlink(basePath."/inc/images/uploads/news/".intval($_GET['id'])."_".$match[1]);
                }
            }
        }

        $show = info(_news_deleted, "?admin=newsadmin");
    break;
    case 'delnewspic':
        //Remove Pic
        foreach($picformat as $tmpendung) {
            if(file_exists(basePath."/inc/images/uploads/news/".intval($_GET['id']).".".$tmpendung))
                @unlink(basePath."/inc/images/uploads/news/".intval($_GET['id']).".".$tmpendung);
        }

        //Remove minimize
        $files = get_files(basePath."/inc/images/uploads/news/",false,true,$picformat);
        foreach ($files as $file) {
            if(preg_match("#".intval($_GET['id'])."(.*?).(gif|jpg|jpeg|png)#",strtolower($file))!= FALSE) {
                $res = preg_match("#".intval($_GET['id'])."_(.*)#",$file,$match);
                if(file_exists(basePath."/inc/images/uploads/news/".intval($_GET['id'])."_".$match[1]))
                    @unlink(basePath."/inc/images/uploads/news/".intval($_GET['id'])."_".$match[1]);
            }
        }

        $show = info(_newspic_deleted, "?admin=newsadmin&do=edit&id=".intval($_GET['id'])."");
    break;
    default:
        $entrys = cnt($db['news']); $show_ = '';
        $qry = db("SELECT * FROM ".$db['news']." ".orderby_sql(array("titel","datum","autor"), 'ORDER BY `public` ASC, `datum` DESC')."
                   LIMIT ".($page - 1)*config('m_adminnews').",".config('m_adminnews')."");
        while($get = _fetch($qry)) {
            $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                          "action" => "admin=newsadmin&amp;do=edit",
                                                          "title" => _button_title_edit));

            $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                              "action" => "admin=newsadmin&amp;do=delete",
                                                              "title" => _button_title_del,
                                                              "del" => convSpace(_confirm_del_news)));

            $titel = show(_news_show_link, array("titel" => re(cut($get['titel'],config('l_newsadmin'))), "id" => $get['id']));
            $intern = ($get['intern'] ? _votes_intern : '');
            $sticky = ($get['sticky'] ? _news_sticky : '');
            $datum = empty($get['datum']) ? _no_public : date("d.m.y H:i", $get['datum'])._uhr;
            $public = ($get['public'] ? '<a href="?admin=newsadmin&amp;do=public&amp;id='.$get['id'].'&amp;what=unset"><img src="../inc/images/public.gif" alt="" title="'._non_public.'" /></a>'
                    : '<a href="?admin=newsadmin&amp;do=public&amp;id='.$get['id'].'&amp;what=set"><img src="../inc/images/nonpublic.gif" alt="" title="'._public.'" /></a>');

            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/admin_show", array("date" => $datum,
                                                    "titel" => $titel,
                                                    "class" => $class,
                                                    "autor" => autor($get['autor']),
                                                    "intnews" => $intern,
                                                    "sticky" => $sticky,
                                                    "public" => $public,
                                                    "edit" => $edit,
                                                    "delete" => $delete));
        }

        if(empty($show))
            $show = '<tr><td colspan="3" class="contentMainSecond">'._no_entrys.'</td></tr>';

        $nav = nav($entrys,config('m_adminnews'),"?admin=newsadmin".(isset($_GET['show']) ? $_GET['show'].orderby_nav() : orderby_nav()));
        $show = show($dir."/admin_news", array("head" => _news_admin_head,
                                               "nav" => $nav,
                                               "autor" => _autor,
                                               "titel" => _titel,
                                               "val" => "newsadmin",
                                               "date" => _datum,
                                               "show" => $show,
                                               "order_autor" => orderby('autor'),
                                               "order_date" => orderby('datum'),
                                               "order_titel" => orderby('titel'),
                                               "edit" => _editicon_blank,
                                               "delete" => _deleteicon_blank,
                                               "add" => _admin_news_head));
    break;
}