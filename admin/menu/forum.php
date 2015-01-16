<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;

      $where = $where.': '._config_forum_head;
        if($_GET['show'] == "subkats")
        {
          $qryk = db("SELECT s1.name,s2.id,s2.kattopic,s2.subtopic,s2.pos
                      FROM ".$db['f_kats']." AS s1
                      LEFT JOIN ".$db['f_skats']." AS s2
                      ON s1.id = s2.sid
                      WHERE s1.id = '".intval($_GET['id'])."'
                      ORDER BY s2.pos");
          while($getk = _fetch($qryk))
          {
            if(!empty($getk['kattopic']))
            {
              $subkat = show(_config_forum_subkats, array("topic" => re($getk['kattopic']),
                                                          "subtopic" => re($getk['subtopic']),
                                                          "id" => $getk['id']));

              $edit = show("page/button_edit_single", array("id" => $getk['id'],
                                                            "action" => "admin=forum&amp;do=editsubkat",
                                                            "title" => _button_title_edit));
              $delete = show("page/button_delete_single", array("id" => $getk['id'],
                                                                "action" => "admin=forum&amp;do=deletesubkat",
                                                                "title" => _button_title_del,
                                                                "del" => convSpace(_confirm_del_entry)));

              $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
              $subkats .= show($dir."/forum_show_subkats_show", array("subkat" => $subkat,
                                                                      "delete" => $delete,
                                                                      "class" => $class,
                                                                      "edit" => $edit));
            }

            $skathead = show(_config_forum_subkathead, array("kat" => re($getk['name'])));
            $add = show(_config_forum_subkats_add, array("id" => $_GET['id']));

            $show = show($dir."/forum_show_subkats", array("head" => _config_forum_head,
                                                           "subkathead" => $skathead,
                                                           "subkats" => $subkats,
                                                           "add" => $add,
                                                           "subkat" => _config_forum_subkat,
                                                           "delete" => _deleteicon_blank,
                                                           "edit" => _editicon_blank));
          }
        } else {
          $qry = db("SELECT * FROM ".$db['f_kats']."
                     ORDER BY kid");
        while($get = _fetch($qry))
        {
          $kat = show(_config_forum_kats_titel, array("kat" => re($get['name']),
                                                      "id" => $get['id']));

          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=".$_GET['admin']."&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=".$_GET['admin']."&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_entry)));
          if($get['intern'] == 1)
          {
            $status = _config_forum_intern;
          } else {
            $status = _config_forum_public;
          }

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $kats .= show($dir."/forum_show_kats", array("class" => $class,
                                                       "kat" => $kat,
                                                       "status" => $status,
                                                       "skats" => cnt($db['f_skats'], " WHERE sid = '".intval($get['id'])."'"),
                                                       "edit" => $edit,
                                                       "delete" => $delete));
        }
        $show = show($dir."/forum", array("head" => _config_forum_head,
                                          "mainkat" => _config_forum_mainkat,
                                          "edit" => _editicon_blank,
                                          "skats" => _cnt,
                                          "status" => _config_forum_status,
                                          "delete" => _deleteicon_blank,
                                          "add" => _config_forum_kats_add,
                                          "kats" => $kats));
        if($do == "newkat")
        {
          $qry = db("SELECT * FROM ".$db['f_kats']."
                     ORDER BY kid");
          while($get = _fetch($qry))
          {
            $positions .= show(_select_field, array("value" => $get['kid']+1,
                                                    "what" => _nach.' '.re($get['name']),
                                                    "sel" => ""));
          }

          $show = show($dir."/katform", array("fkat" => _config_katname,
                                              "head" => _config_forum_kat_head,
                                              "fkid" => _position,
                                              "fart" => _kind,
                                              "positions" => $positions,
                                              "public" => _config_forum_public,
                                              "intern" => _config_forum_intern,
                                              "value" => _button_value_add,
                                              "kat" => ""));
        } elseif($do == "addkat") {
          if(!empty($_POST['kat']))
          {
            if($_POST['kid'] == "1" || "2") $sign = ">= ";
            else  $sign = "> ";

            $posi = db("UPDATE ".$db['f_kats']."
                        SET `kid` = kid+1
                        WHERE kid ".$sign." '".intval($_POST['kid'])."'");

            $qry = db("INSERT INTO ".$db['f_kats']."
                       SET `kid`    = '".intval($_POST['kid'])."',
                           `name`   = '".up($_POST['kat'])."',
                           `intern` = '".intval($_POST['intern'])."'");

            $show = info(_config_forum_kat_added, "?admin=forum");
          } else {
            $show = error(_config_empty_katname, 1);
          }
        } elseif($do == "delete") {
          $what = db("SELECT id FROM ".$db['f_skats']."
                      WHERE sid = '".intval($_GET['id'])."'");
          $get = _fetch($what);

          $qry = db("DELETE FROM ".$db['f_kats']."
                     WHERE id = '".intval($_GET['id'])."'");

          $qry = db("DELETE FROM ".$db['f_threads']."
                     WHERE kid = '".intval($get['id'])."'");

          $qry = db("DELETE FROM ".$db['f_posts']."
                     WHERE kid = '".intval($get['id'])."'");

          $qry = db("DELETE FROM ".$db['f_skats']."
                     WHERE sid = '".intval($_GET['id'])."'");

          $show = info(_config_forum_kat_deleted, "?admin=forum");
        } elseif($do == "edit") {
          $qry = db("SELECT * FROM ".$db['f_kats']."
                     WHERE id = '".intval($_GET['id'])."'");
          while($get = _fetch($qry))
          {
            $pos = db("SELECT * FROM ".$db['f_kats']."
                       ORDER BY kid");
            while($getpos = _fetch($pos))
            {
              if($get['name'] != $getpos['name'])
              {
                $positions .= show(_select_field, array("value" => $getpos['kid']+1,
                                                        "what" => _nach.' '.re($getpos['name'])));
              }
            }

            if($get['intern'] == "1") $sel = 'selected="selected"';

            $show = show($dir."/katform_edit", array("fkat" => _config_katname,
                                                     "head" => _config_forum_kat_head_edit,
                                                     "fkid" => _position,
                                                     "fart" => _kind,
                                                     "id" => $get['id'],
                                                     "sel" => $sel,
                                                     "nothing" => _nothing,
                                                     "positions" => $positions,
                                                     "public" => _config_forum_public,
                                                     "intern" => _config_forum_intern,
                                                     "value" => _button_value_edit,
                                                     "kat" => re($get['name'])));
          }
        } elseif($do == "editkat") {
          if(empty($_POST['kat']))
          {
            $show = error(_config_empty_katname, 1);
          } else {
            if($_POST['kid'] == "lazy"){
              $kid = "";
            }else{
              $kid = "`kid` = '".intval($_POST['kid'])."',";

              if($_POST['kid'] == "1" || "2") $sign = ">= ";
              else  $sign = "> ";
              $posi = db("UPDATE ".$db['f_kats']."
                        SET `kid` = kid+1
                        WHERE `kid` ".$sign." '".intval($_POST['kid'])."'");
            }


            $qry = db("UPDATE ".$db['f_kats']."
                       SET `name`    = '".up($_POST['kat'])."',
                           ".$kid."
                           `intern`  = '".intval($_POST['intern'])."'
                       WHERE id = '".intval($_GET['id'])."'");

            $show = info(_config_forum_kat_edited, "?admin=forum");
          }
        } elseif($do == "newskat") {
          $qry = db("SELECT * FROM ".$db['f_skats']." WHERE sid = " . (int) $_GET['id'] .
                     " ORDER BY pos");
          while($get = _fetch($qry))
          {
            $positions .= show(_select_field, array("value" => $get['pos']+1,
                                                    "what" => _nach.' '.re($get['kattopic']),
                                                    "sel" => ""));
          }
          $show = show($dir."/skatform", array("head" => _config_forum_add_skat,
                                               "fkat" => _config_forum_skatname,
                                               "fstopic" => _config_forum_stopic,
                                               "skat" => "",
                                               "what" => "addskat",
                                               "stopic" => "",
                                               "id" => $_GET['id'],
                                               "nothing" => "",
                                               "tposition" => _position,
                                               "position" => $positions,
                                               "value" => _button_value_add));
        } elseif($do== "addskat") {
          if(empty($_POST['skat']))
          {
            $show = error(_config_forum_empty_skat,1);
          } else {
            if($_POST['order'] == "1" || "2") $sign = ">= ";
            else  $sign = "> ";

            $posi = db("UPDATE ".$db['f_skats']."
                        SET `pos` = pos+1
                        WHERE `pos` ".$sign." '".intval($_POST['order'])."'");

            $qry = db("INSERT INTO ".$db['f_skats']."
                       SET `sid`      = '".intval($_GET['id'])."',
                           `pos`    = '".intval($_POST['order'])."',
                           `kattopic` = '".up($_POST['skat'])."',
                           `subtopic` = '".up($_POST['stopic'])."'");

            $show = info(_config_forum_skat_added, "?admin=forum&show=subkats&amp;id=".$_GET['id']."");
          }
        } elseif($do == "editsubkat") {
          $qry = db("SELECT * FROM ".$db['f_skats']."
                     WHERE id = '".intval($_GET['id'])."'");
          while($get = _fetch($qry)) //--> Start while subkat sort
          {
            $pos = db("SELECT * FROM ".$db['f_skats']." WHERE sid = ".$get['sid']."
                       ORDER BY pos");
            while($getpos = _fetch($pos))
            {
              if($get['kattopic'] != $getpos['kattopic'])
              {
                $positions .= show(_select_field, array("value" => $getpos['pos']+1,
                                                        "what" => _nach.' '.re($getpos['kattopic'])));
              }
            }

          $show = show($dir."/skatform", array("head" => _config_forum_edit_skat,
                                               "fkat" => _config_forum_skatname,
                                               "fstopic" => _config_forum_stopic,
                                               "skat" => re($get['kattopic']),
                                               "what" => "editskat",
                                               "stopic" => re($get['subtopic']),
                                               "id" => $_GET['id'],
                                               "sid" => $get['sid'],
                                               "nothing" => _nothing,
                                               "tposition" => _position,
                                               "position" => $positions,
                                               "value" => _button_value_edit));
            } //--> End while subkat sort
        } elseif($do == "editskat") {
          if(empty($_POST['skat']))
          {
            $show = error(_config_forum_empty_skat,1);
          } else {

            if($_POST['order'] == "lazy"){
              $order = "";
            }else{
              $order = "`pos` = '".intval($_POST['order'])."',";

              if($_POST['order'] == "1" || "2") $sign = ">= ";
              else  $sign = "> ";
              $posi = db("UPDATE ".$db['f_skats']."
                        SET `pos` = pos+1
                        WHERE `pos` ".$sign." '".intval($_POST['order'])."'");
            }

            $qry = db("UPDATE ".$db['f_skats']."
                       SET `kattopic` = '".up($_POST['skat'])."',
                           ".$order."
                           `subtopic` = '".up($_POST['stopic'])."'
                       WHERE id = '".intval($_GET['id'])."'");

            $show = info(_config_forum_skat_edited, "?admin=forum&show=subkats&amp;id=".$_POST['sid']."");
          }
        } elseif($do == "deletesubkat") {
          $qry = db("SELECT sid FROM ".$db['f_skats']."
                     WHERE id = '".intval($_GET['id'])."'");
          $get = _fetch($qry);

          $del = db("DELETE FROM ".$db['f_skats']."
                     WHERE id = '".intval($_GET['id'])."'");

          $del = db("DELETE FROM ".$db['f_threads']."
                     WHERE kid = '".intval($_GET['id'])."'");

          $del = db("DELETE FROM ".$db['f_posts']."
                     WHERE kid = '".intval($_GET['id'])."'");

          $show = info(_config_forum_skat_deleted, "?admin=forum&show=subkats&amp;id=".$get['sid']."");
        }
      }