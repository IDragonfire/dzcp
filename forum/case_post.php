<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_Forum')) {
  if($do == "edit")
  {
    $qry = db("SELECT * FROM ".$db['f_posts']."
               WHERE id = '".intval($_GET['id'])."'");
    $get = _fetch($qry);

    if($get['reg'] == $userid || permission("forum"))
    {
      if($get['reg'] != 0)
        {
            $form = show("page/editor_regged", array("nick" => autor($get['reg']),
                                                 "von" => _autor));
        } else {
        $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                    "emailhead" => _email,
                                                    "hphead" => _hp,
                                                    "postemail" => re($get['email']),
                                                                                    "posthp" => re($get['hp']),
                                                                                    "postnick" => re($get['nick'])));
      }

      $dowhat = show(_forum_dowhat_edit_post, array("id" => $_GET['id']));
      $index = show($dir."/post", array("titel" => _forum_edit_post_head,
                                        "nickhead" => _nick,
                                        "emailhead" => _email,
                                        "kid" => "",
                                        "id" => $_GET['id'],
                                        "ip" => _iplog_info,
                                        "dowhat" => $dowhat,
                                        "form" => $form,
                                        "zitat" => $zitat,
                                        "preview" => _preview,
                                        "br1" => "<!--",
                                        "br2" => "-->",
                                        "security" => _register_confirm,
                                        "lastpost" => "",
                                        "last_post" => _forum_no_last_post,
                                        "bbcodehead" => _bbcode,
                                        "eintraghead" => _eintrag,
                                        "error" => "",
                                        "what" => _button_value_edit,
                                        "posteintrag" => re_bbcode($get['text'])));
    } else {
      $index = error(_error_wrong_permissions, 1);
    }
  } elseif($do == "editpost") {
    $qry = db("SELECT reg FROM ".$db['f_posts']."
               WHERE id = '".intval($_GET['id'])."'");
    $get = _fetch($qry);
    if($get['reg'] == $userid || permission("forum"))
    {
      if($get['reg'] != 0 || permission('forum'))
      {
        $toCheck = empty($_POST['eintrag']);
      } else {
        $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);
      }

      if($toCheck)
        {
        if($get['reg'] != 0)
          {
          if(empty($_POST['eintrag'])) $error = _empty_eintrag;
              $form = show("page/editor_regged", array("nick" => autor($userid),
                                                   "von" => _autor));
          } else {
          if(($_POST['secure'] != $_SESSION['sec_'.$dir]) && !$userid) $error = _error_invalid_regcode;
          elseif(empty($_POST['nick'])) $error = _empty_nick;
              elseif(empty($_POST['email'])) $error = _empty_email;
              elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
              elseif(empty($_POST['eintrag']))$error = _empty_eintrag;
          $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                      "emailhead" => _email,
                                                      "hphead" => _hp));
        }

        $error = show("errors/errortable", array("error" => $error));
        $dowhat = show(_forum_dowhat_edit_post, array("id" => $_GET['id']));
        $index = show($dir."/post", array("titel" => _forum_edit_post_head,
                                                                          "nickhead" => _nick,
                                                                            "bbcodehead" => _bbcode,
                                          "preview" => _preview,
                                                                            "emailhead" => _email,
                                          "zitat" => $zitat,
                                          "form" => $form,
                                          "dowhat" => $dowhat,
                                          "security" => _register_confirm,
                                          "what" => _button_value_edit,
                                          "ip" => _iplog_info,
                                                                            "id" => $_GET['id'],
                                          "kid" => $_GET['kid'],
                                          "br1" => "<!--",
                                          "br2" => "-->",
                                                                            "postemail" => re($get['email']),
                                                                            "postnick" => re($get['nick']),
                                                                              "posteintrag" => re_bbcode($_POST['eintrag']),
                                                                               "error" => $error,
                                                                           "eintraghead" => _eintrag));
      } else {
        $qryp = db("SELECT * FROM ".$db['f_posts']."
                    WHERE id = '".intval($_GET['id'])."'");
        $getp = _fetch($qryp);

        $editedby = show(_edited_by, array("autor" => autor($userid),
                                           "time" => date("d.m.Y H:i", time())._uhr));

        $qry = db("UPDATE ".$db['f_posts']."
                   SET `nick`   = '".up($_POST['nick'])."',
                       `email`  = '".up($_POST['email'])."',
                       `text`   = '".up($_POST['eintrag'])."',
                       `hp`     = '".links($_POST['hp'])."',
                       `edited` = '".addslashes($editedby)."'
                   WHERE id = '".intval($_GET['id'])."'");

      $checkabo = db("SELECT s1.user,s1.fid,s2.nick,s2.id,s2.email FROM ".$db['f_abo']." AS s1
                        LEFT JOIN ".$db['users']." AS s2 ON s2.id = s1.user
                      WHERE s1.fid = '".$getp['sid']."'");
        while($getabo = _fetch($checkabo))
        {
        if($userid != $getabo['user'])
        {
          $topic = db("SELECT topic FROM ".$db['f_threads']." WHERE id = '".$getp['sid']."'");
          $gettopic = _fetch($topic);

            $entrys = cnt($db['f_posts'], " WHERE `sid` = ".$getp['sid']);

            if($entrys == "0") $pagenr = "1";
            else $pagenr = ceil($entrys/config('m_fposts'));

          $subj = show(re(settings('eml_fabo_pedit_subj')), array("titel" => $title));

           $message = show(bbcode_email(settings('eml_fabo_pedit')), array("nick" => re($getabo['nick']),
                                                               "postuser" => fabo_autor($userid),
                                                            "topic" => $gettopic['topic'],
                                                            "titel" => $title,
                                                            "domain" => $httphost,
                                                            "id" => $getp['sid'],
                                                            "entrys" => $entrys+1,
                                                            "page" => $pagenr,
                                                            "text" => bbcode($_POST['eintrag']),
                                                            "clan" => settings('clanname')));

          sendMail(re($getabo['email']),$subj,$message);
        }
      }
        $entrys = cnt($db['f_posts'], " WHERE `sid` = ".$getp['sid']);

        if($entrys == "0") $pagenr = "1";
        else $pagenr = ceil($entrys/config('m_fposts'));

        $lpost = show(_forum_add_lastpost, array("id" => $entrys+1,
                                                 "tid" => $getp['sid'],
                                                 "page" => $pagenr));

        $index = info(_forum_editpost_successful, $lpost);
      }
    } else {
      $index = error(_error_wrong_permissions, 1);
    }
  } elseif($do == "add") {
    if(settings("reg_forum") && !$chkMe)
    {
      $index = error(_error_unregistered,1);
    } else {
      if(!ipcheck("fid(".$_GET['kid'].")", config('f_forum')))
      {
        $check = db("SELECT s2.id,s1.intern FROM ".$db['f_kats']." AS s1
                     LEFT JOIN ".$db['f_skats']." AS s2
                     ON s2.sid = s1.id
                     WHERE s2.id = '".intval($_GET['kid'])."'");
        $checks = _fetch($check);
        if(forumcheck($_GET['id'], "closed"))
        {
          $index = error(_error_forum_closed, 1);
        } elseif($checks['intern'] == 1 && !permission("intforum") && !fintern($checks['id'])) {
          $index = error(_error_no_access, 1);
        } else {
          if($userid >= 1)
          {
              $postnick = data("nick");
              $postemail = data("email");
          } else {
              $postnick = "";
              $postemail = "";
          }
          if(isset($_GET['zitat']))
          {
            $qryzitat = db("SELECT nick,reg,text FROM ".$db['f_posts']."
                            WHERE id = '".intval($_GET['zitat'])."'");
            $getzitat = _fetch($qryzitat);

            if($getzitat['reg'] == "0") $nick = $getzitat['nick'];
            else                        $nick = autor($getzitat['reg']);

            $zitat = zitat($nick, $getzitat['text']);
          } elseif(isset($_GET['zitatt'])) {
            $qryzitat = db("SELECT t_nick,t_reg,t_text FROM ".$db['f_threads']."
                            WHERE id = '".intval($_GET['zitatt'])."'");
            $getzitat = _fetch($qryzitat);

            if($getzitat['t_reg'] == "0") $nick = $getzitat['t_nick'];
            else                          $nick = data("nick",$getzitat['t_reg']);

            $zitat = zitat($nick, $getzitat['t_text']);
          } else {
            $zitat = "";
          }

          $dowhat = show(_forum_dowhat_add_post, array("id" => $_GET['id'],
                                                       "kid" => $_GET['kid']));

          $qryl = db("SELECT * FROM ".$db['f_posts']."
                      WHERE kid = '".intval($_GET['kid'])."'
                      AND sid = '".intval($_GET['id'])."'
                      ORDER BY date DESC");
          if(_rows($qryl))
          {
            $getl = _fetch($qryl);

            if(data("signatur",$getl['reg'])) $sig = _sig.bbcode(data("signatur",$getl['reg']));
            else                               $sig = "";

            if($getl['reg'] != "0") $userposts = show(_forum_user_posts, array("posts" => userstats("forumposts",$getl['reg'])));
            else                    $userposts = "";

            if($getl['reg'] == "0") $onoff = "";
            else                    $onoff = onlinecheck($getl['reg']);

            $text = bbcode($getl['text']);

            if($chkMe == "4") $posted_ip = $getl['ip'];
            else              $posted_ip = _logged;

            $titel = show(_eintrag_titel_forum, array("postid" => (cnt($db['f_posts'], " WHERE sid =".intval($_GET['id']))+1),
                                                                                        "datum" => date("d.m.Y", $getl['date']),
                                                                                        "zeit" => date("H:i", $getl['date'])._uhr,
                                                "url" => '#',
                                                "edit" => "",
                                                "delete" => ""));
            if($getl['reg'] != 0)
            {
              $qryu = db("SELECT nick,icq,hp,email FROM ".$db['users']."
                          WHERE id = '".$getl['reg']."'");
              $getu = _fetch($qryu);

              $email = show(_emailicon_forum, array("email" => eMailAddr(re($getu['email']))));
              $pn = _forum_pn_preview;
              if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
                  else {
                $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
                $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
                  }

              if(empty($getu['hp'])) $hp = "";
              else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
            } else {
              $icq = "";
              $pn = "";
              $email = show(_emailicon_forum, array("email" => eMailAddr(re($getl['email']))));
              if(empty($getl['hp'])) $hp = "";
              else $hp = show(_hpicon_forum, array("hp" => $getl['hp']));
            }

            $lastpost = show($dir."/forum_posts_show", array("nick" => cleanautor($getl['reg'], '', $getl['nick'], re($getl['email'])),
                                                             "postnr" => "",
                                                             "text" => $text,
                                                             "status" => getrank($getl['reg']),
                                                             "avatar" => useravatar($getl['reg']),
                                                             "pn" => $pn,
                                                             "icq" => $icq,
                                                             "hp" => $hp,
                                                             "class" => 'class="commentsRight"',
                                                             "email" => $email,
                                                             "titel" => $titel,
                                                             "p" => ($page-1*config('m_fposts')),
                                                             "ip" => $posted_ip,
                                                             "edited" => $getl['edited'],
                                                             "posts" => $userposts,
                                                             "date" => _posted_by.date("d.m.y H:i", $getl['date'])._uhr,
                                                             "signatur" => $sig,
                                                             "zitat" => _forum_zitat_preview,
                                                             "onoff" => $onoff,
                                                             "top" => "",
                                                             "lp" => cnt($db['f_posts'], " WHERE sid = '".intval($_GET['id'])."'")+1));
          } else {
            $qryt = db("SELECT * FROM ".$db['f_threads']."
                        WHERE kid = '".intval($_GET['kid'])."'
                        AND id = '".intval($_GET['id'])."'");
            $gett = _fetch($qryt);

            if(data("signatur",$gett['t_reg'])) $sig = _sig.bbcode(data("signatur",$gett['t_reg']));
            else $sig = "";

            if($gett['t_reg'] != "0")
              $userposts = show(_forum_user_posts, array("posts" => userstats("forumposts",$gett['t_reg'])));
            else $userposts = "";

            if($gett['t_reg'] == "0") $onoff = "";
            else                      $onoff = onlinecheck($gett['t_reg']);

            $ftxt = hl($gett['t_text'], (isset($_GET['hl']) ? $_GET['hl'] : ''));
            if(isset($_GET['hl'])) $text = bbcode($ftxt['text']);
            else $text = bbcode($gett['t_text']);

            if($chkMe == "4") $posted_ip = $gett['ip'];
            else                 $posted_ip = _logged;

            $titel = show(_eintrag_titel_forum, array("postid" => "1",
                                                                                        "datum" => date("d.m.Y", $gett['t_date']),
                                                                                        "zeit" => date("H:i", $gett['t_date'])._uhr,
                                                "url" => '#',
                                                "edit" => "",
                                                "delete" => ""));
            if($gett['t_reg'] != 0)
            {
              $qryu = db("SELECT nick,icq,hp,email FROM ".$db['users']."
                          WHERE id = '".$gett['t_reg']."'");
              $getu = _fetch($qryu);

              $email = show(_emailicon_forum, array("email" => eMailAddr(re($getu['email']))));
              $pn = show(_pn_write_forum, array("id" => $gett['t_reg'],
                                                                              "nick" => $getu['nick']));
              if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
                  else {
                $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
                $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
                  }

              if(empty($getu['hp'])) $hp = "";
              else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
            } else {
              $icq = "";
              $pn = "";
              $email = show(_emailicon_forum, array("email" => eMailAddr($gett['t_email'])));
              if(empty($gett['t_hp'])) $hp = "";
              else $hp = show(_hpicon_forum, array("hp" => $gett['t_hp']));
            }

            $lastpost = show($dir."/forum_posts_show", array("nick" => cleanautor($gett['t_reg'], '', $gett['t_nick'], $gett['t_email']),
                                                             "postnr" => "",
                                                             "text" => $text,
                                                             "status" => getrank($gett['t_reg']),
                                                             "avatar" => useravatar($gett['t_reg']),
                                                             "pn" => $pn,
                                                             "icq" => $icq,
                                                             "class" => $ftxt['class'],
                                                             "hp" => $hp,
                                                             "email" => $email,
                                                             "titel" => $titel,
                                                             "ip" => $posted_ip,
                                                             "p" => ($page-1*config('m_fposts')),
                                                             "edited" => $gett['edited'],
                                                             "posts" => $userposts,
                                                             "date" => _posted_by.date("d.m.y H:i", $gett['t_date'])._uhr,
                                                             "signatur" => $sig,
                                                             "zitat" => "",
                                                             "onoff" => $onoff,
                                                             "top" => "",
                                                             "lp" => cnt($db['f_posts'], " WHERE sid = '".intval($_GET['id'])."'")+1));
          }

          if($userid >= 1)
            {
                $form = show("page/editor_regged", array("nick" => autor($userid),
                                                     "von" => _autor));
            } else {
            $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                        "emailhead" => _email,
                                                        "hphead" => _hp));
          }

          $title = re($gett['topic']).' - '.$title;
          $index = show($dir."/post", array("titel" => _forum_new_post_head,
                                            "nickhead" => _nick,
                                            "emailhead" => _email,
                                            "id" => $_GET['id'],
                                            "kid" => $_GET['kid'],
                                            "zitat" => $zitat,
                                            "last_post" => _forum_lp_head,
                                            "preview" => _preview,
                                            "lastpost" => $lastpost,
                                            "bbcodehead" => _bbcode,
                                            "form" => $form,
                                            "br1" => "",
                                            "security" => _register_confirm,
                                            "ip" => _iplog_info,
                                            "br2" => "",
                                            "what" => _button_value_add,
                                            "kid" => $_GET['kid'],
                                            "id" => $_GET['id'],
                                            "dowhat" => $dowhat,
                                            "eintraghead" => _eintrag,
                                            "error" => "",
                                            "postnick" => $postnick,
                                            "postemail" => $postemail,
                                            "posthp" => '',
                                            "posteintrag" => ""));
        }
      } else {
        $index = error(show(_error_flood_post, array("sek" => config('f_forum'))), 1);
      }
    }
  } elseif($do == "addpost") {
        $qry_thread = db("SELECT `id`,`kid` FROM ".$db['f_threads']." WHERE `id` = '".(int)$_GET['id']."'");
        if(_rows($qry_thread) == 0)
        {
            $index = error(_id_dont_exist,1);
        } else {
            if(settings("reg_forum") && !$chkMe)
            {
                $index = error(_error_unregistered,1);
            } else {
                $get_threadkid = _fetch($qry_thread);
                $check = db("SELECT s2.id,s1.intern FROM ".$db['f_kats']." AS s1
                                         LEFT JOIN ".$db['f_skats']." AS s2
                                         ON s2.sid = s1.id
                                         WHERE s2.id = '".intval($_GET['kid'])."'");
                $checks = _fetch($check);

                if($checks['intern'] == 1 && !permission("intforum") && !fintern($checks['id'])) {

                    if(!mysqli_persistconns)
                        $mysql->close(); //MySQL

                    exit();
                }

                if($userid >= 1) $toCheck = empty($_POST['eintrag']);
                else $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);

                if($toCheck)
                {
                    if($userid >= 1)
                    {
                        if(empty($_POST['eintrag'])) $error = _empty_eintrag;
                        $form = show("page/editor_regged", array("nick" => autor($userid),
                                                                                                         "von" => _autor));
                    } else {
                        if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || empty($_SESSION['sec_'.$dir])) $error = _error_invalid_regcode;
                        elseif(empty($_POST['nick'])) $error = _empty_nick;
                        elseif(empty($_POST['email'])) $error = _empty_email;
                        elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
                        elseif(empty($_POST['eintrag'])) $error = _empty_eintrag;
                        $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                                                                                "emailhead" => _email,
                                                                                                                "hphead" => _hp));
                    }

                    $error = show("errors/errortable", array("error" => $error));
                    $dowhat = show(_forum_dowhat_add_post, array("id" => $_GET['id'],
                                                                                                             "kid" => $get_threadkid['kid']));
                    $qryl = db("SELECT * FROM ".$db['f_posts']."
                                            WHERE kid = '".intval($get_threadkid['kid'])."'
                                            AND sid = '".intval($_GET['id'])."'
                                            ORDER BY date DESC");
                    if(_rows($qryl))
                    {
                        $getl = _fetch($qryl);

                        if(data("signatur",$getl['reg'])) $sig = _sig.bbcode(data("signatur",$getl['reg']));
                        else $sig = "";

                        if($getl['reg'] != "0") $userposts = show(_forum_user_posts, array("posts" => userstats("forumposts",$getl['reg'])));
                        else $userposts = "";

                        if($getl['reg'] == "0") $onoff = "";
                        else $onoff = onlinecheck($getl['reg']);

                        $ftxt = hl($getl['text'], $_GET['hl']);
                        if($_GET['hl']) $text = bbcode($ftxt['text']);
                        else $text = bbcode($getl['text']);

                        if($chkMe == "4") $posted_ip = $getl['ip'];
                        else $posted_ip = _logged;

                        $titel = show(_eintrag_titel_forum, array("postid" => (cnt($db['f_posts'], " WHERE sid = ".intval($_GET['id']))+1),
                                                                                                "datum" => date("d.m.Y", $getl['date']),
                                                                                                "zeit" => date("H:i", $getl['date'])._uhr,
                                                                                                "url" => '#',
                                                                                                "edit" => "",
                                                                                                "delete" => ""));

                        if($getl['reg'] != 0)
                        {
                            $qryu = db("SELECT nick,icq,hp,email FROM ".$db['users']."
                                                    WHERE id = '".$getl['reg']."'");
                            $getu = _fetch($qryu);

                            $email = show(_emailicon_forum, array("email" => eMailAddr($getu['email'])));
                            $pn = show(_pn_write_forum, array("id" => $getl['reg'],
                                                                                                "nick" => $getu['nick']));
                            if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
                            else {
                                $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
                                $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
                            }

                            if(empty($getu['hp'])) $hp = "";
                            else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
                        } else {
                            $icq = "";
                            $pn = "";
                            $email = show(_emailicon_forum, array("email" => eMailAddr($getl['email'])));
                            if(empty($getl['hp'])) $hp = "";
                            else $hp = show(_hpicon_forum, array("hp" => $getl['hp']));
                        }

                        $nick = autor($getl['reg'], '', $getl['nick'], re($getl['email']));
                        if(!empty($_GET['hl']) && $_SESSION['search_type'] == 'autor')
                        {
                            if(preg_match("#".$_GET['hl']."#i",$nick)) $ftxt['class'] = 'class="highlightSearchTarget"';
                        }

                        $lastpost = show($dir."/forum_posts_show", array("nick" => $nick,
                                                                                                                         "postnr" => "",
                                                                                                                         "text" => $text,
                                                                                                                         "status" => getrank($getl['reg']),
                                                                                                                         "avatar" => useravatar($getl['reg']),
                                                                                                                         "titel" => $titel,
                                                                                                                         "pn" => $pn,
                                                                                                                         "icq" => $icq,
                                                                                                                         "hp" => $hp,
                                                                                                                         "class" => $ftxt['class'],
                                                                                                                         "email" => $email,
                                                                                                                         "ip" => $posted_ip,
                                                                                                                         "p" => ($i+($page-1)*config('m_fposts')),
                                                                                                                         "edited" => $getl['edited'],
                                                                                                                         "posts" => $userposts,
                                                                                                                         "signatur" => $sig,
                                                                                                                         "zitat" => "",
                                                                                                                         "onoff" => $onoff,
                                                                                                                         "top" => "",
                                                                                                                         "lp" => cnt($db['f_posts'], " WHERE sid = '".intval($_GET['id'])."'")+1));
                    } else {
                        $qryt = db("SELECT * FROM ".$db['f_threads']."
                                                WHERE kid = '".intval($get_threadkid['kid'])."'
                                                AND id = '".intval($_GET['id'])."'");
                        $gett = _fetch($qryt);

                        if(data("signatur",$gett['t_reg'])) $sig = _sig.bbcode(data("signatur",$gett['t_reg']));
                        else $sig = "";

                        if($gett['t_reg'] != "0") $userposts = show(_forum_user_posts, array("posts" => userstats("forumposts",$gett['t_reg'])));
                        else $userposts = "";

                        if($gett['t_reg'] == "0") $onoff = "";
                        else $onoff = onlinecheck($gett['t_reg']);

                        $ftxt = hl($gett['t_text'], $_GET['hl']);
                        if($_GET['hl']) $text = bbcode($ftxt['text']);
                        else $text = bbcode($gett['t_text']);

                        if($chkMe == "4") $posted_ip = $gett['ip'];
                        else $posted_ip = _logged;

                        if($gett['t_reg'] != 0)
                        {
                            $qryu = db("SELECT nick,icq,hp,email FROM ".$db['users']."
                                                    WHERE id = '".$gett['t_reg']."'");
                            $getu = _fetch($qryu);

                            $email = show(_emailicon_forum, array("email" => eMailAddr(re($getu['email']))));
                            $pn = show(_pn_write_forum, array("id" => $gett['t_reg'],
                                                                                                "nick" => $getu['nick']));
                            if(empty($getu['icq']) || $getu['icq'] == 0) $icq = "";
                            else {
                                $uin = show(_icqstatus_forum, array("uin" => $getu['icq']));
                                $icq = '<a href="http://www.icq.com/whitepages/about_me.php?uin='.$getu['icq'].'" target="_blank">'.$uin.'</a>';
                            }

                            if(empty($getu['hp'])) $hp = "";
                            else $hp = show(_hpicon_forum, array("hp" => $getu['hp']));
                        } else {
                            $icq = "";
                            $pn = "";
                            $email = show(_emailicon_forum, array("email" => eMailAddr($gett['t_email'])));
                            if(empty($gett['t_hp'])) $hp = "";
                            else $hp = show(_hpicon_forum, array("hp" => $gett['t_hp']));
                        }

                        $nick = autor($gett['t_reg'], '', $gett['t_nick'], $gett['t_email']);
                        if(!empty($_GET['hl']) && $_SESSION['search_type'] == 'autor')
                        {
                            if(preg_match("#".$_GET['hl']."#i",$nick)) $ftxt['class'] = 'class="highlightSearchTarget"';
                        }

                        $lastpost = show($dir."/forum_posts_show", array("nick" => $nick,
                                                                                                                         "postnr" => "",
                                                                                                                         "text" => $text,
                                                                                                                         "status" => getrank($gett['t_reg']),
                                                                                                                         "avatar" => useravatar($gett['t_reg']),
                                                                                                                         "ip" => $posted_ip,
                                                                                                                         "pn" => $pn,
                                                                                                                         "class" => $ftxt['class'],
                                                                                                                         "icq" => $icq,
                                                                                                                         "hp" => $hp,
                                                                                                                         "email" => $email,
                                                                                                                         "edit" => "",
                                                                                                                         "p" => ($i+($page-1)*config('m_fposts')),
                                                                                                                         "delete" => "",
                                                                                                                         "edited" => $gett['edited'],
                                                                                                                         "posts" => $userposts,
                                                                                                                         "date" => _posted_by.date("d.m.y H:i", $gett['t_date'])._uhr,
                                                                                                                         "signatur" => $sig,
                                                                                                                         "zitat" => "",
                                                                                                                         "onoff" => $onoff,
                                                                                                                         "top" => "",
                                                                                                                         "lp" => cnt($db['f_posts'], " WHERE sid = '".intval($_GET['id'])."'")+1));
                    }

                    $index = show($dir."/post", array("titel" => _forum_new_post_head,
                                                                                        "nickhead" => _nick,
                                                                                        "bbcodehead" => _bbcode,
                                                                                        "emailhead" => _email,
                                                                                        "zitat" => $zitat,
                                                                                        "what" => _button_value_add,
                                                                                        "preview" => _preview,
                                                                                        "form" => $form,
                                                                                        "br1" => "",
                                                                                        "br2" => "",
                                                                                        "security" => _register_confirm,
                                                                                        "lastpost" => $lastpost,
                                                                                        "last_post" => _forum_lp_head,
                                                                                        "dowhat" => $dowhat,
                                                                                        "id" => $_GET['id'],
                                                                                        "ip" => _iplog_info,
                                                                                        "kid" => $_GET['kid'],
                                                                                        "postemail" => $_POST['email'],
                                                                                        "posthp" => $_POST['hp'],
                                                                                        "postnick" => re($_POST['nick']),
                                                                                        "posteintrag" => re_bbcode($_POST['eintrag']),
                                                                                        "error" => $error,
                                                                                        "eintraghead" => _eintrag));
                } else {
                    $spam = 0;
                    $qrydp = db("SELECT * FROM ".$db['f_posts']."
                                             WHERE kid = '".intval($get_threadkid['kid'])."'
                                             AND sid = '".intval($_GET['id'])."'
                                             ORDER BY date DESC
                                             LIMIT 1");
                    if(_rows($qrydp))
                    {
                        $getdp = _fetch($qrydp);

                        if($userid >= 1)
                        {
                            if($userid == $getdp['reg'] && settings('double_post')) $spam = 1;
                            else $spam = 0;
                        } else {
                            if($_POST['nick'] == $getdp['nick'] && settings('double_post')) $spam = 1;
                            else $spam = 0;
                        }
                    } else {

                        $qrytdp = db("SELECT * FROM ".$db['f_threads']."
                                    WHERE kid = '".intval($get_threadkid['kid'])."'
                                    AND id = '".intval($_GET['id'])."'");
                        $gettdp = _fetch($qrytdp);

                        if($userid >= 1)
                        {
                            if($userid == $gettdp['t_reg'] && settings('double_post')) $spam = 2;
                            else $spam = 0;
                        } else {
                            if($_POST['nick'] == $gettdp['t_nick'] && settings('double_post')) $spam = 2;
                            else $spam = 0;
                        }
                    }

                    if($spam == 1)
                    {
                        if($userid >= 1) $fautor = autor($userid);
                        else $fautor = autor('', '', $_POST['nick'], $_POST['email']);

                            $text = show(_forum_spam_text, array("autor" => $fautor,
                                                                                                     "ltext" => addslashes($getdp['text']),
                                                                                                     "ntext" => up($_POST['eintrag'])));

                                                    $qry = db("UPDATE ".$db['f_threads']."
                                                                                         SET `lp` = '".time()."'
                                    WHERE kid = '".intval($_GET['kid'])."'
                                    AND id = '".intval($_GET['id'])."'");

                            $qry = db("UPDATE ".$db['f_posts']."
                                                 SET `date`   = '".time()."',
                                                         `text`   = '".$text."'
                                                 WHERE id = '".$getdp['id']."'");
                    } elseif($spam == 2) {
                        if($userid >= 1) $fautor = autor($userid);
                        else $fautor = autor('', '', $_POST['nick'], $_POST['email']);

                            $text = show(_forum_spam_text, array("autor" => $fautor,
                                                                                                     "ltext" => addslashes($gettdp['t_text']),
                                                                                                     "ntext" => up($_POST['eintrag'])));

                            $qry = db("UPDATE ".$db['f_threads']."
                                                 SET `lp`   = '".time()."',
                                                 `t_text`   = '".$text."'
                                                 WHERE id = '".$gettdp['id']."'");
                } else {
                    $qry = db("INSERT INTO ".$db['f_posts']."
                                         SET `kid`   = '".((int)$get_threadkid['kid'])."',
                                                 `sid`   = '".((int)$_GET['id'])."',
                                                 `date`  = '".time()."',
                                                 `nick`  = '".up($_POST['nick'])."',
                                                 `email` = '".up($_POST['email'])."',
                                                 `hp`    = '".links($_POST['hp'])."',
                                                 `reg`   = '".up($userid)."',
                                                 `text`  = '".up($_POST['eintrag'])."',
                                                 `ip`    = '".$userip."'");

                    $update = db("UPDATE ".$db['f_threads']."
                                                SET `lp`    = '".time()."',
                                                        `first` = '0'
                                                WHERE id    = '".intval($_GET['id'])."'");
                }

                setIpcheck("fid(".$get_threadkid['kid'].")");

                    $update = db("UPDATE ".$db['userstats']."
                                                SET `forumposts` = forumposts+1
                                                WHERE `user`       = '".$userid."'");

                    $checkabo = db("SELECT s1.user,s1.fid,s2.nick,s2.id,s2.email FROM ".$db['f_abo']." AS s1
                                    LEFT JOIN ".$db['users']." AS s2 ON s2.id = s1.user
                                                    WHERE s1.fid = '".((int)$_GET['id'])."'");
                    while($getabo = _fetch($checkabo))
                    {
                        if($userid != $getabo['user'])
                        {
                            $topic = db("SELECT topic FROM ".$db['f_threads']." WHERE id = '".intval($_GET['id'])."'");
                            $gettopic = _fetch($topic);

                            $entrys = cnt($db['f_posts'], " WHERE `sid` = ".intval($_GET['id']));

                            if($entrys == "0") $pagenr = "1";
                            else $pagenr = ceil($entrys/config('m_fposts'));

                            $subj = show(settings('eml_fabo_npost_subj'), array("titel" => $title));

                            $message = show(bbcode_email(settings('eml_fabo_npost')), array("nick" => re($getabo['nick']),
                                                                            "postuser" => fabo_autor($userid),
                                                                            "topic" => $gettopic['topic'],
                                                                            "titel" => $title,
                                                                            "domain" => $httphost,
                                                                            "id" => intval($_GET['id']),
                                                                            "entrys" => $entrys+1,
                                                                            "page" => $pagenr,
                                                                            "text" => bbcode($_POST['eintrag']),
                                                                            "clan" => settings('clanname')));

                            sendMail(re($getabo['email']),$subj,$message);
                        }
                    }

                    $entrys = cnt($db['f_posts'], " WHERE `sid` = ".intval($_GET['id']));

                    if($entrys == "0") $pagenr = "1";
                    else $pagenr = ceil($entrys/config('m_fposts'));

                    $lpost = show(_forum_add_lastpost, array("id" => $entrys+1,
                                                                                                     "tid" => $_GET['id'],
                                                                                                     "page" => $pagenr));

                    $index = info(_forum_newpost_successful, $lpost);
                }
            }
        }
  } elseif($do == "delete") {
    $qry = db("SELECT * FROM ".$db['f_posts']."
               WHERE id = '".intval($_GET['id'])."'");
    $get = _fetch($qry);

    if($get['reg'] == $userid OR permission("forum"))
    {
      $del = db("DELETE FROM ".$db['f_posts']."
                 WHERE id = '".intval($_GET['id'])."'");

      $fposts = userstats("forumposts",$get['reg'])-1;
      $upd = db("UPDATE ".$db['userstats']."
                 SET `forumposts` = '".((int)$fposts)."'
                 WHERE user = '".$get['reg']."'");

      $entrys = cnt($db['f_posts'], " WHERE `sid` = ".$get['sid']);

      if($entrys == "0")
      {
        $pagenr = "1";
        $update = db("UPDATE ".$db['f_threads']."
                      SET `first` = '1'
                      WHERE kid = '".$get['kid']."'");
      } else {
        $pagenr = ceil($entrys/config('m_fposts'));
      }

      $lpost = show(_forum_add_lastpost, array("id" => $entrys+1,
                                               "tid" => $get['sid'],
                                               "page" => $pagenr));

      $index = info(_forum_delpost_successful, $lpost);
    }
  }
}