<?php
// Funktionen
function makePrev()
{
  $arr = array(0,1,2,3,4,5,6,7,8,9);
  return $arr[rand(0,9)].$arr[rand(0,9)].$arr[rand(0,9)];
}

function up($txt,$bbcode=0)
{
  $txt = str_replace("& ","&amp; ",$txt);
  $txt = str_replace("\"","&#34;",$txt);
  $txt = trim($txt);
  if(empty($bbcode)) $txt = nl2br($txt);

  $txt = spChars($txt);

  return $txt;
}

function spChars($txt)
{
  $txt = str_replace("","&Auml;",$txt);
  $txt = str_replace("","&auml;",$txt);
  $txt = str_replace("","&Uuml;",$txt);
  $txt = str_replace("","&uuml;",$txt);
  $txt = str_replace("","&Ouml;",$txt);
  $txt = str_replace("","&ouml;",$txt);
  $txt = str_replace("","&szlig;",$txt);
  $txt = str_replace("","&euro;",$txt);

  return $txt;
}

//MySQL-Daten einlesen
$installation = false;
include(basePath.'/inc/config.php');

function install_mysql($login, $nick, $pwd, $email)
{
  global $db;
//-> Awards
  $qry = db("DROP TABLE IF EXISTS ".$db['awards']."");
  $qry = db("CREATE TABLE ".$db['awards']." (
            `id` int(5) NOT NULL auto_increment,
            `squad` int(10) NOT NULL,
            `date` varchar(20) NOT NULL default '',
            `postdate` varchar(20) NOT NULL default '',
            `event`varchar(50) NOT NULL default '',
            `place` varchar(5) NOT NULL default '',
            `prize` text NOT NULL,
            `url` text NOT NULL,
            PRIMARY KEY  (`id`)
            )");
//-> Bannliste
  $qry = db("DROP TABLE IF EXISTS ".$db['banned']."");
  $qry = db("CREATE TABLE ".$db['banned']." (
            `id` int(5) NOT NULL auto_increment,
            `server` int(5) NOT NULL,
            `date` int(20) NOT NULL default '0',
            `steamid` varchar(100) NOT NULL default '',
            `nick` varchar(30) NOT NULL default '',
            `reason` varchar(100) NOT NULL default '',
            PRIMARY KEY  (`id`)
            ) ");
//-> Clankasse
  $qry = db("DROP TABLE IF EXISTS ".$db['clankasse']."");
  $qry = db("CREATE TABLE ".$db['clankasse']." (
             `id` int(20) NOT NULL auto_increment,
             `datum` varchar(20) NOT NULL default '',
             `member` varchar(50) NOT NULL default '0',
             `transaktion` varchar(249) NOT NULL default '',
             `pm` int(1) NOT NULL default '0',
             `betrag` varchar(10) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("DROP TABLE IF EXISTS ".$db['c_payed']."");
  $qry = db("CREATE TABLE ".$db['c_payed']." (
             `id` int(5) NOT NULL auto_increment,
             `user` int(5) NOT NULL default '0',
             `payed` varchar(20) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");
//-> Clankassenkategorien
  $qry = db("DROP TABLE IF EXISTS ".$db['c_kats']."");
  $qry = db("CREATE TABLE ".$db['c_kats']." (
            `id` int(5) NOT NULL auto_increment,
            `kat` varchar(30) NOT NULL default '',
            PRIMARY KEY  (`id`)
            ) ");
  $qry = db("INSERT INTO ".$db['c_kats']." (`id`, `kat`) VALUES (1, 'Servermiete')");
  $qry = db("INSERT INTO ".$db['c_kats']." (`id`, `kat`) VALUES (2, 'Serverbeitrag')");
//-> Clanwars
  $qry = db("DROP TABLE IF EXISTS ".$db['cw']."");
  $qry = db("CREATE TABLE ".$db['cw']." (
            `id` int(5) NOT NULL auto_increment,
            `squad_id` int(19) NOT NULL,
            `gametype` varchar(249) NOT NULL default '',
            `gcountry` varchar(20) NOT NULL default 'de',
            `matchadmins` varchar(249) NOT NULL default '',
            `lineup` varchar(249) NOT NULL default '',
            `glineup` varchar(249) NOT NULL default '',
            `datum` int(20) NOT NULL default '0',
            `clantag` varchar(20) NOT NULL default '',
            `gegner` varchar(100) NOT NULL default '',
            `url` varchar(249) NOT NULL default '',
            `xonx` varchar(10) NOT NULL default '',
            `liga` varchar(30) NOT NULL default '',
            `punkte` int(5) NOT NULL default '0',
            `gpunkte` int(5) NOT NULL default '0',
            `maps` varchar(30) NOT NULL default '',
            `serverip` varchar(50) NOT NULL default '',
            `servername` varchar(249) NOT NULL default '',
            `serverpwd` varchar(20) NOT NULL default '',
            `bericht` text NOT NULL,
            PRIMARY KEY  (`id`)
            ) ");

  $qry = db("INSERT INTO ".$db['cw']." (`id`, `squad_id`, `gametype`, `gcountry`, `matchadmins`, `lineup`, `glineup`, `datum`, `clantag`, `gegner`, `url`, `xonx`, `liga`, `punkte`, `gpunkte`, `maps`, `serverip`, `servername`, `serverpwd`, `bericht`) VALUES
(1, 1, '', 'de', '', '', '', ".(time()-90000).", 'DZCP', 'deV!L`z Clanportal', 'http://www.dzcp.de', '5on5', 'DZCP', 0, 21, 'de_dzcp', '', '', '', '');");
//-> Clanwarkommentare
  $qry = db("DROP TABLE IF EXISTS ".$db['cw_comments']."");
  $qry = db("CREATE TABLE `".$db['cw_comments']."` (
             `id` int(10) NOT NULL auto_increment,
             `cw` int(10) NOT NULL default '0',
             `nick` varchar(20) NOT NULL default '',
             `datum` int(20) NOT NULL default '0',
             `email` varchar(130) NOT NULL default '',
             `hp` varchar(50) NOT NULL default '',
             `reg` int(5) NOT NULL default '0',
             `comment` text NOT NULL,
             `ip` varchar(50) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
//-> Clanwarplayers
  $qry = db("DROP TABLE IF EXISTS ".$db['cw_player']."");
  $qry = db("CREATE TABLE ".$db['cw_player']." (
            `cwid` int(5) NOT NULL default '0',
            `member` int(5) NOT NULL default '0',
            `status` int(5) NOT NULL default '0'
            ) ");
//-> Config
  $qry = db("DROP TABLE IF EXISTS ".$db['config']."");
  $qry = db("CREATE TABLE ".$db['config']." (
             `upicsize` int(5) NOT NULL default '100',
             `gallery` int(5) NOT NULL default '4',
             `m_usergb` int(5) NOT NULL default '10',
             `m_clanwars` int(5) NOT NULL default '10',
             `maxshoutarchiv` int(5) NOT NULL default '20',
             `m_clankasse` int(5) NOT NULL default '20',
             `m_awards` int(5) NOT NULL default '15',
             `m_userlist` int(5) NOT NULL default '40',
             `m_banned` int(5) NOT NULL default '40',
             `maxwidth` int(4) NOT NULL default '400',
             `shout_max_zeichen` int(5) NOT NULL default '100',
             `l_servernavi` int(5) NOT NULL default '22',
             `m_adminnews` int(5) NOT NULL default '20',
             `m_shout` int(5) NOT NULL default '10',
             `m_comments` int(5) NOT NULL default '10',
             `m_archivnews` int(5) NOT NULL default '30',
             `m_gb` int(5) NOT NULL default '10',
             `m_fthreads` int(5) NOT NULL default '20',
             `m_fposts` int(5) NOT NULL default '10',
             `m_news` int(5) NOT NULL default '5',
             `f_forum` int(5) NOT NULL default '20',
             `l_shoutnick` int(5) NOT NULL default '20',
             `f_gb` int(5) NOT NULL default '20',
             `f_membergb` int(5) NOT NULL default '20',
             `f_shout` int(5) NOT NULL default '20',
             `f_newscom` int(5) NOT NULL default '20',
             `f_cwcom` int(5) NOT NULL default '20',
             `f_artikelcom` int(5) NOT NULL default '20',
             `l_newsadmin` int(5) NOT NULL default '20',
             `l_shouttext` int(5) NOT NULL default '22',
             `l_newsarchiv` int(5) NOT NULL default '20',
             `l_forumtopic` int(5) NOT NULL default '20',
             `l_forumsubtopic` int(5) NOT NULL default '20',
             `l_clanwars` int(5) NOT NULL default '30',
             `m_gallerypics` int(5) NOT NULL default '5',
             `m_lnews` int(5) NOT NULL default '6',
             `m_topdl` int(5) NOT NULL default '5',
             `m_ftopics` int(5) NOT NULL default '6',
             `m_lwars` int(5) NOT NULL default '6',
             `m_nwars` int(5) NOT NULL default '6',
             `l_topdl` int(5) NOT NULL default '20',
             `l_ftopics` int(5) NOT NULL default '28',
             `l_lnews` int(5) NOT NULL default '22',
             `l_lwars` int(5) NOT NULL default '12',
             `l_nwars` int(5) NOT NULL default '12',
             `l_lreg` int(5) NOT NULL default '12',
             `m_lreg` int(5) NOT NULL default '5',
             `m_artikel` int(5) NOT NULL default '15',
             `m_cwcomments` int(5) NOT NULL default '10',
             `m_adminartikel` int(5) NOT NULL default '15'
             ) ");

//-> Counter
  $qry = db("DROP TABLE IF EXISTS ".$db['counter']."");
  $qry = db("CREATE TABLE ".$db['counter']." (
             `id` int(5) NOT NULL auto_increment,
             `visitors` int(20) NOT NULL default '0',
             `today` varchar(50) NOT NULL default '0',
             `maxonline` int(5) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("DROP TABLE IF EXISTS ".$db['c_ips']."");
  $qry = db("CREATE TABLE ".$db['c_ips']." (
             `id` int(10) NOT NULL auto_increment,
             `ip` varchar(30) NOT NULL default '0',
             `datum` int(20) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("DROP TABLE IF EXISTS ".$db['c_who']."");
  $qry = db("CREATE TABLE ".$db['c_who']." (
             `id` int(50) NOT NULL auto_increment,
             `ip` char(50) NOT NULL default '',
             `online` int(20) NOT NULL default '0',
             PRIMARY KEY  (`id`),
             UNIQUE KEY `ip` (`ip`)
             ) ");
//-> Downloadkategorien
  $qry = db("DROP TABLE IF EXISTS ".$db['dl_kat']."");
  $qry = db("CREATE TABLE ".$db['dl_kat']." (
             `id` int(11) NOT NULL auto_increment,
             `name` varchar(249) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['dl_kat']." (`id`, `name`) VALUES (1, 'Downloads')");
  $qry = db("INSERT INTO ".$db['dl_kat']." (`id`, `name`) VALUES (2, 'Demos')");
  $qry = db("INSERT INTO ".$db['dl_kat']." (`id`, `name`) VALUES (3, 'Stuff')");
//-> Downloads
  $qry = db("DROP TABLE IF EXISTS ".$db['downloads']."");
  $qry = db("CREATE TABLE ".$db['downloads']." (
             `id` int(11) NOT NULL auto_increment,
             `download` varchar(249) NOT NULL default '',
             `url` varchar(249) NOT NULL default '',
             `beschreibung` varchar(249) NULL,
             `hits` int(50) NOT NULL default '0',
             `kat` int(5) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['downloads']." (`id`, `download`, `url`, `beschreibung`, `hits`, `kat`) VALUES
(1, 'Testdownload', 'http://www.url.de/test.zip', '<p>Das ist ein Testdownload</p>', 0, 1);");
//-> Events (Kalender)
  $qry = db("DROP TABLE IF EXISTS ".$db['events']."");
  $qry = db("CREATE TABLE ".$db['events']." (
             `id` int(5) NOT NULL auto_increment,
             `datum` int(20) NOT NULL default '0',
             `title` varchar(30) NOT NULL default '',
             `event` text NOT NULL,
             PRIMARY KEY  (`id`)
            ) ");
  $qry = db("INSERT INTO ".$db['events']." (`id`, `datum`, `title`, `event`) VALUES (1, ".(time()+90000).", 'Testevent', '<p>Das ist nur ein Testevent! :)</p>');");
//-> Settings
  $idir = $_SERVER['PHP_SELF'];
  $idir = preg_replace("#_installer#", "", $idir);
  $pfad = dirname($idir);

  $httphost = $_SERVER['HTTP_HOST'];
  $host = str_replace('www.','',$httphost);
  $qry = db("DROP TABLE IF EXISTS ".$db['settings']."");
  $qry = db("CREATE TABLE ".$db['settings']." (
             `clanname` varchar(50) NOT NULL default 'Dein Clanname hier!',
             `pfad` varchar(50) NOT NULL default '',
             `balken_vote` varchar(3) NOT NULL default '2',
             `reg_forum` int(1) NOT NULL default '1',
             `reg_cwcomments` int(1) NOT NULL default '1',
             `counter_start` int(10) NOT NULL default '0',
             `balken_vote_menu` varchar(3) NOT NULL default '0.9',
             `balken_cw` varchar(3) NOT NULL default '2.4',
             `reg_dl` int(1) NOT NULL default '1',
             `reg_artikel` int(1) NOT NULL default '1',
             `reg_newscomments` int(1) NOT NULL default '1',
             `tmpdir` varchar(100) NOT NULL default 'version1.5',
             `wmodus` int(1) NOT NULL default '0',
             `persinfo` int(1) NOT NULL default '1',
             `iban` varchar(100) NOT NULL default '',
             `bic` varchar(100) NOT NULL default '',
             `badwords` text NOT NULL,
             `pagetitel` varchar(50) NOT NULL default 'Dein Seitentitel hier!',
             `last_backup` int(20) NOT NULL default '0',
             `squadtmpl` int(1) NOT NULL default '1',
             `i_domain` varchar(50) NOT NULL default 'www.deineUrl.de',
             `i_autor` varchar(249) NOT NULL default 'Max Mustermann',
             `k_nr` varchar(100) NOT NULL default '123456789',
             `k_inhaber` varchar(50) NOT NULL default 'Max Mustermann',
             `k_blz` varchar(100) NOT NULL default '123456789',
             `k_bank` varchar(200) NOT NULL default 'Musterbank',
             `k_waehrung` varchar(15) NOT NULL default '&euro;',
             `ftp_host` varchar(100) NOT NULL default '',
             `ftp_login` varchar(100) NOT NULL default '',
             `ftp_pwd` varchar(100) NOT NULL default '',
             `language` varchar(50) NOT NULL default 'deutsch',
             `domain` varchar(200) NOT NULL default '".$host."',
             `gametiger` varchar(20) NOT NULL default 'cstrike',
             `regcode` int(1) NOT NULL default '1',
             `ts_ip` varchar(200) NOT NULL default '',
             `mailfrom` varchar(200) NOT NULL default 'info@".$host."',
             `ts_port` int(10) NOT NULL default '0',
             `ts_sport` int(10) NOT NULL default '0',
             `ts_width` int(10) NOT NULL default '0',
             `bl_path` varchar(249) NOT NULL default ''
             ) ");

  $qry = db("INSERT INTO ".$db['settings']."
             SET `badwords` = 'arsch,Arsch,arschloch,Arschloch,hure,Hure',
                 `ts_ip` = '80.190.204.164', `ts_port` = '7000', `ts_sport` = '10011'");
//-> Forum: Access
  $qry = db("DROP TABLE IF EXISTS ".$db['f_access']."");
  $qry = db("CREATE TABLE ".$db['f_access']." (
             `user` int(10) NOT NULL default '0',
             `forum` int(10) NOT NULL default '0'
             ) ");
//-> Forum: Kategorien
  $qry = db("DROP TABLE IF EXISTS ".$db['f_kats']."");
  $qry = db("CREATE TABLE ".$db['f_kats']." (
             `id` int(10) NOT NULL auto_increment,
             `kid` int(10) NOT NULL default '0',
             `name` varchar(50) NOT NULL default '',
             `intern` int(1) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['f_kats']." (`id`, `kid`, `name`, `intern`) VALUES (1, 1, 'Hauptforum', 0)");
  $qry = db("INSERT INTO ".$db['f_kats']." (`id`, `kid`, `name`, `intern`) VALUES (2, 2, 'OFFtopic', 0)");
  $qry = db("INSERT INTO ".$db['f_kats']." (`id`, `kid`, `name`, `intern`) VALUES (3, 3, 'Clanforum', 1)");
//-> Forum Unterkategorien
  $qry = db("DROP TABLE IF EXISTS ".$db['f_skats']."");
  $qry = db("CREATE TABLE ".$db['f_skats']." (
             `id` int(10) NOT NULL auto_increment,
             `sid` int(10) NOT NULL default '0',
             `kattopic` varchar(150) NOT NULL default '',
             `subtopic` varchar(150) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['f_skats']." (`id`, `sid`, `kattopic`, `subtopic`) VALUES (1, 1, 'Allgemein', 'Allgemeines...')");
  $qry = db("INSERT INTO ".$db['f_skats']." (`id`, `sid`, `kattopic`, `subtopic`) VALUES (2, 1, 'Homepage', 'Kritiken/Anregungen/Bugs')");
  $qry = db("INSERT INTO ".$db['f_skats']." (`id`, `sid`, `kattopic`, `subtopic`) VALUES (3, 1, 'Server', 'Serverseitige Themen...')");
  $qry = db("INSERT INTO ".$db['f_skats']." (`id`, `sid`, `kattopic`, `subtopic`) VALUES (4, 1, 'Spam', 'Spamt die Bude voll ;)')");
  $qry = db("INSERT INTO ".$db['f_skats']." (`id`, `sid`, `kattopic`, `subtopic`) VALUES (5, 2, 'Sonstiges', '')");
  $qry = db("INSERT INTO ".$db['f_skats']." (`id`, `sid`, `kattopic`, `subtopic`) VALUES (6, 2, 'OFFtopic', '')");
  $qry = db("INSERT INTO ".$db['f_skats']." (`id`, `sid`, `kattopic`, `subtopic`) VALUES (7, 3, 'internes Forum', 'interne Angelegenheiten')");
  $qry = db("INSERT INTO ".$db['f_skats']." (`id`, `sid`, `kattopic`, `subtopic`) VALUES (8, 3, 'Server intern', 'interne Serverangelegenheiten')");
  $qry = db("INSERT INTO ".$db['f_skats']." (`id`, `sid`, `kattopic`, `subtopic`) VALUES (9, 3, 'War Forum', 'Alles &uuml;ber und rundum Clanwars')");
//-> Forumposts
  $qry = db("DROP TABLE IF EXISTS ".$db['f_posts']."");
  $qry = db("CREATE TABLE ".$db['f_posts']." (
             `id` int(10) NOT NULL auto_increment,
             `kid` int(2) NOT NULL default '0',
             `sid` int(2) NOT NULL default '0',
             `date` int(20) NOT NULL default '0',
             `nick` varchar(30) NOT NULL default '',
             `reg` int(1) NOT NULL default '0',
             `email` varchar(130) NOT NULL default '',
             `text` text NOT NULL,
             `edited` text NOT NULL,
             `ip` varchar(50) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
//-> Forumthreads
  $qry = db("DROP TABLE IF EXISTS ".$db['f_threads']."");
  $qry = db("CREATE TABLE ".$db['f_threads']." (
             `id` int(10) NOT NULL auto_increment,
             `kid` int(10) NOT NULL default '0',
             `t_date` int(20) NOT NULL default '0',
             `topic` varchar(249) NOT NULL default '',
             `subtopic` varchar(100) NOT NULL default '',
             `t_nick` varchar(30) NOT NULL default '',
             `t_reg` int(1) NOT NULL default '0',
             `t_email` varchar(130) NOT NULL default '',
             `t_text` text NOT NULL,
             `hits` int(10) NOT NULL default '0',
             `first` int(1) NOT NULL default '0',
             `lp` int(20) NOT NULL default '0',
             `sticky` int(1) NOT NULL default '0',
             `closed` int(1) NOT NULL default '0',
             `global` int(1) NOT NULL default '0',
             `edited` text NOT NULL,
             `ip` varchar(50) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
//-> Gaestebuch
  $qry = db("DROP TABLE IF EXISTS ".$db['gb']."");
  $qry = db("CREATE TABLE ".$db['gb']." (
             `id` int(5) NOT NULL auto_increment,
             `datum` int(20) NOT NULL default '0',
             `nick` varchar(30) NOT NULL default '',
             `email` varchar(130) NOT NULL default '',
             `hp` varchar(30) NOT NULL default '',
             `reg` int(1) NOT NULL default '0',
             `nachricht` text NOT NULL,
             `ip` varchar(50) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
//-> Galerie
  $qry = db("DROP TABLE IF EXISTS ".$db['gallery']."");
  $qry = db("CREATE TABLE ".$db['gallery']." (
             `id` int(5) NOT NULL auto_increment,
             `datum` int(20) NOT NULL default '0',
             `kat` varchar(200) NOT NULL default '',
             `beschreibung` text NULL,
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['gallery']." (`id`, `datum`, `kat`, `beschreibung`) VALUES (1, ".time().", 'Testgalerie', '<p>Das ist die erste Testgalerie.</p>\r\n<p>Hier seht ihr ein paar Bilder die eigentlich nur als Platzhalter dienen :)</p>');");

//-> ipcheck
  $qry = db("DROP TABLE IF EXISTS ".$db['ipcheck']."");
  $qry = db("CREATE TABLE ".$db['ipcheck']." (
             `id` int(11) NOT NULL auto_increment,
             `ip` varchar(100) NOT NULL default '',
             `what` varchar(40) NOT NULL default '',
             `time` int(20) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");
//-> Links
  $qry = db("DROP TABLE IF EXISTS ".$db['links']."");
  $qry = db("CREATE TABLE ".$db['links']." (
             `id` int(5) NOT NULL auto_increment,
             `url` varchar(249) NOT NULL default '',
             `text` varchar(249) NOT NULL default '',
             `banner` int(1) NOT NULL default '0',
             `beschreibung` varchar(249) NULL,
             `hits` int(50) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");

  $qry = db("INSERT INTO ".$db['links']." (`id`, `url`, `text`, `banner`, `beschreibung`, `hits`) VALUES (1, 'http://www.dzcp.de', 'http://www.dzcp.de/banner/dzcp.gif', 1, 'deV!L`z Clanportal', 0)");
  $qry = db("INSERT INTO ".$db['links']." (`id`, `url`, `text`, `banner`, `beschreibung`, `hits`) VALUES (2, 'http://www.my-starmedia.de', 'http://www.my-starmedia.de/extern/b3/b3.gif', 1, '<b>my-STARMEDIAN</b><br />my-STARMEDIA.de - DZCP Mods and Coding', 0)");

//-> LinkUs
  _s($email);
  $qry = db("DROP TABLE IF EXISTS ".$db['linkus']."");
  $qry = db("CREATE TABLE ".$db['linkus']." (
             `id` int(5) NOT NULL auto_increment,
             `url` varchar(249) NOT NULL default '',
             `text` varchar(249) NOT NULL default '',
             `banner` int(1) NOT NULL default '0',
             `beschreibung` varchar(249) NULL,
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['linkus']." (`id`, `url`, `text`, `banner`, `beschreibung`) VALUES (1, 'http://www.dzcp.de', 'http://www.dzcp.de/banner/button.gif', 1, 'deV!L`z Clanportal')");

//-> Nachrichten
  $qry = db("DROP TABLE IF EXISTS ".$db['msg']."");
  $qry = db("CREATE TABLE ".$db['msg']." (
             `id` int(5) NOT NULL auto_increment,
             `datum` int(20) NOT NULL default '0',
             `von` int(5) NOT NULL default '0',
             `an` int(5) NOT NULL default '0',
             `see_u` int(1) NOT NULL,
             `page` int(1) NOT NULL,
             `titel` varchar(80) NOT NULL default '',
             `nachricht` text NOT NULL,
             `see` int(1) NOT NULL default '0',
             `readed` int(1) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");
//-> Navigation
  $qry = db("DROP TABLE IF EXISTS ".$db['navi']."");
  $qry = db("CREATE TABLE ".$db['navi']." (
            `id` int(11) NOT NULL auto_increment,
            `pos` int(20) NOT NULL default '0',
            `kat` varchar(20) default '',
            `shown` int(1) NOT NULL default '0',
            `name` varchar(249) default '',
            `url` varchar(249) default '',
            `target` int(1) NOT NULL default '0',
            `type` int(1) NOT NULL default '0',
            `internal` int(1) NOT NULL default '0',
            `wichtig` int(1) NOT NULL default '0',
            `editor` int(10) NOT NULL default '0',
            PRIMARY KEY  (`id`)
            )");

        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (1, 1, 'nav_main', 1, '_news_', '../news/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (2, 2, 'nav_main', 1, '_newsarchiv_', '../news/?action=archiv', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (3, 3, 'nav_main', 1, '_artikel_', '../artikel/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (4, 4, 'nav_main', 1, '_forum_', '../forum/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (5, 5, 'nav_main', 1, '_gb_', '../gb/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (6, 1, 'nav_server', 1, '_server_', '../server/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (7, 6, 'nav_main', 1, '_kalender_', '../kalender/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (8, 7, 'nav_main', 1, '_votes_', '../votes/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (9, 8, 'nav_main', 1, '_links_', '../links/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (10, 9, 'nav_main', 1, '_sponsoren_', '../sponsors/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (11, 10, 'nav_main', 1, '_downloads_', '../downloads/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (12, 11, 'nav_main', 1, '_userlist_', '../user/?action=userlist', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (38, 12, 'nav_main', 1, '_glossar_', '../glossar/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (13, 1, 'nav_clan', 1, '_squads_', '../squads/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (37, 2, 'nav_clan', 1, '_membermap_', '../membermap/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (14, 3, 'nav_clan', 1, '_cw_', '../clanwars/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (15, 4, 'nav_clan', 1, '_awards_', '../awards/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (16, 5, 'nav_clan', 1, '_rankings_', '../rankings/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (17, 2, 'nav_server', 1, '_serverlist_', '../serverliste/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (18, 3, 'nav_server', 1, '_ts_', '../teamspeak/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (19, 1, 'nav_misc', 1, '_gametiger_', '../gametiger/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (20, 2, 'nav_misc', 1, '_galerie_', '../gallery/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (21, 3, 'nav_misc', 1, '_kontakt_', '../contact/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (22, 4, 'nav_misc', 1, '_joinus_', '../contact/?action=joinus', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (23, 5, 'nav_misc', 1, '_fightus_', '../contact/?action=fightus', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (24, 6, 'nav_misc', 1, '_linkus_', '../linkus/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (25, 7, 'nav_misc', 1, '_stats_', '../stats/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (26, 8, 'nav_misc', 1, '_impressum_', '../impressum/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal, wichtig) VALUES (27, 1, 'nav_admin', 1, '_admin_', '../admin/', 1, 1, 1)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (28, 1, 'nav_user', 1, '_lobby_', '../user/?action=userlobby', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (29, 2, 'nav_user', 1, '_nachrichten_', '../user/?action=msg', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (30, 3, 'nav_user', 1, '_buddys_', '../user/?action=buddys', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (31, 4, 'nav_user', 1, '_edit_profile_', '../user/?action=editprofile', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal, wichtig) VALUES (32, 5, 'nav_user', 1, '_logout_', '../user/?action=logout', 1, 0, 1)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (34, 1, 'nav_member', 1, '_clankasse_', '../clankasse/', 1, 0)");
        $qry = db("INSERT INTO ".$db['navi']." (id, pos, kat, shown, name, url, type, internal) VALUES (35, 2, 'nav_member', 1, '_taktiken_', '../taktik/', 1, 0)");
//-> Newskategorien
  $qry = db("DROP TABLE IF EXISTS ".$db['newskat']."");
  $qry = db("CREATE TABLE ".$db['newskat']." (
             `id` int(5) NOT NULL auto_increment,
             `katimg` varchar(20) NOT NULL default '',
             `kategorie` varchar(40) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['newskat']." (`id`, `katimg`, `kategorie`) VALUES (1, 'hp.jpg', 'Homepage')");
//-> News
  $qry = db("DROP TABLE IF EXISTS ".$db['news']."");
  $qry = db("CREATE TABLE ".$db['news']."(
             `id` int(10) NOT NULL auto_increment,
             `autor` varchar(5) NOT NULL default '',
             `datum` varchar(20) NOT NULL default '',
             `kat` int(2) NOT NULL default '0',
             `sticky` int(20) NOT NULL default 0,
             `titel` varchar(249) NOT NULL default '',
             `intern` int(1) NOT NULL default 0,
             `text` text NOT NULL,
             `klapplink` varchar(20) NOT NULL default '',
             `klapptext` text NOT NULL,
             `link1` varchar(100) NOT NULL default '',
             `url1` varchar(200) NOT NULL default '',
             `link2` varchar(100) NOT NULL default '',
             `url2` varchar(200) NOT NULL default '',
             `link3` varchar(100) NOT NULL default '',
             `url3` varchar(200) NOT NULL default '',
             `viewed` int(10) NOT NULL default 0,
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['news']." (`id`, `autor`, `datum`, `kat`, `titel`, `text`, `klapplink`, `klapptext`, `link1`, `url1`, `link2`, `url2`, `link3`, `url3`, `viewed`) VALUES (1, '1', ".time().", 1, 'deV!L`z Clanportal', '<p>deV!L`z Clanportal wurde erfolgreich installiert!</p><p>Bei Fragen oder Problemen kannst du gerne das Forum unter <a href=\"http://www.dzcp.de/\" target=\"_blank\">www.dzcp.de</a> kontaktieren.</p><p>Mehr Designtemplates und Modifikationen findest du unter <a href=\"http://www.templatebar.de/\" target=\"_blank\" title=\"Templates, Designs &amp; Modifikationen\">www.templatebar.de</a>.</p><p><br /></p><p>Viel Spass mit dem DZCP w&uuml;nscht dir das Team von www.dzcp.de.</p>', '', '', 'www.dzcp.de', 'http://www.dzcp.de', 'TEMPLATEbar.de', 'http://www.templatebar.de', '', '', 0)");
//-> Newskommentare
  $qry = db("DROP TABLE IF EXISTS ".$db['newscomments']."");
  $qry = db("CREATE TABLE ".$db['newscomments']." (
             `id` int(10) NOT NULL auto_increment,
             `news` int(10) NOT NULL default '0',
             `nick` varchar(20) NOT NULL default '',
             `datum` int(20) NOT NULL default '0',
             `email` varchar(130) NOT NULL default '',
             `hp` varchar(50) NOT NULL default '',
             `reg` int(5) NOT NULL default '0',
             `comment` text NOT NULL,
             `ip` varchar(50) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
//-> Artikel
  $qry = db("DROP TABLE IF EXISTS ".$db['artikel']."");
  $qry = db("CREATE TABLE ".$db['artikel']."(
             `id` int(10) NOT NULL auto_increment,
             `autor` varchar(5) NOT NULL default '',
             `datum` varchar(20) NOT NULL default '',
             `kat` int(2) NOT NULL default '0',
             `titel` varchar(249) NOT NULL default '',
             `text` text NOT NULL,
             `link1` varchar(100) NOT NULL default '',
             `url1` varchar(200) NOT NULL default '',
             `link2` varchar(100) NOT NULL default '',
             `url2` varchar(200) NOT NULL default '',
             `link3` varchar(100) NOT NULL default '',
             `url3` varchar(200) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['artikel']." (`id`, `autor`, `datum`, `kat`, `titel`, `text`, `link1`, `url1`, `link2`, `url2`, `link3`, `url3`) VALUES (1, '1', '".time()."', 1, 'Testartikel', '<p>Hier k&ouml;nnte dein Artikel stehen!</p>\r\n<p> </p>', '', '', '', '', '', '');");
//-> Artikelkommentare
  $qry = db("DROP TABLE IF EXISTS ".$db['acomments']."");
  $qry = db("CREATE TABLE ".$db['acomments']." (
             `id` int(10) NOT NULL auto_increment,
             `artikel` int(10) NOT NULL default '0',
             `nick` varchar(20) NOT NULL default '',
             `datum` int(20) NOT NULL default '0',
             `email` varchar(130) NOT NULL default '',
             `hp` varchar(50) NOT NULL default '',
             `reg` int(5) NOT NULL default '0',
             `comment` text NOT NULL,
             `ip` varchar(50) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
//-> Profilfelder
  $qry = db("DROP TABLE IF EXISTS ".$db['profile']."");
  $qry = db("CREATE TABLE ".$db['profile']." (
             `id` int(5) unsigned NOT NULL auto_increment,
             `kid` int(11) NOT NULL default '0',
             `name` varchar(20) NOT NULL default '',
             `feldname` varchar(20) NOT NULL default '',
             `type` int(5) NOT NULL default '1',
             `shown` int(5) NOT NULL default '1',
             PRIMARY KEY  (`id`)
            ) ");

  $qry = db("INSERT INTO ".$db['profile']." VALUES (2, 1, '_job_', 'job', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (3, 1, '_hobbys_', 'hobbys', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (4, 1, '_motto_', 'motto', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (5, 2, '_exclans_', 'ex', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (8, 4, '_drink_', 'drink', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (9, 4, '_essen_', 'essen', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (10, 4, '_film_', 'film', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (11, 4, '_musik_', 'musik', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (12, 4, '_song_', 'song', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (13, 4, '_buch_', 'buch', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (14, 4, '_autor_', 'autor', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (15, 4, '_person_', 'person', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (16, 4, '_sport_', 'sport', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (17, 4, '_sportler_', 'sportler', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (18, 4, '_auto_', 'auto', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (19, 4, '_game_', 'game', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (20, 4, '_favoclan_', 'favoclan', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (21, 4, '_spieler_', 'spieler', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (22, 4, '_map_', 'map', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (23, 4, '_waffe_', 'waffe', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (24, 5, '_system_', 'os', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (25, 5, '_board_', 'board', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (26, 5, '_cpu_', 'cpu', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (27, 5, '_ram_', 'ram', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (28, 5, '_graka_', 'graka', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (29, 5, '_hdd_', 'hdd', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (30, 5, '_monitor_', 'monitor', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (31, 5, '_maus_', 'maus', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (32, 5, '_mauspad_', 'mauspad', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (33, 5, '_headset_', 'headset', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (34, 5, '_inet_', 'inet', 1, 1);");
//-> Partnerbuttons
  $qry = db("DROP TABLE IF EXISTS ".$db['partners']."");
  $qry = db("CREATE TABLE ".$db['partners']." (
            `id` int(5) NOT NULL auto_increment,
            `link` varchar(100) NOT NULL default '',
            `banner` varchar(100) NOT NULL default '',
            PRIMARY KEY  (`id`)
            ) ");

  $qry = db("INSERT INTO ".$db['partners']." (`link`, `banner`) VALUES ('http://www.my-starmedia.de', 'my-starmedia.gif');");
  $qry = db("INSERT INTO ".$db['partners']." (`link`, `banner`) VALUES ('http://www.hogibo.net', 'hogibo.gif');");
  $qry = db("INSERT INTO ".$db['partners']." (`link`, `banner`) VALUES ('http://www.codeking.eu', 'codeking.gif');");
  $qry = db("INSERT INTO ".$db['partners']." (`link`, `banner`) VALUES ('http://www.dzcp.de', 'dzcp.gif');");
  $qry = db("INSERT INTO ".$db['partners']." (`link`, `banner`) VALUES ('http://spenden.dzcp.de', 'spenden.gif');");

//-> Rechte
  $qry = db("DROP TABLE IF EXISTS ".$db['permissions']."");
  $qry = db("CREATE TABLE ".$db['permissions']." (
             `id` int(5) NOT NULL auto_increment,
             `user` int(5) NOT NULL default '0',
             `intforum` int(1) NOT NULL default '0',
             `clankasse` int(1) NOT NULL default '0',
             `clanwars` int(1) NOT NULL default '0',
             `shoutbox` int(1) NOT NULL default '0',
             `serverliste` int(1) NOT NULL default '0',
             `editusers` int(1) NOT NULL default '0',
             `edittactics` int(1) NOT NULL default '0',
             `editsquads` int(1) NOT NULL default '0',
             `editserver` int(1) NOT NULL default '0',
             `editkalender` int(1) NOT NULL default '0',
             `news` int(1) NOT NULL default '0',
             `gb` int(1) NOT NULL default '0',
             `forum` int(1) NOT NULL default '0',
             `votes` int(1) NOT NULL default '0',
             `gallery` int(1) NOT NULL default '0',
             `votesadmin` int(1) NOT NULL default '0',
             `links` int(1) NOT NULL default '0',
             `downloads` int(1) NOT NULL default '0',
             `newsletter` int(1) NOT NULL default '0',
             `intnews` int(1) NOT NULL default '0',
             `rankings` int(1) NOT NULL default '0',
             `contact` int(1) NOT NULL default '0',
             `joinus` int(1) NOT NULL default '0',
             `awards` int(1) NOT NULL default '0',
             `artikel` int(1) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['permissions']." (`id`, `user`, `intforum`, `clankasse`, `clanwars`, `gallery`, `serverliste`, `editusers`, `edittactics`, `editsquads`, `editserver`, `editkalender`, `news`, `gb`, `forum`, `votes`, `votesadmin`, `links`, `downloads`, `newsletter`, `intnews`, `rankings`, `contact`, `joinus`, `awards`, `shoutbox`, `artikel`) VALUES (1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)");
//-> Positionen
  $qry = db("DROP TABLE IF EXISTS ".$db['pos']."");
  $qry = db("CREATE TABLE ".$db['pos']." (
             `id` int(2) NOT NULL auto_increment,
             `pid` int(2) NOT NULL default '0',
             `position` varchar(30) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['pos']." (`id`, `pid`, `position`) VALUES (1, 1, 'Leader')");
  $qry = db("INSERT INTO ".$db['pos']." (`id`, `pid`, `position`) VALUES (2, 2, 'Co-Leader')");
  $qry = db("INSERT INTO ".$db['pos']." (`id`, `pid`, `position`) VALUES (3, 3, 'Webmaster')");
  $qry = db("INSERT INTO ".$db['pos']." (`id`, `pid`, `position`) VALUES (4, 4, 'Member')");
//-> Rankings
  $qry = db("DROP TABLE IF EXISTS ".$db['rankings']."");
  $qry = db("CREATE TABLE ".$db['rankings']." (
             `id` INT(5) NOT NULL AUTO_INCREMENT,
             `league` VARCHAR(50) NOT NULL,
             `lastranking` INT(10) NOT NULL,
             `rank` INT(10) NOT NULL,
             `squad` VARCHAR(5) NOT NULL,
             `url` VARCHAR(249) NOT NULL,
             `postdate` INT(20) NOT NULL,
             PRIMARY KEY (`id`)
             )");
//-> Seiten
  $qry = db("DROP TABLE IF EXISTS ".$db['sites']."");
  $qry = db("CREATE TABLE ".$db['sites']." (
            `id` int(5) NOT NULL auto_increment,
            `titel` varchar(50) NOT NULL default '',
            `text` text NOT NULL,
            `html` int(1) NOT NULL,
            PRIMARY KEY  (`id`)
            ) ");
//-> Server
  $qry = db("DROP TABLE IF EXISTS ".$db['server']."");
  $qry = db("CREATE TABLE ".$db['server']." (
             `id` int(5) NOT NULL auto_increment,
             `status` varchar(100) NOT NULL default '',
             `shown` int(1) NOT NULL default 1,
             `navi` int(1) NOT NULL default '0',
             `bl_file` varchar(100) NOT NULL default '',
             `bl_path` varchar(249) NOT NULL default '',
             `ftp_pwd` varchar(100) NOT NULL default '',
             `ftp_login` varchar(100) NOT NULL default '',
             `ftp_host` varchar(100) NOT NULL default '',
             `name` varchar(50) NOT NULL default '',
             `ip` varchar(50) NOT NULL default '0',
             `port` int(10) NOT NULL default '0',
             `pwd` varchar(20) NOT NULL default '',
             `game` varchar(30) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['server']." (`id`, `navi`, `status`, `name`, `ip`, `port`, `pwd`, `game`) VALUES (1, 1, 'bf2', 'Battlefield-Basis.de II von Hogibo.net', '80.190.178.115', 9260, '', 'bf2.gif')");
//-> Serverliste
  $qry = db("DROP TABLE IF EXISTS ".$db['serverliste']."");
  $qry = db("CREATE TABLE ".$db['serverliste']." (
             `id` int(20) NOT NULL auto_increment,
             `datum` int(4) NOT NULL default '0',
             `clanname` varchar(30) NOT NULL default '',
             `clanurl` varchar(255) NOT NULL default '',
             `ip` varchar(50) NOT NULL default '',
             `port` varchar(10) NOT NULL default '',
             `pwd` varchar(10) NOT NULL default '',
             `checked` int(1) NOT NULL default '0',
             `slots` char(2) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");

  $qry = db("INSERT INTO ".$db['serverliste']." (`id`, `datum`, `clanname`, `clanurl`, `ip`, `port`, `pwd`, `checked`, `slots`) VALUES (1, ".time().", '[-tHu-] teamHanau', 'http://www.thu-clan.de', '82.98.216.10', '27015', '', 1, '17')");
//-> Shoutbox
  $qry = db("DROP TABLE IF EXISTS ".$db['shout']."");
  $qry = db("CREATE TABLE ".$db['shout']." (
             `id` int(11) NOT NULL auto_increment,
             `datum` int(30) NOT NULL default '0',
             `nick` varchar(30) NOT NULL default '',
             `email` varchar(130) NOT NULL default '',
             `text` text NOT NULL,
             `ip` varchar(50) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['shout']." (`id`, `datum`, `nick`, `email`, `text`) VALUES (1, '".time()."', 'deV!L', 'webmaster@dzcp.de', 'Viel Gl&uuml;ck und Erfolg mit eurem Clan!')");
//-> Sponsoren
  $qry = db("DROP TABLE IF EXISTS ".$db['sponsoren']."");
  $qry = db("CREATE TABLE ".$db['sponsoren']." (
             `id` int(5) NOT NULL auto_increment,
             `url` varchar(249) NOT NULL default '',
             `text` varchar(249) NOT NULL default '',
             `banner` int(1) NOT NULL default '0',
             `beschreibung` varchar(249) NULL,
             `hits` int(50) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");
//-> Squads
  $qry = db("DROP TABLE IF EXISTS ".$db['squads']."");
  $qry = db("CREATE TABLE ".$db['squads']." (
             `id` int(5) NOT NULL auto_increment,
             `name` varchar(40) NOT NULL default '',
             `game` varchar(40) NOT NULL default '',
             `icon` varchar(20) NOT NULL default '',
             `pos` int(1) NOT NULL default '0',
             `shown` int(1) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['squads']." (`id`, `name`, `game`, `icon`, `pos`, `shown`) VALUES (1, 'Testsquad', 'Counter-Strike', 'cs.gif', 1, 1)");
//-> Squadusers
  $qry = db("DROP TABLE IF EXISTS ".$db['squaduser']."");
  $qry = db("CREATE TABLE ".$db['squaduser']." (
             `id` int(5) NOT NULL auto_increment,
             `user` int(5) NOT NULL default '0',
             `squad` int(2) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['squaduser']." (`id`, `user`, `squad`) VALUES (1, 1, 1)");
//-> Taktiken
  $qry = db("DROP TABLE IF EXISTS ".$db['taktik']."");
  $qry = db("CREATE TABLE ".$db['taktik']." (
             `id` int(10) NOT NULL auto_increment,
             `datum` int(20) NOT NULL default '0',
             `map` varchar(20) NOT NULL default '',
             `spart` text NOT NULL,
             `standardt` text NOT NULL,
             `sparct` text NOT NULL,
             `standardct` text NOT NULL,
             `autor` int(5) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");
//-> Users
  $qry = db("DROP TABLE IF EXISTS ".$db['users']."");
  $qry = db("CREATE TABLE ".$db['users']." (
             `id` int(5) NOT NULL auto_increment,
             `user` varchar(200) NOT NULL default '',
             `nick` varchar(200) NOT NULL default '',
             `pwd` varchar(255) NOT NULL default '',
             `sessid` varchar(32) NULL,
             `country` varchar(20) NOT NULL default 'de',
             `ip` varchar(50) NOT NULL default '',
             `regdatum` int(20) NOT NULL default '0',
             `email` varchar(200) NOT NULL default '',
             `icq` varchar(20) NOT NULL default '',
             `hlswid` varchar(100) NOT NULL default '',
             `steamid` varchar(20) NOT NULL default '',
             `level` varchar(15) NOT NULL default '',
             `rlname` varchar(200) NOT NULL default '',
             `city` varchar(200) NOT NULL default '',
             `sex` int(1) NOT NULL default '0',
             `bday` varchar(20) NOT NULL default '',
             `hobbys` varchar(249) NOT NULL default '',
             `motto` varchar(249) NOT NULL default '',
             `hp` varchar(200) NOT NULL default '',
             `cpu` varchar(200) NOT NULL default '',
             `ram` varchar(200) NOT NULL default '',
             `monitor` varchar(200) NOT NULL default '',
             `maus` varchar(200) NOT NULL default '',
             `mauspad` varchar(200) NOT NULL default '',
             `headset` varchar(200) NOT NULL default '',
             `board` varchar(200) NOT NULL default '',
             `os` varchar(200) NOT NULL default '',
             `graka` varchar(200) NOT NULL default '',
             `hdd` varchar(200) NOT NULL default '',
             `inet` varchar(200) NOT NULL default '',
             `signatur` text NULL,
             `position` int(2) NOT NULL default '0',
             `status` int(1) NOT NULL default '1',
             `ex` varchar(200) NOT NULL default '',
             `job` varchar(200) NOT NULL default '',
             `time` int(20) NOT NULL default '0',
             `listck` int(1) NOT NULL default '0',
             `online` int(1) NOT NULL default '0',
             `nletter` int(1) NOT NULL default '1',
             `whereami` varchar(20) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("ALTER TABLE ".$db['users']."	ADD `drink` varchar(249) NOT NULL default '',
 									   		                  ADD `essen` varchar(249) NOT NULL default '',
									   		                  ADD `film` varchar(249) NOT NULL default '',
									   		                  ADD `musik` varchar(249) NOT NULL default '',
									   		                  ADD `song` varchar(249) NOT NULL default '',
									   		                  ADD `buch` varchar(249) NOT NULL default '',
											                    ADD `autor` varchar(249) NOT NULL default '',
											                    ADD `person` varchar(249) NOT NULL default '',
											                    ADD `sport` varchar(249) NOT NULL default '',
											                    ADD `sportler` varchar(249) NOT NULL default '',
											                    ADD `auto` varchar(249) NOT NULL default '',
											                    ADD `game` varchar(249) NOT NULL default '',
											                    ADD `favoclan` varchar(249) NOT NULL default '',
											                    ADD `spieler` varchar(249) NOT NULL default '',
											                    ADD `map` varchar(249) NOT NULL default '',
											                    ADD `waffe` varchar(249) NOT NULL default '',
											                    ADD `rasse` varchar(249) NOT NULL default '',
											                    ADD `url2` varchar(249) NOT NULL default '',
											                    ADD `url3` varchar(249) NOT NULL default '',
											                    ADD `beschreibung` text NULL");
  $qry = db("INSERT INTO ".$db['users']." (`id`, `user`, `nick`, `pwd`, `regdatum`, `email`, `level`, `position`, `status`, `online`, `ip`, `sessid`) VALUES (1, '".$login."', '".$nick."', '".md5($pwd)."', '".time()."', '".$email."', '4', 1, 1, 1, '".visitorIp()."', '".session_id()."')");
//-> Userposis
  $qry = db("DROP TABLE IF EXISTS ".$db['userpos']."");
  $qry = db("CREATE TABLE ".$db['userpos']." (
             `id` int(11) NOT NULL auto_increment,
             `user` int(5) NOT NULL default '0',
             `posi` int(5) NOT NULL default '0',
             `squad` int(5) NOT NULL default '0',
             PRIMARY KEY  (`id`)
            ) ");
//-> Buddys
  $qry = db("DROP TABLE IF EXISTS ".$db['buddys']."");
  $qry = db("CREATE TABLE ".$db['buddys']." (
             `id` int(10) NOT NULL auto_increment,
             `user` int(5) NOT NULL default '0',
             `buddy` int(5) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");
//-> Usergallery
  $qry = db("DROP TABLE IF EXISTS ".$db['usergallery']."");
  $qry = db("CREATE TABLE ".$db['usergallery']." (
             `id` int(5) NOT NULL auto_increment,
             `user` int(5) NOT NULL default '0',
             `beschreibung` text NULL,
             `pic` varchar(200) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
//-> UserGB
  $qry = db("DROP TABLE IF EXISTS ".$db['usergb']."");
  $qry = db("CREATE TABLE ".$db['usergb']." (
             `id` int(5) NOT NULL auto_increment,
             `user` int(5) NOT NULL default '0',
             `datum` int(20) NOT NULL default '0',
             `nick` varchar(30) NOT NULL default '',
             `email` varchar(130) NOT NULL default '',
             `hp` varchar(100) NOT NULL default '',
             `reg` int(1) NOT NULL default '0',
             `nachricht` text NOT NULL,
             `ip` varchar(50) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
//-> Userstats
  $qry = db("DROP TABLE IF EXISTS ".$db['userstats']."");
  $qry = db("CREATE TABLE ".$db['userstats']." (
             `id` int(5) NOT NULL auto_increment,
             `user` int(10) NOT NULL default '0',
             `logins` int(100) NOT NULL default '0',
             `writtenmsg` int(10) NOT NULL default '0',
             `lastvisit` int(20) NOT NULL default '0',
             `hits` int(249) NOT NULL default '0',
             `votes` int(5) NOT NULL default '0',
             `profilhits` int(20) NOT NULL default '0',
             `forumposts` int(5) NOT NULL default '0',
             `cws` int(5) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['userstats']." (`id`, `user`, `hits`) VALUES (1, 1, 1)");
//-> Votes
  $qry = db("DROP TABLE IF EXISTS ".$db['votes']."");
  $qry = db("CREATE TABLE ".$db['votes']." (
             `id` int(5) NOT NULL auto_increment,
             `datum` int(20) NOT NULL default '0',
             `titel` varchar(249) NOT NULL default '',
             `intern` int(1) NOT NULL default '0',
             `menu` int(1) NOT NULL default '0',
             `closed` int(1) NOT NULL default '0',
             `von` int(10) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['votes']." (`id`, `datum`, `titel`, `intern`, `menu`, `von`) VALUES (1, '".time()."', 'Wie findet ihr unsere Seite?', 0, 1, 1)");
  $qry = db("DROP TABLE IF EXISTS ".$db['vote_results']."");
  $qry = db("CREATE TABLE ".$db['vote_results']." (
             `id` int(5) NOT NULL auto_increment,
             `vid` int(5) NOT NULL default '0',
             `what` varchar(5) NOT NULL default '',
             `sel` varchar(80) NOT NULL default '',
             `stimmen` int(5) NOT NULL default '0',
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['vote_results']." (`id`, `vid`, `what`, `sel`, `stimmen`) VALUES (1, 1, 'a1', 'Gut', 0)");
  $qry = db("INSERT INTO ".$db['vote_results']." (`id`, `vid`, `what`, `sel`, `stimmen`) VALUES (2, 1, 'a2', 'Schlecht', 0)");
//-> Indizes
  $qry = db("ALTER TABLE ".$db['f_posts']." ADD INDEX (`sid`),
                                            ADD INDEX (`date`)");
  $qry = db("ALTER TABLE ".$db['f_threads']." ADD INDEX (`kid`),
                                              ADD INDEX (`lp`),
                                              ADD INDEX (`topic`),
                                              ADD INDEX (`first`)");
//-> Sessionvariabeln setzen
  $_SESSION['id'] = "1";
  $_SESSION['pwd'] = md5($pwd);
  $_SESSION['ip'] = visitorIp();

  if($login || $nick || $pwd || $email)
  {
    $_SESSION['id'] = "1";
    $_SESSION['pwd'] = md5($pwd);
    $_SESSION['ip'] = visitorIp();
  }
}

function update_mysql()
{
  global $db;

  $qry = db("CREATE TABLE ".$db['profile']." (
             `id` int(5) unsigned NOT NULL auto_increment,
             `kid` int(11) NOT NULL default '0',
             `name` varchar(20) NOT NULL default '',
             `feldname` varchar(20) NOT NULL default '',
             `type` int(5) NOT NULL default '1',
             `shown` int(5) NOT NULL default '1',
             PRIMARY KEY  (`id`)
            ) ");

  $qry = db("INSERT INTO ".$db['profile']." VALUES (1, 1, '_city_', 'city', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (2, 1, '_job_', 'job', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (3, 1, '_hobbys_', 'hobbys', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (4, 1, '_motto_', 'motto', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (5, 2, '_exclans_', 'ex', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (8, 4, '_drink_', 'drink', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (9, 4, '_essen_', 'essen', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (10, 4, '_film_', 'film', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (11, 4, '_musik_', 'musik', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (12, 4, '_song_', 'song', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (13, 4, '_buch_', 'buch', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (14, 4, '_autor_', 'autor', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (15, 4, '_person_', 'person', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (16, 4, '_sport_', 'sport', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (17, 4, '_sportler_', 'sportler', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (18, 4, '_auto_', 'auto', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (19, 4, '_game_', 'game', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (20, 4, '_favoclan_', 'favoclan', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (21, 4, '_spieler_', 'spieler', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (22, 4, '_map_', 'map', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (23, 4, '_waffe_', 'waffe', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (24, 5, '_system_', 'os', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (25, 5, '_board_', 'board', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (26, 5, '_cpu_', 'cpu', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (27, 5, '_ram_', 'ram', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (28, 5, '_graka_', 'graka', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (29, 5, '_hdd_', 'hdd', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (30, 5, '_monitor_', 'monitor', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (31, 5, '_maus_', 'maus', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (32, 5, '_mauspad_', 'mauspad', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (33, 5, '_headset_', 'headset', 1, 1);");
  $qry = db("INSERT INTO ".$db['profile']." VALUES (34, 5, '_inet_', 'inet', 1, 1);");
//Config
  $qry = db("ALTER TABLE ".$db['config']." ADD `m_artikel` int(5) NOT NULL default '20',
                                           ADD `m_adminartikel` int(5) NOT NULL default '20',
                                           ADD `m_cwcomments` int(5) NOT NULL default '20',
                                           ADD `l_shoutnick` int(5) NOT NULL default '20',
                                           ADD `f_artikelcom` int(5) NOT NULL default '20',
                                           ADD `f_cwcom` int(5) NOT NULL default '20',
                                           ADD `m_lreg` int(5) NOT NULL default '5',
                                           ADD `l_lreg` int(5) NOT NULL default '14'");
  $qry = db("UPDATE ".$db['config']."
             SET `maxwidth` = 90,
                 `m_cwcomments` = 10,
                 `m_artikel` = 20,
                 `m_cwcomments` = 20");
//Settings
  $httphost = $_SERVER['HTTP_HOST'];
  $host = str_replace('www.','',$httphost);
  $qry = db("ALTER TABLE ".$db['settings']." ADD `reg_artikel` int(3) NOT NULL default '0',
                                             ADD `reg_cwcomments` int(3) NOT NULL default '0',
                                             ADD `mailfrom` varchar(200) NOT NULL default 'support@".$host."',
                                             ADD `badwords` text NOT NULL,
                                             ADD `squadtmpl` int(1) NOT NULL default '1',
                                             ADD `persinfo` int(1) NOT NULL default '1',
                                             CHANGE `domain` `domain` varchar(200) NOT NULL default '".$host."',
                                             CHANGE `k_bank` `k_bank` varchar(200)");
  $qry = db("INSERT INTO ".$db['settings']."
             SET `badwords` = 'arsch,Arsch,arschloch,Arschloch,hure,Hure'");
//-> Forum: Access
  $qry = db("CREATE TABLE ".$db['f_access']." (
             `user` int(10) NOT NULL default '0',
             `forum` int(10) NOT NULL default '0'
             ) ");
//Permissions
  $qry = db("ALTER TABLE ".$db['permissions']." ADD `artikel` int(1) NOT NULL default '0'");

  $upd = db("UPDATE ".$db['permissions']."
             SET `artikel` = '1'
             WHERE id = '1'");

  $u = db("SELECT email FROM ".$db['users']." WHERE id = '1'");
  $get=mysql_fetch_array($u);
  _s($get['email']);
// Forum Threads
  $qry = db("ALTER TABLE ".$db['f_threads']." ADD `global` int(1) NOT NULL default '0',
                                              ADD `ip` varchar(50) NOT NULL default '0'");
// Forum Posts
  $qry = db("ALTER TABLE ".$db['f_posts']." ADD `ip` varchar(50) NOT NULL default '0'");
//GB
  $qry = db("ALTER TABLE ".$db['gb']." ADD `ip` varchar(50) NOT NULL default '0'");
//Newscomments
  $qry = db("ALTER TABLE ".$db['newscomments']." ADD `ip` varchar(50) NOT NULL default '0'");
//Shoutbox
  $qry = db("ALTER TABLE ".$db['shout']." ADD `ip` varchar(50) NOT NULL default '0'");
//User
  $qry = db("ALTER TABLE ".$db['users']." CHANGE `signatur` `signatur` text NULL,
                                          ADD `nletter` int(1) NOT NULL default '1'");
  $upd = db("UPDATE ".$db['users']."
             SET signatur = ''");
//UserGB
  $qry = db("ALTER TABLE ".$db['usergb']." ADD `ip` varchar(50) NOT NULL default '0'");
//Rankings
  $qry = db("ALTER TABLE ".$db['rankings']." ADD `lastranking` int(10) NOT NULL");
// Usergallery
  $qry = db("ALTER TABLE ".$db['usergallery']." CHANGE `pic` `pic` varchar(200) NOT NULL default ''");
//-> Artikel
  $qry = db("CREATE TABLE ".$db['artikel']."(
             `id` int(10) NOT NULL auto_increment,
             `autor` varchar(5) NOT NULL default '',
             `datum` varchar(20) NOT NULL default '',
             `kat` int(2) NOT NULL default '0',
             `titel` varchar(249) NOT NULL default '',
             `text` text NOT NULL,
             `link1` varchar(100) NOT NULL default '',
             `url1` varchar(200) NOT NULL default '',
             `link2` varchar(100) NOT NULL default '',
             `url2` varchar(200) NOT NULL default '',
             `link3` varchar(100) NOT NULL default '',
             `url3` varchar(200) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
//-> Artikelkommentare
  $qry = db("CREATE TABLE ".$db['acomments']." (
             `id` int(10) NOT NULL auto_increment,
             `artikel` int(10) NOT NULL default '0',
             `nick` varchar(20) NOT NULL default '',
             `datum` int(20) NOT NULL default '0',
             `email` varchar(130) NOT NULL default '',
             `hp` varchar(50) NOT NULL default '',
             `reg` int(5) NOT NULL default '0',
             `comment` text NOT NULL,
             `ip` varchar(50) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
//-> Galerie
  $qry = db("CREATE TABLE ".$db['gallery']." (
             `id` int(5) NOT NULL auto_increment,
             `datum` int(20) NOT NULL default '0',
             `kat` varchar(200) NOT NULL default '',
             `beschreibung` text NULL,
             PRIMARY KEY  (`id`)
             ) ");
//-> Clanwarkommentare
  $qry = db("CREATE TABLE `".$db['cw_comments']."` (
             `id` int(10) NOT NULL auto_increment,
             `cw` int(10) NOT NULL default '0',
             `nick` varchar(20) NOT NULL default '',
             `datum` int(20) NOT NULL default '0',
             `email` varchar(130) NOT NULL default '',
             `hp` varchar(50) NOT NULL default '',
             `reg` int(5) NOT NULL default '0',
             `comment` text NOT NULL,
             `ip` varchar(50) NOT NULL default '',
             PRIMARY KEY  (`id`)
             ) ");
//-> LinkUs
  $qry = db("CREATE TABLE ".$db['linkus']." (
             `id` int(5) NOT NULL auto_increment,
             `url` varchar(249) NOT NULL default '',
             `text` varchar(249) NOT NULL default '',
             `banner` int(1) NOT NULL default '0',
             `beschreibung` varchar(249) NULL,
             PRIMARY KEY  (`id`)
             ) ");
  $qry = db("INSERT INTO ".$db['linkus']." (`id`, `url`, `text`, `banner`, `beschreibung`) VALUES (1, 'http://www.dzcp.de', 'http://www.dzcp.de/banner/button.gif', 1, 'deV!L`z Clanportal')");
}

function update_mysql_1_3()
{
  global $db;

  $qry = db("ALTER TABLE ".$db['settings']." ADD `iban` varchar(100) NOT NULL default '',
                                             ADD `bic` varchar(100) NOT NULL default ''");
  $qry = db("ALTER TABLE ".$db['settings']." CHANGE `language` `language` varchar(100) NOT NULL default 'deutsch'");
  $qry = db("UPDATE ".$db['settings']." SET `language` = 'deutsch'");
  $qry = db("ALTER TABLE ".$db['shout']." CHANGE `text` `text` text NOT NULL");
  $qry = db("ALTER TABLE ".$db['votes']." ADD `closed` int(1) NOT NULL");
  $qry = db("ALTER TABLE ".$db['users']." ADD `listck` int(1) NOT NULL");

}
function update_mysql_1_4()
{
  global $db;

  $qry = db("ALTER TABLE ".$db['config']." ADD `id` int(1) NOT NULL default '1' FIRST,
                                           ADD `securelogin` int(1) NOT NULL default '0',
                                           ADD `allowhover` int(1) NOT NULL default '1',
                                           ADD `teamrow` int(1) NOT NULL default '3',
                                           ADD `l_lartikel` int(1) NOT NULL default '18',
                                           ADD `m_lartikel` int(1) NOT NULL default '5',
                                           ADD `l_team` int(5) NOT NULL default '7'");

  $qry = db("ALTER TABLE ".$db['c_who']." ADD `whereami` text NOT NULL,
                                          ADD `login` int(1) NOT NULL default '0'");
  $qry = db("ALTER TABLE ".$db['users']." CHANGE `whereami` `whereami` text NOT NULL,
                                          ADD `gmaps_koord` varchar(249) NOT NULL");
  $qry = db("ALTER TABLE ".$db['gb']." ADD `editby` text NOT NULL");
  $qry = db("ALTER TABLE ".$db['acomments']." ADD `editby` text NOT NULL");
  $qry = db("ALTER TABLE ".$db['newscomments']." ADD `editby` text NOT NULL");

  $qry = db("ALTER TABLE ".$db['f_posts']." ADD `hp` varchar(249) NOT NULL default ''");
  $qry = db("ALTER TABLE ".$db['downloads']." ADD `date` int(20) NOT NULL default '0'");
  $qry = db("UPDATE ".$db['downloads']." SET `date` = '".time()."'");
  $qry = db("ALTER TABLE ".$db['f_threads']." ADD `t_hp` varchar(249) NOT NULL default ''");

  $qry = db("ALTER TABLE ".$db['usergb']." ADD `editby` text NOT NULL");
  $qry = db("ALTER TABLE ".$db['cw_comments']." ADD `editby` text NOT NULL");

  $qry = db("ALTER TABLE ".$db['squads']." ADD `navi` int(1) NOT NULL default '1',
                                           ADD `status` int(1) NOT NULL default '1'");

  $qry = db("ALTER TABLE ".$db['server']." ADD `qport` varchar(10) NOT NULL default ''");
  $qry = db("ALTER TABLE ".$db['profile']." CHANGE `name` `name` varchar(200) NOT NULL");
  $qry = db("ALTER TABLE ".$db['links']." CHANGE `beschreibung` `beschreibung` text NULL");
  $qry = db("ALTER TABLE ".$db['sponsoren']." CHANGE `beschreibung` `beschreibung` text NULL");

  $qry = db("ALTER TABLE ".$db['settings']." ADD `id` int(1) NOT NULL default '1' FIRST,
                                             ADD `eml_reg_subj` varchar(200) NOT NULL default '',
                                             ADD `eml_pwd_subj` varchar(200) NOT NULL default '',
                                             ADD `eml_nletter_subj` varchar(200) NOT NULL default '',
                                             ADD `eml_reg` text NOT NULL,
                                             ADD `eml_pwd` text NOT NULL,
                                             ADD `eml_nletter` text NOT NULL,
                                             ADD `reg_shout` int(1) NOT NULL default '1',
                                             ADD `gmaps_key` varchar(200) NOT NULL,
                                             ADD `gmaps_who` int(1) NOT NULL default '1',
                                             ADD `prev` int(3) NOT NULL default '0'");

  $qry = db("DROP TABLE IF EXISTS ".$db['reg']."");

  $qry = db("ALTER TABLE ".$db['permissions']." ADD `receivecws` int(1) NOT NULL default '0',
                                                ADD `editor` int(1) NOT NULL default '0',
                                                ADD `glossar` int(1) NOT NULL default '0'");
  $qry = db("DROP TABLE IF EXISTS ".$db['glossar']."");
  $qry = db("CREATE TABLE ".$db['glossar']." (
              `id` int(11) NOT NULL auto_increment,
              `word` varchar(200) NOT NULL,
              `glossar` text NOT NULL,
              PRIMARY KEY (`id`)
            ) ;");
  $qry = db("INSERT INTO ".$db['glossar']." (`id`, `word`, `glossar`) VALUES (1, 'DZCP', '<p>deV!L`z Clanportal - kurz DZCP - ist ein CMS-System speziell f&uuml;r Onlinegaming Clans.</p>\r\n<p>Viele schon in der Grundinstallation vorhandene Module erleichtern die Verwaltung einer Clan-Homepage ungemein.</p>');");

//UPDATE DB
$eml_reg =
'Du hast dich erfolgreich auf unserer Seite registriert!
Deine Logindaten lauten:

##########
Loginname: [user]
Passwort: [pwd]
##########

[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]';

$eml_pwd =
'Ein neues Passwort wurde f&uuml;r deinen Account generiert!

#########
Login-Name: [user]
Passwort: [pwd]
#########

[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]';

$eml_nletter =
'[text]


[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]';

  $qry = db("UPDATE ".$db['settings']."
             SET `prev` = '".((int)makePrev())."',
                 `eml_reg_subj`  = 'Deine Registrierung',
                 `eml_pwd_subj` = 'Deine Zugangsdaten',
                 `eml_nletter_subj` = 'Newsletter',
                 `eml_reg` = '".$eml_reg."',
                 `eml_pwd` = '".$eml_pwd."',
                 `eml_nletter` = '".$eml_nletter."'
             WHERE id = 1");

  $qry = db("UPDATE ".$db['config']."
             SET `securelogin` = '0',
                 `allowhover` = '1',
                 `maxwidth` = '500'
             WHERE id = 1");

  $qry = db("UPDATE ".$db['permissions']."
             SET `receivecws` = '1',
                 `editor` = '1',
                 `glossar` = '1'
             WHERE user = 1");

  $qry = db("DELETE FROM ".$db['profile']."
             WHERE feldname = 'city'");
}
function update_mysql_1_5()
{
  global $db;

  db("ALTER TABLE ".$db['f_threads']." ADD `vote` varchar(10) NOT NULL default '0'");
  db("ALTER TABLE ".$db['votes']." ADD `forum` int(1) NOT NULL default '0'");
  db("ALTER TABLE ".$db['settings']." ADD `eml_fabo_npost_subj` varchar(200) NOT NULL");
  db("ALTER TABLE ".$db['settings']." ADD `eml_fabo_tedit_subj` varchar(200) NOT NULL");
  db("ALTER TABLE ".$db['settings']." ADD `eml_fabo_pedit_subj` varchar(200) NOT NULL");
  db("ALTER TABLE ".$db['settings']." ADD `eml_pn_subj` varchar(200) NOT NULL");
  db("ALTER TABLE ".$db['settings']." ADD `eml_fabo_npost` text NOT NULL");
  db("ALTER TABLE ".$db['settings']." ADD `eml_fabo_tedit` text NOT NULL");
  db("ALTER TABLE ".$db['settings']." ADD `eml_fabo_pedit` text NOT NULL");
  db("ALTER TABLE ".$db['settings']." ADD `eml_pn` text NOT NULL");
  db("ALTER TABLE ".$db['settings']." ADD `k_vwz` varchar(200) NOT NULL");

  db("DROP TABLE IF EXISTS ".$db['f_abo']."");
  db("CREATE TABLE ".$db['f_abo']." (
        `id` int(10) NOT NULL auto_increment,
        `fid` int(10) NOT NULL,
        `datum` int(20) NOT NULL,
        `user` int(5) NOT NULL,
        PRIMARY KEY  (`id`)
      ) ");

  $eml_fabo_npost_subj = 'Neuer Beitrag auf abonniertes Thema im [titel]';
  $eml_fabo_tedit_subj = 'Thread auf abonniertes Thema im [titel] wurde editiert';
  $eml_fabo_pedit_subj = 'Beitrag auf abonniertes Thema im [titel] wurde editiert';
  $eml_fabo_npost = "Hallo [nick],\r\n\r\n[postuser] hat auf das Thema: [topic] auf der Website: \"[titel]\" geantwortet.\r\n\r\nDen neuen Beitrag erreichst Du ber folgenden Link:\r\n<a href=\"http://[domain]/forum/?action=showthread&id=[id]&page=[page]#p[entrys]\">http://[domain]/forum/?action=showthread&id=[id]&page=[page]#p[entrys]</a>\r\n\r\n[postuser] hat folgenden Text geschrieben:\r\n---------------------------------\r\n[text]\r\n---------------------------------\r\n\r\nViele Gre,\r\n\r\nDein [clan]\r\n\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]";
  $eml_fabo_tedit = "Hallo [nick],\r\n		 \r\nDer Thread mit dem Titel: [topic] auf der Website: \"[titel]\" wurde soeben von [postuser] editiert.\r\n\r\nDen editierten Beitrag erreichst Du ber folgenden Link:\r\n<a href=\"http://[domain]/forum/?action=showthread&id=[id]\">http://[domain]/forum/?action=showthread&id=[id]</a>\r\n		 \r\n[postuser] hat folgenden neuen Text geschrieben:\r\n---------------------------------\r\n[text]\r\n---------------------------------\r\n		 \r\nViele Gre,\r\n\r\nDein [clan]\r\n\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]";
  $eml_fabo_pedit = "Hallo [nick],\r\n\r\nEin Beitrag im Thread mit dem Titel: [topic] auf der Website: \"[titel]\" wurde soeben von [postuser] editiert.\r\n\r\nDen editierten Beitrag erreichst Du ber folgenden Link:\r\n<a href=\"http://[domain]/forum/?action=showthread&id=[id]&page=[page]#p[entrys]\">http://[domain]/forum/?action=showthread&id=[id]&page=[page]#p[entrys]</a>\r\n\r\n[postuser] hat folgenden neuen Text geschrieben:\r\n---------------------------------\r\n[text]\r\n---------------------------------\r\n\r\nViele Gre,\r\n\r\nDein [clan]\r\n\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]";
  $eml_pn_subj = "Neue PN auf [domain]";
  $eml_pn = "---------------------------------\r\n\r\nHallo [nick],\r\n\r\nDu hast eine neue Nachricht in deinem Postfach.\r\n\r\nTitel: [titel]\r\n\r\n<a href=\"http://[domain]/user/index.php?action=msg\">Zum Nachrichten-Center</a>\r\n\r\nVG\r\n\r\n[clan]\r\n\r\n---------------------------------";

  db("UPDATE ".$db['settings']."
  	  SET `eml_fabo_npost_subj` = '".up($eml_fabo_npost_subj)."',
  		  `eml_fabo_tedit_subj`   = '".up($eml_fabo_tedit_subj)."',
  		  `eml_fabo_pedit_subj`   = '".up($eml_fabo_pedit_subj)."',
  		  `eml_pn_subj`   		    = '".up($eml_pn_subj)."',
  		  `eml_fabo_npost`        = '".up($eml_fabo_npost)."',
  		  `eml_fabo_tedit`        = '".up($eml_fabo_tedit)."',
  		  `eml_fabo_pedit`        = '".up($eml_fabo_pedit)."',
  		  `eml_pn`		            = '".up($eml_pn)."'
  	  WHERE `id` = '1'");

  db("ALTER TABLE ".$db['users']." ADD `pnmail` int(1) NOT NULL default '1'");
  db("ALTER TABLE ".$db['msg']." ADD `sendmail` int(1) default '0'");
	db("UPDATE ".$db['msg']." SET `sendmail` = '1'");
  db("ALTER TABLE ".$db['gb']." ADD `public` int(1) NOT NULL");
  db("UPDATE ".$db['gb']." SET `public` = '1'");
  db("ALTER TABLE ".$db['pos']." ADD `nletter` int(1) NOT NULL");
  db("ALTER TABLE ".$db['msg']." ADD `sendnews` int(1) default '0' NOT NULL");
  db("ALTER TABLE ".$db['msg']." ADD `senduser` int(5) default '0' NOT NULL");
  db("ALTER TABLE ".$db['msg']." ADD `sendnewsuser` int(5) default '0' NOT NULL");

  db("INSERT INTO ".$db['navi']." SET `pos`   = '0', `kat`   = 'nav_main', `shown` = '1', `name`  = '_news_send_', `url`   = '../news/send.php', `target`   = '0',
		  `type`     = '1', `internal` = '0', `wichtig` = '0', `editor` = '0'");

  db("ALTER TABLE ".$db['config']." ADD `m_events` int(5) default '5' NOT NULL");
  db("INSERT INTO ".$db['config']." SET `m_events` = '5'");
  db("ALTER TABLE ".$db['artikel']." ADD `public` int(1) default '0' NOT NULL");
  db("UPDATE ".$db['artikel']." SET `public` = '1'");
  db("ALTER TABLE ".$db['news']." ADD `public` int(1) default '0' NOT NULL");
  db("UPDATE ".$db['news']." SET `public` = '1'");
  db("ALTER TABLE ".$db['config']." ADD `m_away` int(5) default '10' NOT NULL");
  db("INSERT INTO ".$db['config']." SET `m_away` = '10'");

  db("DROP TABLE IF EXISTS ".$db['away']);
  db("CREATE TABLE ".$db['away']." (
      	 `id` int(5) NOT NULL auto_increment,
		 `userid` INT(14) not null  default '0',
		 `titel` varchar(30) not null,
		 `reason` longtext not null,
		 `start` int(20) not null  default '0',
		 `end` int(20) not null  default '0',
		 `date` text not null,
		 `lastedit` text not null,
		  PRIMARY KEY (`id`)
		  ) ;");

  db("INSERT INTO ".$db['navi']." SET `pos` = '1', `kat` = 'nav_trial', `shown` = '1', `name` = '_awaycal_', `url` = '../away/', `type` = '2', `internal` = '1'");

  db("DROP TABLE IF EXISTS ".$db['sponsoren']);
  db("CREATE TABLE ".$db['sponsoren']." (
          `id` int(5) NOT NULL auto_increment,
		  `name` varchar(249) NOT NULL,
		  `link` varchar(249) NOT NULL,
		  `beschreibung` text NOT NULL,
		  `site` int(1) NOT NULL default '0',
		  `send` varchar(5) NOT NULL,
		  `slink` varchar(249) NOT NULL,
		  `banner` int(1) NOT NULL default '0',
		  `bend` varchar(5) NOT NULL,
		  `blink` varchar(249) NOT NULL,
		  `box` int(1) NOT NULL default '0',
		  `xend` varchar(5) NOT NULL,
		  `xlink` varchar(255) NOT NULL,
		  `pos` int(5) NOT NULL,
		  `hits` int(50) NOT NULL default '0',
		  PRIMARY KEY  (`id`)
		) ;");

		db("INSERT INTO ".$db['sponsoren']." (`id`, `name`, `link`, `beschreibung`, `site`, `send`, `slink`, `banner`, `bend`, `blink`, `box`, `xend`, `xlink`, `pos`, `hits`)
VALUES
(1, 'DZCP', 'http://www.dzcp.de', '<p>deV!L\'z Clanportal, das CMS for Online-Clans!</p>', 0, '', '', 0, '', '', 1, 'gif', '', 7, 0),
(2, 'DZCP Rotationsbanner', 'http://www.dzcp.de', '<p>deV!L`z Clanportal</p>', 0, '', '', 1, '', 'http://www.dzcp.de/banner/dzcp.gif', 0, '', '', 5, 0),
(3, 'TEMPLATEbar', 'http://www.templatebar.de', '<p>Auf TEMPLATEbar.de kannst du dir kosteng&uuml;nstige Clandesigns und/oder Templates von Top Designer erwerben.</p>', 1, '', 'http://www.templatebar.de/___FILES/TBbanner/tb_468x60_2.gif', 1, '', 'http://www.templatebar.de/___FILES/TBbanner/tb_468x60_2.gif', 1, '', 'http://www.templatebar.de/___FILES/TBbanner/tb_88x32.gif', 1, 0),
(4, 'MODSbar.de', 'http://www.modsbar.de', '<p>Auf MODSbar.de kannst du dir kosteng&uuml;nstige Modifikationen und/oder Dienstleistungen von Top Codern erwerben.</p>', 1, '', 'http://www.templatebar.de/___FILES/MBbanner/mb_468x60.gif', 1, '', 'http://www.templatebar.de/___FILES/MBbanner/mb_468x60.gif', 1, '', 'http://www.templatebar.de/___FILES/MBbanner/mb_88x32.gif', 2, 0);");



    db("ALTER TABLE ".$db['partners']." ADD `textlink` INT(1) NOT NULL default '0'");

    db("INSERT INTO ".$db['partners']." (`link`, `banner`, `textlink`) VALUES ('http://www.dzcp.de', 'dzcp.de', 1);");
    db("INSERT INTO ".$db['partners']." (`link`, `banner`, `textlink`) VALUES ('http://www.hogibo.net', 'Webspace', 1);");
    db("INSERT INTO ".$db['partners']." (`link`, `banner`, `textlink`) VALUES ('http://www.freunde.org', 'Freunde finden', 1);");

    db("ALTER TABLE ".$db['permissions']." ADD `pos` INT( 1 ) NOT NULL AFTER `user`");
    db("ALTER TABLE ".$db['f_access']." ADD `pos` INT( 1 ) NOT NULL AFTER `user` ");
    db("ALTER TABLE ".$db['squads']." ADD `beschreibung` TEXT");
    db("ALTER TABLE ".$db['cw']." ADD `top` INT(1) NOT NULL default '0'");
    db("UPDATE ".$db['cw']." SET `top` = '1' WHERE `id` = '1'");
}
function update_mysql_1_5_1()
{
  global $db;
	  db("ALTER TABLE ".$db['serverliste']." CHANGE `clanurl` `clanurl` VARCHAR( 255 ) NOT NULL");
		db("ALTER TABLE ".$db['settings']." ADD `double_post` INT(1) NOT NULL default '1'");
		db("ALTER TABLE ".$db['settings']." ADD `forum_vote` INT(1) NOT NULL default '1'");
}
function update_mysql_1_5_2()
{
  global $db;
		db("ALTER TABLE ".$db['settings']." ADD `gb_activ` INT(1) NOT NULL default '1'");
		db("ALTER TABLE ".$db['settings']." ADD `ts_version` INT(1) NOT NULL AFTER `ts_sport`");
		db("UPDATE ".$db['settings']." SET `ts_version` = '3' WHERE `id` = '1'");
		db("ALTER TABLE ".$db['news']." ADD `timeshift` INT(14) NOT NULL default '0'");
		db("ALTER TABLE ".$db['squads']." ADD `team_show` INT(1) NOT NULL default '1'");

    db("DROP TABLE IF EXISTS ".$db['navi_kats']);
    db("CREATE TABLE ".$db['navi_kats']." (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `name` varchar(200) NOT NULL,
      `placeholder` varchar(200) NOT NULL,
      `level` int(2) NOT NULL,
      PRIMARY KEY (`id`)
    )");

    db("INSERT INTO ".$db['navi_kats']." (`id`, `name`, `placeholder`, `level`) VALUES
        (1, 'Clan Navigation', 'nav_clan', 0),
        (2, 'Main Navigation', 'nav_main', 0),
        (3, 'Server Navigation', 'nav_server', 0),
        (4, 'Misc Navigation', 'nav_misc', 0),
        (5, 'Trial Navigation', 'nav_trial', 2),
        (6, 'Admin Navigation', 'nav_admin', 4),
        (7, 'User Navigation', 'nav_user', 1),
        (8, 'Member Navigation', 'nav_member', 3);
        ");

    db("ALTER TABLE ".$db['config']." ADD `cache_teamspeak` INT( 10 ) NOT NULL DEFAULT '30',
                                      ADD `cache_server` INT( 10 ) NOT NULL DEFAULT '30',
                                      ADD `direct_refresh` INT( 1 ) NOT NULL DEFAULT '0'");
}
function update_mysql_1_5_4()
{
  global $db;
		db("INSERT INTO ".$db['partners']." (`link`, `banner`) VALUES ('http://www.modsbar.de', 'mb_88x32.gif');");
  	db("INSERT INTO ".$db['partners']." (`link`, `banner`) VALUES ('http://www.templatebar.de', 'tb_88x32.gif');");
}
function update_mysql_1_6()
{
  	global $db;
    db("ALTER TABLE `".$db['f_threads']."` CHANGE `edited` `edited` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL");
	db("ALTER TABLE `".$db['users']."` CHANGE `whereami` `whereami` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL");
	db("ALTER TABLE `".$db['downloads']."` ADD `last_dl` INT( 20 ) NOT NULL DEFAULT '0' AFTER `date`");
    db("ALTER TABLE `".$db['settings']."` CHANGE `i_autor` `i_autor` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL");
    db("ALTER TABLE `".$db['gb']."` CHANGE `hp` `hp` VARCHAR(130) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL");
    db("ALTER TABLE `".$db['permissions']."` ADD `gs_showpw` INT(1) NOT NULL DEFAULT '0'");
    db("ALTER TABLE `".$db['settings']."` ADD `urls_linked` INT(1) NOT NULL DEFAULT '1', ADD `ts_customicon` INT(1) NOT NULL DEFAULT '1' AFTER `ts_version`, ADD `ts_showchannel` INT(1) NOT NULL DEFAULT '0' AFTER `ts_customicon`");
    db("ALTER TABLE `".$db['msg']."` CHANGE `see_u` `see_u` INT( 1 ) NOT NULL DEFAULT '0'");
    db("ALTER TABLE `".$db['msg']."` CHANGE `page` `page` INT( 11 ) NOT NULL DEFAULT '0'");
    db("ALTER TABLE `".$db['away']."` CHANGE `lastedit` `lastedit` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL");
    db("ALTER TABLE `".$db['settings']."` DROP `pfad`");
    
    //-> Forum Sortieren
    db("ALTER TABLE ".$db['f_skats']." ADD `pos` int(5) NOT NULL");

    //-> Forum Sortieren funktion: schreibe id von spalte in pos feld um konflikte zu vermeiden!
    $qry = db("SELECT id FROM ".$db['f_skats']."");
     while($get = mysql_fetch_array($qry)){
	   $qrx .= db("UPDATE ".$db['f_skats']." SET `pos` = '".$get['id']."' WHERE `id` = '".$get['id']."'");
     }
    $qry = $qrx;
	//-> Mod/AddOns verwalten
	db("CREATE TABLE ".$db['versions']." (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`id_server` INT(11) NOT NULL,
	`name` VARCHAR(100) NOT NULL,
	`server` LONGTEXT NOT NULL,
	`download_link` LONGTEXT NOT NULL,
	`own_version` VARCHAR(11) NOT NULL,
	`own_date` INT(12) NOT NULL,
	PRIMARY KEY (`id`)) ;");
    
    db("CREATE TABLE ".$db['mods']." (
    `author` varchar(32) NOT NULL,
    `modid` varchar(32) NOT NULL,
    `version` text NOT NULL,
    `serverurl` text NOT NULL,
    `downloadurl` text NOT NULL,
    `installed` datetime NOT NULL,
    `serverversion` text NOT NULL,
    PRIMARY KEY (`author`,`modid`) )");

    //Click IP Counter
    db("CREATE TABLE `".$db['clicks_ips']."` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `ip` varchar(15) NOT NULL DEFAULT '000.000.000.000',
    `uid` int(11) NOT NULL DEFAULT '0',
    `ids` int(11) NOT NULL DEFAULT '0',
    `side` varchar(30) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`), KEY `ip` (`ip`))");
	}
?>