<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;

    $where = $where.': '._artikel;
      $wysiwyg = '_word';
      if($do == "add")
      {
        $qryk = db("SELECT * FROM ".$db['newskat']."");
        while($getk = _fetch($qryk))
        {
          $kat .= show(_select_field, array("value" => $getk['id'],
                                            "sel" => "",
                                            "what" => re($getk['kategorie'])));
        }

        $show = show($dir."/artikel_form", array("head" => _artikel_add,
                                                 "nautor" => _autor,
                                                 "autor" => autor($userid),
                                                 "nkat" => _news_admin_kat,
                                                 "kat" => $kat,
                                                 "preview" => _preview,
                                                 "ntitel" => _titel,
                                                 "lang" => $language,
                                                 "do" => "insert",
                                                 "ntext" => _eintrag,
                                                 "error" => "",
                                                 "titel" => "",
                                                 "artikeltext" => "",
                                                 "link1" => "",
                                                 "link2" => "",
                                                 "link3" => "",
                                                 "url1" => "",
                                                 "url2" => "",
                                                 "url3" => "",
                                                 "button" => _button_value_add,
                                                 "nmore" => _news_admin_more,
                                                 "linkname" => _linkname,
                                                                       "interna" => _news_admin_intern,
                                                 "nurl" => _url));
      } elseif($do == "insert") {
          if(empty($_POST['titel']) || empty($_POST['artikel']))
            {
              if(empty($_POST['titel'])) $error = _empty_artikel_title;
              elseif(empty($_POST['artikel'])) $error = _empty_artikel;

          $qryk = db("SELECT * FROM ".$db['newskat']."");
          while($getk = _fetch($qryk))
          {
            if($_POST['kat'] == $getk['id']) $sel = "selected=\"selected\"";
            else $sel = "";

            $kat .= show(_select_field, array("value" => $getk['id'],
                                              "sel" => $sel,
                                              "what" => $getk['kategorie']));
          }

              $error = show("errors/errortable", array("error" => $error));

          $show = show($dir."/artikel_form", array("head" => _artikel_add,
                                                   "nautor" => _autor,
                                                   "autor" => autor($userid),
                                                   "nkat" => _news_admin_kat,
                                                   "kat" => $kat,
                                                   "preview" => _preview,
                                                   "do" => "insert",
                                                   "ntitel" => _titel,
                                                   "titel" => re($_POST['titel']),
                                                   "artikeltext" => re_bbcode($_POST['artikel']),
                                                   "link1" => re($_POST['link1']),
                                                   "link2" => re($_POST['link2']),
                                                   "link3" => re($_POST['link3']),
                                                   "url1" => $_POST['url1'],
                                                   "url2" => $_POST['url2'],
                                                   "url3" => $_POST['url3'],
                                                   "ntext" => _eintrag,
                                                   "button" => _button_value_add,
                                                   "lang" => $language,
                                                   "error" => $error,
                                                   "nmore" => _news_admin_more,
                                                   "linkname" => _linkname,
                                                   "nurl" => _url));
          } else {
          if($_POST)
          {
            $qry = db("INSERT INTO ".$db['artikel']."
                       SET `autor`  = '".((int)$userid)."',
                           `kat`    = '".((int)$_POST['kat'])."',
                           `titel`  = '".up($_POST['titel'])."',
                           `text`   = '".up($_POST['artikel'],1)."',
                           `link1`  = '".up($_POST['link1'])."',
                           `link2`  = '".up($_POST['link2'])."',
                           `link3`  = '".up($_POST['link3'])."',
                           `url1`   = '".links($_POST['url1'])."',
                           `url2`   = '".links($_POST['url2'])."',
                           `url3`   = '".links($_POST['url3'])."'");
          }
          $show = info(_artikel_added, "?admin=artikel");
        }
      } elseif($do == "edit") {
        $qry = db("SELECT * FROM ".$db['artikel']."
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

        $do = show(_artikel_edit_link, array("id" => $_GET['id']));

        $show = show($dir."/artikel_form", array("head" => _artikel_edit,
                                                 "nautor" => _autor,
                                                 "autor" => autor($userid),
                                                 "nkat" => _news_admin_kat,
                                                 "preview" => _preview,
                                                 "kat" => $kat,
                                                 "do" => $do,
                                                 "ntitel" => _titel,
                                                 "titel" => re($get['titel']),
                                                 "artikeltext" => re_bbcode($get['text']),
                                                 "link1" => re($get['link1']),
                                                 "link2" => re($get['link2']),
                                                 "link3" => re($get['link3']),
                                                 "url1" => $get['url1'],
                                                 "url2" => $get['url2'],
                                                 "url3" => $get['url3'],
                                                 "ntext" => _eintrag,
                                                 "error" => "",
                                                 "lang" => $language,
                                                 "button" => _button_value_edit,
                                                 "linkname" => _linkname,
                                                 "nurl" => _url));
      } elseif($do == "editartikel") {
        if($_POST)
        {
          $qry = db("UPDATE ".$db['artikel']."
                     SET `kat`    = '".((int)$_POST['kat'])."',
                         `titel`  = '".up($_POST['titel'])."',
                         `text`   = '".up($_POST['artikel'],1)."',
                         `link1`  = '".up($_POST['link1'])."',
                         `link2`  = '".up($_POST['link2'])."',
                         `link3`  = '".up($_POST['link3'])."',
                         `url1`   = '".links($_POST['url1'])."',
                         `url2`   = '".links($_POST['url2'])."',
                         `url3`   = '".links($_POST['url3'])."'
                     WHERE id = '".intval($_GET['id'])."'");
        }
        $show = info(_artikel_edited, "?admin=artikel");
      } elseif($do == "delete") {
        $qry = db("DELETE FROM ".$db['artikel']."
                   WHERE id = '".intval($_GET['id'])."'");
        $show = info(_artikel_deleted, "?admin=artikel");
      } elseif($do == 'public') {
        if($_GET['what'] == 'set')
        {
          $upd = db("UPDATE ".$db['artikel']."
                     SET `public` = '1',
                                   `datum`  = '".time()."'
                     WHERE id = '".intval($_GET['id'])."'");
        } elseif($_GET['what'] == 'unset') {
          $upd = db("UPDATE ".$db['artikel']."
                     SET `public` = '0'
                     WHERE id = '".intval($_GET['id'])."'");
        }

        header("Location: ?admin=artikel");
      } else {
        $entrys = cnt($db['artikel']);
        if(!empty($_GET['orderby']) && in_array($_GET['orderby'],array("titel","datum","autor"))) {
        $qry = db("SELECT * FROM ".$db['artikel']."
                   ORDER BY ".mysqli_real_escape_string($mysql, $mysql, $_GET['orderby']." ".$_GET['order'])."
                   LIMIT ".($page - 1)*config('m_adminartikel').",".config('m_adminartikel')."");
        }
        else {$qry = db("SELECT * FROM ".$db['artikel']."
                         ORDER BY `public` ASC, `datum` DESC
                         LIMIT ".($page - 1)*config('m_adminartikel').",".config('m_adminartikel')."");
        }
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=artikel&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=artikel&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_artikel)));
          $titel = show(_artikel_show_link, array("titel" => re(cut($get['titel'],config('l_newsadmin'))),
                                                  "id" => $get['id']));

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

          $public = ($get['public'] == 1)
               ? '<a href="?admin=artikel&amp;do=public&amp;id='.$get['id'].'&amp;what=unset"><img src="../inc/images/public.gif" alt="" title="'._non_public.'" /></a>'
               : '<a href="?admin=artikel&amp;do=public&amp;id='.$get['id'].'&amp;what=set"><img src="../inc/images/nonpublic.gif" alt="" title="'._public.'" /></a>';

          if(empty($get['datum'])) $datum = _no_public;
          else $datum = date("d.m.y H:i", $get['datum'])._uhr;

          $show_ .= show($dir."/admin_show", array("date" => $datum,
                                                   "titel" => $titel,
                                                   "class" => $class,
                                                   "autor" => autor($get['autor']),
                                                       "intnews" => "",
                                                   "sticky" => "",
                                                   "public" => $public,
                                                   "edit" => $edit,
                                                   "delete" => $delete));
        }
        $orderby = empty($_GET['orderby']) ? "" : "&orderby".$_GET['orderby'];
        $orderby .= empty($_GET['order']) ? "" : "&order=".$_GET['order'];
        $nav = nav($entrys,config('m_adminnews'),"?admin=artikel".$_GET['show']."".$orderby);

        $show = show($dir."/admin_news", array("head" => _artikel,
                                               "nav" => $nav,
                                               "autor" => _autor,
                                               "titel" => _titel,
                                               "date" => _datum,
                                               "order_autor" => orderby('autor'),
                                               "order_date" => orderby('datum'),
                                               "order_titel" => orderby('titel'),
                                               "show" => $show_,
                                               "val" => "artikel",
                                               "edit" => _editicon_blank,
                                               "delete" => _deleteicon_blank,
                                               "add" => _artikel_add));
      }