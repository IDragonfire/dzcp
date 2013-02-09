<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath . "/inc/config.php");
include(basePath . "/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
feed();
$where = _site_news;
$title = $pagetitle . " - " . $where . "";
$dir   = "news";
## SECTIONS ##
if (!isset($_GET['action']))
    $action = "";
else
    $action = $_GET['action'];

if (isset($_GET['page']))
    $page = $_GET['page'];
else
    $page = 1;
switch ($action):
    default:
        
        
        $kat = intval($_GET['kat']);
        if ($kat == "lazy" || empty($kat) || $kat == NULL) {
            $navKat   = 'lazy';
            $n_kat    = '';
            $navWhere = "WHERE public = 1 " . (!permission("intnews") ? "AND `intern` = '0'" : '') . "";
        } else {
            $n_kat    = "AND kat = '" . $kat . "'";
            $navKat   = $kat;
            $navWhere = "WHERE kat = '" . $kat . "' AND public = 1 " . (!permission("intnews") ? "AND `intern` = '0'" : '') . "";
        }
        
        if (!permission("intnews"))
            $sqlint = "AND `intern` = '0'";
        $qry = db("SELECT * FROM " . $db['news'] . "
             WHERE sticky >= " . time() . " AND datum <= " . time() . " AND public = 1 " . $sqlint . "
                         " . $n_kat . "
             ORDER BY datum DESC
                   LIMIT " . ($page - 1) * $maxnews . "," . $maxnews . "");
        while ($get = _fetch($qry)) {
            $qrykat = db("SELECT katimg FROM " . $db['newskat'] . "
                  WHERE id = '" . $get['kat'] . "'");
            $getkat = _fetch($qrykat);
            
            $c = cnt($db['newscomments'], " WHERE news = '" . $get['id'] . "'");
            
            if ($c == "1") {
                $comments = show(_news_comment, array(
                    "comments" => "1",
                    "id" => $get['id']
                ));
            } else {
                $comments = show(_news_comments, array(
                    "comments" => $c,
                    "id" => $get['id']
                ));
            }
            
            if ($get['klapptext']) {
                $klapp = show(_news_klapplink, array(
                    "klapplink" => re($get['klapplink']),
                    "which" => "expand",
                    "id" => $get['id']
                ));
            } else {
                $klapp = "";
            }
            
            $viewed = show(_news_viewed, array(
                "viewed" => $get['viewed']
            ));
            
            if (!empty($get['url1'])) {
                $rel    = _related_links;
                $links1 = show(_news_link, array(
                    "link" => re($get['link1']),
                    "url" => $get['url1']
                ));
            } else {
                $links1 = "";
            }
            if (!empty($get['url2'])) {
                $rel    = _related_links;
                $links2 = show(_news_link, array(
                    "link" => re($get['link2']),
                    "url" => $get['url2']
                ));
            } else {
                $links2 = "";
            }
            if (!empty($get['url3'])) {
                $rel    = _related_links;
                $links3 = show(_news_link, array(
                    "link" => re($get['link3']),
                    "url" => $get['url3']
                ));
            } else {
                $links3 = "";
            }
            
            if (!empty($links1) || !empty($links2) || !empty($links3)) {
                $links = show(_news_links, array(
                    "link1" => $links1,
                    "link2" => $links2,
                    "link3" => $links3,
                    "rel" => $rel
                ));
            } else {
                $links = "";
            }
            
            if ($get['intern'] == "1")
                $intern = _votes_intern;
            else
                $intern = "";
            
            $show_sticky .= show($dir . "/news_show", array(
                "titel" => re($get['titel']),
                "kat" => re($getkat['katimg']),
                "id" => $get['id'],
                "comments" => $comments,
                "showmore" => "",
                "dp" => "none",
                "dir" => $designpath,
                "nautor" => _autor,
                "intern" => $intern,
                "sticky" => _news_sticky,
                "ndatum" => _datum,
                "ncomments" => _news_kommentare . ":",
                "klapp" => $klapp,
                "more" => bbcode($get['klapptext']),
                "viewed" => $viewed,
                "text" => bbcode($get['text']),
                "datum" => date("d.m.y H:i", $get['datum']) . _uhr,
                "links" => $links,
                "autor" => autor($get['autor'])
            ));
        }
        
        
        if (!permission("intnews"))
            $sqlint = "AND `intern` = '0'";
        $qry = db("SELECT * FROM " . $db['news'] . "
             WHERE sticky < " . time() . " AND datum <= " . time() . " AND public = 1 " . $sqlint . "
             " . $n_kat . "
                         ORDER BY datum DESC
                   LIMIT " . ($page - 1) * $maxnews . "," . $maxnews . "");
        while ($get = _fetch($qry)) {
            $qrykat = db("SELECT katimg FROM " . $db['newskat'] . "
                  WHERE id = '" . $get['kat'] . "'");
            $getkat = _fetch($qrykat);
            
            $c = cnt($db['newscomments'], " WHERE news = '" . $get['id'] . "'");
            
            if ($c == "1") {
                $comments = show(_news_comment, array(
                    "comments" => "1",
                    "id" => $get['id']
                ));
            } else {
                $comments = show(_news_comments, array(
                    "comments" => $c,
                    "id" => $get['id']
                ));
            }
            
            if ($get['klapptext']) {
                $klapp = show(_news_klapplink, array(
                    "klapplink" => re($get['klapplink']),
                    "which" => "expand",
                    "id" => $get['id']
                ));
            } else {
                $klapp = "";
            }
            
            $viewed = show(_news_viewed, array(
                "viewed" => $get['viewed']
            ));
            
            if (!empty($get['url1'])) {
                $rel    = _related_links;
                $links1 = show(_news_link, array(
                    "link" => re($get['link1']),
                    "url" => $get['url1']
                ));
            } else {
                $links1 = "";
            }
            if (!empty($get['url2'])) {
                $rel    = _related_links;
                $links2 = show(_news_link, array(
                    "link" => re($get['link2']),
                    "url" => $get['url2']
                ));
            } else {
                $links2 = "";
            }
            if (!empty($get['url3'])) {
                $rel    = _related_links;
                $links3 = show(_news_link, array(
                    "link" => re($get['link3']),
                    "url" => $get['url3']
                ));
            } else {
                $links3 = "";
            }
            
            if (!empty($links1) || !empty($links2) || !empty($links3)) {
                $links = show(_news_links, array(
                    "link1" => $links1,
                    "link2" => $links2,
                    "link3" => $links3,
                    "rel" => $rel
                ));
            } else {
                $links = "";
            }
            
            if ($get['intern'] == "1")
                $intern = _votes_intern;
            else
                $intern = "";
            
            $show .= show($dir . "/news_show", array(
                "titel" => re($get['titel']),
                "kat" => re($getkat['katimg']),
                "id" => $get['id'],
                "comments" => $comments,
                "showmore" => "",
                "dp" => "none",
                "nautor" => _autor,
                "dir" => $designpath,
                "intern" => $intern,
                "sticky" => "",
                "ndatum" => _datum,
                "ncomments" => _news_kommentare . ":",
                "klapp" => $klapp,
                "more" => bbcode($get['klapptext']),
                "viewed" => $viewed,
                "text" => bbcode($get['text']),
                "datum" => date("d.m.y H:i", $get['datum']) . _uhr,
                "links" => $links,
                "autor" => autor($get['autor'])
            ));
        }
        
        $qrykat = db("SELECT * FROM " . $db['newskat'] . "");
        while ($getkat = _fetch($qrykat)) {
            if ($_GET['kat'] == $getkat['id'])
                $sel = 'selected';
            else
                $sel = "";
            
            $kategorien .= "<option value='" . $getkat['id'] . "' " . $sel . ">" . $getkat['kategorie'] . "</option>";
        }
        
        $index = show($dir . "/news", array(
            "show" => $show,
            "show_sticky" => $show_sticky,
            "stats" => $stats,
            "nav" => nav(cnt($db['news'], $navWhere), $maxnews, "?kat=" . $navKat, false),
            "kategorien" => $kategorien,
            "choose" => _news_kat_choose,
            "archiv" => _news_archiv
        ));
        break;
    case 'show';
        $update = db("UPDATE " . $db['news'] . "
                SET `viewed` = viewed+1
                WHERE id = '" . intval($_GET['id']) . "'");
        
        $check = db("SELECT intern FROM " . $db['news'] . "
               WHERE id = '" . intval($_GET['id']) . "'");
        $c     = _fetch($check);
        if ($c['intern'] == 1 && !permission("intnews")) {
            $index = error(_error_wrong_permissions, 1);
        } else {
            if (!permission("news")) {
                $shownews = " AND public = 1";
            }
            $qry = db("SELECT * FROM " . $db['news'] . "
               WHERE id = '" . intval($_GET['id']) . "'" . $shownews);
            if (_rows($qry) == 0) {
                $index = error(_id_dont_exist, 1);
            } else {
                $get = _fetch($qry);
                
                $qrykat = db("SELECT katimg FROM " . $db['newskat'] . "
                  WHERE id = '" . $get['kat'] . "'");
                $getkat = _fetch($qrykat);
                
                if ($get['klapptext']) {
                    $klapp = show(_news_klapplink, array(
                        "klapplink" => re($get['klapplink']),
                        "which" => "collapse",
                        "id" => $get['id']
                    ));
                } else {
                    $klapp = "";
                }
                
                $viewed = show(_news_viewed, array(
                    "viewed" => $get['viewed']
                ));
                
                if (!empty($get['url1'])) {
                    $rel    = _related_links;
                    $links1 = show(_news_link, array(
                        "link" => re($get['link1']),
                        "url" => $get['url1']
                    ));
                } else {
                    $links1 = "";
                }
                if (!empty($get['url2'])) {
                    $rel    = _related_links;
                    $links2 = show(_news_link, array(
                        "link" => re($get['link2']),
                        "url" => $get['url2']
                    ));
                } else {
                    $links2 = "";
                }
                if (!empty($get['url3'])) {
                    $rel    = _related_links;
                    $links3 = show(_news_link, array(
                        "link" => re($get['link3']),
                        "url" => $get['url3']
                    ));
                } else {
                    $links3 = "";
                }
                
                if (!empty($links1) || !empty($links2) || !empty($links3)) {
                    $links = show(_news_links, array(
                        "link1" => $links1,
                        "link2" => $links2,
                        "link3" => $links3,
                        "rel" => $rel
                    ));
                } else {
                    $links = "";
                }
                
                $qryc = db("SELECT * FROM " . $db['newscomments'] . "
                                 WHERE news = " . intval($_GET['id']) . "
                                ORDER BY datum DESC
                LIMIT " . ($page - 1) * $maxcomments . "," . $maxcomments . "");
                
                $entrys = cnt($db['newscomments'], " WHERE news = " . intval($_GET['id']));
                $i      = $entrys - ($page - 1) * $maxcomments;
                
                while ($getc = _fetch($qryc)) {
                    if (($chkMe != 'unlogged' && $getc['reg'] == $userid) || permission("news")) {
                        $edit   = show("page/button_edit_single", array(
                            "id" => $get['id'],
                            "action" => "action=show&amp;do=edit&amp;cid=" . $getc['id'],
                            "title" => _button_title_edit
                        ));
                        $delete = show("page/button_delete_single", array(
                            "id" => $get['id'],
                            "action" => "action=show&amp;do=delete&amp;cid=" . $getc['id'],
                            "title" => _button_title_del,
                            "del" => convSpace(_confirm_del_entry)
                        ));
                    } else {
                        $edit   = "";
                        $delete = "";
                    }
                    
                    if ($getc['reg'] == "0") {
                        if ($getc['hp'])
                            $hp = show(_hpicon_forum, array(
                                "hp" => $getc['hp']
                            ));
                        else
                            $hp = "";
                        if ($getc['email'])
                            $email = '<br />' . show(_emailicon_forum, array(
                                "email" => eMailAddr($getc['email'])
                            ));
                        else
                            $email = "";
                        $onoff  = "";
                        $avatar = "";
                        $nick   = show(_link_mailto, array(
                            "nick" => re($getc['nick']),
                            "email" => eMailAddr($getc['email'])
                        ));
                    } else {
                        $hp    = "";
                        $email = "";
                        $onoff = onlinecheck($getc['reg']);
                        $nick  = autor($getc['reg']);
                    }
                    
                    $titel = show(_eintrag_titel, array(
                        "postid" => $i,
                        "datum" => date("d.m.Y", $getc['datum']),
                        "zeit" => date("H:i", $getc['datum']) . _uhr,
                        "edit" => $edit,
                        "delete" => $delete
                    ));
                    
                    if ($chkMe == 4)
                        $posted_ip = $getc['ip'];
                    else
                        $posted_ip = _logged;
                    
                    $comments .= show("page/comments_show", array(
                        "titel" => $titel,
                        "comment" => bbcode($getc['comment']),
                        "nick" => $nick,
                        "hp" => $hp,
                        "editby" => bbcode($getc['editby']),
                        "email" => $email,
                        "avatar" => useravatar($getc['reg']),
                        "onoff" => $onoff,
                        "rank" => getrank($getc['reg']),
                        "ip" => $posted_ip
                    ));
                    $i--;
                }
                
                if (settings("reg_newscomments") == "1" && $chkMe == "unlogged") {
                    $add = _error_unregistered_nc;
                } else {
                    if (isset($userid)) {
                        $form = show("page/editor_regged", array(
                            "nick" => autor($userid),
                            "von" => _autor
                        ));
                    } else {
                        $form = show("page/editor_notregged", array(
                            "nickhead" => _nick,
                            "emailhead" => _email,
                            "hphead" => _hp
                        ));
                    }
                    
                    
                    if (!ipcheck("ncid(" . $_GET['id'] . ")", $flood_newscom)) {
                        $add = show("page/comments_add", array(
                            "titel" => _news_comments_write_head,
                            "bbcodehead" => _bbcode,
                            "form" => $form,
                            "show" => "none",
                            "what" => _button_value_add,
                            "ip" => _iplog_info,
                            "preview" => _preview,
                            "sec" => $dir,
                            "b1" => $u_b1,
                            "b2" => $u_b2,
                            "security" => _register_confirm,
                            "action" => '?action=show&amp;do=add&amp;id=' . $_GET['id'],
                            "prevurl" => '../news/?action=compreview&amp;id=' . $_GET['id'],
                            "lang" => $language,
                            "id" => $_GET['id'],
                            "postemail" => "",
                            "posthp" => "",
                            "postnick" => "",
                            "posteintrag" => "",
                            "error" => "",
                            "eintraghead" => _eintrag
                        ));
                    } else {
                        $add = "";
                    }
                }
                $seiten = nav($entrys, $maxcomments, "?action=show&amp;id=" . $_GET['id'] . "");
                
                $showmore = show($dir . "/comments", array(
                    "head" => _comments_head,
                    "show" => $comments,
                    "seiten" => $seiten,
                    "add" => $add
                ));
                
                if ($get['intern'] == "1")
                    $intern = _votes_intern;
                else
                    $intern = "";
                
                $title = re($get['titel']) . ' - ' . $title;
                $index = show($dir . "/news_show_full", array(
                    "titel" => re($get['titel']),
                    "kat" => re($getkat['katimg']),
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
                    "datum" => date("j.m.y H:i", (empty($get['datum']) ? time() : $get['datum'])) . _uhr,
                    "links" => $links,
                    "autor" => autor($get['autor'])
                ));
                
                if ($_GET['do'] == "add") {
                    if (_rows(db("SELECT `id` FROM " . $db['news'] . " WHERE `id` = '" . (int) $_GET['id'] . "'")) != 0) {
                        if (settings("reg_newscomments") == "1" && $chkMe == "unlogged") {
                            $index = error(_error_have_to_be_logged, 1);
                        } else {
                            if (!ipcheck("ncid(" . $_GET['id'] . ")", $flood_newscom)) {
                                if (isset($userid))
                                    $toCheck = empty($_POST['comment']);
                                else
                                    $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['comment']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_' . $dir] || empty($_SESSION['sec_' . $dir]);
                                
                                if ($toCheck) {
                                    if (isset($userid)) {
                                        if (empty($_POST['eintrag']))
                                            $error = _empty_eintrag;
                                        $form = show("page/editor_regged", array(
                                            "nick" => autor($userid),
                                            "von" => _autor
                                        ));
                                    } else {
                                        if (($_POST['secure'] != $_SESSION['sec_' . $dir]) || empty($_SESSION['sec_' . $dir]))
                                            $error = _error_invalid_regcode;
                                        elseif (empty($_POST['nick']))
                                            $error = _empty_nick;
                                        elseif (empty($_POST['email']))
                                            $error = _empty_email;
                                        elseif (!check_email($_POST['email']))
                                            $error = _error_invalid_email;
                                        elseif (empty($_POST['eintrag']))
                                            $error = _empty_eintrag;
                                        $form = show("page/editor_notregged", array(
                                            "nickhead" => _nick,
                                            "emailhead" => _email,
                                            "hphead" => _hp
                                        ));
                                    }
                                    
                                    $error = show("errors/errortable", array(
                                        "error" => $error
                                    ));
                                    $index = show("page/comments_add", array(
                                        "titel" => _news_comments_write_head,
                                        "nickhead" => _nick,
                                        "bbcodehead" => _bbcode,
                                        "emailhead" => _email,
                                        "security" => _register_confirm,
                                        "hphead" => _hp,
                                        "b1" => $u_b1,
                                        "b2" => $u_b2,
                                        "sec" => $dir,
                                        "form" => $form,
                                        "preview" => _preview,
                                        "prevurl" => '../news/?action=compreview&amp;id=' . $_GET['id'],
                                        "action" => '?action=show&amp;do=add&amp;id=' . $_GET['id'],
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
                                        "eintraghead" => _eintrag
                                    ));
                                } else {
                                    $qry = db("INSERT INTO " . $db['newscomments'] . "
                                                 SET `news`     = '" . ((int) $_GET['id']) . "',
                                                         `datum`    = '" . ((int) time()) . "',
                                                         `nick`     = '" . up($_POST['nick']) . "',
                                                         `email`    = '" . up($_POST['email']) . "',
                                                         `hp`       = '" . links($_POST['hp']) . "',
                                                         `reg`      = '" . ((int) $userid) . "',
                                                         `comment`  = '" . up($_POST['comment'], 1) . "',
                                                         `ip`       = '" . $userip . "'");
                                    
                                    $ncid = "ncid(" . $_GET['id'] . ")";
                                    $qry  = db("INSERT INTO " . $db['ipcheck'] . "
                                                 SET `ip`   = '" . $userip . "',
                                                         `what` = '" . $ncid . "',
                                                         `time` = '" . ((int) time()) . "'");
                                    
                                    $index = info(_comment_added, "?action=show&amp;id=" . $_GET['id'] . "");
                                }
                            } else {
                                $index = error(show(_error_flood_post, array(
                                    "sek" => $flood_newscom
                                )), 1);
                            }
                        }
                    } else {
                        $index = error(_id_dont_exist, 1);
                    }
                }
                
                if ($_GET['do'] == "delete") {
                    $qry = db("SELECT * FROM " . $db['newscomments'] . "
                 WHERE id = '" . intval($_GET['cid']) . "'");
                    $get = _fetch($qry);
                    
                    if ($get['reg'] == $userid || permission('news')) {
                        $qry = db("DELETE FROM " . $db['newscomments'] . "
                   WHERE id = '" . intval($_GET['cid']) . "'");
                        
                        $index = info(_comment_deleted, "?action=show&amp;id=" . $_GET['id'] . "");
                    } else {
                        $index = error(_error_wrong_permissions, 1);
                    }
                } elseif ($_GET['do'] == "editcom") {
                    $qry = db("SELECT * FROM " . $db['newscomments'] . "
                 WHERE id = '" . intval($_GET['cid']) . "'");
                    $get = _fetch($qry);
                    
                    if ($get['reg'] == $userid || permission('news')) {
                        $editedby = show(_edited_by, array(
                            "autor" => autor($userid),
                            "time" => date("d.m.Y H:i", time()) . _uhr
                        ));
                        $qry      = db("UPDATE " . $db['newscomments'] . "
                   SET `nick`     = '" . up($_POST['nick']) . "',
                       `email`    = '" . up($_POST['email']) . "',
                       `hp`       = '" . links($_POST['hp']) . "',
                       `comment`  = '" . up($_POST['comment'], 1) . "',
                       `editby`   = '" . addslashes($editedby) . "'
                   WHERE id = '" . intval($_GET['cid']) . "'");
                        
                        $index = info(_comment_edited, "?action=show&amp;id=" . $_GET['id'] . "");
                    } else {
                        $index = error(_error_edit_post, 1);
                    }
                } elseif ($_GET['do'] == "edit") {
                    $qry = db("SELECT * FROM " . $db['newscomments'] . "
                 WHERE id = '" . intval($_GET['cid']) . "'");
                    $get = _fetch($qry);
                    
                    if ($get['reg'] == $userid || permission('news')) {
                        if ($get['reg'] != 0) {
                            $form = show("page/editor_regged", array(
                                "nick" => autor($get['reg']),
                                "von" => _autor
                            ));
                        } else {
                            $form = show("page/editor_notregged", array(
                                "nickhead" => _nick,
                                "emailhead" => _email,
                                "hphead" => _hp,
                                "postemail" => $get['email'],
                                "posthp" => links($get['hp']),
                                "postnick" => re($get['nick'])
                            ));
                        }
                        
                        $index = show("page/comments_add", array(
                            "titel" => _comments_edit,
                            "nickhead" => _nick,
                            "security" => _register_confirm,
                            "bbcodehead" => _bbcode,
                            "emailhead" => _email,
                            "b1" => $u_b1,
                            "b2" => $u_b2,
                            "hphead" => _hp,
                            "form" => $form,
                            "sec" => $dir,
                            "preview" => _preview,
                            "prevurl" => '../news/?action=compreview&amp;do=edit&amp;id=' . $_GET['id'] . '&amp;cid=' . $_GET['cid'],
                            "action" => '?action=show&amp;do=editcom&amp;id=' . $_GET['id'] . '&amp;cid=' . $_GET['cid'],
                            "ip" => _iplog_info,
                            "lang" => $language,
                            "id" => $_GET['id'],
                            "what" => _button_value_edit,
                            "show" => "",
                            "posteintrag" => re_bbcode($get['comment']),
                            "error" => "",
                            "eintraghead" => _eintrag
                        ));
                    } else {
                        $index = error(_error_edit_post, 1);
                    }
                }
            }
        }
        break;
    case 'preview';
        header("Content-type: text/html; charset=utf-8");
        $qrykat = db("SELECT katimg FROM " . $db['newskat'] . "
                  WHERE id = '" . intval($_POST['kat']) . "'");
        $getkat = _fetch($qrykat);
        
        if ($_POST['klapptitel']) {
            $klapp = show(_news_klapplink, array(
                "klapplink" => re($_POST['klapptitel']),
                "which" => "collapse",
                "id" => 0
            ));
        } else {
            $klapp = "";
        }
        
        $viewed = show(_news_viewed, array(
            "viewed" => '0'
        ));
        
        if (!empty($_POST['url1'])) {
            $rel    = _related_links;
            $links1 = show(_news_link, array(
                "link" => re($_POST['link1']),
                "url" => links($_POST['url1'])
            ));
        } else {
            $links1 = "";
        }
        if (!empty($_POST['url2'])) {
            $rel    = _related_links;
            $links2 = show(_news_link, array(
                "link" => re($_POST['link2']),
                "url" => links($_POST['url2'])
            ));
        } else {
            $links2 = "";
        }
        if (!empty($_POST['url3'])) {
            $rel    = _related_links;
            $links3 = show(_news_link, array(
                "link" => re($_POST['link3']),
                "url" => links($_POST['url3'])
            ));
        } else {
            $links3 = "";
        }
        
        if (!empty($links1) || !empty($links2) || !empty($links3)) {
            $links = show(_news_links, array(
                "link1" => $links1,
                "link2" => $links2,
                "link3" => $links3,
                "rel" => $rel
            ));
        } else {
            $links = "";
        }
        
        if ($_POST['intern'] == 1)
            $intern = _intern;
        if ($_POST['sticky'] == 1)
            $sticky = _news_sticky;
        
        $index = show($dir . "/news_show", array(
            "titel" => re($_POST['titel']),
            "kat" => re($getkat['katimg']),
            "id" => '_prev',
            "comments" => _news_comments_prev,
            "showmore" => "",
            "dp" => "",
            "dir" => $designpath,
            "nautor" => _autor,
            "intern" => $intern,
            "sticky" => $sticky,
            "ndatum" => _datum,
            "ncomments" => _news_kommentare . ":",
            "klapp" => $klapp,
            "more" => bbcode($_POST['morenews'], 1),
            "viewed" => $viewed,
            "text" => bbcode($_POST['newstext'], 1),
            "datum" => date("d.m.y H:i", time()) . _uhr,
            "links" => $links,
            "autor" => autor($_SESSION['id'])
        ));
        
        echo '<table class="mainContent" cellspacing="1">' . $index . '</table>';
        exit;
        break;
    case 'compreview';
        header("Content-type: text/html; charset=utf-8");
        if ($_GET['do'] == 'edit') {
            $qry = db("SELECT * FROM " . $db['newscomments'] . "
               WHERE id = '" . intval($_GET['cid']) . "'");
            $get = _fetch($qry);
            
            $get_id     = '?';
            $get_userid = $get['reg'];
            $get_date   = $get['datum'];
            
            if ($get['reg'] == 0)
                $regCheck = false;
            else {
                $regCheck = true;
                $pUId     = $get['reg'];
            }
            
            $editedby = show(_edited_by, array(
                "autor" => cleanautor($userid),
                "time" => date("d.m.Y H:i", time()) . _uhr
            ));
        } else {
            $get_id     = cnt($db['newscomments'], " WHERE news = " . intval($_GET['id']) . "") + 1;
            $get_userid = $userid;
            $get_date   = time();
            
            if ($chkMe == 'unlogged')
                $regCheck = false;
            else {
                $regCheck = true;
                $pUId     = $userid;
            }
        }
        
        $get_hp    = $_POST['hp'];
        $get_email = $_POST['email'];
        $get_nick  = $_POST['nick'];
        
        if (!$regCheck) {
            if ($get_hp)
                $hp = show(_hpicon_forum, array(
                    "hp" => links($get_hp)
                ));
            if ($get_email)
                $email = '<br />' . show(_emailicon_forum, array(
                    "email" => eMailAddr($get_email)
                ));
            $onoff  = "";
            $avatar = "";
            $nick   = show(_link_mailto, array(
                "nick" => re($get_nick),
                "email" => $get_email
            ));
        } else {
            $hp    = "";
            $email = "";
            $onoff = onlinecheck($get_userid);
            $nick  = cleanautor($get_userid);
        }
        
        $titel = show(_eintrag_titel, array(
            "postid" => $get_id,
            "datum" => date("d.m.Y", $get_date),
            "zeit" => date("H:i", $get_date) . _uhr,
            "edit" => $edit,
            "delete" => $delete
        ));
        
        $index = show("page/comments_show", array(
            "titel" => $titel,
            "comment" => bbcode($_POST['comment'], 1),
            "nick" => $nick,
            "editby" => bbcode($editedby, 1),
            "email" => $email,
            "hp" => $hp,
            "avatar" => useravatar($get_userid),
            "onoff" => $onoff,
            "rank" => getrank($get_userid),
            "ip" => $userip . _only_for_admins
        ));
        
        echo '<table class="mainContent" cellspacing="1">' . $index . '</table>';
        exit;
        break;
    case 'archiv':
        if (permission("intnews")) {
            $intern  = "WHERE public = 1";
            $intern2 = "WHERE intern = 1 OR intern = 0 AND datum <= " . time() . " AND public = 1";
        } else {
            $intern  = "AND intern = 0 AND public = 1";
            $intern2 = "WHERE intern = 0 AND datum <= " . time() . " AND public = 1";
        }
        
        if (isset($_GET['page'])) {
            $psearch = $_GET['search'];
            $pyear   = $_GET['year'];
            $pmonth  = $_GET['month'];
        } else {
            $psearch = $_POST['search'];
            $pyear   = $_POST['year'];
            $pmonth  = $_POST['month'];
        }
        
        if (isset($_GET['page']))
            $page = $_GET['page'];
        else
            $page = 1;
        
        $kat = intval($_GET['kat']);
        if ($kat == "lazy" || $kat == "" || $kat == "NULL")
            $n_kat = "";
        else
            $n_kat = "AND kat = '" . $kat . "'";
        
        if ($search) {
            $qry    = db("SELECT id,titel,autor,datum,kat,text
                  FROM " . $db['news'] . "
                  WHERE text LIKE '%" . $search . "%'
                  " . $intern . "
                                    AND datum <= " . time() . "
                  OR klapptext LIKE '%" . $search . "%'
                  " . $intern . "
                                    AND datum <= " . time() . "
                  ORDER BY datum DESC
                  LIMIT " . ($page - 1) * $maxarchivnews . "," . $maxarchivnews . "");
            $entrys = cnt($db['news'], " WHERE text LIKE '%" . $search . "%' OR klapptext LIKE '%" . $search . "%' " . $intern . "");
            
        } elseif ($pyear) {
            $from = mktime(0, 0, 0, $pmonth, 1, $pyear);
            $til  = mktime(0, 0, 0, $pmonth + 1, 1, $pyear);
            
            $qry    = db("SELECT id,titel,autor,datum,kat,text
               FROM " . $db['news'] . "
               WHERE datum BETWEEN " . $from . " AND " . $til . "
               " . $intern . "
               ORDER BY datum DESC
               LIMIT " . ($page - 1) * $maxarchivnews . "," . $maxarchivnews . "");
            $entrys = cnt($db['news'], " WHERE datum BETWEEN " . $from . " AND " . $til . " " . $intern . "");
        } else {
            $qry    = db("SELECT id,titel,autor,datum,kat,text
               FROM " . $db['news'] . "
               " . $intern2 . "
               " . $n_kat . "
               ORDER BY datum DESC
               LIMIT " . ($page - 1) * $maxarchivnews . "," . $maxarchivnews . "");
            $entrys = cnt($db['news'], " " . $intern2 . " " . $n_kat);
        }
        
        while ($get = _fetch($qry)) {
            $qryk = db("SELECT kategorie FROM " . $db['newskat'] . "
                WHERE id = '" . $get['kat'] . "'");
            $getk = _fetch($qryk);
            
            $comments = cnt($db['newscomments'], " WHERE news = " . $get['id'] . "");
            $titel    = show(_news_show_link, array(
                "titel" => cut(re($get['titel']), $lnewsarchiv),
                "id" => $get['id']
            ));
            $class    = ($color % 2) ? "contentMainSecond" : "contentMainFirst";
            $color++;
            
            $show .= show($dir . "/archiv_show", array(
                "autor" => autor($get['autor']),
                "date" => date("d.m.y", $get['datum']),
                "titel" => $titel,
                "class" => $class,
                "kat" => re($getk['kategorie']),
                "comments" => $comments
            ));
        }
        
        $y   = db("SELECT datum FROM " . $db['news'] . "
           " . $intern2 . "
           ORDER BY datum
           LIMIT 1");
        $sy  = _fetch($y);
        $min = date("Y", $sy['datum']);
        $ty  = date("Y", time());
        
        for ($x = $min; $x <= $ty - 1; $x++) {
            if ($x == date("Y", time()))
                $sel = "selected=\"selected\"";
            else
                $sel = "";
            
            $years .= show(_select_field, array(
                "value" => $x,
                "sel" => $sel,
                "what" => $x
            ));
        }
        
        if ($language == "deutsch")
            $endc = "n";
        else
            $endc = "";
        
        $c = cnt($db['newscomments']);
        if ($c == "1")
            $com = _news_kommentar;
        else
            $com = _news_kommentare . $endc;
        
        $stats = show(_news_stats, array(
            "news" => $entrys,
            "comments" => cnt($db['newscomments']),
            "com" => $com
        ));
        
        $qrykat = db("SELECT * FROM " . $db['newskat'] . "");
        while ($getkat = _fetch($qrykat)) {
            $kategorien .= '<option value="' . $getkat['id'] . '">-> ' . $getkat['kategorie'] . '</option>';
        }
        
        for ($i = 1; $i <= 12; $i++) {
            if (!$pyear) {
                if ($i == date("n", time()))
                    $sel[$i] = "selected=\"selected\"";
                else
                    $sel[$i] = "";
            } else {
                if ($i == nonum($pmonth))
                    $sel[$i] = "selected=\"selected\"";
                else
                    $sel[$i] = "";
            }
            
        }
        
        $nav   = nav($entrys, $maxarchivnews, "?action=archiv&year=" . $pyear . "&month=" . $pmonth . "&search=" . $psearch . "");
        $index = show($dir . "/archiv", array(
            "head" => _news_archiv_head,
            "head_sort" => _news_archiv_sort,
            "date" => _datum,
            "titel" => _titel,
            "years" => $years,
            "nav" => $nav,
            "or" => _or,
            "kategorien" => $kategorien,
            "choose" => _news_kat_choose,
            "search" => re($_POST['search']),
            "btn_search" => _button_value_search,
            "thisyear" => $ty,
            "kat" => _news_admin_kat,
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
            "sel12" => $sel[12]
        ));
        break;
endswitch;
## SETTINGS ##
$time_end = generatetime();
$time     = round($time_end - $time_start, 4);
page($index, $title, $where, $time);
## OUTPUT BUFFER END ##
gz_output();
?>