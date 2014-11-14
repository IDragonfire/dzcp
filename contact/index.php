<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$where = _site_contact;
$title = $pagetitle." - ".$where."";
$dir = "contact";
## SECTIONS ##
switch ($action):
    default:
        $index = show($dir."/contact", array("head" => _site_contact,
                                             "nachricht" => _contact_nachricht,
                                             "nick" => _nick,
                                             "what" => "contact",
                                             "security" => _register_confirm,
                                             "joinus" => "",
                                             "value" => _button_value_send,
                                             "why" => "",
                                             "pflicht" => _contact_pflichtfeld,
                                             "email" => _email,
                                             "skype" => _skypeid,
                                             "steam" => _steamid,
                                             "icq" => _icq));
    break;
    case 'fightus';
        $qry = db("SELECT id,name,game FROM ".$db['squads']."
                   WHERE status = 1
                   AND team_fightus = 1
                   ORDER BY name");

        $squads = '';
        while($get = _fetch($qry))
        {
            $squads .= show(_select_field_fightus, array("id" => $get['id'],
                                                         "squad" => re($get['name']),
                                                         "game" => re($get['game'])));
        }

        if (!_rows($qry))
        {
            $squads = show(_select_field_fightus, array("id" => "0",
            "squad" => _contact_joinus_no_squad_aviable,
            "game" => "?"));
        }

        $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())),
                                                    "month" => dropdown("month",date("m",time())),
                                                    "year" => dropdown("year",date("Y",time()))));

        $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",time())),
                                                    "minute" => dropdown("minute",date("i",time())),
                                                    "uhr" => _uhr));

        $index = show($dir."/fightus", array("head" => _site_fightus,
                                             "nachricht" => _contact_fightus,
                                             "partner" => _contact_fightus_partner,
                                             "clandaten" => _contact_fightus_clandata,
                                             "nick" => _nick,
                                             "datum" => $dropdown_date,
                                             "squad" => _fightus_squad,
                                             "squads" => $squads,
                                             "zeit" => $dropdown_time,
                                             "security" => _register_confirm,
                                             "clan" => _contact_fightus_clanname,
                                             "date" => _datum,
                                             "value" => _button_value_send,
                                             "year" => date("Y", time()),
                                             "maps" => _fightus_maps,
                                             "vs" => _cw_xonx,
                                             "game" => _game,
                                             "hp" => _hp,
                                             "pflicht" => _contact_pflichtfeld,
                                             "email" => _email,
                                             "skype" => _skypeid,
                                             "steam" => _steamid,
                                             "icq" => _icq));
    break;
    case 'joinus';
        $qrysquads = db("SELECT id,name,game FROM ".$db['squads']."
                        WHERE status = 1 AND team_joinus = 1
                        ORDER BY name");

        $squads = '';
        while($getsquads = _fetch($qrysquads))
        {
            $squads .= show(_select_field_fightus, array("id" => $getsquads['id'],
                                                         "squad" => re($getsquads['name']),
                                                         "game" => re($getsquads['game'])));
        }

        if (!_rows($qrysquads))
        {
            $squads = show(_select_field_fightus, array("id" => "0",
                                                 "squad" => _contact_joinus_no_squad_aviable,
                                                 "game" => "?"));
        }

        $joinus = show($dir."/joinus", array("age" => _age,
                                             "squad" => _squad,
                                             "squads" => $squads,
                                             "years" => _years));

        $index = show($dir."/contact", array("head" => _site_joinus,
                                             "nachricht" => _contact_joinus,
                                             "nick" => _nick,
                                             "value" => _button_value_send,
                                             "joinus" => $joinus,
                                             "what" => "joinus",
                                             "security" => _register_confirm,
                                             "why" => _contact_joinus_why,
                                             "pflicht" => _contact_pflichtfeld,
                                             "email" => _email,
                                             "skype" => _skypeid,
                                             "steam" => _steamid,
                                             "icq" => _icq));
    break;
case 'do';
    if($_GET['what'] == "contact")
    {
        if(checkme() == "unlogged" && !$securimage->check($_POST['secure']))
            $index = error((captcha_mathematic ? _error_invalid_regcode_mathematic : _error_invalid_regcode),1);
        elseif(empty($_POST['text']))
            $index = error(_error_empty_nachricht, 1);
        elseif(empty($_POST['email']))
            $index = error(_empty_email, 1);
        elseif(!check_email($_POST['email']))
            $index = error(_error_invalid_email, 1);
        elseif(empty($_POST['nick']))
            $index = error(_empty_nick, 1);
        else {
            $icq = preg_replace("=-=Uis","",$_POST['icq']);
            $email = show(_email_mailto, array("email" => $_POST['email']));
            $text = show(_contact_text, array("icq" => $icq,
                                              "skype" => $_POST['skype'],
                                              "steam" => $_POST['steam'],
                                              "email" => $email,
                                              "text" => $_POST['text'],
                                              "nick" => $_POST['nick']));

            $qry = db("SELECT s1.id FROM ".$db['users']." AS s1
                       LEFT JOIN ".$db['permissions']." AS s2
                       ON s1.id = s2.user
                       WHERE s2.contact = '1' AND s1.`user` != '0 GROUP BY s1.`id`'");

            $sqlAnd = '';
            while($get = _fetch($qry))
            {
                $sqlAnd .= " AND s2.`user` != '".intval($get['id'])."'";
                $qrys = db("INSERT INTO ".$db['msg']."
                            SET `datum`     = '".time()."',
                                `von`       = '0',
                                `an`        = '".intval($get['id'])."',
                                `titel`     = '"._contact_title."',
                                `nachricht` = '".up($text)."'");
            }

            $qry = db("SELECT s2.`user` FROM ".$db['permissions']." AS s1
                       LEFT JOIN ".$db['userpos']." AS s2 ON s1.`pos` = s2.`posi`
                       WHERE s1.`contact` = '1' AND s2.`posi` != '0'".$sqlAnd." GROUP BY s2.`user`");

            while($get = _fetch($qry))
            {
                $qrys = db("INSERT INTO ".$db['msg']."
                            SET `datum`     = '".time()."',
                                `von`       = '0',
                                `an`        = '".intval($get['user'])."',
                                `titel`     = '"._contact_title."',
                                `nachricht` = '".up($text)."'");
            }

            $index = info(_contact_sended, "../news/");
        }
    } elseif($_GET['what'] == "joinus") {
    if(checkme() == "unlogged" && !$securimage->check($_POST['secure']))
        $index = error((captcha_mathematic ? _error_invalid_regcode_mathematic : _error_invalid_regcode),1);
    elseif(empty($_POST['text']))
      $index = error(_error_empty_nachricht, 1);
    elseif(empty($_POST['age']))
      $index = error(_error_empty_age, 1);
    elseif(empty($_POST['email']))
      $index = error(_empty_email, 1);
    elseif(!check_email($_POST['email']))
      $index = error(_error_invalid_email, 1);
    elseif(empty($_POST['nick']))
      $index = error(_empty_nick, 1);
    else {
      if ($_POST['squad'] != 0)
      {
          $qrysquads = _fetch(db("SELECT name FROM ".$db['squads']."
                    WHERE id = ".$_POST['squad']));
      }
      else $qrysquads['name'] = _contact_joinus_no_squad_aviable;

      $icq = preg_replace("=-=Uis","",$_POST['icq']);
      $email = show(_email_mailto, array("email" => $_POST['email']));
      $text = show(_contact_text_joinus, array("icq" => $icq,
                                               "skype" => $_POST['skype'],
                                               "steam" => $_POST['steam'],
                                               "email" => $email,
                                               "age" => $_POST['age'],
                                               "text" => $_POST['text'],
                                               "squad" => re($qrysquads['name']),
                                               "nick" => $_POST['nick']));

      $qry = db("SELECT s1.id FROM ".$db['users']." AS s1
                 LEFT JOIN ".$db['permissions']." AS s2  ON s1.id = s2.user
                 WHERE s2.joinus = '1' AND s1.`user` != '0' GROUP BY s1.`id`");
      $sqlAnd = '';
      while($get = _fetch($qry))
      {
        $sqlAnd .= " AND s2.`user` != '".intval($get['id'])."'";

        $qrys = db("INSERT INTO ".$db['msg']."
                    SET `datum`     = '".time()."',
                        `von`       = '0',
                        `an`        = '".intval($get['id'])."',
                        `titel`     = '"._contact_title_joinus."',
                        `nachricht` = '".up($text)."'");
      }

      $qry = db("SELECT s2.`user` FROM ".$db['permissions']." AS s1
                 LEFT JOIN ".$db['userpos']." AS s2 ON s1.`pos` = s2.`posi`
                 WHERE s1.`joinus` = '1' AND s2.`posi` != '0'".$sqlAnd." GROUP BY s2.`user`");
      while($get = _fetch($qry))
      {
        $qrys = db("INSERT INTO ".$db['msg']."
                    SET `datum`     = '".time()."',
                        `von`       = '0',
                        `an`        = '".intval($get['user'])."',
                        `titel`     = '"._contact_title_joinus."',
                        `nachricht` = '".up($text)."'");
      }

      $index = info(_contact_joinus_sended, "../news/");
    }
  } elseif($_GET['what'] == "fightus") {
    if(checkme() == "unlogged" && !$securimage->check($_POST['secure']))
        $index = error((captcha_mathematic ? _error_invalid_regcode_mathematic : _error_invalid_regcode),1);
    elseif(empty($_POST['clan']))
      $index = error(_error_empty_clanname, 1);
    elseif(empty($_POST['email']))
      $index = error(_empty_email, 1);
    elseif(empty($_POST['maps']))
      $index = error(_empty_fightus_map, 1);
    elseif(!check_email($_POST['email']))
      $index = error(_error_invalid_email, 1);
    elseif(empty($_POST['nick']))
      $index = error(_empty_nick, 1);
    else {
      $icq = preg_replace("=-=Uis","",$_POST['icq']);
      $email = show(_email_mailto, array("email" => $_POST['email']));
      $hp = show(_contact_hp, array("hp" => links($_POST['hp'])));

      if(!empty($_POST['t']) && $_POST['j'] == date("Y", time()))
      {
        $date = $_POST['t'].".".$_POST['m'].".".$_POST['j']."&nbsp;".$_POST['h'].":".$_POST['min']._uhr;
      }

      $qrysq = db("SELECT name FROM ".$db['squads']."
                   WHERE id = '".intval($_POST['squad'])."'");
      $getsq = _fetch($qrysq);

      $msg = show(_contact_text_fightus, array("icq" => $icq,
                                               "skype" => $_POST['skype'],
                                               "steam" => $_POST['steam'],
                                               "email" => $email,
                                               "text" => $_POST['text'],
                                               "clan" => $_POST['clan'],
                                               "hp" => $hp,
                                               "squad" => $getsq['name'],
                                               "game" => $_POST['game'],
                                               "us" => $_POST['us'],
                                               "to" => $_POST['to'],
                                               "date" => $date,
                                               "map" => $_POST['maps'],
                                               "nick" => $_POST['nick']));

      if($chkMe != 4) $add = " AND s2.squad = '".intval($_POST['squad'])."'";
      $who = db("SELECT s1.user FROM ".$db['permissions']." AS s1
                 LEFT JOIN ".$db['squaduser']." AS s2
                 ON s1.user = s2.user
                 WHERE s1.receivecws = '1' AND s1.`user` != '0'
                 ".$add." GROUP BY s1.`user`");
      $sqlAnd = '';
      while($get = _fetch($who))
      {
        $sqlAnd .= " AND s2.`user` != '".intval($get['user'])."'";
        $qry = db("INSERT INTO ".$db['msg']."
                   SET `datum`      = '".time()."',
                       `von`        = '0',
                       `an`         = '".intval($get['user'])."',
                       `titel`      = '"._contact_title_fightus."',
                       `nachricht`  = '".up($msg)."'");

      }

      $qry = db("SELECT s3.`user` FROM ".$db['permissions']." AS s1
                 LEFT JOIN ".$db['userpos']." AS s2 ON s1.`pos` = s2.`posi`
                 LEFT JOIN ".$db['squaduser']." AS s3 ON s2.user = s3.user
                 WHERE s1.`receivecws` = '1' AND s2.`posi` != '0'".$sqlAnd.$add." GROUP BY s2.`user`");
      while($get = _fetch($qry))
      {
        $qry = db("INSERT INTO ".$db['msg']."
                   SET `datum`      = '".time()."',
                       `von`        = '0',
                       `an`         = '".intval($get['user'])."',
                       `titel`      = '"._contact_title_fightus."',
                       `nachricht`  = '".up($msg)."'");
      }
      $index = info(_contact_fightus_sended, "../news/");
    }
  }
break;
endswitch;

## INDEX OUTPUT ##
page($index, $title, $where);
