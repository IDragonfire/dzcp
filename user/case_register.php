<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

if(defined('_UserMenu')) {
    $where = _site_reg;
    if(!$chkMe) {
        $regcode = "";
        if(settings("regcode")) {
            $regcode = show($dir."/register_regcode", array("confirm" => _register_confirm,
                                                            "confirm_add" => _register_confirm_add,));
        }

        $index = show($dir."/register", array("registerhead" => _register_head,
                                              "error" => "",
                                              "name" => _loginname,
                                              "nick" => _nick,
                                              "pwd" => _pwd,
                                              "pwd2" => _pwd2,
                                              "email" => _email,
                                              "r_name" => "",
                                              "r_nick" => "",
                                              "r_email" => "",
                                              "pflicht" => _contact_pflichtfeld,
                                              "value" => _button_value_reg,
                                              "regcode" => $regcode));
    }
    else
        $index = error(_error_user_already_in, 1);


    if ($do == "add" && !$chkMe) {
        $check_user = db_stmt("SELECT id FROM ".$db['users']." WHERE `user`= ?",
                      array('s', up($_POST['user'])),true);

        $check_nick = db_stmt("SELECT id FROM ".$db['users']." WHERE `nick`= ?",
                      array('s', up($_POST['nick'])),true);

        $check_email = db_stmt("SELECT id FROM ".$db['users']." WHERE `email`= ?",
                       array('s', up($_POST['email'])),true);

        $_POST['user'] = trim($_POST['user']); $_POST['nick'] = trim($_POST['nick']);

        if(empty($_POST['user']) || empty($_POST['nick']) || empty($_POST['email']) || ($_POST['pwd'] != $_POST['pwd2']) || (settings("regcode") && !$securimage->check($_POST['secure'])) || $check_user || $check_nick || $check_email) {

        if(settings("regcode") && !$securimage->check($_POST['secure']))
            $error = show("errors/errortable", array("error" => _error_invalid_regcode));

        if($_POST['pwd2'] != $_POST['pwd'])
            $error = show("errors/errortable", array("error" => _wrong_pwd));

        if(!check_email($_POST['email']))
            $error = show("errors/errortable", array("error" => _error_invalid_email));

        if(empty($_POST['email']))
            $error = show("errors/errortable", array("error" => _empty_email));

        if($check_email)
            $error = show("errors/errortable", array("error" => _error_email_exists));

        if(empty($_POST['nick']))
            $error = show("errors/errortable", array("error" => _empty_nick));

        if($check_nick)
            $error = show("errors/errortable", array("error" => _error_nick_exists));

        if(empty($_POST['user']))
            $error = show("errors/errortable", array("error" => _empty_user));

        if($check_user)
            $error = show("errors/errortable", array("error" => _error_user_exists));

        $regcode = "";
        if(settings("regcode")) {
            $regcode = show($dir."/register_regcode", array("confirm" => _register_confirm,
                                                            "confirm_add" => _register_confirm_add,));
        }

        $index = show($dir."/register", array("registerhead" => _register_head,
                                              "error" => $error,
                                              "name" => _loginname,
                                              "nick" => _nick,
                                              "pwd" => _pwd,
                                              "pwd2" => _pwd2,
                                              "email" => _email,
                                              "r_name" => $_POST['user'],
                                              "r_nick" => $_POST['nick'],
                                              "r_email" => $_POST['email'],
                                              "pflicht" => _contact_pflichtfeld,
                                              "value" => _button_value_reg,
                                              "regcode" => $regcode));
        } else {
            if(empty($_POST['pwd'])) {
                $mkpwd = mkpwd();
                $pwd = md5($mkpwd);
                $msg = _info_reg_valid;
            } else {
                $mkpwd = $_POST['pwd'];
                $pwd = md5($mkpwd);
                $msg = _info_reg_valid_pwd;
            }

            db("INSERT INTO ".$db['users']."
                SET `user`     = '".up($_POST['user'])."',
                    `nick`     = '".up($_POST['nick'])."',
                    `email`    = '".up($_POST['email'])."',
                    `pwd`      = '".$pwd."',
                    `regdatum` = '".time()."',
                    `level`    = '1',
                    `time`     = '".time()."',
                    `status`   = '1'");

            $insert_id = _insert_id();
            db("INSERT INTO ".$db['permissions']." SET `user` = '".intval($insert_id)."'");
            db("INSERT INTO ".$db['userstats']." SET `user` = '".intval($insert_id)."', `lastvisit` = '".time()."'");

            setIpcheck("reg(".$insert_id.")");
            $message = show(bbcode_email(settings('eml_reg')), array("user" => $_POST['user'], "pwd" => $mkpwd));
            sendMail($_POST['email'],re(settings('eml_reg_subj')),$message);
            $index = info(show($msg, array("email" => $_POST['email'])), "../user/?action=login");
        }
    }
}