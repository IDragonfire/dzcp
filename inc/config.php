<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

#########################################
//-> DZCP Settings Start
#########################################

define('view_error_reporting', true); // Zeigt alle Fehler und Notices etc.
define('debug_all_sql_querys', false); // Speichert alle ausgefuehrten SQL-Querys in einer Datei
define('debug_save_to_file', false); // Schreibt die die Ausgaben der Debug Console in eine Datei
define('debug_dzcp_handler', true); // Verwende fur Notices, etc. die Debug Console
define('fsockopen_support_bypass', false); //Umgeht die fsockopen pruefung
define('use_curl', false); // Verwendet die CURL PHP Erweiterung, anstelle von file_get_contents() fur externe Zugriffe, wenn vorhanden.

define('use_default_timezone', true); // Verwendende die Zeitzone vom Server
define('default_timezone', 'Europe/Berlin'); // Die zu verwendende Zeitzone selbst einstellen * 'use_default_timezone' auf false stellen *
define('admin_view_dzcp_news', true); // Entscheidet ob der Newstricker in der Administration angezeigt wird

define('thumbgen_cache', true); // Sollen die verkleinerten Bilder der Thumbgen gespeichert werden
define('thumbgen_cache_time', 60*60); // Wie lange sollen die verkleinerten Bilder der Thumbgen im Cache verbleiben

define('template_cache', true); // Sollen das HTML-Template in den Memory Cache geladen werden * nur memcache,wincache,xcache oder apc *
define('template_cache_time', 30); // Wie lange soll das HTML-Template im Memory Cache verbleiben

define('feed_update_time', 10*60); // Wann soll der Newsfeed aktualisiert werden
define('feed_enable_on_debug', false); // Soll der Newsfeed im Debugmodus generiert werden
define('file_get_contents_timeout', 10); // Nach wie viel Sekunden soll der Downloade externe quellen abgebrochen werden

define('cookie_expires', (60*60*24*30*12)); // Wie Lange die Cookies des CMS ihre Gueltigkeit behalten.
define('cookie_domain', ''); // Die Domain, der das Cookie zur Verfugung steht.
define('cookie_dir', '/'); // Der Pfad auf dem Server, fur welchen das Cookie verfugbar sein wird.

define('autologin_expire', (14*24*60*60)); // Wie lange die Autologins gultigbleiben bis zum erneuten login, bis zu 14 Tage

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

define('ts3dns_server', true); //Sollen Teamspeak 3 DNS Server erkannt werden

define('captcha_case_sensitive', false); //Unterscheidet Groß und Kleinschreibung beim Captcha
define('captcha_mathematic', false); //Stellt den Usern einfache Rechenaufgaben anstelle eines Captcha Codes

define('count_clicks_expires', (24*60*60)); // Wie Lange die IPs fur den Click-Counter gespeichert bleiben.
/*
* Bitte vor der Aktivierung der Persistent Connections lesen:
* http://php.net/manual/de/features.persistent-connections.php
* * Expert *
*/
define('mysqli_persistconns', false);

#########################################
//-> Sessions Settings Start * Expert *
#########################################

define('sessions_backend', 'php'); //Das zu verwendendes Backend: php,mysql,memcache,apc
define('sessions_encode_type', 'sha1'); //Verwende die sha1 codierung fuer session ids
define('sessions_encode', true); //Inhalt der Sessions zusatzlich verschlusseln
define('sessions_ttl_maxtime', (2*60*60)); //Live-Time der Sessions * 2h
define('sessions_memcache_host', 'localhost'); //Server Adresse fur das Sessions Backend: memcache
define('sessions_memcache_port', 11211); //Server Port fur das Sessions Backend: memcache

define('sessions_mysql_sethost', false); //Verwende eine externe Datenbank fur die Sessions
define('sessions_mysql_host', 'localhost'); //MySQL Host
define('sessions_mysql_user', 'user'); //MySQL Username
define('sessions_mysql_pass', 'xxxx'); //MySQL Passwort
define('sessions_mysql_db', 'test'); //MySQL Database
/* SQL Tabelle */
/*
 CREATE TABLE IF NOT EXISTS `dzcp_sessions` (
         `id` int(11) NOT NULL,
         `ssid` varchar(200) NOT NULL DEFAULT '',
         `time` int(11) NOT NULL,
         `data` text) DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
ALTER TABLE `dzcp_sessions` ADD PRIMARY KEY (`id`), ADD KEY `ssid` (`ssid`), ADD KEY `time` (`time`);
*/

#########################################
//-> Cache Settings Start * Expert *
#########################################

$config_cache = array();
$config_cache['use_cache'] = true; // verwende einen Cache, um abfragen zwischenzuspeichern
$config_cache['storage'] = "files"; // welcher Cache: auto,memcache,files,sqlite,wincache,xcache oder apc
$config_cache['server'] = array(array("127.0.0.1",11211,1)); //adressen fur die memcache server
$config_cache['dbc'] = true; //verwende database query caching * nur mit memory cache
$config_cache['dbc_auto_memcache'] = false; //automatische memcache verfugbarkeisprufung

//-> Legt die UserID des Rootadmins fest
//-> (dieser darf bestimmte Dinge, den normale Admins nicht duerfen, z.B. andere Admins editieren)
$rootAdmins = array(1); // Die ID/s der User die Rootadmins sein sollen, bei mehreren mit "," trennen '1,4,2,6' usw.
#$rootAdmins = array(1,2,4,9); // etc.

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
            "autologin" =>      $prefix."autologin",
            "buddys" =>         $prefix."userbuddys",
            "ipcheck" =>        $prefix."ipcheck",
            "clicks_ips" =>     $prefix."clicks_ips",
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
            "captcha" =>        $prefix.'captcha',
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
            "sessions" =>       $prefix."sessions",
            "taktik" =>         $prefix."taktiken",
            "users" =>          $prefix."users",
            "usergallery" =>    $prefix."usergallery",
            "usergb" =>         $prefix."usergb",
            "userpos" =>        $prefix."userposis",
            "userstats" =>      $prefix."userstats",
            "votes" =>          $prefix."votes",
            "vote_results" =>   $prefix."vote_results");
unset($prefix,$sql_host,$sql_user,$sql_pass,$sql_db);

function show($tpl="", $array=array(), $array_lang_constant=array(), $array_block=array()) {
    global $tmpdir,$chkMe,$cache,$config_cache,$installation;
    if(!empty($tpl) && $tpl != null) {
        $template = basePath."/inc/_templates_/".$tmpdir."/".$tpl;

        //HTML Cache for Template Files
        if(!$installation) {
            $cacheHash = md5($template);
            if(template_cache && $config_cache['use_cache'] && dbc_index::useMem() && $cache->isExisting('tpl_'.$cacheHash)) {
                $tpl = string::decode($cache->get('tpl_'.$cacheHash));
                if(show_dbc_debug)
                    DebugConsole::insert_info('template::show()', 'Get Template-Cache: "'.'tpl_'.$cacheHash.'"');
            }
            else {
                if(file_exists($template.".html")) {
                    $tpl = file_get_contents($template.".html");

                    if(template_cache && $config_cache['use_cache'] && dbc_index::useMem()) {
                        $cache->set('tpl_'.$cacheHash,string::encode($tpl),template_cache_time);

                        if(show_dbc_debug)
                            DebugConsole::insert_loaded('template::show()', 'Set Template-Cache: "'.'tpl_'.$cacheHash.'"');
                    }
                }
            }
        }
        else {
            if(file_exists($template.".html"))
                $tpl = file_get_contents($template.".html");
        }

        //put placeholders in array
        $array['dir'] = '../inc/_templates_/'.$tmpdir;
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

require_once(basePath."/inc/database.php"); //database functions