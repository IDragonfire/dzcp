<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_Clanwars')) {
    $qry = db("SELECT s1.id,s1.datum,s1.clantag,s1.gegner,s1.url,s1.xonx,s1.liga,s1.punkte,s1.gpunkte,s1.maps,s1.serverip,s1.servername,
               s1.serverpwd,s1.bericht,s1.squad_id,s1.gametype,s1.gcountry,s1.lineup,s1.glineup,s1.matchadmins,s2.icon,s2.name,s2.game
               FROM ".$db['cw']." AS s1
               LEFT JOIN ".$db['squads']." AS s2 ON s1.squad_id = s2.id
               WHERE s1.id = '".intval($_GET['id'])."'");

    if(!_rows($qry))
        $index = error(_cw_dont_exist,1);
    else {
        $get = _fetch($qry);  $serverpwd = ""; $serverpwd = ""; $players = "";
        if($chkMe != 1 && $chkMe >= 2 && $get['punkte'] == "0" && $get['gpunkte'] == "0") {
            if($get['datum'] > time()) {
                $qryp = db("SELECT status,member FROM ".$db['cw_player']." WHERE cwid = '".intval($_GET['id'])."' ORDER BY status");
                while($getp = _fetch($qryp))
                {
                    if($getp['status'] == "0")
                        $status = _cw_player_want;
                    elseif($getp['status'] == "1")
                        $status = _cw_player_dont_want;
                    else
                        $status = _cw_player_dont_know;

                    $sely = ""; $seln = ""; $selm = "";
                    if($getp['member'] == $userid) {
                        $sely = $getp['status'] == "0" ? 'checked="checked"' : '';
                        $seln = $getp['status'] == "1" ? 'checked="checked"' : '';
                        $selm = $getp['status'] == "2" ? 'checked="checked"' : '';
                    }

                    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                    $show_players .= show($dir."/players_show", array("nick" => autor($getp['member']),
                                                                      "class" => $class,
                                                                      "status" => $status));
                }

                $cntPlayers = cnt($db['cw_player'], " WHERE cwid = '".intval($_GET['id'])."' AND member = '".$userid."'", "cwid");
                $value = $cntPlayers ? _button_value_edit : _button_value_add; $form_player = "";
                if(db("SELECT id FROM ".$db['squaduser']." WHERE squad = '".$get['squad_id']."' AND user = '".$userid."'",true)) {
                    $form_player = show($dir."/form_player",array("id" => intval($_GET['id']),
                                                                   "admin" => (permission('clanwars') ? '<input id="contentSubmitAdmin" type="button" value="'._cw_reset_button.'" class="submit" onclick="DZCP.submitButton(\'contentSubmitAdmin\');DZCP.goTo(\'?action=resetplayers&amp;id='.intval($_GET['id']).'\')" />' : ''),
                                                                   "yes" => _yes,
                                                                   "no" => _no,
                                                                   "sely" => (empty($sely) && empty($seln) && empty($selm) ? 'checked="checked"' : $sely),
                                                                   "seln" => $seln,
                                                                   "selm" => $selm,
                                                                   "maybe" => _maybe,
                                                                   "value" => $value,
                                                                   "play" => _cw_players_play));
                }

                $players = show($dir."/players", array("show_players" => $show_players,
                                                       "nick" => _nick,
                                                       "status" => _status,
                                                       "head" => _cw_players_head,
                                                       "form_player" => $form_player));

                $serverpwd = show(_cw_serverpwd, array("cw_serverpwd" => re($get['serverpwd'])));
            }
        }

        $show = show(_cw_details_squad, array("game" => re($get['game']),
                                              "name" => re($get['name']),
                                              "id" => $get['squad_id'],
                                              "img" => squad($get['icon'])));
        $flagge = flag($get['gcountry']);
        $gegner = show(_cw_details_gegner_blank, array("gegner" => re($get['clantag']." - ".$get['gegner']),
                                                       "url" => !empty($get['url']) ? re($get['url']) : "#"));

        $server = show(_cw_details_server, array("servername" => re($get['servername']),
                                                 "serverip" => re($get['serverip'])));

        if($get['punkte'] == "0" && $get['gpunkte'] == "0")
            $result = _cw_no_results;
        else
            $result = cw_result_details($get['punkte'], $get['gpunkte']);

        $editcw = "";
        if(permission("clanwars")) {
            $editcw = show("page/button_edit_single", array("id" => $get['id'],
                                                            "action" => "action=admin&amp;do=edit",
                                                            "title" => _button_title_edit));
        }

        $bericht = $get['bericht'] ? bbcode($get['bericht']) : "&nbsp;";
        $libPath = "inc/images/clanwars/"; $cw_sc_loops = 0;
        $files = get_files(basePath."/inc/images/clanwars/",false,true,$picformat,false,array(),'minimize'); $cw_screenshots = array();
        if($files) {
            $file_id = 0;
            foreach ($files as $file) {
                if(preg_match("#^".intval($_GET['id'])."_(.*?).(gif|jpg|jpeg|png)#",strtolower($file))!=FALSE && strpos($file, '_logo') === false) {
                    $file_id++; $cw_screenshots[$file_id] = img_cw($libPath,$file);
                }
            }

            $cw_sc_loops = ceil($file_id/4); $sc1=1; $sc2=2; $sc3=3; $sc4=4; $sc5=5; $sc6=6; $sc7=7; $sc8=8; $sc9=9; $sc10=10; $show_sc = '';
            for ($i = 0; $i < $cw_sc_loops; $i++) {
                $show_sc .= show($dir."/show_screenshots", array("screen1" => (array_key_exists($sc1, $cw_screenshots) ? $cw_screenshots[$sc1] : ''),
                                                                 "screen2" => (array_key_exists($sc2, $cw_screenshots) ? $cw_screenshots[$sc2] : ''),
                                                                 "screen3" => (array_key_exists($sc3, $cw_screenshots) ? $cw_screenshots[$sc3] : ''),
                                                                 "screen4" => (array_key_exists($sc4, $cw_screenshots) ? $cw_screenshots[$sc4] : ''),
																 "screen5" => (array_key_exists($sc5, $cw_screenshots) ? $cw_screenshots[$sc5] : ''),
																 "screen6" => (array_key_exists($sc6, $cw_screenshots) ? $cw_screenshots[$sc6] : ''),
																 "screen7" => (array_key_exists($sc7, $cw_screenshots) ? $cw_screenshots[$sc7] : ''),
																 "screen8" => (array_key_exists($sc8, $cw_screenshots) ? $cw_screenshots[$sc8] : ''),
																 "screen9" => (array_key_exists($sc9, $cw_screenshots) ? $cw_screenshots[$sc9] : ''),
                                                                 "screen10" => (array_key_exists($sc10, $cw_screenshots) ? $cw_screenshots[$sc10] : ''),
																 "screenshot1" => (array_key_exists($sc1, $cw_screenshots) ? _cw_screenshot.' '.$sc1 : ''),
                                                                 "screenshot2" => (array_key_exists($sc2, $cw_screenshots) ? _cw_screenshot.' '.$sc2 : ''),
                                                                 "screenshot3" => (array_key_exists($sc3, $cw_screenshots) ? _cw_screenshot.' '.$sc3 : ''),
																 "screenshot4" => (array_key_exists($sc4, $cw_screenshots) ? _cw_screenshot.' '.$sc4 : ''),
																 "screenshot5" => (array_key_exists($sc5, $cw_screenshots) ? _cw_screenshot.' '.$sc5 : ''),
																 "screenshot6" => (array_key_exists($sc6, $cw_screenshots) ? _cw_screenshot.' '.$sc6 : ''),
																 "screenshot7" => (array_key_exists($sc7, $cw_screenshots) ? _cw_screenshot.' '.$sc7 : ''),
																 "screenshot8" => (array_key_exists($sc8, $cw_screenshots) ? _cw_screenshot.' '.$sc8 : ''),
                                                                 "screenshot9" => (array_key_exists($sc9, $cw_screenshots) ? _cw_screenshot.' '.$sc9 : ''),
																 "screenshot10" => (array_key_exists($sc10, $cw_screenshots) ? _cw_screenshot.' '.$sc10 : '')));
                $sc1 = $sc1+4; $sc2 = $sc2+4; $sc3 = $sc3+4; $sc4 = $sc4+4; $sc5 = $sc5+4; $sc6 = $sc6+4; $sc7 = $sc7+4; $sc8 = $sc8+4; $sc9 = $sc9+4; $sc10 = $sc10+4;
            }
        }

        $screens = $cw_sc_loops >= 1 ? show($dir."/screenshots", array("head" => _cw_screens, "show_screenshots" => $show_sc)) : '';
        $qryc = db("SELECT * FROM ".$db['cw_comments']."
                    WHERE cw = ".intval($_GET['id'])."
                    ORDER BY datum DESC
                    LIMIT ".($page - 1)*config('m_cwcomments').",".config('m_cwcomments')."");

        $entrys = cnt($db['cw_comments'], " WHERE cw = ".intval($_GET['id']));
        $i = $entrys-($page - 1)*config('m_cwcomments'); $comments = '';
        while($getc = _fetch($qryc)) {
            $edit = ""; $delete = "";
            if(($chkMe >= 1 && $getc['reg'] == $userid) || permission("clanwars")) {
                $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                              "action" => "action=details&amp;do=edit&amp;cid=".$getc['id'],
                                                              "title" => _button_title_edit));

                $delete = show("page/button_delete_single", array("id" => $_GET['id'],
                                                                  "action" => "action=details&amp;do=delete&amp;cid=".$getc['id'],
                                                                  "title" => _button_title_del,
                                                                  "del" => convSpace(_confirm_del_entry)));
            }

            $hp = ""; $email = ""; $onoff = ""; $avatar = "";
            if(!$getc['reg']) {
                $hp = $getc['hp'] ? show(_hpicon, array("hp" => $getc['hp'])) : '';
                $email = $getc['email'] ? '<br />'.show(_emailicon_forum, array("email" => eMailAddr($getc['email']))) : '';
                $nick = show(_link_mailto, array("nick" => re($getc['nick']), "email" => $getc['email']));
            } else {
                $onoff = onlinecheck($getc['reg']);
                $nick = autor($getc['reg']);
            }

            $titel = show(_eintrag_titel, array("postid" => $i,
                                                "datum" => date("d.m.Y", $getc['datum']),
                                                "zeit" => date("H:i", $getc['datum'])._uhr,
                                                "edit" => $edit,
                                                "delete" => $delete));

            $posted_ip = $chkMe == "4" ? $getc['ip'] : _logged;
            $comments .= show("page/comments_show", array("titel" => $titel,
                                                          "comment" => bbcode($getc['comment']),
                                                          "editby" => bbcode($getc['editby']),
                                                          "nick" => $nick,
                                                          "hp" => $hp,
                                                          "email" => $email,
                                                          "avatar" => useravatar($getc['reg']),
                                                          "onoff" => $onoff,
                                                          "rank" => getrank($getc['reg']),
                                                          "ip" => $posted_ip));
            $i--;
        }

        if(settings("reg_cwcomments") && !$chkMe)
            $add = _error_unregistered_nc;
        else {
            $add = "";
            if(!ipcheck("cwid(".$_GET['id'].")", config('f_cwcom'))) {
                if($userid >= 1)
                    $form = show("page/editor_regged", array("nick" => autor($userid), "von" => _autor));
                else {
                    $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                                "emailhead" => _email,
                                                                "hphead" => _hp,
                                                                "postemail" => '',
                                                                "posthp" => '',
                                                                "postnick" => ''));
                }

                $add = show("page/comments_add", array("titel" => _cw_comments_add,
                                                       "nickhead" => _nick,
                                                       "bbcodehead" => _bbcode,
                                                       "emailhead" => _email,
                                                       "hphead" => _hp,
                                                       "security" => _register_confirm,
                                                       "sec" => $dir,
                                                       "security" => _register_confirm,
                                                       "show" => "none",
                                                       "ip" => _iplog_info,
                                                       "preview" => _preview,
                                                       "action" => '?action=details&amp;do=add&amp;id='.$_GET['id'],
                                                       "prevurl" => '../clanwars/?action=compreview&amp;id='.$_GET['id'],
                                                       "id" => $_GET['id'],
                                                       "what" => _button_value_add,
                                                       "form" => $form,
                                                       "posteintrag" => "",
                                                       "error" => "",
                                                       "eintraghead" => _eintrag));
            }
        }

        $seiten = nav($entrys,config('m_cwcomments'),"?action=details&amp;id=".$_GET['id']."");
        $comments = show($dir."/comments",array("head" => _cw_comments_head,
                                                "show" => $comments,
                                                "seiten" => $seiten,
                                                "add" => $add));

        $logo_squad = '_defaultlogo.jpg'; $logo_gegner = '_defaultlogo.jpg';
        foreach($picformat AS $end) {
            if(file_exists(basePath.'/inc/images/clanwars/'.$get['id'].'_logo.'.$end)) {
                $logo_gegner = $get['id'].'_logo.'.$end;
                break;
            }
        }

        foreach($picformat AS $end) {
            if(file_exists(basePath.'/inc/images/squads/'.$get['squad_id'].'_logo.'.$end)) {
                $logo_squad = $get['squad_id'].'_logo.'.$end;
                break;
            }
        }

        $logos = ($logo_squad == '_defaultlogo.jpg') && ($logo_gegner == '_defaultlogo.jpg');
        $pagetitle = re($get['name']).' vs. '.re($get['gegner']).' - '.$pagetitle;

        $index = show($dir."/details", array("head" => _cw_head_details,
                                             "result_head" => _cw_head_results,
                                             "lineup_head" => _cw_head_lineup,
                                             "admin_head" => _cw_head_admin,
                                             "gametype_head" => _cw_head_gametype,
                                             "squad_head" => _cw_head_squad,
                                             "flagge" => $flagge,
                                             "br1" => ($logos ? '<!--' : ''),
                                             "br2" => ($logos ? '-->' : ''),
                                             "logo_squad" => $logo_squad,
                                             "logo_gegner" => $logo_gegner,
                                             "squad" => $show,
                                             "squad_name" => re($get['name']),
                                             "gametype" => empty($get['gametype']) ? '-' : re($get['gametype']),
                                             "lineup" => preg_replace("#\,#","<br />",re($get['lineup'])),
                                             "glineup" => preg_replace("#\,#","<br />",re($get['glineup'])),
                                             "match_admins" => empty($get['matchadmins']) ? '-' : re($get['matchadmins']),
                                             "datum" => _datum,
                                             "gegner" => _cw_head_gegner,
                                             "xonx" => _cw_head_xonx,
                                             "liga" => _cw_head_liga,
                                             "maps" => _cw_maps,
                                             "server" => _server,
                                             "result" => _cw_head_result,
                                             "players" => $players,
                                             "edit" => $editcw,
                                             "comments" => $comments,
                                             "bericht" => _cw_bericht,
                                             "serverpwd" => $serverpwd,
                                             "cw_datum" => date("d.m.Y H:i", $get['datum'])._uhr,
                                             "cw_gegner" => $gegner,
                                             "cw_xonx" => empty($get['xonx']) ? '-' : re($get['xonx']),
                                             "cw_liga" => empty($get['liga']) ? '-' : re($get['liga']),
                                             "cw_maps" => empty($get['maps']) ? '-' : re($get['maps']),
                                             "cw_server" => $server,
                                             "cw_result" => $result,
                                             "cw_bericht" => $bericht,
                                             "screenshots" => $screens));

        if($do == "add") {
            if(_rows(db("SELECT `id` FROM ".$db['cw']." WHERE `id` = '".(int)$_GET['id']."'")) != 0) {
                if(settings("reg_cwcomments") && !$chkMe )
                    $index = error(_error_have_to_be_logged, 1);
                else {
                    if(!ipcheck("cwid(".$_GET['id'].")", config('f_cwcom'))) {
                        if($userid >= 1)
                            $toCheck = empty($_POST['comment']);
                        else
                            $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['comment']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);

                        if($toCheck) {
                            if($userid >= 1) {
                                if(empty($_POST['comment']))
                                    $error = _empty_eintrag;

                                $form = show("page/editor_regged", array("nick" => autor($userid), "von" => _autor));
                            } else {
                                if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || empty($_SESSION['sec_'.$dir]))
                                    $error = _error_invalid_regcode;
                                elseif(empty($_POST['nick']))
                                    $error = _empty_nick;
                                elseif(empty($_POST['email']))
                                    $error = _empty_email;
                                elseif(!check_email($_POST['email']))
                                    $error = _error_invalid_email;
                                elseif(empty($_POST['comment']))
                                    $error = _empty_eintrag;

                                $form = show("page/editor_notregged", array("nickhead" => _nick,"emailhead" => _email,"hphead" => _hp));
                            }

                            $error = show("errors/errortable", array("error" => $error));
                            $index = show("page/comments_add", array("titel" => _cw_comments_add,
                                                                     "nickhead" => _nick,
                                                                     "bbcodehead" => _bbcode,
                                                                     "emailhead" => _email,
                                                                     "hphead" => _hp,
                                                                     "ip" => _iplog_info,
                                                                     "security" => _register_confirm,
                                                                     "what" => _button_value_add,
                                                                     "sec" => $dir,
                                                                     "form" => $form,
                                                                     "preview" => _preview,
                                                                     "action" => '?action=details&amp;do=add&amp;id='.$_GET['id'],
                                                                     "prevurl" => '../clanwars/?action=compreview&id='.$_GET['id'],
                                                                     "id" => $_GET['id'],
                                                                     "show" => "",
                                                                     "postemail" => isset($_POST['email']) ? $_POST['email'] : '',
                                                                     "posthp" => isset($_POST['hp']) ? links($_POST['hp']) : '',
                                                                     "postnick" => isset($_POST['nick']) ? re($_POST['nick']) : '',
                                                                     "posteintrag" => re_bbcode($_POST['comment']),
                                                                     "error" => $error,
                                                                     "eintraghead" => _eintrag));
                        } else {
                            db("INSERT INTO ".$db['cw_comments']."
                                SET `cw`       = '".((int)$_GET['id'])."',
                                    `datum`    = '".time()."',
                                    `nick`     = '".(isset($_POST['nick']) ? up($_POST['nick']) : up(data('nick')))."',
                                    `email`    = '".(isset($_POST['email']) ? up($_POST['email']) : up(data('email')))."',
                                    `hp`       = '".(isset($_POST['hp']) ? links($_POST['hp']) : links(data('hp')))."',
                                    `reg`      = '".((int)$userid)."',
                                    `comment`  = '".up($_POST['comment'],1)."',
                                    `ip`       = '".$userip."'");

                            setIpcheck("cwid(".$_GET['id'].")");
                            $index = info(_comment_added, "?action=details&amp;id=".$_GET['id']."");
                        }
                    }
                    else
                        $index = error(show(_error_flood_post, array("sek" => config('f_cwcom'))), 1);
                }
            }
            else
                $index = error(_id_dont_exist,1);
        }

        if($do == "delete") {
            $get = db("SELECT reg FROM ".$db['cw_comments']." WHERE id = '".intval($_GET['cid'])."'",false,true);
            if($get['reg'] == $userid || permission('clanwars'))
            {
                db("DELETE FROM ".$db['cw_comments']." WHERE id = '".intval($_GET['cid'])."'");
                $index = info(_comment_deleted, "?action=details&amp;id=".intval($_GET['id'])."");
            }
            else
                $index = error(_error_wrong_permissions, 1);
        } elseif($do == "editcom") {
            $get = db("SELECT * FROM ".$db['cw_comments']." WHERE id = '".intval($_GET['cid'])."'",false,true);
            if($get['reg'] == $userid || permission('clanwars')) {
                $editedby = show(_edited_by, array("autor" => autor($userid), "time" => date("d.m.Y H:i", time())._uhr));
                db("UPDATE ".$db['cw_comments']."
                    SET `nick`     = '".(isset($_POST['nick']) ? up($_POST['nick']) : up(data('nick')))."',
                        `email`    = '".(isset($_POST['email']) ? up($_POST['email']) : up(data('email')))."',
                        `hp`       = '".(isset($_POST['hp']) ? links($_POST['hp']) : links(data('hp')))."',
                        `comment`  = '".up($_POST['comment'],1)."',
                        `editby`   = '".addslashes($editedby)."'
                    WHERE id = '".intval($_GET['cid'])."'");
                $index = info(_comment_edited, "?action=details&amp;id=".$_GET['id']."");
            }
            else
                $index = error(_error_edit_post,1);
        } elseif($do == "edit") {
            $get = db("SELECT * FROM ".$db['cw_comments']." WHERE id = '".intval($_GET['cid'])."'",false,true);
            if($get['reg'] == $userid || permission('clanwars')) {
                if($get['reg'] != 0)
                    $form = show("page/editor_regged", array("nick" => autor($get['reg']), "von" => _autor));
                else {
                    $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                                "emailhead" => _email,
                                                                "hphead" => _hp,
                                                                "postemail" => re($get['email']),
                                                                "posthp" => links($get['hp']),
                                                                "postnick" => re($get['nick'])));
                }

                $index = show("page/comments_add", array("titel" => _comments_edit,
                                                         "nickhead" => _nick,
                                                         "bbcodehead" => _bbcode,
                                                         "emailhead" => _email,
                                                         "hphead" => _hp,
                                                         "security" => _register_confirm,
                                                         "sec" => $dir,
                                                         "form" => $form,
                                                         "preview" => _preview,
                                                         "prevurl" => '../clanwars/?action=compreview&do=edit&id='.$_GET['id'].'&amp;cid='.$_GET['cid'],
                                                         "action" => '?action=details&amp;do=editcom&amp;id='.$_GET['id'].'&amp;cid='.$_GET['cid'],
                                                         "ip" => _iplog_info,
                                                         "id" => $_GET['id'],
                                                         "what" => _button_value_edit,
                                                         "show" => "",
                                                         "posteintrag" => re_bbcode($get['comment']),
                                                         "error" => "",
                                                         "eintraghead" => _eintrag));
            }
            else
                $index = error(_error_edit_post,1);
        }
    }
}