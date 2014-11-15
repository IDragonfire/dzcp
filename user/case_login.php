<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_UserMenu')) {
    $where = _site_user_login;
    if($do == "yes") {
        ## Prüfe ob der Secure Code aktiviert ist und richtig eingegeben wurde ##
        switch (isset($_GET['from']) ? $_GET['from'] : 'default') {
            case 'menu': $securimage->namespace = 'menu_login'; break;
            default: $securimage->namespace = 'default'; break;
        }

        if(settings('securelogin') && (!isset($_POST['secure']) || !$securimage->check($_POST['secure'])))
            $index = error(captcha_mathematic ? _error_invalid_regcode_mathematic : _error_invalid_regcode);
        else {
            if(checkpwd($_POST['user'], md5($_POST['pwd']))) {
                $get = db_stmt("SELECT `id`,`user`,`nick`,`pwd`,`email`,`level`,`time` FROM ".$db['users']." WHERE `user` = ? AND `pwd` = ? AND `level` != '0'", array('ss', up($_POST['user']), md5($_POST['pwd'])),false,true);
                if(!isBanned($get['id'])) {
                    $permanent_key = '';
                    if(isset($_POST['permanent'])) {
                        cookie::put('id', $get['id']);
                        $permanent_key = md5(mkpwd(8));
                        if(db_stmt("SELECT `id` FROM `".$db['autologin']."` WHERE `host` = ?", array('s', gethostbyaddr($userip)),true) >= 1) {
                            //Update Autologin
                            db_stmt("UPDATE `".$db['autologin']."` SET `ssid` = '".session_id()."',
                                                                       `pkey` = '".$permanent_key."',
                                                                       `ip` = '".$userip."',
                                                                       `date` = ".time().",
                                                                       `update` = ".time().",
                                                                       `expires` = ".autologin_expire." WHERE `host` = ?", array('s', gethostbyaddr($userip)));
                        } else {
                            //Insert Autologin
                            db_stmt("INSERT INTO `".$db['autologin']."` SET `uid` = ".$get['id'].",
                                                                     `ssid` = '".session_id()."',
                                                                     `pkey` = '".$permanent_key."',
                                                                     `ip` = '".$userip."',
                                                                     `host` = ?,
                                                                     `date` = ".time().",
                                                                     `update` = 0,
                                                                     `expires` = ".autologin_expire.";",array('s', gethostbyaddr($userip)));
                        }                        
                        cookie::put('pkey', $permanent_key);
                        cookie::save();
                    }

                    $_SESSION['id']         = $get['id'];
                    $_SESSION['pwd']        = $get['pwd'];
                    $_SESSION['lastvisit']  = $get['time'];
                    $_SESSION['ip']         = $userip;

                    db("UPDATE ".$db['userstats']." SET `logins` = logins+1 WHERE user = ".$get['id']);
                    db("UPDATE ".$db['users']." SET `online` = '1', `sessid` = '".session_id()."', `ip` = '".$userip."' WHERE id = ".$get['id']);
                    setIpcheck("login(".$get['id'].")");

                    //-> Aktualisiere Ip-Count Tabelle
                    $qry = db("SELECT id FROM `".$db['clicks_ips']."` WHERE `ip` LIKE '".$userip."' AND `uid` = 0");
                    if(_rows($qry)) while($get_ci = _fetch($qry)) { db("UPDATE `".$db['clicks_ips']."` SET `uid` = ".$get['id']." WHERE `id` = ".$get_ci['id'].";"); }

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

                cookie::put('id', '');
                cookie::put('pkey', '');
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
        } else
            $index = error(_error_user_already_in, 1);
    }
}