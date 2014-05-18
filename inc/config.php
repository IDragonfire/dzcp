<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

#########################################
//-> DZCP Settings Start
#########################################

define('view_error_reporting', false); // Zeigt alle Fehler und Notices etc.
define('debug_all_sql_querys', false);
define('debug_save_to_file', false);
define('debug_dzcp_handler', true);
define('fsockopen_support_bypass', false); //Umgeht die fsockopen pruefung

define('use_default_timezone', true); // Verwendende die Zeitzone vom Server
define('default_timezone', 'Europe/Berlin'); // Die zu verwendende Zeitzone selbst einstellen * 'use_default_timezone' auf false stellen *
define('admin_view_dzcp_news', true); // Entscheidet ob der Newstricker in der Administration angezeigt wird

define('thumbgen_cache', true); // Sollen die verkleinerten Bilder der Thumbgen gespeichert werden
define('thumbgen_cache_time', 60*60); // Wie lange soll das Bild aus dem Cache verwendet werden

define('feed_update_time', 10*60); // Wann soll der Newsfeed aktualisiert werden
define('cookie_expires', (60*60*24*30*12)); // Wie Lange die Cookies des CMS ihre Gueltigkeit behalten.
define('file_get_contents_timeout', 10);

define('auto_db_optimize', true); // Soll in der Datenbank regelmaessig ein OPTIMIZE TABLE ausgefuehrt werden?
define('auto_db_optimize_interval', (7*24*60*60)); // Wann soll der OPTIMIZE TABLE ausgefuehrt werden, alle 7 Tage.

define('dzcp_version_checker', true); // Version auf DZCP.de abgleichen und benachrichtigen ob eine neue Version zur Verfuegung steht
define('dzcp_version_checker_refresh', (30*60)); // Wie lange soll gewartet werden um einen Versionsabgleich auszufuehren

define('buffer_gzip_compress_level', 4); // Level der GZIP Kompression 1 - 9
define('buffer_show_licence_bar', true); // Schaltet die "Powered by DZCP - deV!L`z Clanportal V1.6" am ende der Seite an oder aus

define('steam_enable', true); // Steam Status anzeigen
define('steam_avatar_cache', true); // Steam Useravatare fuer schnellen Zugriff speichern
define('steam_avatar_refresh', (60*60)); // Wann soll das Avatarbild aktualisiert werden
define('steam_refresh', (8*60*60)); // Wann soll der Steam Status in der Userliste aktualisiert werden
define('steam_api_refresh', 30); // Wann sollen die Daten der Steam API aktualisiert werden * Online / Offline / In-Game Status
define('steam_infos_cache', true); //Sollen die Profil Daten zwischen gespeichert werden, * Cache Use
define('steam_only_proxy', false); //Sollen soll nur der Steam Proxy Server verwendet werden

/*
* Bitte vor der Aktivierung der Persistent Connections lesen:
* http://php.net/manual/de/features.persistent-connections.php
*/
define('mysqli_persistconns', false);

$config_cache = array();
$config_cache['storage'] = "files"; //auto,memcache,files,sqlite,wincache,xcache oder apc
$config_cache['server'] = array(array("127.0.0.1",11211,1));
$config_cache['dbc'] = true; //use database query caching * only use with memory cache
$config_cache['dbc_auto_memcache'] = false; //use database querie caching * auto memcache check

//-> Legt die UserID des Rootadmins fest
//-> (dieser darf bestimmte Dinge, den normale Admins nicht duerfen, z.B. andere Admins editieren)
$rootAdmins = array(1); // Die ID/s der User die Rootadmins sein sollen, bei mehreren mit "," trennen '1,4,2,6' usw.

#########################################
//-> DZCP Settings End
#########################################

if(function_exists("date_default_timezone_set") && function_exists("date_default_timezone_get") && use_default_timezone)
    @date_default_timezone_set(@date_default_timezone_get());
else if(!use_default_timezone) date_default_timezone_set(default_timezone);
else date_default_timezone_set("Europe/Berlin");
if(!isset($thumbgen)) $thumbgen = false;

if(!$thumbgen) {
    if(view_error_reporting) {
        error_reporting(E_ALL);

        if(function_exists('ini_set'))
            ini_set('display_errors', 1);

        DebugConsole::initCon();

        if(debug_dzcp_handler)
            set_error_handler('dzcp_error_handler');
    } else {
        if(function_exists('ini_set'))
            ini_set('display_errors', 0);

        error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

        if(debug_dzcp_handler)
            set_error_handler('dzcp_error_handler');
    }
}

## REQUIRES ##
//DZCP-Install default variable
if(!isset($installer)) $installer = false;
if(!isset($sql_host) || !isset($sql_user) || !isset($sql_pass) || !isset($sql_db)) {
    $sql_prefix = ''; $sql_host = ''; $sql_user =  ''; $sql_pass = ''; $sql_db = '';
}

if(file_exists(basePath."/inc/mysql.php"))
    require_once(basePath."/inc/mysql.php");

if(!isset($installation)) $installation = false;
if(!isset($updater)) $updater = false;
if(!isset($global_index)) $global_index = false;

function show($tpl="", $array=array(), $array_lang_constant=array(), $array_block=array()) {
    global $tmpdir,$chkMe;
    if(!empty($tpl) && $tpl != null) {
        $template = basePath."/inc/_templates_/".$tmpdir."/".$tpl;
        $array['dir'] = '../inc/_templates_/'.$tmpdir;

        if(file_exists($template.".html"))
            $tpl = file_get_contents($template.".html");

        //put placeholders in array
        $pholder = explode("^",pholderreplace($tpl));
        for($i=0;$i<=count($pholder)-1;$i++) {
            if(in_array($pholder[$i],$array_block))
                continue;

            if(array_key_exists($pholder[$i],$array))
                continue;

            if(!strstr($pholder[$i], 'lang_'))
                continue;

            if(defined(substr($pholder[$i], 4)))
                $array[$pholder[$i]] = (count($array_lang_constant) >= 1 ? show(constant(substr($pholder[$i], 4)),$array_lang_constant) : constant(substr($pholder[$i], 4)));
        }

        unset($pholder);

        $tpl = (!$chkMe ? preg_replace("|<logged_in>.*?</logged_in>|is", "", $tpl) : preg_replace("|<logged_out>.*?</logged_out>|is", "", $tpl));
        $tpl = str_ireplace(array("<logged_in>","</logged_in>","<logged_out>","</logged_out>"), '', $tpl);

        if(count($array) >= 1) {
            foreach($array as $value => $code)
            { $tpl = str_replace('['.$value.']', $code, $tpl); }
        }
    }

    return $tpl;
}

//-> MySQL-Datenbankangaben
$prefix = $sql_prefix;
$db = array("host" =>           $sql_host,
            "user" =>           $sql_user,
            "pass" =>           $sql_pass,
            "db" =>             $sql_db,
            "prefix" =>         $prefix,
            "artikel" =>        $prefix."artikel",
            "acomments" =>      $prefix."acomments",
            "awards" =>         $prefix."awards",
            "away" =>           $prefix."away",
            "banned" =>         $prefix."banned",
            "buddys" =>         $prefix."userbuddys",
            "ipcheck" =>        $prefix."ipcheck",
            "clankasse" =>      $prefix."clankasse",
            "c_kats" =>         $prefix."clankasse_kats",
            "c_payed" =>        $prefix."clankasse_payed",
            "config" =>         $prefix."config",
            "counter" =>        $prefix."counter",
            "c_ips" =>          $prefix."counter_ips",
            "c_who" =>          $prefix."counter_whoison",
            "cw" =>             $prefix."clanwars",
            "cw_comments" =>    $prefix."cw_comments",
            "cw_player" =>      $prefix."clanwar_players",
            "downloads" =>      $prefix."downloads",
            "dl_kat" =>         $prefix."download_kat",
            "events" =>         $prefix."events",
            "f_access" =>       $prefix."f_access",
            "f_abo" =>          $prefix."f_abo",
            "f_kats" =>         $prefix."forumkats",
            "f_posts" =>        $prefix."forumposts",
            "f_skats" =>        $prefix."forumsubkats",
            "f_threads" =>      $prefix."forumthreads",
            "gallery" =>        $prefix."gallery",
            "gb" =>             $prefix."gb",
            "glossar" =>        $prefix."glossar",
            "links" =>          $prefix."links",
            "linkus" =>         $prefix."linkus",
            "ipban" =>          $prefix."ipban",
            "ip2dns" =>         $prefix."iptodns",
            "msg" =>            $prefix."messages",
            "news" =>           $prefix."news",
            "navi" =>           $prefix."navi",
            "navi_kats" =>      $prefix."navi_kats",
            "newscomments" =>   $prefix."newscomments",
            "newskat" =>        $prefix."newskat",
            "partners" =>       $prefix."partners",
            "permissions" =>    $prefix."permissions",
            "pos" =>            $prefix."positions",
            "profile" =>        $prefix."profile",
            "rankings" =>       $prefix."rankings",
            "reg" =>            $prefix."reg",
            "server" =>         $prefix."server",
            "serverliste" =>    $prefix."serverliste",
            "settings" =>       $prefix."settings",
            "shout" =>          $prefix."shoutbox",
            "sites" =>          $prefix."sites",
            "squads" =>         $prefix."squads",
            "squaduser" =>      $prefix."squaduser",
            "sponsoren" =>      $prefix."sponsoren",
            "slideshow" =>      $prefix."slideshow",
            "taktik" =>         $prefix."taktiken",
            "users" =>          $prefix."users",
            "usergallery" =>    $prefix."usergallery",
            "usergb" =>         $prefix."usergb",
            "userpos" =>        $prefix."userposis",
            "userstats" =>      $prefix."userstats",
            "votes" =>          $prefix."votes",
            "vote_results" =>   $prefix."vote_results");
unset($prefix,$sql_host,$sql_user,$sql_pass,$sql_db);

if($db['host'] != '' && $db['user'] != '' && $db['pass'] != '' && $db['db'] != '' && !$thumbgen) {
    $db_host = (mysqli_persistconns ? 'p:' : '').$db['host'];
    $mysql = new mysqli($db_host,$db['user'],$db['pass'],$db['db']);
    if ($mysql->connect_error) { die("<b>Fehler beim Zugriff auf die Datenbank!"); }
}

//MySQLi-Funktionen
function _rows($rows) {
    return array_key_exists('_stmt_rows_', $rows) ? $rows['_stmt_rows_'] : $rows->num_rows;
}

function _fetch($fetch) {
    return array_key_exists('_stmt_rows_', $fetch) ? $fetch[0] : $fetch->fetch_assoc();
}

function _real_escape_string($string='') {
    global $mysql;
    return !empty($string) ? $mysql->real_escape_string($string) : '';
}

function db($query='',$rows=false,$fetch=false) {
    global $prefix,$mysql,$clanname,$updater;

    if(debug_all_sql_querys) DebugConsole::wire_log('debug', 9, 'SQL_Query', $query);
    if($updater) { $qry = $mysql->query($query); } else {
        if(!$qry = $mysql->query($query)) {
            DebugConsole::sql_error_handler($query);
            die('<b>Upps...</b><br /><br />Entschuldige bitte! Das h&auml;tte nicht passieren d&uuml;rfen. Wir k&uuml;mmern uns so schnell wie m&ouml;glich darum.<br><br>'.$clanname.'<br><br>'._back);
        }
    }

    if ($rows && !$fetch)
        return _rows($qry);
    else if($fetch && $rows)
        return $qry->fetch_array(MYSQLI_NUM);
    else if($fetch && !$rows)
        return _fetch($qry);

    return $qry;
}

/**
 *  i     corresponding variable has type integer
 *  d     corresponding variable has type double
 *  s     corresponding variable has type string
 *  b     corresponding variable is a blob and will be sent in packets
 */
function db_stmt($query,$params=array('si', 'hallo', '4'),$rows=false,$fetch=false) {
    global $prefix,$mysql;
    if(!$statement = $mysql->prepare($query)) die('<b>MySQL-Query failed:</b><br /><br /><ul>'.
                                     '<li><b>ErrorNo</b> = '.!empty($prefix) ? str_replace($prefix,'',$mysql->connect_errno) : $mysql->connect_errno.
                                     '<li><b>Error</b>   = '.!empty($prefix) ? str_replace($prefix,'',$mysql->connect_error) : $mysql->connect_error.
                                     '<li><b>Query</b>   = '.!empty($prefix) ? str_replace($prefix,'',$query).'</ul>' : $query);

    call_user_func_array(array($statement, 'bind_param'), refValues($params));
    if(!$statement->execute()) die('<b>MySQL-Query failed:</b><br /><br /><ul>'.
                                     '<li><b>ErrorNo</b> = '.!empty($prefix) ? str_replace($prefix,'',$mysql->connect_errno) : $mysql->connect_errno.
                                     '<li><b>Error</b>   = '.!empty($prefix) ? str_replace($prefix,'',$mysql->connect_error) : $mysql->connect_error.
                                     '<li><b>Query</b>   = '.!empty($prefix) ? str_replace($prefix,'',$query).'</ul>' : $query);

    $meta = mysqli_stmt_result_metadata($statement);
    if(!$meta || empty($meta)) { mysqli_stmt_close($statement); return; }
    $row = array(); $parameters = array(); $results = array();
    while ( $field = mysqli_fetch_field($meta) ) {
        $parameters[] = &$row[$field->name];
    }

    mysqli_stmt_store_result($statement);
    $results['_stmt_rows_'] = mysqli_stmt_num_rows($statement);
    call_user_func_array(array($statement, 'bind_result'), refValues($parameters));

    while ( mysqli_stmt_fetch($statement) ) {
        $x = array();
        foreach( $row as $key => $val ) {
            $x[$key] = $val;
        }

        $results[] = $x;
    }

    if ($rows && !$fetch)
        return _rows($results);
    else if($fetch && !$rows)
        return _fetch($results);

    return $results;
}

function db_optimize() {
    global $db; $sql = '';
    $blacklist = array('host','user','pass','db','prefix');
    foreach ($db as $key => $tb) {
        if(!in_array($key,$blacklist))
            $sql .= '`'.$tb.'`, ';
    }

    $sql = substr($sql, 0, -2);
    db('OPTIMIZE TABLE '.$sql.';');
}

function refValues($arr) {
    if (strnatcmp(phpversion(),'5.3') >= 0) {
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = &$arr[$key];

        return $refs;
    }

    return $arr;
}

//Auto Update Detect
if(file_exists(basePath."/_installer/index.php") &&
   file_exists(basePath."/inc/mysql.php") && !$installation && !$thumbgen) {

    $sqlqry = db('SHOW TABLE STATUS'); $table_data = array();
    while($table = _fetch($sqlqry))
    { $table_data[$table['Name']] = true; }

    if(!array_key_exists($db['ipban'],$table_data) && !$installer)
        $global_index ? header('Location: _installer/update.php') :
                        header('Location: ../_installer/update.php');
    unset($user_check);
}

function sql_backup() {
    global $mysql,$db;
    $backup_table_data = array();

    //Table Drop
    $sqlqry = db('SHOW TABLE STATUS');
    while($table = _fetch($sqlqry))
    { $backup_table_data[$table['Name']]['drop'] = 'DROP TABLE IF EXISTS `'.$table['Name'].'`;'; }
    unset($table);

    //Table Create
    foreach($backup_table_data as $table => $null) {
        unset($null);
        $sqlqry = db('SHOW CREATE TABLE '.$table.';');
        while($table = _fetch($sqlqry))
        { $backup_table_data[$table['Table']]['create'] = $table['Create Table'].';'; }
    }
    unset($table);

    //Insert Create
    foreach($backup_table_data as $table => $null) {
        unset($null); $backup = '';
        $sqlqry = db('SELECT * FROM '.$table.' ;');
        while($dt = _fetch($sqlqry)) {
            if(!empty($dt)) {
                $backup_data = '';
                foreach ($dt as $key => $var)
                { $backup_data .= "`".$key."` = '".((string)(str_replace("'", "`", $var)))."',"; }

                $backup .= "INSERT INTO `".$table."` SET ".substr($backup_data, 0, -1).";\r\n";
                unset($backup_data);
            }
        }

        $backup_table_data[$table]['insert'] = $backup;
        unset($backup);
    }
    unset($table);

    $sql_backup =  "-- -------------------------------------------------------------------\r\n";
    $sql_backup .= "-- Datenbank Backup von deV!L`z Clanportal v."._version."\r\n";
    $sql_backup .= "-- Build: "._release." * "._build."\r\n";
    $sql_backup .= "-- Host: ".$db['host']."\r\n";
    $sql_backup .= "-- Erstellt am: ".date("d.m.Y")." um ".date("H:i")."\r\n";
    $sql_backup .= "-- MySQL-Version: ".mysqli_get_server_info($mysql)."\r\n";
    $sql_backup .= "-- PHP Version: ".phpversion()."\r\n";
    $sql_backup .= "-- -------------------------------------------------------------------\r\n\r\n";
    $sql_backup .= "--\r\n-- Datenbank: `".$db['db']."`\r\n--\n\n";
    $sql_backup .= "-- -------------------------------------------------------------------\r\n";
    foreach($backup_table_data as $table => $data) {
        $sql_backup .= "\r\n--\r\n-- Tabellenstruktur: `".$table."`\r\n--\r\n\r\n";
        $sql_backup .= $data['drop']."\r\n";
        $sql_backup .= $data['create']."\r\n";

        if(!empty($data['insert'])) {
            $sql_backup .= "\r\n--\r\n-- Datenstruktur: `".$table."`\r\n--\r\n\r\n";
            $sql_backup .= $data['insert']."\r\n";
        }
    }

    unset($data);
    return $sql_backup;
}