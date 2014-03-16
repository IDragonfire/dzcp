<?php
if(_adminMenu != 'true') exit;

    $where = $where.': '._news_admin_head;
      $wysiwyg = '_word';
      if($_GET['do'] == "add")
      {
        $qryk = db("SELECT * FROM ".$db['newskat']."");
        while($getk = _fetch($qryk))
        {
          $kat .= show(_select_field, array("value" => $getk['id'],
                                            "sel" => "",
                                            "what" => re($getk['kategorie'])));
        }
        $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())),
                                                              "month" => dropdown("month",date("m",time())),
                                                      "year" => dropdown("year",date("Y",time()))));

        $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",time())),
                                                      "minute" => dropdown("minute",date("i",time())),
                                                    "uhr" => _uhr));

        $timeshift_date = show(_dropdown_date_ts, array("nr" => "ts",
                                                                                                                "day" => dropdown("day",date("d",time())),
                                                                                                                "month" => dropdown("month",date("m",time())),
                                                                                                                "year" => dropdown("year",date("Y",time()))));

                $timeshift_time = show(_dropdown_time_ts, array("nr" => "ts",
                                                                                                                "hour" => dropdown("hour",date("H",time())),
                                                                                                                "minute" => dropdown("minute",date("i",time())),
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
                                              "lang" => $language,
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
      } elseif($_GET['do'] == "insert") {
          if(empty($_POST['titel']) || empty($_POST['newstext']))
            {
              if(empty($_POST['titel'])) $error = _empty_news_title;
              elseif(empty($_POST['newstext'])) $error = _empty_news;

          $qryk = db("SELECT * FROM ".$db['newskat']."");
          while($getk = _fetch($qryk))
          {
            if($_POST['kat'] == $getk['id']) $sel = "selected=\"selected\"";
            else $sel = "";

            $kat .= show(_select_field, array("value" => $getk['id'],
                                              "sel" => $sel,
                                              "what" => re($getk['kategorie'])));
          }

              $error = show("errors/errortable", array("error" => $error));
              if($_POST['intern']) $int = "checked=\"checked\"";
          if($_POST['sticky']) $sticky = "checked=\"checked\"";
                    if($_POST['timeshift']) $timeshift = "checked=\"checked\"";


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

                    $show = show($dir."/news_form", array("head" => _admin_news_head,
                                                "nautor" => _autor,
                                                "autor" => autor($userid),
                                                "nimage" => _news_userimage,
                                              "n_newspic" => "",
                                              "delnewspic" => "",
                                                "nkat" => _news_admin_kat,
                                                "kat" => $kat,
                                                "preview" => _preview,
                                                "lang" => $language,
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
          } else {
          if($_POST['sticky']) $stickytime = mktime($_POST['h'],$_POST['min'],0,$_POST['m'],$_POST['t'],$_POST['j']);

                    if($_POST['timeshift']){
                        $timeshifttime = mktime($_POST['h_ts'],$_POST['min_ts'],0,$_POST['m_ts'],$_POST['t_ts'],$_POST['j_ts']);
                        $timeshift = "`timeshift` = '1',";
                        $public = "`public` = '1',";
                        $datum = "`datum` = '".((int)$timeshifttime)."',";
                    } else {
                      $timeshift = "";
                        $public = '';
                        $datum = '';
                    }


                $qry = db("INSERT INTO ".$db['news']."
                     SET `autor`      = '".((int)$userid)."',
                         `kat`        = '".((int)$_POST['kat'])."',
                         `titel`      = '".up($_POST['titel'])."',
                         `text`       = '".up($_POST['newstext'],1)."',
                         `klapplink`  = '".up($_POST['klapptitel'])."',
                         `klapptext`  = '".up($_POST['morenews'],1)."',
                         `link1`      = '".up($_POST['link1'])."',
                         `link2`      = '".up($_POST['link2'])."',
                         `link3`      = '".up($_POST['link3'])."',
                         `url1`       = '".links($_POST['url1'])."',
                         `url2`       = '".links($_POST['url2'])."',
                         `url3`       = '".links($_POST['url3'])."',
                         `intern`     = '".((int)$_POST['intern'])."',
                         ".$timeshift."
                                                 ".$public."
                                                 ".$datum."
                                                 `sticky`     = '".((int)$stickytime)."'");

          $tmpname = $_FILES['newspic']['tmp_name'];
          @copy($tmpname, basePath."/inc/images/uploads/news/".mysqli_insert_id($mysql).".jpg");
          @unlink($tmpname);

          $show = info(_news_sended, "?admin=newsadmin");
        }
      } elseif($_GET['do'] == "edit") {
        $qry = db("SELECT * FROM ".$db['news']."
                   WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

        $qryk = db("SELECT * FROM ".$db['newskat']."");
        while($getk = _fetch($qryk))
        {
          if($get['kat'] == $getk['id']) $sel = "selected=\"selected\"";
          else $sel = "";

          $kat .= show(_select_field, array("value" => $getk['id'],
                                            "sel" => $sel,
                                            "what" => re($getk['kategorie'])));
        }
        $do = show(_news_edit_link, array("id" => $_GET['id']));

        if($get['intern'] == 1) $int = "checked=\"checked\"";
                if($get['timeshift'] == 1) $timeshift = "checked=\"checked\"";
        if($get['sticky'] != 0)
        {
          $sticky = 'checked="checked"';
          $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",$get['sticky'])),
                                                                "month" => dropdown("month",date("m",$get['sticky'])),
                                                        "year" => dropdown("year",date("Y",$get['sticky']))));

          $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",$get['sticky'])),
                                                        "minute" => dropdown("minute",date("i",$get['sticky'])),
                                                      "uhr" => _uhr));
        } else {
          $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())),
                                                                "month" => dropdown("month",date("m",time())),
                                                        "year" => dropdown("year",date("Y",time()))));

          $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",time())),
                                                        "minute" => dropdown("minute",date("i",time())),
                                                      "uhr" => _uhr));
        }

                if($get['timeshift'] != 0)
        {
          $timeshift = 'checked="checked"';
                    $timeshift_date = show(_dropdown_date_ts, array("nr" => "ts",
                                                                                                                    "day" => dropdown("day",date("d",$get['datum'])),
                                                                                                                    "month" => dropdown("month",date("m",$get['datum'])),
                                                                                                                    "year" => dropdown("year",date("Y",$get['datum']))));

                    $timeshift_time = show(_dropdown_time_ts, array("nr" => "ts",
                                                                                                                    "hour" => dropdown("hour",date("H",$get['datum'])),
                                                                                                                    "minute" => dropdown("minute",date("i",$get['datum'])),
                                                                                                                    "uhr" => _uhr));
                } else {
          $timeshift = '';
                    $timeshift_date = show(_dropdown_date_ts, array("nr" => "ts",
                                                                                                                    "day" => dropdown("day",date("d",time())),
                                                                                                                    "month" => dropdown("month",date("m",time())),
                                                                                                                    "year" => dropdown("year",date("Y",time()))));

                    $timeshift_time = show(_dropdown_time_ts, array("nr" => "ts",
                                                                                                                    "hour" => dropdown("hour",date("H",time())),
                                                                                                                    "minute" => dropdown("minute",date("i",time())),
                                                                                                                    "uhr" => _uhr));
                }

        if(file_exists(basePath.'/inc/images/uploads/news/'.$_GET['id'].'.jpg')){
          $newsimage = img_size('inc/images/uploads/news/'.$_GET['id'].'.jpg')."<br /><br />";
          $delnewspic = '<a href="?admin=newsadmin&do=delnewspic&id='.$_GET['id'].'">'._newspic_del.'</a><br /><br />';
        }else{
          $newsimage = "";
          $delnewspic = "";
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
                                              "lang" => $language,
                                              "nklapptitel" => _news_admin_klapptitel,
                                              "nmore" => _news_admin_more,
                                              "linkname" => _linkname,
                                                                                         "intern" => $int,
                                              "sticky" => $sticky,
                                              "getsticky" => _news_get_sticky,
                                              "till" => _news_sticky_till,
                                                                                            "gettimeshift" => _news_get_timeshift,
                                                                                            "from" => _news_timeshift_from,
                                              "day" => $day,
                                              "month" => $month,
                                              "year" => $year,
                                              "hour" => $hour,
                                              "minute" => $minute,
                                                                    "interna" => _news_admin_intern,
                                              "nurl" => _url));
      } elseif($_GET['do'] == "editnews") {
        if($_POST)
        {
          if($_POST['sticky']) $stickytime = mktime($_POST['h'],$_POST['min'],0,$_POST['m'],$_POST['t'],$_POST['j']);

                    if($_POST['timeshift']){
                        $timeshifttime = mktime($_POST['h_ts'],$_POST['min_ts'],0,$_POST['m_ts'],$_POST['t_ts'],$_POST['j_ts']);
                        $timeshift = "`timeshift` = '1',";
                        $public = "`public` = '1',";
                        $datum = "`datum` = '".((int)$timeshifttime)."',";
                    } else {
                      $timeshift = "";
                        $public = '';
                        $datum = '';
                    }

          $qry = db("UPDATE ".$db['news']."
                     SET `kat`        = '".((int)$_POST['kat'])."',
                         `titel`      = '".up($_POST['titel'])."',
                         `text`       = '".up($_POST['newstext'],1)."',
                         `klapplink`  = '".up($_POST['klapptitel'])."',
                         `klapptext`  = '".up($_POST['morenews'],1)."',
                         `link1`      = '".up($_POST['link1'])."',
                         `url1`       = '".links($_POST['url1'])."',
                         `link2`      = '".up($_POST['link2'])."',
                         `url2`       = '".links($_POST['url2'])."',
                         `link3`      = '".up($_POST['link3'])."',
                                   `intern`     = '".((int)$_POST['intern'])."',
                         `url3`       = '".links($_POST['url3'])."',
                                                 ".$timeshift."
                                                 ".$public."
                                                 ".$datum."
                         `sticky`     = '".((int)$stickytime)."'
                     WHERE id = '".intval($_GET['id'])."'");
          if($_FILES['newspic']['tmp_name']) {
            $tmpname = $_FILES['newspic']['tmp_name'];
            if(file_exists(basePath.'/inc/images/uploads/news/'.intval($_GET['id']).'.jpg')){
                @unlink(basePath."/inc/images/uploads/news/".intval($_GET['id']).".jpg");
                @copy($tmpname, basePath."/inc/images/uploads/news/".intval($_GET['id']).".jpg");
                @unlink($tmpname);
            }else{
                @copy($tmpname, basePath."/inc/images/uploads/news/".intval($_GET['id']).".jpg");
                 @unlink($tmpname);
            }
          }

        }
        $show = info(_news_edited, "?admin=newsadmin");
      } elseif($_GET['do'] == 'public') {
        if($_GET['what'] == 'set')
        {
          $upd = db("UPDATE ".$db['news']."
                     SET `public` = '1',
                                      `datum`  = '".time()."'
                     WHERE id = '".intval($_GET['id'])."'");
        } elseif($_GET['what'] == 'unset') {
          $upd = db("UPDATE ".$db['news']."
                     SET `public` = '0'
                     WHERE id = '".intval($_GET['id'])."'");
        }

        header("Location: ?admin=newsadmin");
      } elseif($_GET['do'] == "delete") {
        $del = db("DELETE FROM ".$db['news']."
                   WHERE id = '".intval($_GET['id'])."'");
        $del = db("DELETE FROM ".$db['newscomments']."
                   WHERE news = '".intval($_GET['id'])."'");
        @unlink(basePath."/inc/images/uploads/news/".intval($_GET['id']).".jpg");

        $show = info(_news_deleted, "?admin=newsadmin");
      } elseif($_GET['do'] == "delnewspic") {

        @unlink(basePath."/inc/images/uploads/news/".intval($_GET['id']).".jpg");

        $show = info(_newspic_deleted, "?admin=newsadmin&do=edit&id=".intval($_GET['id'])."");
      } else {
        $entrys = cnt($db['news']);
        if(!empty($_GET['orderby']) && in_array($_GET['orderby'],array("titel","datum","autor"))) {
        $qry = db("SELECT * FROM ".$db['news']."
                   ORDER BY ".mysqli_real_escape_string($mysql, $_GET['orderby']." ".$_GET['order'])."
                   LIMIT ".($page - 1)*$maxadminnews.",".$maxadminnews."");
        }
        else{
        $qry = db("SELECT * FROM ".$db['news']." ORDER BY `public` ASC, `datum` DESC LIMIT ".($page - 1)*$maxadminnews.",".$maxadminnews."");
        }
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=newsadmin&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=newsadmin&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_news)));
          $titel = show(_news_show_link, array("titel" => re(cut($get['titel'],$lnewsadmin)),
                                               "id" => $get['id']));

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

          if($get['intern'] == "1") $intern = _votes_intern;
          else $intern = "";
          if($get['sticky'] == "0") $sticky = "";
          else $sticky = _news_sticky;

          $public = ($get['public'] == 1)
               ? '<a href="?admin=newsadmin&amp;do=public&amp;id='.$get['id'].'&amp;what=unset"><img src="../inc/images/public.gif" alt="" title="'._non_public.'" /></a>'
               : '<a href="?admin=newsadmin&amp;do=public&amp;id='.$get['id'].'&amp;what=set"><img src="../inc/images/nonpublic.gif" alt="" title="'._public.'" /></a>';
          if(empty($get['datum'])) $datum = _no_public;
          else $datum = date("d.m.y H:i", $get['datum'])._uhr;

          $show_ .= show($dir."/admin_show", array("date" => $datum,
                                                   "titel" => $titel,
                                                   "class" => $class,
                                                   "autor" => autor($get['autor']),
                                                       "intnews" => $intern,
                                                   "sticky" => $sticky,
                                                   "public" => $public,
                                                   "edit" => $edit,
                                                   "delete" => $delete));
        }

        $orderby = empty($_GET['orderby']) ? "" : "&orderby".$_GET['orderby'];
        $orderby .= empty($_GET['order']) ? "" : "&order=".$_GET['order'];
        $nav = nav($entrys,$maxadminnews,"?admin=newsadmin".$_GET['show']."".$orderby);

        $show = show($dir."/admin_news", array("head" => _news_admin_head,
                                               "nav" => $nav,
                                               "autor" => _autor,
                                               "titel" => _titel,
                                               "val" => "newsadmin",
                                               "date" => _datum,
                                               "show" => $show_,
                                               "order_autor" => orderby('autor'),
                                               "order_date" => orderby('datum'),
                                               "order_titel" => orderby('titel'),
                                               "edit" => _editicon_blank,
                                               "delete" => _deleteicon_blank,
                                               "add" => _admin_news_head));
      }