<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;

    $where = $where.': '._config_useradd_head;
      $dropdown_age = show(_dropdown_date, array("day" => dropdown("day",$bdayday,1),
                                                            "month" => dropdown("month",$bdaymonth,1),
                                                   "year" => dropdown("year",$bdayyear,1)));

        $gmaps = show('membermap/geocoder', array('form' => 'adduser'));


        $qrysq = db("SELECT id,name FROM ".$db['squads']."
                     ORDER BY pos");
        while($getsq = _fetch($qrysq))
        {
          $qrypos = db("SELECT id,position FROM ".$db['pos']."
                        ORDER BY pid");
          $posi = "";
          while($getpos = _fetch($qrypos))
          {
            $check = db("SELECT * FROM ".$db['userpos']."
                         WHERE posi = '".$getpos['id']."'
                         AND squad = '".$getsq['id']."'
                         AND user = '".intval($_GET['edit'])."'");
            if(_rows($check)) $sel = 'selected="selected"';
            else $sel = "";

            $posi .= show(_select_field_posis, array("value" => $getpos['id'],
                                                     "sel" => $sel,
                                                     "what" => re($getpos['position'])));
          }

          $qrysquser = db("SELECT squad FROM ".$db['squaduser']."
                           WHERE user = '".intval($_GET['edit'])."'
                           AND squad = '".$getsq['id']."'");

          if(_rows($qrysquser))$check = 'checked="checked"';
          else $check = "";

          $esquads .= show(_checkfield_squads, array("id" => $getsq['id'],
                                                     "check" => $check,
                                                     "eposi" => $posi,
                                                     "noposi" => _user_noposi,
                                                     "squad" => re($getsq['name'])));
        }

      $show = show($dir."/register", array("registerhead" => _useradd_head,
                                                                                     "pname" => _loginname,
                                                                                     "pnick" => _nick,
                                                                                     "pemail" => _email,
                                                                                     "pbild" => _profil_ppic,
                                                                                     "abild" => _profil_avatar,
                                                                                     "ppwd" => _pwd,
                                                                                     "squadhead" => _admin_user_squadhead,
                                                                                     "squad" => _member_admin_squad,
                                                                                     "posi" => _profil_position,
                                                                                     "esquad" => $esquads,
                                                                                     "about" => _useradd_about,
                                                                                     "level_info" => _level_info,
                                                                                     "rechte" => _config_positions_rights,
                                                                                     "getpermissions" => getPermissions(),
                                                                                     "getboardpermissions" => getBoardPermissions(),
                                                                                     "forenrechte" => _config_positions_boardrights,
                                                                                     "preal" => _profil_real,
                                                                                     "psex" => _profil_sex,
                                                                                     "sex" => _pedit_male,
                                                                                     "pbday" => _profil_bday,
                                                                                     "dropdown_age" => $dropdown_age,
                                                                                     "pcity" => _profil_city,
                                                                                     "pcountry" => _profil_country,
                                                                                     "country" => show_countrys($get['country']),
                                                                                     "gmaps" => $gmaps,
                                                                                     "level" => _admin_user_level,
                                                                                     "ruser" => _status_user,
                                                                                     "trial" => _status_trial,
                                                                                     "alvl" => "",
                                           "member" => _status_member,
                                                                                     "admin" => _status_admin,
                                                                                     "banned" => _admin_level_banned,
                                                                                     "value" => _button_value_reg));
      if($do == "add")
      {
        $check_user = db_stmt("SELECT id FROM ".$db['users']." WHERE `user`= ?",
                  array('s', up($_POST['user'])),true);

        $check_nick = db_stmt("SELECT id FROM ".$db['users']." WHERE `nick`= ?",
                  array('s', up($_POST['nick'])),true);

        $check_email = db_stmt("SELECT id FROM ".$db['users']." WHERE `email`= ?",
                  array('s', up($_POST['email'])),true);

        if(empty($_POST['user']))
        {
            $show = error(_empty_user, 1);
        } elseif(empty($_POST['nick'])) {
            $show = error(_empty_nick, 1);
        } elseif(empty($_POST['email'])) {
            $show = error(_empty_email, 1);
        } elseif(!check_email($_POST['email'])) {
            $show = error(_error_invalid_email, 1);
        } elseif($check_user) {
            $show = error(_error_user_exists, 1);
        } elseif($check_nick) {
            $show = error(_error_nick_exists, 1);
        } elseif($check_email) {
            $show = error(_error_email_exists, 1);
        } else {

        if(empty($_POST['pwd']))  $mkpwd = mkpwd();
        else                      $mkpwd = $_POST['pwd'];
        $pwd = md5($mkpwd);

        $bday = ($_POST['t'] && $_POST['m'] && $_POST['j'] ? cal($_POST['t']).".".cal($_POST['m']).".".$_POST['j'] : 0);
        $qry = db("INSERT INTO ".$db['users']."
                             SET `user`     = '".up($_POST['user'])."',
                                 `nick`     = '".up($_POST['nick'])."',
                                 `email`    = '".up($_POST['email'])."',
                                 `pwd`      = '".up($pwd)."',
                                 `rlname`   = '".up($_POST['rlname'])."',
                                 `sex`      = '".intval($_POST['sex'])."',
                                 `bday`     = '".(!$bday ? 0 : strtotime($bday))."',
                                 `city`     = '".up($_POST['city'])."',
                                 `country`  = '".up($_POST['land'])."',
                                 `regdatum` = '".time()."',
                                 `level`    = '".intval($_POST['level'])."',
                                 `time`     = '".time()."',
                                 `gmaps_koord`  = '".up($_POST['gmaps_koord'])."',
                                 `status`   = '1'");

      $insert_id = _insert_id();
      setIpcheck("createuser(".$_SESSION['id']."_".$insert_id.")");

    // permissions
      if(!empty($_POST['perm']))
      {
        foreach($_POST['perm'] AS $v => $k) $p .= "`".substr($v, 2)."` = '".intval($k)."',";
                         if(!empty($p)) $p = ', '.substr($p, 0, strlen($p) - 1);

        db("INSERT INTO ".$db['permissions']." SET `user` = '".intval($insert_id)."'".$p);
      }
    ////////////////////

    // internal boardpermissions
      if(!empty($_POST['board']))
      {
          foreach($_POST['board'] AS $v)
            db("INSERT INTO ".$db['f_access']." SET `user` = '".intval($insert_id)."', `forum` = '".$v."'");
      }
    ////////////////////

      $sq = db("SELECT * FROM ".$db['squads']."");
      while($getsq = _fetch($sq))
      {
        if(isset($_POST['squad'.$getsq['id']]))
        {
          $qry = db("INSERT INTO ".$db['squaduser']."
                     SET `user`  = '".intval($insert_id)."',
                         `squad` = '".intval($_POST['squad'.$getsq['id']])."'");
        }

        if(isset($_POST['squad'.$getsq['id']]))
        {
          $qry = db("INSERT INTO ".$db['userpos']."
                     SET `user`   = '".intval($insert_id)."',
                         `posi`   = '".intval($_POST['sqpos'.$getsq['id']])."',
                         `squad`  = '".intval($getsq['id'])."'");
        }
      }

    //Profilfoto
    if(!empty($_FILES['file']))
    {
        $tmpname = $_FILES['file']['tmp_name'];
        $name = $_FILES['file']['name'];
        $type = $_FILES['file']['type'];
        $size = $_FILES['file']['size'];

        $endung = explode(".", $_FILES['file']['name']);
        $endung = strtolower($endung[count($endung)-1]);

        if($tmpname)
        {
            $imageinfo = getimagesize($tmpname);
            foreach($picformat as $tmpendung)
            {
                if(file_exists(basePath."/inc/images/uploads/userpics/".$insert_id.".".$tmpendung)) {
                    @unlink(basePath."/inc/images/uploads/userpics/".$insert_id.".".$tmpendung);
                }
            }
            copy($tmpname, basePath."/inc/images/uploads/userpics/".$insert_id.".".strtolower($endung)."");
            @unlink($_FILES['file']['tmp_name']);
        }
    }

    //Avatar
    if(!empty($_FILES['file_avatar']))
    {
        $tmpname = $_FILES['file_avatar']['tmp_name'];
        $name = $_FILES['file_avatar']['name'];
        $type = $_FILES['file_avatar']['type'];
        $size = $_FILES['file_avatar']['size'];

        $endung = explode(".", $_FILES['file_avatar']['name']);
        $endung = strtolower($endung[count($endung)-1]);

        if($tmpname)
        {
            $imageinfo = getimagesize($tmpname);
            foreach($picformat as $tmpendung)
            {
                if(file_exists(basePath."/inc/images/uploads/useravatare/".$insert_id.".".$tmpendung)) {
                @unlink(basePath."/inc/images/uploads/useravatare/".$insert_id.".".$tmpendung);
                }
            }

            copy($tmpname, basePath."/inc/images/uploads/useravatare/".$insert_id.".".strtolower($endung)."");
            @unlink($_FILES['file_avatar']['tmp_name']);
        }
    }

      $qry = db("INSERT INTO ".$db['userstats']."
                       SET `user`       = '".intval($insert_id)."',
                   `lastvisit`    = '".time()."'");

      $show = info(_uderadd_info, "../admin/");

      }
    }