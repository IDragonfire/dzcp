<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_UserMenu')) {
    $where = _site_user_login;
    if($do == "yes") {
        if(config('securelogin') && ($_POST['secure'] != $_SESSION['sec_login'] || empty($_SESSION['sec_login'])))
            $index = error(_error_invalid_regcode, 1);
        else {
            if(checkpwd($_POST['user'], md5($_POST['pwd']))) {
                $get = db_stmt("SELECT id,user,nick,pwd,email,level,time FROM ".$db['users']." WHERE user = ? AND pwd = ? AND level != '0'", array('ss', up($_POST['user']), md5($_POST['pwd'])),false,true);
                if(!isBanned($get['id'])) {
                    $permanent_key = '';
                    if(isset($_POST['permanent'])) {
                        set_cookie($prev."id",$get['id']);
                        $permanent_key = md5(mkpwd(8));
                        set_cookie($prev."pkey",$permanent_key);
                    }

                    ## Aktualisiere Datenbank ##
                    db("UPDATE ".$db['users']." SET `online` = '1', `sessid` = '".session_id()."', `ip` = '".$_SESSION['ip']."', `pkey` = '".$permanent_key."' WHERE id = '".$get['id']."'");

                    $_SESSION['id']         = $get['id'];
                    $_SESSION['pwd']        = $get['pwd'];
                    $_SESSION['lastvisit']  = $get['time'];
                    $_SESSION['ip']         = $userip;

                    db("UPDATE ".$db['userstats']." SET `logins` = logins+1 WHERE user = ".$get['id']);
                    db("UPDATE ".$db['users']." SET `online` = '1', `sessid` = '".session_id()."', `ip` = '".$userip."', `pkey` = '".$permanent_key."' WHERE id = ".$get['id']);
                    setIpcheck("login(".$get['id'].")");

                    header("Location: ?action=userlobby");
                }
                else
                    $index = error(_login_banned);
            } else {
                $qry = db("SELECT id FROM ".$db['users']." WHERE user = '".up($_POST['user'])."'");
                if(_rows($qry)) {
                    $get = _fetch($qry);
                    setIpcheck("trylogin(".$get['id'].")");
                }

                set_cookie($prev."id","");
                set_cookie($prev."pkey","");
                $index = error(_login_pwd_dont_match);
            }
        }
    } else {
        if(!$chkMe) {
            $secure = config('securelogin') ? show($dir."/secure", array("help" => _login_secure_help, "security" => _register_confirm)) : '';
            $index = show($dir."/login", array("loginhead" => _login_head,
                                               "loginname" => _loginname,
                                               "secure" => $secure,
                                               "lostpwd" => _login_lostpwd,
                                               "permanent" => _login_permanent,
                                               "pwd" => _pwd));
        } else {
            $index = error(_error_user_already_in, 1);
            set_cookie($prev."id","");
            set_cookie($prev."pkey","");
        }
    }
}