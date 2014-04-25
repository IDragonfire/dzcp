<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_UserMenu')) {
  if(!permission("editusers"))
  {
    $index = error(_error_wrong_permissions, 1);
  } elseif($_GET['edit'] == $userid) {
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

      if(_rows($qrysquser)) $check = 'checked="checked"';
      else $check = "";

      $esquads .= show(_checkfield_squads, array("id" => $getsq['id'],
                                                 "check" => $check,
                                                 "eposi" => $posi,
                                                 "noposi" => _user_noposi,
                                                 "squad" => re($getsq['name'])));
    }

    $index = show($dir."/admin_self", array("squadhead" => _admin_user_squadhead,
                                            "showpos" => getrank($_GET['edit']),
                                            "esquad" => $esquads,
                                            "nothing" => _nothing,
                                            "value" => _button_value_edit,
                                            "eposi" => $posi,
                                            "squad" => _member_admin_squad,
                                            "posi" => _profil_position,
                                            "deletesq" => $deletesq));
  } elseif(isset($_GET['edit']) && data("level",intval($_GET['edit'])) == 4 && !rootAdmin($userid)) {
    $index = error(_error_edit_admin, 1);
  } else {
    if($do == "identy")
    {
      if(data("level",intval($_GET['id'])) == 4 && !rootAdmin($userid))
      {
        $index = error(_identy_admin, 1);
      } else {
        $msg = show(_admin_user_get_identy, array("nick" => autor($_GET['id'])));
        $index = info($msg, "?action=user&amp;id=".$_GET['id']."");

        cookie::put('id', '');
        cookie::put('pkey', '');
        @session_regenerate_id();

        $_SESSION['id'] = $_GET['id'];
        $_SESSION['pwd'] = data("pwd",intval($_GET['id']));
        $_SESSION['ip'] = $userip;

        $qry = db("UPDATE ".$db['users']."
                                 SET `online` = '1',
                       `sessid` = '".session_id()."',
                       `ip`     = '".$userip."'
                                WHERE id = ".intval($_GET['id']));

        setIpcheck("ident(".$userid."_".intval($_GET['id']).")");
      }
    } elseif($do == "update") {
      if($_POST)
      {
    // permissions
          db("DELETE FROM ".$db['permissions']." WHERE `user` = '".intval($_GET['user'])."'");
          if(!empty($_POST['perm']))
          {
            foreach($_POST['perm'] AS $v => $k) $p .= "`".substr($v, 2)."` = '".intval($k)."',";
                                 if(!empty($p)) $p = ', '.substr($p, 0, strlen($p) - 1);

            db("INSERT INTO ".$db['permissions']." SET `user` = '".intval($_GET['user'])."'".$p);
          }
    ////////////////////

    // internal boardpermissions
          db("DELETE FROM ".$db['f_access']." WHERE `user` = '".intval($_GET['user'])."'");
          if(!empty($_POST['board']))
          {
            foreach($_POST['board'] AS $v)
              db("INSERT INTO ".$db['f_access']." SET `user` = '".intval($_GET['user'])."', `forum` = '".$v."'");
          }
    ////////////////////

        $del = db("DELETE FROM ".$db['squaduser']."
                   WHERE user = '".intval($_GET['user'])."'");
        $del = db("DELETE FROM ".$db['userpos']."
                   WHERE user = '".intval($_GET['user'])."'");

        $sq = db("SELECT * FROM ".$db['squads']."");
        while($getsq = _fetch($sq))
        {
          if(isset($_POST['squad'.$getsq['id']]))
          {
            $qry = db("INSERT INTO ".$db['squaduser']."
                       SET `user`   = '".((int)$_GET['user'])."',
                           `squad`  = '".((int)$_POST['squad'.$getsq['id']])."'");
          }

          if(isset($_POST['squad'.$getsq['id']]))
          {
            $qry = db("INSERT INTO ".$db['userpos']."
                       SET `user`   = '".((int)$_GET['user'])."',
                           `posi`   = '".((int)$_POST['sqpos'.$getsq['id']])."',
                           `squad`  = '".((int)$getsq['id'])."'");
          }
        }

        if($_POST['passwd']) $newpwd = "`pwd` = '".md5($_POST['passwd'])."',";

        $update_level = $_POST['level'] == 'banned' ? 0 : $_POST['level'];
        $update_banned = $_POST['level'] == 'banned' ? 1 : 0;
        $qry = db("UPDATE ".$db['users']."
                   SET ".$newpwd."
                       `nick`   = '".up($_POST['nick'])."',
                       `email`  = '".$_POST['email']."',
                       `user`   = '".up($_POST['loginname'])."',
                       `listck` = '".((int)$_POST['listck'])."',
                       `level`  = '".((int)$update_level)."',
                       `banned`  = '".((int)$update_banned)."'
                   WHERE id = '".intval($_GET['user'])."'");

        setIpcheck("upduser(".$userid."_".intval($_GET['user']).")");
      }
      $index = info(_admin_user_edited, "?action=userlist");
    } elseif($do == "updateme") {
      $del = db("DELETE FROM ".$db['squaduser']."
                 WHERE user = '".$userid."'");
      $del = db("DELETE FROM ".$db['userpos']."
                 WHERE user = '".$userid."'");

      $sq = db("SELECT * FROM ".$db['squads']."");
      while($getsq = _fetch($sq))
      {
        if(isset($_POST['squad'.$getsq['id']]))
        {
          $qry = db("INSERT INTO ".$db['squaduser']."
                     SET `user`  = '".((int)$userid)."',
                         `squad` = '".((int)$_POST['squad'.$getsq['id']])."'");
        }

        if(isset($_POST['squad'.$getsq['id']]))
        {
          $qry = db("INSERT INTO ".$db['userpos']."
                     SET `user`   = '".((int)$userid)."',
                         `posi`   = '".((int)$_POST['sqpos'.$getsq['id']])."',
                         `squad`  = '".((int)$getsq['id'])."'");
        }
      }

      $index = info(_admin_user_edited, "?action=user&amp;id=".$userid."");
    } elseif($do == "delete") {
      $index = show(_user_delete_verify, array("user" => autor($_GET['id']),
                                               "id" => $_GET['id']));

      if($_GET['verify'] == "yes")
      {
        if(data("level",intval($_GET['id'])) == 4 || data("level",intval($_GET['id'])) == 3)
        {
          $index = error(_user_cant_delete_admin, 2);
        } else {
          setIpcheck("deluser(".$userid."_".intval($_GET['id']).")");

          $upd = db("UPDATE ".$db['f_posts']."
                     SET `reg` = 0
                     WHERE reg = ".intval($_GET['id'])."");

          $upd = db("UPDATE ".$db['f_threads']."
                     SET `t_reg` = 0
                     WHERE t_reg = ".intval($_GET['id'])."");

          $upd = db("UPDATE ".$db['gb']."
                     SET `reg` = 0
                     WHERE reg = ".intval($_GET['id'])."");

          $upd = db("UPDATE ".$db['newscomments']."
                     SET `reg` = 0
                     WHERE reg = ".intval($_GET['id'])."");

          $del = db("DELETE FROM ".$db['msg']."
                     WHERE von = '".intval($_GET['id'])."'
                     OR an = '".intval($_GET['id'])."'");

          $del = db("DELETE FROM ".$db['news']."
                     WHERE autor = '".intval($_GET['id'])."'");

          $del = db("DELETE FROM ".$db['permissions']."
                     WHERE user = '".intval($_GET['id'])."'");

          $del = db("DELETE FROM ".$db['squaduser']."
                     WHERE user = '".intval($_GET['id'])."'");

          $del = db("DELETE FROM ".$db['taktik']."
                     WHERE autor = '".intval($_GET['id'])."'");

          $del = db("DELETE FROM ".$db['buddys']."
                     WHERE user = '".intval($_GET['id'])."'
                     OR buddy = '".intval($_GET['id'])."'");

          $upd = db("UPDATE ".$db['usergb']."
                     SET `reg` = 0
                     WHERE reg = ".intval($_GET['id'])."");

          $del = db("DELETE FROM ".$db['userpos']."
                     WHERE user = '".intval($_GET['id'])."'");

          $del = db("DELETE FROM ".$db['users']."
                     WHERE id = '".intval($_GET['id'])."'");

          $del = db("DELETE FROM ".$db['userstats']."
                     WHERE user = '".intval($_GET['id'])."'");

          $index = info(_user_deleted, "?action=userlist");;
        }
      }
    } else {
      $qry = db("SELECT id,user,nick,pwd,email,level,position,listck
                 FROM ".$db['users']."
                 WHERE id = '".intval($_GET['edit'])."'");
      while($get = _fetch($qry))
      {
        if($get['level'] == 1)     $selu = 'selected="selected"';
        elseif($get['level'] == 2) $selt = 'selected="selected"';
        elseif($get['level'] == 3) $selm = 'selected="selected"';
        elseif($get['level'] == 4) $sela = 'selected="selected"';

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

        $get_identy = show(_admin_user_get_identitat, array("id" => $_GET['edit']));
        $editpwd = show($dir."/admin_editpwd", array("pwd" => _new_pwd,
                                                     "epwd" => ""));

                if($chkMe == 4)    $elevel = show(_elevel_admin_select, array("selu" => $selu,
                                                                              "selt" => $selt,
                                                                              "selm" => $selm,
                                                                              "sela" => $sela,
                                                                              "ruser" => _status_user,
                                                                              "banned" => _admin_level_banned,
                                                                              "trial" => _status_trial,
                                                                              "member" => _status_member,
                                                                              "admin" => _status_admin));

                elseif(permission("editusers"))  $elevel = show(_elevel_perm_select, array("selu" => $selu,
                                                                                           "selt" => $selt,
                                                                                           "selm" => $selm,
                                                                                           "ruser" => _status_user,
                                                                                           "banned" => _admin_level_banned,
                                                                                           "trial" => _status_trial,
                                                                                           "member" => _status_member));

        $index = show($dir."/admin", array("enick" => re($get['nick']),
                                           "user" => intval($_GET['edit']),
                                           "value" => _button_value_edit,
                                           "eemail" => $get['email'],
                                           "eloginname" => $get['user'],
                                           "esquad" => $esquads,
                                           "editpwd" => $editpwd,
                                           "eposi" => $posi,
                                           "rechte" => _config_positions_rights,
                                           "getpermissions" => getPermissions(intval($_GET['edit'])),
                                           "getboardpermissions" => getBoardPermissions(intval($_GET['edit'])),
                                           "forenrechte" => _config_positions_boardrights,
                                           "showpos" => getrank($_GET['edit']),
                                           "nothing" => _nothing,
                                           "listck" => (empty($get['listck']) ? '' : ' checked="checked"'),
                                           "clankasse" => _user_list_ck,
                                           "auth_info" => _admin_user_clanhead_info,
                                           "alvl" => $get['level'],
                                           "elevel" => $elevel,
                                           "level_info" => _level_info,
                                           "gallery" => _admin_user_gallery,
                                           "yes" => _yes,
                                           "no" => _no,
                                           "cw_info" => _cw_info,
                                           "edithead" => _admin_user_edithead,
                                           "personalhead" => _admin_user_personalhead,
                                           "squadhead" => _admin_user_squadhead,
                                           "clanhead" => _admin_user_clanhead,
                                           "nick" => _nick,
                                           "email" => _email,
                                           "loginname" => _loginname,
                                           "identitat" => _admin_user_identitat,
                                           "get" => $get_identy,
                                           "squad" => _member_admin_squad,
                                           "newsletter" => _member_admin_newsletter,
                                           "downloads" => _member_admin_downloads,
                                           "links" => _member_admin_links,
                                           "votes" => _member_admin_votes,
                                           "votesadmin" => _member_admin_votesadmin,
                                           "gb" => _member_admin_gb,
                                           "forum" => _member_admin_forum,
                                           "intnews" => _member_admin_intnews,
                                           "intforum" => _member_admin_intforums,
                                           "i_forum" => $i_forum,
                                           "forums" => _forum,
                                           "access" => _access,
                                           "news" => _member_admin_news,
                                           "clanwars" => _member_admin_clanwars,
                                           "posi" => _profil_position,
                                           "level" => _admin_user_level,
                                           "ck" => _admin_user_clankasse,
                                           "sl" => _admin_user_serverliste,
                                           "eu" => _admin_user_edituser,
                                           "et" => _admin_user_edittactics,
                                           "esq" => _admin_user_editsquads,
                                           "eserver" => _admin_user_editserver,
                                           "ek" => _admin_user_editkalender));
      }
    }
  }
}
