<?php
if(_adminMenu != 'true') exit;

    $where = $where.': '._dl;
      if($_GET['do'] == "new")
      {
        $qry = db("SELECT * FROM ".$db['dl_kat']."
                   ORDER BY name");
        while($get = _fetch($qry))
        {
          $kats .= show(_select_field, array("value" => $get['id'],
                                             "what" => re($get['name']),
                                             "sel" => ""));
        }

        $files = get_files('../downloads/files/',false,true);
        for($i=0; $i<count($files); $i++)
        {
          $dl .= show(_downloads_files_exists, array("dl" => $files[$i],
                                                     "sel" => ""));
        }

        $show = show($dir."/form_dl", array("admin_head" => _downloads_admin_head,
                                            "ddownload" => "",
                                            "dintern" => "",
                                             "durl" => "",
                                             "oder" => _or,
                                             "lang" => $language,
                                             "file" => $dl,
                                             "nothing" => "",
                                             "nofile" => _downloads_nofile,
                                             "lokal" => _downloads_lokal,
                                             "what" => _button_value_add,
                                             "do" => "add",
                                             "exist" => _downloads_exist,
                                             "dbeschreibung" => "",
                                             "kat" => _downloads_kat,
                                             "kats" => $kats,
                                             "url" => _downloads_url,
                                             "beschreibung" => _beschreibung,
                                             "download" => _downloads_name,
                                             "intern" => _internal));
      } elseif($_GET['do'] == "add") {
        if(empty($_POST['download']) || empty($_POST['url']))
        {
          if(empty($_POST['download'])) $show = error(_downloads_empty_download, 1);
          elseif(empty($_POST['url']))  $show = error(_downloads_empty_url, 1);
        } else {

          if(preg_match("#^www#i",$_POST['url'])) $dl = links($_POST['url']);
          else                                    $dl = up($_POST['url']);

          $qry = db("INSERT INTO ".$db['downloads']."
                     SET `download`     = '".up($_POST['download'])."',
                         `url`          = '".$dl."',
                         `date`         = '".((int)time())."',
                         `beschreibung` = '".up($_POST['beschreibung'],1)."',
                         `kat`          = '".((int)$_POST['kat'])."',
                         `intern`          = '".((int)$_POST['intern'])."'");

          $show = info(_downloads_added, "?admin=dladmin");
        }
      } elseif($_GET['do'] == "edit") {
        $qry  = db("SELECT * FROM ".$db['downloads']."
                    WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

        $qryk = db("SELECT * FROM ".$db['dl_kat']."
                    ORDER BY name");
        while($getk = _fetch($qryk))
        {
          if($getk['id'] == $get['kat']) $sel = "selected=\"selected\"";
          else $sel = "";

          $kats .= show(_select_field, array("value" => $getk['id'],
                                             "what" => re($getk['name']),
                                             "sel" => $sel));
        }

        $show = show($dir."/form_dl", array("admin_head" => _downloads_admin_head_edit,
                                            "ddownload" => re($get['download']),
                                            "dintern" => $get['intern'] ? 'checked="checked"' : '',
                                            "durl" => re($get['url']),
                                            "file" => $dl,
                                            "lokal" => _downloads_lokal,
                                            "exist" => _downloads_exist,
                                            "nothing" => _nothing,
                                            "nofile" => _downloads_nofile,
                                            "oder" => _or,
                                            "dbeschreibung" => re_bbcode($get['beschreibung']),
                                            "kat" => _downloads_kat,
                                            "what" => _button_value_edit,
                                            "do" => "editdl&amp;id=".$_GET['id']."",
                                            "kats" => $kats,
                                            "url" => _downloads_url,
                                            "beschreibung" => _beschreibung,
                                            "download" => _downloads_name,
                                            "intern" => _internal));
      } elseif($_GET['do'] == "editdl") {
        if(empty($_POST['download']) || empty($_POST['url']))
        {
          if(empty($_POST['download'])) $show = error(_downloads_empty_download, 1);
          elseif(empty($_POST['url']))  $show = error(_downloads_empty_url, 1);
        } else {
          if(preg_match("#^www#i",$_POST['url'])) $dl = links($_POST['url']);
          else                                    $dl = up($_POST['url']);

          $qry = db("UPDATE ".$db['downloads']."
                     SET `download`     = '".up($_POST['download'])."',
                         `url`          = '".$dl."',
                         `beschreibung` = '".up($_POST['beschreibung'],1)."',
                         `date`         = '".((int)time())."',
                         `kat`          = '".((int)$_POST['kat'])."',
                         `intern`          = '".((int)$_POST['intern'])."'
                     WHERE id = '".intval($_GET['id'])."'");

          $show = info(_downloads_edited, "?admin=dladmin");
        }
      } elseif($_GET['do'] == "delete") {
        $qry = db("DELETE FROM ".$db['downloads']."
                   WHERE id = '".intval($_GET['id'])."'");

        $show = info(_downloads_deleted, "?admin=dladmin");
      } else {
        $qry = db("SELECT * FROM ".$db['downloads']."
                   ORDER BY id");
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=dladmin&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=dladmin&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_dl)));

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $show_ .= show($dir."/downloads_show", array("id" => $get['id'],
                                                       "dl" => re($get['download']),
                                                       "class" => $class,
                                                       "edit" => $edit,
                                                       "delete" => $delete
                                                       ));
        }

        $show = show($dir."/downloads", array("head" => _dl,
                                              "date" => _datum,
                                              "titel" => _dl_file,
                                              "add" => _downloads_admin_head,
                                              "show" => $show_
                                              ));
      }