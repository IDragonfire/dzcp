<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/common.php");

## SETTINGS ##
$where = _site_contact;
$dir = "news";

## SECTIONS ##
    switch ($action):
    default:

            if(!$chkMe)
            {
                $form = show($dir."/send_form1", array("nachricht" => _site_news,
                                                       "nick" => _nick,
                                                       "titel" => _titel,
                                                       "note" => _news_send_note,
                                                       "value" => _button_value_send,
                                                       "what" => "sendnews",
                                                       "security" => _register_confirm,
                                                       "pflicht" => _contact_pflichtfeld,
                                                       "email" => _email,
                                                       "hp" => _news_send_source,
                                                       "error" => "",
                                                       "s_nick" => "",
                                                       "s_email" => "",
                                                       "s_hp" => "",
                                                       "s_titel" => "",
                                                       "s_text" => "",
                                                       "s_info" => ""));
            } else {

                $form = show($dir."/send_form2", array("nachricht" => _site_news,
                                                       "nick" => _nick,
                                                       "titel" => _titel,
                                                       "note" => _news_send_note,
                                                       "user" => autor($userid),
                                                       "value" => _button_value_send,
                                                       "what" => "sendnews",
                                                       "security" => _register_confirm,
                                                       "pflicht" => _contact_pflichtfeld,
                                                       "hp" => _news_send_source,
                                                       "error" => "",
                                                       "s_hp" => "",
                                                       "s_titel" => "",
                                                       "s_text" => "",
                                                       "s_info" => ""));
            }


      $index = show($dir."/send", array("error" => "",
                                          "form" => $form,
                                          "description" => _news_send_description,
                                        "head" => _news_send));

    break;
    case 'do';
      if($_GET['what'] == "sendnews")
      {
           if((!$userid && (empty($_POST['nick']))) || (!$userid && empty($_POST['email']) || $_POST['email'] == "E-Mail") || empty($_POST['titel']) || empty($_POST['text']) || (($_POST['secure'] != $_SESSION['sec_sendnews'] || $_SESSION['sec_sendnews'] == NULL) && !$userid))
        {
          if(($_POST['secure'] != $_SESSION['sec_sendnews'] || $_SESSION['sec_sendnews'] == NULL) && !$userid) $error = show("errors/errortable", array("error" => _error_invalid_regcode));
          if(empty($_POST['text'])) $error = show("errors/errortable", array("error" => _error_empty_nachricht));
          if(empty($_POST['titel'])) $error = show("errors/errortable", array("error" => _empty_titel));
          if(!$userid && !check_email($_POST['email'])) $error = show("errors/errortable", array("error" => _error_invalid_email));
          if(!$userid && empty($_POST['email']) || $_POST['email'] == "E-Mail") $error = show("errors/errortable", array("error" => _empty_email));
          if(!$userid && (empty($_POST['nick']))) $error = show("errors/errortable", array("error" => _empty_nick));

            if(!$chkMe)
            {
                $form = show($dir."/send_form1", array("nachricht" => _site_news,
                                                       "nick" => _nick,
                                                       "titel" => _titel,
                                                       "note" => _news_send_note,
                                                       "value" => _button_value_send,
                                                       "what" => "sendnews",
                                                       "security" => _register_confirm,
                                                       "pflicht" => _contact_pflichtfeld,
                                                       "email" => _email,
                                                       "hp" => _news_send_source,
                                                       "s_nick" => $_POST['nick'],
                                                       "s_email" => $_POST['email'],
                                                       "s_hp" => $_POST['hp'],
                                                       "s_titel" => $_POST['titel'],
                                                       "s_text" => $_POST['text'],
                                                       "s_info" => $_POST['info']));
            } else {

                $form = show($dir."/send_form2", array("nachricht" => _site_news,
                                                       "nick" => _nick,
                                                       "titel" => _titel,
                                                       "note" => _news_send_note,
                                                       "user" => autor($userid),
                                                       "value" => _button_value_send,
                                                       "what" => "sendnews",
                                                       "security" => _register_confirm,
                                                       "pflicht" => _contact_pflichtfeld,
                                                       "hp" => _news_send_source,
                                                       "s_hp" => $_POST['hp'],
                                                       "s_titel" => $_POST['titel'],
                                                       "s_text" => $_POST['text'],
                                                       "s_info" => $_POST['info']));
            }

            $index = show($dir."/send", array("error" => $error,
                                              "form" => $form,
                                                "description" => _news_send_description,
                                              "head" => _news_send));

        } else {
          $hp = show(_contact_hp, array("hp" => links($_POST['hp'])));
          if(!$userid) $nick = $_POST['nick'];
          else $nick = blank_autor($userid);
          if(!$userid) $von_nick = "0";
          else $von_nick = $userid;
          if(!$userid) $titel = show(_news_send_titel, array("nick" => $_POST['nick']));
          else $titel = show(_news_send_titel, array("nick" => blank_autor($userid)));
          if(!$userid) $email = show(_email_mailto, array("email" => $_POST['email']));
          else $email = '--';
          if(!$userid) $sendnews = '1';
          else $sendnews = '2';
          if(!$userid) $user = $_POST['nick'];
          else $user = $userid;

          $text = show(_contact_text_sendnews, array("hp" => $hp,
                                                     "email" => $email,
                                                     "titel" => up($_POST['titel']),
                                                     "text" => up($_POST['text']),
                                                     "info" => up($_POST['info']),
                                                     "nick" => $nick));

          $qry = db("SELECT id,level FROM ".$db['users']."");
          while($get = _fetch($qry))
          {
                    if(permission('news',$get['id']) or $get['level'] == 4)
                    {
                        $update = db("INSERT INTO ".$db['msg']."
                                                  SET `datum`     = '".time()."',
                                                          `von`       = '".$von_nick."',
                                                          `an`        = '".intval($get['id'])."',
                                                          `titel`     = '".$titel."',
                                                          `nachricht` = '".up($text)."',
                                                          `sendnews`  = '".$sendnews."',
                                                          `senduser`  = '".up($user)."'");
                    }
                }
          $index = info(_news_send_done, "../news/");
        }
      }
    break;
    endswitch;

## INDEX OUTPUT ##
$title = $pagetitle." - ".$where;
page($index, $title, $where);