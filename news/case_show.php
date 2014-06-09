<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_News')) {
        $check = db("SELECT intern FROM ".$db['news']." WHERE id = '".intval($_GET['id'])."'",false,true);

        if($check['intern'] && !permission("intnews"))
            $index = error(_error_wrong_permissions, 1);
        else
        {
            $qry = db("SELECT * FROM ".$db['news']." WHERE id = '".intval($_GET['id'])."'".(permission("news") ? "" : " AND public = 1") );
            if(_rows($qry) == 0)
                $index = error(_id_dont_exist,1);
            else
            {
                db("UPDATE ".$db['news']." SET `viewed` = viewed+1 WHERE id = '".intval($_GET['id'])."'");

                $get = _fetch($qry);
                $getkat = db("SELECT katimg FROM ".$db['newskat']." WHERE id = '".$get['kat']."'",false,true);

                $klapp = "";
                if($get['klapptext'])
                    $klapp = show(_news_klapplink, array("klapplink" => re($get['klapplink']),
                            "which" => "expand",
                            "id" => $get['id']));

                $viewed = show(_news_viewed, array("viewed" => $get['viewed']));

                $links1 = ""; $rel = "";
                if(!empty($get['url1'])) {
                    $rel = _related_links;
                    $links1 = show(_news_link, array("link" => re($get['link1']),
                            "url" => $get['url1']));
                }

                $links2 = "";
                if(!empty($get['url2'])) {
                    $rel = _related_links;
                    $links2 = show(_news_link, array("link" => re($get['link2']),
                            "url" => $get['url2']));
                }

                $links3 = "";
                if(!empty($get['url3'])) {
                    $rel = _related_links;
                    $links3 = show(_news_link, array("link" => re($get['link3']),
                            "url" => $get['url3']));
                }

                $links = "";
                if(!empty($links1) || !empty($links2) || !empty($links3))
                    $links = show(_news_links, array("link1" => $links1,
                            "link2" => $links2,
                            "link3" => $links3,
                            "rel" => $rel));

                $qryc = db("SELECT * FROM ".$db['newscomments']."
                            WHERE news = ".intval($_GET['id'])."
                            ORDER BY datum DESC
                            LIMIT ".($page - 1)*config('m_comments').",".config('m_comments')."");

                $entrys = cnt($db['newscomments'], " WHERE news = ".intval($_GET['id']));
                $i = $entrys-($page - 1)*config('m_comments');

                $comments = ''; $i = 0;
                while($getc = _fetch($qryc)) {
                    $edit = ""; $delete = "";
                    if(($chkMe >= 1 && $getc['reg'] == $userid) || permission("news")) {
                        $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                                      "action" => "action=show&amp;do=edit&amp;cid=".$getc['id'],
                                                                      "title" => _button_title_edit));

                        $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                                         "action" => "action=show&amp;do=delete&amp;cid=".$getc['id'],
                                                                         "title" => _button_title_del,
                                                                         "del" => convSpace(_confirm_del_entry)));
                    }

                    $email = ""; $hp = ""; $onoff = onlinecheck($getc['reg']); $nick = autor($getc['reg']); $avatar = ""; $onoff = "";
                    if($getc['reg'] == "0") {
                        if($getc['hp'])
                            $hp = show(_hpicon_forum, array("hp" => $getc['hp']));

                        if($getc['email'])
                            $email = '<br />'.show(_emailicon_forum, array("email" => eMailAddr(re($getc['email']))));

                        $nick = show(_link_mailto, array("nick" => re($getc['nick']), "email" => eMailAddr(re($getc['email']))));
                    }

                    $titel = show(_eintrag_titel, array("postid" => $i,
                                                        "datum" => date("d.m.Y", $getc['datum']),
                                                        "zeit" => date("H:i", $getc['datum'])._uhr,
                                                        "edit" => $edit,
                                                        "delete" => $delete));

                    $posted_ip = $chkMe == 4 ? $getc['ip'] : _logged;
                    $comments .= show("page/comments_show", array("titel" => $titel,
                                                                  "comment" => bbcode($getc['comment']),
                                                                  "nick" => $nick,
                                                                  "hp" => $hp,
                                                                  "editby" => bbcode($getc['editby']),
                                                                  "email" => $email,
                                                                  "avatar" => useravatar($getc['reg']),
                                                                  "onoff" => $onoff,
                                                                  "rank" => getrank($getc['reg']),
                                                                  "ip" => $posted_ip));
                $i--;
            }

            if(settings("reg_newscomments") && !$chkMe)
                $add = _error_unregistered_nc;
            else
            {
                if($userid >= 1)
                    $form = show("page/editor_regged", array("nick" => autor($userid), "von" => _autor));
                else
                    $form = show("page/editor_notregged", array("nickhead" => _nick, "emailhead" => _email, "hphead" => _hp));

                $add = '';
                if(!ipcheck("ncid(".$_GET['id'].")", config('f_newscom')))
                {
                    $add = show("page/comments_add", array("titel" => _news_comments_write_head,
                                                           "bbcodehead" => _bbcode,
                                                           "form" => $form,
                                                           "show" => "none",
                                                           "what" => _button_value_add,
                                                           "ip" => _iplog_info,
                                                           "preview" => _preview,
                                                           "sec" => $dir,
                                                           "security" => _register_confirm,
                                                           "action" => '?action=show&amp;do=add&amp;id='.$_GET['id'],
                                                           "prevurl" => '../news/?action=compreview&id='.$_GET['id'],
                                                           "id" => $_GET['id'],
                                                           "postemail" => "",
                                                           "posthp" => "",
                                                           "postnick" => "",
                                                           "posteintrag" => "",
                                                           "error" => "",
                                                           "eintraghead" => _eintrag));
                }
            }

            $seiten = nav($entrys,config('m_comments'),"?action=show&amp;id=".$_GET['id']."");
            $showmore = show($dir."/comments",array("head" => _comments_head,
                                                    "show" => $comments,
                                                    "seiten" => $seiten,
                                                    "add" => $add));

            $intern = $get['intern'] ? _votes_intern : "";
            $newsimage = '../inc/images/newskat/'.$getkat['katimg'];
            foreach($picformat as $tmpendung) {
                if(file_exists(basePath."/inc/images/uploads/news/".$get['id'].".".$tmpendung)) {
                    $newsimage = '../inc/images/uploads/news/'.$get['id'].'.'.$tmpendung;
                    break;
                }
            }

            $title = re($get['titel']).' - '.$title;
            $index = show($dir."/news_show_full", array("titel" => re($get['titel']),
                                                   "kat" => $newsimage,
                                                   "id" => $get['id'],
                                                   "comments" => "",
                                                   "dp" => "compact",
                                                   "nautor" => _autor,
                                                   "dir" => $designpath,
                                                   "ndatum" => _datum,
                                                   "rel" => $rel,
                                                   "sticky" => "",
                                                   "intern" => $intern,
                                                   "ncomments" => "",
                                                   "showmore" => $showmore,
                                                   "klapp" => $klapp,
                                                   "more" => bbcode($get['klapptext']),
                                                   "viewed" => "",
                                                   "text" => bbcode($get['text']),
                                                   "datum" => date("j.m.y H:i", (empty($get['datum']) ? time() : $get['datum']))._uhr,
                                                   "links" => $links,
                                                   "autor" => autor($get['autor'])));

            switch($do)
            {
                case 'add':
                    if(db("SELECT `id` FROM ".$db['news']." WHERE `id` = ".intval($_GET['id']),true,false) != 0)
                    {
                        if(settings("reg_newscomments") && !$chkMe)
                            $index = error(_error_have_to_be_logged, 1);
                        else {
                            if(!ipcheck("ncid(".$_GET['id'].")", config('f_newscom'))) {
                                if($userid >= 1)
                                    $toCheck = empty($_POST['comment']);
                                else
                                    $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['comment']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);

                                if($toCheck) {
                                    if($userid >= 1) {
                                        if(empty($_POST['eintrag'])) $error = _empty_eintrag;
                                        $form = show("page/editor_regged", array("nick" => autor($userid), "von" => _autor));
                                    } else {
                                        if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || empty($_SESSION['sec_'.$dir])) $error = _error_invalid_regcode;
                                        else if(empty($_POST['nick'])) $error = _empty_nick;
                                        else if(empty($_POST['email'])) $error = _empty_email;
                                        else if(!check_email($_POST['email'])) $error = _error_invalid_email;
                                        else if(empty($_POST['eintrag'])) $error = _empty_eintrag;
                                        $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                                                    "emailhead" => _email,
                                                                                    "hphead" => _hp));
                                    }

                                    $error = show("errors/errortable", array("error" => $error));
                                    $index = show("page/comments_add", array("titel" => _news_comments_write_head,
                                                                             "nickhead" => _nick,
                                                                             "bbcodehead" => _bbcode,
                                                                             "emailhead" => _email,
                                                                             "security" => _register_confirm,
                                                                             "hphead" => _hp,
                                                                             "sec" => $dir,
                                                                             "form" => $form,
                                                                             "preview" => _preview,
                                                                             "prevurl" => '../news/?action=compreview&amp;id='.$_GET['id'],
                                                                             "action" => '?action=show&amp;do=add&amp;id='.$_GET['id'],
                                                                             "ip" => _iplog_info,
                                                                             "id" => $_GET['id'],
                                                                             "what" => _button_value_add,
                                                                             "show" => "",
                                                                             "postemail" => $_POST['email'],
                                                                             "posthp" => links($_POST['hp']),
                                                                             "postnick" => re($_POST['nick']),
                                                                             "posteintrag" => re_bbcode($_POST['comment']),
                                                                             "error" => $error,
                                                                             "eintraghead" => _eintrag));
                                } else {
                                    db("INSERT INTO ".$db['newscomments']." SET `news`     = '".intval($_GET['id'])."',
                                                                                `datum`    = '".time()."',
                                                                                `nick`     = '".(isset($_POST['nick']) ? up($_POST['nick']) : data('nick'))."',
                                                                                `email`    = '".(isset($_POST['email']) ? up($_POST['email']) : data('email'))."',
                                                                                `hp`       = '".(isset($_POST['hp']) ? links($_POST['hp']) : links(data('hp')))."',
                                                                                `reg`      = '".intval($userid)."',
                                                                                `comment`  = '".up($_POST['comment'])."',
                                                                                `ip`       = '".$userip."'");

                                    setIpcheck("ncid(".intval($_GET['id']).")");
                                    $index = info(_comment_added, "?action=show&amp;id=".$_GET['id']."");
                                }
                            }
                            else
                                $index = error(show(_error_flood_post, array("sek" => config('f_newscom'))), 1);
                        }
                    }
                    else
                        $index = error(_id_dont_exist,1);
                break;
                case 'delete':
                    $get = db("SELECT `reg` FROM ".$db['newscomments']." WHERE `id` = '".($cid=intval($_GET['cid']))."'",false,true);
                    if($get['reg'] == $userid || permission('news')) {
                        db("DELETE FROM ".$db['newscomments']." WHERE `id` = '".$cid."'");
                        $index = info(_comment_deleted, "?action=show&amp;id=".$_GET['id']."");
                    }
                    else
                        $index = error(_error_wrong_permissions, 1);
                break;
                case 'editcom':
                    $get = db("SELECT `reg` FROM ".$db['newscomments']." WHERE `id` = '".($cid=intval($_GET['cid']))."'",false,true);
                    if($get['reg'] == $userid || permission('news')) {
                        $editedby = show(_edited_by, array("autor" => autor($userid), "time" => date("d.m.Y H:i", time())._uhr));
                        $qry = db("UPDATE ".$db['newscomments']."
                                   SET `nick`     = '".(isset($_POST['nick']) ? up($_POST['nick']) : '')."',
                                       `email`    = '".(isset($_POST['email']) ? up($_POST['email']) : '')."',
                                       `hp`       = '".(isset($_POST['hp']) ? links($_POST['hp']) : '')."',
                                       `comment`  = '".(isset($_POST['comment']) ? up($_POST['comment']) : '')."',
                                       `editby`   = '".addslashes($editedby)."'
                                   WHERE id = ".$cid);

                        $index = info(_comment_edited, "?action=show&amp;id=".$_GET['id']."");
                    }
                    else
                        $index = error(_error_edit_post,1);
                break;
                case 'edit':
                    $get = db("SELECT `reg`,`comment` FROM ".$db['newscomments']." WHERE `id` = '".intval($_GET['cid'])."'",false,true);
                    if($get['reg'] == $userid || permission('news')) {
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
                                                                 "security" => _register_confirm,
                                                                 "bbcodehead" => _bbcode,
                                                                 "emailhead" => _email,
                                                                 "hphead" => _hp,
                                                                 "form" => $form,
                                                                 "sec" => $dir,
                                                                 "preview" => _preview,
                                                                 "prevurl" => '../news/?action=compreview&do=edit&id='.$_GET['id'].'&cid='.$_GET['cid'],
                                                                 "action" => '?action=show&amp;do=editcom&amp;id='.$_GET['id'].'&amp;cid='.$_GET['cid'],
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
                break;
            }
        }
    }
}