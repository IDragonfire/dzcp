<?php
if(_adminMenu != 'true') exit;

    $where = $where.': '._partners_head;
      if($do == "add")
      {
        $files = get_files('../banner/partners/',false,true);
        for($i=0; $i<count($files); $i++)
        {
          $banners .= show(_partners_select_icons, array("icon" => $files[$i],
                                                         "sel" => ""));
        }
        $show = show($dir."/form_partners", array("do" => "addbutton",
                                                  "head" => _partners_add_head,
                                                  "nothing" => "",
                                                  "banner" => _partners_button,
                                                  "link" => _link,
                                                  "e_link" => "",
                                                  "e_textlink" => "",
                                                  "or" => _or,
                                                  "textlink" => _partnerbuttons_textlink,
                                                  "banners" => $banners,
                                                  "what" => _button_value_add));
      } elseif($do == "addbutton") {
        if(empty($_POST['link']))
        {
          $show = error(_empty_url, 1);
        } else {
          $qry = db("INSERT INTO ".$db['partners']."
                     SET `link`     = '".links($_POST['link'])."',
                         `banner`   = '".up(empty($_POST['textlink']) ? $_POST['banner'] : $_POST['textlink'])."',
                         `textlink` = '".intval(empty($_POST['textlink']) ? 0 : 1)."'");

          $show = info(_partners_added, "?admin=partners");
        }
      } elseif($do == "edit") {
        $qry = db("SELECT * FROM ".$db['partners']."
                   WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

        $files = get_files('../banner/partners/',false,true);
        for($i=0; $i<count($files); $i++)
        {
          if(re($get['banner']) == $files[$i]) $sel = "selected=\"selected\"";
          else $sel = "";

          $banners .= show(_partners_select_icons, array("icon" => $files[$i],
                                                         "sel" => $sel));
        }
        $show = show($dir."/form_partners", array("do" => "editbutton&amp;id=".$get['id']."",
                                                  "head" => _partners_edit_head,
                                                  "nothing" => "",
                                                  "banner" => _partners_button,
                                                  "link" => _link,
                                                  "e_link" => re($get['link']),
                                                  "e_textlink" => (empty($get['textlink']) ? '' : re($get['banner'])),
                                                  "or" => _or,
                                                  "textlink" => _partnerbuttons_textlink,
                                                  "banners" => $banners,
                                                  "what" => _button_value_edit));
      } elseif($do == "editbutton") {
        if(empty($_POST['link']))
        {
          $show = error(_empty_url, 1);
        } else {
          $qry = db("UPDATE ".$db['partners']."
                     SET `link`     = '".links($_POST['link'])."',
                         `banner`   = '".up(empty($_POST['textlink']) ? $_POST['banner'] : $_POST['textlink'])."',
                         `textlink` = '".intval(empty($_POST['textlink']) ? 0 : 1)."'
                     WHERE id = '".intval($_GET['id'])."'");

          $show = info(_partners_edited, "?admin=partners");
        }
      } elseif($do == "delete") {
        $del = db("DELETE FROM ".$db['partners']."
                   WHERE id = '".intval($_GET['id'])."'");

        $show = info(_partners_deleted,"?admin=partners");
      } else {
        $qry = db("SELECT * FROM ".$db['partners']."
                   ORDER BY id");
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=partners&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=partners&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_entry)));

          $rlink = str_replace('http://', '', re($get['link']));
          $button = '<img src="../banner/partners/'.re($get['banner']).'" alt="'.$rlink.'" title="'.$rlink.'" />';
          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $show_ .= show($dir."/partners_show", array("class" => $class,
                                                      "button" => (empty($get['textlink']) ? $button : '<center>'._partnerbuttons_textlink.': <b>'.re($get['banner']).'</b></center>'),
                                                      "link" => re($get['link']),
                                                      "id" => $get['id'],
                                                      "edit" => $edit,
                                                      "delete" => $delete));
        }

        $show = show($dir."/partners", array("head" => _partners_head,
                                             "add" => _partners_link_add,
                                             "show" => $show_,
                                             "edit" => _editicon_blank,
                                             "del" =>_deleteicon_blank,
                                             "link" => _link,
                                             "button" => _partners_button));
      }