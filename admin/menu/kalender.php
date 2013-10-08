<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       contentmenu
// Rechte:    permission('editkalender')
///////////////////////////////
if(_adminMenu != 'true') exit;

  $where = $where.': '._kalender_head;
  if(!permission("editkalender"))
  {
    $show = error(_error_wrong_permissions, 1);
  } else {
    if($_GET['do'] == "add")
    {
      $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())),
				      	                                  "month" => dropdown("month",date("m",time())),
                                      	          "year" => dropdown("year",date("Y",time()))));

      $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",time())),
                                      	          "minute" => dropdown("minute",date("i",time())),
                                                  "uhr" => _uhr));
      $show = show($dir."/form_kalender", array("datum" => _datum,
                                                "event" => _kalender_event,
                                                "dropdown_time" => $dropdown_time,
                                                "dropdown_date" => $dropdown_date,
                                                "beschreibung" => _beschreibung,
                                                "what" => _button_value_add,
                                                "do" => "addevent",
                                                "k_event" => "",
                                                "k_beschreibung" => "",
                                                "head" => _kalender_admin_head));
    } elseif($_GET['do'] == "addevent") {
      if(empty($_POST['title']) || empty($_POST['event']))
      {
        if(empty($_POST['title']))     $show = error(_kalender_error_no_title,1);
        elseif(empty($_POST['event'])) $show = error(_kalender_error_no_event,1);
      } else {
        $time = mktime($_POST['h'],$_POST['min'],0,$_POST['m'],$_POST['t'],$_POST['j']);

        $insert = db("INSERT INTO ".$db['events']."
                      SET `datum` = '".((int)$time)."',
                          `title` = '".up($_POST['title'])."',
                          `event` = '".up($_POST['event'],1)."'");

        $show = info(_kalender_successful_added,"?admin=kalender");
      }
    } elseif($_GET['do'] == "edit") {
      $qry = db("SELECT * FROM ".$db['events']."
                 WHERE id = '".intval($_GET['id'])."'");
      $get = _fetch($qry);

      $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",$get['datum'])),
				      	                                  "month" => dropdown("month",date("m",$get['datum'])),
                                      	          "year" => dropdown("year",date("Y",$get['datum']))));

      $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",$get['datum'])),
                                      	          "minute" => dropdown("minute",date("i",$get['datum'])),
                                                  "uhr" => _uhr));
      $show = show($dir."/form_kalender", array("datum" => _datum,
                                                "event" => _kalender_event,
                                                "dropdown_time" => $dropdown_time,
                                                "dropdown_date" => $dropdown_date,
                                                "beschreibung" => _beschreibung,
                                                "what" => _button_value_edit,
                                                "do" => "editevent&amp;id=".$_GET['id'],
                                                "k_event" => re($get['title']),
                                                "k_beschreibung" => re_bbcode($get['event']),
                                                "head" => _kalender_admin_head_edit));
    } elseif($_GET['do'] == "editevent") {
      if(empty($_POST['title']) || empty($_POST['event']))
      {
        if(empty($_POST['title']))     $show = error(_kalender_error_no_title,1);
        elseif(empty($_POST['event'])) $show = error(_kalender_error_no_event,1);
      } else {
        $time = mktime($_POST['h'],$_POST['min'],0,$_POST['m'],$_POST['t'],$_POST['j']);

        $update = db("UPDATE ".$db['events']."
                      SET `datum` = '".((int)$time)."',
                          `title` = '".up($_POST['title'])."',
                          `event` = '".up($_POST['event'],1)."'
                      WHERE id = '".intval($_GET['id'])."'");

        $show = info(_kalender_successful_edited,"?admin=kalender");
      }
    } elseif($_GET['do'] == "delete") {
      $del = db("DELETE FROM ".$db['events']."
                 WHERE id = '".intval($_GET['id'])."'");

      $show = info(_kalender_deleted,"?admin=kalender");
    } else {
      if(!empty($_GET['orderby']) && in_array($_GET['orderby'],array("titel","datum"))) {
	        $qry = db("SELECT * FROM ".$db['events']."
                   ORDER BY ".mysql_real_escape_string($_GET['orderby']." ".$_GET['order'])."");
	  }
      else {$qry = db("SELECT * FROM ".$db['events']."
                 ORDER BY datum DESC");
	  }
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=kalender&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=kalender&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_kalender)));

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

          $show_ .= show($dir."/kalender_show", array("datum" => date("d.m.y H:i", $get['datum'])._uhr,
                                                      "event" => re($get['title']),
                                                      "time" => $get['datum'],
                                                      "id" => $get['sqid'],
                                                      "class" => $class,
                                                      "edit" => $edit,
                                                      "delete" => $delete));
        }

        $show = show($dir."/kalender", array("head" => _kalender_admin_head,
                                             "date" => _datum,
                                             "titel" => _kalender_event,
                                             "show" => $show_,
											 "order_date" => orderby('datum'),
											 "order_titel" => orderby('event'),
                                             "add" => _kalender_admin_head_add
                                             ));
    }
  }
?>