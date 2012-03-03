<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       contentmenu
// Rechte:    permission('links')
///////////////////////////////
if(_adminMenu != 'true') exit;

    if(permission('links'))
    {
      if($_GET['do'] == "new")
      {
        $show = show($dir."/form_linkus", array("head" => _linkus_admin_head,
                                                "link" => _linkus_link,
                                                "beschreibung" => _linkus_beschreibung,
                                                "art" => _linkus_art,
                                                "text" => _linkus_admin_textlink,
                                                "banner" => _linkus_admin_bannerlink,
                                                "bchecked" => "checked=\"checked\"",
                                                "tchecked" => "",
                                                "llink" => _linkus_bsp_target,
                                                "lbeschreibung" => _linkus_bsp_desc,
                                                "btext" => _linkus_text,
                                                "ltext" => _linkus_bsp_bannerurl,
                                                "what" => _button_value_add,
                                                "do" => "add"));
      } elseif($_GET['do'] == "add") {
        if(empty($_POST['link']) || empty($_POST['beschreibung']) || empty($_POST['text']))
        {
          if(empty($_POST['link']))             $show = error(_linkus_empty_link, 1);
          elseif(empty($_POST['beschreibung'])) $show = error(_linkus_empty_beschreibung, 1);
          elseif(empty($_POST['text']))         $show = error(_linkus_empty_text, 1);
        } else {
          $qry = db("INSERT INTO ".$db['linkus']."
                     SET `url`          = '".links($_POST['link'])."',
                         `text`         = '".up($_POST['text'])."',
                         `banner`       = '".up($_POST['banner'])."',
                         `beschreibung` = '".up($_POST['beschreibung'])."'");
  
          $show = info(_linkus_added, "?admin=linkus");
        }
      } elseif($_GET['do'] == "edit") {
        $qry = db("SELECT * FROM ".$db['linkus']."
                   WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

        $show = show($dir."/form_linkus", array("head" => _linkus_admin_edit,
                                                "link" => _linkus_link,
                                                "beschreibung" => _linkus_beschreibung,
                                                "art" => _linkus_art,
                                                "text" => _linkus_admin_textlink,
                                                "banner" => _linkus_admin_bannerlink,
                                                "llink" => $get['url'],
                                                "lbeschreibung" => re($get['beschreibung']),
                                                "btext" => _linkus_text,
                                                "ltext" => $get['text'],
                                                "what" => _button_value_edit,
                                                "do" => "editlink&amp;id=".$_GET['id'].""));
      } elseif($_GET['do'] == "editlink") {
        if(empty($_POST['link']) || empty($_POST['beschreibung']) || empty($_POST['text']))
        {
          if(empty($_POST['link']))             $show = error(_linkus_empty_link, 1);
          elseif(empty($_POST['beschreibung'])) $show = error(_linkus_empty_beschreibung, 1);
          elseif(empty($_POST['text']))         $show = error(_linkus_empty_text, 1);
        } else {
          $qry = db("UPDATE ".$db['linkus']."
                     SET `url`          = '".links($_POST['link'])."',
                         `text`         = '".up($_POST['text'])."',
                         `banner`       = '".up($_POST['banner'])."',
                         `beschreibung` = '".up($_POST['beschreibung'])."'
                     WHERE id = '".intval($_GET['id'])."'");
  
          $show = info(_linkus_edited, "?admin=linkus");
        }
      } elseif($_GET['do'] == "delete") {
        $qry = db("DELETE FROM ".$db['linkus']."
                   WHERE id = '".intval($_GET['id'])."'");
  
        $show = info(_linkus_deleted, "?admin=linkus");
      } else {
        $qry = db("SELECT * FROM ".$db['linkus']."
                   ORDER BY banner DESC");
        $cnt = 1;
        while($get = _fetch($qry))
        {
          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    
          $banner = show(_linkus_bannerlink, array("id" => $get['id'],
                                                   "banner" => re($get['text'])));

          $edit = show("page/button_edit", array("id" => $get['id'],
                                                 "action" => "admin=linkus&amp;do=edit",
                                                 "title" => _button_title_edit));
          $delete = show("page/button_delete", array("id" => $get['id'],
                                                     "action" => "admin=linkus&amp;do=delete",
                                                     "title" => _button_title_del));

          $show_ .= show($dir."/linkus_show", array("class" => $class,
                                                    "beschreibung" => re($get['beschreibung']),
                                                    "edit" => $edit,
                                                    "delete" => $delete,
                                                    "cnt" => $cnt,
                                                    "banner" => $banner,
                                                    "besch" => re($get['beschreibung']),
    									    		                      "url" => $get['url']));
          $cnt++;
        }

        $show = show($dir."/linkus", array("head" => _linkus_head,
                                           "show" => $show_,
                                           "add" => _linkus_admin_head));
      }
    } else {
      $show = error(_error_wrong_permissions, 1);
    }
?>