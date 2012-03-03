<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       contentmenu
// Rechte:    permission('rankings')
///////////////////////////////
if(_adminMenu != 'true') exit;

    $where = $where.': '._config_rankings;
    if(!permission("rankings"))
    {
      $show = error(_error_wrong_permissions, 1);
    } else {
      if($_GET['do'] == "add")
      {
        $qrys = db("SELECT * FROM ".$db['squads']."
                    WHERE status = '1'
                    ORDER BY game ASC");
        while($gets = _fetch($qrys))
        {
          $squads .= show(_select_field_ranking_add, array("what" => re($gets['name']),
  		              									                     "value" => $gets['id'],
                                                           "icon" => $gets['icon'],
                                                           "sel" => ""));
        }
        $show = show($dir."/form_rankings", array("head" => _rankings_add_head,
                                                  "do" => "addranking",
                                                  "what" => _button_value_add,
                                                  "squad" => _rankings_squad,
                                                  "league" => _rankings_league,
                                                  "rank" => _rankings_admin_place,
                                                  "squads" => $squads,
                                                  "e_league" => "",
                                                  "e_rank" => "",
                                                  "e_url" => "",
                                                  "url" => _rankings_teamlink));
      } elseif($_GET['do'] == "addranking") {
        if(empty($_POST['league']) || empty($_POST['url']) || empty($_POST['rank']))
        {
          if(empty($_POST['league']))   $show = error(_error_empty_league,1);
          elseif(empty($_POST['url']))  $show = error(_error_empty_url,1);
          elseif(empty($_POST['rank'])) $show = error(_error_empty_rank,1);
        } else {
          $qry = db("INSERT INTO ".$db['rankings']."
                     SET `league`   = '".up($_POST['league'])."',
                         `squad`    = '".up($_POST['squad'])."',
                         `url`      = '".links($_POST['url'])."',
                         `rank`     = '".((int)$_POST['rank'])."',
                         `postdate` = '".((int)time())."'");

          $show = info(_ranking_added, "?admin=rankings");
        }
      } elseif($_GET['do'] == "edit") {
        $qry = db("SELECT * FROM ".$db['rankings']."
                   WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

        $qrys = db("SELECT * FROM ".$db['squads']."
                    WHERE status = '1'
                    ORDER BY game ASC");
        while($gets = _fetch($qrys))
        {
          if($get['squad'] == $gets['id']) $sel = "selected=\"selected\"";
          else $sel = "";
          $squads .= show(_select_field_ranking_add, array("what" => re($gets['name']),
  		              									                     "value" => $gets['id'],
                                                           "icon" => $gets['icon'],
                                                           "sel" => $sel));
        }
        $show = show($dir."/form_rankings", array("head" => _rankings_edit_head,
                                                  "do" => "editranking&amp;id=".$_GET['id']."",
                                                  "what" => _button_value_edit,
                                                  "squad" => _rankings_squad,
                                                  "league" => _rankings_league,
                                                  "rank" => _rankings_admin_place,
                                                  "squads" => $squads,
                                                  "e_league" => re($get['league']),
                                                  "e_rank" => $get['rank'],
                                                  "e_url" => re($get['url']),
                                                  "url" => _rankings_teamlink));
      } elseif($_GET['do'] == "editranking") {
        if(empty($_POST['league']) || empty($_POST['url']) || empty($_POST['rank']))
        {
          if(empty($_POST['league']))   $show = error(_error_empty_league,1);
          elseif(empty($_POST['url']))  $show = error(_error_empty_url,1);
          elseif(empty($_POST['rank'])) $show = error(_error_empty_rank,1);
        } else {
          $qry = db("SELECT rank FROM ".$db['rankings']."
                     WHERE id = '".intval($_GET['id'])."'");
          $get = _fetch($qry);

          $qry = db("UPDATE ".$db['rankings']."
                     SET `league`       = '".up($_POST['league'])."',
                         `squad`        = '".up($_POST['squad'])."',
                         `url`          = '".links($_POST['url'])."',
                         `rank`         = '".((int)$_POST['rank'])."',
                         `lastranking`  = '".((int)$get['rank'])."',
                         `postdate`     = '".((int)time())."'
                     WHERE id = '".intval($_GET['id'])."'");

          $show = info(_ranking_edited, "?admin=rankings");
        }
      } elseif($_GET['do'] == "delete") {
        $del = db("DELETE FROM ".$db['rankings']."
                   WHERE id = '".intval($_GET['id'])."'");

        $show = info(_ranking_deleted, "?admin=rankings");
      } else {
      $qry = db("SELECT s1.*,s2.name,s2.id AS sqid FROM ".$db['rankings']." AS s1
                 LEFT JOIN ".$db['squads']." AS s2
                 ON s1.squad = s2.id
                 ORDER BY s1.postdate DESC");
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=rankings&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=rankings&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_ranking)));

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

          $show_ .= show($dir."/rankings_show", array("squad" => re($get['name']),
                                                      "league" => re($get['league']),
                                                      "id" => $get['sqid'],
                                                      "class" => $class,
                                                      "edit" => $edit,
                                                      "delete" => $delete));
        }

        $show = show($dir."/rankings", array("head" => _config_rankings,
                                             "league" => _cw_head_liga,
                                             "squad" => _cw_head_squad,
                                             "show" => $show_,
                                             "add" => _rankings_add_head
                                             ));
      }
    }
?>