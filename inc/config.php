<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
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
define('use_curl', true); // Verwendet die CURL PHP Erweiterung, anstelle von file_get_contents() fur externe Zugriffe, wenn vorhanden.

define('use_default_timezone', true); // Verwendende die Zeitzone vom Server
define('default_timezone', 'Europe/Berlin'); // Die zu verwendende Zeitzone selbst einstellen * 'use_default_timezone' auf false stellen *
define('admin_view_dzcp_news', true); // Entscheidet ob der DZCP Newstricker in der Administration angezeigt wird
define('show_empty_paginator', false); //Die Paginatoren sind immer sichtbar

define('thumbgen_cache', true); // Sollen die verkleinerten Bilder der Thumbgen gespeichert werden
define('thumbgen_cache_time', 60*60); // Wie lange sollen die verkleinerten Bilder der Thumbgen im Cache verbleiben

define('template_cache', true); // Sollen das HTML-Template in den Memory Cache geladen werden * nur memcache,wincache,xcache oder apc *
define('template_cache_time', 30); // Wie lange soll das HTML-Template im Memory Cache verbleiben

define('feed_update_time', 10*60); // Wann soll der Newsfeed aktualisiert werden
define('feed_enable_on_debug', false); // Soll der Newsfeed im Debugmodus generiert werden
define('file_get_contents_timeout', 10); // Nach wie viel Sekunden soll der Download externer Quellen abgebrochen werden

define('cookie_expires', (60*60*24*30*12)); // Wie Lange die Cookies des CMS ihre Gueltigkeit behalten.
define('cookie_domain', ''); // Die Domain, der das Cookie zur Verfugung steht.
define('cookie_dir', '/'); // Der Pfad auf dem Server, fur welchen das Cookie verfugbar sein wird.

define('autologin_expire', (14*24*60*60)); // Wie lange die Autologins gultig bleiben bis zum erneuten login, bis zu 14 Tage

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

define('server_show_empty_players', false); //Alle Spieler anzeigen, die deren Namen nicht angezeigt werden können, User werden sonst entfernt.

define('ts3viewer_skin', 'default'); //Verwendet TS3 Icons Sets die sich als Ordner im Verzeichniss 'inc\images\tsviewer\' befinden.
define('ts3viewer_icon_to_drive', false); //Sollen Custom Icons vom Teamspeak 3 Server dauerhaft gespeichert werden

define('captcha_case_sensitive', false); //Unterscheidet Groß und Kleinschreibung beim Captcha
define('captcha_mathematic', true); //Stellt den Usern einfache Rechenaufgaben anstelle eines Captcha Codes

define('count_clicks_expires', (24*60*60)); // Wie Lange die IPs fur den Click-Counter gespeichert bleiben.

/*
* Bitte vor der Aktivierung der Persistent Connections lesen:
* http://php.net/manual/de/features.persistent-connections.php
* * Expert *
*/
define('mysqli_persistconns', false);

/*
 * Use SMTP connection with authentication for Mailing
 */
define('phpmailer_use_smtp', false); //Use SMTP for Mailing
define('phpmailer_use_auth', true); //Use SMTP authentication
define('phpmailer_smtp_host', 'localhost'); //Hostname of the mail server
define('phpmailer_smtp_port', 25); //SMTP port number
define('phpmailer_smtp_user', ''); //Username to use for SMTP authentication
define('phpmailer_smtp_password', '');//Password to use for SMTP authentication

#########################################
//-> Sessions Settings Start * Expert *
#########################################
define('sessions_backend', 'mysql'); //Das zu verwendendes Backend: php,mysql,memcache,apc
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

/* SQL Tabelle fur externe Datenbank */ /*
 CREATE TABLE IF NOT EXISTS `[prefix]_sessions` (
         `id` int(11) NOT NULL AUTO_INCREMENT,
          `ssid` varchar(200) NOT NULL DEFAULT '',
          `time` int(11) NOT NULL DEFAULT '0',
          `data` blob,
          PRIMARY KEY (`id`),
          KEY `ssid` (`ssid`),
          KEY `time` (`time`)
        ) DEFAULT CHARSET=latin1;
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

// -> Zeichen fur den Passwort Generator
// ->                       Alphabet:                       Alphabet klein:               Zahlen:        Sonderzeichen:
$passwordComponents = array("ABCDEFGHIJKLMNOPQRSTUVWXYZ" , "abcdefghijklmnopqrstuvwxyz" , "0123456789" , "#$@!");