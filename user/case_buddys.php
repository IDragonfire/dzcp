<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

if(defined('_UserMenu')) {
    $where = _site_user_buddys;
    if(!$chkMe)
        $index = error(_error_have_to_be_logged, 1);
    else {
        $qry = db("SELECT buddy FROM ".$db['buddys']." WHERE user = ".$userid);
        $too = ""; $buddys = "";
        while($get = _fetch($qry)) {
            $pn = show(_pn_write, array("id" => $get['buddy'], "nick" => data("nick",$get['buddy'])));
            $delete = show(_buddys_delete, array("id" => $get['buddy']));
            $yesnocheck = db("SELECT * FROM ".$db['buddys']." where user = '".$get['buddy']."' AND buddy = '".$userid."'");
            $too = db("SELECT * FROM ".$db['buddys']." where user = '".$get['buddy']."' AND buddy = '".$userid."'",true) ? _buddys_yesicon : _buddys_noicon;

            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $buddys .= show($dir."/buddys_show", array("nick" => autor($get['buddy']),
                                                       "onoff" => onlinecheck($get['buddy']),
                                                       "pn" => $pn,
                                                       "class" => $class,
                                                       "too" => $too,
                                                       "delete" => $delete));
        }

        $qry = db("SELECT id,nick FROM ".$db['users']."
                   WHERE level != 0
                   ORDER BY nick");
        $users = "";
        while($get = _fetch($qry)) {
            $users .= show(_to_users, array("id" => $get['id'],
                                            "nick" => data("nick",$get['id'])));
        }

        $add = show("".$dir."/buddys_add", array("users" => $users,
                                                 "value" => _button_value_addto));

        $index = show($dir."/buddys", array("buddyhead" => _buddyhead,
                                            "nick" => _nick,
                                            "pn" => _pnicon_blank,
                                            "mybuddys" => _buddys,
                                            "addbuddys" => _addbuddys,
                                            "buddynick" => _buddynick,
                                            "delete" => _deleteicon_blank,
                                            "too" => _yesno,
                                            "show" => $buddys,
                                            "add" => $add,
                                            "legende" => _legende,
                                            "yes" => _buddys_yesicon,
                                            "no" => _buddys_noicon,
                                            "legendeaddedtoo" => _buddys_legende_addedtoo,
                                            "legendedontaddedtoo" => _buddys_legende_dontaddedtoo));

        if($do == "add") {
            if($_POST['users'] == "-") {
                $index = error(_error_select_buddy, 1);
            } elseif($_POST['users'] == $userid) {
                $index = error(_error_buddy_self, 1);
            } elseif(!check_buddy($_POST['users'])) {
                $index = error(_error_buddy_already_in, 1);
            } else {
                $qry = db("INSERT INTO ".$db['buddys']."
                           SET `user`   = '".intval($userid)."',
                               `buddy`  = '".intval($_POST['users'])."'");

                $msg = show(_buddy_added_msg, array("user" => autor($userid)));
                $title = _buddy_title;

                db("INSERT INTO ".$db['msg']."
                    SET `datum`     = '".time()."',
                        `von`       = '0',
                        `an`        = '".intval($_POST['users'])."',
                        `titel`     = '".up($title)."',
                        `nachricht` = '".up($msg)."'");

                $index = info(_add_buddy_successful, "?action=buddys");
            }
        } elseif($do == "addbuddy") {
            $user = isset($_GET['id']) ? $_GET['id'] : $_POST['users'];
            if($user == "-") {
                $index = error(_error_select_buddy, 1);
            } elseif($user == $userid) {
                $index = error(_error_buddy_self, 1);
            } elseif(!check_buddy($user)) {
                $index = error(_error_buddy_already_in, 1);
            } else {
                db("INSERT INTO ".$db['buddys']."
                    SET `user`   = '".intval($userid)."',
                        `buddy`  = '".intval($user)."'");

                $msg = show(_buddy_added_msg, array("user" => addslashes(autor($userid))));
                $title = _buddy_title;

                db("INSERT INTO ".$db['msg']."
                    SET `datum`     = '".time()."',
                        `von`       = '0',
                        `an`        = '".intval($user)."',
                        `titel`     = '".up($title)."',
                        `nachricht` = '".up($msg)."'");

                $index = info(_add_buddy_successful, "?action=buddys");
            }
        } elseif($do == "delete") {
            db("DELETE FROM ".$db['buddys']."
                WHERE buddy = ".intval($_GET['id'])."
                AND user = '".$userid."'");

            $msg = show(_buddy_del_msg, array("user" => addslashes(autor($userid))));
            $title = _buddy_title;

            db("INSERT INTO ".$db['msg']."
                SET `datum`     = '".time()."',
                    `von`       = '0',
                    `an`        = '".intval($_GET['id'])."',
                    `titel`     = '".up($title)."',
                    `nachricht` = '".up($msg)."'");

            $index = info(_buddys_delete_successful, "../user/?action=buddys");
        }
    }
}