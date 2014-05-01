<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_UserMenu')) {
    $where = _user_profile_of.'autor_'.$_GET['id'];
    if(!db("SELECT id FROM ".$db['users']." WHERE id = '".intval($_GET['id'])."'",true) ? true : false)
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
        $bday = (!$get['bday'] || empty($get['bday'])) ? "-" : date('d.m.Y',$get['bday']);

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
                $pos = '';
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
                while($getgl = _fetch($qrygl)) {
                    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                    $gal .= show($dir."/profil_gallery_show", array("picture" => img_size("inc/images/uploads/usergallery"."/".$qryperm['id']."_".$getgl['pic']),
                                                                    "beschreibung" => bbcode($getgl['beschreibung']),
                                                                    "class" => $class));
                }

                if(empty($gal))
                    $gal = show(_no_entrys_yet, array("colspan" => "3"));

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
                     LIMIT ".($page - 1)*config('m_usergb').",".config('m_usergb')."");

        $entrys = cnt($db['usergb'], " WHERE user = ".intval($_GET['id']));
        $i = $entrys-($page - 1)*config('m_usergb');

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

            if(empty($membergb))
                $membergb = show(_no_entrys_yet, array("colspan" => "1"));

            $add = "";
            if(!ipcheck("mgbid(".$_GET['id'].")", config('f_membergb'))) {
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

            $seiten = nav($entrys,config('m_usergb'),"?action=user&amp;id=".$_GET['id']."&show=gb");
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
            $steam = (!empty($get['steamid']) && steam_enable ? '<div id="infoSteam_'.md5(re($get['steamid'])).'"><div style="width:100%;text-align:center"><img src="../inc/images/ajax-loader-mini.gif" alt="" /></div><script language="javascript" type="text/javascript">DZCP.initDynLoader("infoSteam_'.md5(re($get['steamid'])).'","steam","&steamid='.re($get['steamid']).'");</script></div>' : '-');

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
                                                   "psteam" => _steam,
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
}