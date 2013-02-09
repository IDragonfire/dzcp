<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath . "/inc/config.php");
include(basePath . "/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$where = _site_contact;
$title = $pagetitle . " - " . $where . "";
$dir   = "news";
## SECTIONS ##
if (!isset($_GET['action']))
    $action = "";
else
    $action = $_GET['action'];

switch ($action):
    default:
        
        if ($chkMe == 'unlogged') {
            $form = show($dir . "/send_form1", array(
                "nachricht" => _site_news,
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
                "s_info" => ""
            ));
        } else {
            $form = show($dir . "/send_form2", array(
                "nachricht" => _site_news,
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
                "s_info" => ""
            ));
        }
        
        
        $index = show($dir . "/send", array(
            "error" => "",
            "form" => $form,
            "description" => _news_send_description,
            "head" => _news_send
        ));
        
        break;
    case 'do';
        if ($_GET['what'] == "sendnews") {
            if ((!isset($userid) && (empty($_POST['nick']))) || (!isset($userid) && empty($_POST['email']) || $_POST['email'] == "E-Mail") || empty($_POST['titel']) || empty($_POST['text']) || (($_POST['secure'] != $_SESSION['sec_sendnews'] || $_SESSION['sec_sendnews'] == NULL) && !isset($userid))) {
                if (($_POST['secure'] != $_SESSION['sec_sendnews'] || $_SESSION['sec_sendnews'] == NULL) && !isset($userid))
                    $error = show("errors/errortable", array(
                        "error" => _error_invalid_regcode
                    ));
                if (empty($_POST['text']))
                    $error = show("errors/errortable", array(
                        "error" => _error_empty_nachricht
                    ));
                if (empty($_POST['titel']))
                    $error = show("errors/errortable", array(
                        "error" => _empty_titel
                    ));
                if (!isset($userid) && !check_email($_POST['email']))
                    $error = show("errors/errortable", array(
                        "error" => _error_invalid_email
                    ));
                if (!isset($userid) && empty($_POST['email']) || $_POST['email'] == "E-Mail")
                    $error = show("errors/errortable", array(
                        "error" => _empty_email
                    ));
                if (!isset($userid) && (empty($_POST['nick'])))
                    $error = show("errors/errortable", array(
                        "error" => _empty_nick
                    ));
                
                if ($chkMe == 'unlogged') {
                    $form = show($dir . "/send_form1", array(
                        "nachricht" => _site_news,
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
                        "s_info" => $_POST['info']
                    ));
                } else {
                    $form = show($dir . "/send_form2", array(
                        "nachricht" => _site_news,
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
                        "s_info" => $_POST['info']
                    ));
                }
                
                $index = show($dir . "/send", array(
                    "error" => $error,
                    "form" => $form,
                    "description" => _news_send_description,
                    "head" => _news_send
                ));
                
            } else {
                $hp = show(_contact_hp, array(
                    "hp" => links($_POST['hp'])
                ));
                if (!isset($userid))
                    $nick = $_POST['nick'];
                else
                    $nick = blank_autor($userid);
                if (!isset($userid))
                    $von_nick = "0";
                else
                    $von_nick = $userid;
                if (!isset($userid))
                    $titel = show(_news_send_titel, array(
                        "nick" => $_POST['nick']
                    ));
                else
                    $titel = show(_news_send_titel, array(
                        "nick" => blank_autor($userid)
                    ));
                if (!isset($userid))
                    $email = show(_email_mailto, array(
                        "email" => $_POST['email']
                    ));
                else
                    $email = '--';
                if (!isset($userid))
                    $sendnews = '1';
                else
                    $sendnews = '2';
                if (!isset($userid))
                    $user = $_POST['nick'];
                else
                    $user = $userid;
                
                $text = show(_contact_text_sendnews, array(
                    "hp" => $hp,
                    "email" => $email,
                    "titel" => up($_POST['titel']),
                    "text" => up($_POST['text']),
                    "info" => up($_POST['info']),
                    "nick" => $nick
                ));
                
                $qry = db("SELECT id,level FROM " . $db['users'] . "");
                while ($get = _fetch($qry)) {
                    if (perm_sendnews($get['id']) or $get['level'] == 4) {
                        $update = db("INSERT INTO " . $db['msg'] . "
                                              SET `datum`     = '" . ((int) time()) . "',
                                                      `von`       = '" . $von_nick . "',
                                                      `an`        = '" . ((int) $get['id']) . "',
                                                      `titel`     = '" . $titel . "',
                                                      `nachricht` = '" . up($text, 1) . "',
                                                      `sendnews`  = '" . $sendnews . "',
                                                      `senduser`  = '" . $user . "'");
                    }
                }
                $index = info(_news_send_done, "../news/");
            }
        }
        break;
endswitch;
## SETTINGS ##
$title    = $pagetitle . " - " . $where . "";
$time_end = generatetime();
$time     = round($time_end - $time_start, 4);
page($index, $title, $where, $time);
## OUTPUT BUFFER END ##
gz_output();
?>
