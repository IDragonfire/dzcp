<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
include(basePath."/user/helper.php");

## SETTINGS ##
$time_start = generatetime();
lang($language);
$dir = "user";
$where = _site_user;

## SECTIONS ##
switch ($action):
case 'login';
    $where = _site_user_login;
    if($do == "yes") {
        if($secureLogin == 1 && ($_POST['secure'] != $_SESSION['sec_login'] || empty($_SESSION['sec_login'])))
            $index = error(_error_invalid_regcode, 1);
        else {
            if(checkpwd($_POST['user'], md5($_POST['pwd']))) {
                $get = db_stmt("SELECT id,user,nick,pwd,email,level,time FROM ".$db['users']." WHERE user = ? AND pwd = ? AND level != '0'", array('ss', up($_POST['user']), md5($_POST['pwd'])),false,true);
                if(!isBanned($get['id'])) {
                    $permanent_key = '';
                    if(isset($_POST['permanent'])) {
                        set_cookie($prev."id",$get['id']);
                        $permanent_key = md5(mkpwd(8));
                        set_cookie($prev."pkey",$permanent_key);
                    }

                    ## Aktualisiere Datenbank ##
                    db("UPDATE ".$db['users']." SET `online` = '1', `sessid` = '".session_id()."', `ip` = '".$_SESSION['ip']."', `pkey` = '".$permanent_key."' WHERE id = '".$get['id']."'");

                    $_SESSION['id']         = $get['id'];
                    $_SESSION['pwd']        = $get['pwd'];
                    $_SESSION['lastvisit']  = $get['time'];
                    $_SESSION['ip']         = $userip;

                    db("UPDATE ".$db['userstats']." SET `logins` = logins+1 WHERE user = ".$get['id']);
                    db("UPDATE ".$db['users']." SET `online` = '1', `sessid` = '".session_id()."', `ip` = '".$userip."', `pkey` = '".$permanent_key."' WHERE id = ".$get['id']);
                    setIpcheck("login(".$get['id'].")");

                    header("Location: ?action=userlobby");
                }
                else
                    $index = error(_login_banned);
            } else {
                $qry = db("SELECT id FROM ".$db['users']." WHERE user = '".up($_POST['user'])."'");
                if(_rows($qry)) {
                    $get = _fetch($qry);
                    setIpcheck("trylogin(".$get['id'].")");
                }

                set_cookie($prev."id","");
                set_cookie($prev."pkey","");
                $index = error(_login_pwd_dont_match);
            }
        }
    } else {
        if(!$chkMe) {
            $secure = $secureLogin ? show($dir."/secure", array("help" => _login_secure_help, "security" => _register_confirm)) : '';
            $index = show($dir."/login", array("loginhead" => _login_head,
                                               "loginname" => _loginname,
                                               "secure" => $secure,
                                               "lostpwd" => _login_lostpwd,
                                               "permanent" => _login_permanent,
                                               "pwd" => _pwd));
        } else {
            $index = error(_error_user_already_in, 1);
            set_cookie($prev."id","");
            set_cookie($prev."pkey","");
        }
    }
break;
case 'lostpwd';
    $where = _site_user_lostpwd;
    if(!$chkMe) {
        $index = show($dir."/lostpwd", array("head" => _lostpwd_head,
                                             "name" => _loginname,
                                             "value" => _button_value_send,
                                             "security" => _register_confirm,
                                             "email" => _email));

        if($do == "sended") {
            $get = db_stmt("SELECT id,user,level,pwd FROM ".$db['users']." WHERE `user`= ? AND `email` = ?",
                            array('ss', up($_POST['user']), up($_POST['email'])),false,true);

            if(_rows($qry) && ($_POST['secure'] == $_SESSION['sec_lostpwd'] && $_SESSION['sec_lostpwd'] != NULL)) {
                $pwd = mkpwd();
                db("UPDATE ".$db['users']." SET `pwd` = '".md5($pwd)."' WHERE id = '".$get['id']."'");
                setIpcheck("pwd(".$get['id'].")");
                $message = show(bbcode_email(settings('eml_pwd')), array("user" => $_POST['user'],"pwd" => $pwd));
                $subject = re(settings('eml_pwd_subj'));
                sendMail($_POST['email'],$subject,$message);
                $index = info(_lostpwd_valid, "../user/?action=login");
            } else {
                setIpcheck("trypwd(".$get['id'].")");
                if($_POST['secure'] != $_SESSION['sec_lostpwd'] || empty($_SESSION['sec_lostpwd']))
                    $index = error(_error_invalid_regcode,1);
                else
                    $index = error(_lostpwd_failed, 1);
            }
        }
    }
    else
        $index = error(_error_user_already_in, 1);
break;
case 'logout';
    $where = _site_user_logout;
    if($chkMe && $userid) {
        db("UPDATE ".$db['users']." SET online = '0', pkey = '', sessid = '' WHERE id = '".$userid."'");
        setIpcheck("logout(".$userid.")");

        set_cookie($prev.'id', '');
        set_cookie($prev.'pkey','');
        set_cookie(session_name(), '');

        session_unset();
        session_destroy();
        session_regenerate_id();
    }

    header("Location: ../news/");
break;
case 'register';
    $where = _site_reg;
    if(!$chkMe) {
        $check_regcode = settings("regcode"); $regcode = "";
        if($check_regcode) {
              $regcode = show($dir."/register_regcode", array("confirm" => _register_confirm,
                                                              "confirm_add" => _register_confirm_add,));
        }

        $index = show($dir."/register", array("registerhead" => _register_head,
                                              "error" => "",
                                              "name" => _loginname,
                                              "nick" => _nick,
                                              "pwd" => _pwd,
                                              "pwd2" => _pwd2,
                                              "email" => _email,
                                              "r_name" => "",
                                              "r_nick" => "",
                                              "r_email" => "",
                                              "pflicht" => _contact_pflichtfeld,
                                              "value" => _button_value_reg,
                                              "regcode" => $regcode));
    }
    else
        $index = error(_error_user_already_in, 1);

    if ($do == "add") {
        $check_user = db_stmt("SELECT id FROM ".$db['users']." WHERE `user`= ?",
                array('s', up($_POST['user'])),true,false);

        $check_nick = db_stmt("SELECT id FROM ".$db['users']." WHERE `nick`= ?",
                array('s', up($_POST['nick'])),true,false);

        $check_email = db_stmt("SELECT id FROM ".$db['users']." WHERE `email`= ?",
                array('s', up($_POST['email'])),true,false);

        $_POST['user'] = trim($_POST['user']); $_POST['nick'] = trim($_POST['nick']);

        if(empty($_POST['user']) || empty($_POST['nick']) || empty($_POST['email']) || ($_POST['pwd'] != $_POST['pwd2']) || ($check_regcode && ($_POST['confirm'] != $_SESSION['sec_reg'] || $_SESSION['sec_reg'] == NULL)) || $check_user || $check_nick || $check_email) {

        if($check_regcode && ($_POST['confirm'] != $_SESSION['sec_reg'] || $_SESSION['sec_reg'] == NULL))
            $error = show("errors/errortable", array("error" => _error_invalid_regcode));

        if($_POST['pwd2'] != $_POST['pwd'])
            $error = show("errors/errortable", array("error" => _wrong_pwd));

        if(!check_email($_POST['email']))
            $error = show("errors/errortable", array("error" => _error_invalid_email));

        if(empty($_POST['email']))
            $error = show("errors/errortable", array("error" => _empty_email));

        if($check_email)
            $error = show("errors/errortable", array("error" => _error_email_exists));

        if(empty($_POST['nick']))
            $error = show("errors/errortable", array("error" => _empty_nick));

        if($check_nick)
            $error = show("errors/errortable", array("error" => _error_nick_exists));

        if(empty($_POST['user']))
            $error = show("errors/errortable", array("error" => _empty_user));

        if($check_user)
            $error = show("errors/errortable", array("error" => _error_user_exists));

        $regcode = "";
        if($check_regcode) {
            $regcode = show($dir."/register_regcode", array("confirm" => _register_confirm,
                                                            "confirm_add" => _register_confirm_add,));
        }

        $index = show($dir."/register", array("registerhead" => _register_head,
                                              "error" => $error,
                                              "name" => _loginname,
                                              "nick" => _nick,
                                              "pwd" => _pwd,
                                              "pwd2" => _pwd2,
                                              "email" => _email,
                                              "r_name" => $_POST['user'],
                                              "r_nick" => $_POST['nick'],
                                              "r_email" => $_POST['email'],
                                              "pflicht" => _contact_pflichtfeld,
                                              "value" => _button_value_reg,
                                              "regcode" => $regcode));
        } else {
            if(empty($_POST['pwd']))
            {
                $mkpwd = mkpwd();
                $pwd = md5($mkpwd);
                $msg = _info_reg_valid;
            } else {
                $mkpwd = $_POST['pwd'];
                $pwd = md5($mkpwd);
                $msg = _info_reg_valid_pwd;
            }

            db("INSERT INTO ".$db['users']."
                     SET `user`     = '".up($_POST['user'])."',
                         `nick`     = '".up($_POST['nick'])."',
                         `email`    = '".up($_POST['email'])."',
                         `pwd`      = '".$pwd."',
                         `regdatum` = '".((int)time())."',
                         `level`    = '1',
                         `time`     = '".time()."',
                         `status`   = '1'");

            $insert_id = mysqli_insert_id($mysql);
            db("INSERT INTO ".$db['permissions']." SET `user` = '".((int)$insert_id)."'");
            db("INSERT INTO ".$db['userstats']." SET `user` = '".((int)$insert_id)."', `lastvisit` = '".((int)time())."'");

            setIpcheck("reg(".$insert_id.")");
            $message = show(bbcode_email(settings('eml_reg')), array("user" => $_POST['user'], "pwd" => $mkpwd));
            sendMail($_POST['email'],re(settings('eml_reg_subj')),$message);
            $index = info(show($msg, array("email" => $_POST['email'])), "../user/?action=login");
        }
    }
break;
case 'userlobby';
    $where = _site_user_lobby;
    if($chkMe) {
        $can_erase = false;

        //Get Userinfos
        $lastvisit = userstats('lastvisit');
        $lastvisit = empty($lastvisit) ? "0" : $lastvisit;

        /** Neue Foreneintraege anzeigen */
        $qrykat = db("SELECT s1.id,s2.kattopic,s1.intern,s2.id FROM ".$db['f_kats']." AS s1
                      LEFT JOIN ".$db['f_skats']." AS s2
                      ON s1.id = s2.sid
                      ORDER BY s1.kid,s2.kattopic");

        $forumposts = '';
        if(_rows($qrykat) >= 1) {
            while($getkat = _fetch($qrykat)) {
                unset($nthread);
                unset($post);
                unset($forumposts_show);

                if(fintern($getkat['id'])) {
                    $qrytopic = db("SELECT lp,id,topic,first,sticky FROM ".$db['f_threads']."
                                    WHERE kid = '".$getkat['id']."'
                                    AND lp > ".$lastvisit."
                                    ORDER BY lp DESC
                                    LIMIT 150");
                    if(_rows($qrytopic) >= 1) {
                        $forumposts_show = '';
                        while($gettopic = _fetch($qrytopic)) {
                            $lp = ""; $cnt = "";
                            $count = cnt($db['f_posts'], " WHERE date > ".$lastvisit." AND sid = '".$gettopic['id']."'");
                            $lp = cnt($db['f_posts'], " WHERE sid = '".$gettopic['id']."'");

                            if($count == 0) {
                                $cnt = 1;
                                $pagenr = 1;
                                $post = "";
                            } elseif($count == 1) {
                                $cnt = 1;
                                $pagenr = ceil($lp/$maxfposts);
                                $post = _new_post_1;
                            } else {
                                $cnt = $count;
                                $pagenr = ceil($lp/$maxfposts);
                                $post = _new_post_2;
                            }

                            $nthread = $gettopic['first'] == 1 ? _no_new_thread : _new_thread;

                            if(check_new($gettopic['lp'],1)) {
                                $intern = ($getkat['intern'] != 1 ? '' : '<span class="fontWichtig">'._internal.':</span>&nbsp;&nbsp;&nbsp;');
                                $wichtig = ($gettopic['sticky'] != 1 ? '' : '<span class="fontWichtig">'._sticky.':</span> ');

                                $date = (date("d.m.")==date("d.m.",$gettopic['lp']))
                                  ? '['.date("H:i",$gettopic['lp']).']'
                                  : date("d.m.",$gettopic['lp']).' ['.date("H:i",$gettopic['lp']).']';

                                $can_erase = true;
                                $forumposts_show .= '&nbsp;&nbsp;'.$date. show(_user_new_forum, array("cnt" => $cnt,
                                                                                                      "tid" => $gettopic['id'],
                                                                                                      "thread" => re($gettopic['topic']),
                                                                                                      "intern" => $intern,
                                                                                                      "wichtig" => $wichtig,
                                                                                                      "post" => $post,
                                                                                                      "page" => $pagenr,
                                                                                                      "nthread" => $nthread,
                                                                                                      "lp" => $lp +1));
                            }
                        }
                    }

                    if(!empty($forumposts_show))
                        $forumposts .= '<div style="padding:4px;padding-left:0"><span class="fontBold">'.$getkat['kattopic'].'</span></div>'.$forumposts_show;
                }
            }
        }

        /** Neue Clanwars anzeigen */
        $qrycw = db("SELECT s1.*,s2.icon FROM ".$db['cw']." AS s1
                     LEFT JOIN ".$db['squads']." AS s2
                     ON s1.squad_id = s2.id
                     ORDER BY s1.datum");
        $cws = '';
        if(_rows($qrycw) >= 1) {
            while($getcw = _fetch($qrycw)) {
                if(!empty($getcw) && check_new($getcw['datum'],1)) {
                    $check = cnt($db['cw'], " WHERE datum >".$lastvisit."");

                    if($check == 1) {
                        $cnt = 1;
                        $eintrag = _new_eintrag_1;
                    } else {
                        $cnt = $check;
                        $eintrag = _new_eintrag_2;
                    }

                    $can_erase = true;
                    $cws .= show(_user_new_cw, array("datum" => date("d.m. H:i", $getcw['datum'])._uhr,
                                                     "id" => $getcw['id'],
                                                     "icon" => $getcw['icon'],
                                                     "gegner" => re($getcw['clantag'])));
                }
            }
        }

        /** Neue Registrierte User anzeigen */
        $getu = db("SELECT id,regdatum FROM ".$db['users']." ORDER BY id DESC",false,true); $user = '';
        if(!empty($getu) && check_new($getu['regdatum'],1)) {
            $check = cnt($db['users'], " WHERE regdatum > ".$lastvisit."");

            if($check == 1) {
                $cnt = 1;
                $eintrag = _new_users_1;
            } else {
                $cnt = $check;
                $eintrag = _new_users_2;
            }

            $can_erase = true;
            $user = show(_user_new_users, array("cnt" => $cnt,
                                                "eintrag" => $eintrag));
        }

        /** Neue Eintruage im Guastebuch anzeigen */
        $permission_gb = permission("gb"); $activ = "";
        if(!$permission_gb && $gb_activ == '1')
            $activ = "WHERE public = 1";

        $gb = '';
        $getgb = db("SELECT id,datum FROM ".$db['gb']." ".$activ." ORDER BY id DESC",false,true);
        if(!empty($getgb) && check_new($getgb['datum'],1)) {
            $cntgb = "";
            if(!$permission_gb && $gb_activ == '1')
                $cntgb = "AND public = 1";

            $check = cnt($db['gb'], " WHERE datum > ".$lastvisit." ".$cntgb."");

            if($check == "1") {
                $cnt = "1";
                $eintrag = _new_eintrag_1;
            } else {
                $cnt = $check;
                $eintrag = _new_eintrag_2;
            }

            $can_erase = true;
            $gb = show(_user_new_gb, array("cnt" => $cnt,
                                           "eintrag" => $eintrag));
        }

        /** Neue Eintruage im User Guastebuch anzeigen */
        $getmember = db("SELECT id,datum FROM ".$db['usergb']." WHERE user = '".$userid."' ORDER BY datum DESC",false,true);

        $membergb = '';
        if(!empty($getmember) && check_new($getmember['datum'],1)) {
            $check = cnt($db['usergb'], " WHERE datum > ".$lastvisit." AND user = '".$userid."'");
            if($check == "1") {
                $cnt = "1";
                $eintrag = _new_eintrag_1;
            } else {
                $cnt = $check;
                $eintrag = _new_eintrag_2;
            }

            $can_erase = true;
            $membergb = show(_user_new_membergb, array("cnt" => $cnt,
                                                       "id" => $userid,
                                                       "eintrag" => $eintrag));
        }

        /** Neue Private Nachrichten anzeigen */
        $getmsg = db("SELECT id,an,datum FROM ".$db['msg']."
                      WHERE an = '".$userid."'
                      AND readed = 0
                      AND see_u = 0
                      ORDER BY datum DESC",false,true);

        $check = cnt($db['msg'], " WHERE an = '".$userid."' AND readed = 0 AND see_u = 0");
        if($check == 1)
            $mymsg = show(_lobby_mymessage, array("cnt" => 1));
        else if($check >= 1) {
            $mymsg = show(_lobby_mymessages, array("cnt" => $check));
        } else
            $mymsg = show(_lobby_no_mymessages, array());

        /** Neue News anzeigen */
        if($chkMe >= 2) {
            $qrynews = db("SELECT id,datum FROM ".$db['news']."
                           WHERE public = 1
                           AND datum <= ".time()."
                           ORDER BY id DESC");
        } else {
            $qrynews = db("SELECT id,datum FROM ".$db['news']."
                           WHERE public = 1
                           AND intern = 0
                           AND datum <= ".time()."
                           ORDER BY id DESC");
        }

        $news = '';
        if(_rows($qrynews) >= 1) {
            while($getnews  = _fetch($qrynews)) {
                if(check_new($getnews['datum'],1)) {
                    $check = cnt($db['news'], " WHERE datum > ".$lastvisit." AND public = 1");
                    $cnt = $check == "1" ? "1" : $check;
                    $can_erase = true;
                    $news = show(_user_new_news, array("cnt" => $cnt,
                                                       "eintrag" => _lobby_new_news));
                }
            }
        }

        /** Neue News comments anzeigen */
        $qrycheckn = db("SELECT id,titel FROM ".$db['news']." WHERE public = 1 AND datum <= ".time().""); $newsc = '';
        if(_rows($qrycheckn) >= 1) {
            while($getcheckn = _fetch($qrycheckn)) {
                $getnewsc = db("SELECT id,news,datum FROM ".$db['newscomments']." WHERE news = '".$getcheckn['id']."' ORDER BY datum DESC",false,true);
                if(check_new($getnewsc['datum'],1)) {
                    $check = cnt($db['newscomments'], " WHERE datum > ".$lastvisit." AND news = '".$getnewsc['news']."'");
                    if($check == "1") {
                        $cnt = "1";
                        $eintrag = _lobby_new_newsc_1;
                    } else {
                        $cnt = $check;
                        $eintrag = _lobby_new_newsc_2;
                    }

                    $can_erase = true;
                    $newsc .= show(_user_new_newsc, array("cnt" => $cnt,
                                                          "id" => $getnewsc['news'],
                                                          "news" => re($getcheckn['titel']),
                                                          "eintrag" => $eintrag));
                }
            }
        }

        /** Neue Clanwars comments anzeigen */
        $qrycheckcw = db("SELECT id FROM ".$db['cw']); $cwcom = '';
        if(_rows($qrycheckcw) >= 1) {
            while($getcheckcw = _fetch($qrycheckcw)) {
                $getcwc = db("SELECT id,cw,datum FROM ".$db['cw_comments']." WHERE cw = '".$getcheckcw['id']."' ORDER BY datum DESC",false,true);
                if(!empty($getcwc) && check_new($getcwc['datum'],1))
                {
                    $check = cnt($db['cw_comments'], " WHERE datum > ".$lastvisit." AND cw = '".$getcwc['cw']."'");
                    if($check == 1) {
                      $cnt = 1;
                      $eintrag = _lobby_new_cwc_1;
                    } else {
                      $cnt = $check;
                      $eintrag = _lobby_new_cwc_2;
                    }

                    $can_erase = true;
                    $cwcom .= show(_user_new_clanwar, array("cnt" => $cnt,
                                                            "id" => $getcwc['cw'],
                                                            "eintrag" => $eintrag));
                }
            }
        }

        /** Neue Votes anzeigen */
        if(permission("votes")) {
            $getnewv = db("SELECT datum FROM ".$db['votes']."
                           WHERE forum = 0
                           ORDER BY datum DESC",false,true);
        } else {
            $getnewv = db("SELECT datum FROM ".$db['votes']."
                           WHERE intern = 0
                           AND forum = 0
                           ORDER BY datum DESC",false,true);
        }

        $newv = '';
        if(!empty($getnewv) && check_new($getnewv['datum'],1)) {
            $check = cnt($db['votes'], " WHERE datum > ".$lastvisit." AND forum = 0");
            if($check == "1") {
                $cnt = "1";
                $eintrag = _new_vote_1;
            } else {
                $cnt = $check;
                $eintrag = _new_vote_2;
            }

            $can_erase = true;
            $newv = show(_user_new_votes, array("cnt" => $cnt,
                                                "eintrag" => $eintrag));
        }

        /** Kalender Events anzeigen */
        $getkal = db("SELECT * FROM ".$db['events']." WHERE datum > '".time()."' ORDER BY datum",false,true);
        $nextkal = '';
        if(!empty($getkal) && check_new($getkal['datum'],1)) {
            if(date("d.m.Y",$getkal['datum']) == date("d.m.Y", time())) {
              $nextkal = show(_userlobby_kal_today, array("time" => mktime(0,0,0,date("m",$getkal['datum']), date("d",$getkal['datum']),date("Y",$getkal['datum']))));
            } else {
              $nextkal = show(_userlobby_kal_not_today, array("time" => mktime(0,0,0,date("m",$getkal['datum']), date("d",$getkal['datum']),date("Y",$getkal['datum'])),
                                                              "date" => date("d.m.Y", $getkal['datum'])));
            }
        }

        /** Neue Awards anzeigen */
        $getaw = db("SELECT id,postdate FROM ".$db['awards']." ORDER BY id DESC",false,true); $awards = '';
        if(!empty($getaw) && check_new($getaw['postdate'],1)) {
            $check = cnt($db['awards'], " WHERE postdate > ".$lastvisit);
            if($check == "1") {
                $cnt = "1";
                $eintrag = _new_awards_1;
            } else {
                $cnt = $check;
                $eintrag = _new_awards_2;
            }

            $can_erase = true;
            $awards = show(_user_new_awards, array("cnt" => $cnt,
                                                   "eintrag" => $eintrag));
        }

        /** Neue Rankings anzeigen */
        $getra = db("SELECT id,postdate FROM ".$db['rankings']." ORDER BY id DESC",false,true);
        $rankings = '';
        if(!empty($getra) && check_new($getra['postdate'],1)) {
            $check = cnt($db['rankings'], " WHERE postdate > ".$lastvisit);
            if($check == "1") {
                $cnt = "1";
                $eintrag = _new_rankings_1;
            } else {
                $cnt = $check;
                $eintrag = _new_rankings_2;
            }

            $can_erase = true;
            $rankings = show(_user_new_rankings, array("cnt" => $cnt,
                                                       "eintrag" => $eintrag));
        }

        /** Neue Artikel anzeigen */
        $qryart = db("SELECT id,datum FROM ".$db['artikel']." WHERE public = 1 ORDER BY id DESC"); $artikel = '';
        if(_rows($qryart) >= 1) {
            while($getart  = _fetch($qryart)) {
                if(check_new($getart['datum'],1)) {
                    $check = cnt($db['artikel'], " WHERE datum > ".$lastvisit." AND public = 1");
                    if($check == "1") {
                          $cnt = "1";
                          $eintrag = _lobby_new_art_1;
                    } else {
                          $cnt = $check;
                          $eintrag = _lobby_new_art_2;
                    }

                    $can_erase = true;
                    $artikel = show(_user_new_art, array("cnt" => $cnt,
                                                         "eintrag" => $eintrag));
                }
            }
        }

        /** Neue Artikel Comments anzeigen */
        $qrychecka = db("SELECT id FROM ".$db['artikel']." WHERE public = 1"); $artc = '';
        if(_rows($qrychecka) >= 1) {
            while($getchecka = _fetch($qrychecka)) {
                $getartc = db("SELECT id,artikel,datum FROM ".$db['acomments']."
                               WHERE artikel = '".$getchecka['id']."'
                               ORDER BY datum DESC",false,true);

                if(!empty($getartc) && check_new($getartc['datum'],1)) {
                    $check = cnt($db['acomments'], " WHERE datum > ".$lastvisit." AND artikel = '".$getartc['artikel']."'");
                    if($check == "1") {
                        $cnt = "1";
                        $eintrag = _lobby_new_artc_1;
                    } else {
                        $cnt = $check;
                        $eintrag = _lobby_new_artc_2;
                    }

                    $can_erase = true;
                    $artc .= show(_user_new_artc, array("cnt" => $cnt,
                                                        "id" => $getartc['artikel'],
                                                        "eintrag" => $eintrag));
                }
            }
        }

        /** Neue Bilder in der Gallery anzeigen */
        $getgal = db("SELECT id,datum FROM ".$db['gallery']." ORDER BY id DESC",false,true); $gal = '';
        if(!empty($getgal) && check_new($getgal['datum'],1)) {
            $check = cnt($db['gallery'], " WHERE datum > ".$lastvisit);
            if($check == "1") {
                $cnt = "1";
                $eintrag = _new_gal_1;
            } else {
                $cnt = $check;
                $eintrag = _new_gal_2;
            }

            $can_erase = true;
            $gal = show(_user_new_gallery, array("cnt" => $cnt,
                                                 "eintrag" => $eintrag));
        }

        /** Neue Aways anzeigen */
        $qryawayn = db("SELECT * FROM ".$db['away']." ORDER BY id"); $away_new = '';
        if(_rows($qryawayn) >= 1) {
            $awayn = '';
            while($getawayn = _fetch($qryawayn)) {
                if(check_new($getawayn['date'],1) && data('level') >= 2) {
                    $awayn .= show(_user_away_new, array("id" => $getawayn['id'],
                                                         "user" => autor($getawayn['userid']),
                                                         "ab" => date("d.m.y",$getawayn['start']),
                                                         "wieder" => date("d.m.y",$getawayn['end']),
                                                         "what" => $getawayn['titel']));
                }
            }

            $can_erase = true;
            $away_new = show(_user_away, array("naway" => _lobby_away_new,
                                               "away" => $awayn));
        }

        /** Alle Aways anzeigen */
        $qryawaya = db("SELECT * FROM ".$db['away']." WHERE start <= '".time()."' AND end >= '".time()."' ORDER BY start"); $away_now = "";
        if(_rows($qryawaya) >= 1) {
            $awaya = "";
            while($getawaya = _fetch($qryawaya)) {
                if(_rows($qryawaya) && data('level') >= 2) {
                    $wieder = '';
                    if($getawaya['end'] > time())
                        $wieder = _away_to2.' <b>'.date("d.m.y",$getawaya['end']).'</b>';

                    if(date("d.m.Y",$getawaya['end']) == date("d.m.Y",time()))
                        $wieder = _away_today;

                    $awaya .= show(_user_away_now, array("id" => $getawaya['id'],
                                                         "user" => autor($getawaya['userid']),
                                                         "wieder" => $wieder,
                                                         "what" => $getawaya['titel']));
                }
            }

            $away_now = show(_user_away_currently, array("ncaway" => _lobby_away,
                                                         "caway" => $awaya));
        }

        /** Neue Forum Topics anzeigen */
        $qryft = db("SELECT s1.t_text,s1.id,s1.topic,s1.kid,s2.kattopic,s3.intern,s1.sticky
                     FROM ".$db['f_threads']." s1, ".$db['f_skats']." s2, ".$db['f_kats']." s3
                     WHERE s1.kid = s2.id
                     AND s2.sid = s3.id
                     ORDER BY s1.lp DESC
                     LIMIT 10");
        $ftopics = '';
        if(_rows($qryft) >= 1) {
            while($getft = _fetch($qryft)) {
                if(fintern($getft['kid'])) {
                    $lp = cnt($db['f_posts'], " WHERE sid = '".$getft['id']."'");
                    $pagenr = ceil($lp/$maxfposts);
                    $page = ($pagenr == 0 ? 1 : $pagenr);
                    $getp = db("SELECT text FROM ".$db['f_posts']."
                                WHERE kid = '".$getft['kid']."'
                                AND sid = '".$getft['id']."'
                                ORDER BY date DESC
                                LIMIT 1",false,true);

                    $text = strip_tags(!empty($getp) ? $getp['text'] : $getft['t_text']);
                    $intern = $getft['intern'] != 1 ? "" : '<span class="fontWichtig">'._internal.':</span>';
                    $wichtig = $getft['sticky'] != 1 ? '' : '<span class="fontWichtig">'._sticky.':</span> ';
                    $ftopics .= show($dir."/userlobby_forum", array("id" => $getft['id'],
                                                                    "pagenr" => $page,
                                                                    "p" => $lp +1,
                                                                    "intern" => $intern,
                                                                    "wichtig" => $wichtig,
                                                                    "lpost" => cut(re($text), 100),
                                                                    "kat" => re($getft['kattopic']),
                                                                    "titel" => re($getft['topic']),
                                                                    "kid" => $getft['kid']));
                }
            }
        }

        // Userlevel
        if(($lvl = data("level")) == 1) $mylevel = _status_user;
        elseif($lvl == 2) $mylevel = _status_trial;
        elseif($lvl == 3) $mylevel = _status_member;
        elseif($lvl == 4) $mylevel = _status_admin;

        $erase = $can_erase ? _user_new_erase : '';
        $index = show($dir."/userlobby", array("userlobbyhead" => _userlobby,
                                               "erase" => $erase,
                                               "pic" => useravatar(),
                                               "mynick" => autor($userid),
                                               "myrank" => getrank($userid),
                                               "myposts" => userstats("forumposts"),
                                               "mylogins" => userstats("logins"),
                                               "myhits" => userstats("hits"),
                                               "mymsg" => $mymsg,
                                               "mylevel" => $mylevel,
                                               "puser" => _user,
                                               "plevel" => _admin_user_level,
                                               "plogins" => _profil_logins,
                                               "phits" => _profil_pagehits,
                                               "prank" => _profil_position,
                                               "pposts" => _profil_forenposts,
                                               "nkal" => _kalender,
                                               "kal" => $nextkal,
                                               "nart" => _artikel,
                                               "art" => $artikel,
                                               "nartc" => _lobby_artikelc,
                                               "artc" => $artc,
                                               "board" => _forum,
                                               "threads" => _forum_thread,
                                               "rankings" => $rankings,
                                               "nrankings" => _lobby_rankings,
                                               "awards" => $awards,
                                               "nawards" => _lobby_awards,
                                               "nforum" => _lobby_forum,
                                               "ftopics" => $ftopics,
                                               "lastforum" => _last_forum,
                                               "forum" => $forumposts,
                                               "nvotes" => _lobby_votes,
                                               "ncwcom" => _cw_comments_head,
                                               "cwcom" => $cwcom,
                                               "ngal" => _lobby_gallery,
                                               "gal" => $gal,
                                               "votes" => $newv,
                                               "cws" => $cws,
                                               "ncws" => _lobby_cw,
                                               "nnewsc" => _lobby_newsc,
                                               "newsc" => $newsc,
                                               "ngb" => _lobby_gb,
                                               "gb" => $gb,
                                               "nuser" => _lobby_user,
                                               "user" => $user,
                                               "nmgb" => _lobby_membergb,
                                               "mgb" => $membergb,
                                               "nmsg" => _msg,
                                               "nnews" => _lobby_news,
                                               "news" => $news,
                                               "away_new" => $away_new,
                                               "away_now" => $away_now,
                                               "neuerungen" => _lobby_new));
    }
    else
        $index = error(_error_have_to_be_logged, 1);
break;
case 'erase';
    if($userid) {
        $_SESSION['lastvisit'] = data("time");
        db("UPDATE ".$db['userstats']."
            SET `lastvisit` = '".((int)$_SESSION['lastvisit'])."'
            WHERE user = '".$userid."'");
    }

    header("Location: ?action=userlobby");
break;
case 'user';
    $where = _user_profile_of.'autor_'.$_GET['id'];
    if(!exist((int)$_GET['id']))
        $index = error(_user_dont_exist, 1);
    else {
        db("UPDATE ".$db['userstats']."
            SET `profilhits` = profilhits+1
            WHERE user = '".intval($_GET['id'])."'");

        $get = db("SELECT * FROM ".$db['users']." WHERE id = '".intval($_GET['id'])."'",false,true);
        $sex = '-';
        if($get['sex'] == "1")
            $sex = _male;
        elseif($get['sex'] == "2")
            $sex = _female;

        $hp = empty($get['hp']) ? "-" : "<a href=\"".$get['hp']."\" target=\"_blank\">".$get['hp']."</a>";
        $email = empty($get['email']) ? "-" : "<img src=\"../inc/images/mailto.gif\" alt=\"\" align=\"texttop\"> <a href=\"mailto:".eMailAddr($get['email'])."\" target=\"_blank\">".eMailAddr($get['email'])."</a>";
        $pn = show(_pn_write, array("id" => $_GET['id'], "nick" => $get['nick']));
        $hlsw = empty($get['hlswid']) ? "-" : show(_hlswicon, array("id" => re($get['hlswid']), "img" => "1", "css" => ""));
        $xboxu = empty($get['xboxid']) ? "-" : show(_xboxicon, array("id" => str_replace(" ","%20",trim(re($get['xboxid']))), "img" => "1", "css" => ""));
        $xboxuser = empty($get['xboxid']) ? _noxboxavatar : show(_xboxpic, array("id" => str_replace(" ","%20",trim(re($get['xboxid']))), "img" => "1", "css" => ""));
        $psnu = empty($get['psnid']) ? "-" : show(_psnicon, array("id" => str_replace(" ","%20",trim(re($get['psnid']))), "img" => "1", "css" => ""));
        $originu = empty($get['originid']) ? '-' : show(_originicon, array("id" => str_replace(" ","%20",trim(re($get['originid']))), "img" => "1", "css" => ""));
        $battlenetu = empty($get['battlenetid']) ? '-' : show(_battleneticon, array("id" => str_replace(" ","%20",trim(re($get['battlenetid']))), "img" => "1", "css" => ""));
        $bday = ($get['bday'] == ".." || $get['bday'] == 0 || empty($get['bday'])) ? "-" : $get['bday'];

        $icq = "-"; $icqnr = '';
        if(!empty($get['icq'])) {
            $icq = show(_icqstatus, array("uin" => $get['icq']));
            $icqnr = re($get['icq']);
        }

        $status = ($get['status'] == 1 || ($getl['level'] != 1 && isset($_GET['sq']))) ? _aktiv_icon : _inaktiv_icon;
        $getl = db("SELECT * FROM ".$db['users']." WHERE id = '".intval($_GET['id'])."'",false,true);

        $clan = "";
        if($getl['level'] != 1 || isset($_GET['sq'])) {
            $sq = db("SELECT * FROM ".$db['userpos']." WHERE user = '".intval($_GET['id'])."'");
            $cnt = cnt($db['userpos'], " WHERE user = '".$get['id']."'"); $i=1;

            if(_rows($sq) && !isset($_GET['sq'])) {
                while($getsq = _fetch($sq)) {
                    if($i == $cnt) $br = "";
                    else $br = "-";

                    $pos .= " ".getrank($get['id'],$getsq['squad'],1)." ".$br;
                    $i++;
                }
            }
            else if(isset($_GET['sq']))
                $pos = getrank($get['id'],$_GET['sq'],1);
            else
                $pos = getrank($get['id']);

            $qrycustom = db("SELECT * FROM ".$db['profile']." WHERE kid = '2' AND shown = '1' ORDER BY id ASC"); $custom_clan = '';
            while($getcustom = _fetch($qrycustom)) {
                $getcontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']." WHERE id = '".intval($_GET['id'])."' LIMIT 1",false,true);
                if(!empty($getcontent[$getcustom['feldname']])) {
                    if($getcustom['type'] == 2)
                        $custom_clan .= show(_profil_custom_url, array("name" => re(pfields_name($getcustom['name'])), "value" => re($getcontent[$getcustom['feldname']])));
                    else if($getcustom['type'] == 3)
                        $custom_clan .= show(_profil_custom_mail, array("name" => re(pfields_name($getcustom['name'])), "value" => eMailAddr(re($getcontent[$getcustom['feldname']]))));
                    else
                        $custom_clan .= show(_profil_custom, array("name" => re(pfields_name($getcustom['name'])), "value" => re($getcontent[$getcustom['feldname']])));
                }
            }

            $clan = show($dir."/clan", array("clan" => _profil_clan,
                                             "pposition" => _profil_position,
                                             "pstatus" => _profil_status,
                                             "position" => $pos,
                                             "status" => $status,
                                             "custom_clan" => $custom_clan));
        }

        $buddyadd = show(_addbuddyicon, array("id" => $_GET['id']));

        $edituser = "";
        if(permission("editusers")) {
            $edituser = show("page/button_edit_single", array("id" => "",
                                                              "action" => "action=admin&amp;edit=".$_GET['id'],
                                                              "title" => _button_title_edit));
            $edituser = str_replace("&amp;id=","",$edituser);
        }

        if(isset($_GET['show']) && $_GET['show'] == "gallery") {
            $qrygl = db("SELECT * FROM ".$db['usergallery']."
                          WHERE user = '".intval($_GET['id'])."'
                          ORDER BY id DESC");

            $qryperm = db("SELECT id,perm_gallery FROM ".$db['users']." WHERE id = ".$_GET['id'],false,true);
            $qryuser = db("SELECT level FROM ".$db['users']." WHERE id = ".$userid,false,true);
            $gal = '';
            if($qryperm['perm_gallery'] < $qryuser['level'] || $qryperm['id'] == $userid) {
                $color = 0;
                while($getgl = _fetch($qrygl)) {
                    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                    $gal .= show($dir."/profil_gallery_show", array("picture" => img_size("inc/images/uploads/usergallery"."/".$qryperm['id']."_".$getgl['pic']),
                                                                    "beschreibung" => bbcode($getgl['beschreibung']),
                                                                    "class" => $class));
                }

                $show = show($dir."/profil_gallery", array("galleryhead" => _gallery_head,
                                                           "pic" => _gallery_pic,
                                                           "beschr" => _gallery_beschr,
                                                           "showgallery" => $gal));
        }
        else
            $show = _gallery_no_perm;

    }
    elseif(isset($_GET['show']) && $_GET['show'] == "gb")
    {
        $addgb = show(_usergb_eintragen, array("id" => $_GET['id']));
        $qrygb = db("SELECT * FROM ".$db['usergb']."
                     WHERE user = ".intval($_GET['id'])."
                     ORDER BY datum DESC
                     LIMIT ".($page - 1)*$maxusergb.",".$maxusergb."");

        $entrys = cnt($db['usergb'], " WHERE user = ".intval($_GET['id']));
        $i = $entrys-($page - 1)*$maxusergb;

        $membergb = '';
        while($getgb = _fetch($qrygb)) {
            $gbhp = $getgb['hp'] ? show(_hpicon, array("hp" => $getgb['hp'])) : "";
            $gbemail = $getgb['email'] ? show(_emailicon, array("email" => eMailAddr($getgb['email']))) : "";

            $edit = ""; $delete = "";
            if(permission('editusers') || $_GET['id'] == $userid) {
                $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                              "action" => "action=user&amp;show=gb&amp;do=edit&amp;gbid=".$getgb['id'],
                                                              "title" => _button_title_edit));

                $delete = show("page/button_delete_single", array("id" => $_GET['id'],
                                                                  "action" => "action=user&amp;show=gb&amp;do=delete&amp;gbid=".$getgb['id'],
                                                                  "title" => _button_title_del,
                                                                  "del" => convSpace(_confirm_del_entry)));
            }

            if(!$getgb['reg']) {
                $www = "";
                $hp = $getgb['hp'] ? show(_hpicon_forum, array("hp" => $getgb['hp'])) : "";
                $email = $getgb['email'] ? '<br />'.show(_emailicon_forum, array("email" => eMailAddr($getgb['email']))) : "";
                $onoff = ""; $avatar = "";
                $nick = show(_link_mailto, array("nick" => re($getgb['nick']),
                                                 "email" => eMailAddr($getgb['email'])));
            } else {
                $www = data("hp",$getgb['reg']);
                $hp = empty($www) ? '' : show(_hpicon_forum, array("hp" => $www));
                $email = '<br />'.show(_emailicon_forum, array("email" => eMailAddr(data("email",$getgb['reg']))));
                $onoff = onlinecheck($getgb['reg']);
                $nick = autor($getgb['reg']);
            }

            $titel = show(_eintrag_titel, array("postid" => $i,
                                                "datum" => date("d.m.Y", $getgb['datum']),
                                                "zeit" => date("H:i", $getgb['datum'])._uhr,
                                                "edit" => $edit,
                                                "delete" => $delete));

            $posted_ip = ($chkMe == 4 ? $getgb['ip'] : _logged);
            $membergb .= show("page/comments_show", array("titel" => $titel,
                                                          "comment" => bbcode($getgb['nachricht']),
                                                          "nick" => $nick,
                                                          "hp" => $hp,
                                                          "editby" => bbcode($getgb['editby']),
                                                          "email" => $email,
                                                          "avatar" => useravatar($getgb['reg']),
                                                          "onoff" => $onoff,
                                                          "rank" => getrank($getgb['reg']),
                                                          "ip" => $posted_ip));
            $i--;
        }

        $add = "";
        if(!ipcheck("mgbid(".$_GET['id'].")", $flood_membergb)) {
            if($userid >= 1) {
                $form = show("page/editor_regged", array("nick" => autor($userid),
                                                         "von" => _autor));
            } else {
                $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                            "emailhead" => _email,
                                                            "hphead" => _hp,
                                                            "postemail" => ""));
            }

            $add = show($dir."/usergb_add", array("titel" => _eintragen_titel,
                                                  "nickhead" => _nick,
                                                  "bbcodehead" => _bbcode,
                                                  "emailhead" => _email,
                                                  "hphead" => _hp,
                                                  "form" => $form,
                                                  "security" => _register_confirm,
                                                  "preview" => _preview,
                                                  "ed" => "&amp;uid=".$_GET['id'],
                                                  "whaturl" => "add",
                                                  "reg" => "",
                                                  "id" => $_GET['id'],
                                                  "add_head" => _gb_add_head,
                                                  "what" => _button_value_add,
                                                  "lang" => $language,
                                                  "ip" => _iplog_info,
                                                  "posteintrag" => "",
                                                  "error" => "",
                                                  "eintraghead" => _eintrag));
        }

        $seiten = nav($entrys,$maxusergb,"?action=user&amp;id=".$_GET['id']."&show=gb");
        $qryperm = db("SELECT perm_gb FROM ".$db['users']." WHERE id = ".$_GET['id'],false,true);
        $add = $qryperm['perm_gb'] != 1 ? "" : $add;
        $show = show($dir."/profil_gb",array("gbhead" => _membergb,
                                             "show" => $membergb,
                                             "seiten" => $seiten,
                                             "entry" => $add));
    } else {
        $custom_about = custom_content(1);
        $custom_contact = custom_content(3);

        $custom_hardware = custom_content(5);
        if($custom_hardware['count'] != 0)
            $hardware_head = show(_profil_head_cont, array("what" => _profil_hardware));

        $custom_favos = custom_content(4);
        if($custom_favos['count'] != 0)
            $favos_head = show(_profil_head_cont, array("what" => _profil_favos));

        $rlname = $get['rlname'] ? re($get['rlname']) : "-";
        $skypename = $get['skypename'] ? "<a href=\"skype:".$get['skypename']."?chat\"><img src=\"http://mystatus.skype.com/smallicon/".$get['skypename']."\" style=\"border: none;\" width=\"16\" height=\"16\" alt=\"".$get['skypename']."\"/></a>" : "-";
        $steam = $get['steamid'] ? show(_steamicon, array("steamid" => $get['steamid']))." ".re($get['steamid']) : "-";

        $city = re($get['city']); $beschreibung = bbcode($get['beschreibung']);
        $show = show($dir."/profil_show",array("hardware_head" => $hardware_head,
                                               "about" => _profil_about,
                                               "country" => flag($get['country']),
                                               "pcity" => _profil_city,
                                               "city" => (empty($city) ? '-' : $city),
                                               "stats_hits" => _profil_pagehits,
                                               "stats_profilhits" => _profil_profilhits,
                                               "stats_msgs" => _profil_msgs,
                                               "stats_lastvisit" => _profil_last_visit,
                                               "stats_forenposts" => _profil_forenposts,
                                               "stats_logins" => _profil_logins,
                                               "stats_cws" => _profil_cws,
                                               "stats_reg" => _profil_registered,
                                               "stats_votes" => _profil_votes,
                                               "logins" => userstats("logins",$_GET['id']),
                                               "hits" => userstats("hits",$_GET['id']),
                                               "msgs" => userstats("writtenmsg",$_GET['id']),
                                               "forenposts" => userstats("forumposts",$_GET['id']),
                                               "votes" => userstats("votes",$_GET['id']),
                                               "cws" => userstats("cws",$_GET['id']),
                                               "regdatum" => date("d.m.Y H:i", $get['regdatum'])._uhr,
                                               "lastvisit" => date("d.m.Y H:i", userstats("lastvisit",$_GET['id']))._uhr,
                                               "contact" => _profil_contact,
                                               "preal" => _profil_real,
                                               "pemail" => _email,
                                               "picq" => _icq,
                                               "phlsw" => _hlswstatus,
                                               "psteam" => _steamid,
                                               "xboxl" => _xboxstatus,
                                               "xboxavatarl" => _xboxuserpic,
                                               "psnl" => _psnstatus,
                                               "skypel" => _skypestatus,
                                               "originl" => _originstatus,
                                               "battlenetl" => _battlenetstatus,
                                               "php" => _hp,
                                               "hp" => $hp,
                                               "pnick" => _nick,
                                               "pbday" => _profil_bday,
                                               "page" => _profil_age,
                                               "psex" => _profil_sex,
                                               "gamestuff" => _profil_gamestuff,
                                               "xfire" => re($get['hlswid']),
                                               "xboxx" => re($get['xboxid']),
                                               "psnn" => re($get['psnid']),
                                               "originn" => re($get['originid']),
                                               "battlenett" => re($get['battlenetid']),
                                               "buddyadd" => $buddyadd,
                                               "userstats" => _profil_userstats,
                                               "pos" => _profil_os,
                                               "pcpu" => _profil_cpu,
                                               "pram" => _profil_ram,
                                               "phdd" => _profil_hdd,
                                               "pboard" => _profil_board,
                                               "pmaus" => _profil_maus,
                                               "nick" => autor($get['id']),
                                               "rlname" => $rlname,
                                               "bday" => $bday,
                                               "age" => getAge($get['bday']),
                                               "sex" => $sex,
                                               "email" => $email,
                                               "icq" => $icq,
                                               "icqnr" => $icqnr,
                                               "skypename" => $skypename,
                                               "skype" => $get['skypename'],
                                               "pn" => $pn,
                                               "edituser" => $edituser,
                                               "hlswid" => $hlsw,
                                               "xboxid" => $xboxu,
                                               "xboxavatar" => $xboxuser,
                                               "psnid" => $psnu,
                                               "originid" => $originu,
                                               "battlenetid" => $battlenetu,
                                               "steam" => $steam,
                                               "onoff" => onlinecheck($get['id']),
                                               "clan" => $clan,
                                               "picture" => userpic($get['id']),
                                               "favos_head" => $favos_head,
                                               "sonst" =>    _profil_sonst,
                                               "pich" => _profil_ich,
                                               "pposition" => _profil_position,
                                               "pstatus" => _profil_status,
                                               "position" => getrank($get['id']),
                                               "status" => $status,
                                               "ich" => (empty($beschreibung) ? '-' : $beschreibung),
                                               "custom_about" => $custom_about['content'],
                                               "custom_contact" => $custom_contact['content'],
                                               "custom_favos" => $custom_favos['content'],
                                               "custom_hardware" => $custom_hardware['content']));
    }

    $navi_profil = show(_profil_navi_profil, array("id" => $_GET['id']));
    $navi_gb = show(_profil_navi_gb, array("id" => $_GET['id']));
    $navi_gallery = show(_profil_navi_gallery, array("id" => $_GET['id']));
    $profil_head = show(_profil_head, array("profilhits" => userstats("profilhits",$_GET['id'])));

    $index = show($dir."/profil", array("profilhead" => $profil_head,
                                        "show" => $show,
                                        "nick" => autor($_GET['id']),
                                        "profil" => $navi_profil,
                                        "gb" => $navi_gb,
                                        "gallery" => $navi_gallery));

    if($do == "delete") {
        if($chkMe == "4" || $_GET['id'] == $userid) {
            db("DELETE FROM ".$db['usergb']."
                WHERE user = '".intval($_GET['id'])."'
                AND id = '".intval($_GET['gbid'])."'");

            $index = info(_gb_delete_successful, "?action=user&amp;id=".$_GET['id']."&show=gb");
        }
        else
            $index = error(_error_wrong_permissions, 1);

    }
    else if($do == "edit")
    {
        $get = db("SELECT * FROM ".$db['usergb']."
                   WHERE id = '".intval($_GET['gbid'])."'",false,true);

        if($get['reg'] == $userid || permission('editusers')) {
            if($get['reg'] != 0) {
                $form = show("page/editor_regged", array("nick" => autor($get['reg']), "von" => _autor));
            } else {
                $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                            "emailhead" => _email,
                                                            "hphead" => _hp,
                                                            "postemail" => re($get['email']),
                                                            "posthp" => re($get['hp']),
                                                            "postnick" => re($get['nick'])));
            }

            $index = show($dir."/usergb_add", array("nickhead" => _nick,
                                                    "add_head" => _gb_edit_head,
                                                    "bbcodehead" => _bbcode,
                                                    "emailhead" => _email,
                                                    "preview" => _preview,
                                                    "whaturl" => "edit&gbid=".$_GET['gbid'],
                                                    "ed" => "&amp;do=edit&amp;uid=".$_GET['id']."&amp;gbid=".$_GET['gbid'],
                                                    "security" => _register_confirm,
                                                    "what" => _button_value_edit,
                                                    "reg" => $get['reg'],
                                                    "hphead" => _hp,
                                                    "id" => $_GET['id'],
                                                    "form" => $form,
                                                    "postemail" => $get['email'],
                                                    "posthp" => $get['hp'],
                                                    "postnick" => re($get['nick']),
                                                    "posteintrag" => re_bbcode($get['nachricht']),
                                                    "error" => $error,
                                                    "ip" => _iplog_info,
                                                    "eintraghead" => _eintrag));
        }
        else
            $index = error(_error_edit_post,1);
    }
}
break;
case 'usergb';
  $where = _site_user_profil;
    if(_rows(db("SELECT `id` FROM ".$db['users']." WHERE `id` = '".(int)$_GET['id']."'")) != 0)
    {
        if($_GET['do'] == "add")
        {

            if($userid >= 1) $toCheck = empty($_POST['eintrag']);
            else
                $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);

            if($toCheck)
            {
                if($userid >= 1)
                {
                    if(empty($_POST['eintrag'])) $error = _empty_eintrag;

                    $form = show("page/editor_regged", array("nick" => autor($userid),
                                                                                                     "von" => _autor));
                } else {
                    if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || empty($_SESSION['sec_'.$dir])) $error = _error_invalid_regcode;
                    elseif(empty($_POST['nick']))  $error = _empty_nick;
                    elseif(empty($_POST['email'])) $error = _empty_email;
                    elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
                    elseif(empty($_POST['eintrag'])) $error = _empty_eintrag;

                    $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                                                                            "emailhead" => _email,
                                                                                                            "hphead" => _hp,));
                }

                $error = show("errors/errortable", array("error" => $error));

                $index = show($dir."/usergb_add", array("titel" => _eintragen_titel,
                                                                                                "nickhead" => _nick,
                                                                                                "add_head" => _gb_add_head,
                                                                                                "bbcodehead" => _bbcode,
                                                                                                "emailhead" => _email,
                                                                                                "preview" => _preview,
                                                                                                "ed" => "&amp;uid=".$_GET['id'],
                                                                                                "whaturl" => "add",
                                                                                                "security" => _register_confirm,
                                                                                                "what" => _button_value_add,
                                                                                                "hphead" => _hp,
                                                                                                "id" => $_GET['id'],
                                                                                                "reg" => $_POST['reg'],
                                                                                                "form" => $form,
                                                                                                "postemail" => $_POST['email'],
                                                                                                "posthp" => $_POST['hp'],
                                                                                                "postnick" => re($_POST['nick']),
                                                                                                "posteintrag" => re_bbcode($_POST['eintrag']),
                                                                                                "error" => $error,
                                                                                                "ip" => _iplog_info,
                                                                                                "eintraghead" => _eintrag));
            } else {
                $qryperm = _fetch(db("SELECT perm_gb FROM ".$db['users']." WHERE id = ".$_GET['id']));
                if ($qryperm['perm_gb'] == 1)
                {
                    $qry = db("INSERT INTO ".$db['usergb']."
                                         SET `user`       = '".((int)$_GET['id'])."',
                                                 `datum`      = '".((int)time())."',
                                                 `nick`       = '".up($_POST['nick'])."',
                                                 `email`      = '".up($_POST['email'])."',
                                                 `hp`         = '".links($_POST['hp'])."',
                                                 `reg`        = '".((int)$userid)."',
                                                 `nachricht`  = '".up($_POST['eintrag'],1)."',
                                                 `ip`         = '".$userip."'");

                    setIpcheck("mgbid(".intval($_GET['id']).")");

                    $index = info(_usergb_entry_successful, "?action=user&amp;id=".$_GET['id']."&show=gb");
                }
            }
        } elseif($_GET['do'] == 'edit') {
                if($_POST['reg'] == $userid || permission('editusers'))
                {
                    if($_POST['reg'] == 0)
                    {
                         $addme = "`nick`       = '".up($_POST['nick'])."',
                                             `email`      = '".up($_POST['email'])."',
                                             `hp`         = '".links($_POST['hp'])."',";
                    }

                    $editedby = show(_edited_by, array("autor" => autor($userid),
                                                                                         "time" => date("d.m.Y H:i", time())._uhr));

                    $upd = db("UPDATE ".$db['usergb']."
                                         SET ".$addme."
                                                 `nachricht`  = '".up($_POST['eintrag'],1)."',
                                                 `reg`        = '".((int)$_POST['reg'])."',
                                                 `editby`     = '".addslashes($editedby)."'
                                         WHERE id = '".intval($_GET['gbid'])."'");

                    $index = info(_gb_edited, "?action=user&show=gb&id=".$_GET['id']);
                } else {
                    $index = error(_error_edit_post,1);
                }
        }
    } else{
            $index = error(_user_dont_exist,1);
    }
break;
case 'preview';
  header("Content-type: text/html; charset=utf-8");
  if($_GET['do'] == 'edit')
  {
    $qry = db("SELECT * FROM ".$db['usergb']."
               WHERE id = '".intval($_GET['gbid'])."'");
    $get = _fetch($qry);

    $get_id = '?';
    $get_userid = $get['reg'];
    $get_date = $get['datum'];

    if($get['reg'] == 0) $regCheck = true;
    $editby = show(_edited_by, array("autor" => cleanautor($userid),
                                     "time" => date("d.m.Y H:i", time())._uhr));
  } else {
    $get_id = cnt($db['usergb'], "WHERE user = ".intval($_GET['uid']))+1;
    $get_userid = $userid;
    $get_date = time();

    if(!$chkMe) $regCheck = true;
  }

  if($regCheck)
    {
    $get_hp = $_POST['hp'];
    $get_email = $_POST['email'];
    $get_nick = $_POST['nick'];

    $onoff = "";
    $avatar = "";
    $nick = show(_link_mailto, array("nick" => re($get_nick),
                                     "email" => eMailAddr($get_email)));
  } else {
    $get_hp = data('hp');
    $email = data('email');
    $onoff = onlinecheck($userid);
    $get_nick = autor($userid);
  }

  if($get_hp) $gbhp = show(_hpicon, array("hp" => links($get_hp)));
  else $gbhp = "";

  if($get_email) $gbemail = show(_emailicon, array("email" => eMailAddr($get_email)));
  else $gbemail = "";

  $titel = show(_eintrag_titel, array("postid" => $get_id,
                                                                          "datum" => date("d.m.Y", time()),
                                                                        "zeit" => date("H:i", time())._uhr,
                                      "edit" => $edit,
                                      "delete" => $delete));

  if($chkMe == 4) $posted_ip = $ip;
  else            $posted_ip = _logged;

    $index .= show("page/comments_show", array("titel" => $titel,
                                                                                "comment" => bbcode(re($_POST['eintrag']),1),
                                             "nick" => $get_nick,
                                             "hp" => $gbhp,
                                             "editby" => $editby,
                                             "email" => $gbemail,
                                             "avatar" => useravatar(),
                                             "onoff" => $onoff,
                                             "rank" => getrank($userid),
                                             "ip" => $posted_ip));

  echo '<table class="mainContent" cellspacing="1">'.$index.'</table>';
  exit;
break;
case 'editprofile';
  $where = _site_user_editprofil;
  if(!$chkMe)
  {
      $index = error(_error_have_to_be_logged, 1);
  } else {
    if($_GET['gallery'] == "delete")
    {
      $qrygl = db("SELECT * FROM ".$db['usergallery']."
                   WHERE user = '".$userid."'
                   AND id = '".intval($_GET['gid'])."'");
        while($getgl = _fetch($qrygl))
        {
        $qry = db("DELETE FROM ".$db['usergallery']."
                   WHERE id = '".intval($_GET['gid'])."'");

        $unlinkgallery = show(_gallery_edit_unlink, array("img" => $getgl['pic'],
                                                          "user" => $userid));
        unlink($unlinkgallery);
      }

      $index = info(_info_edit_gallery_done, "?action=editprofile&show=gallery");

    } elseif($_GET['do'] == "edit")    {
            $check_user = db("SELECT id FROM ".$db['users']."
                                              WHERE user = '".intval($_POST['user'])."'
                                              AND id != '".$userid."'");
          $check_nick = db("SELECT id FROM ".$db['users']."
                                              WHERE nick = '".$_POST['nick']."'
                                              AND id != '".$userid."'");
          $check_email = db("SELECT id  FROM ".$db['users']."
                                               WHERE email = '".$_POST['email']."'
                                               AND id != '".$userid."'");

      if(empty($_POST['user']))
      {
            $index = error(_empty_user, 1);
        } elseif(empty($_POST['nick'])) {
          $index = error(_empty_nick, 1);
      } elseif(empty($_POST['email'])) {
            $index = error(_empty_email, 1);
        } elseif(!check_email($_POST['email'])) {
            $index = error(_error_invalid_email, 1);
        } elseif(_rows($check_user)) {
            $index = error(_error_user_exists, 1);
        } elseif(_rows($check_nick)) {
            $index = error(_error_nick_exists, 1);
        } elseif(_rows($check_email)) {
            $index = error(_error_email_exists, 1);
        } else {
            if ($_POST['pwd'])
            {
                if ($_POST['pwd'] == $_POST['cpwd'])
                {
                    $newpwd = "pwd = '".md5($_POST['pwd'])."',";
                    $index = info(_info_edit_profile_done, "?action=user&amp;id=".$userid."");
                    $_SESSION['pwd'] = md5($_POST['pwd']);
                }
                else
                {
                    $index = error(_error_passwords_dont_match, 1);
                }
            } else {
                $newpwd = "";
          $index = info(_info_edit_profile_done, "?action=user&amp;id=".$userid."");
            }

            $icq = preg_replace("=-=Uis","",$_POST['icq']);

        if($_POST['t'] && $_POST['m'] && $_POST['j']) $bday = cal($_POST['t']).".".cal($_POST['m']).".".$_POST['j'];
        if($_POST['steamid3']) $steamid = $_POST['steamid1'].":".$_POST['steamid2'].":".$_POST['steamid3'];

            $qrycustom = db("SELECT feldname,type FROM ".$db['profile']);
          while($getcustom = _fetch($qrycustom))
          {
              if($getcustom['type'] == 2) $customfields .= " ".$getcustom['feldname']." = '".links($_POST[$getcustom['feldname']])."', ";
              else $customfields .= " ".$getcustom['feldname']." = '".up($_POST[$getcustom['feldname']])."', ";
            }
        if($_POST['steamid'] != "STEAM_") $steamid = up(trim($_POST['steamid']));
           else $steamid = "";

          $qry = db("UPDATE ".$db['users']."
                       SET    ".$newpwd."
                        ".$customfields."
                  `country`      = '".$_POST['land']."',
                  `user`         = '".up($_POST['user'])."',
                  `nick`         = '".up($_POST['nick'])."',
                  `rlname`       = '".up($_POST['rlname'])."',
                  `sex`          = '".((int)$_POST['sex'])."',
                  `status`       = '".((int)$_POST['status'])."',
                  `bday`         = '".$bday."',
                  `email`        = '".up($_POST['email'])."',
                  `nletter`      = '".((int)$_POST['nletter'])."',
                  `pnmail`       = '".((int)$_POST['pnmail'])."',
                  `city`         = '".up($_POST['city'])."',
                  `gmaps_koord`  = '".up($_POST['gmaps_koord'])."',
                  `hp`           = '".links($_POST['hp'])."',
                  `icq`          = '".((int)$icq)."',
                  `hlswid`       = '".up(trim($_POST['hlswid']))."',
          `xboxid`       = '".up(trim($_POST['xboxid']))."',
                  `psnid`        = '".up(trim($_POST['psnid']))."',
          `originid`     = '".up(trim($_POST['originid']))."',
          `battlenetid`  = '".up(trim($_POST['battlenetid']))."',
                  `steamid`      = '".$steamid."',
          `skypename`    = '".up(trim($_POST['skypename']))."',
                  `signatur`     = '".up($_POST['sig'],1)."',
                  `beschreibung` = '".up($_POST['ich'],1)."',
                  `perm_gb`      = '".up($_POST['visibility_gb'])."',
                  `perm_gallery` = '".up($_POST['visibility_gallery'])."'
           WHERE id = ".$userid);
          }
      } elseif($_GET['do'] == "delete") {
                $qrydel = db("SELECT id,nick,email,hp FROM ".$db['users']."
                                            WHERE id = '".intval($userid)."'");
                $getdel = _fetch($qrydel);

                $qry = db("UPDATE ".$db['f_threads']."
                                     SET `t_nick`   = '".up($getdel['nick'])."',
                                             `t_email`  = '".up($getdel['email'])."',
                                             `t_hp`            = '".links($getdel['hp'])."',
                                             `t_reg`        = '0'
                                     WHERE t_reg = '".intval($getdel['id'])."'");

                $qry = db("UPDATE ".$db['f_posts']."
                                     SET `nick`   = '".up($getdel['nick'])."',
                                             `email`  = '".up($getdel['email'])."',
                                             `hp`            = '".links($getdel['hp'])."',
                                             `reg`        = '0'
                                     WHERE reg = '".intval($getdel['id'])."'");

                $qry = db("UPDATE ".$db['newscomments']."
                                     SET `nick`     = '".up($getdel['nick'])."',
                                             `email`    = '".up($getdel['email'])."',
                                             `hp`       = '".links($getdel['hp'])."',
                                             `reg`            = '0'
                                     WHERE reg = '".intval($getdel['id'])."'");

                $qry = db("UPDATE ".$db['acomments']."
                                     SET `nick`     = '".up($getdel['nick'])."',
                                             `email`    = '".up($getdel['email'])."',
                                             `hp`       = '".links($getdel['hp'])."',
                                             `reg`            = '0'
                                     WHERE reg = '".intval($getdel['id'])."'");

                $del = db("DELETE FROM ".$db['msg']."
                                     WHERE von = '".intval($getdel['id'])."'
                                     OR an = '".intval($getdel['id'])."'");

                $del = db("DELETE FROM ".$db['news']."
                                     WHERE autor = '".intval($getdel['id'])."'");

                $del = db("DELETE FROM ".$db['permissions']."
                                     WHERE user = '".intval($getdel['id'])."'");

                $del = db("DELETE FROM ".$db['squaduser']."
                                     WHERE user = '".intval($getdel['id'])."'");

                $del = db("DELETE FROM ".$db['buddys']."
                                     WHERE user = '".intval($getdel['id'])."'
                                     OR buddy = '".intval($getdel['id'])."'");

                $upd = db("UPDATE ".$db['usergb']."
                                     SET `reg` = 0
                                     WHERE reg = ".intval($getdel['id'])."");

                $del = db("DELETE FROM ".$db['userpos']."
                                     WHERE user = '".intval($getdel['id'])."'");

                $del = db("DELETE FROM ".$db['users']."
                                     WHERE id = '".intval($getdel['id'])."'");

                $del = db("DELETE FROM ".$db['userstats']."
                                     WHERE user = '".intval($getdel['id'])."'");

                foreach($picformat as $tmpendung)
                {
                    if(file_exists(basePath."/inc/images/uploads/userpics/".intval($getdel['id']).".".$tmpendung))
                    {
                        @unlink(basePath."/inc/images/uploads/userpics/".intval($getdel['id']).".".$tmpendung);
                    }
                    if(file_exists(basePath."/inc/images/uploads/useravatare/".intval($getdel['id']).".".$tmpendung))
                    {
                        @unlink(basePath."/inc/images/uploads/useravatare/".intval($getdel['id']).".".$tmpendung);
                    }
                }

                $index = info(_info_account_deletet, '../news/');
    } else {
      $qry = db("SELECT * FROM ".$db['users']."
                             WHERE id = '".$userid."'");
        $get = _fetch($qry);

        if($get['sex'] == "1") $sex = _pedit_male;
        elseif($get['sex'] == "2") $sex = _pedit_female;
        else $sex = _pedit_sex_ka;

        if($get['perm_gb'] == 1) $perm_gb = _pedit_perm_allow;
        else $perm_gb = _pedit_perm_deny;

        switch($get['perm_gallery'])
        {
            case 0: $perm_gallery = _pedit_perm_public;
            break;
            case 1: $perm_gallery = _pedit_perm_user;
            break;
            case 2: $perm_gallery = _pedit_perm_member;
            break;
        }

        if($get['status'] == 1) $status = _pedit_aktiv;
        else $status = _pedit_inaktiv;

        $qryl = db("SELECT * FROM ".$db['users']."
                                WHERE id = '".$userid."'");
        $getl = _fetch($qryl);

        if($getl['level'] == "1")
        {
            $clan = '<input type="hidden" name="status" value="1" />';
        } else {
          $qrycustom = db("SELECT * FROM ".$db['profile']."
                                     WHERE kid = '2' AND shown = '1'
                                     ORDER BY id ASC");
          while($getcustom = _fetch($qrycustom))
          {
              $qrycontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']."
                                     WHERE id = '".$userid."'" );
          $getcontent = _fetch($qrycontent);
              $custom_clan .= show(_profil_edit_custom, array("name" => pfields_name($getcustom['name']).":",
                                                                                       "feldname" => $getcustom['feldname'],
                                                                                      "value" => re($getcontent[$getcustom['feldname']])));
            }

          $clan = show($dir."/edit_clan", array("clan" => _profil_clan,
                                                                  "pstatus" => _profil_status,
                                                                  "pexclans" => _profil_exclans,
                                                                  "status" => $status,
                                                                  "exclans" => $get['ex'],
                                                                  "custom_clan" => $custom_clan));
      }

      list($steamid1,$steamid2,$steamid3) = explode(':', $get['steamid']);
        list($bdayday, $bdaymonth, $bdayyear) = explode('.', $get['bday']);

      if($_GET['show'] == "gallery")
      {
        $qrygl = db("SELECT * FROM ".$db['usergallery']."
                     WHERE user = '".$userid."'
                     ORDER BY id DESC");
          while($getgl = _fetch($qrygl))
          {
          $pic = show(_gallery_pic_link, array("img" => $getgl['pic'],
                                               "user" => $userid));
          $delete = show(_gallery_deleteicon, array("id" => $getgl['id']));
          $edit = show(_gallery_editicon, array("id" => $getgl['id']));
          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

          $gal .= show($dir."/edit_gallery_show", array("picture" => img_size("inc/images/uploads/usergallery"."/".$userid."_".$getgl['pic']),
                                                        "beschreibung" => bbcode($getgl['beschreibung']),
                                                        "class" => $class,
                                                        "delete" => $delete,
                                                        "edit" => $edit));
        }
        $show = show($dir."/edit_gallery", array("galleryhead" => _gallery_head,
                                                 "pic" => _gallery_pic,
                                                 "new" => _gallery_edit_new,
                                                 "del" => _deleteicon_blank,
                                                 "edit" => _editicon_blank,
                                                 "beschr" => _gallery_beschr,
                                                 "showgallery" => $gal));
      } else {
        $dropdown_age = show(_dropdown_date, array("day" => dropdown("day",$bdayday,1),
                                                              "month" => dropdown("month",$bdaymonth,1),
                                                     "year" => dropdown("year",$bdayyear,1)));

        $qrycustom = db("SELECT * FROM ".$db['profile']."
                                   WHERE kid = '1' AND shown = '1'
                         ORDER BY id ASC");
          while($getcustom = _fetch($qrycustom))
          {
              $qrycontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']."
                                      WHERE id = '".$userid."'
                                        LIMIT 1");
          $getcontent = _fetch($qrycontent);

          $custom_about .= show(_profil_edit_custom, array("name" => re(pfields_name($getcustom['name'])).":",
                                                                                        "feldname" => $getcustom['feldname'],
                                                                                       "value" => re($getcontent[$getcustom['feldname']])));
            }

        $qrycustom = db("SELECT * FROM ".$db['profile']."
                                    WHERE kid = '3' AND shown = '1'
                         ORDER BY id ASC");
          while($getcustom = _fetch($qrycustom))
          {
              $qrycontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']."
                                      WHERE id = '".$userid."'
                                        LIMIT 1");
          $getcontent = _fetch($qrycontent);
              $custom_contact .= show(_profil_edit_custom, array("name" => re(pfields_name($getcustom['name'])).":",
                                                                                          "feldname" => $getcustom['feldname'],
                                                                                         "value" => re($getcontent[$getcustom['feldname']])));
            }

            $qrycustom = db("SELECT * FROM ".$db['profile']."
                                   WHERE kid = '4' AND shown = '1'
                         ORDER BY id ASC");
          while($getcustom = _fetch($qrycustom))
          {
              $qrycontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']."
                                      WHERE id = '".$userid."'
                                        LIMIT 1");
              $getcontent = _fetch($qrycontent);
          $custom_favos .= show(_profil_edit_custom, array("name" => re(pfields_name($getcustom['name'])).":",
                                                                                       "feldname" => $getcustom['feldname'],
                                                                                       "value" => re($getcontent[$getcustom['feldname']])));
            }

            $qrycustom = db("SELECT * FROM ".$db['profile']."
                                    WHERE kid = '5' AND shown = '1'
                         ORDER BY id ASC");
          while($getcustom = _fetch($qrycustom))
          {
              $qrycontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']."
                                       WHERE id = '".$userid."'
                                        LIMIT 1");
              $getcontent = _fetch($qrycontent);

          $custom_hardware .= show(_profil_edit_custom, array("name" => re(pfields_name($getcustom['name'])).":",
                                                                                          "feldname" => $getcustom['feldname'],
                                                                                          "value" => re($getcontent[$getcustom['feldname']])));
            }

        if(!empty($get['icq']) && $get['icq'] != 0) $icq = $get['icq'];
        if($get['nletter'] == 1) $pnl = "checked=\"checked\"";
        if($get['pnmail'] == 1) $pnm = "checked=\"checked\"";

        $pic = userpic($get['id']);
        $avatar = useravatar($get['id']);
        if(!preg_match("#nopic#",$pic))
          $deletepic = "| "._profil_delete_pic;
        if(!preg_match("#noavatar#",$avatar))
          $deleteava = "| "._profil_delete_ava;
          $gmaps = show('membermap/geocoder', array('form' => 'editprofil'));


          if($userid == $rootAdmin) $delete = _profil_del_admin;
                else $delete = show("page/button_delete_account", array("id" => $get['id'],
                                                                                                                              "action" => "action=editprofile&amp;do=delete",
                                                                                                                              "value" => _button_title_del_account,
                                                                                                                              "del" => convSpace(_confirm_del_account)));

        $show = show($dir."/edit_profil", array("hardware" => _profil_hardware,
                                                "hphead" => _profil_hp,
                                                "visibility" => _pedit_visibility,
                                                "pvisibility_gb" => _pedit_visibility_gb,
                                                "pvisibility_gallery" => _pedit_visibility_gallery,
                                                "country" => show_countrys($get['country']),
                                                "pcountry" => _profil_country,
                                                "about" => _profil_about,
                                                "picturehead" => _profil_pic,
                                                "contact" => _profil_contact,
                                                "preal" => _profil_real,
                                                "pnick" => _nick,
                                                "pemail1" => _email,
                                                "php" => _hp,
                                                "pava" => _profil_avatar,
                                                "pbday" => _profil_bday,
                                                "psex" => _profil_sex,
                                                "pname" => _loginname,
                                                "ppwd" => _new_pwd,
                                                "cppwd" => _pwd2,
                                                "picq" => _icq,
                                                "psig" => _profil_sig,
                                                "ppic" => _profil_ppic,
                                                "phlswid" => _hlswid,
                        "xboxidl" => _xboxid,
                        "psnidl" => _psnid,
                        "skypeidl" => _skypeid,
                        "originidl" => _originid,
                        "battlenetidl" => _battlenetid,
                                                "pcity" => _profil_city,
                                                "city" => re($get['city']),
                                                "psteamid" => _steamid,
                        "v_steamid" => re($get['steamid']),
                        "skypename" => $get['skypename'],
                                                "nletter" => _profil_nletter,
                                                "pnmail" => _profil_pnmail,
                                                "pnl" => $pnl,
                                                "pnm" => $pnm,
                                                "pwd" => "",
                                                "dropdown_age" => $dropdown_age,
                                                "ava" => $avatar,
                                                "hp" => re($get['hp']),
                                                "gmaps" => $gmaps,
                                                "nick" => re($get['nick']),
                                                "name" => re($get['user']),
                                                "gmaps_koord" => re($get['gmaps_koord']),
                                                "rlname" => re($get['rlname']),
                                                "bdayday" => $bdayday,
                                                "bdaymonth" => $bdaymonth,
                                                "bdayyear" =>$bdayyear,
                                                "sex" => $sex,
                                                "email" => re($get['email']),
                                                "visibility_gb" => $perm_gb,
                                                "visibility_gallery" => $perm_gallery,
                                                "icqnr" => $icq,
                                                "sig" => re_bbcode($get['signatur']),
                                                "hlswid" => $get['hlswid'],
                        "xboxid" => $get['xboxid'],
                          "psnid" => $get['psnid'],
                        "originid" => $get['originid'],
                        "battlenetid" => $get['battlenetid'],
                                                "steamid1" => $steamid1,
                                                "steamid2" => $steamid2,
                                                "steamid3" => $steamid3,
                                                "clan" => $clan,
                                                "pic" => $pic,
                                                "editpic" => _profil_edit_pic,
                                                "editava" => _profil_edit_ava,
                                                "deleteava" => $deleteava,
                                                "deletepic" => $deletepic,
                                                "favos" => _profil_favos,
                                                "pich" => _profil_ich,
                                                "pposition" => _profil_position,
                                                "pstatus" => _profil_status,
                                                "position" => getrank($get['id']),
                                                "value" => _button_value_edit,
                                                "status" => $status,
                                                "lang" => $language,
                                                "sonst" => _profil_sonst,
                                                "custom_about" => $custom_about,
                                                "custom_contact" => $custom_contact,
                                                "custom_favos" => $custom_favos,
                                                "custom_hardware" => $custom_hardware,
                                                "ich" => re_bbcode($get['beschreibung']),
                                                "del" => _profil_del_account,
                                                "delete" => $delete));
      }

        $index = show($dir."/edit", array("profilhead" => _profil_edit_head,
                                        "editgallery" => _profil_edit_gallery_link,
                                        "editprofil" => _profil_edit_profil_link,
                                        "nick" => autor($get['id']),
                                        "show" => $show));
    }
  }
break;
case 'msg';
  $where = _site_msg;
  if(!$chkMe)
    {
        $index = error(_error_have_to_be_logged, 1);
    } else {
      if($_GET['do'] == "show")
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
      } elseif($_GET['do'] == "sendnewsdone") {
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
      } elseif($_GET['do'] == "showsended") {
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
      } elseif($_GET['do'] == "answer") {
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
                                              "lang" => $language,
                                              "bbcodehead" => _bbcode,
                                              "eintraghead" => _answer,
                                              "nick" => autor($get['von']),
                                              "zitat" => zitat(autor($get['von']),$get['nachricht'])));
      }
      } elseif($_GET['do'] == "pn") {
          if(!$chkMe)       $index = error(_error_have_to_be_logged);
          elseif($_GET['id'] == $userid) $index = error(_error_msg_self, 1);
          else {

      $titel = show(_msg_from_nick, array("nick" => data($userid,"nick")));

          $index = show($dir."/answer", array("von" => $userid,
                                              "an" => $_GET['id'],
                                              "titel" => $titel,
                                                "value" => _button_value_msg,
                                                "lang" => $language,
                                              "titelhead" => _titel,
                                              "headtitel" => _msg_titel,
                                              "nickhead" => _to,
                                              "bbcodehead" => _bbcode,
                                              "eintraghead" => _answer,
                                              "nick" => autor($_GET['id']),
                                              "zitat" => ""));
          }
      } elseif($_GET['do'] == "sendanswer") {
        if(empty($_POST['titel']))
          {
              $index = error(_empty_titel, 1);
          } elseif(empty($_POST['eintrag'])) {
              $index = error(_empty_eintrag, 1);
          } else {
              $qry = db("INSERT INTO ".$db['msg']."
                         SET `datum`      = '".((int)time())."',
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
      } elseif($_GET['do'] == "delete") {
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
      } elseif($_GET['do'] == "deletethis") {
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
    } elseif($_GET['do'] == "deletesended") {
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
      } elseif($_GET['do'] == "new") {
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
                                       "lang" => $language,
                                                                           "titelhead" => _titel,
                                                                           "titel" => _msg_titel,
                                                                            "nickhead" => _nick,
                                                                            "bbcodehead" => _bbcode,
                                                                            "eintraghead" => _eintrag,
                                                                            "posttitel" => "",
                                                                            "error" => "",
                                                                            "posteintrag" => ""));
      } elseif($_GET['do'] == "send") {
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
              if($get['id'] == $_POST['users']) $selected = "selected=\"selected\"";
                else $selected = "";

                $users .= show(_to_users, array("id" => $get['id'],
                                                                                "nick" => data("nick",$get['id']),
                                                                                "selected" => $selected));
            }

            $qry = db("SELECT id,user,buddy FROM ".$db['buddys']."
                                 WHERE user = ".$userid);
            while($get = _fetch($qry))
            {
                if($get['buddy'] == $_POST['buddys']) $selected = "selected=\"selected\"";
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
                                         "lang" => $language,
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
                           SET `datum`      = '".((int)time())."',
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
break;
case 'userlist';
  $where = _site_ulist;
  $entrys = cnt($db['users']," WHERE level != 0");

if($_GET['show'] == "search")
{
    $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,sex,icq,status,position,regdatum
                         FROM ".$db['users']."
             WHERE nick LIKE '%".$_GET['search']."%'
             AND level != 0
             ORDER BY nick
             LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist."");
} elseif($_GET['show'] == "bday") {
    $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,sex,icq,status,position,regdatum
                         FROM ".$db['users']."
             WHERE bday LIKE '".date("d", intval($_GET['time'])).".".date("m", intval($_GET['time'])).".____"."'
             AND level != 0
             ORDER BY nick
             LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist."");
} elseif($_GET['show'] == "newreg") {
      $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,
                      sex,icq,status,position,regdatum FROM ".$db['users']."
               WHERE regdatum > '".$_SESSION['lastvisit']."'
               AND level != '0'
                           ORDER BY regdatum DESC,nick
               LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist."");
  } elseif($_GET['show'] == "lastlogin") {
      $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,
                      sex,icq,status,position,regdatum FROM ".$db['users']."
               WHERE level != '0'
                           ORDER BY time DESC,nick
               LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist."");
  } elseif($_GET['show'] == "lastreg") {
      $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,sex,
                      icq,status,position,regdatum FROM ".$db['users']."
               WHERE level != '0'
                           ORDER BY regdatum DESC,nick
               LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist."");
  } elseif($_GET['show'] == "online") {
      $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,
                      sex,icq,status,position,time FROM ".$db['users']."
               WHERE level != '0'
                           ORDER BY time DESC,nick
               LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist."");
  } elseif($_GET['show'] == "country") {
      $qry = db("SELECT id,nick,level,email,hp,steamid,
                      hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,sex,icq,status,position,country FROM ".$db['users']."
               WHERE level != '0'
                           ORDER BY country,nick
               LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist."");
  } elseif($_GET['show'] == "sex") {
      $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,
                      bday,sex,icq,status,position FROM ".$db['users']."
               WHERE level != '0'
                           ORDER BY sex DESC
               LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist."");
  } elseif($_GET['show'] == "banned") {
      $qry = db("SELECT id,nick,level,email,hp,steamid,
                      hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,sex,icq,status,position FROM ".$db['users']."
               WHERE level = '0'
                           ORDER BY nick
               LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist."");
  } elseif(!empty($_GET['orderby']) && in_array($_GET['orderby'],array("nick","bday"))) {
    $tmp_orderby = $_GET['orderby'];
    if($_GET['orderby'] == "bday") $_GET['orderby'] = "bday_order";
    $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,
                      xboxid,psnid,originid,battlenetid,bday,sex,icq,status,position,STR_TO_DATE(bday, '%d.%m.%Y') AS bday_order FROM ".$db['users']."
               WHERE level != '0'
                           ORDER BY ".mysqli_real_escape_string($mysql, $_GET['orderby']." ".$_GET['order'])."
               LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist."");
    $_GET['orderby'] = $tmp_orderby;
  } else {
      $qry = db("SELECT id,nick,level,email,hp,steamid,hlswid,skypename,xboxid,psnid,originid,battlenetid,bday,sex,
               icq,status,position FROM ".$db['users']."
               WHERE level != '0'
                           ORDER BY level DESC,nick
               LIMIT ".($page - 1)*$maxuserlist.",".$maxuserlist."");

  }
    while($get = _fetch($qry))
    {
        $email = show(_emailicon, array("email" => eMailAddr($get['email'])));

        if(empty($get['hlswid'])) $hlsw = "-";
        else $hlsw = show(_hlswicon, array("id" => re($get['hlswid']),
                                                                         "img" => "1",
                                                                         "css" => ""));
    if(empty($get['xboxid'])) $xboxu = "-";
    else $xboxu = show(_xboxicon, array("id" => re($get['xboxid']),
                                        "img" => "1",
                                       "css" => ""));
    if(empty($get['psnid'])) $psnu = "-";
    else $psnu = show(_psnicon, array("id" => re($get['psnid']),
                                        "img" => "1",
                                       "css" => ""));
    if(empty($get['originid'])) $originu = "-";
    else $originu = show(_originicon, array("id" => re($get['originid']),
                                        "img" => "1",
                                       "css" => ""));
    if(empty($get['battlenetid'])) $battlenetu = "-";
    else $battlenetu = show(_battleneticon, array("id" => re($get['battlenetid']),
                                        "img" => "1",
                                       "css" => ""));
        if(empty($get['steamid'])) $steam = '-';
        else $steam = show(_steamicon, array("steamid" => $get['steamid']));

        if(empty($get['icq']))
        {
            $icq = "-";
        } else {
      $uin = show(_icqstatus, array("uin" => $get['icq']));
      $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$get['icq'].'" target="_blank">'.$uin.'</a>';
        }
      if(empty($get['skypename'])) $skypename = '-';
      else $skypename = "<a href=\"skype:".$get['skypename']."?chat\"><img src=\"http://mystatus.skype.com/smallicon/".$get['skypename']."\" style=\"border: none;\" width=\"16\" height=\"16\" alt=\"".$get['skypename']."\"      /></a>";
    if(empty($get['hp'])) $hp = "-";
    else $hp = show(_hpicon, array("hp" => $get['hp']));

        if($get['sex'] == "1")$sex = _maleicon;
        elseif($get['sex'] == "2")$sex = _femaleicon;
        else $sex = "-";

        if($get['status'] == 1) $getstatus = _aktiv_icon;
        else $getstatus = _inaktiv_icon;

        if(data("level",$get['id']) > 1) $status = $getstatus;
        else $status = "";

        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

    if(permission("editusers"))
    {
      $edit = show("page/button_edit", array("id" => "",
                                             "action" => "action=admin&amp;edit=".$get['id'],
                                             "title" => _button_title_edit));
      $edit = str_replace("&amp;id=","",$edit);
      $delete = show("page/button_delete", array("id" => $get['id'],
                                                 "action" => "action=admin&amp;do=delete",
                                                 "title" => _button_title_del));

    } else {
      $edit = "";
      $delete = "";
    }

        $userliste .= show($dir."/userliste_show", array("nick" => autor($get['id'],'','',10),
                                                                                                         "level" => getrank($get['id']),
                                                                                                         "status" => $status,
                                                                                                         "email" => $email,
                                                                                                         "age" => getAge($get['bday']),
                                                                                                         "mf" => $sex,
                                                                               "edit" => $edit,
                                                                                 "delete" => $delete,
                                                                                "class" => $class,
                                                                                                         "icq" => $icq,
                                                     "skypename" => $skypename,
                                                                                                         "icquin" => $get['icq'],
                                                                                                         "onoff" => onlinecheck($get['id']),
                                                                                                         "hp" => $hp,
                                                     "steam" => $steam,
                                                     "xboxu" => $xboxu,
                                                      "psnu" => $psnu,
                                                     "originu" => $originu,
                                                     "battlenetu" => $battlenetu,
                                                                                                         "hlsw" => $hlsw));
    }
  $orderby = empty($_GET['orderby']) ? "" : "&orderby".$_GET['orderby'];
  $orderby .= empty($_GET['order']) ? "" : "&order=".$_GET['order'];
  $seiten = nav($entrys,$maxuserlist,"?action=userlist&show=".$_GET['show']."".$orderby);

  if(permission("editusers"))
  {
    $edel = '<td class="contentMainTop" colspan="2">&nbsp;</td>';
  }

  if(isset($_GET['search']) && !empty($_GET['search']))
    $search = $_GET['search'];
  else $search = _nick;

    $index = show($dir."/userliste", array("userlistehead" => _userlist,
                                         "nickhead" => _nick,
                                         "normal" => _ulist_normal,
                                         "country" => _ulist_country,
                                         "sex" => _ulist_sex,
                                         "cnt" => $entrys." "._user,
                                         "lastreg" => _ulist_lastreg,
                                         "online" => _ulist_online,
                                         "age" => _ulist_age,
                                         "login" => _ulist_last_login,
                                         "bday" => _ulist_bday,
                                         "sort" => _ulist_sort,
                                         "banned" => _ulist_acc_banned,
                                         "edel" => $edel,
                                         "search" => $search,
                                         "value" => _button_value_search,
                                         "mficon" => _mficon_blank,
                                         "nav" => $seiten,
                                         "statushead" => _status,
                                         "emailicon" => _emailicon_blank,
                                         "addbuddyicon" => _addbuddyicon_blank,
                                         "agehead" => _profil_age,
                                         "icqicon" => _icqicon_blank,
                                         "pnicon" => _pnicon_blank,
                                         "hpicon" => _hpicon_blank,
                     "xboxicon" => _xboxicon_blank,
                     "psnicon" => _psnicon_blank,
                     "originicon" => _originicon_blank,
                     "battleneticon" => _battleneticon_blank,
                       "steamicon" => _steamicon_blank,
                                         "hlswicon" => _hlswicon_blank,
                                         "order_nick" => orderby('nick'),
                                         "order_age" => orderby('bday'),
                                         "show" => $userliste));
break;
case 'buddys';
  $where = _site_user_buddys;
  if(!$chkMe)
  {
      $index = error(_error_have_to_be_logged, 1);
  } else {
      $qry = db("SELECT * FROM ".$db['buddys']."
                           WHERE user = ".$userid);
    $too = "";
      while($get = _fetch($qry))
      {
        $pn = show(_pn_write, array("id" => $get['buddy'],
                                  "nick" => data("nick",$get['buddy'])));
          $delete = show(_buddys_delete, array("id" => $get['buddy']));

          $yesnocheck = db("SELECT * FROM ".$db['buddys']."
                                              where user = '".$get['buddy']."'
                        AND buddy = '".$userid."'");

          if(_rows($yesnocheck)) $too = _buddys_yesicon;
          else $too = _buddys_noicon;

      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $buddys .= show($dir."/buddys_show", array("nick" => autor($get['buddy']),
                                                                                               "onoff" => onlinecheck($get['buddy']),
                                                                                               "pn" => $pn,
                                                 "class" => $class,
                                                                                               "too" => $too,
                                                                                               "delete" => $delete));
      }

        $qry = db("SELECT id,nick FROM ".$db['users']."
               WHERE level != 0
                             ORDER BY nick");
        while($get = _fetch($qry))
        {
            $users .= show(_to_users, array("id" => $get['id'],
                                                                            "nick" => data("nick",$get['id'])));
        }

        $add = show("".$dir."/buddys_add", array("users" => $users,
                                             "value" => _button_value_addto));

        $index = show($dir."/buddys", array("buddyhead" => _buddyhead,
                                                                                "nick" => _nick,
                                                                                "pn" => _pnicon_blank,
                                                                                "mybuddys" => _buddys,
                                                                                "addbuddys" => _addbuddys,
                                                                                "buddynick" => _buddynick,
                                                                                "delete" => _deleteicon_blank,
                                                                                "too" => _yesno,
                                                                                "show" => $buddys,
                                                                                "add" => $add,
                                                                                "legende" => _legende,
                                                                                "yes" => _buddys_yesicon,
                                        "no" => _buddys_noicon,
                                                                                "legendeaddedtoo" => _buddys_legende_addedtoo,
                                        "legendedontaddedtoo" => _buddys_legende_dontaddedtoo));

    if($_GET['do'] == "add")
    {
        if($_POST['users'] == "-")
          {
              $index = error(_error_select_buddy, 1);
          } elseif($_POST['users'] == $userid) {
              $index = error(_error_buddy_self, 1);
          } elseif(!check_buddy($_POST['users'])) {
              $index = error(_error_buddy_already_in, 1);
          } else {
              $qry = db("INSERT INTO ".$db['buddys']."
                             SET `user`   = '".((int)$userid)."',
                       `buddy`  = '".((int)$_POST['users'])."'");

        $msg = show(_buddy_added_msg, array("user" => autor($userid)));
        $title = _buddy_title;

        $send = db("INSERT INTO ".$db['msg']."
                    SET `datum`     = '".((int)time())."',
                        `von`       = '0',
                        `an`        = '".((int)$_POST['users'])."',
                        `titel`     = '".up($title)."',
                        `nachricht` = '".up($msg, 1)."'");

              $index = info(_add_buddy_successful, "?action=buddys");
          }
    } elseif($_GET['do'] == "addbuddy") {
      if(isset($_GET['id'])) $user = $_GET['id'];
      else $user = $_POST['users'];

        if($user == "-")
          {
              $index = error(_error_select_buddy, 1);
          } elseif($user == $userid) {
              $index = error(_error_buddy_self, 1);
          } elseif(!check_buddy($user)) {
              $index = error(_error_buddy_already_in, 1);
          } else {
              $qry = db("INSERT INTO ".$db['buddys']."
                           SET `user`   = '".((int)$userid)."',
                       `buddy`  = '".((int)$user)."'");

        $msg = show(_buddy_added_msg, array("user" => addslashes(autor($userid))));
        $title = _buddy_title;

        $send = db("INSERT INTO ".$db['msg']."
                    SET `datum`     = '".((int)time())."',
                        `von`       = '0',
                        `an`        = '".((int)$user)."',
                        `titel`     = '".up($title)."',
                        `nachricht` = '".up($msg, 1)."'");

              $index = info(_add_buddy_successful, "?action=buddys");
          }
    } elseif($_GET['do'] == "delete") {
          $qry = db("DELETE FROM ".$db['buddys']."
                               WHERE buddy = ".intval($_GET['id'])."
                 AND user = '".$userid."'");

      $msg = show(_buddy_del_msg, array("user" => addslashes(autor($userid))));
      $title = _buddy_title;

      $send = db("INSERT INTO ".$db['msg']."
                  SET `datum`     = '".((int)time())."',
                      `von`       = '0',
                      `an`        = '".((int)$_GET['id'])."',
                      `titel`     = '".up($title)."',
                      `nachricht` = '".up($msg, 1)."'");

          $index = info(_buddys_delete_successful, "../user/?action=buddys");
      }
  }
break;
case 'language';
    if(isset($_GET['set'])) {
      if(file_exists(basePath."/inc/lang/languages/".$_GET['set'].".php"))
         set_cookie($prev.'language',$_GET['set']);
    }

    header("Location: ".$_SERVER['HTTP_REFERER']);
break;
case 'admin';
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

        if(_rows($check)) $sel = "selected=\"selected\"";
        else $sel = "";

        $posi .= show(_select_field_posis, array("value" => $getpos['id'],
                                                 "sel" => $sel,
                                                 "what" => re($getpos['position'])));
      }

      $qrysquser = db("SELECT squad FROM ".$db['squaduser']."
                       WHERE user = '".intval($_GET['edit'])."'
                       AND squad = '".$getsq['id']."'");

      if(_rows($qrysquser)) $check = "checked=\"checked\"";
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
  } elseif(data("level",intval($_GET['edit'])) == 4 && $userid != $rootAdmin) {
    $index = error(_error_edit_admin, 1);
  } else {
    if($do == "identy")
    {
      if(data("level",intval($_GET['id'])) == 4 && $userid != $rootAdmin)
      {
        $index = error(_identy_admin, 1);
      } else {
        $msg = show(_admin_user_get_identy, array("nick" => autor($_GET['id'])));
        $index = info($msg, "?action=user&amp;id=".$_GET['id']."");

        set_cookie($prev.'id','');
        set_cookie($prev.'pkey',"");

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
    } elseif($_GET['do'] == "update") {
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
    } elseif($_GET['do'] == "updateme") {
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
    } elseif($_GET['do'] == "delete") {
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
        if($get['level'] == 1)     $selu = "selected=\"selected\"";
        elseif($get['level'] == 2) $selt = "selected=\"selected\"";
        elseif($get['level'] == 3) $selm = "selected=\"selected\"";
        elseif($get['level'] == 4) $sela = "selected=\"selected\"";

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
            if(_rows($check)) $sel = "selected=\"selected\"";
            else $sel = "";

            $posi .= show(_select_field_posis, array("value" => $getpos['id'],
                                                     "sel" => $sel,
                                                     "what" => re($getpos['position'])));
          }

          $qrysquser = db("SELECT squad FROM ".$db['squaduser']."
                           WHERE user = '".intval($_GET['edit'])."'
                           AND squad = '".$getsq['id']."'");

          if(_rows($qrysquser))$check = "checked=\"checked\"";
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
break;
case 'get_steam_image';
    if(!fsockopen_support()) die(_fopen);
    $data=strtolower(trim($_GET['steam_id']));
    if ($data!='') {
        if (ereg('7656119', $data)) {
            $ret = $data;
        }
        else if (substr($data,0,7)=='steam_0') {
            $tmp=explode(':',$data);
            if ((count($tmp)==3) && is_numeric($tmp[1]) && is_numeric($tmp[2])) {
                $friendid=($tmp[2]*2)+$tmp[1]+1197960265728;
                $friendid='7656'.$friendid;
                $ret = $friendid;
            }
        }

        if ($ret!= null) {
            if($cache->check('xml_'.$ret)) {
                $steam_profile = simplexml_load_file("http://steamcommunity.com/profiles/".$ret."/?xml=1");
                $cache->set('xml_'.$ret, $steam_profile, 3600);
            }
            else
                $steam_profile = $cache->get('xml_'.$ret);
        }
        else
        {
            if($cache->check('xml_'.$data)) {
                $steam_profile = simplexml_load_file("http://steamcommunity.com/id/".str_replace('steam_','ERROR_POFILE_FIXED',$data)."/?xml=1");
                $cache->set('xml_'.$data, $steam_profile, 3600);
            }
            else
                $steam_profile = $cache->get('xml_'.$data);

            $ret = $steam_profile->steamID64;
        }

        if (empty($steam_profile->error) && $ret != "") {
            if($cache->check("steamsignature_".$ret)) {
            $image_cache = fileExists('http://steamsignature.com/profile/english/'.$ret.'.png');
            if($image_cache && !empty($image_cache))
                $cache->set("steamsignature_".$ret, bin2hex($image_cache), 3600);
            }
            else
                $image_cache = hextobin($cache->get("steamsignature_".$ret));
        }
        else {
            if($cache->check("steamsignature_error_pic")) {
                $image_cache = fileExists('http://steamsignature.com/profile/english/error_not_found.png');
                if($image_cache && !empty($image_cache))
                    $cache->set("steamsignature_error_pic", bin2hex($image_cache), 3600);
            }
            else
                $image_cache = hextobin($cache->get("steamsignature_error_pic"));
        }

        header('Content-Type: image/png');
        die($image_cache);
    }
break;

endswitch;
## SETTINGS ##
$whereami = preg_replace_callback("#autor_(.*?)$#",create_function('$id', 'return data("nick","$id[1]");'),$where);
$title = $pagetitle." - ".$whereami."";
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where,$time);

## OUTPUT BUFFER END ##
gz_output();
