<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       contentmenu
// Rechte:    permission('links')
///////////////////////////////
if(_adminMenu != 'true') exit;

    $where = $where.': '._config_links;
    if(!permission("links"))
    {
      $index = error(_error_wrong_permissions, 1);
    } else {
      if($_GET['do'] == "new")
      {
        $linktyp = '
<tr>
  <td class="contentMainTop" width="25%"><span class="fontBold">'._link_type.':</span></td>
  <td class="contentMainFirst" align="center">
    <table class="hperc" cellspacing="2">
      <tr>
        <td style="width:20px"><input type="radio" name="type" class="checkbox" value="links" checked=\"checked\" /></td>
        <td>'._link.'</td>
      </tr>
      <tr>
        <td><input type="radio" name="type" class="checkbox" value="sponsoren" /></td>
        <td>'._sponsor.'</td>
      </tr>
    </table>
  </td>
</tr>';
        $show = show($dir."/form_links", array("head" => _links_admin_head,
                                               "link" => _links_link,
                                               "beschreibung" => _links_beschreibung,
                                               "art" => _links_art,
                                               "linktyp" => $linktyp,
                                               "text" => _links_admin_textlink,
                                               "banner" => _links_admin_bannerlink,
                                               "bchecked" => "checked=\"checked\"",
                                               "tchecked" => "",
                                               "llink" => "",
                                               "lbeschreibung" => "",
                                               "btext" => _links_text,
                                               "ltext" => "",
                                               "what" => _button_value_add,
                                               "do" => "add"));
      } elseif($_GET['do'] == "add") {
        if(empty($_POST['link']) || empty($_POST['beschreibung']) || (isset($_POST['banner']) && empty($_POST['text'])))
        {
          if(empty($_POST['link']))             $show = error(_links_empty_link, 1);
          elseif(empty($_POST['beschreibung'])) $show = error(_links_empty_beschreibung, 1);
          elseif(empty($_POST['text']))         $show = error(_links_empty_text, 1);
        } else {
          $qry = db("INSERT INTO ".$db['links']."
                     SET `url`          = '".links($_POST['link'])."',
                         `text`         = '".up($_POST['text'])."',
                         `banner`       = '".up($_POST['banner'])."',
                         `beschreibung` = '".up($_POST['beschreibung'], 1)."'");

          $show = info(_link_added, "?admin=links");
        }
      } elseif($_GET['do'] == "edit") {

        $qry = db("SELECT * FROM ".$db[$_GET['type']]."
                   WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

        if($get['banner'] == 1) $bchecked = "checked=\"checked\"";
        else $tchecked = "checked=\"checked\"";

        $linktyp = '<input type="hidden" name="type" value="'.$_GET['type'].'" />';

        $show = show($dir."/form_links", array("head" => _links_admin_head_edit,
                                               "link" => _links_link,
                                               "linktyp" => $linktyp,
                                               "beschreibung" => _links_beschreibung,
                                               "art" => _links_art,
                                               "text" => _links_admin_textlink,
                                               "banner" => _links_admin_bannerlink,
                                               "bchecked" => $bchecked,
                                               "tchecked" => $tchecked,
                                               "llink" => $get['url'],
                                               "lbeschreibung" => re($get['beschreibung']),
                                               "btext" => _links_text,
                                               "ltext" => re($get['text']),
                                               "what" => _button_value_edit,
                                               "do" => "editlink&amp;id=".$_GET['id'].""));
      } elseif($_GET['do'] == "editlink") {
        if(empty($_POST['link']) || empty($_POST['beschreibung']) || (isset($_POST['banner']) && empty($_POST['text'])))
        {
          if(empty($_POST['link']))             $show = error(_links_empty_link, 1);
          elseif(empty($_POST['beschreibung'])) $show = error(_links_empty_beschreibung, 1);
          elseif(empty($_POST['text']))         $show = error(_links_empty_text, 1);
        } else {
            $qry = db("UPDATE ".$db['links']."
                       SET `url`          = '".links($_POST['link'])."',
                           `text`         = '".up($_POST['text'])."',
                           `banner`       = '".up($_POST['banner'])."',
                           `beschreibung` = '".up($_POST['beschreibung'],1)."'
                       WHERE id = '".intval($_GET['id'])."'");

          $show = info(_link_edited, "?admin=links");
        }
      } elseif($_GET['do'] == "delete") {
        $qry = db("DELETE FROM ".$db[$_GET['type']]."
                   WHERE id = '".intval($_GET['id'])."'");

        $show = info(_link_deleted, "?admin=links");
      } else {
        $qry = db("SELECT * FROM ".$db['links']."
                   ORDER BY banner DESC");
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=links&amp;do=edit&amp;type=links",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=links&amp;do=delete&amp;type=links",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_link)));

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

          $show1 .= show($dir."/links_show", array("link" => cut(re($get['url']),40),
                                                   "class" => $class,
                                                   "type" => "links",
                                                   "edit" => $edit,
                                                   "delete" => $delete
                                                   ));
        }

        $show = show($dir."/links", array("head1" => _links_head,
                                          "head2" => _sponsor_head,
                                          "titel" => _link,
                                          "show1" => $show1,
                                          "add" => _links_admin_head
                                          ));
      }
    }
?>