<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1 Final
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;

    $where = $where.': '._profile_head;
      if($do == "add")
      {
        $show = show($dir."/form_profil", array("head" => _profile_add_head,
                                                "name" => _profile_name,
                                                                      "type" => _profile_type,
                                                "value" => _button_value_add,
                                                                      "kat" => _profile_kat,
                                                                      "form_kat" => _profile_kat_dropdown,
                                                                      "form_type" => _profile_type_dropdown));
      } elseif($do == "addprofile") {
        if(empty($_POST['name']))
        {
          $show = error(_profil_no_name,1);
        } elseif($_POST['kat']=="lazy")
        {
          $show = error(_profil_no_kat,1);
          } elseif($_POST['type']=="lazy")
        {
          $show = error(_profil_no_type,1);
        } else {
          $name = preg_replace("#[[:punct:]]|[[:space:]]#Uis", "", $_POST['name']);

          $add = db("INSERT INTO ".$db['profile']."
                     SET `name` = '".up($name)."',
                                   `type` = '".intval($_POST['type'])."',
                         `kid`  = '".intval($_POST['kat'])."'");
              $insID = _insert_id();

          $feldname = "custom_".$insID;
              $add = db("UPDATE ".$db['profile']."
                     SET `feldname` = '".$feldname."'
                             WHERE id = '".intval($insID)."'");

              $add = db("ALTER TABLE `".$db['users']."` ADD `".$feldname."` varchar(249) NOT NULL DEFAULT ''");

              $show = info(_profile_added,"?admin=profile");
        }
      } elseif($do == "delete") {
        $qry = db("SELECT feldname FROM ".$db['profile']."
                   WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);
          $del = db("ALTER TABLE ".$db['users']." DROP `".$get['feldname']."`");
        $del = db("DELETE FROM ".$db['profile']."
                   WHERE id = '".intval($_GET['id'])."'");
        $show = info(_profil_deleted, "?admin=profile");
      } elseif($do == "edit") {
        $qry = db("SELECT * FROM ".$db['profile']."
                   WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

        $shown = str_replace("<option value='".$get['shown']."'>", "<option selected=\"selected\" value='".$get['shown']."'>", _profile_shown_dropdown);
          $kat = str_replace("<option value='".$get['kid']."'>", "<option selected=\"selected\" value='".$get['kid']."'>", _profile_kat_dropdown);
          $type = str_replace("<option value='".$get['type']."'>", "<option selected=\"selected\" value='".$get['type']."'>", _profile_type_dropdown);

        $show = show($dir."/form_profil_edit", array("name" => _profile_name,
                                                                             "p_name" => re($get['name']),
                                                                             "kat" => _profile_kat,
                                                                             "type" => _profile_type,
                                                                             "id" => $_GET['id'],
                                                     "value" => _button_value_edit,
                                                                             "shown" => _profile_shown,
                                                                             "form_shown" => $shown,
                                                                             "form_kat" => $kat,
                                                                             "form_type" => $type,
                                                     "head" => _profile_edit_head));
      } elseif($do == "editprofil") {
        if(empty($_POST['name']))
        {
          $show = error(_profil_no_name,1);
        } else {
          $name = preg_replace("#[[:punct:]]|[[:space:]]#Uis", "", $_POST['name']);

              $add = db("UPDATE ".$db['profile']."
                     SET `name`  = '".up($name)."',
                                   `kid`   = '".intval($_POST['kat'])."',
                                   `type`  = '".intval($_POST['type'])."',
                                   `shown` = '".intval($_POST['shown'])."'
                             WHERE id = '".intval($_GET['id'])."'");

              $show = info(_profile_edited,"?admin=profile");
        }
      } elseif($do == "shown") {
          if($_GET['what'] == 'set')
        {
          $upd = db("UPDATE ".$db['profile']."
                     SET `shown` = '1'
                     WHERE id = '".intval($_GET['id'])."'");
        } elseif($_GET['what'] == 'unset') {
          $upd = db("UPDATE ".$db['profile']."
                     SET `shown` = '0'
                     WHERE id = '".intval($_GET['id'])."'");
        }
        header("Location: ?admin=profile");
      } else {
        $qry = db("SELECT * FROM ".$db['profile']."
                   WHERE kid = '1'
                         ORDER BY name");
        while($get = _fetch($qry))
        {
          $shown = ($get['shown'] == 1)
               ? '<a href="?admin=profile&amp;do=shown&amp;id='.$get['id'].'&amp;what=unset"><img src="../inc/images/yes.gif" alt="" title="'._non_public.'" /></a>'
               : '<a href="?admin=profile&amp;do=shown&amp;id='.$get['id'].'&amp;what=set"><img src="../inc/images/no.gif" alt="" title="'._public.'" /></a>';

          if($get['type'] == "1") $type = _profile_type_1;
              elseif($get['type'] == "2") $type = _profile_type_2;
              elseif($get['type'] == "3") $type = _profile_type_3;

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=profile&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=profile&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_profil)));
          $show_about .= show($dir."/profil_show", array("class" => $class,
                                                         "name" => re($get['name']),
                                                                               "type" => $type,
                                                                                   "shown" => $shown,
                                                         "edit" => $edit,
                                                         "del" => $delete));
        }

        $qry = db("SELECT * FROM ".$db['profile']."
                   WHERE kid = '2'
                         ORDER BY name");
        while($get = _fetch($qry))
        {
          $shown = ($get['shown'] == 1)
               ? '<a href="?admin=profile&amp;do=shown&amp;id='.$get['id'].'&amp;what=unset"><img src="../inc/images/yes.gif" alt="" title="'._non_public.'" /></a>'
               : '<a href="?admin=profile&amp;do=shown&amp;id='.$get['id'].'&amp;what=set"><img src="../inc/images/no.gif" alt="" title="'._public.'" /></a>';

          if($get['type'] == "1") $type = _profile_type_1;
              elseif($get['type'] == "2") $type = _profile_type_2;
              elseif($get['type'] == "3") $type = _profile_type_3;

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=profile&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=profile&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_profil)));
          $show_clan .= show($dir."/profil_show", array("class" => $class,
                                                        "name" => re($get['name']),
                                                                              "type" => $type,
                                                                                  "shown" => $shown,
                                                        "edit" => $edit,
                                                        "del" => $delete));
        }
        $qry = db("SELECT * FROM ".$db['profile']."
                   WHERE kid = '3'
                         ORDER BY name");
        while($get = _fetch($qry))
        {
          $shown = ($get['shown'] == 1)
               ? '<a href="?admin=profile&amp;do=shown&amp;id='.$get['id'].'&amp;what=unset"><img src="../inc/images/yes.gif" alt="" title="'._non_public.'" /></a>'
               : '<a href="?admin=profile&amp;do=shown&amp;id='.$get['id'].'&amp;what=set"><img src="../inc/images/no.gif" alt="" title="'._public.'" /></a>';

          if($get['type'] == "1") $type = _profile_type_1;
              elseif($get['type'] == "2") $type = _profile_type_2;
              elseif($get['type'] == "3") $type = _profile_type_3;

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=profile&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=profile&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_profil)));
          $show_contact .= show($dir."/profil_show", array("class" => $class,
                                                           "name" => re($get['name']),
                                                                                 "type" => $type,
                                                                                       "shown" => $shown,
                                                           "edit" => $edit,
                                                           "del" => $delete));
        }
          $qry = db("SELECT * FROM ".$db['profile']."
                   WHERE kid = '4'
                         ORDER BY name");
        while($get = _fetch($qry))
        {
           $shown = ($get['shown'] == 1)
               ? '<a href="?admin=profile&amp;do=shown&amp;id='.$get['id'].'&amp;what=unset"><img src="../inc/images/yes.gif" alt="" title="'._non_public.'" /></a>'
               : '<a href="?admin=profile&amp;do=shown&amp;id='.$get['id'].'&amp;what=set"><img src="../inc/images/no.gif" alt="" title="'._public.'" /></a>';

          if($get['type'] == "1") $type = _profile_type_1;
              elseif($get['type'] == "2") $type = _profile_type_2;
              elseif($get['type'] == "3") $type = _profile_type_3;

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=profile&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=profile&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_profil)));
          $show_favos .= show($dir."/profil_show", array("class" => $class,
                                                         "name" => re($get['name']),
                                                                               "type" => $type,
                                                                                   "shown" => $shown,
                                                         "edit" => $edit,
                                                         "del" => $delete));
        }
        $qry = db("SELECT * FROM ".$db['profile']."
                   WHERE kid = '5'
                         ORDER BY name");
        while($get = _fetch($qry))
        {
          $shown = ($get['shown'] == 1)
               ? '<a href="?admin=profile&amp;do=shown&amp;id='.$get['id'].'&amp;what=unset"><img src="../inc/images/yes.gif" alt="" title="'._non_public.'" /></a>'
               : '<a href="?admin=profile&amp;do=shown&amp;id='.$get['id'].'&amp;what=set"><img src="../inc/images/no.gif" alt="" title="'._public.'" /></a>';

          if($get['type'] == "1") $type = _profile_type_1;
              elseif($get['type'] == "2") $type = _profile_type_2;
              elseif($get['type'] == "3") $type = _profile_type_3;

              $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=profile&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=profile&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_profil)));
          $show_hardware .= show($dir."/profil_show", array("class" => $class,
                                                            "name" => re($get['name']),
                                                                                  "type" => $type,
                                                                                        "shown" => $shown,
                                                            "edit" => $edit,
                                                            "del" => $delete));
        }

        $show = show($dir."/profil", array("show_about" => $show_about,
                                                             "show_clan" => $show_clan,
                                                             "show_contact" => $show_contact,
                                                             "show_favos" => $show_favos,
                                                             "show_hardware" => $show_hardware,
                                                             "about" => _profile_about,
                                                             "clan" => _profile_clan,
                                                             "contact" => _profile_contact,
                                                             "favos" => _profile_favos,
                                                             "hardware" => _profile_hardware,
                                           "name" => _profile_name,
                                           "info" => _navi_info,
                                           "standard" => _standard_link_do,
                                           "head" => _profile_head,
                                           "add" => _profile_add,
                                           "type" => _profile_type,
                                           "edit" => _editicon_blank,
                                           "del" => _deleteicon_blank,
                                                             "shown" => _profile_shown));
      }