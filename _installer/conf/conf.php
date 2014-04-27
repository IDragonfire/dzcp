<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

###############
## Variablen ##
###############
define('_disabled_fopen', 'Dein Webserver unterst&uuml;tzt die Funktion <i>fopen</i> nicht!');
define('_do_config', 'Du musst die Konfiguration erfolgreich abschlie&szlig;en, um die Datenbank installieren zu k&ouml;nnen!');
define('_true', '<img src="img/true.gif" border="0" alt="" vspace="0" align="center"> ');
define('_false', '<img src="img/false.gif" border="0" alt="" vspace="0" align="center"> ');
define('_link_start', '<font class="enabled">&raquo; Lizenz</font>');
define('_link_start_1', '<font class="disabled">1. Lizenz</font>');
define('_link_require', '<font class="enabled">&raquo; Erweiterungen</font>');
define('_link_require_1', '<font class="disabled">2. Erweiterungen</font>');
define('_link_prepare', '<font class="enabled">&raquo; Vorbereitung</font>');
define('_link_prepare_1', '<font class="disabled">3. Vorbereitung</font>');
define('_link_install', '<font class="enabled">&raquo; MySQL</font>');
define('_link_install_1', '<font class="disabled">4. MySQL</font>');
define('_link_db', '<font class="enabled">&raquo; Installation</font>');
define('_link_db_1', '<font class="disabled">5. Installation</font>');
define('_link_dbu', '<font class="enabled">&raquo; Update</font>');
define('_link_dbu_1', '<font class="disabled">4. Update</font>');
define('_link_done', '<font class="enabled">&raquo; Done</font>');
define('_link_done_1', '<font class="disabled">6. Done</font>');

define('_link_update_done', '<font class="enabled">&raquo; Done</font>');
define('_link_update_done_1', '<font class="disabled">5. Done</font>');

################
## Funktionen ##
################
function check_file_dir($file, $is_file=false) {
    if($is_file == 1) $what = "Dir:&nbsp;";
    else $what = "File:";

    $_file = preg_replace("#\.\.#Uis", "", $file);
    if(is_writable($file))
        return _true."<font color='green'><b>".$what."</b>&nbsp;&nbsp;&nbsp; ".$_file."</font><br />";
    else
        return _false."<font color='red'><b>".$what."</b>&nbsp;&nbsp;&nbsp; ".$_file."</font><br />";
}

function set_ftp_chmod($file,$pfad,$host,$user,$pwd) {
    $conn = @ftp_connect($host);
    @ftp_login($conn, $user, $pwd);
    ftp_site($conn, 'CHMOD 0777 '.$pfad.'/'.$file);
}

function _m ($prefix, $host, $user, $pwd, $db) {
    $fp = @fopen("../inc/mysql.php","w");
    @fwrite($fp,"<?php
                 \$sql_prefix = '".$prefix."';
                 \$sql_host = '".$host."';
                 \$sql_user =  '".$user."';
                 \$sql_pass = '".$pwd."';
                 \$sql_db = '".$db."';
               ?>");
    @fclose($fp);
}

function get_files($dir) {
    $dp = @opendir($dir);
    $files = array();
    while($file = @readdir($dp))
      {
        if($file != '.' && $file != '..')
              array_push($files, $file);
    }
      @closedir($dp);
      sort($files);
      return($files);
}

function makePrev() {
    $arr = array(0,1,2,3,4,5,6,7,8,9);
    return $arr[rand(0,9)].$arr[rand(0,9)].$arr[rand(0,9)];
}

function up($txt,$bbcode=0) {
    $txt = str_replace("& ","&amp; ",$txt);
    $txt = str_replace("\"","&#34;",$txt);
    $txt = trim($txt);
    if(empty($bbcode)) $txt = nl2br($txt);
    return spChars($txt);
}

function spChars($txt) {
    $txt = str_replace("Ä","&Auml;",$txt);
    $txt = str_replace("ä","&auml;",$txt);
    $txt = str_replace("Ü","&Uuml;",$txt);
    $txt = str_replace("ü","&uuml;",$txt);
    $txt = str_replace("Ö","&Ouml;",$txt);
    $txt = str_replace("ö","&ouml;",$txt);
    $txt = str_replace("ß","&szlig;",$txt);
    return str_replace("€","&euro;",$txt);
}

function visitorIp() {
    $TheIp=$_SERVER['REMOTE_ADDR'];
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        $TheIp = $_SERVER['HTTP_X_FORWARDED_FOR'];

    if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']))
        $TheIp = $_SERVER['HTTP_CLIENT_IP'];

    if(isset($_SERVER['HTTP_FROM']) && !empty($_SERVER['HTTP_FROM']))
        $TheIp = $_SERVER['HTTP_FROM'];

    $TheIp_X = explode('.',$TheIp);
    if(count($TheIp_X) == 4 && $TheIp_X[0]<=255 && $TheIp_X[1]<=255 && $TheIp_X[2]<=255 && $TheIp_X[3]<=255 && preg_match("!^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$!",$TheIp))
        return trim($TheIp);

    return '0.0.0.0';
}