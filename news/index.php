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
feed();
$where = _site_news;
$title = $pagetitle." - ".$where."";
$dir = "news";

## SECTIONS ##
if(check_internal_url())
    $index = error(_error_have_to_be_logged, 1);
else
{
    switch($action):
        default:
            if(!($kat = isset($_GET['kat']) ? intval($_GET['kat']) : 0)) {
                $navKat = 'lazy';
                $n_kat = '';
                $navWhere = "WHERE public = 1 ".(!permission("intnews") ? "AND `intern` = '0'" : '')."";
            } else {
                $n_kat = "AND kat = '".$kat."'";
                $navKat = $kat;
                $navWhere = "WHERE kat = '".$kat."' AND public = 1 ".(!permission("intnews") ? "AND `intern` = '0'" : '')."";
            }

            //Sticky News
            $qry = db("SELECT * FROM ".$db['news']."
                       WHERE sticky >= ".time()."
                       AND datum <= ".time()."
                       AND public = 1 ".(permission("intnews") ? "" : "AND `intern` = '0'")."
                       ".$n_kat."
                       ORDER BY datum DESC
                       LIMIT ".($page - 1)*config('m_news').",".config('m_news')."");

            $show_sticky = '';
            if(_rows($qry)) {
                while($get = _fetch($qry)) {
                    $getkat = db("SELECT katimg FROM ".$db['newskat']." WHERE id = '".$get['kat']."'",false,true);
                    $count = cnt($db['newscomments'], " WHERE news = '".$get['id']."'");

                    $comments = show(_news_comments, array("comments" => '0', "id" => $get['id']));
                    if($count >= 2)
                        $comments = show(_news_comments, array("comments" => $count, "id" => $get['id']));
                    else if($count == 1)
                        $comments = show(_news_comment, array("comments" => "1", "id" => $get['id']));

                    $klapp = "";
                    if($get['klapptext'])
                        $klapp = show(_news_klapplink, array("klapplink" => re($get['klapplink']),
                                                             "which" => "expand",
                                                             "id" => $get['id']));

                    $viewed = show(_news_viewed, array("viewed" => $get['viewed']));

                    $links1 = "";
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

                    $intern = $get['intern'] ? _votes_intern : "";

                    if(file_exists(basePath.'/inc/images/uploads/news/'.$get['id'].'.jpg'))
                        $newsimage = '../inc/images/uploads/news/'.$get['id'].'.jpg';
                    else
                        $newsimage = '../inc/images/newskat/'.$getkat['katimg'];

                    $show_sticky .= show($dir."/news_show", array("titel" => re($get['titel']),
                                                                  "kat" => $newsimage,
                                                                  "id" => $get['id'],
                                                                  "comments" => $comments,
                                                                  "showmore" => "",
                                                                  "dp" => "none",
                                                                  "dir" => $designpath,
                                                                  "nautor" => _autor,
                                                                  "intern" => $intern,
                                                                  "sticky" => _news_sticky,
                                                                  "ndatum" => _datum,
                                                                  "ncomments" => _news_kommentare.":",
                                                                  "klapp" => $klapp,
                                                                  "more" => bbcode($get['klapptext']),
                                                                  "viewed" => $viewed,
                                                                  "text" => bbcode($get['text']),
                                                                  "datum" => date("d.m.y H:i", $get['datum'])._uhr,
                                                                  "links" => $links,
                                                                  "autor" => autor($get['autor'])));
                }
            }

            //News
            $qry = db("SELECT * FROM ".$db['news']."
                       WHERE sticky < ".time()." AND datum <= ".time()." AND public = 1 ".(permission("intnews") ? "" : "AND `intern` = '0'")."
                       ".$n_kat."
                       ORDER BY datum DESC
                       LIMIT ".($page - 1)*config('m_news').",".config('m_news')."");
           $show = '';
           if(_rows($qry)) {
                while($get = _fetch($qry))
                {
                    $getkat = db("SELECT katimg FROM ".$db['newskat']." WHERE id = '".$get['kat']."'",false,true);
                    $count = cnt($db['newscomments'], " WHERE news = '".$get['id']."'");

                    $comments = show(_news_comments, array("comments" => '0', "id" => $get['id']));
                    if($count >= 2)
                        $comments = show(_news_comments, array("comments" => $count, "id" => $get['id']));
                    else if($count == 1)
                        $comments = show(_news_comment, array("comments" => "1", "id" => $get['id']));

                    $klapp = "";
                    if($get['klapptext'])
                        $klapp = show(_news_klapplink, array("klapplink" => re($get['klapplink']),
                                                             "which" => "expand",
                                                             "id" => $get['id']));

                    $viewed = show(_news_viewed, array("viewed" => $get['viewed']));

                    $links1 = "";
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

                    $intern = $get['intern'] ? _votes_intern : "";

                    if(file_exists(basePath.'/inc/images/uploads/news/'.$get['id'].'.jpg'))
                        $newsimage = '../inc/images/uploads/news/'.$get['id'].'.jpg';
                    else
                        $newsimage = '../inc/images/newskat/'.$getkat['katimg'];

                    $show .= show($dir."/news_show", array("titel" => re($get['titel']),
                                                           "kat" => $newsimage,
                                                           "id" => $get['id'],
                                                           "comments" => $comments,
                                                           "showmore" => "",
                                                           "dp" => "none",
                                                           "nautor" => _autor,
                                                           "dir" => $designpath,
                                                           "intern" => $intern,
                                                           "sticky" => "",
                                                           "ndatum" => _datum,
                                                           "ncomments" => _news_kommentare.":",
                                                           "klapp" => $klapp,
                                                           "more" => bbcode($get['klapptext']),
                                                           "viewed" => $viewed,
                                                           "text" => bbcode($get['text']),
                                                           "datum" => date("d.m.y H:i", $get['datum'])._uhr,
                                                           "links" => $links,
                                                           "autor" => autor($get['autor'])));
            }
        }

        $qrykat = db("SELECT * FROM ".$db['newskat']."");
        $kategorien = '';
        if(_rows($qrykat)) {
            while($getkat = _fetch($qrykat)) {
                $sel = (isset($_GET['kat']) && $_GET['kat'] == $getkat['id'] ? 'selected' : '');
                $kategorien .= "<option value='".$getkat['id']."' ".$sel.">".$getkat['kategorie']."</option>";
            }
        }

        $index = show($dir."/news", array("show" => $show,
                                          "show_sticky" => $show_sticky,
                                          "nav" => nav(cnt($db['news'],$navWhere),config('m_news'),"?kat=".$navKat,false),
                                          "kategorien" => $kategorien,
                                          "choose" => _news_kat_choose,
                                          "archiv" => _news_archiv));
    break;
    case 'show';
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

                $links1 = "";
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
                            $email = '<br />'.show(_emailicon_forum, array("email" => eMailAddr($getc['email'])));

                        $nick = show(_link_mailto, array("nick" => re($getc['nick']), "email" => eMailAddr($getc['email'])));
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
                                                           "lang" => $language,
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
            if(file_exists(basePath.'/inc/images/uploads/news/'.$get['id'].'.jpg'))
                $newsimage = '../inc/images/uploads/news/'.$get['id'].'.jpg';
            else
                $newsimage = '../inc/images/newskat/'.$getkat['katimg'];

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
                                                                             "lang" => $language,
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
                                                                                `nick`     = '".up($_POST['nick'])."',
                                                                                `email`    = '".up($_POST['email'])."',
                                                                                `hp`       = '".links($_POST['hp'])."',
                                                                                `reg`      = '".intval($userid)."',
                                                                                `comment`  = '".up($_POST['comment'],1)."',
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
                                       `comment`  = '".(isset($_POST['comment']) ? up($_POST['comment'],1) : '')."',
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
                                                                        "postemail" => $get['email'],
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
                                                                 "lang" => $language,
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
    break;
    case 'preview';
        header("Content-type: text/html; charset=utf-8");
        $getkat = db("SELECT katimg FROM ".$db['newskat']." WHERE id = '".intval($_POST['kat'])."'",false,true);

        $klapp = "";
        if($_POST['klapptitel']) {
            $klapp = show(_news_klapplink, array("klapplink" => re($_POST['klapptitel']),
                                                 "which" => "collapse",
                                                 "id" => 0));
        }

        $links1 = ""; $rel = "";
        if(!empty($_POST['url1'])) {
            $rel = _related_links;
            $links1 = show(_news_link, array("link" => re($_POST['link1']),
                                             "url" => links($_POST['url1'])));
        }

        $links2 = "";
        if(!empty($_POST['url2'])) {
            $rel = _related_links;
            $links2 = show(_news_link, array("link" => re($_POST['link2']),
                                             "url" => links($_POST['url2'])));
        }

        $links3 = "";
        if(!empty($_POST['url3'])) {
            $rel = _related_links;
            $links3 = show(_news_link, array("link" => re($_POST['link3']),
                                             "url" => links($_POST['url3'])));
        }

        $links = '';
        if(!empty($links1) || !empty($links2) || !empty($links3)) {
            $links = show(_news_links, array("link1" => $links1,
                                             "link2" => $links2,
                                             "link3" => $links3,
                                             "rel" => $rel));
        }

        $intern = ''; $sticky = '';
        if(isset($_POST['intern']) && $_POST['intern'] == 1) $intern = _votes_intern;
        if(isset($_POST['sticky']) && $_POST['sticky'] == 1) $sticky = _news_sticky;

        $newsimage = '../inc/images/newskat/'.re($getkat['katimg']);
        $viewed = show(_news_viewed, array("viewed" => '0'));
        $index = show($dir."/news_show_full", array("titel" => re($_POST['titel']),
                                               "kat" => $newsimage,
                                               "id" => '_prev',
                                               "comments" => _news_comments_prev,
                                               "showmore" => "",
                                               "dp" => "",
                                               "dir" => $designpath,
                                               "nautor" => _autor,
                                               "intern" => $intern,
                                               "sticky" => $sticky,
                                               "ndatum" => _datum,
                                               "ncomments" => _news_kommentare.":",
                                               "klapp" => $klapp,
                                               "more" => bbcode(re($_POST['morenews']),1),
                                               "viewed" => $viewed,
                                               "text" => bbcode(re($_POST['newstext']),1),
                                               "datum" => date("d.m.y H:i", time())._uhr,
                                               "links" => $links,
                                               "autor" => autor($_SESSION['id'])));

        echo '<table class="mainContent" cellspacing="1">'.$index.'</table>';
        exit;
    break;
    case 'compreview';
        header("Content-type: text/html; charset=utf-8");
        if($do == 'edit') {
            $get = db("SELECT * FROM ".$db['newscomments']." WHERE `id` = '".intval($_GET['cid'])."'",false,true);
            $get_id = '?';
            $get_userid = $get['reg'];
            $get_date = $get['datum'];
            $regCheck = !$get['reg'] ? false : true;
            $editedby = show(_edited_by, array("autor" => cleanautor($userid),
                                               "time" => date("d.m.Y H:i", time())._uhr));
        } else {
            $get_id = cnt($db['newscomments'], " WHERE news = ".intval($_GET['id']))+1;
            $get_userid = $userid;
            $get_date = time();
            $regCheck = $chkMe >= 1 ? true : false;
            $editedby = '';
        }

        $email = ""; $hp = "";
        if(!$regCheck) {
            $get_hp = isset($_POST['hp']) ? $_POST['hp'] : '';
            $get_email = isset($_POST['email']) ? $_POST['email'] : '';
            $get_nick = isset($_POST['nick']) ? $_POST['nick'] : '';

            if(!empty($get_hp))
                $hp = show(_hpicon_forum, array("hp" => links($get_hp)));

            if(!empty($get_email))
                $email = '<br />'.show(_emailicon_forum, array("email" => eMailAddr($get_email)));

            $onoff = "";
            $avatar = "";
            $nick = show(_link_mailto, array("nick" => re($get_nick),
                                             "email" => $get_email));
        } else {
            $onoff = onlinecheck($get_userid);
            $nick = cleanautor($get_userid);
        }

        $titel = show(_eintrag_titel, array("postid" => $get_id,
                                              "datum" => date("d.m.Y", $get_date),
                                              "zeit" => date("H:i", $get_date)._uhr,
                                              "edit" => '',
                                              "delete" => ''));

        $index = show("page/comments_show", array("titel" => $titel,
                                                  "comment" => bbcode(re($_POST['comment']),1),
                                                  "nick" => $nick,
                                                  "editby" => bbcode($editedby,1),
                                                  "email" => $email,
                                                  "hp" => $hp,
                                                  "avatar" => useravatar($get_userid),
                                                  "onoff" => $onoff,
                                                  "rank" => getrank($get_userid),
                                                  "ip" => $userip._only_for_admins));

        echo '<table class="mainContent" cellspacing="1">'.$index.'</table>';
        exit;
    break;
    case 'archiv':
        if(permission("intnews")) {
            $intern = "WHERE `public` = 1";
            $intern2 = "WHERE `intern` = 1 OR `intern` = 0 AND `datum` <= ".time()." AND `public` = 1";
        } else {
            $intern = "AND `intern` = 0 AND `public` = 1";
            $intern2 = "WHERE `intern` = 0 AND `datum` <= ".time()." AND `public` = 1";
        }

        if(isset($_GET['page'])) {
            $psearch = isset($_GET['search']) ? $_GET['search'] : '';
            $pyear = isset($_GET['year']) ? $_GET['year'] : '';
            $pmonth = isset($_GET['month']) ? $_GET['month'] : '';
        } else {
            $psearch = isset($_POST['search']) ? $_POST['search'] : '';
            $pyear = isset($_POST['year']) ? $_POST['year'] : '';
            $pmonth = isset($_POST['month']) ? $_POST['month'] : '';
        }

        $kat = isset($_GET['kat']) ? intval($_GET['kat']) : 0;
        $n_kat = !$kat ? "" : "AND kat = '".$kat."'";

        if(($search = isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : false)) {
            $qry = db("SELECT `id`,`titel`,`autor`,`datum`,`kat`,`text`
                      FROM ".$db['news']."
                      WHERE `text` LIKE '%".$search."%'
                      ".$intern."
                      AND `datum` <= ".time()."
                      OR `klapptext` LIKE '%".$search."%'
                      ".$intern."
                      AND `datum` <= ".time()."
                      ORDER BY `datum` DESC
                      LIMIT ".($page - 1)*config('m_archivnews').",".config('m_archivnews')."");

            $entrys = cnt($db['news'], " WHERE text LIKE '%".$search."%' OR klapptext LIKE '%".$search."%' ".$intern."");

        } else if($pyear) {
            $from = mktime(0,0,0,$pmonth,1,$pyear);
            $til = mktime(0,0,0,$pmonth+1,1,$pyear);

            $qry = db("SELECT id,titel,autor,datum,kat,text FROM ".$db['news']."
                       WHERE datum BETWEEN ".$from ." AND ".$til."
                       ".$intern."
                       ORDER BY datum DESC
                       LIMIT ".($page - 1)*config('m_archivnews').",".config('m_archivnews')."");
            $entrys = cnt($db['news'], " WHERE datum BETWEEN ".$from." AND ".$til." ".$intern."");
        } else if(!empty($_GET['orderby']) && in_array($_GET['orderby'],array("date","autor","titel","kat"))) {
            $qry = db("SELECT id,titel,autor,datum,kat,text FROM ".$db['news']."
                       ".$intern2."
                       ".$n_kat."
                       ORDER BY ".mysqli_real_escape_string($mysql, $_GET['orderby']." ".$_GET['order'])."
                       LIMIT ".($page - 1)*config('m_archivnews').",".config('m_archivnews')."");
            $entrys = cnt($db['news'], " ".$intern2." ".$n_kat);
        } else {
            $qry = db("SELECT id,titel,autor,datum,kat,text
                       FROM ".$db['news']."
                       ".$intern2."
                       ".$n_kat."
                       ORDER BY datum DESC
                       LIMIT ".($page - 1)*config('m_archivnews').",".config('m_archivnews')."");
            $entrys = cnt($db['news'], " ".$intern2." ".$n_kat);
        }

        $color = 0; $show = '';
        while($get = _fetch($qry)) {
            $getk = db("SELECT kategorie FROM ".$db['newskat']." WHERE id = '".$get['kat']."'",false,true);
            $comments = cnt($db['newscomments'], " WHERE news = ".$get['id']."");
            $titel = show(_news_show_link, array("titel" => cut(re($get['titel']),config('l_newsarchiv')), "id" => $get['id']));
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/archiv_show", array("autor" => autor($get['autor']),
                                                     "date" => date("d.m.y", $get['datum']),
                                                     "titel" => $titel,
                                                     "class" => $class,
                                                     "kat" => re($getk['kategorie']),
                                                     "comments" => $comments));
        }

        $y = db("SELECT datum FROM ".$db['news']." ".$intern2." ORDER BY datum LIMIT 1");
        $sy = _fetch($y);
        $min = date("Y",$sy['datum']);
        $ty = date("Y", time());

        $years = '';
        for($x=$min;$x<=$ty-1;$x++) {
            $sel = ($x == date("Y", time()) ? 'selected="selected"' : "");
            $years .= show(_select_field, array("value" => $x,
                                                "sel" => $sel,
                                                "what" => $x));
        }

        $endc = $language == "deutsch" ? 'n' : '';
        $ccount = cnt($db['newscomments']);
        $com = ($ccount == "1" ? _news_kommentar : _news_kommentare.$endc);

        $stats = show(_news_stats, array("news" => $entrys,
                                         "comments" => cnt($db['newscomments']),
                                         "com" => $com));

        $qrykat = db("SELECT * FROM ".$db['newskat'].""); $kategorien = '';
        while($getkat = _fetch($qrykat)) {
            $kategorien .= '<option value="'.$getkat['id'].'">-> '.$getkat['kategorie'].'</option>';
        }

        for($i=1;$i<=12;$i++) {
            if(!$pyear) {
                  if($i == date("n", time())) $sel[$i] = "selected=\"selected\"";
                  else $sel[$i] = "";
            } else {
                  if($i == nonum($pmonth)) $sel[$i] = "selected=\"selected\"";
                  else $sel[$i] = "";
            }
        }

        $orderby = empty($_GET['orderby']) ? "" : "&orderby".$_GET['orderby'];
        $orderby .= empty($_GET['order']) ? "" : "&order=".$_GET['order'];
        $nav = nav($entrys,config('m_archivnews'),"?action=archiv&year=".$pyear."&month=".$pmonth."&search=".$psearch."".$orderby);

        $index = show($dir."/archiv", array("head" => _news_archiv_head,
                                            "head_sort" => _news_archiv_sort,
                                            "date" => _datum,
                                            "titel" => _titel,
                                            "years" => $years,
                                            "nav" => $nav,
                                            "or" => _or,
                                            "kategorien" => $kategorien,
                                            "choose" => _news_kat_choose,
                                            "search" => re($search),
                                            "btn_search" => _button_value_search,
                                            "thisyear" => $ty,
                                            "kat" => _news_admin_kat,
                                            "order_date" => orderby('datum'),
                                            "order_titel" => orderby('titel'),
                                            "order_autor" => orderby('autor'),
                                            "order_kat" => orderby('kat'),
                                            "show" => $show,
                                            "stats" => $stats,
                                            "stichwort" => _stichwort,
                                            "autor" => _autor,
                                            "com" => _news_com,
                                            "jan" => _jan,
                                            "feb" => _feb,
                                            "mar" => _mar,
                                            "apr" => _apr,
                                            "mai" => _mai,
                                            "jun" => _jun,
                                            "jul" => _jul,
                                            "aug" => _aug,
                                            "sep" => _sep,
                                            "okt" => _okt,
                                            "nov" => _nov,
                                            "dez" => _dez,
                                            "sel01" => $sel[1],
                                            "sel02" => $sel[2],
                                            "sel03" => $sel[3],
                                            "sel04" => $sel[4],
                                            "sel05" => $sel[5],
                                            "sel06" => $sel[6],
                                            "sel07" => $sel[7],
                                            "sel08" => $sel[8],
                                            "sel09" => $sel[9],
                                            "sel10" => $sel[10],
                                            "sel11" => $sel[11],
                                            "sel12" => $sel[12]));
    break;
    endswitch;
}

## INDEX OUTPUT ##
$title = $pagetitle." - ".$where."";
page($index, $title, $where);

## OUTPUT BUFFER END ##
gz_output();