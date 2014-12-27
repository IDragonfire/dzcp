<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;

    $where = $where.': '._admin_pos;
      $qry = db("SELECT * FROM ".$db['pos']."
                 ORDER BY pid");
      while($get = _fetch($qry))
      {
        $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                      "action" => "admin=positions&amp;do=edit",
                                                      "title" => _button_title_edit));
        $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                          "action" => "admin=positions&amp;do=delete",
                                                          "title" => _button_title_del,
                                                          "del" => convSpace(_confirm_del_entry)));
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

        $show_ .= show($dir."/dlkats_show", array("gameicon" => $gameicon,
                                                  "edit" => $edit,
                                                  "name" => re($get['position']),
                                                  "class" => $class,
                                                  "delete" => $delete));
      }

      $show = show($dir."/dlkats", array("head" => _admin_pos,
                                         "show" => $show_,
                                         "add" => _pos_new_head,
                                         "whatkat" => 'positions',
                                         "download" => _admin_download_kat,
                                         "edit" => _editicon_blank,
                                         "delete" => _deleteicon_blank));

      if($do == "edit")
      {
        $qry1 = db("SELECT * FROM ".$db['pos']."
                    ORDER BY pid");
        while($get1 = _fetch($qry1))
        {
          $positions .= show(_select_field, array("value" => $get1['pid']+1,
                                                  "what" => _nach.' '.re($get1['position']),
                                                  "sel" => ""));
        }

        $qry = db("SELECT * FROM ".$db['pos']."
                   WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

        $show = show($dir."/form_pos", array("newhead" => _pos_edit_head,
                                             "do" => "editpos&amp;id=".intval($_GET['id'])."",
                                             "kat" => $get['position'],
                                             "pos" => _position,
                                             "rechte" => _config_positions_rights,
                                             "getpermissions" => getPermissions(intval($_GET['id']), 1),
                                             "getboardpermissions" => getBoardPermissions(intval($_GET['id']), 1),
                                             "forenrechte" => _config_positions_boardrights,
                                             "positions" => $positions,
                                             "nothing" => _nothing,
                                             "what" => _button_value_edit,
                                             "dlkat" => _admin_download_kat));
      } elseif($do == "editpos") {
        if(empty($_POST['kat']))
        {
          $show = error(_pos_empty_kat,1);
        } else {
          if($_POST['pos'] == "lazy")
              {
                    $pid = "";
              } else {
                    $pid = ",`pid` = '".intval($_POST['pos'])."'";

            if($_POST['pos'] == "1" || "2") $sign = ">= ";
            else $sign = "> ";

            $posi = db("UPDATE ".$db['pos']."
                        SET `pid` = pid+1
                        WHERE pid ".$sign." '".intval($_POST['pos'])."'");
              }

          $qry = db("UPDATE ".$db['pos']."
                     SET `position` = '".up($_POST['kat'])."'
                         ".$pid."
                     WHERE id = '".intval($_GET['id'])."'");
    // permissions
          db("DELETE FROM ".$db['permissions']." WHERE `pos` = '".intval($_GET['id'])."'");
          if(!empty($_POST['perm']))
          {
            foreach($_POST['perm'] AS $v => $k) $p .= "`".substr($v, 2)."` = '".intval($k)."',";
                                  if(!empty($p))$p = ', '.substr($p, 0, strlen($p) - 1);

            db("INSERT INTO ".$db['permissions']." SET `pos` = '".intval($_GET['id'])."'".$p);
          }
    ////////////////////

    // internal boardpermissions
          db("DELETE FROM ".$db['f_access']." WHERE `pos` = '".intval($_GET['id'])."'");
          if(!empty($_POST['board']))
          {
            foreach($_POST['board'] AS $v)
              db("INSERT INTO ".$db['f_access']." SET `pos` = '".intval($_GET['id'])."', `forum` = '".$v."'");
          }
    ////////////////////

          $show = info(_pos_admin_edited, "?admin=positions");
        }
      } elseif($do == "delete") {
        db("DELETE FROM ".$db['pos']." WHERE id = '".intval($_GET['id'])."'");
        db("DELETE FROM ".$db['permissions']." WHERE pos = '".intval($_GET['id'])."'");

        $show = info(_pos_admin_deleted, "?admin=positions");

      } elseif($do == "new") {
        $qry = db("SELECT * FROM ".$db['pos']."
                   ORDER BY pid");
        while($get = _fetch($qry))
        {
          $positions .= show(_select_field, array("value" => $get['pid']+1,
                                                            "what" => _nach.' '.re($get['position']),
                                                            "sel" => ""));
        }
        $show = show($dir."/form_pos", array("newhead" => _pos_new_head,
                                             "do" => "add",
                                             "pos" => _position,
                                             "rechte" => _config_positions_rights,
                                             "getpermissions" => getPermissions(),
                                             "getboardpermissions" => getBoardPermissions(),
                                             "nothing" => "",
                                             "forenrechte" => _config_positions_boardrights,
                                             "positions" => $positions,
                                             "kat" => "",
                                             "what" => _button_value_add,
                                             "dlkat" => _admin_download_kat));
      } elseif($do == "add") {
        if(empty($_POST['kat']))
        {
          $show = error(_pos_empty_kat,1);
        } else {
          if($_POST['pos'] == "1" || "2") $sign = ">= ";
          else $sign = "> ";

          $posi = db("UPDATE ".$db['pos']."
                      SET `pid` = pid+1
                      WHERE pid ".$sign." '".intval($_POST['pos'])."'");

          $qry = db("INSERT INTO ".$db['pos']."
                     SET `pid`        = '".intval($_POST['pos'])."',
                         `position`  = '".up($_POST['kat'])."'");
          $posID = _insert_id();
    // permissions
          foreach($_POST['perm'] AS $v => $k) $p .= "`".substr($v, 2)."` = '".intval($k)."',";
                                if(!empty($p))$p = ', '.substr($p, 0, strlen($p) - 1);

          db("INSERT INTO ".$db['permissions']." SET `pos` = '".$posID."'".$p);
    ////////////////////

    // internal boardpermissions
          if(!empty($_POST['board']))
          {
            foreach($_POST['board'] AS $v)
              db("INSERT INTO ".$db['f_access']." SET `pos` = '".$posID."', `forum` = '".$v."'");
          }
    ////////////////////

          $show = info(_pos_admin_added, "?admin=positions");
        }
      }