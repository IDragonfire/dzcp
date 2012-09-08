<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$time_start = generatetime();
lang($language);
feed();
$where = _site_news;
$title = $pagetitle." - ".$where."";
$dir = "news";
$index = "";

## SECTIONS ##
$page = (isset($_GET['page']) ? $_GET['page'] : 1);
switch ((isset($_GET['action']) ? $_GET['action'] : '')):
default:
    $kat = (isset($_GET['kat']) ? intval($_GET['kat']) : false);

    if(!$kat) 
    {
        $navKat = 'lazy';
        $n_kat = '';
        $navWhere = '';
    } 
    else
    {
        $n_kat = "AND kat = '".$kat."'";
        $navKat = $kat;
        $navWhere = "WHERE kat = '".$kat."'";
    }

    $qry = db("SELECT * FROM ".$db['news']." 
               WHERE sticky >= ".time()." 
               AND datum <= ".time()." 
               AND public = 1 ".(!permission("intnews") ? "AND `intern` = '0'" : '')." ".$n_kat."
               ORDER BY datum DESC LIMIT ".($page - 1)*$maxnews.",".$maxnews."");
				   
    $show_sticky = "";
    while($get = _fetch($qry))
    {
        $getkat = _fetch(db("SELECT katimg FROM ".$db['newskat']." WHERE id = '".$get['kat']."'"));
        $c = cnt($db['newscomments'], " WHERE news = '".$get['id']."'");
        $klapp = ""; $links = ""; $links1 = ""; $links2 = ""; $links3 = "";

        if($c == 1)
            $comments = show(_news_comment, array("comments" => "1", "id" => $get['id']));
        else 
            $comments = show(_news_comments, array("comments" => $c, "id" => $get['id']));


        if($get['klapptext'])
            $klapp = show(_news_klapplink, array("klapplink" => re($get['klapplink']), "which" => "expand", "id" => $get['id']));

        $viewed = show(_news_viewed, array("viewed" => $get['viewed']));
        
        if(!empty($get['url1']))
            $links1 = show(_news_link, array("link" => re($get['link1']), "url" => $get['url1']));
	
        if(!empty($get['url2']))
            $links2 = show(_news_link, array("link" => re($get['link2']), "url" => $get['url2']));
        	
        if(!empty($get['url3']))
            $links3 = show(_news_link, array("link" => re($get['link3']), "url" => $get['url3']));

        if(!empty($links1) || !empty($links2) || !empty($links3))
            $links = show(_news_links, array("link1" => $links1, "link2" => $links2, "link3" => $links3, "rel" => _related_links));

        $intern = ($get['intern'] == 1 ? _votes_intern : '');
        $show_sticky .= show($dir."/news_show", array(  "titel" => re($get['titel']),
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
                                                        "ncomments" => _news_kommentare.":",
                                                        "klapp" => $klapp,
                                                        "more" => bbcode($get['klapptext']),
                                                        "viewed" => $viewed,
                                                        "text" => bbcode($get['text']),
                                                        "datum" => date("d.m.y H:i", $get['datum'])._uhr,
                                                        "links" => $links,
                                                        "autor" => autor($get['autor'])));
    }

    $qry = db("SELECT * FROM ".$db['news']." 
               WHERE sticky < ".time()." 
               AND datum <= ".time()." 
               AND public = 1 ".(!permission("intnews") ? "AND `intern` = '0'" : '')." ".$n_kat."
               ORDER BY datum DESC LIMIT ".($page - 1)*$maxnews.",".$maxnews."");
  
    $show = "";
    while($get = _fetch($qry))
    {
        $klapp = ""; $links = ""; $links1 = ""; $links2 = ""; $links3 = "";
        $getkat = _fetch(db("SELECT katimg FROM ".$db['newskat']." WHERE id = '".$get['kat']."'"));
        $c = cnt($db['newscomments'], " WHERE news = '".$get['id']."'");

        if($c == 1)
            $comments = show(_news_comment, array("comments" => "1", "id" => $get['id']));
        else
            $comments = show(_news_comments, array("comments" => $c, "id" => $get['id']));

        if($get['klapptext'])
          $klapp = show(_news_klapplink, array("klapplink" => re($get['klapplink']), "which" => "expand", "id" => $get['id']));

        $viewed = show(_news_viewed, array("viewed" => $get['viewed']));
    
        if(!empty($get['url1']))
            $links1 = show(_news_link, array("link" => re($get['link1']), "url" => $get['url1']));
        
        if(!empty($get['url2']))
            $links2 = show(_news_link, array("link" => re($get['link2']), "url" => $get['url2']));
        
        if(!empty($get['url3']))
            $links3 = show(_news_link, array("link" => re($get['link3']), "url" => $get['url3']));
    
        if(!empty($links1) || !empty($links2) || !empty($links3))
            $links = show(_news_links, array("link1" => $links1, "link2" => $links2, "link3" => $links3, "rel" => _related_links));
    
        $intern = ($get['intern'] ? _votes_intern : '');
        $show .= show($dir."/news_show", array("titel" => re($get['titel']),
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
                                               "ncomments" => _news_kommentare.":",
                                               "klapp" => $klapp,
                                               "more" => bbcode($get['klapptext']),
                                               "viewed" => $viewed,
                                               "text" => bbcode($get['text']),
                                               "datum" => date("d.m.y H:i", $get['datum'])._uhr,
                                               "links" => $links,
                                               "autor" => autor($get['autor'])));
    }

    $qrykat = db("SELECT * FROM ".$db['newskat']."");
    $kategorien = "";
    while($getkat = _fetch($qrykat))
    {
        $sel = (isset($_GET['kat']) && $_GET['kat'] == $getkat['id'] ? 'selected' : '');
        $kategorien .= "<option value='".$getkat['id']."' ".$sel.">".$getkat['kategorie']."</option>";
    }
      
    $index = show($dir."/news", array(  "show" => $show,
                                        "show_sticky" => $show_sticky,
                                        "nav" => nav(cnt($db['news'],$navWhere),$maxnews,"?kat=".$navKat,false),
                                        "kategorien" => $kategorien,
                                        "choose" => _news_kat_choose,
                                        "archiv" => _news_archiv));
break;
case 'show';
    $klapp = ""; $links = ""; $links1 = ""; $links2 = ""; $links3 = ""; $comments = "";
    db("UPDATE ".$db['news']." SET `viewed` = viewed+1 WHERE id = '".intval($_GET['id'])."'");
    $c = _fetch(db("SELECT intern,public FROM ".$db['news']." WHERE id = '".intval($_GET['id'])."'"));
  
    if(!permission("news") && !$c['public'])
        $index = error(_error_wrong_permissions, 1);
    else if($c['intern'] && !permission("intnews"))
        $index = error(_error_wrong_permissions, 1);
    else 
    {
        $qry = db("SELECT * FROM ".$db['news']." WHERE id = '".intval($_GET['id'])."'".(!permission("news") ? " AND public = 1" : ""));
    
        if(_rows($qry) == 0) 
            $index = error(_id_dont_exist,1);
        else 
        {
            $get = _fetch($qry);
            $getkat = _fetch(db("SELECT katimg FROM ".$db['newskat']." WHERE id = '".$get['kat']."'"));

            if($get['klapptext'])
                 $klapp = show(_news_klapplink, array("klapplink" => re($get['klapplink']), "which" => "collapse", "id" => $get['id']));

            $viewed = show(_news_viewed, array("viewed" => $get['viewed']));

            if(!empty($get['url1']))
                 $links1 = show(_news_link, array("link" => re($get['link1']), "url" => $get['url1']));
            
            if(!empty($get['url2']))
                 $links2 = show(_news_link, array("link" => re($get['link2']), "url" => $get['url2']));
            
            if(!empty($get['url3']))
                $links3 = show(_news_link, array("link" => re($get['link3']), "url" => $get['url3']));

            if(!empty($links1) || !empty($links2) || !empty($links3))
                $links = show(_news_links, array("link1" => $links1, "link2" => $links2, "link3" => $links3, "rel" => _related_links));

            $qryc = db("SELECT * FROM ".$db['newscomments']." WHERE news = ".intval($_GET['id'])." ORDER BY datum DESC LIMIT ".($page - 1)*$maxcomments.",".$maxcomments."");
            $entrys = cnt($db['newscomments'], " WHERE news = ".intval($_GET['id']));
            $i = $entrys-($page - 1)*$maxcomments;

            while($getc = _fetch($qryc))
            {
                $edit = ""; $delete = ""; $hp = ""; $email = ""; $onoff = "";
                if(($chkMe != 'unlogged' && $getc['reg'] == $userid) || permission("news"))
                {
                    $edit = show("page/button_edit_single", array("id" => $get['id'], "action" => "action=show&amp;do=edit&amp;cid=".$getc['id']."&amp;postid=".$i, "title" => _button_title_edit));                        
                    $delete = show("page/button_delete_single", array("id" => $get['id'], "action" => "action=show&amp;do=delete&amp;cid=".$getc['id'], "title" => _button_title_del, "del" => convSpace(_confirm_del_entry)));                    
                }

                if(!$getc['reg'])
                {
                    $hp = ($getc['hp'] ? show(_hpicon_forum, array("hp" => $getc['hp'])) : '');
                    $email = ($getc['email'] ? '<br />'.show(_emailicon_forum, array("email" => eMailAddr($getc['email']))) : '');
                    $nick = show(_link_mailto, array("nick" => re($getc['nick']), "email" => eMailAddr($getc['email'])));
                } 
                else 
                {
                    $onoff = onlinecheck($getc['reg']);
                    $nick = autor($getc['reg']);
                }

                $titel = show(_eintrag_titel, array("postid" => $i, "datum" => date("d.m.Y", $getc['datum']), "zeit" => date("H:i", $getc['datum'])._uhr, "edit" => $edit, "delete" => $delete));
                $posted_ip = ($chkMe == 4 ? $getc['ip'] : _logged);
                $comments .= show("page/comments_show", array(  "titel" => $titel,
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

            if(settings("reg_newscomments") == "1" && $chkMe == "unlogged")
                $add = _error_unregistered_nc;
            else 
            {
                if(isset($userid))
                    $form = show("page/editor_regged", array("nick" => autor($userid), "von" => _autor));
                else
                    $form = show("page/editor_notregged", array("nickhead" => _nick, "emailhead" => _email, "hphead" => _hp));

				$add = '';
                if(!ipcheck("ncid(".$_GET['id'].")", $flood_newscom))
                {
                    $add = show("page/comments_add", array( "titel" => _news_comments_write_head,
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
			
            $seiten = nav($entrys,$maxcomments,"?action=show&amp;id=".$_GET['id']."");
            $showmore = show($dir."/comments",array("head" => _comments_head,
                                                    "show" => $comments,
                                                    "seiten" => $seiten,
                                                    "add" => $add));

            $intern = ($get['intern'] ? _votes_intern : '');
            $title = re($get['titel']).' - '.$title;
			
            $index = show($dir."/news_show_full", array("titel" => re($get['titel']),
                                                        "kat" => re($getkat['katimg']),
                                                        "id" => $get['id'],
                                                        "comments" => "",
                                                        "dp" => "compact",
                                                        "nautor" => _autor,
                                                        "dir" => $designpath,
                                                        "ndatum" => _datum,
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

            // do case
            switch (isset($_GET['do']) ? $_GET['do'] : '') 
            {
                case 'add':
                    if(_rows(db("SELECT `id` FROM ".$db['news']." WHERE `id` = '".(int)$_GET['id']."'")) != 0)
                    {
                        if(settings("reg_newscomments") == "1" && $chkMe == "unlogged")
                            $index = error(_error_have_to_be_logged, 1);
                        else
                        {
                            if(!ipcheck("ncid(".$_GET['id'].")", $flood_newscom))
                            {
                                if(isset($userid))
                                    $toCheck = empty($_POST['comment']);
                                else
                                    $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['comment']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);
                                	
                                if($toCheck)
                                {
                                    if(isset($userid))
                                    {
                                        if(empty($_POST['eintrag'])) $error = _empty_eintrag;
                                        $form = show("page/editor_regged", array("nick" => autor($userid), "von" => _autor));
                                    }
                                    else
                                    {
                                        if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || empty($_SESSION['sec_'.$dir]))
                                            $error = _error_invalid_regcode;
                                        else if(empty($_POST['nick']))
                                            $error = _empty_nick;
                                        else if(empty($_POST['email']))
                                            $error = _empty_email;
                                        else if(!check_email($_POST['email']))
                                            $error = _error_invalid_email;
                                        else if(empty($_POST['eintrag']))
                                            $error = _empty_eintrag;
                                        else
                                            $error = '';
                    
                                        $form = show("page/editor_notregged", array("nickhead" => _nick, "emailhead" => _email, "hphead" => _hp));
                                    }
                    
                                    $error = show("errors/errortable", array("error" => $error));
                                    $index = show("page/comments_add", array("titel" => _news_comments_write_head,
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
                                                                             "prevurl" => '../news/?action=compreview&id='.$_GET['id'],
                                                                             "action" => '?action=show&amp;do=add&amp;id='.$_GET['id'],
                                                                             "ip" => _iplog_info,
                                                                             "lang" => $language,
                                                                             "id" => $_GET['id'],
                                                                             "what" => _button_value_add,
                                                                             "show" => "",
                                                                             "postemail" => (isset($_POST['email']) ? $_POST['email'] : ''),
                                                                             "posthp" => (isset($_POST['hp']) ? links($_POST['hp']) : ''),
                                                                             "postnick" => (isset($_POST['nick']) ? re($_POST['nick']) : ''),
                                                                             "posteintrag" => (isset($_POST['comment']) ? re_bbcode($_POST['comment']) : ''),
                                                                             "error" => $error,
                                                                             "eintraghead" => _eintrag));
                                }
                                else
                                {
                                    db("INSERT INTO ".$db['newscomments']."
                                    SET `news`     = '".((int)$_GET['id'])."',
                                        `datum`    = '".((int)time())."',
                                        ".(isset($_POST['email']) ? "`email` = '".up($_POST['email'])."'," : "")."         
                                        ".(isset($_POST['nick']) ? "`nick` = '".up($_POST['nick'])."'," : "")."         
                                        ".(isset($_POST['hp']) ? "`hp` = '".links($_POST['hp'])."'," : "")." 
                                        `reg`      = '".((int)$userid)."',
                                        `comment`  = '".up($_POST['comment'],1)."',
                                        `ip`       = '".visitorIp()."'");
                    
                                    wire_ipcheck("ncid(".$_GET['id'].")");
                                    $index = info(_comment_added, "?action=show&amp;id=".$_GET['id']."");
                                }
                            }
                            else
                                $index = error(show(_error_flood_post, array("sek" => $flood_newscom)), 1);
                        }
                    }
                    else
                        $index = error(_id_dont_exist,1);
                break;
                case 'delete':
                    $get = _fetch(db("SELECT * FROM ".$db['newscomments']." WHERE id = '".intval($_GET['cid'])."'"));
                    if($get['reg'] == $userid || permission('news'))
                    {
                        $qry = db("DELETE FROM ".$db['newscomments']." WHERE id = '".intval($_GET['cid'])."'");
                        $index = info(_comment_deleted, "?action=show&amp;id=".$_GET['id']."");
                    }
                    else
                        $index = error(_error_wrong_permissions, 1);
                break;
                case 'editcom':
                    $get = _fetch(db("SELECT * FROM ".$db['newscomments']." WHERE id = '".intval($_GET['cid'])."'"));
                    
                    if($get['reg'] == $userid || permission('news'))
                    {
                        $editedby = show(_edited_by, array("autor" => autor($userid), "time" => date("d.m.Y H:i", time())._uhr));
                        db("UPDATE ".$db['newscomments']." SET
                            ".(isset($_POST['email']) ? "`email` = '".up($_POST['email'])."'," : "")."         
                            ".(isset($_POST['nick']) ? "`nick` = '".up($_POST['nick'])."'," : "")."         
                            ".(isset($_POST['hp']) ? "`hp` = '".links($_POST['hp'])."'," : "")."
                            `comment`  = '".up($_POST['comment'],1)."',
                            `editby`   = '".addslashes($editedby)."'
                            WHERE id = '".intval($_GET['cid'])."'");
                    
                        $index = info(_comment_edited, "?action=show&amp;id=".$_GET['id']."");
                    }
                    else
                        $index = error(_error_edit_post,1);
                break;
                case 'edit':
                    $get = _fetch(db("SELECT * FROM ".$db['newscomments']." WHERE id = '".intval($_GET['cid'])."'"));
                    if($get['reg'] == $userid || permission('news'))
                    {
                        if($get['reg'] != 0)
                            $form = show("page/editor_regged", array("nick" => autor($get['reg']),"von" => _autor));
                        else
                            $form = show("page/editor_notregged", array("nickhead" => _nick, "emailhead" => _email, "hphead" => _hp, "postemail" => $get['email'], "posthp" => links($get['hp']), "postnick" => re($get['nick'])));
                    
                        $index = show("page/comments_add", array("titel" => _comments_edit,
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
                                                                 "prevurl" => '../news/?action=compreview&do=edit&id='.$_GET['id'].'&cid='.$_GET['cid'].'&postid='.$_GET['postid'],
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
    $getkat = _fetch(db("SELECT katimg FROM ".$db['newskat']." WHERE id = '".intval($_POST['kat'])."'"));
    $klapp = ""; $links = ""; $links1 = ""; $links2 = ""; $links3 = "";
    if($_POST['klapptitel'])
    {
      $klapp = show(_news_klapplink, array("klapplink" => re($_POST['klapptitel']),
                                           "which" => "collapse",
                                           "id" => 0));
    }

    $viewed = show(_news_viewed, array("viewed" => '0'));

    if(!empty($_POST['url1']))
    {
      $rel = _related_links;
      $links1 = show(_news_link, array("link" => re($_POST['link1']),
                                       "url" => links($_POST['url1'])));
    }
    
    if(!empty($_POST['url2']))
    {
      $rel = _related_links;
      $links2 = show(_news_link, array("link" => re($_POST['link2']),
                                       "url" => links($_POST['url2'])));
    }
    
    if(!empty($_POST['url3']))
    {
      $rel = _related_links;
      $links3 = show(_news_link, array("link" => re($_POST['link3']),
                                       "url" => links($_POST['url3'])));
    }
    
    if(!empty($links1) || !empty($links2) || !empty($links3))
    {
      $links = show(_news_links, array("link1" => $links1,
                                       "link2" => $links2,
                                       "link3" => $links3,
                                       "rel" => $rel));
    }

    $index = show($dir."/news_show", array("titel" => re($_POST['titel']),
                                           "kat" => re($getkat['katimg']),
                                           "id" => '_prev',
                                           "comments" => _news_comments_prev,
                                           "showmore" => "",
                                           "dp" => "",
                                           "dir" => $designpath,
                                           "nautor" => _autor,
			                               "intern" => ($_POST['intern'] ? _intern : ''),
                                           "sticky" => ($_POST['sticky'] ? _news_sticky : ''),
                                           "ndatum" => _datum,
                                           "ncomments" => _news_kommentare.":",
                                           "klapp" => $klapp,
                                           "more" => bbcode($_POST['morenews'],1),
                                           "viewed" => $viewed,
                                           "text" => bbcode($_POST['newstext'],1),
                                           "datum" => date("d.m.y H:i", time())._uhr,
                                           "links" => $links,
                                           "autor" => autor($_SESSION['id'])));
               
    echo '<table class="mainContent" cellspacing="1">'.$index.'</table>';
    exit();
break;
case 'compreview';
    header("Content-type: text/html; charset=utf-8");
    if(isset($_GET['do']) ? ($_GET['do'] == 'edit') : false)
    {
        $get = _fetch(db("SELECT * FROM ".$db['newscomments']." WHERE id = '".intval($_GET['cid'])."'"));
        
        $get_id = $_GET['postid'];
        $get_userid = $get['reg'];
        $get_date = $get['datum'];
        $get_last_edit = (!empty($get['editby']) ? true : false);
        
        if($get['reg'] == 0) 
            $regCheck = false;
        else 
        {
            $regCheck = true;
            $pUId = $get['reg'];
        }
            
        $editedby = show(_edited_by, array("autor" => cleanautor($userid), "time" => date("d.m.Y H:i", time())._uhr));
    } 
    else 
    {
        $get_id = cnt($db['newscomments'], " WHERE news = ".intval($_GET['id'])."")+1;
        $get_userid = $userid;
        $get_date = time();
        $get_last_edit = false;
            
        if($chkMe == 'unlogged') 
            $regCheck = false;
        else 
        {
            $regCheck = true;
            $pUId = $userid;
        }
    }

    $get_hp = (isset($_POST['hp']) ? $_POST['hp'] : '');
    $get_email = (isset($_POST['email']) ? $_POST['email'] : '');
    $get_nick = (isset($_POST['nick']) ? $_POST['nick'] : '');
  
    if(!$regCheck)
    {
        if($get_hp) 
            $hp = show(_hpicon_forum, array("hp" => links($get_hp)));
        
        if($get_email) 
            $email = '<br />'.show(_emailicon_forum, array("email" => eMailAddr($get_email)));
        
        $onoff = "";
        $avatar = "";
        $nick = show(_link_mailto, array("nick" => re($get_nick), "email" => $get_email));
    } 
    else 
    {
        $hp = "";
        $email = "";
        $onoff = onlinecheck($get_userid);
        $nick = cleanautor($get_userid);
    }

    $titel = show(_eintrag_titel, array("postid" => $get_id,
                                        "datum" => date("d.m.Y", $get_date),
                                        "zeit" => date("H:i", $get_date)._uhr,
                                        "edit" => '',
                                        "delete" => ''));
                                        
    $index = show("page/comments_show", array("titel" => $titel,
                                              "comment" => bbcode($_POST['comment'],1),
                                              "nick" => $nick,
                                              "editby" => ($get_last_edit ? $editedby : ''),
                                              "email" => $email,
                                              "hp" => $hp,
                                              "avatar" => useravatar($get_userid),
                                              "onoff" => $onoff,
                                              "rank" => getrank($get_userid),
                                              "ip" => visitorIp()._only_for_admins));
        
    echo '<table class="mainContent" cellspacing="1">'.$index.'</table>';
    exit();
break;
case 'archiv':
    if(permission("intnews"))
    {
        $intern = "WHERE public = 1";
        $intern2 = "WHERE intern = 1 OR intern = 0 AND datum <= ".time()." AND public = 1";
    }
    else 
    {
        $intern = "AND intern = 0 AND public = 1";
        $intern2 = "WHERE intern = 0 AND datum <= ".time()." AND public = 1";
    }
    
    $page = (isset($_GET['page']) ? $_GET['page'] : 1);
    $n_kat = (empty($kat) ? '' : "AND kat = '".$kat."'");
    $qry = db("SELECT id,titel,autor,datum,kat,text FROM ".$db['news']." ".$intern2." ".$n_kat." ORDER BY datum DESC LIMIT ".($page - 1)*$maxarchivnews.",".$maxarchivnews."");
    $entrys = cnt($db['news'], " ".$intern2." ".$n_kat);
    
    $color = 1; $show = '';
    while($get = _fetch($qry))
    {
        $getk = _fetch(db("SELECT kategorie FROM ".$db['newskat']." WHERE id = '".$get['kat']."'"));
        $comments = cnt($db['newscomments'], " WHERE news = ".$get['id']."");
        $titel = show(_news_show_link, array("titel" => cut(re($get['titel']),$lnewsarchiv), "id" => $get['id']));
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $show .= show($dir."/archiv_show", array("autor" => autor($get['autor']), 
                                                 "date" => date("d.m.y", $get['datum']), 
                                                 "titel" => $titel, 
                                                 "class" => $class, 
                                                 "kat" => re($getk['kategorie']), 
                                                 "comments" => $comments));
    }
    
    $nav = nav($entrys,$maxarchivnews,"?action=archiv");
    $index = show($dir."/archiv", array("head" => _news_archiv_head,
                                        "date" => _datum,
                                        "titel" => _titel,
                                        "nav" => $nav,
                                        "kat" => _news_admin_kat,
                                        "show" => $show,
                                        "autor" => _autor));
break;
endswitch;

## SETTINGS ##
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where, $time);

## OUTPUT BUFFER END ##
gz_output();
?>