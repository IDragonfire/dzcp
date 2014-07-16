<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_UserMenu')) {
    $where = _site_msg;
    if(!$chkMe) {
        $index = error(_error_have_to_be_logged, 1);
    } else {
        switch ($do) {
            case 'show':
                $get = db("SELECT * FROM ".$db['msg']." WHERE id = ".intval($_GET['id']),false,true);
                if($get['von'] == $userid || $get['an'] == $userid) {
                    db("UPDATE ".$db['msg']." SET `readed` = 1 WHERE id = ".intval($_GET['id']));
                    $delete = show(_delete, array("id" => $get['id']));

                    if(!$get['von']) {
                        $answermsg = show(_msg_answer_msg, array("nick" => "MsgBot"));
                        $answer = "&nbsp;";
                    } else {
                        $answermsg = show(_msg_answer_msg, array("nick" => autor($get['von'])));
                        $answer = show(_msg_answer, array("id" => $get['id']));
                    }

                    if($get['sendnews'] == 1 || $get['sendnews'] == 2) {
                        $sendnews = show(_msg_sendnews_user, array("id" => $get['id'], "datum" => $get['datum']));
                    } elseif($get['sendnews'] == 3) {
                        $sendnews = show(_msg_sendnews_done, array("user" => autor($get['sendnewsuser'])));
                    } else {
                        $sendnews = '';
                    }

                    $index = show($dir."/msg_show", array("answermsg" => $answermsg,
                                                          "titel" => re($get['titel']),
                                                          "nachricht" => bbcode($get['nachricht']),
                                                          "answer" => $answer,
                                                          "sendnews" => $sendnews,
                                                          "delete" => $delete));
                }
            break;
            case 'sendnewsdone':
                $qry = db("SELECT id FROM ".$db['msg']." WHERE id = '".intval($_GET['id'])."'");
                while($get = _fetch($qry)) {
                    db("UPDATE ".$db['msg']." SET `sendnews` = 3, `sendnewsuser` = '".((int)$userid)."', `readed`= 1
                        WHERE datum = '".intval($_GET['datum'])."'");

                    $index = info(_send_news_done, "?action=msg&do=show&id=".$get['id']."");
                }
            break;
            case 'showsended':
                $get = db("SELECT * FROM ".$db['msg']." WHERE id = ".intval($_GET['id']),false,true);
                if($get['von'] == $userid || $get['an'] == $userid) {
                    $answermsg = show(_msg_sended_msg, array("nick" => autor($get['an'])));
                    $answer = _back;
                    $index = show($dir."/msg_show", array("answermsg" => $answermsg,
                                                          "titel" => re($get['titel']),
                                                          "nachricht" => bbcode($get['nachricht']),
                                                          "answer" => $answer,
                                                          "sendnews" => "",
                                                          "delete" => ""));
                }
            break;
            case 'answer':
                $get = db("SELECT * FROM ".$db['msg']." WHERE id = ".intval($_GET['id']),false,true);
                if($get['von'] == $userid || $get['an'] == $userid) {
                    $titel = (preg_match("#RE:#is",re($get['titel'])) ? re($get['titel']) : "RE: ".re($get['titel']));
                    $index = show($dir."/answer", array("von" => $userid,
                                                        "an" => $get['von'],
                                                        "titel" => $titel,
                                                        "headtitel" => _msg_titel_answer,
                                                        "titelhead" => _titel,
                                                        "nickhead" => _to,
                                                        "value" => _button_value_msg,
                                                        "bbcodehead" => _bbcode,
                                                        "eintraghead" => _answer,
                                                        "nick" => autor($get['von']),
                                                        "zitat" => zitat(autor($get['von']),$get['nachricht'])));
                }
            break;
            case 'pn':
                if(!$chkMe)
                    $index = error(_error_have_to_be_logged);
                elseif($_GET['id'] == $userid)
                    $index = error(_error_msg_self, 1);
                else {
                    $titel = show(_msg_from_nick, array("nick" => data("nick")));
                    $index = show($dir."/answer", array("von" => $userid,
                                                        "an" => $_GET['id'],
                                                        "titel" => $titel,
                                                        "value" => _button_value_msg,
                                                        "titelhead" => _titel,
                                                        "headtitel" => _msg_titel,
                                                        "nickhead" => _to,
                                                        "bbcodehead" => _bbcode,
                                                        "eintraghead" => _answer,
                                                        "nick" => autor($_GET['id']),
                                                        "zitat" => ""));
                }
            break;
            case 'sendanswer':
                if(empty($_POST['titel'])) {
                    $index = error(_empty_titel, 1);
                } elseif(empty($_POST['eintrag'])) {
                    $index = error(_empty_eintrag, 1);
                } else {
                    db("INSERT INTO ".$db['msg']."
                         SET `datum`      = ".time().",
                             `von`        = ".((int)$_POST['von']).",
                             `an`         = ".((int)$_POST['an']).",
                             `titel`      = '".up($_POST['titel'])."',
                             `nachricht`  = '".up($_POST['eintrag'])."',
                             `see`        = 1");

                    db("UPDATE ".$db['userstats']." SET `writtenmsg` = writtenmsg+1 WHERE `user` = ".((int)$userid));
                    $index = info(_msg_answer_done, "?action=msg");
                }
            break;
            case 'delete':
                $qry = db("SELECT id,see FROM ".$db['msg']." WHERE `an` = '".((int)$userid)."' AND `see_u` = 0");
                while($get = _fetch($qry)) {
                    if(isset($_POST['pe'.$get['id']])) {
                        if(!$get['see'])
                            db("DELETE FROM ".$db['msg']." WHERE `id` = ".intval($_POST['pe'.$get['id']]));
                        else
                            db("UPDATE ".$db['msg']." SET `see_u` = 1 WHERE `id` = ".intval($_POST['pe'.$get['id']]));
                    }
                }

                header("Location: ?action=msg");
            break;
            case 'deletethis':
                $get = db("SELECT see FROM ".$db['msg']." WHERE id = '".intval($_GET['id'])."'",false,true);
                if(!$get['see'])
                    db("DELETE FROM ".$db['msg']." WHERE id = ".intval($_GET['id']));
                else
                    db("UPDATE ".$db['msg']." SET `see_u` = 1 WHERE id = ".intval($_GET['id']));

                $index = info(_msg_deleted, "?action=msg");
            break;
            case 'deletesended':
                $qry = db("SELECT id,see_u FROM ".$db['msg']." WHERE `von` = '".((int)$userid)."' AND `see` = 1");
                while($get = _fetch($qry)) {
                    if(isset($_POST['pa'.$get['id']])) {
                        if($get['see_u'])
                            db("DELETE FROM ".$db['msg']." WHERE `id` = ".intval($_POST['pa'.$get['id']]));
                        else
                            db("UPDATE ".$db['msg']." SET `see` = 0 WHERE `id` = ".intval($_POST['pa'.$get['id']]));
                    }
                }

                header("Location: ?action=msg");
            break;
            case 'new':
                $qry = db("SELECT id,nick FROM ".$db['users']." WHERE id != '".((int)$userid)."' ORDER BY nick"); $users = '';
                while($get = _fetch($qry)) {
                    $users .= show(_to_users, array("id" => $get['id'],
                                                    "selected" => "",
                                                    "nick" => data("nick",$get['id'])));
                }

                $qry = db("SELECT id,user,buddy FROM ".$db['buddys']." WHERE user = ".((int)$userid)." ORDER BY user"); $buddys = '';
                while($get = _fetch($qry)) {
                    $buddys .= show(_to_buddys, array("id" => $get['buddy'],
                                                      "selected" => "",
                                                      "nick" => data("nick",$get['buddy'])));
                }

                $index = show($dir."/new", array("von" => $userid,
                                                 "an" => _to,
                                                 "or" => _or,
                                                 "buddys" => $buddys,
                                                 "users" => $users,
                                                 "value" => _button_value_msg,
                                                 "titelhead" => _titel,
                                                 "titel" => _msg_titel,
                                                 "nickhead" => _nick,
                                                 "bbcodehead" => _bbcode,
                                                 "eintraghead" => _eintrag,
                                                 "posttitel" => "",
                                                 "error" => "",
                                                 "posteintrag" => ""));
            break;
            case 'send':
                if(empty($_POST['titel']) || empty($_POST['eintrag']) || $_POST['buddys'] == "-" && $_POST['users'] == "-" || $_POST['buddys'] != "-"
                   && $_POST['users'] != "-" || $_POST['users'] == $userid || $_POST['buddys'] == $userid) {

                    if(empty($_POST['titel']))
                        $error = _empty_titel;
                    elseif(empty($_POST['eintrag']))
                        $error = _empty_eintrag;
                    elseif($_POST['buddys'] == "-" AND $_POST['users'] == "-")
                        $error = _empty_to;
                    elseif($_POST['buddys'] != "-" AND $_POST['users'] != "-")
                        $error = _msg_to_just_1;
                    elseif($_POST['buddys'] OR $_POST['users'] == $userid)
                        $error = _msg_not_to_me;

                    $error = show("errors/errortable", array("error" => $error));

                    $qry = db("SELECT id FROM ".$db['users']." WHERE id != '".((int)$userid)."' ORDER BY nick"); $users = '';
                    while($get = _fetch($qry)) {
                        $selected = isset($_POST['users']) && $get['id'] == $_POST['users'] ? 'selected="selected"' : '';
                        $users .= show(_to_users, array("id" => $get['id'],
                                                        "nick" => data("nick",$get['id']),
                                                        "selected" => $selected));
                    }

                    $qry = db("SELECT id,user,buddy FROM ".$db['buddys']." WHERE user = ".((int)$userid)); $buddys = '';
                    while($get = _fetch($qry)) {
                        $selected = isset($_POST['buddys']) && $get['buddy'] == $_POST['buddys'] ? 'selected="selected"' : '';
                        $buddys .= show(_to_buddys, array("id" => $get['buddy'],
                                                          "nick" => data("nick",$get['buddy']),
                                                          "selected" => $selected));
                    }

                    $index = show($dir."/new", array("von" => $userid,
                                                     "an" => _to,
                                                     "or" => _or,
                                                     "posttitel" => re($_POST['titel']),
                                                     "posteintrag" => re_bbcode($_POST['eintrag']),
                                                     "postto" => $_POST['buddys']."".$_POST['users'],
                                                     "buddys" => $buddys,
                                                     "value" => _button_value_msg,
                                                     "users" => $users,
                                                     "titelhead" => _titel,
                                                     "titel" => _msg_titel,
                                                     "nickhead" => _nick,
                                                     "bbcodehead" => _bbcode,
                                                     "error" => $error,
                                                     "eintraghead" => _eintrag));
                } else {
                    $to = ($_POST['buddys'] == "-" ? $_POST['users'] : $_POST['buddys']);
                    db("INSERT INTO ".$db['msg']."
                        SET `datum`      = ".time().",
                            `von`        = ".((int)$userid).",
                            `an`         = ".((int)$to).",
                            `titel`      = '".up($_POST['titel'])."',
                            `nachricht`  = '".up($_POST['eintrag'])."',
                            `see`        = 1");

                    db("UPDATE ".$db['userstats']." SET `writtenmsg` = writtenmsg+1 WHERE `user` = ".((int)$userid));
                    $index = info(_msg_answer_done, "?action=msg");
                }
            break;
            default:
                $qry = db("SELECT * FROM ".$db['msg']." WHERE `an` = ".((int)$userid)." AND `see_u` = 0 ORDER BY datum DESC");
                $posteingang = '';
                while($get = _fetch($qry)) {
                    $titel = "-"; $absender = "-"; $date = "-"; $delete = ""; $new = "";
                    if(_rows($qry)) {
                        $absender = !$get['von'] ? _msg_bot : autor($get['von']);
                        $titel = show(_msg_in_title, array("titel" => re($get['titel'])));
                        $delete = _delete;
                        $date = date("d.m.Y H:i", $get['datum'])._uhr;
                        $new = !$get['readed'] && !$get['see_u'] ? _newicon : '';
                    }

                    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                    $posteingang.= show($dir."/posteingang", array("titel" => $titel,
                                                                   "absender" => $absender,
                                                                   "datum" => $date,
                                                                   "class" => $class,
                                                                   "delete" => $delete,
                                                                   "new" => $new,
                                                                   "id" => $get['id']));
                }

                $qry = db("SELECT * FROM ".$db['msg']." WHERE `von` = ".$userid." AND `see` = 1 ORDER BY datum DESC");
                $postausgang = '';
                while($get = _fetch($qry)) {
                    $titel = show(_msg_out_title, array("titel" => re($get['titel'])));
                    $delete = _msg_delete_sended;
                    $date = date("d.m.Y H:i", $get['datum'])._uhr;
                    $readed = !$get['readed'] ? _noicon : _yesicon;
                    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                    $postausgang.= show($dir."/postausgang", array("titel" => $titel,
                                                                   "empfaenger" => autor($get['an']),
                                                                   "datum" => $date,
                                                                   "class" => $class,
                                                                   "readed" => $readed,
                                                                   "delete" => $delete,
                                                                   "id" => $get['id']));
                }

                $msghead = show(_msghead, array("nick" => autor($userid)));
                $index = show($dir."/msg", array("msghead" => $msghead,
                                                 "posteingang" => _posteingang,
                                                 "postausgang" => _postausgang,
                                                 "titel" => _msg_title,
                                                 "del" => _msg_del,
                                                 "absender" => _msg_absender,
                                                 "legende" => _legende,
                                                 "legendemsg" => _legende_msg,
                                                 "legendereaded" => _legende_readed,
                                                 "empfaenger" => _msg_empfaenger,
                                                 "datum" => _datum,
                                                 "new" => _msg_new,
                                                 "newicon" => _newicon,
                                                 "yesno" => _yesno,
                                                 "deleteicon" => _deleteicon_blank,
                                                 "showincoming" => $posteingang,
                                                 "showsended" => $postausgang));
            break;
        }
    }
}