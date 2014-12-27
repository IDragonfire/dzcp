<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

if(defined('_UserMenu')) {
    $where = _site_user_lostpwd;
    if(!$chkMe) {
        $index = show($dir."/lostpwd", array("head" => _lostpwd_head,
                                             "name" => _loginname,
                                             "value" => _button_value_send,
                                             "security" => _register_confirm,
                                             "email" => _email));

        if($do == "sended") {
            $qry = db_stmt("SELECT id,user,level,pwd FROM ".$db['users']." WHERE `user`= ? AND `email` = ?",
                            array('ss', up($_POST['user']), up($_POST['email'])));
             $get = _fetch($qry);

        if(_rows($qry) && (isset($_POST['secure']) || $securimage->check($_POST['secure']))) {
            $pwd = mkpwd();
            db("UPDATE ".$db['users']." SET `pwd` = '".md5($pwd)."' WHERE id = '".$get['id']."'");
            setIpcheck("pwd(".$get['id'].")");
            $message = show(bbcode_email(re(settings('eml_pwd'))), array("user" => $_POST['user'],"pwd" => $pwd));
            $subject = re(settings('eml_pwd_subj'));
            sendMail($_POST['email'],$subject,$message);
            $index = info(_lostpwd_valid, "../user/?action=login");
        } else {
            setIpcheck("trypwd(".$get['id'].")");
            if(settings('securelogin') && isset($_POST['secure']) && !$securimage->check($_POST['secure']))
                $index = error(captcha_mathematic ? _error_invalid_regcode_mathematic : _error_invalid_regcode,1);
            else
                $index = error(_lostpwd_failed, 1);
            }
        }
    }
    else
        $index = error(_error_user_already_in, 1);
}