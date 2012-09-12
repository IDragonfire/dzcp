<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       contentmenu
// Rechte:    permission('votesadmin')
///////////////////////////////
if(_adminMenu != 'true') exit;

    $where = $where.': '._votes_head;
    if(!permission("votesadmin"))
    {
      $show = error(_error_wrong_permissions, 1);
    } else {

      if($_GET['do'] == 'new')
      {
        $show = show($dir."/form_vote", array("head" => _votes_admin_head,
                                              "value" => _button_value_add,
                                              "what" => "&amp;do=add",
                                              "closed" => "",
                                              "question1" => "",
                                              "a1" => "",
                                              "a2" => "",
                                              "a3" => "",
                                              "a4" => "",
                                              "a5" => "",
                                              "a6" => "",
                                              "a7" => "",
                                              "error" => "",
                                              "br1" => "<!--",
                                              "br2" => "-->",
                                              "a8" => "",
                                              "a9" => "",
                                              "a10" => "",
                                              "intern" => "",
                                              "interna" => _votes_admin_intern,
                                              "question" => _votes_admin_question,
                                              "answer" => _votes_admin_answer));
      } elseif($_GET['do'] == "add") {
    	  if(empty($_POST['question']) || empty($_POST['a1']) || empty($_POST['a2']))
  		  {
  		    if(empty($_POST['question'])) $error = _empty_votes_question;
  		    elseif(empty($_POST['a1']))   $error = _empty_votes_answer;
  		    elseif(empty($_POST['a2']))   $error = _empty_votes_answer;

  	  	  $error = show("errors/errortable", array("error" => $error));

          if($_POST['intern']) $intern = "checked=\"checked\"";

          $show = show($dir."/form_vote", array("head" => _votes_admin_head,
                                                "value" => _button_value_add,
                                                "what" => "&amp;do=add",
                                                "question1" => re($_POST['question']),
                                                "a1" => $_POST['a1'],
                                                "closed" => "",
                                                "br1" => "<!--",
                                                "br2" => "-->",
                                                "a2" => $_POST['a2'],
                                                "a3" => $_POST['a3'],
                                                "a4" => $_POST['a4'],
                                                "a5" => $_POST['a5'],
                                                "a6" => $_POST['a6'],
                                                "a7" => $_POST['a7'],
                                                "error" => $error,
                                                "a8" => $_POST['a8'],
                                                "a9" => $_POST['a9'],
                                                "a10" => $_POST['a10'],
                                                "intern" => $intern,
                                                "interna" => _votes_admin_intern,
                                                "question" => _votes_admin_question,
                                                "answer" => _votes_admin_answer));
        } else {
          $qry = db("INSERT INTO ".$db['votes']."
                     SET `datum`  = '".((int)time())."',
                         `titel`  = '".up($_POST['question'])."',
                         `intern` = '".((int)$_POST['intern'])."',
                         `von`    = '".((int)$userid)."'");

          $vid = sql_get_insert_id();

          $qry = db("INSERT INTO ".$db['vote_results']."
                    SET `vid`   = '".((int)$vid)."',
                        `what`  = 'a1',
                        `sel`   = '".up($_POST['a1'])."'");

          $qry = db("INSERT INTO ".$db['vote_results']."
                     SET `vid`  = '".((int)$vid)."',
                         `what` = 'a2',
                         `sel`  = '".up($_POST['a2'])."'");

          if(!empty($_POST['a3']))
          {
            $qry = db("INSERT INTO ".$db['vote_results']."
                       SET `vid`  = '".((int)$vid)."',
                           `what` = 'a3',
                           `sel`  = '".up($_POST['a3'])."'");
          }
          if(!empty($_POST['a4']))
          {
            $qry = db("INSERT INTO ".$db['vote_results']."
                       SET `vid`  = '".((int)$vid)."',
                           `what` = 'a4',
                           `sel`  = '".up($_POST['a4'])."'");
          }
          if(!empty($_POST['a5']))
          {
            $qry = db("INSERT INTO ".$db['vote_results']."
                       SET `vid`  = '".((int)$vid)."',
                           `what` = 'a5',
                           `sel`  = '".up($_POST['a5'])."'");
          }
          if(!empty($_POST['a6']))
          {
            $qry = db("INSERT INTO ".$db['vote_results']."
                       SET `vid`  = '".((int)$vid)."',
                           `what` = 'a6',
                           `sel`  = '".up($_POST['a6'])."'");
          }
          if(!empty($_POST['a7']))
          {
            $qry = db("INSERT INTO ".$db['vote_results']."
                       SET `vid`  = '".((int)$vid)."',
                           `what` = 'a7',
                           `sel`  = '".up($_POST['a7'])."'");
          }
          if(!empty($_POST['a8']))
          {
            $qry = db("INSERT INTO ".$db['vote_results']."
                       SET `vid`  = '".((int)$vid)."',
                           `what` = 'a8',
                           `sel`  = '".up($_POST['a8'])."'");
          }
          if(!empty($_POST['a9']))
          {
            $qry = db("INSERT INTO ".$db['vote_results']."
                       SET `vid`  = '".((int)$vid)."',
                           `what` = 'a9',
                           `sel`  = '".up($_POST['a9'])."'");
          }
          if(!empty($_POST['a10']))
          {
            $qry = db("INSERT INTO ".$db['vote_results']."
                       SET `vid`  = '".((int)$vid)."',
                           `what` = 'a10',
                           `sel`  = '".up($_POST['a10'])."'");
          }

          $show = info(_vote_admin_successful, "?admin=votes");
        }
      } elseif($_GET['do'] == "delete") {
        $qry = db("DELETE FROM ".$db['votes']."
                   WHERE id = '".intval($_GET['id'])."'");

        $qry = db("DELETE FROM ".$db['vote_results']."
                   WHERE vid = '".intval($_GET['id'])."'");

        $vid = "vid_".$_GET['id'];
        $qry = db("DELETE FROM ".$db['ipcheck']."
                   WHERE what = '".$vid."'");

        $show = info(_vote_admin_delete_successful, "?admin=votes");
      } elseif($_GET['do'] == "edit") {
        $qry = db("SELECT * FROM ".$db['votes']."
                   WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

        if($get['intern'] == "1") $intern = "checked=\"checked\"";
        if($get['closed'] == "1") $isclosed = "checked=\"checked\"";

        $what = "&amp;do=editvote&amp;id=".$_GET['id']."";
        
        $show = show($dir."/form_vote", array("head" => _votes_admin_edit_head,
                                              "value" => "edit",
                                              "id" => $_GET['id'],
                                              "what" => $what,
                                              "value" => _button_value_edit,
                                              "br1" => "",
                                              "br2" => "",
                                              "question1" => re($get['titel']),
                                              "a1" => voteanswer("a1",$_GET['id']),
                                              "a2" => voteanswer("a2",$_GET['id']),
                                              "a3" => voteanswer("a3",$_GET['id']),
                                              "a4" => voteanswer("a4",$_GET['id']),
                                              "a5" => voteanswer("a5",$_GET['id']),
                                              "a6" => voteanswer("a6",$_GET['id']),
                                              "a7" => voteanswer("a7",$_GET['id']),
                                              "error" => "",
                                              "a8" => voteanswer("a8",$_GET['id']),
                                              "a9" => voteanswer("a9",$_GET['id']),
                                              "a10" => voteanswer("a10",$_GET['id']),
                                              "intern" => $intern,
                                              "isclosed" => $isclosed,
                                              "closed" => _votes_admin_closed,
                                              "interna" => _votes_admin_intern,
                                              "question" => _votes_admin_question,
                                              "answer" => _votes_admin_answer));
      } elseif($_GET['do'] == "editvote") {
        $qry = db("SELECT * FROM ".$db['vote_results']."
                  WHERE vid = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

        $upd = db("UPDATE ".$db['votes']."
                   SET `titel`  = '".up($_POST['question'])."',
                       `intern` = '".((int)$_POST['intern'])."',
                       `closed` = '".((int)$_POST['closed'])."'
                   WHERE id = '".intval($_GET['id'])."'");

        $upd1 = db("UPDATE ".$db['vote_results']."
                    SET `sel` = '".up($_POST['a1'])."'
                    WHERE what = 'a1'
                    AND vid = '".intval($_GET['id'])."'");

        $upd2 = db("UPDATE ".$db['vote_results']."
                    SET `sel` = '".up($_POST['a2'])."'
                    WHERE what = 'a2'
                    AND vid = '".intval($_GET['id'])."'");

        for($i=3; $i<=10; $i++)
        {
          if(!empty($_POST['a'.$i.'']))
          {
            if(cnt($db['vote_results'], " WHERE vid = '".intval($_GET['id'])."' AND what = 'a".$i."'") != 0)
            {
              $upd = db("UPDATE ".$db['vote_results']."
                         SET `sel` = '".up($_POST['a'.$i.''])."'
                         WHERE what = 'a".$i."'
                         AND vid = '".intval($_GET['id'])."'");
            } else {
              $ins = db("INSERT INTO ".$db['vote_results']."
                         SET `vid` = '".$_GET['id']."',
                             `what` = 'a".$i."',
                             `sel` = '".up($_POST['a'.$i.''])."'");
            }
          }

          if(cnt($db['vote_results'], " WHERE vid = '".intval($_GET['id'])."' AND what = 'a".$i."'") != 0 && empty($_POST['a'.$i.'']))
          {
            $del = db("DELETE FROM ".$db['vote_results']."
                       WHERE vid = '".intval($_GET['id'])."'
                       AND what = 'a".$i."'");
          }
        }

        $show = info(_vote_admin_successful_edited, "?admin=votes");
      } elseif($_GET['do'] == "menu") {
        $qryv = db("SELECT intern FROM ".$db['votes']."
                    WHERE id = '".intval($_GET['id'])."'
                    AND intern = 1");
        if(_rows($qryv))
        {
          $show = error(_vote_admin_menu_isintern, 1);
        } else {
          $qrys = db("SELECT * FROM ".$db['votes']."
                      WHERE id = '".intval($_GET['id'])."'");
          $get = _fetch($qrys);

          if($get['menu'] == 1)
          {
            $qry = db("UPDATE ".$db['votes']."
                       SET menu = '0'");

            header("Location: ?admin=votes");
          } else {
            $qry = db("UPDATE ".$db['votes']."
                       SET menu = '0'");

            $qry = db("UPDATE ".$db['votes']."
                       SET menu = '1'
                       WHERE id = '".intval($_GET['id'])."'");

            header("Location: ?admin=votes");
          }
        }
      } else {
        $qry = db("SELECT * FROM ".$db['votes']."
		           WHERE forum = 0
                   ORDER BY datum DESC");
        while($get = _fetch($qry))
        {
          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=votes&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=votes&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_vote)));
          if($get['menu'] == "1") $icon = "yes";
          else $icon = "no";

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

          $show_ .= show($dir."/votes_show", array("date" => date("d.m.Y",$get['datum']),
                                                   "vote" => re($get['titel']),
                                                   "class" => $class,
                                                   "edit" => $edit,
                                                   "icon" => $icon,
                                                   "delete" => $delete,
                                                   "autor" => autor($get['von']),
                                                   "id" => $get['id']));
        }

        $show = show($dir."/votes", array("head" => _votes_head,
                                          "date" => _datum,
                                          "autor" => _autor,
                                          "add" => _votes_admin_head,
                                          "stimmen" => _votes_stimmen,
                                          "titel" => _titel,
                                          "yesno" => _yesno,
                                          "legende" => _legende,
                                          "legendemenu" => _vote_legendemenu,
                                          "show" => $show_));
      }
    }
?>