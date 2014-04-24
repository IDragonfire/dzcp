<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
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
            $get = db_stmt("SELECT id,user,level,pwd FROM ".$db['users']." WHERE `user`= ? AND `email` = ?",
                            array('ss', up($_POST['user']), up($_POST['email'])),false,true);

        if(_rows($qry) && ($_POST['secure'] == $_SESSION['sec_lostpwd'] && $_SESSION['sec_lostpwd'] != NULL)) {
            $pwd = mkpwd();
            db("UPDATE ".$db['users']." SET `pwd` = '".md5($pwd)."' WHERE id = '".$get['id']."'");
            setIpcheck("pwd(".$get['id'].")");
            $message = show(bbcode_email(settings('eml_pwd')), array("user" => $_POST['user'],"pwd" => $pwd));
            $subject = re(settings('eml_pwd_subj'));
            sendMail($_POST['email'],$subject,$message);
            $index = info(_lostpwd_valid, "../user/?action=login");
        } else {
            setIpcheck("trypwd(".$get['id'].")");
            if($_POST['secure'] != $_SESSION['sec_lostpwd'] || empty($_SESSION['sec_lostpwd']))
                $index = error(_error_invalid_regcode,1);
            else
                $index = error(_lostpwd_failed, 1);
            }
        }
    }
    else
        $index = error(_error_user_already_in, 1);
}