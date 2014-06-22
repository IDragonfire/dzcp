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
$where = _site_artikel;
$title = $pagetitle." - ".$where."";
$dir = "artikel";

## SECTIONS ##
switch ($action):
default:
    $qry = db("SELECT id,kat,titel,datum,autor,text FROM ".$db['artikel']."
               WHERE public = 1
               ".orderby_sql(array("artikel","titel","datum","kat"), 'ORDER BY datum DESC')."
               LIMIT ".($page - 1)*config('m_artikel').",".config('m_artikel')."");
    $entrys = cnt($db['artikel']);
    if(_rows($qry)) {
        while($get = _fetch($qry)) {
            $getk = db("SELECT kategorie FROM ".$db['newskat']." WHERE id = '".$get['kat']."'",false,true);
            $titel = '<a style="display:block" href="?action=show&amp;id='.$get['id'].'">'.$get['titel'].'</a>';
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/artikel_show", array("titel" => $titel,
                                                      "kat" => re($getk['kategorie']),
                                                      "id" => $get['id'],
                                                      "display" => "none",
                                                      "nautor" => _autor,
                                                      "ndatum" => _datum,
                                                      "class" => $class,
                                                      "ncomments" => _news_kommentare.":",
                                                      "text" => bbcode($get['text']),
                                                      "datum" => date("d.m.Y", $get['datum']),
                                                      "autor" => autor($get['autor'])));
        }
    } else {
        $show = show(_no_entrys_yet, array("colspan" => "4"));
    }

    $seiten = nav($entrys,config('m_artikel'),"?page".(isset($_GET['show']) ? $_GET['show'] : 0).orderby_nav());
    $index = show($dir."/artikel", array("show" => $show,
                                         "nav" => $seiten,
                                         "artikel" => _artikel,
                                         "kat" => _news_admin_kat,
                                         "datum" => _datum,
                                         "autor" => _autor,
                                         "order_autor" => orderby('autor'),
                                         "order_datum" => orderby('datum'),
                                         "order_titel" => orderby('titel'),
                                         "order_kat" => orderby('kat'),
                                         "archiv" => _news_archiv));
break;
case 'show';
    $qry = db("SELECT * FROM ".$db['artikel']."
               WHERE id = '".intval($_GET['id'])."'".(permission("artikel") ? "" : " AND public = 1"));

    if(_rows($qry) == 0) {
        $index = error(_id_dont_exist,1);
    } else {
        while($get = _fetch($qry)) {
            $getkat = db("SELECT katimg FROM ".$db['newskat']."
                          WHERE id = '".intval($get['kat'])."'",false,true);

            $links1 = ""; $links2 = ""; $links3 = ""; $links = "";
            if($get['url1']) {
                $rel = _related_links;
                $links1 = show(_artikel_link, array("link" => re($get['link1']),
                                                    "url" => $get['url1']));
            }

            if($get['url2']) {
                $rel = _related_links;
                $links2 = show(_artikel_link, array("link" => re($get['link2']),
                                                    "url" => $get['url2']));
            }

            if($get['url3']) {
                $rel = _related_links;
                $links3 = show(_artikel_link, array("link" => re($get['link3']),
                                                    "url" => $get['url3']));
            }

            if(!empty($links1) || !empty($links2) || !empty($links3)) {
                $links = show(_artikel_links, array("link1" => $links1,
                                                    "link2" => $links2,
                                                    "link3" => $links3,
                                                    "rel" => $rel));
            }

            $entrys = cnt($db['acomments'], " WHERE artikel = ".intval($_GET['id']));
            $qryc = db("SELECT * FROM ".$db['acomments']."
                        WHERE artikel = ".intval($_GET['id'])."
                        ORDER BY datum DESC
                        LIMIT ".($page - 1)*config('m_comments').",".config('m_comments')."");

            $i = ($entrys-($page - 1)*config('m_comments')); $comments = '';
            while($getc = _fetch($qryc)) {
                $hp = ($getc['hp'] ? show(_hpicon, array("hp" => $getc['hp'])) : "");

                $edit = ""; $delete = "";
                if(($chkMe >= 1 && $getc['reg'] == $userid) || permission("artikel")) {
                    $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                                  "action" => "action=show&amp;do=edit&amp;cid=".$getc['id'],
                                                                  "title" => _button_title_edit));

                    $delete = show("page/button_delete_single", array("id" => $_GET['id'],
                                                                      "action" => "action=show&amp;do=delete&amp;cid=".$getc['id'],
                                                                      "title" => _button_title_del,
                                                                      "del" => convSpace(_confirm_del_entry)));
                }

                if(!$getc['reg'])
                {
                    $hp = ($getc['hp'] ? show(_hpicon_forum, array("hp" => $getc['hp'])) : "");
                    $email = ($getc['email'] ? '<br />'.show(_emailicon_forum, array("email" => eMailAddr(re($getc['email'])))) : "");
                    $onoff = ""; $avatar = "";
                    $nick = show(_link_mailto, array("nick" =>re($getc['nick']),
                                                     "email" => eMailAddr(re($getc['email']))));
                } else {
                    $email = ""; $hp = "";
                    $onoff = onlinecheck($getc['reg']);
                    $nick = autor($getc['reg']);
                }

                $titel = show(_eintrag_titel, array("postid" => $i,
                                                    "datum" => date("d.m.Y", $getc['datum']),
                                                    "zeit" => date("H:i", $getc['datum'])._uhr,
                                                    "edit" => $edit,
                                                    "delete" => $delete));

                $posted_ip = ($chkMe == "4" ? $getc['ip'] : _logged);
                $comments .= show("page/comments_show", array("titel" => $titel,
                                                              "comment" => bbcode($getc['comment']),
                                                              "editby" => bbcode($getc['editby']),
                                                              "nick" => $nick,
                                                              "email" => $email,
                                                              "hp" => $hp,
                                                              "avatar" => useravatar($getc['reg']),
                                                              "onoff" => $onoff,
                                                              "rank" => getrank($getc['reg']),
                                                              "ip" => $posted_ip));
                $i--;
            }

            $add = "";
            if(settings("reg_artikel") && !$chkMe) {
                $add = _error_unregistered_nc;
            } else {
                if($userid >= 1)
                {
                    $form = show("page/editor_regged", array("nick" => autor($userid),
                                                             "von" => _autor));
                } else {
                    $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                                "emailhead" => _email,
                                                                "hphead" => _hp));
                }

                if(!ipcheck("artid(".$_GET['id'].")", config('f_newscom'))) {
                    $add = show("page/comments_add", array("titel" => _artikel_comments_write_head,
                                                           "bbcodehead" => _bbcode,
                                                           "form" => $form,
                                                           "show" => "none",
                                                           "what" => _button_value_add,
                                                           "ip" => _iplog_info,"sec" => $dir,
                                                           "security" => _register_confirm,
                                                           "preview" => _preview,
                                                           "action" => '?action=show&amp;do=add&amp;id='.$_GET['id'],
                                                           "prevurl" => '../artikel/?action=compreview&id='.$_GET['id'],
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
                                                    "icq" => "",
                                                    "add" => $add));

            $artikelimage = '../inc/images/newskat/'.re($getkat['katimg']);
            foreach($picformat as $tmpendung) {
                if(file_exists(basePath."/inc/images/uploads/artikel/".$get['id'].".".$tmpendung)) {
                    $artikelimage = '../inc/images/uploads/artikel/'.$get['id'].'.'.$tmpendung;
                    break;
                }
            }

            $index = show($dir."/show_more", array("titel" => re($get['titel']),
                                                   "id" => $get['id'],
                                                   "comments" => "",
                                                   "display" => "inline",
                                                   "nautor" => _autor,
                                                   "kat" => $artikelimage,
                                                   "ndatum" => _datum,
                                                   "showmore" => $showmore,
                                                   "icq" => "",
                                                   "text" => bbcode($get['text']),
                                                   "datum" => date("j.m.y H:i", intval($get['datum']))._uhr,
                                                   "links" => $links,
                                                   "autor" => autor($get['autor'])));
        }

  if($do == "add")
  {
        if(_rows(db("SELECT `id` FROM ".$db['artikel']." WHERE `id` = '".(int)$_GET['id']."'")) != 0)
        {
            if(settings("reg_artikel") && !$chkMe)
                $index = error(_error_have_to_be_logged, 1);
            else {
                if(!ipcheck("artid(".$_GET['id'].")", config('f_artikelcom')))
                {
                    if($userid >= 1)
                        $toCheck = empty($_POST['comment']);
                    else
                        $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['comment']) || !check_email($_POST['email']) || !$securimage->check($_POST['secure']);

                    if($toCheck)
                    {
                        if($userid >= 1)
                        {
                            if(empty($_POST['eintrag'])) $error = _empty_eintrag;
                            $form = show("page/editor_regged", array("nick" => autor($userid),
                                                                                                             "von" => _autor));
                        } else {
                            if(!$securimage->check($_POST['secure']))
                                $error = show("errors/errortable", array("error" => captcha_mathematic ? _error_invalid_regcode_mathematic : _error_invalid_regcode));
                            elseif(empty($_POST['nick'])) $error = _empty_nick;
                            elseif(empty($_POST['email'])) $error = _empty_email;
                            elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
                            elseif(empty($_POST['eintrag'])) $error = _empty_eintrag;

                            $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                                                                                    "emailhead" => _email,
                                                                                                                    "hphead" => _hp));
                        }



                        $error = show("errors/errortable", array("error" => $error));
                        $index = show("page/comments_add", array("titel" => _artikel_comments_write_head,
                                                                                                         "nickhead" => _nick,
                                                                                                         "bbcodehead" => _bbcode,
                                                                                                         "sec" => $dir,
                                                                                                         "security" => _register_confirm,
                                                                                                         "emailhead" => _email,
                                                                                                         "form" => $form,
                                                                                                         "hphead" => _hp,
                                                                                                         "preview" => _preview,
                                                                                                         "action" => '?action=show&amp;do=add&amp;id='.$_GET['id'],
                                                                                                         "prevurl" => '../artikel/?action=compreview&amp;id='.$_GET['id'],
                                                                                                         "id" => $_GET['id'],
                                                                                                         "what" => _button_value_add,
                                                                                                         "postemail" => $_POST['email'],
                                                                                                         "ip" => _iplog_info,
                                                                                                         "posthp" => links($_POST['hp']),
                                                                                                         "postnick" => re($_POST['nick']),
                                                                                                         "show" => "",
                                                                                                         "posteintrag" => re_bbcode($_POST['comment']),
                                                                                                         "error" => $error,
                                                                                                         "eintraghead" => _eintrag));
                    } else {
                        $qry = db("INSERT INTO ".$db['acomments']."
                                             SET `artikel`  = '".((int)$_GET['id'])."',
                                                     `datum`    = '".time()."',
                                                     `nick`     = '".(isset($_POST['nick']) ? up($_POST['nick']) : data('nick'))."',
                                                     `email`    = '".(isset($_POST['email']) ? up($_POST['email']) : data('email'))."',
                                                     `hp`       = '".(isset($_POST['hp']) ? links($_POST['hp']) : links(data('hp')))."',
                                                     `reg`      = '".((int)$userid)."',
                                                     `comment`  = '".up($_POST['comment'])."',
                                                     `ip`       = '".$userip."'");

                        setIpcheck("artid(".$_GET['id'].")");

                        $index = info(_comment_added, "?action=show&amp;id=".$_GET['id']."");
                    }
                } else {
                    $index = error(show(_error_flood_post, array("sek" => config('f_newscom'))), 1);
                }
            }
        } else{
            $index = error(_id_dont_exist,1);
        }
  } elseif($do == "delete") {
    $qry = db("SELECT * FROM ".$db['acomments']."
               WHERE id = '".intval($_GET['cid'])."'");
    $get = _fetch($qry);

    if($get['reg'] == $userid || permission('artikel'))
    {
      $qry = db("DELETE FROM ".$db['acomments']."
                 WHERE id = '".intval($_GET['cid'])."'");

      $index = info(_comment_deleted, "?action=show&amp;id=".$_GET['id']."");
    } else {
      $index = error(_error_wrong_permissions, 1);
    }
  } elseif($do == "editcom") {
    $qry = db("SELECT * FROM ".$db['acomments']."
               WHERE id = '".intval($_GET['cid'])."'");
    $get = _fetch($qry);

    if($get['reg'] == $userid || permission('artikel'))
    {
        $editedby = show(_edited_by, array("autor" => autor($userid),
                                           "time" => date("d.m.Y H:i", time())._uhr));
        $qry = db("UPDATE ".$db['acomments']."
                   SET `nick`     = '".up($_POST['nick'])."',
                       `email`    = '".up($_POST['email'])."',
                       `hp`       = '".links($_POST['hp'])."',
                       `comment`  = '".up($_POST['comment'])."',
                       `editby`   = '".addslashes($editedby)."'
                   WHERE id = '".intval($_GET['cid'])."'");

        $index = info(_comment_edited, "?action=show&amp;id=".$_GET['id']."");
      } else {
        $index = error(_error_edit_post,1);
      }
    } elseif($do == "edit") {
      $qry = db("SELECT * FROM ".$db['acomments']."
                 WHERE id = '".intval($_GET['cid'])."'");
      $get = _fetch($qry);

      if($get['reg'] == $userid || permission('artikel'))
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
                                                                                              "posthp" => links($get['hp']),
                                                                                                  "postnick" => re($get['nick']),
                                                      ));
        }

            $index = show("page/comments_add", array("titel" => _comments_edit,
                                                 "nickhead" => _nick,
                                                 "bbcodehead" => _bbcode,
                                                 "emailhead" => _email,
                                                 "sec" => $dir,
                                                 "security" => _register_confirm,
                                                 "hphead" => _hp,
                                                 "form" => $form,
                                                 "preview" => _preview,
                                                 "prevurl" => '../artikel/?action=compreview&amp;do=edit&amp;id='.$_GET['id'].'&amp;cid='.$_GET['cid'],
                                                 "action" => '?action=show&amp;do=editcom&amp;id='.$_GET['id'].'&amp;cid='.$_GET['cid'],
                                                 "ip" => _iplog_info,
                                                 "id" => $_GET['id'],
                                                 "what" => _button_value_edit,
                                                 "show" => "",
                                                 "posteintrag" => re_bbcode($get['comment']),
                                                 "error" => "",
                                                 "eintraghead" => _eintrag));
      } else {
        $index = error(_error_edit_post,1);
      }
    }
  }
break;
case 'preview';
    header("Content-type: text/html; charset=utf-8");

    $qrykat = db("SELECT katimg FROM ".$db['newskat']."
                  WHERE id = '".intval($_POST['kat'])."'");
    $getkat = _fetch($qrykat);

    if($_POST['url1'])
    {
      $rel = _related_links;
      $links1 = show(_artikel_link, array("link" => re($_POST['link1']),
                                          "url" => links($_POST['url1'])));
    } else {
      $links1 = "";
    }
    if($_POST['url2'])
    {
      $rel = _related_links;
      $links2 = show(_artikel_link, array("link" => re($_POST['link2']),
                                          "url" => links($_POST['url2'])));
    } else {
      $links2 = "";
    }
    if($_POST['url3'])
    {
      $rel = _related_links;
      $links3 = show(_artikel_link, array("link" => re($_POST['link3']),
                                          "url" => links($_POST['url3'])));
    } else {
      $links3 = "";
    }

    if(!empty($links1) || !empty($links2) || !empty($links3))
    {
      $links = show(_artikel_links, array("link1" => $links1,
                                          "link2" => $links2,
                                          "link3" => $links3,
                                          "rel" => $rel));
    } else {
      $links = "";
    }

    $artikelimage = '../inc/images/newskat/'.re($getkat['katimg']);
    foreach($picformat as $tmpendung) {
        if(file_exists(basePath."/inc/images/uploads/artikel/".$get['id'].".".$tmpendung)) {
            $artikelimage = '../inc/images/uploads/artikel/'.$get['id'].'.'.$tmpendung;
            break;
        }
    }

    $index = show($dir."/show_more", array("titel" => re($_POST['titel']),
                                           "id" => $get['id'],
                                           "comments" => "",
                                           "display" => "inline",
                                           "nautor" => _autor,
                                           "kat" => $artikelimage,
                                           "ndatum" => _datum,
                                           "showmore" => $showmore,
                                           "icq" => "",
                                           "text" => bbcode(re($_POST['artikel']),true),
                                           "datum" => date("j.m.y H:i")._uhr,
                                           "links" => $links,
                                           "autor" => autor($userid)));

    echo '<table class="mainContent" cellspacing="1">'.$index.'</table>';

    if(!mysqli_persistconns)
        $mysql->close(); //MySQL

    exit();
break;
    case 'compreview';
        if($do == 'edit') {
            $get = db("SELECT * FROM ".$db['acomments']." WHERE id = '".intval($_GET['cid'])."'",false,true);

            $get_id = '?';
            $get_userid = $get['reg'];
            $get_date = $get['datum'];
            $regCheck = false;

            if($get['reg']) {
                $regCheck = true;
                $pUId = $get['reg'];
            }

            $editedby = show(_edited_by, array("autor" => cleanautor($userid),
                                               "time" => date("d.m.Y H:i", time())._uhr));
        } else {
            $get_id = cnt($db['acomments'], " WHERE artikel = ".intval($_GET['id'])."")+1;
            $get_userid = $userid;
            $get_date = time();
            $regCheck = false;
            $editedby = '';

            if(!$chkMe) {
                $regCheck = true;
                $pUId = $userid;
            }
        }

        if($regCheck) {
            $get_hp = isset($_POST['hp']) ? $_POST['hp'] : '';
            $get_email = isset($_POST['email']) ? $_POST['email'] : '';
            $get_nick = isset($_POST['nick']) ? $_POST['nick'] : '';

            $hp = $get_hp ? show(_hpicon_forum, array("hp" => links($get_hp))) : "";
            $email = $get_email ? '<br />'.show(_emailicon_forum, array("email" => eMailAddr($get_email))) : "";
            $onoff = "";
            $avatar = "";
            $nick = show(_link_mailto, array("nick" => re($get_nick),
                                             "email" => $get_email));
        } else {
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
                                                  "comment" => bbcode(re($_POST['comment']),true),
                                                  "nick" => $nick,
                                                  "editby" => bbcode($editedby,true),
                                                  "email" => $email,
                                                  "hp" => $hp,
                                                  "avatar" => useravatar($get_userid),
                                                  "onoff" => $onoff,
                                                  "rank" => getrank($get_userid),
                                                  "ip" => $userip._only_for_admins));

        echo '<table class="mainContent" cellspacing="1">'.$index.'</table>';

        if(!mysqli_persistconns)
            $mysql->close(); //MySQL

        exit();
    break;
endswitch;

## INDEX OUTPUT ##
page($index, $title, $where);