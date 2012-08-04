<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       settingsmenu
// Rechte:    permission('news') || permission('artikel')
///////////////////////////////
if(_adminMenu != 'true') exit;

    $where = $where.': '._config_newskats_edit_head;
    if(!permission("news") && !permission('artikel'))
    {
      $show = error(_error_wrong_permissions, 1);
    } else {
      $qry = db("SELECT * FROM ".$db['newskat']."
                 ORDER BY `kategorie`");
      while($get = _fetch($qry))
      {
        $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                      "action" => "admin=news&amp;do=edit",
                                                      "title" => _button_title_edit));
        $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                          "action" => "admin=news&amp;do=delete",
                                                          "title" => _button_title_del,
                                                          "del" => convSpace(_confirm_del_kat)));
        $img = show(_config_newskats_img, array("img" => re($get['katimg'])));

        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $kats .= show($dir."/newskats_show", array("mainkat" => re($get['kategorie']),
                                                   "class" => $class,
                                                   "img" => $img,
                                                   "delete" => $delete,
                                                   "edit" => $edit));
      }

      $show = show($dir."/newskats", array("head" => _config_newskats_head,
                                           "kats" => $kats,
                                           "add" => _config_newskats_add_head,
                                           "img" => _config_newskats_katbild,
                                           "delete" => _deleteicon_blank,
                                           "edit" => _editicon_blank,
                                           "mainkat" => _config_newskats_kat));
      if($_GET['do'] == "delete")
      {
        $qry = db("SELECT katimg FROM ".$db['newskat']."
                   WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

        @unlink(basePath."/inc/images/newskat/".$get['katimg']);

        $del = db("DELETE FROM ".$db['newskat']."
                   WHERE id = '".intval($_GET['id'])."'");

        $show = info(_config_newskat_deleted, "?admin=news");
      } elseif($_GET['do'] == "add") {
        $files = get_files('../inc/images/newskat/',false,true);
        for($i=0; $i<count($files); $i++)
        {
          $img .= show(_select_field, array("value" => $files[$i],
                                            "sel" => "",
                                            "what" => $files[$i]));
        }

        $show = show($dir."/newskatform", array("head" => _config_newskats_add_head,
                                                "nkat" => _config_katname,
                                                "kat" => "",
                                                "value" => _button_value_add,
                                                "nothing" => "",
                                                "do" => "addnewskat",
                                                "nimg" => _config_newskats_katbild,
                                                "upload" => _config_neskats_katbild_upload,
                                                "img" => $img));
      } elseif($_GET['do'] == "addnewskat") {
        if(empty($_POST['kat']))
        {
          $show = error(_config_empty_katname,1);
        } else {
          $qry = db("INSERT INTO ".$db['newskat']."
                     SET `katimg`     = '".up($_POST['img'])."',
                         `kategorie`  = '".up($_POST['kat'])."'");

          $show = info(_config_newskats_added, "?admin=news");
        }
      } elseif($_GET['do'] == "edit") {
        $qry = db("SELECT * FROM ".$db['newskat']."
                   WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

        $files = get_files('../inc/images/newskat/',false,true);
        for($i=0; $i<count($files); $i++)
        {
          if($get['katimg'] == $files[$i]) $sel = "selected=\"selected\"";
          else $sel = '';

          $img .= show(_select_field, array("value" => $files[$i],
                                            "sel" => $sel,
                                            "what" => $files[$i]));
        }

        $upload = show(_config_neskats_katbild_upload_edit, array("id" => $_GET['id']));
        $do = show(_config_newskats_editid, array("id" => $_GET['id']));

        $show = show($dir."/newskatform", array("head" => _config_newskats_edit_head,
                                                "nkat" => _config_katname,
                                                "kat" => re($get['kategorie']),
                                                "value" => _button_value_edit,
                                                "id" => $_GET['id'],
                                                "nothing" => _nothing,
                                                "do" => $do,
                                                "nimg" => _config_newskats_katbild,
                                                "upload" => $upload,
                                                "img" => $img));
      } elseif($_GET['do'] == "editnewskat") {
        if(empty($_POST['kat']))
        {
          $show = error(_config_empty_katname,1);
        } else {
          if($_POST['img'] == "lazy") $katimg = "";
          else $katimg = "`katimg` = '".up($_POST['img'])."',";

          $qry = db("UPDATE ".$db['newskat']."
                     SET ".$katimg."
                         `kategorie` = '".up($_POST['kat'])."'
                     WHERE id = '".intval($_GET['id'])."'");

          $show = info(_config_newskats_edited, "?admin=news");
        }
      }
    }
?>