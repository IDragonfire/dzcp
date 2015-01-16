<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;

    $where = $where.': '._admin_dlkat;
      $qry = db("SELECT * FROM ".$db['dl_kat']."
                 ORDER BY name");
      while($get = _fetch($qry))
      {
        $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                      "action" => "admin=dl&amp;do=edit",
                                                      "title" => _button_title_edit));
        $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                          "action" => "admin=dl&amp;do=delete",
                                                          "title" => _button_title_del,
                                                          "del" => convSpace(_confirm_del_kat)));
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

        $show_ .= show($dir."/dlkats_show", array("gameicon" => $gameicon,
                                                 "edit" => $edit,
                                                 "name" => re($get['name']),
                                                 "class" => $class,
                                                 "delete" => $delete));
      }

      $show = show($dir."/dlkats", array("head" => _admin_dlkat,
                                         "show" => $show_,
                                         "add" => _dl_new_head,
                                         "whatkat" => 'dl',
                                         "download" => _admin_download_kat,
                                         "edit" => _editicon_blank,
                                         "delete" => _deleteicon_blank));

      if($do == "edit")
      {
        $qry = db("SELECT * FROM ".$db['dl_kat']."
                   WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

        $show = show($dir."/dlkats_form", array("newhead" => _dl_edit_head,
                                                "do" => "editkat&amp;id=".$_GET['id']."",
                                                "kat" => re($get['name']),
                                                "what" => _button_value_edit,
                                                "dlkat" => _dl_dlkat));
      } elseif($do == "editkat") {
        if(empty($_POST['kat']))
        {
          $show = error(_dl_empty_kat,1);
        } else {
          $qry = db("UPDATE ".$db['dl_kat']."
                     SET `name` = '".up($_POST['kat'])."'
                     WHERE id = '".intval($_GET['id'])."'");

          $show = info(_dl_admin_edited, "?admin=dl");
        }
      } elseif($do == "delete") {
        $qry = db("DELETE FROM ".$db['dl_kat']."
                   WHERE id = '".intval($_GET['id'])."'");

        $show = info(_dl_admin_deleted, "?admin=dl");

      } elseif($do == "new") {
        $show = show($dir."/dlkats_form", array("newhead" => _dl_new_head,
                                                "do" => "add",
                                                "kat" => "",
                                                "what" => _button_value_add,
                                                "dlkat" => _dl_dlkat));
      } elseif($do == "add") {
        if(empty($_POST['kat']))
        {
          $show = error(_dl_empty_kat,1);
        } else {
          $qry = db("INSERT INTO ".$db['dl_kat']."
                     SET `name` = '".up($_POST['kat'])."'");

          $show = info(_dl_admin_added, "?admin=dl");
        }
      }