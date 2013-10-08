<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       contentmenu
// Rechte:    permission('newsletter')
///////////////////////////////
if(_adminMenu != 'true') exit;

    $where = $where.': '._nletter;
   if(!permission("newsletter"))
   {
     $show = error(_error_wrong_permissions, 1);
   } else {
        if($_GET['do'] == 'preview')
    {
      $show = show($dir."/nletter_prev", array("head" => _nletter_prev_head,
                                               "text" => bbcode_nletter($_POST['eintrag'])));
      echo '<table class="mainContent" cellspacing="1">'.$show.'</table>';
      exit;
    } elseif($_GET['do'] == "send") {
        if(empty($_POST['eintrag']) || $_POST['to'] == "-")
          {
            if(empty($_POST['eintrag'])) $error = _empty_eintrag;
            elseif($_POST['to'] == "-") $error = _empty_to;

            $error = show("errors/errortable", array("error" => $error));

            $qry = db("SELECT id,name FROM ".$db['squads']."
                       ORDER BY name");
            while($get = _fetch($qry))
            {
          if($_POST['to'] == $get['id']) $selsq = "selected=\"selected\"";
          else $selsq = "";

          $squads .= show(_to_squads, array("id" => $get['id'],
                                            "sel" => $selsq,
                                            "name" => re($get['name'])));
        }

        if($_POST['to'] == "reg") $selr = "selected=\"selected\"";
        elseif($_POST['to'] == "member") $selm = "selected=\"selected\"";
        elseif($_POST['to'] == "leader") $sell = "selected=\"selected\"";

            $show = show($dir."/nletter", array("von" => $userid,
                                                "an" => _to,
                                                "who" => _msg_global_who,
                                                "reg" => _msg_global_reg,
                                                "selr" => $selr,
                                                "selm" => $selm,
                                                "sell" => $sell,
                                                "value" => _button_value_nletter,
                                                "lang" => $language,
                                                "preview" => _preview,
                                                "allmembers" => _msg_global_all,
                                                "all_leader" => _msg_all_leader,
                                                "leader" => _msg_leader,
                                                "squad" => _msg_global_squad,
                                                "squads" => $squads,
                                                "posteintrag" => re_bbcode($_POST['eintrag']),
                                                "titel" => _nletter_head,
                                                "nickhead" => _nick,
                                                "bbcodehead" => _bbcode,
                                                "error" => $error,
                                                "eintraghead" => _eintrag));
          } else {
        if($_POST['to'] == "reg")
        {
                  $message = show(bbcode_email(settings('eml_nletter')), array("text" => bbcode_nletter($_POST['eintrag'])));
                  $subject = re(settings('eml_nletter_subj'));

          $qry = db("SELECT email FROM ".$db['users']."
                     WHERE nletter = 1");
          while($get = _fetch($qry))
          {
            sendMail($get['email'],$subject,$message);
          }

              $qry = db("UPDATE ".$db['userstats']."
                         SET `writtenmsg` = writtenmsg+1
                         WHERE user = ".intval($userid));

              $show = info(_msg_reg_answer_done, "?admin=nletter");

        } elseif($_POST['to'] == "member") {
          $message = show(bbcode_email(settings('eml_nletter')), array("text" => bbcode_nletter($_POST['eintrag'])));
                  $subject = re(settings('eml_nletter_subj'));

          $qry = db("SELECT email FROM ".$db['users']."
                     WHERE level >= 2");
          while($get = _fetch($qry))
          {
            sendMail($get['email'],$subject,$message);
          }

              $qry = db("UPDATE ".$db['userstats']."
                        SET `writtenmsg` = writtenmsg+1
                        WHERE user = ".intval($userid));

              $show = info(_msg_member_answer_done, "?admin=nletter");
        } elseif($_POST['to'] == "leader") {
          $message = show(bbcode_email(settings('eml_nletter')), array("text" => bbcode_nletter($_POST['eintrag'])));
                  $subject = re(settings('eml_nletter_subj'));

          $qry = db("SELECT s2.email	FROM ".$db['squaduser']." AS s1
                     LEFT JOIN ".$db['users']." AS s2 ON s2.id=s1.user
                     LEFT JOIN ".$db['userpos']." AS s3 ON s3.squad=s1.squad AND s3.user=s1.user
                     LEFT JOIN ".$db['pos']." AS s4 ON s4.id=s3.posi
                     WHERE s4.nletter = '1'");

          while($get = _fetch($qry))
          {
            sendMail($get['email'],$subject,$message);
          }

              $qry = db("UPDATE ".$db['userstats']."
                          SET `writtenmsg` = writtenmsg+1
                          WHERE user = ".intval($userid));

              $show = info(_msg_member_answer_done, "?admin=nletter");
        } else {
          $message = show(bbcode_email(settings('eml_nletter')), array("text" => bbcode_nletter($_POST['eintrag'])));
                  $subject = re(settings('eml_nletter_subj'));

          $qry = db("SELECT s2.email FROM ".$db['squaduser']." AS s1
                     LEFT JOIN ".$db['users']." AS s2
                     ON s1.user = s2.id
                     WHERE s1.squad = '".$_POST['to']."'");
          while($get = _fetch($qry))
          {
            sendMail($get['email'],$subject,$message);
          }

              $qry = db("UPDATE ".$db['userstats']."
                          SET `writtenmsg` = writtenmsg+1
                          WHERE user = ".intval($userid));

              $show = info(_msg_squad_answer_done, "?admin=nletter");
        }
      }
    } else {
      $qry = db("SELECT id,name FROM ".$db['squads']."
         ORDER BY name");
          while($get = _fetch($qry))
          {
              $squads .= show(_to_squads, array("id" => $get['id'],
                                                "sel" => "",
                                                   "name" => re($get['name'])));
          }

      $show = show($dir."/nletter", array("von" => $userid,
                                             "an" => _to,
                                            "selr" => "",
                                          "selm" => "",
                                          "who" => _msg_global_who,
                                            "squads" => $squads,
                                          "preview" => _preview,
                                          "reg" => _msg_global_reg,
                                          "allmembers" => _msg_global_all,
                                          "all_leader" => _msg_all_leader,
                                          "leader" => _msg_leader,
                                          "squad" => _msg_global_squad,
                                               "titel" => _nletter_head,
                                          "value" => _button_value_nletter,
                                          "lang" => $language,
                                          "nickhead" => _nick,
                                          "bbcodehead" => _bbcode,
                                          "eintraghead" => _eintrag,
                                          "error" => "",
                                          "posteintrag" => ""));
      }
    }
?>