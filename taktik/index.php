<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$where = _site_taktiken;
$title = $pagetitle." - ".$where."";
$dir = "taktik";
## SECTIONS ##
switch ($action):
default:
  if(!$chkMe || $chkMe < 2)
  {
    $index = error(_error_wrong_permissions, 1);
  }
      if(!empty($_GET['orderby']) && in_array($_GET['orderby'],array("map","autor"))) {
      $qry = db("SELECT id,datum,map,spart,sparct,standardt,standardct,autor
                 FROM ".$db['taktik']."
                 ORDER BY ".mysqli_real_escape_string($mysql, $_GET['orderby']." ".$_GET['order'])."");
      }
      else {
      $qry = db("SELECT id,datum,map,spart,sparct,standardt,standardct,autor
                 FROM ".$db['taktik']."
                 ORDER BY id DESC");
      }
  {
      while ($get = _fetch($qry))
      {
        if($get['sparct'] != "") $sparct = show(_taktik_spar_ct, array("id" => $get['id']));
        else $sparct = "";

        if($get['spart'] != "") $spart = show(_taktik_spar_t, array("id" => $get['id']));
        else $spart = "";

        if($get['standardct'] != "") $standardct = show(_taktik_standard_ct, array("id" => $get['id']));
        else $standardct = "";

        if($get['standardt'] != "") $standardt = show(_taktik_standard_t, array("id" => $get['id']));
        else $standardt = "";

        $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                      "action" => "action=do&amp;what=edit",
                                                      "title" => _button_title_edit));
        $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                          "action" => "action=do&amp;what=delete",
                                                          "title" => _button_title_del,
                                                          "del" => convSpace(_confirm_del_taktik)));

        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $show .= show($dir."/taktiken_show", array("map" => re($get['map']),
                                                   "id" => $get['id'],
                                                   "class" => $class,
                                                   "standard_t" => $standardt,
                                                   "standard_ct" => $standardct,
                                                   "spar_t" => $spart,
                                                   "edit" => $edit,
                                                   "delete" => $delete,
                                                   "spar_ct" => $sparct,
                                                   "autor" => autor($get['autor'])));
    }

    $index .= show($dir."/taktiken", array("show" => $show,
                                           "taktik_head" => _taktik_head,
                                           "new_taktik" => _taktik_new,
                                           "upload" => _taktik_upload,
                                           "map" => _map,
                                           "edit" => _editicon_blank,
                                           "delete" => _deleteicon_blank,
                                           "t" => _taktik_t,
                                           "ct" => _taktik_ct,
                                           "order_map" => orderby('map'),
                                           "order_autor" => orderby('autor'),
                                           "autor" => _autor));
  }
break;
case 'spar':
  if ($chkMe < "2")
  {
    $index = error(_error_wrong_permissions, 1);
  } else {
    $qry = db("SELECT * FROM ".$db['taktik']."
               WHERE id = ".intval($_GET['id']));
    $get = _fetch($qry);

    if($_GET['what'] == "ct")
    {
      $what = _taktik_tspar_ct;
      $show = bbcode($get['sparct']);
    } elseif($_GET['what'] == "t") {
      $what = _taktik_tspar_t;
      $show = bbcode($get['spart']);
    }

    $posted = show(_taktik_posted, array("autor" => autor($get['autor']),
                                         "datum" => date("d.m.Y", $get['datum'])));

    $headline = show(_taktik_headline, array("what" => $what,
                                             "map" => re($get['map'])));

    $index = show($dir."/taktik", array("id" => $_GET['id'],
                                        "posted" => $posted,
                                        "headline" => $headline,
                                        "show" => $show));
  }
break;
case 'standard':
  if ($chkMe < "2")
  {
    $index = error(_error_wrong_permissions, 1);
  } else {
    $qry = db("SELECT * FROM ".$db['taktik']."
               WHERE id = ".intval($_GET['id']));
    $get = _fetch($qry);

    if($_GET['what'] == "ct")
    {
       $what = _taktik_tstandard_ct;
       $show = bbcode($get['standardct']);
    } elseif($_GET['what'] == "t") {
       $what = _taktik_tstandard_t;
       $show = bbcode($get['standardt']);
    }

    $posted = show(_taktik_posted, array("autor" => autor($get['autor']),
                                         "datum" => date("d.m.Y", $get['datum'])));

    $headline = show(_taktik_headline, array("what" => $what,
                                             "map" => re($get['map'])));

    $index = show($dir."/taktik", array("id" => $_GET['id'],
                                        "posted" => $posted,
                                        "headline" => $headline,
                                        "show" => $show));
  }
break;
case 'do':
  if(!permission("edittactics"))
  {
    $index = error(_error_wrong_permissions, 1);
  } else {
    $wysiwyg = '_word';

    if($_GET['what'] == "new")
    {
      $qry = db("SELECT * FROM ".$db['taktik']."");
      $get = _fetch($qry);

      $files = get_files("../inc/images/uploads/taktiken/",false,true);
      for($i=0; $i<count($files); $i++)
      {
        $screen .= show(_member_admin_select_icons, array("iconimg" => $files[$i]));
        $icons = $files[$i];
      }

      $index = show($dir."/new", array("date" => date("d.m.Y"),
                                       "autor" => autor($userid),
                                       "tautor" => _autor,
                                       "value" => _button_value_add,
                                       "map" => _map,
                                       "choose" => _taktik_choose,
                                       "screen" => $screen,
                                       "iconimg" => $icons,
                                       "spar_ct" => _taktik_tspar_ct,
                                       "spar_t" => _taktik_tspar_t,
                                       "standard_t" => _taktik_tstandard_t,
                                       "standard_ct" => _taktik_tstandard_ct,
                                       "newtaktik_head" => _taktik_new_head));


    } elseif ($_GET['what'] == "add") {
      if(!$_POST['map'])
      {
        $index = error(_error_taktik_empty_map, 1);
      } else {
        $qry = db("INSERT INTO ".$db['taktik']."
                   SET `datum`      = '".((int)time())."',
                       `map`        = '".up($_POST['map'])."',
                       `spart`      = '".up($_POST['spart'], 1)."',
                       `sparct`     = '".up($_POST['sparct'], 1)."',
                       `standardt`  = '".up($_POST['standardt'], 1)."',
                       `standardct` = '".up($_POST['standardct'], 1)."',
                       `autor`      = '".((int)$userid)."'");

        $index = info(_taktik_added, "../taktik/");
      }
    } elseif ($_GET['what'] == "delete" && $_GET['id']) {
      $qry = db("DELETE FROM ".$db['taktik']."
                 WHERE id = ".intval($_GET['id']));

      $index = info(_taktik_deleted, "../taktik/");
    } elseif ($_GET['what'] == "edit" && $_GET['id']) {
      $qry = db("SELECT * FROM ".$db['taktik']."
                 WHERE id = ".intval($_GET['id']));
      $get = _fetch($qry);

      $files = get_files("../inc/images/uploads/taktiken/",false,true);
      for($i=0; $i<count($files); $i++)
      {
        $screen .= show(_member_admin_select_icons, array("iconimg" => $files[$i]));
        $icons = $files[$i];
      }

      $index = show($dir."/edit", array("id" => $_GET['id'],
                                        "map" => re($get['map']),
                                        "autor" => autor($get['autor']),
                                        "value" => _button_value_edit,
                                        "tautor" => _autor,
                                        "tmap" => _map,
                                        "choose" => _taktik_choose,
                                        "select" => $select,
                                        "screen" => $screen,
                                        "spar_tct" => _taktik_tspar_ct,
                                        "spar_tt" => _taktik_tspar_t,
                                        "standard_tt" => _taktik_tstandard_t,
                                        "standard_tct" => _taktik_tstandard_ct,
                                        "edit_head" => _taktik_edit_head,
                                        "standard_t" => re($get['standardt']),
                                        "standard_ct" => re($get['standardct']),
                                        "spar_ct" => re($get['sparct']),
                                        "spar_t" => re($get['spart'])));

    } elseif ($_GET['what'] == "update" && $_POST['id']) {
      if(!$_POST['map'])
      {
        $index = error(_error_taktik_empty_map, 1);
      } else {
        $qry = db("UPDATE ".$db['taktik']."
                   SET `map`        = '".up($_POST['map'])."',
                       `sparct`     = '".up($_POST['sparct'], 1)."',
                       `spart`      = '".up($_POST['spart'], 1)."',
                       `standardct` = '".up($_POST['standardct'], 1)."',
                       `standardt`  = '".up($_POST['standardt'], 1)."'
                   WHERE id = ".intval($_POST['id']));

        $index = info(_error_taktik_edited, "../taktik/");
      }
    }
  }
break;
endswitch;
## SETTINGS ##
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where,$time,$wysiwyg);
## OUTPUT BUFFER END ##
gz_output();
?>

