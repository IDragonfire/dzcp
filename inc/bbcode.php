<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

## Error Reporting ##
if(!defined('DEBUG_LOADER'))
    exit('<b>Die Debug-Console wurde nicht geladen!<p>
    Bitte &Uuml;berpr&uuml;fen Sie ob die index.php einen "include(basePath."/inc/debugger.php");" Eintrag hat.</b>');

## INCLUDES/REQUIRES ##
require_once(basePath.'/inc/crypt.php');
require_once(basePath.'/inc/sessions.php');
require_once(basePath.'/inc/secure.php');
require_once(basePath.'/inc/_version.php');
require_once(basePath.'/inc/pop3.php');
require_once(basePath.'/inc/smtp.php');
require_once(basePath.'/inc/phpmailer.php');
require_once(basePath."/inc/cookie.php");
require_once(basePath.'/inc/server_query/_functions.php');
require_once(basePath."/inc/teamspeak_query.php");
require_once(basePath."/inc/phpfastcache/phpfastcache.php");
require_once(basePath.'/inc/steamapi.php');
require_once(basePath.'/inc/sfs.php');
require_once(basePath.'/inc/securimage/securimage_color.php');
require_once(basePath.'/inc/securimage/securimage.php');

## Is AjaxJob ##
$ajaxJob = (!isset($ajaxJob) ? false : $ajaxJob);

//Cache
$config_cache['htaccess'] = true;
$config_cache['fallback'] = array( "memcache" => "apc", "memcached" =>  "apc", "apc" =>  "sqlite", "sqlite" => "files");
$config_cache['path'] = basePath."/inc/_cache_";

if(!is_dir($config_cache['path'])) //Check cache dir
    mkdir($config_cache['path'], 0777, true);

$config_cache['securityKey'] = settings('prev',false);
phpFastCache::setup($config_cache);
$cache = new phpFastCache();
$securimage = new Securimage();
dbc_index::init();

//-> Automatische Datenbank Optimierung
if(auto_db_optimize && settings('db_optimize',false) <= time() && !$installer && !$updater) {
    @ignore_user_abort(true);
    db("UPDATE `".$db['settings']."` SET `db_optimize` = ".(time()+auto_db_optimize_interval)." WHERE `id` = 1;");
    sfs::cleanup_db(); db_optimize(); $securimage->clearOldCodesFromDatabase();
    setIpcheck("db_optimize()");
    @ignore_user_abort(false);
}

//-> Settingstabelle auslesen * Use function settings('xxxxxx');
if(!dbc_index::issetIndex('settings')) {
    $get_settings = db("SELECT * FROM `".$db['settings']."`;",false,true);
    dbc_index::setIndex('settings', $get_settings);
    unset($get_settings);
}

//-> Configtabelle auslesen * Use function config('xxxxxx');
if(!dbc_index::issetIndex('config')) {
    $get_config = db("SELECT * FROM `".$db['config']."`;",false,true);
    dbc_index::setIndex('config', $get_config);
    unset($get_config);
}

//-> Cookie initialisierung
cookie::init('dzcp_'.settings('prev'));

//-> SteamAPI
SteamAPI::set('apikey',re(settings('steam_api_key')));

//-> Language auslesen
$language = (cookie::get('language') != false ? (file_exists(basePath.'/inc/lang/languages/'.cookie::get('language').'.php') ? cookie::get('language') : re(settings('language'))) : re(settings('language')));

//-> einzelne Definitionen
$isSpider = isSpider();
$subfolder = basename(dirname(dirname($_SERVER['PHP_SELF']).'../'));
$httphost = $_SERVER['HTTP_HOST'].(empty($subfolder) ? '' : '/'.$subfolder);
$domain = str_replace('www.','',$httphost);
$pagetitle = settings('pagetitel');
$sdir = settings('tmpdir');
$useronline = 1800;
$reload = 3600 * 24;
$datum = time();
$today = date("j.n.Y");
$picformat = array("jpg", "gif", "png");
$userip = visitorIp();
$maxpicwidth = 90;
$maxadmincw = 10;
$maxfilesize = @ini_get('upload_max_filesize');
$UserAgent = trim(GetServerVars('HTTP_USER_AGENT'));

//-> Global
$action = isset($_GET['action']) ? $_GET['action'] : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$do = isset($_GET['do']) ? $_GET['do'] : '';
$index = ''; $show = ''; $color = 0;

//-> Neue Kernel Funktionen einbinden, sofern vorhanden
if($functions_files = get_files(basePath.'/inc/additional-kernel/',false,true,array('php'))) {
    foreach($functions_files AS $func)
    { include(basePath.'/inc/additional-kernel/'.$func); }
    unset($functions_files,$func);
}

/**
 * Pruft eine IP gegen eine IP-Range
 * @param ipv4 $ip
 * @param ipv4 range $range
 * @return boolean
 */
function validateIpV4Range ($ip, $range) {
    if(!is_array($range)) {
        $counter = 0;
        $tip = explode ('.', $ip);
        $rip = explode ('.', $range);
        foreach ($tip as $targetsegment) {
            $rseg = $rip[$counter];
            $rseg = preg_replace ('=(\[|\])=', '', $rseg);
            $rseg = explode ('-', $rseg);
            if (!isset($rseg[1]))
                $rseg[1] = $rseg[0];

            if ($targetsegment < $rseg[0] || $targetsegment > $rseg[1])
                return false;

            $counter++;
        }
    } else {
        foreach ($range as $range_num) {
            $counter = 0;
            $tip = explode ('.', $ip);
            $rip = explode ('.', $range_num);
            foreach ($tip as $targetsegment)
            {
                $rseg = $rip[$counter];
                $rseg = preg_replace ('=(\[|\])=', '', $rseg);
                $rseg = explode ('-', $rseg);
                if (!isset($rseg[1]))
                    $rseg[1] = $rseg[0];

                if ($targetsegment < $rseg[0] || $targetsegment > $rseg[1])
                    return false;

                $counter++;
            }
        }
    }

    return true;
}

// -> Pruft ob die IP gesperrt und gultig ist
function check_ip() {
    global $db,$ajaxJob,$isSpider,$userip;
    if(!$ajaxJob && !$isSpider && !isIP($userip, true)) {
        if((!isIP($userip) && !isIP($userip,true)) || $userip == false || empty($userip)) {
            dzcp_session_destroy();
            die('Deine IP ist ung&uuml;ltig!<p>Your IP is invalid!');
        }

        //Banned IP
        $banned_ip_sql = db("SELECT `id`,`typ` FROM `".$db['ipban']."` WHERE `ip` = '".$userip."' AND `enable` = 1;");
        if(_rows($banned_ip_sql) >= 1) {
            while($banned_ip = _fetch($banned_ip_sql))
            if($banned_ip['typ'] == '2' || $banned_ip['typ'] == '3') {
                dzcp_session_destroy();
                die('Deine IP ist gesperrt!<p>Your IP is banned!');
            }
        }

        unset($banned_ip,$banned_ip_sql);
        if(allow_url_fopen_support() && !isIP($userip) && !validateIpV4Range($userip, '[192].[168].[0-255].[0-255]') && 
        !validateIpV4Range($userip, '[127].[0].[0-255].[0-255]') && 
        !validateIpV4Range($userip, '[10].[0-255].[0-255].[0-255]') && 
        !validateIpV4Range($userip, '[172].[16-31].[0-255].[0-255]')) {
            sfs::check(); //SFS Update
            if(sfs::is_spammer()) {
                db("DELETE FROM `".$db['ip2dns']."` WHERE `sessid` = `".session_id()."`;");
                dzcp_session_destroy();
                die('Deine IP-Adresse ist auf <a href="http://www.stopforumspam.com/" target="_blank">http://www.stopforumspam.com/</a> gesperrt, die IP wurde zu oft f√ºr Spam Angriffe auf Webseiten verwendet.<p>
                     Your IP address is known on <a href="http://www.stopforumspam.com/" target="_blank">http://www.stopforumspam.com/</a>, your IP has been used for spam attacks on websites.');
            }
        }
    }
}

check_ip(); // IP Prufung * No IPV6 Support *

function dzcp_session_destroy() {
    $_SESSION['id']        = '';
    $_SESSION['pwd']       = '';
    $_SESSION['ip']        = '';
    $_SESSION['lastvisit'] = '';
    session_unset();
    session_destroy();
    session_regenerate_id();
    cookie::clear();
}

//-> Auslesen der Cookies und automatisch anmelden
if(cookie::get('id') != false && cookie::get('pkey') != false && empty($_SESSION['id']) && !checkme()) {
    //-> Permanent Key aus der Datenbank suchen
    $sql = db_stmt("SELECT `id`,`uid`,`update`,`expires` FROM `".$db['autologin']."` WHERE `pkey` = ? AND `uid` = ?;",array('ss', cookie::get('pkey'), cookie::get('id')));
    if(_rows($sql)) {
        $get_almgr = _fetch($sql);
        if((!$get_almgr['update'] || (time() < ($get_almgr['update'] + $get_almgr['expires'])))) {
            //-> User aus der Datenbank suchen
            $sql = db_stmt("SELECT `id`,`user`,`nick`,`pwd`,`email`,`level`,`time` FROM `".$db['users']."` WHERE `id` = ? AND `level` != 0;",array('i', cookie::get('id')));
            if(_rows($sql)) {
                $get = _fetch($sql);
              
                //-> Generiere neuen permanent-key
                $permanent_key = md5(mkpwd(8));
                cookie::put('pkey', $permanent_key);
                cookie::save();
                
                //Update Autologin
                db("UPDATE `".$db['autologin']."` SET `ssid` = '".session_id()."',
                                                      `pkey` = '".$permanent_key."',
                                                      `ip` = '".visitorIp()."',
                                                      `host` = '".gethostbyaddr(visitorIp())."',
                                                      `update` = ".time().",
                                                      `expires` = ".autologin_expire." WHERE `id` = ".$get_almgr['id'].";");

                //-> Schreibe Werte in die Server Sessions
                $_SESSION['id']         = $get['id'];
                $_SESSION['pwd']        = $get['pwd'];
                $_SESSION['lastvisit']  = $get['time'];
                $_SESSION['ip']         = visitorIp();

                if(data("ip",$get['id']) != $_SESSION['ip'])
                    $_SESSION['lastvisit'] = data("time",$get['id']);

                if(empty($_SESSION['lastvisit']))
                    $_SESSION['lastvisit'] = data("time",$get['id']);

                //-> Aktualisiere Datenbank
                db("UPDATE `".$db['users']."` SET `online` = '1', `sessid` = '".session_id()."', `ip` = '".$_SESSION['ip']."' WHERE `id` = ".$get['id'].";");

                //-> Aktualisiere die User-Statistik
                db("UPDATE `".$db['userstats']."` SET `logins` = logins+1 WHERE `user` = ".$get['id'].";");

                //-> Aktualisiere Ip-Count Tabelle
                $qry = db("SELECT `id` FROM `".$db['clicks_ips']."` WHERE `ip` = '".$userip."' AND `uid` = 0;");
                if(_rows($qry)) while($get_ci = _fetch($qry)) { db("UPDATE `".$db['clicks_ips']."` SET `uid` = ".$get['id']." WHERE `id` = ".$get_ci['id'].";"); }

                unset($get,$permanent_key,$get_almgr);
            } else {
                dzcp_session_destroy();
                $_SESSION['id']        = '';
                $_SESSION['pwd']       = '';
                $_SESSION['ip']        = '';
                $_SESSION['lastvisit'] = '';
                $_SESSION['pkey']      = '';
            }

            unset($sql);
        } else {
            db("DELETE FROM `".$db['autologin']."` WHERE `id` = ".$get_almgr['id'].";");
            dzcp_session_destroy();
        }
    }
}

//-> Sprache aendern
if(isset($_GET['set_language']) && !empty($_GET['set_language'])) {
    if(file_exists(basePath."/inc/lang/languages/".$_GET['set_language'].".php")) {
        cookie::put('language', $_GET['set_language']);
        cookie::save();
    }

    header("Location: ".$_SERVER['HTTP_REFERER']);
}

lang($language); //Lade Sprache
$userid = userid();
$chkMe = checkme();
if(!$chkMe) {
    $_SESSION['id']        = '';
    $_SESSION['pwd']       = '';
    $_SESSION['ip']        = '';
    $_SESSION['lastvisit'] = '';
}

//-> Prueft ob der User gebannt ist, oder die IP des Clients warend einer offenen session ver√§ndert wurde.
if($chkMe && $userid && !empty($_SESSION['ip'])) {
    if($_SESSION['ip'] != visitorIp() || isBanned($userid,false) ) {
        dzcp_session_destroy();
        header("Location: ../news/");
    }
}

/*
 * DZCP V1.6.1
 * Aktualisiere die Client DNS & User Agent
 */
if(session_id()) {
    if(db("SELECT `id` FROM `".$db['ip2dns']."` WHERE `update` <= ".time()." AND `sessid` = '".session_id()."';",true)) {
        $bot = SearchBotDetect();
        db("UPDATE `".$db['ip2dns']."` SET `time` = ".(time()+10*60).", `update` = ".(time()+60).", `ip` = '".$userip."', "
        . "`agent` = '".up($UserAgent)."', `dns` = '".up(gethostbyaddr($userip))."', `bot` = ".($bot['bot'] ? 1 : 0).", "
        . "`bot_name` = '".up($bot['name'])."', `bot_fullname` = '".up($bot['fullname'])."' WHERE `sessid` = '".session_id()."';"); unset($bot);
    } else {
        if(!db("SELECT `id` FROM `".$db['ip2dns']."` WHERE `sessid` = '".session_id()."';",true) ) {
            $bot = SearchBotDetect();
            db("INSERT INTO `".$db['ip2dns']."` SET `sessid` = '".session_id()."', `time` = ".(time()+10*60).", `ip` = '".$userip."', "
            . "`agent` = '".up($UserAgent)."', `dns` = '".up(gethostbyaddr($userip))."', `bot` = ".($bot['bot'] ? 1 : 0).", "
            . "`bot_name` = '".up($bot['name'])."', `bot_fullname` = '".up($bot['fullname'])."';"); unset($bot);
        }
    }

    //-> Cleanup DNS DB
    $qryDNS = db("SELECT `id` FROM `".$db['ip2dns']."` WHERE `time` <= ".time().";");
    if(_rows($qryDNS) >= 1) {
        while($getDNS = _fetch($qryDNS)) {
            db("DELETE FROM `".$db['ip2dns']."` WHERE `id` = ".$getDNS['id'].";");
        } unset($getDNS);
    } unset($qryDNS);
}

/**
* DZCP V1.6.1
* Erkennt bekannte Bots am User Agenten
*/
function SearchBotDetect() { 
    global $UserAgent,$db;
    $sql = db("SELECT * FROM `".$db['botlist']."` WHERE `enabled` = 1;");
    if(_rows($sql) >= 1) {
        while ($botdata = _fetch($sql)) {
            switch ($botdata['type']) {
                case 1:
                    if(preg_match(re($botdata['regexpattern']), $UserAgent, $matches)) {
                        return array('fullname' => re($botdata['name'])." V".trim($matches[1]), 'name' =>re($botdata['name']), 'bot' => true); 
                    }
                break;
                case 2:
                    if(preg_match(re($botdata['regexpattern']), $UserAgent, $matches)) {
                        list($majorVer, $minorVer) = explode(".", $matches[1]);
                        return array('fullname' => re($botdata['name'])." V".trim($majorVer).'.'.trim($minorVer), 'name' =>re($botdata['name']), 'bot' => true); 
                    } 
                break;
                case 3:
                    if(preg_match(re($botdata['regexpattern']), $UserAgent, $matches)) {
                        list($majorVer, $minorVer, $build) = explode(".", $matches[1]);
                        return array('fullname' => re($botdata['name'])." V".trim($majorVer).'.'.trim($minorVer).'.'.trim($build), 'name' =>re($botdata['name']), 'bot' => true); 
                    } 
                break;
                default:
                     if(preg_match(re($botdata['regexpattern']), $UserAgent)) {
                        if(empty($botdata['name_extra'])) $botdata['name_extra'] = $botdata['name'];
                        return array('fullname' => re($botdata['name_extra']), 'name' => re($botdata['name']), 'bot' => true); 
                    }
                break;
            }
        }
    }
    
    return array('fullname'=>'',"name"=>'',"bot"=>false); 
}

/**
* DZCP V1.6.1
* Browser-Cache nicht verwenden -> Ajax
*/
function addNoCacheHeaders() {
    header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

/**
* DZCP V1.6.1
* Gibt die IP des Besuchers / Users zuruck
* Forwarded IP Support
*/
function visitorIp() {
    $SetIP = '0.0.0.0';
    $ServerVars = array('REMOTE_ADDR','HTTP_CLIENT_IP','HTTP_X_FORWARDED_FOR','HTTP_X_FORWARDED',
    'HTTP_FORWARDED_FOR','HTTP_FORWARDED','HTTP_VIA','HTTP_X_COMING_FROM','HTTP_COMING_FROM');
    foreach ($ServerVars as $ServerVar) {
        if($IP=detectIP($ServerVar)) {
            if(isIP($IP) && !validateIpV4Range($IP, '[192].[168].[0-255].[0-255]') && 
		!validateIpV4Range($IP, '[127].[0].[0-255].[0-255]') && 
		!validateIpV4Range($IP, '[10].[0-255].[0-255].[0-255]') && 
		!validateIpV4Range($IP, '[172].[16-31].[0-255].[0-255]')) {
                    return $IP;
            } else $SetIP = $IP;
            
            if(isIP($IP,true)) //IPV6
                return $IP;
        }
    }
    
    return $SetIP;
}

function detectIP($var) {
    if(!empty($var) && ($REMOTE_ADDR = GetServerVars($var)) && !empty($REMOTE_ADDR)) {
        $REMOTE_ADDR = trim($REMOTE_ADDR);
        if(isIP($REMOTE_ADDR) || isIP($REMOTE_ADDR,true))
             return $REMOTE_ADDR;
    }
    
    return false;
}

/**
 * Check given ip for ipv6 or ipv4.
 * @param    string        $ip
 * @param    boolean       $v6
 * @return   boolean
 */
function isIP($ip,$v6=false) {
    if(!$v6 && substr_count($ip,":") < 1) {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ? true : false;
    }
    
    return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ? true : false;
}

/**
 * Funktion um notige Erweiterungen zu prufen
 * @return boolean
 **/
function fsockopen_support() {
    if(fsockopen_support_bypass) return true;

    if(disable_functions('fsockopen') || disable_functions('fopen'))
        return false;

    return true;
}

function disable_functions($function='') {
    if(!function_exists($function)) return true;
    $disable_functions = ini_get('disable_functions');
    if(empty($disable_functions)) return false;
    $disabled_array = explode(',', $disable_functions);
    foreach ($disabled_array as $disabled) {
       if(strtolower(trim($function)) == strtolower(trim($disabled)))
            return true;
    }

    return false;
}

function allow_url_fopen_support() {
    if(ini_get('allow_url_fopen') == 1)
        return true;

    return false;
}

/**
 * Auslesen der UserID
 * @return integer
 **/
function userid() {
    global $db;
    if(empty($_SESSION['id']) || empty($_SESSION['pwd'])) return 0;
    if(!dbc_index::issetIndex('user_'.$_SESSION['id'])) {
        $sql = db("SELECT * FROM `".$db['users']."` WHERE `id` = ".intval($_SESSION['id'])." AND `pwd` = '".$_SESSION['pwd']."';");
        if(!_rows($sql)) return 0;
        $get = _fetch($sql);
        dbc_index::setIndex('user_'.$get['id'], $get);
        return $get['id'];
    }

    return dbc_index::getIndexKey('user_'.$_SESSION['id'], 'id');
}

//-> Templateswitch
$files = get_files(basePath.'/inc/_templates_/',true);
if(isset($_GET['tmpl_set'])) {
    foreach ($files as $templ) {
        if($templ == $_GET['tmpl_set']) {
            cookie::put('tmpdir', $templ);
            cookie::save();
            header("Location: ".$_SERVER['HTTP_REFERER']);
        }
    }
}

if(cookie::get('tmpdir') != false && cookie::get('tmpdir') != NULL) {
    if(file_exists(basePath."/inc/_templates_/".cookie::get('tmpdir')))
        $tmpdir = cookie::get('tmpdir');
    else
        $tmpdir = $files[0];
} else {
    if(file_exists(basePath."/inc/_templates_/".$sdir))
        $tmpdir = $sdir;
    else
        $tmpdir = $files[0];
}
unset($files);

$designpath = '../inc/_templates_/'.$tmpdir;

//-> Languagefiles einlesen
function lang($lng,$pfad='') {
    global $charset;
    if(!file_exists(basePath."/inc/lang/languages/".$lng.".php")) {
        $files = get_files(basePath.'/inc/lang/languages/',false,true,array('php'));
        $lng = str_replace('.php','',$files[0]);
    }

    include(basePath."/inc/lang/global.php");
    include(basePath."/inc/lang/languages/".$lng.".php");
}

/**
 * Sprachdateien auflisten
 * @return string/html
 **/
function languages() {
    $lang="";
    $files = get_files(basePath.'/inc/lang/languages/',false,true,array('php'));
    for($i=0;$i<=count($files)-1;$i++) {
        $file = str_replace('.php','',$files[$i]);
        $upFile = strtoupper(substr($file,0,1)).substr($file,1);
        if(file_exists('../inc/lang/flaggen/'.$file.'.gif'))
            $lang .= '<a href="?set_language='.$file.'"><img src="../inc/lang/flaggen/'.$file.'.gif" alt="'.$upFile.'" title="'.$upFile.'" class="icon" /></a> ';
    }

    return $lang;
}

//-> Userspezifiesche Dinge
if($userid >= 1 && $ajaxJob != true && isset($_SESSION['lastvisit']))
    db("UPDATE `".$db['userstats']."` SET `hits` = hits+1, `lastvisit` = ".intval($_SESSION['lastvisit'])." WHERE `user` = ".$userid.";");

//-> Settings auslesen
function settings($what,$use_dbc=true) {
    global $db;

    if(is_array($what)) {
        if($use_dbc)
            $dbd = dbc_index::getIndex('settings');
        else
            $dbd = db("SELECT * FROM `".$db['settings']."`;",false,true);

        $return = array();
        foreach ($dbd as $key => $var) {
            if(!in_array($key,$what))
                continue;

            $return[$key] = $var;
        }

        return $return;
    } else {
        if($use_dbc)
            return dbc_index::getIndexKey('settings', $what);

        $get = db("SELECT `".$what."` FROM `".$db['settings']."`;",false,true);
        return $get[$what];
    }
}

//-> Config auslesen
function config($what,$use_dbc=true) {
    global $db;

    if(is_array($what)) {
        if($use_dbc)
            $dbd = dbc_index::getIndex('config');
        else
            $dbd = db("SELECT * FROM `".$db['config']."`;",false,true);

        $return = array();
        foreach ($dbd as $key => $var) {
            if(!in_array($key,$what))
                continue;

            $return[$key] =  $var;
        }

        return $return;
    } else {
        if($use_dbc)
            return dbc_index::getIndexKey('config', $what);

        $get = db("SELECT `".$what."` FROM `".$db['config']."`;",false,true);
        return $get[$what];
    }
}

//-> Prueft ob der User ein Rootadmin ist
function rootAdmin($userid=0) {
    global $rootAdmins;
    $userid = (!$userid ? userid() : $userid);
    if(!count($rootAdmins)) return false;
    return in_array($userid, $rootAdmins);
}

//-> PHP-Code farbig anzeigen
function highlight_text($txt) {
    while(preg_match("=\[php\](.*)\[/php\]=Uis",$txt)!=FALSE) {
        $res = preg_match("=\[php\](.*)\[/php\]=Uis",$txt,$matches);
        $src = $matches[1];
        $src = str_replace('<?php','',$src);
        $src = str_replace('<?php','',$src);
        $src = str_replace('?>','',$src);
        $src = str_replace("&#39;", "'", $src);
        $src = str_replace("&#34;", "\"", $src);
        $src = str_replace("&amp;","&",$src);
        $src = str_replace("&lt;","<",$src);
        $src = str_replace("&gt;",">",$src);
        $src = str_replace('<?php','&#60;?',$src);
        $src = str_replace('?>','?&#62;',$src);
        $src = str_replace("&quot;","\"",$src);
        $src = str_replace("&nbsp;"," ",$src);
        $src = str_replace("&nbsp;"," ",$src);
        $src = str_replace("<p>","\n",$src);
        $src = str_replace("</p>","",$src);
        $l = explode("<br />", $src);
        $src = preg_replace("#\<br(.*?)\>#is","\n",$src);
        $src = '<?php'.$src.' ?>';
        $colors = array('#111111' => 'string', '#222222' => 'comment', '#333333' => 'keyword', '#444444' => 'bg',     '#555555' => 'default', '#666666' => 'html');

        foreach ($colors as $color => $key)
            ini_set('highlight.'.$key, $color);

        // Farben ersetzen & highlighten
        $src = preg_replace('!style="color: (#\d{6})"!e','"class=\"".$prefix.$colors["\1"]."\""',highlight_string($src, TRUE));

        // PHP-Tags komplett entfernen
        $src = str_replace('&lt;?php','',$src);
        $src = str_replace('?&gt;','',$src);
        $src = str_replace('&amp;</span><span class="comment">#60;?','&lt;?',$src);
        $src = str_replace('?&amp;</span><span class="comment">#62;','?&gt;',$src);
        $src = str_replace('&amp;#60;?','&lt;?',$src);
        $src = str_replace('?&amp;#62;','?&gt;',$src);
        $src = str_replace(":", "&#58;", $src);
        $src = str_replace("(", "&#40;", $src);
        $src = str_replace(")", "&#41;", $src);
        $src = str_replace("^", "&#94;", $src);

        // Zeilen zaehlen
        $lines = "";
        for($i=1;$i<=count($l)+1;$i++)
            $lines .= $i.".<br />";

        // Ausgabe
        $code = '<div class="codeHead">&nbsp;&nbsp;&nbsp;Code:</div><div class="code"><table style="width:100%;padding:0px" cellspacing="0"><tr><td class="codeLines">'.$lines.'</td><td class="codeContent">'.$src.'</td></table></div>';
        $txt = preg_replace("=\[php\](.*)\[/php\]=Uis",$code,$txt,1);
    }

    return $txt;
}

function regexChars($txt) {
    $search  = array('"', '\\', '<', '>', '/',
    '.', ':', '^', '$', '|',
    '?', '*', '+', '-', '(',
    ')', '[', ']', '}', '{',
    "\r", "\n" );

    $replace = array('&quot;', '\\\\', '\<', '\>', '\/',
    '\.', '\:', '\^', '\$', '\|',
    '\?', '\*', '\+', '\-', '\(',
    '\)', '\[', '\]', '\}', '\{',
    '', '' );

    return str_replace($search,$replace,strip_tags($txt));
}

//-> Glossarfunktion
$use_glossar = true; //Global
function glossar_load_index() {
    global $db,$use_glossar;
    if(!$use_glossar) return false;

    $gl_words = array(); $gl_desc = array();
    $qryglossar = db("SELECT `word`,`glossar` FROM `".$db['glossar']."`;");
    while($getglossar = _fetch($qryglossar)) {
        $gl_words[] = re($getglossar['word']);
        $gl_desc[]  = $getglossar['glossar'];
    }

    dbc_index::setIndex('glossar', array('gl_words' => $gl_words, 'gl_desc' => $gl_desc));
}

function glossar($txt) {
    global $db,$gl_words,$gl_desc,$use_glossar,$ajaxJob;

    if(!$use_glossar || $ajaxJob)
        return $txt;

    if(!dbc_index::issetIndex('glossar'))
        glossar_load_index();

    $gl_words = dbc_index::getIndexKey('glossar', 'gl_words');
    $gl_desc = dbc_index::getIndexKey('glossar', 'gl_desc');
    $txt = str_replace(array('&#93;','&#91;'),array(']','['),$txt);

    // mark words
    if(count($gl_words) >= 1) {
        foreach($gl_words as $gl_word) {
            $w = addslashes(regexChars(html_entity_decode($gl_word)));
            $search  = array(' '.$w.' ', '>'.$w.'<', '>'.$w.' ',' '.$w.'<');
            $replace = array(' <tmp|'.$w.'|tmp> ','> <tmp|'.$w.'|tmp> <','> <tmp|'.$w.'|tmp> ',' <tmp|'.$w.'|tmp> <');
            $txt = str_ireplace($search, $replace, $txt);
        }

        // replace words
        for($g=0;$g<=count($gl_words)-1;$g++) {
            $desc = regexChars($gl_desc[$g]);
            $info = 'onmouseover="DZCP.showInfo(\''.jsconvert($desc).'\')" onmouseout="DZCP.hideInfo()"';
            $w = regexChars(html_entity_decode($gl_words[$g]));
            $r = "<a class=\"glossar\" href=\"../glossar/?word=".$gl_words[$g]."\" ".$info.">".$gl_words[$g]."</a>";
            $txt = str_ireplace('<tmp|'.$w.'|tmp>', $r, $txt);
        }

        unset($w,$r,$info,$desc,$gl_word);
    }

    return str_replace(array(']','['),array('&#93;','&#91;'),$txt);
}

function bbcodetolow($founds) {
    return "[".strtolower($founds[1])."]".trim($founds[2])."[/".strtolower($founds[3])."]";
}

//-> Replaces
function replace($txt,$type=false,$no_vid_tag=false) {
    $txt = str_replace("&#34;","\"",$txt);

    if($type)
        $txt = preg_replace("#<img src=\"(.*?)\" mce_src=\"(.*?)\"(.*?)\>#i","<img src=\"$2\" alt=\"\">",$txt);

    $txt = preg_replace_callback("/\[(.*?)\](.*?)\[\/(.*?)\]/","bbcodetolow",$txt);
    $var = array("/\[url\](.*?)\[\/url\]/",
                 "/\[img\](.*?)\[\/img\]/",
                 "/\[url\=(http\:\/\/)?(.*?)\](.*?)\[\/url\]/",
                 "/\[b\](.*?)\[\/b\]/",
                 "/\[i\](.*?)\[\/i\]/",
                 "/\[u\](.*?)\[\/u\]/",
                 "/\[color=(.*?)\](.*?)\[\/color\]/");

    $repl = array("<a href=\"$1\" target=\"_blank\">$1</a>",
                  "<img src=\"$1\" class=\"content\" alt=\"\" />",
                  "<a href=\"http://$2\" target=\"_blank\">$3</a>",
                  "<b>$1</b>",
                  "<i>$1</i>",
                  "<u>$1</u>",
                  "<span style=\"color:$1\">$2</span>");

    $txt = preg_replace($var,$repl,$txt);
    $txt = preg_replace_callback("#\<img(.*?)\>#", create_function('$img','if(preg_match("#class#i",$img[1])) return "<img".$img[1].">"; else return "<img class=\"content\"".$img[1].">";'), $txt);

    if(!$no_vid_tag) {
        $txt = preg_replace_callback("/\[youtube\](?:http?s?:\/\/)?(?:www\.)?youtu(?:\.be\/|be\.com\/watch\?v=)([A-Z0-9\-_]+)(?:&(.*?))?\[\/youtube\]/i",
                create_function('$match','return \'<object width="425" height="344"><param name="movie" value="//www.youtube.com/v/\'.trim($match[1]).\'?hl=de_DE&amp;version=3&amp;rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="//www.youtube.com/v/\'.trim($match[1]).\'?hl=de_DE&amp;version=3&amp;rel=0" type="application/x-shockwave-flash" width="425" height="344" allowscriptaccess="always" allowfullscreen="true"></embed></object>\';'), $txt);
    }

    $txt = str_replace("\"","&#34;",$txt);
    return preg_replace("#(\w){1,1}(&nbsp;)#Uis","$1 ",$txt);
}

//-> Badword Filter
function BadwordFilter($txt) {
    $words = explode(",",trim(settings('badwords')));
    foreach($words as $word)
    { $txt = preg_replace("#".$word."#i", str_repeat("*", strlen($word)), $txt); }
    return $txt;
}

//-> Funktion um Bestimmte Textstellen zu markieren
function hl($text, $word) {
    if(!empty($_GET['hl']) && $_SESSION['search_type'] == 'text') {
        if($_SESSION['search_con'] == 'or') {
            $words = explode(" ",$word);
            for($x=0;$x<count($words);$x++)
                $ret['text'] = preg_replace("#".$words[$x]."#i",'<span class="fontRed" title="'.$words[$x].'">'.$words[$x].'</span>',$text);
        }
        else
            $ret['text'] = preg_replace("#".$word."#i",'<span class="fontRed" title="'.$word.'">'.$word.'</span>',$text);

        if(!preg_match("#<span class=\"fontRed\" title=\"(.*?)\">#", $ret['text']))
            $ret['class'] = 'class="commentsRight"';
        else
            $ret['class'] = 'class="highlightSearchTarget"';
    } else {
        $ret['text'] = $text;
        $ret['class'] = 'class="commentsRight"';
    }

    return $ret;
}

//-> Leerzeichen mit + ersetzen (w3c)
function convSpace($string) {
    return str_replace(" ","+",$string);
}

//-> BBCode
function re_bbcode($txt) {
    $search  = array("'","[","]","&lt;","&gt;");
    $replace = array("&#39;","&#91;","&#93;","&#60;","&#62;");
    return stripslashes(str_replace($search, $replace, spChars($txt)));
}

/* START # from wordpress under GBU GPL license
   URL autolink function */
function _make_url_clickable_cb($matches) {
    $ret = '';
    $url = $matches[2];

    if ( empty($url) )
        return $matches[0];
    // removed trailing [.,;:] from URL
    if ( in_array(substr($url, -1), array('.', ',', ';', ':')) === true ) {
        $ret = substr($url, -1);
        $url = substr($url, 0, strlen($url)-1);
    }

    return $matches[1] . "<a href=\"$url\" rel=\"nofollow\">$url</a>" . $ret;
}

function _make_web_ftp_clickable_cb($matches) {
    $ret = '';
    $dest = $matches[2];
    $dest = 'http://' . $dest;

    if ( empty($dest) )
        return $matches[0];

    // removed trailing [,;:] from URL
    if ( in_array(substr($dest, -1), array('.', ',', ';', ':')) === true ) {
        $ret = substr($dest, -1);
        $dest = substr($dest, 0, strlen($dest)-1);
    }

    return $matches[1] . "<a href=\"$dest\" rel=\"nofollow\">$dest</a>" . $ret;
}

function _make_email_clickable_cb($matches) {
    $email = $matches[2] . '@' . $matches[3];
    return $matches[1] . "<a href=\"mailto:$email\">$email</a>";
}

function make_clickable($ret) {
    $ret = ' ' . $ret;
    // in testing, using arrays here was found to be faster
    $ret = preg_replace_callback('#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', '_make_url_clickable_cb', $ret);
    $ret = preg_replace_callback('#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is', '_make_web_ftp_clickable_cb', $ret);
    $ret = preg_replace_callback('#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i', '_make_email_clickable_cb', $ret);

    // this one is not in an array because we need it to run last, for cleanup of accidental links within links
    $ret = preg_replace("#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i", "$1$3</a>", $ret);
    return trim($ret);
}

/* END # from wordpress under GBU GPL license */

//Diverse BB-Codefunktionen
function bbcode($txt, $tinymce=false, $no_vid=false, $ts=false, $nolink=false) {
    global $charset;

    $txt = html_entity_decode($txt,ENT_COMPAT,$charset);
    if(!$no_vid && settings('urls_linked') && !$nolink)
        $txt = make_clickable($txt);

    $txt = str_replace("\\","\\\\",$txt);
    $txt = str_replace("\\n","<br />",$txt);
    $txt = BadwordFilter($txt);
    $txt = replace($txt,$tinymce,$no_vid);
    $txt = highlight_text($txt);

    if(!$tinymce) $txt = re_bbcode($txt);
    if(!$ts) $txt = strip_tags($txt,"<br><object><em><param><embed><strong><iframe><hr><table><tr><td><div><span><a><b><font><i><u><p><ul><ol><li><br /><img>");
    $txt = smileys($txt);
    if(!$no_vid) $txt = glossar($txt);
    return str_replace(array("&#34;","<p></p>"),array("\"","<p>&nbsp;</p>"),$txt);
}

function bbcode_nletter($txt) {
    $txt = stripslashes($txt);
    $txt = nl2br(trim($txt));
    return '<style type="text/css">p { margin: 0px; padding: 0px; }</style>'.$txt;
}

function bbcode_nletter_plain($txt) {
    $txt = preg_replace("#\<\/p\>#Uis","\r\n",$txt);
    $txt = preg_replace("#\<br(.*?)\>#Uis","\r\n",$txt);
    $txt = str_replace("p { margin: 0px; padding: 0px; }","",$txt);
    $txt = convert_feed($txt);
    $txt = str_replace("&amp;#91;","[",$txt);
    $txt = str_replace("&amp;#93;","]",$txt);
    return strip_tags($txt);
}

function convert_feed($txt) {
    global $charset;
    $txt = stripslashes($txt);
    $txt = str_replace("&Auml;","Ae",$txt);
    $txt = str_replace("&auml;","ae",$txt);
    $txt = str_replace("&Uuml;","Ue",$txt);
    $txt = str_replace("&uuml;","ue",$txt);
    $txt = str_replace("&Ouml;","Oe",$txt);
    $txt = str_replace("&ouml;","oe",$txt);
    $txt = htmlentities($txt, ENT_QUOTES, $charset);
    $txt = str_replace("&amp;","&",$txt);
    $txt = str_replace("&lt;","<",$txt);
    $txt = str_replace("&gt;",">",$txt);
    $txt = str_replace("&#60;","<",$txt);
    $txt = str_replace("&#62;",">",$txt);
    $txt = str_replace("&#34;","\"",$txt);
    $txt = str_replace("&nbsp;"," ",$txt);
    $txt = str_replace("&szlig;","ss",$txt);
    $txt = preg_replace("#&(.*?);#is","",$txt);
    $txt = str_replace("&","&amp;",$txt);
    $txt = str_replace("", "\"",$txt);
    $txt = str_replace("", "\"",$txt);
    return strip_tags($txt);
}

function bbcode_html($txt,$tinymce=0) {
    $txt = str_replace("&lt;","<",$txt);
    $txt = str_replace("&gt;",">",$txt);
    $txt = str_replace("&quot;","\"",$txt);
    $txt = BadwordFilter($txt);
    $txt = replace($txt,$tinymce);
    $txt = highlight_text($txt);
    $txt = re_bbcode($txt);
    $txt = smileys($txt);
    $txt = glossar($txt);
    return str_replace("&#34;","\"",$txt);
}

function bbcode_email($txt) {
    $txt = bbcode($txt);
    $txt = str_replace("&#91;","[",$txt);
    return str_replace("&#93;","]",$txt);
}

//-> Textteil in Zitat-Tags setzen
function zitat($nick,$zitat) {
    $zitat = str_replace(chr(145), chr(39), $zitat);
    $zitat = str_replace(chr(146), chr(39), $zitat);
    $zitat = str_replace("'", "&#39;", $zitat);
    $zitat = str_replace(chr(147), chr(34), $zitat);
    $zitat = str_replace(chr(148), chr(34), $zitat);
    $zitat = str_replace(chr(10), " ", $zitat);
    $zitat = str_replace(chr(13), " ", $zitat);
    $zitat = preg_replace("#[\n\r]+#", "<br />", $zitat);
    return '<div class="quote"><b>'.$nick.' '._wrote.':</b><br />'.re_bbcode($zitat).'</div><br /><br /><br />';
}

/**
 * DZCP V1.6.1
 * Decodiert Strings und Texte von UTF8.
 * Auslesen von Werten aus der Datenbank.
 *
 * @param string $txt
 * @return string
 */
function re($txt = '') {
    return string::decode($txt);
}

function re_entry($txt) {
    return stripslashes($txt);
}

/**
 * BBCODE in Smileys umwandeln
 * @param string $txt
 * @return string
 */
function smileys($txt) {
    if(!dbc_index::issetIndex('smileys')) {
        $smileys = get_files(basePath.'/inc/images/smileys',false,true);
        dbc_index::setIndex('smileys', $smileys);
    } else $smileys = dbc_index::getIndex('smileys');

    foreach($smileys as $smiley) {
        $bbc = preg_replace("=.gif=Uis","",$smiley);
        if(preg_match("=:".$bbc.":=Uis",$txt)!=FALSE)
            $txt = preg_replace("=:".$bbc.":=Uis","<img src=\"../inc/images/smileys/".$bbc.".gif\" alt=\"\" />", $txt);
    }

    $var = array("/\ :D/","/\ :P/","/\ ;\)/","/\ :\)/","/\ :-\)/","/\ :\(/","/\ :-\(/","/\ ;-\)/");
    $repl = array(" <img src=\"../inc/images/smileys/grin.gif\" alt=\"\" />",
                  " <img src=\"../inc/images/smileys/zunge.gif\" alt=\"\" />",
                  " <img src=\"../inc/images/smileys/zwinker.gif\" alt=\"\" />",
                  " <img src=\"../inc/images/smileys/smile.gif\" alt=\"\" />",
                  " <img src=\"../inc/images/smileys/smile.gif\" alt=\"\" />",
                  " <img src=\"../inc/images/smileys/traurig.gif\" alt=\"\" />",
                  " <img src=\"../inc/images/smileys/traurig.gif\" alt=\"\" />",
                  " <img src=\"../inc/images/smileys/zwinker.gif\" alt=\"\" />");

  $txt = preg_replace($var,$repl, $txt);
  return str_replace(" ^^"," <img src=\"../inc/images/smileys/^^.gif\" alt=\"\" />", $txt);
}

//-> Flaggen ausgeben
function flagge($txt) {
    $var = array("/\:de:/",
                 "/\:ch:/",
                 "/\:at:/",
                 "/\:au:/",
                 "/\:be:/",
                 "/\:br:/",
                 "/\:ca:/",
                 "/\:gb:/",
                 "/\:pl:/",
                 "/\:cz:/",
                 "/\:dk:/",
                 "/\:es:/",
                 "/\:en:/",
                 "/\:fi:/",
                 "/\:fr:/",
                 "/\:gr:/",
                 "/\:hr:/",
                 "/\:us:/",
                 "/\:it:/",
                 "/\:se:/",
                 "/\:eu:/",
                 "/\:nl:/",
                 "/\:na:/",
                 "/\:no:/",
                 "/\:ru:/");

    $repl = array("<img src=\"../inc/images/flaggen/de.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/ch.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/at.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/au.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/be.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/br.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/ca.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/uk.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/pl.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/cz.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/dk.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/es.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/fo.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/fi.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/fr.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/gr.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/hr.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/us.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/it.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/se.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/eu.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/nl.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/nocountry.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/no.gif\" alt=\"\" />",
                  "<img src=\"../inc/images/flaggen/ru.gif\" alt=\"\" />" );

    return preg_replace($var,$repl, $txt);
}

//-> Funktion um Ausgaben zu kuerzen
function cut($str, $length = null, $dots = true) {
    if($length === 0)
        return '';

    $start = 0;
    $dots = ($dots == true && strlen(html_entity_decode($str)) > $length) ? '...' : '';

    if(strpos($str, '&') === false)
        return (($length === null) ? substr($str, $start) : substr($str, $start, $length)).$dots;

    $chars = preg_split('/(&[^;\s]+;)|/', $str, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE);
    $html_length = count($chars);

    if(($html_length === 0) || ($start >= $html_length) || (isset($length) && ($length <= -$html_length)))
        return '';

    if($start >= 0)
        $real_start = $chars[$start][1];
    else {
        $start = max($start,-$html_length);
        $real_start = $chars[$html_length+$start][1];
    }

    if (!isset($length))
        return substr($str, $real_start).$dots;
    else if($length > 0)
        return (($start+$length >= $html_length) ? substr($str, $real_start) : substr($str, $real_start, $chars[max($start,0)+$length][1] - $real_start)).$dots;
    else
        return substr($str, $real_start, $chars[$html_length+$length][1] - $real_start).$dots;
}

function wrap($str, $width = 75, $break = "\n", $cut = true) {
    return strtr(str_replace(htmlentities($break), $break, htmlentities(wordwrap(html_entity_decode($str), $width, $break, $cut), ENT_QUOTES)), array_flip(get_html_translation_table(HTML_SPECIALCHARS, ENT_COMPAT)));
}

//-> Funktion um Dateien aus einem Verzeichnis auszulesen
function get_files($dir=null,$only_dir=false,$only_files=false,$file_ext=array(),$preg_match=false,$blacklist=array(),$blacklist_word=false) {
    $files = array();
    if(!file_exists($dir) && !is_dir($dir)) return $files;
    if($handle = @opendir($dir)) {
        if($only_dir) {
            while(false !== ($file = readdir($handle))) {
                if($file != '.' && $file != '..' && !is_file($dir.'/'.$file)) {
                    if(!count($blacklist) && (!$blacklist_word || strpos(strtolower($file), $blacklist_word) === false) && ($preg_match ? preg_match($preg_match,$file) : true))
                        $files[] = $file;
                    else {
                        if(!in_array($file, $blacklist) && (!$blacklist_word || strpos(strtolower($file), $blacklist_word) === false) && ($preg_match ? preg_match($preg_match,$file) : true))
                            $files[] = $file;
                    }
                }
            } //while end
        } else if($only_files) {
            while(false !== ($file = readdir($handle))) {
                if($file != '.' && $file != '..' && is_file($dir.'/'.$file)) {
                    if(!in_array($file, $blacklist) && (!$blacklist_word || strpos(strtolower($file), $blacklist_word) === false) && !count($file_ext) && ($preg_match ? preg_match($preg_match,$file) : true))
                        $files[] = $file;
                    else {
                        ## Extension Filter ##
                        $exp_string = array_reverse(explode(".", $file));
                        if(!in_array($file, $blacklist) && (!$blacklist_word || strpos(strtolower($file), $blacklist_word) === false) && in_array(strtolower($exp_string[0]), $file_ext) && ($preg_match ? preg_match($preg_match,$file) : true))
                            $files[] = $file;
                    }
                }
            } //while end
        } else {
            while(false !== ($file = readdir($handle))) {
                if($file != '.' && $file != '..' && is_file($dir.'/'.$file)) {
                    if(!in_array($file, $blacklist) && (!$blacklist_word || strpos(strtolower($file), $blacklist_word) === false) && !count($file_ext) && ($preg_match ? preg_match($preg_match,$file) : true))
                        $files[] = $file;
                    else {
                        ## Extension Filter ##
                        $exp_string = array_reverse(explode(".", $file));
                        if(!in_array($file, $blacklist) && (!$blacklist_word || strpos(strtolower($file), $blacklist_word) === false) && in_array(strtolower($exp_string[0]), $file_ext) && ($preg_match ? preg_match($preg_match,$file) : true))
                            $files[] = $file;
                    }
                } else {
                    if(!in_array($file, $blacklist) && (!$blacklist_word || strpos(strtolower($file), $blacklist_word) === false) && $file != '.' && $file != '..' && ($preg_match ? preg_match($preg_match,$file) : true))
                        $files[] = $file;
                }
            } //while end
        }

        if(is_resource($handle))
            closedir($handle);

        if(!count($files))
            return false;

        return $files;
    }
    else
        return false;
}

//-> Gibt einen Teil eines nummerischen Arrays wieder
function limited_array($array=array(),$begin,$max) {
    $array_exp = array();
    $range=range($begin=($begin-1), ($begin+$max-1));
    foreach($array as $key => $wert) {
        if(array_var_exists($key, $range))
            $array_exp[$key] = $wert;
    }

    return $array_exp;
}

function array_var_exists($var,$search)
{ foreach($search as $key => $var_) { if($var_==$var) return true; } return false; }

/**
 * Funktion um eine Datei im Web auf Existenz zu prufen und abzurufen
 * @return String
 **/
function fileExists($url,$timeout=1) {
    if((!allow_url_fopen_support() && !use_curl || (use_curl && !extension_loaded('curl'))))
        return false;
    
    $url_p = @parse_url($url);
    $host = $url_p['host'];
    $port = isset($url_p['port']) ? $url_p['port'] : 80;
    
    if(!ping_port($host,$port,$timeout)) return false;
    unset($host,$port);
   
    if(class_exists('Snoopy')) { //Use Snoopy HTTP Client
        $snoopy = new Snoopy;
        if(!$snoopy->fetch($url))
            return false;
        
        return ((string)(trim($snoopy->results)));
    }

    if(use_curl && extension_loaded('curl')) {
        if(!$curl = curl_init())
            return false;
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT , $timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout * 2); // x 2

        if($url_p['scheme'] == 'https') { //SSL
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        }
        
        if(!$content = curl_exec($curl))
            return false;

        @curl_close($curl);
        unset($curl);
    } else {
        if($url_p['scheme'] == 'https') //HTTPS not Supported!
            $url = str_replace('https', 'http', $url);
        
        $context = stream_context_create(array('http' => array('method'=>"GET", 
        'header'=>"Content-Type: text/html; charset=utf-8" , 'timeout' => $timeout * 2)));
        if(!$content = @file_get_contents($url, false, $context, -1, 40000))
            return false;
    }

    return ((string)(trim($content)));
}

/**
 * Funktion um Sonderzeichen in HTML Text zu konvertieren
 * @param string $txt
 * @return string
 */
function spChars($txt) {
    $search  = array("ƒ","‰","‹","¸","÷","ˆ","ﬂ");
    $replace = array("&Auml;","&auml;","&Uuml;","&uuml;","&Ouml;","&ouml;","&szlig;");
    return str_replace($search,$replace,$txt);
}

/**
 * DZCP V1.6.1
 * Codiert Strings und Texte in UTF8.
 * Schreiben von Werten in die Datenbank.
 *
 * @param string $txt
 * @return uft8 string
 */
function up($txt = '') {
    return string::encode($txt);
}

/**
 * DZCP V1.6.1
 * Gibt Informationen uber Server und Ausfuhrungsumgebung zuruck
 *
 * @param string $var
 * @return string
 */
function GetServerVars($var) {
    if(array_key_exists($var, $_SERVER) && !empty($_SERVER[$var]))
        return string::encode($_SERVER[$var]);
    else if(array_key_exists($var, $_ENV) && !empty($_ENV[$var]))
        return string::encode($_ENV[$var]);
    
    return false;
}

//-> Funktion um diverse Dinge aus Tabellen auszaehlen zu lassen
//-> Single & Multi Version
function cnt($db, $where = "", $what = "id") {
    $cnt_sql = db("SELECT COUNT(".$what.") AS `cnt` FROM `".$db."` ".$where.";");
    if(_rows($cnt_sql)) {
        $cnt = _fetch($cnt_sql);
        return $cnt['cnt'];
    }

    return 0;
}

function cnt_multi($db, $where = "", $whats = array('id')) {
    $cnt_sql = "";
    foreach ($whats as $what) {
        $cnt_sql .= "COUNT(".$what.") AS `cnt_".$what."`,";
    }
    $cnt_sql = substr($cnt_sql, 0, -1);
    $cnt_sql = db("SELECT ".$cnt_sql." FROM `".$db."` ".$where.";");
    if(_rows($cnt_sql))
        return _fetch($cnt_sql);

    return array();
}

//-> Funktion um diverse Dinge aus Tabellen zusammenzaehlen zu lassen
//-> Single & Multi Version
function sum($db, $where = "", $what = "id") {
    $sum_sql = db("SELECT SUM(".$what.") AS `sum` FROM `".$db."` ".$where.";");
    if(_rows($sum_sql)) {
        $sum = _fetch($sum_sql);
        return $sum['sum'];
    }

    return 0;
}

function sum_multi($db, $where = "", $whats = array('id')) {
    $sum_sql = "";
    foreach ($whats as $what) {
        $sum_sql .= "SUM(".$what.") AS `sum_".$what."`,";
    }
    $sum_sql = substr($sum_sql, 0, -1);
    $sum_sql = db("SELECT ".$sum_sql." FROM `".$db."` ".$where.";");
    if(_rows($sum_sql))
        return _fetch($sum_sql);

    return array();
}

function orderby($sort) {
    $split = explode("&",$_SERVER['QUERY_STRING']);
    $url = "?";

    foreach($split as $part) {
        if(strpos($part,"orderby") === false && strpos($part,"order") === false && !empty($part)) {
            $url .= $part;
            $url .= "&";
        }
    }

    if(isset($_GET['orderby']) && $_GET['order']) {
        if($_GET['orderby'] == $sort && $_GET['order'] == "ASC")
            return $url."orderby=".$sort."&order=DESC";
    }

    return $url."orderby=".$sort."&order=ASC";
}

function orderby_sql($sort_by=array(), $default_order='',$join='', $order_by = array('ASC','DESC')) {
    if(!isset($_GET['order']) || empty($_GET['order']) || !in_array($_GET['order'],$order_by)) return $default_order;
    if(!isset($_GET['orderby']) || empty($_GET['orderby']) || !in_array($_GET['orderby'],$sort_by)) return $default_order;
    $orderby_real = _real_escape_string($_GET['orderby']);
    $order_real = _real_escape_string($_GET['order']);
    if(empty($orderby_real) || empty($order_real)) return $default_order;
    $join = !empty($join) ? $join.'.' : '';
    return 'ORDER BY '.$join.$orderby_real." ".$order_real;
}

function orderby_nav() {
    $orderby = isset($_GET['orderby']) ? "&orderby".$_GET['orderby'] : "";
    $orderby .= isset($_GET['order']) ? "&order=".$_GET['order'] : "";
    return $orderby;
}

//-> Funktion um ein Datenbankinhalt zu highlighten
function highlight($word) {
    if(substr(phpversion(),0,1) == 5)
        return str_ireplace($word,'<span class="fontRed">'.$word.'</span>',$word);
    else
        return str_replace($word,'<span class="fontRed">'.$word.'</span>',$word);
}

//-> Counter updaten
function updateCounter() {
    global $db,$reload,$today,$datum,$userip;
    $ipcheck = db("SELECT `id`,`ip`,`datum` FROM `".$db['c_ips']."` WHERE `ip` = '".$userip."' AND FROM_UNIXTIME(datum,'%d.%m.%Y') = '".date("d.m.Y")."';");
    db("DELETE FROM `".$db['c_ips']."` WHERE datum+".$reload." <= ".time()." OR FROM_UNIXTIME(datum,'%d.%m.%Y') != '".date("d.m.Y")."';");
    $count = db("SELECT `id`,`visitors`,`today` FROM `".$db['counter']."` WHERE `today` = '".$today."';");
    if(_rows($ipcheck)>=1) {
        $get = _fetch($ipcheck);
        $sperrzeit = $get['datum']+$reload;
        if($sperrzeit <= time()) {
            db("DELETE FROM `".$db['c_ips']."` WHERE `ip` = '".$userip."';");

            if(_rows($count))
                db("UPDATE `".$db['counter']."` SET `visitors` = visitors+1 WHERE `today` = '".$today."';");
            else
                db("INSERT INTO `".$db['counter']."` SET `visitors` = '1', `today` = '".$today."';");

            db("INSERT INTO `".$db['c_ips']."` SET `ip` = '".$userip."', `datum` = '".intval($datum)."';");
        }
    } else {
        if(_rows($count))
            db("UPDATE `".$db['counter']."` SET `visitors` = visitors+1 WHERE `today` = '".$today."';");
       else
            db("INSERT INTO `".$db['counter']."` SET `visitors` = '1', `today` = '".$today."';");
        db("INSERT INTO `".$db['c_ips']."` SET `ip` = '".$userip."', `datum` = '".intval($datum)."';");
    }
}

//-> Updatet die Maximalen User die gleichzeitig online sind
function update_maxonline() {
    global $db,$today;
    $count = cnt($db['c_who']);
    $get = db("SELECT `maxonline` FROM `".$db['counter']."` WHERE `today` = '".$today."';",false,true);
    if($get['maxonline'] < $count)
        db("UPDATE `".$db['counter']."` SET `maxonline` = ".$count." WHERE `today` = '".$today."';");
}

//-> Aktualisiert die Position der Gaste & User
function update_online($where='') {
    global $db,$useronline,$userip,$chkMe,$isSpider,$userid;
    if(!$isSpider && !empty($where)) {
        db("DELETE FROM `".$db['c_who']."` WHERE `online` < ".time().";");
        $sql = db("SELECT `id` FROM `".$db['c_who']."` WHERE `ip` = '".$userip."';");
        if(_rows($sql)) {
            $get = _fetch($sql);
            db("UPDATE `".$db['c_who']."` SET `whereami` = '".up($where)."', 
                                              `online` = ".(time()+$useronline).", 
                                              `login`    = ".(!$chkMe ? 0 : 1)." 
                                          WHERE `id` = '".$get['id']."';");
        } else {
            db("INSERT INTO `".$db['c_who']."` SET `ip`  = '".$userip."',
                                                   `online`   = ".(time()+$useronline).",
                                                   `whereami` = '".up($where)."',
                                                   `login`    = ".(!$chkMe ? 0 : 1).";");
        }
        
        if($chkMe)
            db("UPDATE `".$db['users']."` SET `time` = ".time().", `whereami` = '".up($where)."' WHERE `id` = ".intval($userid).";");
    }
}

//-> Prueft, wieviele Besucher gerade online sind
function online_guests($where='') {
    global $db,$useronline,$isSpider;
    if(!$isSpider) {
        $whereami = (empty($where) ? '' : " AND `whereami` = '".$where."'");
        return cnt($db['c_who']," WHERE online+".$useronline.">".time()."".$whereami." AND `login` = 0");
    }
    
    return 0;
}

//-> Prueft, wieviele registrierte User gerade online sind
function online_reg($where='') {
    global $db,$useronline,$isSpider;
    if(!$isSpider) {
        $whereami = (empty($where) ? '' : " AND `whereami` = '".$where."'");
        return cnt($db['users'], " WHERE time+".$useronline.">".time()."".$whereami." AND `online` = 1");
    }
    
    return 0;
}

//-> Prueft, ob der User eingeloggt ist und wenn ja welches Level besitzt er
function checkme($userid_set=0) {
    global $db;
    if(!$userid = ($userid_set != 0 ? intval($userid_set) : userid())) return 0;
    if(empty($_SESSION['id']) || empty($_SESSION['pwd'])) return 0;
    if(!dbc_index::issetIndex('user_'.$userid)) {
        $qry = db("SELECT * FROM `".$db['users']."` WHERE `id` = ".$userid." AND `pwd` = '".$_SESSION['pwd']."' AND `ip` = '".$_SESSION['ip']."';");
        if(!_rows($qry)) return 0;
        $get = _fetch($qry);
        dbc_index::setIndex('user_'.$get['id'], $get);
        return $get['level'];
    }

    return dbc_index::getIndexKey('user_'.$userid, 'level');
}

//-> Prueft, ob der User gesperrt ist und meldet ihn ab
function isBanned($userid_set=0,$logout=true) {
    global $db,$userid;
    $userid_set = $userid_set ? $userid_set : $userid;
    if(checkme($userid_set) >= 1 || $userid_set) {
        $get = db("SELECT `banned` FROM `".$db['users']."` WHERE `id` = ".intval($userid_set)." LIMIT 1;",false,true);
        if($get['banned']) {
            if($logout) {
                dzcp_session_destroy();
                $userid = 0; $chkMe = 0;
            }

            return true;
        }
    }

    return false;
}

//-> Prueft, ob ein User diverse Rechte besitzt
function permission($check,$uid=0) {
    global $db,$userid,$chkMe;
    if(!$uid) $uid = $userid;

    if($chkMe == 4)
        return true;
    else {
        if($uid) {
            // check rank permission
            if(db("SELECT s1.`".$check."` FROM `".$db['permissions']."` AS `s1`
                   LEFT JOIN `".$db['userpos']."` AS `s2` ON s1.`pos` = s2.`posi`
                   WHERE s2.`user` = ".intval($uid)." AND s1.`".$check."` = 1 AND s2.`posi` != 0;",true))
                return true;

            // check user permission
            if(!dbc_index::issetIndex('user_permission_'.$uid)) {
                $permissions = db("SELECT * FROM `".$db['permissions']."` WHERE `user` = ".intval($uid).";",false,true);
                dbc_index::setIndex('user_permission_'.$uid, $permissions);
            }

            return dbc_index::getIndexKey('user_permission_'.$uid, $check) ? true : false;
        }
        else
            return false;
    }
}

//-> Checkt, ob neue Nachrichten vorhanden sind
function check_msg() {
    global $db;
    if(db("SELECT `page` FROM `".$db['msg']."` WHERE `an` = ".intval($_SESSION['id'])." AND `page` = 0;",true)) {
        db("UPDATE `".$db['msg']."` SET `page` = 1 WHERE `an` = ".intval($_SESSION['id']).";");
        return show("user/new_msg", array("new" => _site_msg_new));
    }

    return false;
}

//-> Prueft sicherheitsrelevante Gegebenheiten im Forum
function forumcheck($tid, $what) {
    global $db;
    return db("SELECT `".$what."` FROM `".$db['f_threads']."` WHERE `id` = ".intval($tid)." AND ".$what." = 1;",true) ? true : false;
}

//-> Prueft ob ein User schon in der Buddyliste vorhanden ist
function check_buddy($buddy) {
    global $db,$userid;
    return !db("SELECT `buddy` FROM `".$db['buddys']."` WHERE `user` = ".intval($userid)." AND `buddy` = ".intval($buddy).";",true) ? true : false;
}

//-> Funktion um bei Clanwars Endergebnisse auszuwerten
function cw_result($punkte, $gpunkte) {
    if($punkte > $gpunkte)
        return '<span class="CwWon">'.$punkte.':'.$gpunkte.'</span> <img src="../inc/images/won.gif" alt="" class="icon" />';
    else if($punkte < $gpunkte)
        return '<span class="CwLost">'.$punkte.':'.$gpunkte.'</span> <img src="../inc/images/lost.gif" alt="" class="icon" />';
    else
        return '<span class="CwDraw">'.$punkte.':'.$gpunkte.'</span> <img src="../inc/images/draw.gif" alt="" class="icon" />';
}

function cw_result_pic($punkte, $gpunkte) {
    if($punkte > $gpunkte)
        return '<img src="../inc/images/won.gif" alt="" class="icon" />';
    else if($punkte < $gpunkte)
        return '<img src="../inc/images/lost.gif" alt="" class="icon" />';
    else
        return '<img src="../inc/images/draw.gif" alt="" class="icon" />';
}

//-> Funktion um bei Clanwars Endergebnisse auszuwerten ohne bild
function cw_result_nopic($punkte, $gpunkte) {
    if($punkte > $gpunkte)
        return '<span class="CwWon">'.$punkte.':'.$gpunkte.'</span>';
    else if($punkte < $gpunkte)
        return '<span class="CwLost">'.$punkte.':'.$gpunkte.'</span>';
    else
        return '<span class="CwDraw">'.$punkte.':'.$gpunkte.'</span>';
}

//-> Funktion um bei Clanwars Endergebnisse auszuwerten ohne bild und ohne farbe
function cw_result_nopic_nocolor($punkte, $gpunkte) {
    if($punkte > $gpunkte)
        return $punkte.':'.$gpunkte;
    else if($punkte < $gpunkte)
        return $punkte.':'.$gpunkte;
    else
        return $punkte.':'.$gpunkte;
}

//-> Funktion um bei Clanwars Details Endergebnisse auszuwerten ohne bild
function cw_result_details($punkte, $gpunkte) {
    if($punkte > $gpunkte)
        return '<td class="contentMainFirst" align="center"><span class="CwWon">'.$punkte.'</span></td><td class="contentMainFirst" align="center"><span class="CwLost">'.$gpunkte.'</span></td>';
    else if($punkte < $gpunkte)
        return '<td class="contentMainFirst" align="center"><span class="CwLost">'.$punkte.'</span></td><td class="contentMainFirst" align="center"><span class="CwWon">'.$gpunkte.'</span></td>';
    else
        return '<td class="contentMainFirst" align="center"><span class="CwDraw">'.$punkte.'</span></td><td class="contentMainFirst" align="center"><span class="CwDraw">'.$gpunkte.'</span></td>';
}

//-> Flaggen ausgeben
function flag($code) {
    global $picformat;
    if(empty($code))
        return '<img src="../inc/images/flaggen/nocountry.gif" alt="" class="icon" />';

    foreach($picformat as $end) {
        if(file_exists(basePath."/inc/images/flaggen/".$code.".".$end)) break;
    }

    if(file_exists(basePath."/inc/images/flaggen/".$code.".".$end))
        return'<img src="../inc/images/flaggen/'.$code.'.'.$end.'" alt="" class="icon" />';

    return '<img src="../inc/images/flaggen/nocountry.gif" alt="" class="icon" />';
}

function rawflag($code) {
    global $picformat;
    if(empty($code))
        return '<img src=../inc/images/flaggen/nocountry.gif alt= class=icon />';

    foreach($picformat as $end) {
        if(file_exists(basePath."/inc/images/flaggen/".$code.".".$end)) break;
    }

    if(file_exists(basePath."/inc/images/flaggen/".$code.".".$end))
        return '<img src=../inc/images/flaggen/'.$code.'.'.$end.' alt= class=icon />';

    return '<img src=../inc/images/flaggen/nocountry.gif alt= class=icon />';
}

//-> Liste der Laender ausgeben
function show_countrys($i="") {
    if($i != "")
        $options = preg_replace('#<option value="'.$i.'">(.*?)</option>#', '<option value="'.$i.'" selected="selected"> \\1</option>', _country_list);
    else
        $options = preg_replace('#<option value="de"> Deutschland</option>#', '<option value="de" selected="selected"> Deutschland</option>', _country_list);

    return '<select id="land" name="land" class="dropdown">'.$options.'</select>';
}

//-> Gameicon ausgeben
function squad($code) {
    global $picformat;
    if(empty($code))
        return '<img src="../inc/images/gameicons/nogame.gif" alt="" class="icon" />';

    $code = str_replace(array('.png','.gif','.jpg'),'',$code);
    foreach($picformat as $end) {
        if(file_exists(basePath."/inc/images/gameicons/".$code.".".$end)) break;
    }

    if(file_exists(basePath."/inc/images/gameicons/".$code.".".$end))
        return'<img src="../inc/images/gameicons/'.$code.'.'.$end.'" alt="" class="icon" />';

    return '<img src="../inc/images/gameicons/nogame.gif" alt="" class="icon" />';
}

//-> Funktion um bei DB-Eintraegen URLs einem http:// zuzuweisen
function links($hp) {
    return !empty($hp) ? 'http://'.str_replace("http://","",$hp) : $hp;
}

//-> Funktion um Passwoerter generieren zu lassen
function mkpwd($passwordLength=8,$specialcars=true) {
    global $passwordComponents;
    $componentsCount = count($passwordComponents);

    if(!$specialcars && $componentsCount == 4) {
        unset($passwordComponents[3]);
        $componentsCount = count($passwordComponents);
    }

    shuffle($passwordComponents); $password = '';
    for ($pos = 0; $pos < $passwordLength; $pos++) {
        $componentIndex = ($pos % $componentsCount);
        $componentLength = strlen($passwordComponents[$componentIndex]);
        $random = rand(0, $componentLength-1);
        $password .= $passwordComponents[$componentIndex]{ $random };
    }

    unset($random,$componentLength,$componentIndex);
    return $password;
}

//-> Passwortabfrage
function checkpwd($user, $pwd) {
    global $db;
    return db("SELECT `id`,`user`,`nick`,`pwd`
               FROM `".$db['users']."`
               WHERE `user` = '".up($user)."'
               AND `pwd` = '".up($pwd)."'
               AND `level` != 0;",true) ? true : false;
}

//-> Infomeldung ausgeben
function info($msg, $url, $timeout = 5) {
    if(config('direct_refresh'))
        return header('Location: '.str_replace('&amp;', '&', $url));

    $u = parse_url($url); $parts = '';
    $u['query'] = array_key_exists('query', $u) ? $u['query'] : '';
    $u['query'] = str_replace('&amp;', '&', $u['query']);
    foreach(explode('&', $u['query']) as $p) {
        $p = explode('=', $p);
        if(count($p) == 2)
            $parts .= '<input type="hidden" name="'.$p[0].'" value="'.$p[1].'" />'."\r\n";
    }

    if(!array_key_exists('path',$u)) $u['path'] = '';
    return show("errors/info", array("msg" => $msg,
                                     "url" => $u['path'],
                                     "rawurl" => html_entity_decode($url),
                                     "parts" => $parts,
                                     "timeout" => $timeout,
                                     "info" => _info,
                                     "weiter" => _weiter,
                                     "backtopage" => _error_fwd));
}

//-> Errormmeldung ausgeben
function error($error, $back=1) {
    return show("errors/error", array("error" => $error, "back" => $back, "fehler" => _error, "backtopage" => _error_back));
}

//-> Errormmeldung ohne "zurueck" ausgeben
function error2($error) {
    return show("errors/error2", array("error" => $error, "fehler" => _error));
}

//-> Email wird auf korrekten Syntax & Erreichbarkeit ueberprueft
function check_email($email) {
    return (!preg_match("#^([a-zA-Z0-9\.\_\-]+)@([a-zA-Z0-9\.\-]+\.[A-Za-z][A-Za-z]+)$#", $email) ? false : true);
}

//-> Bilder verkleinern
function img_size($img) {
    return "<a href=\"../".$img."\" rel=\"lightbox[l_".intval($img)."]\"><img src=\"../thumbgen.php?img=".$img."\" alt=\"\" /></a>";
}

function img_cw($folder="", $img="") {
    return "<a href=\"../".$folder.$img."\" rel=\"lightbox[cw_".intval($folder)."]\"><img src=\"../thumbgen.php?img=".$folder.$img."\" alt=\"\" /></a>";
}

function gallery_size($img="") {
    return "<a href=\"../gallery/images/".$img."\" rel=\"lightbox[gallery_".intval($img)."]\"><img src=\"../thumbgen.php?img=gallery/images/".$img."\" alt=\"\" /></a>";
}

/**
* DZCP V1.6.1
* CSS Basierend - Blaetterfunktion
* [Previous][1][Next]
* [Previous][1][2][3][4][Next]
* [Previous][1][2][3][4][...][20][Next]
* [Previous][1][...][16][17][18][19][20][Next]
* [Previous][1][...][13][14][15][16][...][20][Next]
*/
function nav($entrys, $perpage, $urlpart='', $recall = 0) {
    global $page;
    if(!$entrys || !$perpage) { 
        $entrys = 1; 
        $perpage = 10; 
    }
    
    $total_pages  = ceil($entrys / $perpage);
    $maximum_links = ((9 - $recall) / 2);
    $no_recall = !$recall ? false : true;
    $offset_izq = ($page - $maximum_links) < 0 ? $page - $maximum_links : 0;
    $offset_der = ($total_pages - $page) < $maximum_links ? $maximum_links - ($total_pages - $page) : 0;
    $pagination =""; $urlpart_extended = empty($urlpart) ? '?' : '&amp;'; $recall = 0;

    if ($page == 1) {
        $pagination.= "<div class='pagination active'>"._paginator_previous."</div>";
    } else {
        $pagina_anterior = $page - 1;
        $pagination .= "<a href='".$urlpart.$urlpart_extended."page=".$pagina_anterior."' class='pagination'>"._paginator_previous."</a>";
    }
    
    $pager = array(); $pagination_f = '';
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i <= ($page - $maximum_links) - $offset_der || $i > ($page + $maximum_links) - $offset_izq) { $pager[$i] = false; continue; }
        $pagination_f .= ($i == $page ? "<div class='pagination active'>" .$i. "</div>" : "<a href='".$urlpart.$urlpart_extended."page=".$i."' class='pagination'>".$i."</a>");
        $pager[$i] = true;
    }
    
    if(!$pager[1]) {
        $pagination.= "<a href='".$urlpart.$urlpart_extended."page=1' class='pagination'>1</a>";
        $pagination.= "<div class='pagination active'>...</div>";
        $recall = ($recall+1);
    }
    
    $pagination.= $pagination_f;
    if(!$pager[$total_pages]) {
        $pagination.= "<div class='pagination active'>...</div>";
        $pagination.= "<a href='".$urlpart.$urlpart_extended."page=".$total_pages."' class='pagination'>".$total_pages."</a>";
        $recall = ($recall+1);
    }
    
    if($recall && !$no_recall) {
        return nav($entrys, $perpage, $urlpart, $recall);
    }
            
    if ($page == $total_pages) {
        $pagination.= "<div class='pagination active'>"._paginator_next."</div>";
    } else {
        $pagina_posterior = $page + 1;
        $pagination.= "<a href='".$urlpart.$urlpart_extended."page=".$pagina_posterior."' class='pagination'>"._paginator_next."</a>";
    }

    return $pagination."</div>";
}

//-> Nickausgabe mit Profillink oder Emaillink (reg/nicht reg)
function autor($uid, $class="", $nick="", $email="", $cut="",$add="") {
    global $db;
    if(!dbc_index::issetIndex('user_'.intval($uid))) {
        $qry = db("SELECT * FROM `".$db['users']."` WHERE `id` = ".intval($uid).";");
        if(_rows($qry)) {
            $get = _fetch($qry);
            dbc_index::setIndex('user_'.$get['id'], $get);
        } else {
            $nickname = (!empty($cut)) ? cut(re($nick), $cut) : re($nick);
            return CryptMailto($email,_user_link_noreg,array("nick" => $nickname, "class" => $class));
        }
    }

    $nickname = (!empty($cut)) ? cut(re(dbc_index::getIndexKey('user_'.intval($uid), 'nick')), $cut) : re(dbc_index::getIndexKey('user_'.intval($uid), 'nick'));
    return show(_user_link, array("id" => $uid,
                                  "country" => flag(dbc_index::getIndexKey('user_'.intval($uid), 'country')),
                                  "class" => $class,
                                  "get" => $add,
                                  "nick" => $nickname));
}

function cleanautor($uid, $class="", $nick="", $email="", $cut="") {
    global $db;
    if(!dbc_index::issetIndex('user_'.intval($uid))) {
        $qry = db("SELECT * FROM `".$db['users']."` WHERE `id` = ".intval($uid).";");
        if(_rows($qry)) {
            $get = _fetch($qry);
            dbc_index::setIndex('user_'.$get['id'], $get);
        }
        else
            return CryptMailto($email,_user_link_noreg,array("nick" => re($nick), "class" => $class));
    }

    return show(_user_link_preview, array("id" => $uid, "country" => flag(dbc_index::getIndexKey('user_'.intval($uid), 'country')),
                                          "class" => $class, "nick" => re(dbc_index::getIndexKey('user_'.intval($uid), 'nick'))));
}

function rawautor($uid) {
    global $db;
    if(!dbc_index::issetIndex('user_'.intval($uid))) {
        $qry = db("SELECT * FROM `".$db['users']."` WHERE `id` = ".intval($uid).";");
        if(_rows($qry)) {
            $get = _fetch($qry);
            dbc_index::setIndex('user_'.$get['id'], $get);
        }
        else
            return rawflag('')." ".jsconvert(re($uid));
    }

    return rawflag(dbc_index::getIndexKey('user_'.intval($uid), 'country'))." ".
    jsconvert(re(dbc_index::getIndexKey('user_'.intval($uid), 'nick')));
}

//-> Nickausgabe ohne Profillink oder Emaillink fr das ForenAbo
function fabo_autor($uid) {
    global $db;
    $qry = db("SELECT `nick` FROM `".$db['users']."` WHERE `id` = ".intval($uid).";");
    if(_rows($qry)) {
        $get = _fetch($qry);
        return show(_user_link_fabo, array("id" => $uid, "nick" => re($get['nick'])));
    }

    return '';
}

function blank_autor($uid) {
    global $db;
    $qry = db("SELECT `nick` FROM `".$db['users']."` WHERE `id` = ".intval($uid).";");
    if(_rows($qry)) {
        $get = _fetch($qry);
        return show(_user_link_blank, array("id" => $uid, "nick" => re($get['nick'])));
    }

    return '';
}

//-> Rechte abfragen
function jsconvert($txt)
{ return str_replace(array("'","&#039;","\"","\r","\n"),array("\'","\'","&quot;","",""),$txt); }

//-> interner Forencheck
function fintern($id) {
    global $db,$userid,$chkMe;
    if(!$chkMe) {
        $fget = db("SELECT s1.`intern`,s2.`id` FROM `".$db['f_kats']."` AS `s1` LEFT JOIN `".$db['f_skats']."` AS `s2` ON s2.`sid` = s1.`id` WHERE s2.`id` = ".intval($id).";",false,true);
        return empty($fget['intern']) ? true : false;
    }
    
    $team = db("SELECT s1.`id` FROM `".$db['f_access']."` AS `s1` LEFT JOIN `".$db['userpos']."` AS `s2` ON s1.`pos` = s2.`posi` WHERE s2.`user` = ".intval($userid)." AND s2.`posi` != 0 AND s1.`forum` = ".intval($id).";",true);
    $user = db("SELECT `id` FROM `".$db['f_access']."` WHERE `user` = ".intval($userid)." AND `forum` = ".intval($id).";",true);
    if($user || $team || $chkMe == 4 || !$fget['intern']) return true;
    else if(!$chkMe) return false;
    else return false;
}

//-> Einzelne Userdaten ermitteln
function data($what='id',$tid=0) {
    global $db,$userid;
    if(!$tid) $tid = $userid;
    if(!dbc_index::issetIndex('user_'.$tid)) {
        $get = db("SELECT * FROM `".$db['users']."` WHERE `id` = ".intval($tid).";",false,true);
        dbc_index::setIndex('user_'.$tid, $get);
    }

    return re_entry(dbc_index::getIndexKey('user_'.$tid, $what));
}

//-> Einzelne Userstatistiken ermitteln
function userstats($what='id',$tid=0) {
    global $db,$userid;
    if(!$tid) $tid = $userid;
    if(!dbc_index::issetIndex('userstats_'.$tid)) {
        $get = db("SELECT * FROM `".$db['userstats']."` WHERE `user` = ".intval($tid).";",false,true);
        dbc_index::setIndex('userstats_'.$tid, $get);
    }

    return re_entry(dbc_index::getIndexKey('userstats_'.$tid, $what));
}

//- Funktion zum versenden von Emails
function sendMail($mailto,$subject,$content) {
    global $language;
    $mail = new PHPMailer;
    if(phpmailer_use_smtp) {
        $mail->isSMTP();
        $mail->Host = phpmailer_smtp_host;
        $mail->Port = phpmailer_smtp_port;
        $mail->SMTPAuth = phpmailer_use_auth;
        $mail->Username = phpmailer_smtp_user;
        $mail->Password = phpmailer_smtp_password;
    }
    
    $mail->From = ($mailfrom =settings('mailfrom'));
    $mail->FromName = $mailfrom;
    $mail->AddAddress(preg_replace('/(\\n+|\\r+|%0A|%0D)/i', '',$mailto));
    $mail->Subject = $subject;
    $mail->msgHTML($content);
    $mail->AltBody = bbcode_nletter_plain($content);
    $mail->setLanguage(($language=='deutsch')?'de':'en', basePath.'/inc/lang/sendmail/');
    return $mail->Send();
}

function check_msg_emal() {
    global $db,$httphost,$ajaxJob;
    if(!$ajaxJob) {
        $qry = db("SELECT s1.`an`,s1.`page`,s1.`titel`,s1.`sendmail`,s1.`id` AS `mid`, "
                . "s2.`id`,s2.`nick`,s2.`email`,s2.`pnmail` FROM `".$db['msg']."` AS `s1` "
                . "LEFT JOIN `".$db['users']."` AS `s2` ON s2.`id` = s1.`an` WHERE `page` = 0 AND `sendmail` = 0;");
        if(_rows($qry)) {
            while($get = _fetch($qry)) {
                if($get['pnmail']) {
                    db("UPDATE `".$db['msg']."` SET `sendmail` = 1 WHERE `id` = ".$get['mid'].";");
                    $subj = show(settings('eml_pn_subj'), array("domain" => $httphost));
                    $message = show(bbcode_email(settings('eml_pn')), array("nick" => re($get['nick']), "domain" => $httphost, "titel" => $get['titel'], "clan" => settings('clanname')));
                    sendMail(re($get['email']), $subj, $message);
                }
            }
        }
    }
}

check_msg_emal(); //CALL

//-> Checkt ob ein Ereignis neu ist
function check_new($datum = 0, $output=false, $datum2 = 0) {
    global $db,$userid;
    if($userid) {
        $lastvisit = userstats('lastvisit', $userid);
        if($datum >= $lastvisit || $datum2 >= $lastvisit)
            return (!$output ? true : $output);
    }
    
    return (!$output ? false : '');
}

//-> DropDown Mens Date/Time
function dropdown($what, $wert, $age = 0) {
    if($what == "day") {
        $return = ($age == 1 ? '<option value="" class="dropdownKat">'._day.'</option>'."\n" : '');
        for($i=1; $i<32; $i++) {
            if($i==$wert)
                $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
            else
                $return .= "<option value=\"".$i."\">".$i."</option>\n";
        }
    } else if($what == "month") {
        $return = ($age == 1 ? '<option value="" class="dropdownKat">'._month.'</option>'."\n" : '');
        for($i=1; $i<13; $i++) {
            if($i==$wert)
                $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
            else
                $return .= "<option value=\"".$i."\">".$i."</option>\n";
        }
    } else if($what == "year") {
        if($age == 1) {
            $return ='<option value="" class="dropdownKat">'._year.'</option>'."\n";
            for($i=date("Y",time())-80; $i<date("Y",time())-10; $i++)
            {
                if($i==$wert)
                    $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
                else
                    $return .= "<option value=\"".$i."\">".$i."</option>\n";
            }
        } else {
            $return = '';
            for($i=date("Y",time())-3; $i<date("Y",time())+3; $i++) {
                if($i==$wert)
                    $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
                else
                    $return .= "<option value=\"".$i."\">".$i."</option>\n";
            }
        }
    } else if($what == "hour") {
        $return = '';
        for($i=0; $i<24; $i++) {
            if($i==$wert)
                $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
            else
                $return .= "<option value=\"".$i."\">".$i."</option>\n";
        }
    } else if($what == "minute") {
        $return = '';
        for($i="00"; $i<60; $i++) {
            if($i == 0 || $i == 15 || $i == 30 || $i == 45) {
                if($i==$wert)
                    $return .= "<option value=\"".$i."\" selected=\"selected\">".$i."</option>\n";
                else
                    $return .= "<option value=\"".$i."\">".$i."</option>\n";
            }
        }
    }

    return $return;
}

//Games fuer den Livestatus
function sgames($game = '') {
    $protocols = get_files(basePath.'/inc/server_query/',false,true,array('php')); $games = '';
    foreach($protocols AS $protocol) {
        unset($gamemods, $server_name_config);
        $protocol = str_replace('.php', '', $protocol);
        if(substr($protocol, 0, 1) != '_') {
            $explode = '##############################################################################################################################';
            $protocol_config = explode($explode, file_get_contents(basePath.'/inc/server_query/'.$protocol.'.php'));
            eval(str_replace('<?php', '', $protocol_config[0]));

            if(!empty($server_name_config) && count($server_name_config) > 2) {
                $gamemods = '';
                foreach($server_name_config AS $slabel => $sconfig)
                    $gamemods .= $sconfig[1].', ';
            }

            $gamemods = empty($gamemods) ? '' : ' ('.substr($gamemods, 0, strlen($gamemods) - 2).')';
            $games .= '<option value="'.$protocol.'">';

            switch($protocol):
                case 'bf1942'; case 'bf2142'; case 'bf2'; case 'bfvietnam'; case 'bfbc2';
                    $protocol = strtr($protocol, array('bfbc2' => 'Battlefield Bad Company 2', 'bfv' => 'Battlefield V', 'bf' => 'Battlefield '));
                break;
                case 'bf3'; $protocol = 'Battlefield 3'; break;
                case 'bf4'; $protocol = 'Battlefield 4'; break;
                case 'swat4'; $protocol = strtoupper($protocol); break;
                case 'aarmy'; $protocol = 'Americas Army'; break;
                case 'arma'; $protocol = 'Armed Assault'; break;
                case 'wet'; $protocol = 'Wolfenstein: Enemy Territory'; break;
                case 'mta'; $protocol = 'Multi-Theft-Auto'; break;
                case 'cnc'; $protocol = 'Command &amp; Conquer'; break;
                case 'sof2'; $protocol = 'Soldiers of Fortune 2'; break;
                case 'ut'; $protocol = 'Unreal Tournament'; break;
                default;
                    $protocol = ucfirst(str_replace('_', ' ', $protocol));
                    $protocol = (strlen($protocol) < 4) ? strtoupper($protocol) : $protocol;
                break;
            endswitch;

            $games .= $protocol.$gamemods;
            $games .= '</option>';
        }
    }

    return str_replace("value=\"".$game."\"","value=\"".$game."\" selected=\"selected\"",$games);
}

//Umfrageantworten selektieren
function voteanswer($what, $vid) {
    global $db;
    $get = db("SELECT `sel` FROM `".$db['vote_results']."` WHERE `what` = '".up($what)."' AND `vid` = ".intval($vid).";",false,true);
    return $get['sel'];
}

//Profilfelder konvertieren
function conv($txt) {
    return str_replace(array("‰","¸","ˆ","ƒ","‹","÷","ﬂ"), array("ae","ue","oe","Ae","Ue","Oe","ss"), $txt);
}

//-> Geburtstag errechnen
function getAge($bday) {
    if(!empty($bday) && $bday) {
        $bday = date('d.m.Y',$bday);
        list($tiday,$iMonth,$iYear) = explode(".",$bday);
        $iCurrentDay = date('j');
        $iCurrentMonth = date('n');
        $iCurrentYear = date('Y');

        if(($iCurrentMonth>$iMonth) || (($iCurrentMonth==$iMonth) && ($iCurrentDay>=$tiday)))
            return $iCurrentYear - $iYear;
        else
            return $iCurrentYear - ($iYear + 1);
    }
    else
        return '-';
}

//-> Ausgabe der Position des einzelnen Members
function getrank($tid, $squad="", $profil=false) {
    global $db;
    if($squad) {
        if($profil)
            $qry = db("SELECT s1.`posi`,s2.`name` FROM `".$db['userpos']."` AS `s1` LEFT JOIN `".$db['squads']."` AS `s2` ON s1.`squad` = s2.`id` WHERE s1.`user` = ".intval($tid)." AND s1.`squad` = ".intval($squad)." AND s1.`posi` != 0;");
        else
            $qry = db("SELECT `posi` FROM `".$db['userpos']."` WHERE `user` = ".intval($tid)." AND `squad` = ".intval($squad)." AND `posi` != 0;");

        if(_rows($qry)) {
            while($get = _fetch($qry)) {
                $getp = db("SELECT `position` FROM `".$db['pos']."` WHERE `id` = ".intval($get['posi']).";",false,true);
                if(!empty($get['name'])) $squadname = '<b>'.$get['name'].':</b> ';
                else $squadname = '';
                return $squadname.$getp['position'];
            }
        } else {
            $get = _fetch(db("SELECT `level`,`banned` FROM `".$db['users']."` WHERE `id` = ".intval($tid).";"));
            if(!$get['level'] && !$get['banned'])     return _status_unregged;
            else if($get['level'] == 1)               return _status_user;
            else if($get['level'] == 2)               return _status_trial;
            else if($get['level'] == 3)               return _status_member;
            else if($get['level'] == 4)               return _status_admin;
            else if(!$get['level'] && $get['banned']) return _status_banned;
            else return _gast;
        }
    } else {
        $qry = db("SELECT s1.*,s2.`position` FROM `".$db['userpos']."` AS `s1` LEFT JOIN `".$db['pos']."` AS `s2` ON s1.`posi` = s2.`id` WHERE s1.`user` = ".intval($tid)." AND s1.`posi` != 0 ORDER BY s2.pid ASC");
        if(_rows($qry)) {
            $get = _fetch($qry);
            return $get['position'];
        } else {
            $get = db("SELECT `level`,`banned` FROM `".$db['users']."` WHERE `id` = ".intval($tid).";",false,true);
            if(!$get['level'] && !$get['banned'])    return _status_unregged;
            elseif($get['level'] == 1)               return _status_user;
            elseif($get['level'] == 2)               return _status_trial;
            elseif($get['level'] == 3)               return _status_member;
            elseif($get['level'] == 4)               return _status_admin;
            elseif(!$get['level'] && $get['banned']) return _status_banned;
            else return _gast;
        }
    }
}

//-> Session fuer den letzten Besuch setzen
function set_lastvisit() {
    global $db,$useronline,$userid;
    if($userid) {
        if(!db("SELECT `id` FROM `".$db['users']."` WHERE `id` = ".intval($userid)." AND time+".$useronline.">".time().";",true)) {
            $_SESSION['lastvisit'] = data("time");
        }
    }
}

//-> Checkt welcher User gerade noch online ist
function onlinecheck($tid) {
    global $db,$useronline;
    $users_id_index = array();
    if(dbc_index::issetIndex('onlinecheck'))
        $users_id_index = dbc_index::getIndex('onlinecheck');
    
    if(array_key_exists($tid, $users_id_index)) {
        $row = dbc_index::getIndexKey('onlinecheck', $tid);
    } else {
        $row = db("SELECT `id` FROM `".$db['users']."` WHERE `id` = '".intval($tid)."' AND time+".$useronline.">".time()." AND `online` = 1;",true);
        $users_id_index[$tid] = $row;
        dbc_index::setIndex('onlinecheck', $users_id_index);
    }

    return $row ? "<img src=\"../inc/images/online.gif\" alt=\"\" class=\"icon\" />" : "<img src=\"../inc/images/offline.gif\" alt=\"\" class=\"icon\" />";
}

//Funktion fuer die Sprachdefinierung der Profilfelder
function pfields_name($name) {
    $pattern = array("=_city_=Uis","=_hobbys_=Uis","=_motto_=Uis","=_job_=Uis","=_exclans_=Uis","=_email2_=Uis","=_email3_=Uis","=_autor_=Uis","=_auto_=Uis","=_buch_=Uis",
    "=_drink_=Uis","=_essen_=Uis","=_favoclan_=Uis","=_film_=Uis","=_game_=Uis","=_map_=Uis","=_musik_=Uis","=_person_=Uis","=_song_=Uis","=_spieler_=Uis","=_sportler_=Uis",
    "=_sport_=Uis","=_waffe_=Uis","=_board_=Uis","=_cpu_=Uis","=_graka_=Uis","=_hdd_=Uis","=_headset_=Uis","=_inet_=Uis","=_maus_=Uis","=_mauspad_=Uis","=_monitor_=Uis",
    "=_ram_=Uis","=_system_=Uis");

    $replacement = array(_profil_city,_profil_hobbys,_profil_motto,_profil_job,_profil_exclans,_profil_email2,_profil_email3,_profil_autor,_profil_auto,
    _profil_buch,_profil_drink,_profil_essen,_profil_favoclan,_profil_film,_profil_game,_profil_map,_profil_musik,_profil_person,_profil_song,_profil_spieler,
    _profil_sportler,_profil_sport,_profil_waffe,_profil_board,_profil_cpu,_profil_graka,_profil_hdd,_profil_headset,_profil_inet,_profil_maus,_profil_mauspad,
    _profil_monitor,_profil_ram,_profil_os);

    return preg_replace($pattern, $replacement, $name);
}

//-> Checkt versch. Dinge anhand der Hostmaske eines Users
function ipcheck($what,$time = "") {
    global $db,$userip;
    $get = db("SELECT `time`,`what` FROM `".$db['ipcheck']."` WHERE `what` = '".$what."' AND `ip` = '".$userip."' ORDER BY `time` DESC;",false,true);
    if(preg_match("#vid#", $get['what']))
        return true;
    else {
        if($get['time']+$time<time())
            db("DELETE FROM `".$db['ipcheck']."` WHERE `what` = '".$what."' AND `ip` = '".$userip."' AND time+".$time."<".time().";");

        return ($get['time']+$time>time() ? true : false);
    }
}

//-> Gibt die Tageszahl eines Monats aus
function days_in_month($month, $year)
{ return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31); }

//-> Setzt bei einem Tag >10 eine 0 vorran (Kalender)
function cal($i) {
    if(preg_match("=10|20|30=Uis",$i) == FALSE) $i = preg_replace("=0=", "", $i);
    if($i < 10) $tag_nr = "0".$i;
    else $tag_nr = $i;
    return $tag_nr;
}

//-> Entfernt fuehrende Nullen bei Monatsangaben
function nonum($i) {
    if(preg_match("=10=Uis",$i) == false)
        return preg_replace("=0=", "", $i);

    return $i;
}

//-> Konvertiert Platzhalter in die jeweiligen bersetzungen
function navi_name($name) {
    $name = trim($name);
    if(preg_match("#^_(.*?)_$#Uis",$name)) {
        $name = preg_replace("#_(.*?)_#Uis", "$1", $name);

        if(defined("_".$name))
            return constant("_".$name);
    }

    return $name;
}

// Userpic ausgeben
function userpic($userid, $width=170,$height=210) {
    global $picformat;
    foreach($picformat as $endung) {
        if(file_exists(basePath."/inc/images/uploads/userpics/".$userid.".".$endung)) {
            $pic = show(_userpic_link, array("id" => $userid, "endung" => $endung, "width" => $width, "height" => $height));
            break;
        }
        else
            $pic = show(_no_userpic, array("width" => $width, "height" => $height));
    }

    return $pic;
}

// Useravatar ausgeben
function useravatar($uid=0, $width=100,$height=100) {
    global $picformat,$userid;
    $uid = $uid == 0 ? $userid : $uid;
    foreach($picformat as $endung) {
        if(file_exists(basePath."/inc/images/uploads/useravatare/".$uid.".".$endung)) {
            $pic = show(_userava_link, array("id" => $uid, "endung" => $endung, "width" => $width, "height" => $height));
            break;
        }
        else
            $pic = show(_no_userava, array("width" => $width, "height" => $height));
    }

    return $pic;
}

// Userpic fuer Hoverinformationen ausgeben
function hoveruserpic($userid, $width=170,$height=210) {
    global $picformat;
    $pic = "../inc/images/nopic.gif', '".$width."', '".$height;
    foreach($picformat as $endung) {
        if(file_exists(basePath."/inc/images/uploads/userpics/".$userid.".".$endung)) {
            $pic = "../inc/images/uploads/userpics/".$userid.".".$endung."', '".$width."', '".$height."";
            break;
        }
    }

    return $pic;
}

// Adminberechtigungen ueberpruefen
function admin_perms($userid) {
    global $db,$chkMe;

    if(empty($userid))
        return false;

   // no need for these admin areas
    $e = array('gb', 'shoutbox', 'editusers', 'votes', 'contact', 'joinus', 'intnews', 'forum', 'gs_showpw');

   // check user permission
    $c = db("SELECT * FROM `".$db['permissions']."` WHERE `user` = ".intval($userid).";",false,true);
    if(!empty($c)) {
        foreach($c AS $v => $k) {
            if($v != 'id' && $v != 'user' && $v != 'pos' && !in_array($v, $e)) {
                if($k == 1) {
                    return true;
                    break;
                }
            }
        }
    }

   // check rank permission
    $qry = db("SELECT s1.* FROM `".$db['permissions']."` AS `s1` LEFT JOIN `".$db['userpos']."` AS `s2` ON s1.`pos` = s2.`posi` WHERE s2.`user` = ".intval($userid)." AND s2.`posi` != 0;");
    while($r = _fetch($qry)) {
        foreach($r AS $v => $k) {
            if($v != 'id' && $v != 'user' && $v != 'pos' && !in_array($v, $e)) {
                if($k == 1) {
                    return true;
                    break;
                }
            }
        }
    }

    return ($chkMe == 4) ? true : false;
}

/**
 * Erkennt Spider und Crawler um sie von der Besucherstatistik auszuschliessen.
 * @return boolean
 */
function isSpider() {
    $bots_basic = array('bot', 'b o t', 'spider', 'spyder', 'crawl', 'slurp', 'robo', 'yahoo', 'ask', 'google', '80legs', 'acoon',
            'altavista', 'al_viewer', 'appie', 'appengine-google', 'arachnoidea', 'archiver', 'asterias', 'ask jeeves', 'beholder',
            'bildsauger', 'bingsearch', 'bingpreview', 'bumblebee', 'bramptonmoose', 'cherrypicker', 'crescent', 'coccoc', 'cosmos',
            'docomo', 'drupact', 'emailsiphon', 'emailwolf', 'extractorpro', 'exalead ng', 'ezresult', 'feedfetcher', 'fido', 'fireball',
            'flipboardproxy', 'gazz', 'getweb', 'gigabaz', 'gulliver', 'harvester', 'hcat', 'heritrix', 'hloader', 'hoge', 'httrack',
            'incywincy', 'infoseek', 'infohelfer', 'inktomi', 'indy library', 'informant', 'internetami', 'internetseer', 'link', 'larbin',
            'jakarta', 'mata hari', 'medicalmatrix', 'mercator', 'miixpc', 'moget', 'msnptc', 'muscatferret', 'netcraftsurveyagent',
            'openxxx', 'picmole', 'piranha', 'pldi.net', 'p357x', 'quosa', 'rambler', 'rippers', 'rganalytics', 'scan', 'scooter', 'ScoutJet',
            'siclab', 'siteexplorer', 'sly', 'searchme', 'spy', 'swisssearch', 'sqworm', 'trivial', 't-h-u-n-d-e-r-s-t-o-n-e', 'teoma',
            'twiceler', 'ultraseek', 'validator', 'webbandit', 'webmastercoffee', 'webwhacker', 'wevika', 'wisewire', 'yandex', 'zyborg',
            'Teoma', 'alexa', 'froogle', 'Gigabot', 'inktomi', 'looksmart', 'URL_Spider_SQL', 'Firefly', 'NationalDirectory', 'Ask Jeeves', 'TECNOSEEK', 
            'InfoSeek', 'WebFindBot', 'girafabot', 'crawler', 'www.galaxy.com', 'Googlebot', 'Googlebot/2.1', 'Google', 'Google Webmaster', 'Scooter', 
            'James Bond', 'Slurp', 'msnbot', 'appie', 'FAST', 'WebBug', 'Spade', 'ZyBorg', 'rabaz', 'Baiduspider', 'Feedfetcher-Google',
            'TechnoratiSnoop', 'Rankivabot', 'Mediapartners-Google', 'Sogou web spider', 'WebAlta Crawler', 'MJ12bot',
            'Yandex', 'YaDirectBot', 'StackRambler','DotBot','dotbot');

    $UserAgent = trim(GetServerVars('HTTP_USER_AGENT'));
    foreach ($bots_basic as $bot) {
        if(stristr($UserAgent, $bot) !== FALSE || strpos($bot, $UserAgent)) {
            return true;
        }
    }

    //Old DZCP Spiders Text
    if(file_exists(basePath.'/inc/_spiders.txt')) {
        $ex = explode("\n", file_get_contents(basePath.'/inc/_spiders.txt'));
        for($i=0;$i<=count($ex)-1;$i++) {
            if(stristr($UserAgent, trim($ex[$i]))) {
                return true;
            }
        }
    }
	
    return false;
}

//-> filter placeholders
function pholderreplace($pholder) {
    $search = array('@<script[^>]*?>.*?</script>@si','@<style[^>]*?>.*?</style>@siU','@<[\/\!][^<>]*?>@si','@<![\s\S]*?--[ \t\n\r]*>@');

    //Replace
    $pholder = preg_replace("#<script(.*?)</script>#is","",$pholder);
    $pholder = preg_replace("#<style(.*?)</style>#is","",$pholder);
    $pholder = preg_replace($search, '', $pholder);
    $pholder = str_replace(" ","",$pholder);
    $pholder = preg_replace("#&(.*?);#s","",$pholder);
    $pholder = str_replace("\r","",$pholder);
    $pholder = str_replace("\n","",$pholder);
    $pholder = preg_replace("#\](.*?)\[#is","][",$pholder);
    $pholder = str_replace("][","^",$pholder);
    $pholder = preg_replace("#^(.*?)\[#s","",$pholder);
    $pholder = preg_replace("#\](.*?)$#s","",$pholder);
    $pholder = str_replace("[","",$pholder);
    return str_replace("]","",$pholder);
}

//-> Zugriffsberechtigung auf die Seite
function check_internal_url() {
    global $db,$chkMe;
    if($chkMe >= 1) return false;
    $install_pfad = explode("/",dirname(dirname($_SERVER['SCRIPT_NAME'])."../"));
    $now_pfad = explode("/",$_SERVER['REQUEST_URI']); $pfad = '';
    foreach($now_pfad as $key => $value) {
        if(!empty($value)) {
            if(!isset($install_pfad[$key]) || $value != $install_pfad[$key]) {
                $pfad .= "/".$value;
            }
        }
    }

    list($pfad) = explode('&',$pfad);
    $pfad = "..".$pfad;

    if(strpos($pfad, "?") === false && strpos($pfad, ".php") === false)
        $pfad .= "/";

    if(strpos($pfad, "index.php") !== false)
        $pfad = str_replace('index.php','',$pfad);

    $qry_navi = db("SELECT `internal` FROM `".$db['navi']."` WHERE `url` = '".$pfad."' OR `url` = '".$pfad.'index.php'."';");
    if(_rows($qry_navi)) {
        $get_navi = _fetch($qry_navi);
        if($get_navi['internal'])
            return true;
    }

    return false;
}

//-> Ladezeit
function generatetime() {
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}

//-> Rechte abfragen
function getPermissions($checkID = 0, $pos = 0) {
    global $db;

    if(!empty($checkID)) {
        $check = empty($pos) ? 'user' : 'pos'; $checked = array();
        $qry = db("SELECT * FROM ".$db['permissions']." WHERE `".$check."` = '".intval($checkID)."'");
        if(_rows($qry)) foreach(_fetch($qry) AS $k => $v) $checked[$k] = $v;
    }

    $permission = array();
    $qry = db("SHOW COLUMNS FROM ".$db['permissions']."");
    while($get = _fetch($qry)) {
        if($get['Field'] != 'id' && $get['Field'] != 'user' && $get['Field'] != 'pos' && $get['Field'] != 'intforum') {
            @eval("\$lang = _perm_".$get['Field'].";");
            $chk = empty($checked[$get['Field']]) ? '' : ' checked="checked"';
            $permission[$lang] = '<input type="checkbox" class="checkbox" id="'.$get['Field'].'" name="perm[p_'.$get['Field'].']" value="1"'.$chk.' /><label for="'.$get['Field'].'"> '.$lang.'</label> ';
        }
    }

    natcasesort($permission); $break = 1; $p = '';
    foreach($permission AS $perm) {
        $br = ($break % 2) ? '<br />' : ''; $break++;
        $p .= $perm.$br;
    }

    return $p;
}

//-> interne Foren-Rechte abfragen
function getBoardPermissions($checkID = 0, $pos = 0) {
    global $db, $dir;

    $break = 0; $i_forum = ''; $fkats = '';
    $qry = db("SELECT `id`,`name` FROM `".$db['f_kats']."` WHERE `intern` = 1 ORDER BY `kid` ASC;");
    while($get = _fetch($qry)) {
        unset($kats, $fkats, $break);
        $kats = (empty($katbreak) ? '' : '<div style="clear:both">&nbsp;</div>').'<table class="hperc" cellspacing="1"><tr><td class="contentMainTop"><b>'.re($get["name"]).'</b></td></tr></table>';
        $katbreak = 1;

        $qry2 = db("SELECT `kattopic`,`id` FROM `".$db['f_skats']."` WHERE `sid` = ".$get['id']." ORDER BY `kattopic` ASC;"); $break = 0; $fkats = '';
        while($get2 = _fetch($qry2)) {
            $br = ($break % 2) ? '<br />' : ''; $break++;
            $check =  db("SELECT `id` FROM `".$db['f_access']."` WHERE `".(empty($pos) ? 'user' : 'pos')."` = ".intval($checkID)." AND ".(empty($pos) ? 'user' : 'pos')." != 0 AND `forum` = ".$get2['id'].";");
            $chk = _rows($check) ? ' checked="checked"' : '';
            $fkats .= '<input type="checkbox" class="checkbox" id="board_'.$get2['id'].'" name="board['.$get2['id'].']" value="'.$get2['id'].'"'.$chk.' /><label for="board_'.$get2['id'].'"> '.re($get2['kattopic']).'</label> '.$br;
        }

        $i_forum .= $kats.$fkats;
    }

    return $i_forum;
}

//-> schreibe in dei IPCheck Tabelle
function setIpcheck($what = '') {
    global $db, $userip;
    db("INSERT INTO `".$db['ipcheck']."` SET `ip` = '".$userip."', `user_id` = ".userid().", `what` = '".$what."', `time` = ".time().";");
}

//-> Preuft ob alle clicks nur einmal gezahlt werden *gast/user
function count_clicks($side_tag='',$clickedID=0,$update=true) {
    global $db,$userip,$userid,$chkMe,$isSpider;
    if(!$isSpider) {
        $qry = db("SELECT `id`,`side` FROM `".$db['clicks_ips']."` WHERE `uid` = 0 AND `time` <= ".time().";");
        if(_rows($qry)) while($get = _fetch($qry)) { if($get['side'] != 'vote') db("DELETE FROM `".$db['clicks_ips']."` WHERE `id` = ".$get['id'].";"); }

        if($chkMe != 'unlogged') {
            if(db("SELECT `id` FROM `".$db['clicks_ips']."` WHERE `uid` = ".$userid." AND `ids` = ".$clickedID." AND `side` = '".$side_tag."';",true))
                return false;

            if(db("SELECT `id` FROM `".$db['clicks_ips']."` WHERE `ip` = '".$userip."' AND `ids` = ".$clickedID." AND `side` = '".$side_tag."';",true)) {
                if($update)
                    db("UPDATE `".$db['clicks_ips']."` SET `uid` = ".$userid.", `time` = '".(time()+count_clicks_expires)."' WHERE `ip` = '".$userip."' AND `ids` = ".$clickedID." AND `side` = '".$side_tag."';");

                return false;
            } else {
                if($update)
                    db("INSERT INTO `".$db['clicks_ips']."` (`id` ,`ip` ,`uid` ,`ids`, `side`, `time`) VALUES (NULL , '".$userip."', ".$userid.", ".$clickedID.", '".$side_tag."', '".(time()+count_clicks_expires)."');");

                return true;
            }
        } else {
            if(!db("SELECT id FROM `".$db['clicks_ips']."` WHERE `ip` = '".$userip."' AND `ids` = ".$clickedID." AND `side` = '".$side_tag."';",true)) {
                if($update)
                    db("INSERT INTO `".$db['clicks_ips']."` (`id` ,`ip` ,`uid` ,`ids`, `side`, `time`) VALUES (NULL , '".$userip."', 0, ".$clickedID.", '".$side_tag."', '".(time()+count_clicks_expires)."');");

                return true;
            }
        }
    }

    return false;
}

function is_php($version='5.3.0')
{ return (floatval(phpversion()) >= $version); }

function hextobin($hexstr) {
    if(is_php('5.4.0'))
        return hex2bin($hexstr);
    // < PHP 5.4
    $n = strlen($hexstr);
    $sbin="";
    $i=0;
    while($i<$n) {
        $a =substr($hexstr,$i,2);
        $c = pack("H*",$a);
        if ($i==0){$sbin=$c;}
        else {$sbin.=$c;}
        $i+=2;
    }

    return $sbin;
}

//-> Codiert Text zur Speicherung
final class string {
    /**
     * Codiert Text in das UTF8 Charset.
     *
     * @param string $txt
     */
    public static function encode($txt='')
    { global $charset; return utf8_encode(stripcslashes(spChars(htmlentities($txt, ENT_COMPAT, $charset)))); }

    /**
     * Decodiert UTF8 Text in das aktuelle Charset der Seite.
     *
     * @param utf8 string $txt
     */
    public static function decode($txt='')
    { global $charset; return trim(stripslashes(spChars(html_entity_decode(utf8_decode($txt), ENT_COMPAT, $charset),true))); }
}

//-> Speichert Ruckgaben der MySQL Datenbank zwischen um SQL-Queries einzusparen
final class dbc_index {
    private static $index = array();
    private static $is_mem = false;

    public static final function init() {
        self::$is_mem = self::MemSetIndex();
    }

    public static final function setIndex($index_key,$data) {
        global $cache,$config_cache;

        if(self::MemSetIndex()) {
            if(show_dbc_debug)
                DebugConsole::insert_info('dbc_index::setIndex()', 'Set index: "'.$index_key.'" to cache');

            if($config_cache['use_cache'])
                $cache->set('dbc_'.$index_key, serialize($data), 1.2);
        }

        if(show_dbc_debug)
            DebugConsole::insert_info('dbc_index::setIndex()', 'Set index: "'.$index_key.'"');

        self::$index[$index_key] = $data;
    }

    public static final function getIndex($index_key) {
        if(!self::issetIndex($index_key))
            return false;

        if(show_dbc_debug)
            DebugConsole::insert_info('dbc_index::getIndex()', 'Get full index: "'.$index_key.'"');

        return self::$index[$index_key];
    }

    public static final function getIndexKey($index_key,$key) {
        if(!self::issetIndex($index_key))
            return false;

        $data = self::$index[$index_key];
        if(empty($data) || !array_key_exists($key,$data))
            return false;

        if(show_dbc_debug)
            DebugConsole::insert_info('dbc_index::getIndexKey()', 'Get from index: "'.$index_key.'" get key: "'.$key.'"');

        return $data[$key];
    }

    public static final function issetIndex($index_key) {
        global $cache,$config_cache;
        if(isset(self::$index[$index_key])) return true;
        if(self::$is_mem && $config_cache['use_cache'] && $cache->isExisting('dbc_'.$index_key)) {

            if(show_dbc_debug)
                DebugConsole::insert_loaded('dbc_index::issetIndex()', 'Load index: "'.$index_key.'" from cache');

            self::$index[$index_key] = unserialize($cache->get('dbc_'.$index_key));
            return true;
        }

        return false;
    }

    public static final function useMem() {
        return self::$is_mem;
    }

    private static final function MemSetIndex() {
        global $config_cache;
        if(!$config_cache['dbc']) return false;
        switch ($config_cache['storage']) {
            case 'apc': return (extension_loaded('apc') && ini_get('apc.enabled') && strpos(PHP_SAPI,"CGI") === false); break;
            case 'memcached': return (ping_port($config_cache['server'][0][0],$config_cache['server'][0][1],0.2) && class_exists("memcached")); break;
            case 'memcache': return (ping_port($config_cache['server'][0][0],$config_cache['server'][0][1],0.2) && function_exists("memcache_connect")); break;
            case 'xcache': return (extension_loaded('xcache') && function_exists("xcache_get")); break;
            case 'wincache': return (extension_loaded('wincache') && function_exists("wincache_ucache_set")); break;
            case 'auto':
                return ((extension_loaded('apc') && ini_get('apc.enabled') && strpos(PHP_SAPI,"CGI") === false) ||
                       ($config_cache['dbc_auto_memcache'] && ping_port($config_cache['server'][0][0],$config_cache['server'][0][1],0.2) && class_exists("memcached")) ||
                       ($config_cache['dbc_auto_memcache'] && ping_port($config_cache['server'][0][0],$config_cache['server'][0][1],0.2) && function_exists("memcache_connect")) ||
                       (extension_loaded('xcache') && function_exists("xcache_get")) ||
                       (extension_loaded('wincache') && function_exists("wincache_ucache_set")));
            break;
            default: return false; break;
        }

        return false;
    }
}

/**
 * Gibt die vergangene zeit zwischen $timestamp und $aktuell als lesbaren string zurueck.
 * bsp: 3 Wochen, 4 Tage, 5 Sekunden
 * @param int $timestamp * der timestamp der ersten zeit-marke.
 * @param int $aktuell * der timestamp der zweiten zeit-marke. * aktuelle zeit *
 * @param int $anzahl_einheiten * wie viele einheiten sollen maximal angezeigt werden
 * @param boolean $zeige_leere_einheiten * sollen einheiten, die den wert 0 haben, angezeigt werden?
 * @param array $zeige_einheiten * zeige nur angegebene einheiten. jahre werden zb in sekunden umgerechnet
 * @param string $standard * falls der timestamp 0 oder ungueltig ist, gebe diesen string zurueck
 * @return string
 */
function get_elapsed_time( $timestamp, $aktuell = null, $anzahl_einheiten = null, $zeige_leere_einheiten = null, $zeige_einheiten = null, $standard = null ) {
    if ( $aktuell === null ) $aktuell = time();
    if ( $anzahl_einheiten === null ) $anzahl_einheiten = 1;
    if ( $zeige_leere_einheiten === null ) $zeige_leere_einheiten = true;
    if ( !is_array( $zeige_einheiten ) ) $zeige_einheiten = array();
    if ( $standard === null ) $standard = "nie";
    if ( $timestamp == 0 ) return $standard;
    if ( $timestamp > $aktuell ) $timestamp = $aktuell;
    if ( $anzahl_einheiten < 1 ) $anzahl_einheiten = 10;
    $zeit = bcsub( $aktuell, $timestamp );
    if ( $zeit < 1 ) $zeit = 1; $arr = array();
    $werte = array( 63115200 => _years, 31557600 => _year.' ', 4838400 => _months, 2419200 => _month.' ',
            1209600 => _weeks, 604800 => _week.' ', 172800 => _days.' ', 86400 => _day.' ', 7200 => _hours,
            3600 => _hour.' ', 120 => _minutes, 60 => _minute.' ',  1 => _seconds );

    if ( ( is_array( $zeige_einheiten ) ) and ( count( $zeige_einheiten ) > 0 ) ) {
        $neu = array();
        foreach ( $werte as $key => $val ) {
            if ( in_array( $val, $zeige_einheiten ) )
                $neu[$key] = $val;
        }

        $werte = $neu;
    }

    foreach ( $werte as $div => $einheit ) {
        if ( $zeit < $div ) {
            if ( count( $arr ) != 0 )
                $arr[$einheit] = 0;

            continue;
        }

        $anzahl = bcdiv( $zeit, $div );
        $zeit -= bcmul( $anzahl, $div );
        $arr[$einheit] = $anzahl;
    }

    reset( $arr ); $output = 0; $ret = "";
    while ( ( count( $arr ) > 0 ) and ( $output < $anzahl_einheiten ) ) {
        $key = key( $arr );
        $cur = current( $arr );
        $einheit = ( $cur == 1 ) ? substr( $key, 0, bcsub( strlen( $key ), 1 ) ) : $key;
        if ( ( $cur != 0 ) or ( $zeige_leere_einheiten == true ) )
            $ret .= ( empty( $ret ) )
            ? ($anzahl_einheiten == 1 ? round($cur, 0, PHP_ROUND_HALF_DOWN) : $cur) . " " . $einheit
            : ", " . ($anzahl_einheiten == 1 ? round($cur, 0, PHP_ROUND_HALF_DOWN) : $cur) . " " . $einheit;
        $output++;
        unset( $arr[$key] );
    }
    return $ret;
}

/**
 * Verschlusselt eine E-Mail Adresse per Javascript
 * @param string $email
 * @param string $template
 * @return string
 */
function CryptMailto($email='',$template=_emailicon,$custom=array()) {
    if(empty($template) || empty($email) || !permission("editusers")) return '';
    $character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
    $key = str_shuffle($character_set); $cipher_text = ''; $id = 'e'.rand(1,999999999);
    for ($i=0;$i<strlen($email);$i+=1) $cipher_text.= $key[strpos($character_set,$email[$i])];
    $script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
    $script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
    if(!empty($custom) && count($custom) >= 1) $template = show($template,$custom);
    $script.= 'document.getElementById("'.$id.'").innerHTML="'.$template.'"';
    $script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")";
    $script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';
    return '<span id="'.$id.'">[javascript protected email address]</span>'.$script;
}

//-> Neue Languages einbinden, sofern vorhanden
if($language_files = get_files(basePath.'/inc/additional-languages/'.$language.'/',false,true,array('php'))) {
    foreach($language_files AS $languages)
    { include(basePath.'/inc/additional-languages/'.$language.'/'.$languages); }
    unset($language_files,$languages);
}

//-> Neue Funktionen einbinden, sofern vorhanden
if($functions_files = get_files(basePath.'/inc/additional-functions/',false,true,array('php'))) {
    foreach($functions_files AS $func)
    { include(basePath.'/inc/additional-functions/'.$func); }
    unset($functions_files,$func);
}

//-> Navigation einbinden
if(file_exists(basePath.'/inc/menu-functions/navi.php'))
    include_once(basePath.'/inc/menu-functions/navi.php');

//-> Ausgabe des Indextemplates
function page($index='',$title='',$where='',$wysiwyg='',$index_templ='index') {
    global $db,$userid,$userip,$tmpdir,$chkMe,$charset,$mysql,$dir;
    global $designpath,$language,$cp_color,$time_start;

    // Timer Stop
    $time = round(generatetime() - $time_start,4);

    // JS-Dateine einbinden
    $lng = ($language=='deutsch')?'de':'en';
    $edr = ($wysiwyg=='_word')?'advanced':'normal';
    $lcolor = ($cp_color==1)?'lcolor=true;':'';
    $java_vars = '<script language="javascript" type="text/javascript">var maxW = '.config('maxwidth').',lng = \''.$lng.'\',dzcp_editor = \''.$edr.'\';'.$lcolor.'</script>'."\n";

    if(!strstr($_SERVER['HTTP_USER_AGENT'],'Android') && !strstr($_SERVER['HTTP_USER_AGENT'],'webOS'))
        $java_vars .= '<script language="javascript" type="text/javascript" src="'.$designpath.'/_js/wysiwyg.js"></script>'."\n";;

    if(settings("wmodus") && $chkMe != 4) {
        if(config('securelogin'))
            $secure = show("menu/secure", array("help" => _login_secure_help, "security" => _register_confirm));

        $login = show("errors/wmodus_login", array("what" => _login_login, "secure" => $secure, "signup" => _login_signup, "permanent" => _login_permanent, "lostpwd" => _login_lostpwd));
        cookie::save(); //Save Cookie
        echo show("errors/wmodus", array("wmodus" => _wartungsmodus,
                                         "head" => _wartungsmodus_head,
                                         "tmpdir" => $tmpdir,
                                         "java_vars" => $java_vars,
                                         "dir" => $designpath,
                                         "title" => re(strip_tags($title)),
                                         "login" => $login));
    } else {
        updateCounter();
        update_maxonline();

        //check permissions
        if(!$chkMe) {
            include_once(basePath.'/inc/menu-functions/login.php');
            $check_msg = '';
        }
        else {
            $check_msg = check_msg(); set_lastvisit(); $login = "";
        }

        //init templateswitch
        $tmpldir=""; $tmps = get_files(basePath.'/inc/_templates_/',true);
        foreach ($tmps as $tmp) {
            $selt = ($tmpdir == $tmp ? 'selected="selected"' : '');
            $tmpldir .= show(_select_field, array("value" => "?tmpl_set=".$tmp,  "what" => $tmp,  "sel" => $selt));
        }

        //misc vars
        $lang = $language;
        $template_switch = show("menu/tmp_switch", array("templates" => $tmpldir));
        $clanname = re(settings("clanname"));
        $time = show(_generated_time, array("time" => $time));
        $headtitle = show(_index_headtitle, array("clanname" => $clanname));
        $rss = $clanname;
        $title = re(strip_tags($title));

        if(check_internal_url())
            $index = error(_error_have_to_be_logged, 1);

        $where = preg_replace_callback("#autor_(.*?)$#",create_function('$id', 'return re(data("nick","$id[1]"));'),$where);
        $index = empty($index) ? '' : (!$check_msg ? '' : $check_msg).'<table class="mainContent" cellspacing="1">'.$index.'</table>';
        update_online($where); //Update Stats

        //template index autodetect
        $index_templ = ($index_templ == 'index' && file_exists($designpath.'/index_'.$dir.'.html') ? 'index_'.$dir : $index_templ);
        
        //check if placeholders are given
        $pholder = file_get_contents($designpath."/".$index_templ.".html");

        //filter placeholders
        $dir = $designpath; //after template index autodetect!!!
        $blArr = array("[clanname]","[title]","[java_vars]","[login]","[template_switch]","[headtitle]","[index]","[time]","[rss]","[dir]","[charset]","[where]","[lang]");
        $pholdervars = '';
        for($i=0;$i<=count($blArr)-1;$i++) {
            if(preg_match("#".$blArr[$i]."#",$pholder))
                $pholdervars .= $blArr[$i];
        }

        for($i=0;$i<=count($blArr)-1;$i++)
            $pholder = str_replace($blArr[$i],"",$pholder);

        $pholder = pholderreplace($pholder);
        $pholdervars = pholderreplace($pholdervars);

        $menu_functions_index = get_files(basePath.'/inc/menu-functions',false,true,array('php'));
        $menu_index = array();
        foreach ($menu_functions_index as $mfphp) {
            $file = str_replace('.php', '', $mfphp);
            $menu_index[$file] = file_exists(basePath.'/inc/menu-functions/'.$file.'.php');
        } unset($menu_functions_index);

        //put placeholders in array
        $arr = array();
        $pholder = explode("^",$pholder);
        for($i=0;$i<=count($pholder)-1;$i++) {
            if(strstr($pholder[$i], 'nav_'))
                $arr[$pholder[$i]] = navi($pholder[$i]);
            else {
                //optimize code * spart ~8ms
                if(array_key_exists($pholder[$i], $menu_index) && $menu_index[$pholder[$i]])
                    include_once(basePath.'/inc/menu-functions/'.$pholder[$i].'.php');

                if(function_exists($pholder[$i]))
                    $arr[$pholder[$i]] = $pholder[$i]();
            }
        }

        $pholdervars = explode("^",$pholdervars);
        for($i=0;$i<=count($pholdervars)-1;$i++)
        { $arr[$pholdervars[$i]] = $$pholdervars[$i]; }
        $arr['sid'] = (mt_rand(1,10) / 100);

        //index output
        $index = (file_exists(basePath."/inc/_templates_/".$tmpdir."/".$index_templ.".html") ? show($index_templ, $arr) : show("index", $arr));
        if(!mysqli_persistconns) $mysql->close(); //MySQL
        cookie::save(); //Save Cookie
        if(debug_save_to_file) DebugConsole::save_log(); //Debug save to file
        $output = view_error_reporting ? DebugConsole::show_logs().$index : $index; //Debug Console + Index Out
        gz_output($output); // OUTPUT BUFFER END
    }
}