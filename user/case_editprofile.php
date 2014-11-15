<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_UserMenu')) {
    $where = _site_user_editprofil;
    if (!$chkMe) {
        $index = error(_error_have_to_be_logged, 1);
    } else {
        if (isset($_GET['gallery']) && $_GET['gallery'] == "delete") {
            $qrygl = db("SELECT * FROM `" . $db['usergallery'] . "` WHERE `user` = " . $userid . " AND `id` = " . intval($_GET['gid']) . ";");
            if(_rows($qrygl)) {
                $getgl = _fetch($qrygl);
                $files = get_files(basePath."/inc/images/uploads/usergallery/",false,true,$picformat);
                foreach ($files as $file) {
                    $pic = explode('.', $getgl['pic']); $pic = $pic[0];
                    if(preg_match("#".$userid."_".$pic."_(.*?).(gif|jpg|jpeg|png)#",strtolower($file))!= FALSE) {
                        $res = preg_match("#".$userid."_".$pic."_(.*)#",$file,$match);
                        if (file_exists(basePath."/inc/images/uploads/usergallery/".$userid."_".$pic."_".$match[1])) {
                            unlink(basePath."/inc/images/uploads/usergallery/".$userid."_".$pic."_".$match[1]);
                        }
                    }
                }

                if (file_exists(basePath . '/inc/images/uploads/usergallery/'.$userid.'_'.$getgl['pic'])) {
                    unlink(basePath . '/inc/images/uploads/usergallery/'.$userid.'_'.$getgl['pic']);
                }
                
                db("DELETE FROM `" . $db['usergallery'] . "` WHERE `id` = " . intval($_GET['gid']) . ";");
            }

            $index = info(_info_edit_gallery_done, "?action=editprofile&show=gallery");
        } else {
            switch ($do) {
                case 'edit':
                    $check_user = db_stmt("SELECT id FROM " . $db['users'] . " WHERE `user`= ? AND `id` != ?", array('si', up($_POST['user']), $userid), true, false);
                    $check_nick = db_stmt("SELECT id FROM " . $db['users'] . " WHERE `nick`= ? AND `id` != ?", array('si', up($_POST['nick']), $userid), true, false);
                    $check_email = db_stmt("SELECT id FROM " . $db['users'] . " WHERE `email`= ? AND `id` != ?", array('si', up($_POST['email']), $userid), true, false);

                    if(!isset($_POST['user']) || empty($_POST['user'])) {
                        $index = error(_empty_user, 1);
                    } elseif (!isset($_POST['nick']) || empty($_POST['nick'])) {
                        $index = error(_empty_nick, 1);
                    } elseif (!isset($_POST['email']) || empty($_POST['email'])) {
                        $index = error(_empty_email, 1);
                    } elseif (!isset($_POST['email']) || !check_email($_POST['email'])) {
                        $index = error(_error_invalid_email, 1);
                    } elseif ($check_user) {
                        $index = error(_error_user_exists, 1);
                    } elseif ($check_nick) {
                        $index = error(_error_nick_exists, 1);
                    } elseif ($check_email) {
                        $index = error(_error_email_exists, 1);
                    } else {
                        $index = info(_info_edit_profile_done, "?action=user&amp;id=" . $userid . "");
                        $newpwd = "";
                        
                        if (isset($_POST['pwd'])) {
                            if ($_POST['pwd'] == $_POST['cpwd']) {
                                $_SESSION['pwd'] = md5($_POST['pwd']);
                                $newpwd = "pwd = '" . $_SESSION['pwd'] . "',";
                                $index = info(_info_edit_profile_done, "?action=user&amp;id=" . $userid . "");
                            } else {
                                $index = error(_error_passwords_dont_match, 1);
                            }
                        }

                        $icq = preg_replace("=-=Uis", "", $_POST['icq']);
                        $bday = ($_POST['t'] && $_POST['m'] && $_POST['j'] ? cal($_POST['t']) . "." . cal($_POST['m']) . "." . $_POST['j'] : 0);

                        $qrycustom = db("SELECT feldname,type FROM " . $db['profile']); $customfields = '';
                        while ($getcustom = _fetch($qrycustom)) {
                            $customfields .= " " . $getcustom['feldname'] . " = '" . ($getcustom['type'] == 2 ? links($_POST[$getcustom['feldname']]) : up($_POST[$getcustom['feldname']])) . "', ";
                        }

                        db("UPDATE " . $db['users'] . " SET " . $newpwd . "
                                                            " . $customfields . "
                                                            `country`      = '" . $_POST['land'] . "',
                                                            `user`         = '" . up($_POST['user']) . "',
                                                            `nick`         = '" . up($_POST['nick']) . "',
                                                            `rlname`       = '" . up($_POST['rlname']) . "',
                                                            `sex`          = '" . intval( $_POST['sex']) . "',
                                                            `status`       = '" . intval( $_POST['status']) . "',
                                                            `bday`         = '" . (!$bday ? 0 : strtotime($bday)) . "',
                                                            `email`        = '" . up($_POST['email']) . "',
                                                            `nletter`      = '" . intval( $_POST['nletter']) . "',
                                                            `pnmail`       = '" . intval( $_POST['pnmail']) . "',
                                                            `city`         = '" . up($_POST['city']) . "',
                                                            `gmaps_koord`  = '" . up($_POST['gmaps_koord']) . "',
                                                            `hp`           = '" . links($_POST['hp']) . "',
                                                            `icq`          = '" . intval( $icq) . "',
                                                            `hlswid`       = '" . up(trim($_POST['hlswid'])) . "',
                                                            `xboxid`       = '" . up(trim($_POST['xboxid'])) . "',
                                                            `psnid`        = '" . up(trim($_POST['psnid'])) . "',
                                                            `originid`     = '" . up(trim($_POST['originid'])) . "',
                                                            `battlenetid`  = '" . up(trim($_POST['battlenetid'])) . "',
                                                            `steamid`      = '" . up(trim($_POST['steamid'])) . "',
                                                            `skypename`    = '" . up(trim($_POST['skypename'])) . "',
                                                            `signatur`     = '" . up($_POST['sig']) . "',
                                                            `beschreibung` = '" . up($_POST['ich']) . "',
                                                            `perm_gb`      = '" . up($_POST['visibility_gb']) . "',
                                                            `perm_gallery` = '" . up($_POST['visibility_gallery']) . "'
                                                        WHERE id = " . $userid);
            }
                break;
                case 'delete':
                    if(!rootAdmin($userid)) {
                        $getdel = db("SELECT `id`,`nick`,`email`,`hp` FROM " . $db['users'] . " WHERE `id` = '" . intval($userid) . "'",false,true);
                        db("UPDATE " . $db['f_threads'] . " SET `t_nick`   = '" . $getdel['nick'] . "',
                                                                `t_email`  = '" . $getdel['email'] . "',
                                                                `t_hp`     = '" . links($getdel['hp']) . "',
                                                                `t_reg`    = 0,
                                                           WHERE t_reg     = " . $getdel['id'] . ";");

                        db("UPDATE " . $db['f_posts'] . " SET `nick`   = '" . $getdel['nick'] . "',
                                                              `email`  = '" . $getdel['email'] . "',
                                                              `hp`     = '" . links($getdel['hp']) . "',
                                                        WHERE `reg`    = " . $getdel['id'] . ";");

                        db("UPDATE " . $db['newscomments'] . " SET `nick`     = '" . $getdel['nick'] . "',
                                                                   `email`    = '" . $getdel['email'] . "',
                                                                   `hp`       = '" . links($getdel['hp']) . "',
                                                                   `reg`      = 0,
                                                             WHERE `reg`      = " . $getdel['id'] . ";");

                        db("UPDATE " . $db['acomments'] . " SET `nick`     = '" . $getdel['nick'] . "',
                                                                `email`    = '" . $getdel['email'] . "',
                                                                `hp`       = '" . links($getdel['hp']) . "',
                                                                `reg`      = 0,
                                                          WHERE `reg`      = " . $getdel['id'] . ";");

                        db("DELETE FROM " . $db['msg'] . " WHERE `von` = " . $getdel['id'] . "
                                                            OR   `an`  = " . $getdel['id'] . ";");

                        db("UPDATE " . $db['usergb'] . " SET `reg` = 0 WHERE `reg` = " . $getdel['id'] . ";");
                        db("DELETE FROM " . $db['news'] . " WHERE `autor` = " . $getdel['id'] . ";");
                        db("DELETE FROM " . $db['permissions'] . " WHERE `user` = " . $getdel['id'] . ";");
                        db("DELETE FROM " . $db['squaduser'] . " WHERE `user` = " . $getdel['id'] . ";");
                        db("DELETE FROM " . $db['buddys'] . " WHERE `user` = " . $getdel['id'] . "
                                                                OR `buddy` = " . $getdel['id'] . ";");
                        db("DELETE FROM " . $db['userpos'] . " WHERE `user` = " . $getdel['id'] . ";");
                        db("DELETE FROM " . $db['users'] . " WHERE `id` = " . $getdel['id'] . ";");
                        db("DELETE FROM " . $db['userstats'] . " WHERE `user` = " . $getdel['id'] . ";");
                        db("DELETE FROM " . $db['clicks_ips'] . " WHERE `uid` = " . $getdel['id']. ";");

                        $qrygl = db("SELECT * FROM `" . $db['usergallery'] . "` WHERE `user` = " . $getdel['id'] . ";");
                        if(_rows($qrygl)) {
                            while ($getgl = _fetch($qrygl)) {
                                $files = get_files(basePath."/inc/images/uploads/usergallery/",false,true,$picformat);
                                foreach ($files as $file) {
                                    $pic = explode('.', $getgl['pic']); $pic = $pic[0];
                                    if(preg_match("#".$getdel['id']."_".$pic."_(.*?).(gif|jpg|jpeg|png)#",strtolower($file))!= FALSE) {
                                        $res = preg_match("#".$getdel['id']."_".$pic."_(.*)#",$file,$match);
                                        if (file_exists(basePath."/inc/images/uploads/usergallery/".$getdel['id']."_".$pic."_".$match[1])) {
                                            unlink(basePath."/inc/images/uploads/usergallery/".$getdel['id']."_".$pic."_".$match[1]);
                                        }
                                    }
                                }

                                if (file_exists(basePath . '/inc/images/uploads/usergallery/'.$getdel['id'].'_'.$getgl['pic'])) {
                                    unlink(basePath . '/inc/images/uploads/usergallery/'.$getdel['id'].'_'.$getgl['pic']);
                                }
                                
                                db("DELETE FROM `" . $db['usergallery'] . "` WHERE `id` = " . $getgl['id'] . ";");
                            }
                        }
                        
                        $files = get_files(basePath."/inc/images/uploads/userpics/",false,true,$picformat);
                        foreach ($files as $file) {
                            if(preg_match("#".$getdel['id']."_(.*?).(gif|jpg|jpeg|png)#",strtolower($file))!= FALSE) {
                                $res = preg_match("#".$getdel['id']."_(.*)#",$file,$match);
                                if (file_exists(basePath."/inc/images/uploads/userpics/".$getdel['id']."_".$match[1])) {
                                    unlink(basePath."/inc/images/uploads/userpics/".$getdel['id']."_".$match[1]);
                                }
                            }
                        }
                        
                        $files = get_files(basePath."/inc/images/uploads/useravatare/",false,true,$picformat);
                        foreach ($files as $file) {
                            if(preg_match("#".$getdel['id']."_(.*?).(gif|jpg|jpeg|png)#",strtolower($file))!= FALSE) {
                                $res = preg_match("#".$getdel['id']."_(.*)#",$file,$match);
                                if (file_exists(basePath."/inc/images/uploads/useravatare/".$getdel['id']."_".$match[1])) {
                                    unlink(basePath."/inc/images/uploads/useravatare/".$getdel['id']."_".$match[1]);
                                }
                            }
                        }
                        
                        foreach ($picformat as $tmpendung) {
                            if (file_exists(basePath . "/inc/images/uploads/userpics/" . intval($getdel['id']) . "." . $tmpendung)) {
                                @unlink(basePath . "/inc/images/uploads/userpics/" . intval($getdel['id']) . "." . $tmpendung);
                            }

                            if (file_exists(basePath . "/inc/images/uploads/useravatare/" . intval($getdel['id']) . "." . $tmpendung)) {
                                @unlink(basePath . "/inc/images/uploads/useravatare/" . intval($getdel['id']) . "." . $tmpendung);
                            }
                        }

                        dzcp_session_destroy();
                        $index = info(_info_account_deletet, '../news/');
                    }
                break;
                default:
                    $get = db("SELECT * FROM `" . $db['users'] . "` WHERE `id` = " . $userid . ";", false, true);
                    switch(isset($_GET['show']) ? $_GET['show'] : '') {
                        case 'gallery':
                            $qrygl = db("SELECT * FROM `" . $db['usergallery'] . "` WHERE `user` = " . $userid . " ORDER BY id DESC"); $gal = ""; $color = 0;
                            while ($getgl = _fetch($qrygl)) {
                                $pic = show(_gallery_pic_link, array("img" => $getgl['pic'], "user" => $userid));
                                $delete = show(_gallery_deleteicon, array("id" => $getgl['id']));
                                $edit = show(_gallery_editicon, array("id" => $getgl['id']));
                                $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                                $gal .= show($dir . "/edit_gallery_show", array("picture" => img_size("inc/images/uploads/usergallery" . "/" . $userid . "_" . $getgl['pic']),
                                                                                "beschreibung" => bbcode($getgl['beschreibung']),
                                                                                "class" => $class,
                                                                                "delete" => $delete,
                                                                                "edit" => $edit));
                            }
                            
                            if(empty($gal))
                                $gal = '<tr><td colspan="3" class="contentMainSecond">'._no_entrys.'</td></tr>';

                            $show = show($dir . "/edit_gallery", array("galleryhead" => _gallery_head,
                                                                       "pic" => _gallery_pic,
                                                                       "new" => _gallery_edit_new,
                                                                       "del" => _deleteicon_blank,
                                                                       "edit" => _editicon_blank,
                                                                       "beschr" => _gallery_beschr,
                                                                       "showgallery" => $gal));
                        break;
                        case 'almgr':
                            switch ($do) {
                                case 'self_add':
                                    $permanent_key = md5(mkpwd(8));
                                    if(db_stmt("SELECT `id` FROM `".$db['autologin']."` WHERE `host` = ?", array('s', gethostbyaddr($userip)),true) >= 1) {
                                        //Update Autologin
                                        db_stmt("UPDATE `".$db['autologin']."` SET `ssid` = '".session_id()."',
                                                                                   `pkey` = '".$permanent_key."',
                                                                                   `ip` = '".$userip."',
                                                                                   `date` = ".time().",
                                                                                   `update` = ".time().",
                                                                                   `expires` = ".autologin_expire." WHERE `host` = ?", array('s', gethostbyaddr($userip)));
                                    } else {
                                        //Insert Autologin
                                        db_stmt("INSERT INTO `".$db['autologin']."` SET `uid` = ".$get['id'].",
                                                                                        `ssid` = '".session_id()."',
                                                                                        `pkey` = '".$permanent_key."',
                                                                                        `ip` = '".$userip."',
                                                                                        `name` = ?, 
                                                                                        `host` = ?,
                                                                                        `date` = ".time().",
                                                                                        `update` = 0,
                                                                                        `expires` = ".autologin_expire.";",array('ss', cut(gethostbyaddr($userip),20), gethostbyaddr($userip)));
                                    }
                                    
                                    cookie::put('id', $get['id']);
                                    cookie::put('pkey', $permanent_key);
                                    cookie::save(); unset($permanent_key);
                                    $index = info(_info_almgr_self_added, '../user/?action=editprofile&show=almgr');
                                break;
                                case 'self_remove':
                                    if(db_stmt("SELECT `id` FROM `".$db['autologin']."` WHERE `host` = ? AND `ssid` = ?", array('ss', gethostbyaddr($userip), session_id()),true) >= 1) {
                                        db("DELETE FROM `".$db['autologin']."` WHERE `ssid` = '".session_id()."';");
                                        cookie::delete('pkey');
                                        cookie::delete('id');
                                        cookie::save();
                                        $index = info(_info_almgr_self_deletet, '../user/?action=editprofile&show=almgr');
                                    }
                                break;
                                case 'almgr_delete':
                                    if(db_stmt("SELECT `id` FROM `".$db['autologin']."` WHERE `id` = ?", array('i', $_GET['id']),true) >= 1) {
                                        db("DELETE FROM `".$db['autologin']."` WHERE `id` = '".  intval($_GET['id'])."';");
                                        cookie::delete('pkey');
                                        cookie::delete('id');
                                        cookie::save();
                                        $index = info(_info_almgr_deletet, '../user/?action=editprofile&show=almgr');
                                    }
                                break;
                                case 'almgr_edit':
                                    $qry = db_stmt("SELECT * FROM `".$db['autologin']."` WHERE `id` = ?", array('i', $_GET['id']));
                                    if(_rows($qry) >= 1) {
                                        $get = _fetch($qry);
                                        $show = show($dir . "/edit_almgr_from", array("name" => re($get['name']),
                                                                                      "id" => $get['id'],
                                                                                      "host" => $get['host'],
                                                                                      "ip" => $get['ip'],
                                                                                      "ssid" => $get['ssid'],
                                                                                      "pkey" => $get['pkey'],
                                                                                      "value" => _button_value_edit));
                                    }
                                break;
                                case 'almgr_edit_save':
                                    if(db_stmt("SELECT id FROM `".$db['autologin']."` WHERE `id` = ?", array('i', $_GET['id']),true) >= 1) {
                                        db_stmt("UPDATE `".$db['autologin']."` SET `name` = ? WHERE `id` = ?", array('si', up($_POST['name']), $_GET['id']));
                                        $index = info(_almgr_editd, '../user/?action=editprofile&show=almgr');
                                    }
                                break;
                            }
                            
                            if(empty($index)) {
                                $qry = db("SELECT * FROM `".$db['autologin']."` WHERE `uid` = ".$userid.";"); $almgr = ""; $color = 0;
                                if(_rows($qry)) {
                                    while($get = _fetch($qry)) { 
                                        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                                        $almgr .= show($dir . "/edit_almgr_show", array("delete" => show(_almgr_deleteicon, array("id" => $get['id'])),
                                                                                        "edit" => show(_almgr_editicon, array("id" => $get['id'])),                                            
                                                                                        "class" => $class,
                                                                                        "name" => re($get['name']),
                                                                                        "host" => $get['host'],
                                                                                        "ip" => $get['ip'],
                                                                                        "create" => date('d.m.Y',$get['date']),
                                                                                        "lused" => !$get['update'] ? '-' : date('d.m.Y',$get['update']),
                                                                                        "expires" => date('d.m.Y',((!$get['update'] ? time() : $get['update'])+$get['expires']))));
                                    }
                                }

                                //Empty
                                if(empty($almgr))
                                    $almgr = '<tr><td colspan="6" class="contentMainSecond">'._no_entrys.'</td></tr>';

                                if(empty($show))
                                    $show = show($dir . "/edit_almgr", array("del" => _deleteicon_blank,"edit" => _editicon_blank,"showalmgr" => $almgr));
                            }
                        break;
                        default:
                            $sex = ($get['sex'] == 1 ? _pedit_male : ($get['sex'] == 2 ? _pedit_female : _pedit_sex_ka));
                            $perm_gb = ($get['perm_gb'] ? _pedit_perm_allow : _pedit_perm_deny);
                            $status = ($get['status'] ? _pedit_aktiv : _pedit_inaktiv);

                            switch ($get['perm_gallery']) {
                                case 0: $perm_gallery = _pedit_perm_public;
                                    break;
                                case 1: $perm_gallery = _pedit_perm_user;
                                    break;
                                case 2: $perm_gallery = _pedit_perm_member;
                                    break;
                            }

                            if ($get['level'] == 1) {
                                $clan = '<input type="hidden" name="status" value="1" />';
                            } else {
                                $qrycustom = db("SELECT `feldname`,`name` FROM " . $db['profile'] . " WHERE kid = 2 AND shown = 1 ORDER BY id ASC"); $custom_clan = "";
                                while ($getcustom = _fetch($qrycustom)) {
                                    $getcontent = db("SELECT " . $getcustom['feldname'] . " FROM " . $db['users'] . " WHERE id = " . $userid . ";",false,true);
                                    $custom_clan .= show(_profil_edit_custom, array("name" => pfields_name($getcustom['name']) . ":", 
                                                                                    "feldname" => $getcustom['feldname'],
                                                                                    "value" => re($getcontent[$getcustom['feldname']])));
                                }

                                $clan = show($dir . "/edit_clan", array("clan" => _profil_clan,
                                                                        "pstatus" => _profil_status,
                                                                        "pexclans" => _profil_exclans,
                                                                        "status" => $status,
                                                                        "exclans" => re($get['ex']),
                                                                        "custom_clan" => $custom_clan));
                            }

                            $bdayday = 0; $bdaymonth = 0; $bdayyear = 0;
                            if (!empty($get['bday']) && $get['bday'])
                                list($bdayday, $bdaymonth, $bdayyear) = explode('.', date('d.m.Y', $get['bday']));

                            $dropdown_age = show(_dropdown_date, array("day" => dropdown("day", $bdayday, 1),
                                                                       "month" => dropdown("month", $bdaymonth, 1),
                                                                       "year" => dropdown("year", $bdayyear, 1)));

                            $icq = (!empty($get['icq']) && $get['icq'] != 0 ? $get['icq'] : "");
                            $pnl = ($get['nletter'] ? 'checked="checked"' : '');
                            $pnm = ($get['pnmail'] ? 'checked="checked"' : '');
                            $gmaps = show('membermap/geocoder', array('form' => 'editprofil'));

                            $pic = userpic($get['id']); $deletepic = '';
                            if (!preg_match("#nopic#", $pic))
                                $deletepic = "| " . _profil_delete_pic;

                            $avatar = useravatar($get['id']); $deleteava = '';
                            if (!preg_match("#noavatar#", $avatar))
                                $deleteava = "| " . _profil_delete_ava;

                            if (rootAdmin($userid))
                                $delete = _profil_del_admin;
                            else
                                $delete = show("page/button_delete_account", array("id" => $get['id'],
                                                                                   "action" => "action=editprofile&amp;do=delete",
                                                                                   "value" => _button_title_del_account,
                                                                                   "del" => convSpace(_confirm_del_account)));

                            $show = show($dir . "/edit_profil", array("hardware" => _profil_hardware,
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
                                                                      "bdayyear" => $bdayyear,
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
                                                                      "sonst" => _profil_sonst,
                                                                      "custom_about" => getcustom(1),
                                                                      "custom_contact" => getcustom(3),
                                                                      "custom_favos" => getcustom(4),
                                                                      "custom_hardware" => getcustom(5),
                                                                      "ich" => re_bbcode($get['beschreibung']),
                                                                      "del" => _profil_del_account,
                                                                      "delete" => $delete));
                        break;
                    }

                    if(empty($index))
                        $index = show($dir . "/edit", array("profilhead" => _profil_edit_head,
                                                            "editgallery" => _profil_edit_gallery_link,
                                                            "editprofil" => _profil_edit_profil_link,
                                                            "editalmgr" => _profil_edit_almgr_link,
                                                            "nick" => autor($get['id']),
                                                            "show" => $show));
                break;
            }
        }
    }
}