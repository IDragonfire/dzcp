<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;

    $where = $where.': '._awards_head;
        if($do == "new")
      {
          $qry = db("SELECT * FROM ".$db['squads']."
                   ORDER BY game ASC");
          while($get = _fetch($qry))
        {
          $squads .= show(_awards_admin_add_select_field_squads, array("name" => $get['name'],
                                                                            "game" => $get['game'],
                                                                       "icon" => $get['icon'],
                                                                                                "id" => $get['id']));
        }

          $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())),
                                                                         "month" => dropdown("month",date("m",time())),
                                                        "year" => dropdown("year",date("Y",time()))));

        $show = show($dir."/form_awards", array("head" => _awards_admin_head_add,
                                                "date" => _awards_head_date,
                                                                  "squad" => _awards_head_squad,
                                                                  "event" => _awards_head_event,
                                                                  "url" => _awards_head_link,
                                                                  "place" => _awards_head_place,
                                                                  "prize" => _awards_head_prize,
                                                                  "squads" => $squads,
                                                                  "dropdown_date" => $dropdown_date,
                                                                 "do" => "add",
                                                "what" => _button_value_add,
                                                                  "award_event" => "",
                                                "award_url" => "",
                                                "award_place" => "",
                                                "award_prize" => ""));
      } elseif($do == "edit") {
        $qry = db("SELECT * FROM ".$db['awards']."
                   WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

          $qrym = db("SELECT * FROM ".$db['squads']."
                    ORDER BY game");
          while($gets = _fetch($qrym))
          {
              if($get['squad'] == $gets['id']) $sel = 'selected="selected"';
            else $sel = "";

             $squads .= show(_awards_admin_edit_select_field_squads, array("id" => $gets['id'],
                                                                                                          "name" => re($gets['name']),
                                                                                   "game" => re($gets['game']),
                                                                        "icon" => re($gets['icon']),
                                                                                                    "sel" => $sel));
          }

          $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",$get['date'])),
                                                                        "month" => dropdown("month",date("m",$get['date'])),
                                                        "year" => dropdown("year",date("Y",$get['date']))));

        $show = show($dir."/form_awards", array("head" => _awards_admin_head_edit,
                                                "date" => _awards_head_date,
                                                                  "squad" => _awards_head_squad,
                                                                  "event" => _awards_head_event,
                                                                  "url" => _awards_head_link,
                                                                  "place" => _awards_head_place,
                                                                  "prize" => _awards_head_prize,
                                                "do" => "editaw&amp;id=".$_GET['id']."",
                                                "what" => _button_value_edit,
                                                                  "squads" => $squads,
                                                                  "dropdown_date" => $dropdown_date,
                                                                  "award_event" => re($get['event']),
                                                                   "award_url" => $get['url'],
                                                                  "award_place" => re($get['place']),
                                                                  "award_prize" => re($get['prize'])));
      } elseif($do == "add") {
          if(empty($_POST['event']) || empty($_POST['url']))
        {
              if(empty($_POST['event']))
              {
                  $show = error(_awards_empty_event, 1);
              } elseif(empty($_POST['url'])) {
                  $show = error(_awards_empty_url, 1);
              }
        } else {
              if(empty($_POST['place'])) $place = "-";
              else $place = $_POST['place'];

              if(empty($_POST['prize'])) $prize = "-";
              else $prize = $_POST['prize'];

          $datum = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);

          $qry = db("INSERT INTO ".$db['awards']."
                     SET `date`     = '".((int)$datum)."',
                         `postdate` = '".time()."',
                                   `squad`    = '".((int)$_POST['squad'])."',
                                 `event`    = '".up($_POST['event'])."',
                         `url`      = '".links($_POST['url'])."',
                                   `place`    = '".up($place)."',
                                   `prize`    = '".up($prize)."'");

          $show = info(_awards_admin_added, "?admin=awards");
        }
      } elseif($do == "editaw") {
          if(empty($_POST['event']) || empty($_POST['url']))
        {
              if(empty($_POST['event']))
              {
                  $index = error(_awards_empty_event, 1);
              } elseif(empty($_POST['url'])) {
                  $index = error(_awards_empty_url, 1);
              }
        } else {
              if(empty($_POST['place'])) $place = "-";
              else $place = $_POST['place'];
            }

        if(empty($_POST['prize'])) $prize = "-";
            else $prize = $_POST['prize'];

            $datum = mktime(0,0,0,$_POST['m'],$_POST['t'],$_POST['j']);

            $qry = db("UPDATE ".$db['awards']."
                   SET `date`   = '".((int)$datum)."',
                                 `squad`  = '".(int)($_POST['squad'])."',
                                 `event`  = '".up($_POST['event'])."',
                       `url`    = '".links($_POST['url'])."',
                                 `place`  = '".up($place)."',
                                 `prize`  = '".up($prize)."'
                   WHERE id = '".intval($_GET['id'])."'");

        $show = info(_awards_admin_edited, "?admin=awards");
      } elseif($do == "delete") {
        $qry = db("DELETE FROM ".$db['awards']."
                   WHERE id = '".intval($_GET['id'])."'");

        $show = info(_awards_admin_deleted, "?admin=awards");
      } else {
        $qry = db("SELECT * FROM ".$db['awards']." ".orderby_sql(array("event","date"), 'ORDER BY date DESC')); $show_ = '';
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=awards&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=awards&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_award)));

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

          $show_ .= show($dir."/awards_show", array("datum" => date("d.m.Y",$get['date']),
                                                    "award" => re($get['event']),
                                                    "id" => $get['squad'],
                                                    "class" => $class,
                                                    "edit" => $edit,
                                                    "delete" => $delete));
        }

        $show = show($dir."/awards", array("head" => _awards_head,
                                           "date" => _datum,
                                           "titel" => _award,
                                           "show" => $show_,
                                           "order_titel" => orderby('event'),
                                           "order_date" => orderby('date'),
                                           "add" => _awards_admin_head_add
                                           ));
      }