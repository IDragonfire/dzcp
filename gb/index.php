<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$where = _site_gb;
$title = $pagetitle." - ".$where."";
$dir = "gb";
## SECTIONS ##
switch ($action):
default:
  $add = show(_gb_eintragen, array("id" => $_GET['id']));
  if(!permission("gb") && $gb_activ == '1') $activ = "WHERE public = 1";
  elseif(permission("gb") && $gb_activ == '1') $activ = "";
    elseif(permission("gb") && $gb_activ == '0') $activ = "";
    elseif($gb_activ == '0') $activ = "";
  $qry = db("SELECT * FROM ".$db['gb']."
               ".$activ."
                   ORDER BY datum DESC
             LIMIT ".($page - 1)*$maxgb.",".$maxgb."");

  $entrys = cnt($db['gb']);
  $i = $entrys-($page - 1)*$maxgb;

  if(_rows($qry))
  {
      while($get = _fetch($qry))
      {
      if($get['hp']) $gbhp = show(_hpicon, array("hp" => $get['hp']));
      else $gbhp = "";

      if($get['email']) $gbemail = show(_emailicon, array("email" => eMailAddr($get['email'])));
      else $gbemail = "";

      if(($get['reg'] == $userid && $userid >= 1) || permission("gb"))
      {
        $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                      "action" => "action=do&amp;what=edit",
                                                      "title" => _button_title_edit));
        $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                         "action" => "action=do&amp;what=delete",
                                                         "title" => _button_title_del,
                                                         "del" => convSpace(_confirm_del_entry)));

        $comment = show(_gb_commenticon, array("id" => $get['id'],
                                               "title" => _button_title_comment));
      } else {
        $delete = "";
        $edit = "";
        $comment = "";
      }
      $public = "";
      if(permission("gb") && $gb_activ == 1)
      {
        $public = ($get['public'] == 1)
             ? '<a href="?action=do&amp;what=unset&amp;id='.$get['id'].'"><img src="../inc/images/public.gif" alt="" title="nicht ver&ouml;ffentlichen" align="top" style="padding-top:1px"/></a>'
             : '<a href="?action=do&amp;what=set&amp;id='.$get['id'].'"><img src="../inc/images/nonpublic.gif" alt="" title="ver&ouml;ffentlichen" align="top" style="padding-top:1px"/></a>';      } else {

      }

          if($get['reg'] == "0")
          {
              $gbtitel = show(_gb_titel_noreg, array("postid" => $i,
                                                                                           "nick" => re($get['nick']),
                                               "edit" => $edit,
                                               "delete" => $delete,
                                               "comment" => $comment,
                                               "public" => $public,
                                                                                           "email" => $gbemail,
                                                                                           "datum" => date("d.m.Y", $get['datum']),
                                               "uhr" => _uhr,
                                                                                           "zeit" => date("H:i", $get['datum']),
                                                                                           "hp" => $gbhp));
          } else {
               $gbtitel = show(_gb_titel, array("postid" => $i,
                                                                                  "nick" => autor($get['reg']),
                                          "edit" => $edit,
                                          "delete" => $delete,
                                          "uhr" => _uhr,
                                          "comment" => $comment,
                                          "public" => $public,
                                                                                  "id" => $get['reg'],
                                                                                  "email" => $gbemail,
                                                                                  "datum" => date("d.m.Y", $get['datum']),
                                                                                  "zeit" => date("H:i", $get['datum']),
                                                                                 "hp" => $gbhp));
          }

      if($chkMe == "4") $posted_ip = $get['ip'];
      else $posted_ip = _logged;

          $show .= show($dir."/gb_show", array("gbtitel" => $gbtitel,
                                                                               "nachricht" => bbcode($get['nachricht']),
                                           "editby" => bbcode($get['editby']),
                                           "ip" => $posted_ip));
          $i--;
    }
  } else {
    $show = show(_no_entrys_yet, array("colspan" => "2"));
  }

  $seiten = nav($entrys,$maxgb,"?action=nav");

  if(!ipcheck("gb", $flood_gb))
  {
    if($userid >= 1)
      {
          $form = show("page/editor_regged", array("nick" => autor($userid),
                                               "von" => _autor));
      } else {
      $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                  "emailhead" => _email,
                                                  "hphead" => _hp,
                                                  "postemail" => ""));
    }

    $entry = show($dir."/add", array("titel" => _eintragen_titel,
                                                                        "nickhead" => _nick,
                                                                        "bbcodehead" => _bbcode,
                                     "add_head" => _gb_add_head,
                                                                     "emailhead" => _email,
                                     "what" => _button_value_add,
                                     "security" => _register_confirm,
                                     "lang" => $language,
                                     "ed" => "",
                                     "reg" => "",
                                     "whaturl" => "addgb",
                                                                     "hphead" => _hp,
                                     "preview" => _preview,
                                                                     "id" => $_GET['id'],
                                     "form" => $form,
                                                                     "posthp" => "",
                                                                     "postnick" => "",
                                                                     "posteintrag" => "",
                                     "ip" => _iplog_info,
                                                                     "error" => "",
                                                                 "eintraghead" => _eintrag));
  }

  $index = show($dir."/gb",array("gbhead" => _gb_head,
                                                               "show" => $show,
                                 "add" => $add,
                                 "entry" => $entry,
                                 "addgb" => $addgb,
                                 "seiten" => $seiten));
break;
case 'do';
  if($_GET['what'] == "addgb")
  {
    if($userid >= 1)
    {
      $toCheck = empty($_POST['eintrag']);
    } else {
      $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['eintrag']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || $_SESSION['sec_'.$dir] == NULL;
    }
      if($toCheck)
        {
      if($userid >= 1)
        {
        if(empty($_POST['eintrag'])) $error = _empty_eintrag;
            $form = show("page/editor_regged", array("nick" => autor($userid),
                                                 "von" => _autor));
        } else {
        if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || $_SESSION['sec_'.$dir] == NULL) $error = _error_invalid_regcode;
        elseif(empty($_POST['nick'])) $error = _empty_nick;
            elseif(empty($_POST['email'])) $error = _empty_email;
            elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
            elseif(empty($_POST['eintrag']))$error = _empty_eintrag;
        $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                    "emailhead" => _email,
                                                    "hphead" => _hp));
      }

          $error = show("errors/errortable", array("error" => $error));

          $index = show($dir."/add", array("titel" => _eintragen_titel,
                                                                           "nickhead" => _nick,
                                                                           "bbcodehead" => _bbcode,
                                                                           "emailhead" => _email,
                                                                           "hphead" => _hp,
                                       "preview" => _preview,
                                       "security" => _register_confirm,
                                       "add_head" => _gb_add_head,
                                       "ed" => "",
                                       "whaturl" => "addgb",
                                       "what" => _button_value_add,
                                       "form" => $form,
                                       "lang" => $language,
                                       "reg" => "",
                                       "ip" => _iplog_info,
                                                                           "id" => $_GET['id'],
                                                                           "postemail" => $_POST['email'],
                                                                           "posthp" => links($_POST['hp']),
                                                                           "postnick" => $_POST['nick'],
                                                                           "posteintrag" => re_bbcode($_POST["eintrag"]),
                                                                           "error" => $error,
                                                                           "eintraghead" => _eintrag));
      } else {
          $qry = db("INSERT INTO ".$db['gb']."
                 SET `datum`      = '".((int)time())."',
                     `nick`       = '".up($_POST['nick'])."',
                     `email`      = '".up($_POST['email'])."',
                     `hp`         = '".links($_POST['hp'])."',
                     `reg`        = '".((int)$userid)."',
                     `nachricht`  = '".up($_POST['eintrag'], 1)."',
                     `ip`         = '".$userip."'");

        setIpcheck("gb");
        $index = info(_gb_entry_successful, "../gb/");
      }
  }
  //FIX START
  elseif($_GET['what'] == 'set')
  {
          if(permission('gb'))
        {
            db("UPDATE ".$db['gb']." SET `public` = '1' WHERE id = '".intval($_GET['id'])."'");
            header("Location: ../gb/");
        }
        else
        $index = error(_error_edit_post,1);
    }
    elseif($_GET['what'] == 'unset')
    {
        if(permission('gb'))
        {
               db("UPDATE ".$db['gb']." SET `public` = '0' WHERE id = '".intval($_GET['id'])."'");
               header("Location: ../gb/");
        }
        else
        $index = error(_error_edit_post,1);
    }
    elseif($_GET['what'] == "delete")
    {
        $qry = db("SELECT * FROM ".$db['gb']." WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

    if($get['reg'] == $userid && $chkMe >= 1 or permission('gb'))
    {
      db("DELETE FROM ".$db['gb']." WHERE id = '".intval($_GET['id'])."'");
      $index = info(_gb_delete_successful, "../gb/");
    }
    else
        $index = error(_error_edit_post,1);

    }
    elseif($_GET['what'] == "edit")
    {
    $qry = db("SELECT * FROM ".$db['gb']."  WHERE id = '".intval($_GET['id'])."'");
    $get = _fetch($qry);

    if($get['reg'] == $userid && $chkMe >= 1 or permission('gb'))
    //FIX END
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

      $index = show($dir."/add", array("titel" => _eintragen_titel,
                                                                          "nickhead" => _nick,
                                                                          "bbcodehead" => _bbcode,
                                       "add_head" => _gb_edit_head,
                                                                       "emailhead" => _email,
                                       "what" => _button_value_edit,
                                       "security" => _register_confirm,
                                       "lang" => $language,
                                       "reg" => $get['reg'],
                                       "whaturl" => "editgb&amp;id=".$get['id'],
                                                                       "hphead" => _hp,
                                       "ed" => "&edit=".$get['id'],
                                       "preview" => _preview,
                                                                       "id" => $get['id'],
                                       "form" => $form,
                                                                       "posteintrag" => re_bbcode($get['nachricht']),
                                       "ip" => _iplog_info,
                                                                       "error" => "",
                                                                   "eintraghead" => _eintrag));
      } else {
        $index = error(_error_edit_post,1);
      }
    } elseif($_GET['what'] == 'editgb') {
      if($_POST['reg'] == $userid || permission('gb'))
      {
        if($_POST['reg'] == 0)
        {
           $addme = "`nick`       = '".up($_POST['nick'])."',
                     `email`      = '".up($_POST['email'])."',
                     `hp`         = '".links($_POST['hp'])."',";
        }

        $editedby = show(_edited_by, array("autor" => autor($userid),
                                           "time" => date("d.m.Y H:i", time())._uhr));

        $upd = db("UPDATE ".$db['gb']."
                   SET ".$addme."
                       `nachricht`  = '".up($_POST['eintrag'], 1)."',
                       `reg`        = '".((int)$_POST['reg'])."',
                       `editby`     = '".addslashes($editedby)."'
                   WHERE id = '".intval($_GET['id'])."'");

        $index = info(_gb_edited, "../gb/");
      } else {
        $index = error(_error_edit_post,1);
      }
    }
break;
case 'admin';
  if(!permission("gb"))
  {
    $index = error(_error_wrong_permissions, 1);
  } else {
    if($_GET['do'] == "addcomment")
    {
      $qry = db("SELECT * FROM ".$db['gb']."
                 WHERE id = '".intval($_GET['id'])."'");
      $get = _fetch($qry);

      if($get['hp']) $gbhp = show(_hpicon, array("hp" => $get['hp']));
      else $gbhp = "";

      if($get_email) $gbemail = show(_emailicon, array("email" => eMailAddr($get['email'])));
      else $gbemail = "";

      if(permission("gb")) $comment = show(_gb_commenticon, array("id" => $get['id']));
      else $comment = "";

          if($get['reg'] == "0")
          {
              $gbtitel = show(_gb_titel_noreg, array("postid" => "?",
                                                                                           "nick" => re($get['nick']),
                                               "edit" => "",
                                               "delete" => "",
                                               "comment" => "",
                                               "public" => "",
                                               "uhr" => _uhr,
                                                                                           "email" => $gbemail,
                                                                                           "datum" => date("d.m.Y", $get['datum']),
                                                                                           "zeit" => date("H:i", $get['datum']),
                                                                                           "hp" => $gbhp));
          } else {
              $gbtitel = show(_gb_titel, array("postid" => "?",
                                                                               "nick" => data("nick",$get['reg']),
                                         "edit" => "",
                                         "public" => "",
                                         "delete" => "",
                                         "uhr" => _uhr,
                                         "comment" => "",
                                                                               "id" => $get['reg'],
                                                                                 "email" => $gbemail,
                                                                                 "datum" => date("d.m.Y", $get['datum']),
                                                                                 "zeit" => date("H:i", $get['datum']),
                                                                                "hp" => $gbhp));
          }

          $entry = show($dir."/gb_show", array("gbtitel" => $gbtitel,
                                                                             "nachricht" => bbcode($get['nachricht']),
                                           "editby" => bbcode($get['editby']),
                                           "ip" => $get['ip']));

      $index = show($dir."/gb_addcomment", array("head" => _gb_addcomment_head,
                                                 "entry" => $entry,
                                                 "what" => _button_value_add,
                                                 "id" => $_GET['id'],
                                                 "head_gb" => _gb_addcomment_headgb));
    } elseif($_GET['do'] == "postcomment") {
      $qry = db("SELECT * FROM ".$db['gb']."
                 WHERE id = '".intval($_GET['id'])."'");
      $get = _fetch($qry);

      $comment = show($dir."/commentlayout", array("nick" => autor($userid),
                                                   "datum" => date("d.m.Y H:i", time())._uhr,
                                                   "comment" => up($_POST['comment'], 1),
                                                   "nachricht" => $get['nachricht']));

      $upd = db("UPDATE ".$db['gb']."
                 SET `nachricht` = '".$comment."'
                 WHERE id = '".intval($_GET['id'])."'");

      $index = info(_gb_comment_added, "../gb/");
    }
  }
break;
case 'preview';
  header("Content-type: text/html; charset=utf-8");
  if(isset($_GET['edit']) && !empty($_GET['edit']))
  {
    $qry = db("SELECT * FROM ".$db['gb']."
               WHERE id = '".intval($_GET['edit'])."'");
    $get = _fetch($qry);

    $get_id = '?';
    $get_userid = $get['reg'];
    $get_date = $get['datum'];

    if($get['reg'] == 0) $regCheck = true;
    $editby = show(_edited_by, array("autor" => cleanautor($userid),
                                     "time" => date("d.m.Y H:i", time())._uhr));
  } else {
    $get_id = cnt($db['gb'])+1;
    $get_userid = $userid;
    $get_date = time();

    if(!$chkMe) $regCheck = true;
  }

  $get_hp = $_POST['hp'];
  $get_email = $_POST['email'];
  $get_nick = $_POST['nick'];

  if($get_hp) $gbhp = show(_hpicon, array("hp" => links($get_hp)));
  else $gbhp = "";

  if($get_email) $gbemail = show(_emailicon, array("email" => eMailAddr($get_email)));
  else $gbemail = "";

  if($regCheck)
    {
      $gbtitel = show(_gb_titel_noreg, array("postid" => $get_id,
                                                                                   "nick" => re($get_nick),
                                           "edit" => "",
                                           "delete" => "",
                                           "comment" => "",
                                                               "public" => "",
                                           "uhr" => _uhr,
                                                                               "email" => $gb_email,
                                                                                   "datum" => date("d.m.Y",$get_date),
                                                                                   "zeit" => date("H:i",$get_date),
                                                                                   "hp" => $gbhp));
    } else {
      $gbtitel = show(_gb_titel, array("postid" => $get_id,
                                                                       "nick" => autor($get_userid),
                                     "edit" => "",
                                     "uhr" => _uhr,
                                     "delete" => "",
                                     "comment" => "",
                                     "public" => "",
                                                                        "id" => $get_userid,
                                                                        "email" => $gb_email,
                                                                         "datum" => date("d.m.Y",$get_date),
                                                                         "zeit" => date("H:i",$get_date),
                                                                        "hp" => $gbhp));
    }

  $index = show($dir."/gb_show", array("gbtitel" => $gbtitel,
                                                                       "nachricht" => bbcode(re($_POST['eintrag']),1),
                                       "editby" => bbcode($editby,1),
                                       "ip" => $userip._only_for_admins));

  echo '<table class="mainContent" cellspacing="1">'.$index.'</table>';
  exit;
break;
endswitch;
## SETTINGS ##
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where,$time);
## OUTPUT BUFFER END ##
gz_output();
?>
