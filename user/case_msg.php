<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_UserMenu')) {
  $where = _site_msg;
  if(!$chkMe)
    {
        $index = error(_error_have_to_be_logged, 1);
    } else {
      if($do == "show")
      {
      $qry = db("SELECT * FROM ".$db['msg']."
                           WHERE id = ".intval($_GET['id']));
        $get = _fetch($qry);
      if($get['von'] == $userid || $get['an'] == $userid)
      {
            $update = db("UPDATE ".$db['msg']."
                                        SET `readed` = 1
                                        WHERE id = ".intval($_GET['id']));

            $delete = show(_delete, array("id" => $get['id']));

        if($get['von'] == 0)
        {
          $answermsg = show(_msg_answer_msg, array("nick" => "MsgBot"));
          $answer = "&nbsp;";
        } else {
          $answermsg = show(_msg_answer_msg, array("nick" => autor($get['von'])));
            $answer = show(_msg_answer, array("id" => $get['id']));
          }

        if($get['sendnews'] == 1 || $get['sendnews'] == 2)
        {
          $sendnews = show(_msg_sendnews_user, array("id" => $get['id'],
                                                     "datum" => $get['datum']));
        } elseif($get['sendnews'] == 3) {
          $sendnews = show(_msg_sendnews_done, array("user" => autor($get['sendnewsuser'])));
        } else { $sendnews = ''; }

          $index = show($dir."/msg_show", array("answermsg" => $answermsg,
                                                "titel" => re($get['titel']),
                                                "nachricht" => bbcode($get['nachricht']),
                                                "answer" => $answer,
                                                "sendnews" => $sendnews,
                                                "delete" => $delete));
      }
      } elseif($do == "sendnewsdone") {
          $qry = db("SELECT * FROM ".$db['msg']."
                     WHERE id = '".intval($_GET['id'])."'");
          while($get = _fetch($qry))
          {
             $update = db("UPDATE ".$db['msg']."
                               SET `sendnews` = 3,
                               `sendnewsuser` = '".$userid."',
                               `readed`= 1
                               WHERE datum = '".intval($_GET['datum'])."'");

            $index = info(_send_news_done, "?action=msg&do=show&id=".$get['id']."");
          }
      } elseif($do == "showsended") {
          $qry = db("SELECT * FROM ".$db['msg']."
                     WHERE id = ".intval($_GET['id']));
          $get = _fetch($qry);

      if($get['von'] == $userid || $get['an'] == $userid)
      {
            $answermsg = show(_msg_sended_msg, array("nick" => autor($get['an'])));
            $answer = _back;

            $index = show($dir."/msg_show", array("answermsg" => $answermsg,
                                                "titel" => re($get['titel']),
                                                "nachricht" => bbcode($get['nachricht']),
                                                "answer" => $answer,
                                                "sendnews" => "",
                                                "delete" => ""));
      }
      } elseif($do == "answer") {
          $qry = db("SELECT * FROM ".$db['msg']."
                               WHERE id = ".intval($_GET['id']));
          $get = _fetch($qry);

      if($get['von'] == $userid || $get['an'] == $userid)
      {
        if(preg_match("#RE:#is",re($get['titel']))) $titel = re($get['titel']);
        else $titel = "RE: ".re($get['titel']);

            $index = show($dir."/answer", array("von" => $userid,
                                              "an" => $get['von'],
                                              "titel" => $titel,
                                              "headtitel" => _msg_titel_answer,
                                              "titelhead" => _titel,
                                              "nickhead" => _to,
                                              "value" => _button_value_msg,
                                              "bbcodehead" => _bbcode,
                                              "eintraghead" => _answer,
                                              "nick" => autor($get['von']),
                                              "zitat" => zitat(autor($get['von']),$get['nachricht'])));
      }
      } elseif($do == "pn") {
          if(!$chkMe)       $index = error(_error_have_to_be_logged);
          elseif($_GET['id'] == $userid) $index = error(_error_msg_self, 1);
          else {

      $titel = show(_msg_from_nick, array("nick" => data($userid,"nick")));

          $index = show($dir."/answer", array("von" => $userid,
                                              "an" => $_GET['id'],
                                              "titel" => $titel,
                                              "value" => _button_value_msg,
                                              "titelhead" => _titel,
                                              "headtitel" => _msg_titel,
                                              "nickhead" => _to,
                                              "bbcodehead" => _bbcode,
                                              "eintraghead" => _answer,
                                              "nick" => autor($_GET['id']),
                                              "zitat" => ""));
          }
      } elseif($do == "sendanswer") {
        if(empty($_POST['titel']))
          {
              $index = error(_empty_titel, 1);
          } elseif(empty($_POST['eintrag'])) {
              $index = error(_empty_eintrag, 1);
          } else {
              $qry = db("INSERT INTO ".$db['msg']."
                         SET `datum`      = '".time()."',
                                `von`        = '".((int)$_POST['von'])."',
                             `an`         = '".((int)$_POST['an'])."',
                             `titel`      = '".up($_POST['titel'])."',
                             `nachricht`  = '".up($_POST['eintrag'], 1)."',
                             `see`        = '1'");

              $qry = db("UPDATE ".$db['userstats']."
                           SET `writtenmsg` = writtenmsg+1
                           WHERE user = ".$userid);

        $index = info(_msg_answer_done, "?action=msg");
          }
      } elseif($do == "delete") {
      $qry = db("SELECT * FROM ".$db['msg']."
                 WHERE an = '".$userid."'
                 AND see_u = 0");
      while($get = _fetch($qry))
      {
        if(isset($_POST['pe'.$get['id']]))
        {
          if($get['see'] == 0)
          {
            $del = db("DELETE FROM ".$db['msg']."
                       WHERE id = ".intval($_POST['pe'.$get['id']]));
          } else {
                $del = db("UPDATE ".$db['msg']."
                                     SET `see_u` = 1
                                      WHERE id = ".intval($_POST['pe'.$get['id']]));
          }
        }
          }

        header("Location: ?action=msg");
      } elseif($do == "deletethis") {
      $qry = db("SELECT * FROM ".$db['msg']."
                 WHERE id = '".intval($_GET['id'])."'");
      $get = _fetch($qry);

      if($get['see'] == 0)
      {
        $del = db("DELETE FROM ".$db['msg']."
                   WHERE id = ".intval($_GET['id']));
      } else {
            $del = db("UPDATE ".$db['msg']."
                                SET `see_u` = 1
                              WHERE id = ".intval($_GET['id']));
      }

      $index = info(_msg_deleted, "?action=msg");
    } elseif($do == "deletesended") {
      $qry = db("SELECT * FROM ".$db['msg']."
                 WHERE von = '".$userid."'
                 AND see = 1");
      while($get = _fetch($qry))
      {
        if(isset($_POST['pa'.$get['id']]))
        {
          if($get['see_u'] == "1")
          {
            $del = db("DELETE FROM ".$db['msg']."
                       WHERE id = ".intval($_POST['pa'.$get['id']]));
          } else {
                $del = db("UPDATE ".$db['msg']."
                                     SET `see` = 0
                                      WHERE id = ".intval($_POST['pa'.$get['id']]));
          }
        }
          }

          header("Location: ?action=msg");
      } elseif($do == "new") {
          $qry = db("SELECT id,nick FROM ".$db['users']."
                 WHERE id != '".$userid."'
                               ORDER BY nick");
          while($get = _fetch($qry))
          {
              $users .= show(_to_users, array("id" => $get['id'],
                                        "selected" => "",
                                                                              "nick" => data("nick",$get['id'])));
          }

          $qry = db("SELECT id,user,buddy FROM ".$db['buddys']."
                               WHERE user = ".$userid."
                               ORDER BY user");
          while($get = _fetch($qry))
          {
              $buddys .= show(_to_buddys, array("id" => $get['buddy'],
                                          "selected" => "",
                                                                                  "nick" => data("nick",$get['buddy'])));
          }

          $index = show($dir."/new", array("von" => $userid,
                                                                           "an" => _to,
                                                                           "or" => _or,
                                                                           "buddys" => $buddys,
                                                                           "users" => $users,
                                                                           "value" => _button_value_msg,
                                                                           "titelhead" => _titel,
                                                                           "titel" => _msg_titel,
                                                                            "nickhead" => _nick,
                                                                            "bbcodehead" => _bbcode,
                                                                            "eintraghead" => _eintrag,
                                                                            "posttitel" => "",
                                                                            "error" => "",
                                                                            "posteintrag" => ""));
      } elseif($do == "send") {
        if(empty($_POST['titel']) || empty($_POST['eintrag']) || $_POST['buddys'] == "-" && $_POST['users'] == "-" || $_POST['buddys'] != "-"
      && $_POST['users'] != "-" || $_POST['users'] == $userid || $_POST['buddys'] == $userid)
          {
            if(empty($_POST['titel'])) $error = _empty_titel;
            elseif(empty($_POST['eintrag'])) $error = _empty_eintrag;
            elseif($_POST['buddys'] == "-" AND $_POST['users'] == "-") $error = _empty_to;
            elseif($_POST['buddys'] != "-" AND $_POST['users'] != "-") $error = _msg_to_just_1;
            elseif($_POST['buddys'] OR $_POST['users'] == $userid) $error = _msg_not_to_me;

            $error = show("errors/errortable", array("error" => $error));

            $qry = db("SELECT id FROM ".$db['users']."
                   WHERE id != '".$userid."'
                   ORDER BY nick");
            while($get = _fetch($qry))
            {
              if($get['id'] == $_POST['users']) $selected = 'selected="selected"';
                else $selected = "";

                $users .= show(_to_users, array("id" => $get['id'],
                                                                                "nick" => data("nick",$get['id']),
                                                                                "selected" => $selected));
            }

            $qry = db("SELECT id,user,buddy FROM ".$db['buddys']."
                                 WHERE user = ".$userid);
            while($get = _fetch($qry))
            {
                if($get['buddy'] == $_POST['buddys']) $selected = 'selected="selected"';
                else $selected = "";

                $buddys .= show(_to_buddys, array("id" => $get['buddy'],
                                                                                    "nick" => data("nick",$get['buddy']),
                                                                                    "selected" => $selected));
            }

            $index = show($dir."/new", array("von" => $userid,
                                                                             "an" => _to,
                                                                             "or" => _or,
                                                                             "posttitel" => re($_POST['titel']),
                                                                             "posteintrag" => re_bbcode($_POST['eintrag']),
                                                                             "postto" => $_POST['buddys']."".$_POST['users'],
                                                                             "buddys" => $buddys,
                                                                             "value" => _button_value_msg,
                                                                             "users" => $users,
                                                                             "titelhead" => _titel,
                                                                             "titel" => _msg_titel,
                                                                             "nickhead" => _nick,
                                                                             "bbcodehead" => _bbcode,
                                                                             "error" => $error,
                                                                             "eintraghead" => _eintrag));
          } else {
                if($_POST['buddys'] == "-") $to = $_POST['users'];
            else $to = $_POST['buddys'];

            $qry = db("INSERT INTO ".$db['msg']."
                           SET `datum`      = '".time()."',
                       `von`        = '".((int)$userid)."',
                       `an`         = '".((int)$to)."',
                       `titel`      = '".up($_POST['titel'])."',
                       `nachricht`  = '".up($_POST['eintrag'], 1)."',
                       `see`        = '1'");

            $qry = db("UPDATE ".$db['userstats']."
                                 SET `writtenmsg` = writtenmsg+1
                                 WHERE user = ".$userid);

            $index = info(_msg_answer_done, "?action=msg");
          }
      } else {
          $qry = db("SELECT * FROM ".$db['msg']."
                               WHERE an = ".$userid."
                 AND see_u = '0'
                               ORDER BY datum DESC");
        while($get = _fetch($qry))
          {
            if(_rows($qry))
              {
          if($get['von'] == 0) $absender = _msg_bot;
          else $absender = autor($get['von']);

                  $titel = show(_msg_in_title, array("titel" => re($get['titel'])));

                  $delete = _delete;
                  $date = date("d.m.Y H:i", $get['datum'])._uhr;
                  if($get['readed'] == 0 && $get['see_u'] == 0) $new = _newicon;
          else                                          $new = '';
              } else {
                  $titel = "-";
                  $absender = "-";
                  $date = "-";
                  $delete = "";
                  $new = "";
              }

        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $posteingang.= show($dir."/posteingang", array("titel" => $titel,
                                                                                                         "absender" => $absender,
                                                                                                         "datum" => $date,
                                                       "class" => $class,
                                                                                                         "delete" => $delete,
                                                                                                         "new" => $new,
                                                                                                         "id" => $get['id']));
            }

          $qry = db("SELECT * FROM ".$db['msg']."
                               WHERE von = ".$userid."
                               AND see = 1
                               ORDER BY datum DESC");

        while($get = _fetch($qry))
          {
              $titel = show(_msg_out_title, array("titel" => re($get['titel'])));
              $delete = _msg_delete_sended;
              $date = date("d.m.Y H:i", $get['datum'])._uhr;


            if($get['readed'] == "0") $readed = _noicon;
              else $readed = _yesicon;

        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $postausgang.= show($dir."/postausgang", array("titel" => $titel,
                                                                                                         "empfaenger" => autor($get['an']),
                                                                                                         "datum" => $date,
                                                       "class" => $class,
                                                                                                         "readed" => $readed,
                                                                                                         "delete" => $delete,
                                                                                                         "id" => $get['id']));
            }

          $msghead = show(_msghead, array("nick" => autor($userid)));

          $index = show($dir."/msg", array("msghead" => $msghead,
                                                                              "posteingang" => _posteingang,
                                                                              "postausgang" => _postausgang,
                                                                              "titel" => _msg_title,
                                        "del" => _msg_del,
                                                                              "absender" => _msg_absender,
                                                                              "legende" => _legende,
                                                                              "legendemsg" => _legende_msg,
                                                                              "legendereaded" => _legende_readed,
                                                                              "empfaenger" => _msg_empfaenger,
                                                                              "datum" => _datum,
                                                                              "new" => _msg_new,
                                        "newglobal" => $newglobal,
                                                                              "newicon" => _newicon,
                                                                              "yesno" => _yesno,
                                                                              "deleteicon" => _deleteicon_blank,
                                                                              "showincoming" => $posteingang,
                                                                              "showsended" => $postausgang));
      }
    }
}