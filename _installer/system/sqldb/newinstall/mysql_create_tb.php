<?php
//=======================================
//Create DZCP Database MySQL Installer
//=======================================

//Neuinstallation
function install_mysql_create()
{
	global $db;
	@ignore_user_abort(true);
  
    //===============================================================
    //-> Artikelkommentare ==========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['acomments']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['acomments']."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `artikel` int(10) NOT NULL DEFAULT '0',
      `nick` varchar(20) NOT NULL DEFAULT '',
      `datum` int(20) NOT NULL DEFAULT '0',
      `email` varchar(130) NOT NULL DEFAULT '',
      `hp` varchar(50) NOT NULL DEFAULT '',
      `reg` int(5) NOT NULL DEFAULT '0',
      `comment` text NOT NULL,
      `ip` varchar(50) NOT NULL DEFAULT '',
      `editby` text NOT NULL,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
    

    //===============================================================
    //-> Addons =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['addons']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['addons']."` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`author` varchar(249) NOT NULL DEFAULT '',
	`name` varchar(249) NOT NULL DEFAULT '',
	`version` varchar(30) NOT NULL DEFAULT '',
	`updater` int(1) NOT NULL DEFAULT '1',
	`last_checked` int(20) NOT NULL DEFAULT '0',
	`new_version` int(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
	) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
  
    //===============================================================
    //-> Artikel ====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['artikel']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['artikel']."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `autor` varchar(5) NOT NULL DEFAULT '',
      `datum` varchar(20) NOT NULL DEFAULT '',
      `kat` int(2) NOT NULL DEFAULT '0',
      `titel` varchar(249) NOT NULL DEFAULT '',
      `text` text NOT NULL,
      `link1` varchar(100) NOT NULL DEFAULT '',
      `url1` varchar(200) NOT NULL DEFAULT '',
      `link2` varchar(100) NOT NULL DEFAULT '',
      `url2` varchar(200) NOT NULL DEFAULT '',
      `link3` varchar(100) NOT NULL DEFAULT '',
      `url3` varchar(200) NOT NULL DEFAULT '',
      `public` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
  
    //===============================================================
    //-> Awards =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['awards']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['awards']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `squad` int(10) NOT NULL,
      `date` varchar(20) NOT NULL DEFAULT '',
      `postdate` varchar(20) NOT NULL DEFAULT '',
      `event` varchar(50) NOT NULL DEFAULT '',
      `place` varchar(5) NOT NULL DEFAULT '',
      `prize` text NOT NULL,
      `url` text NOT NULL,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Away =======================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['away']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['away']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `userid` int(14) NOT NULL DEFAULT '0',
      `titel` varchar(30) NOT NULL,
      `reason` longtext NOT NULL,
      `start` int(20) NOT NULL DEFAULT '0',
      `end` int(20) NOT NULL DEFAULT '0',
      `date` text NOT NULL,
      `lastedit` text,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
		
    //===============================================================
    //-> Clankasse ==================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['clankasse']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['clankasse']."` (
      `id` int(20) NOT NULL AUTO_INCREMENT,
      `datum` varchar(20) NOT NULL DEFAULT '',
      `member` varchar(50) NOT NULL DEFAULT '0',
      `transaktion` varchar(249) NOT NULL DEFAULT '',
      `pm` int(1) NOT NULL DEFAULT '0',
      `betrag` varchar(10) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Clankassenkategorien =======================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['c_kats']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['c_kats']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `kat` varchar(30) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Clankassenzahlungen ========================================
    //===============================================================	
    db("DROP TABLE IF EXISTS `".$db['c_payed']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['c_payed']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `user` int(5) NOT NULL DEFAULT '0',
      `payed` varchar(20) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
		
    //===============================================================
    //-> Clanwars ===================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['cw']."`;",false,false,true);	
    db("CREATE TABLE IF NOT EXISTS `".$db['cw']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `squad_id` int(19) NOT NULL,
      `gametype` varchar(249) NOT NULL DEFAULT '',
      `gcountry` varchar(20) NOT NULL DEFAULT 'de',
      `matchadmins` varchar(249) NOT NULL DEFAULT '',
      `lineup` varchar(249) NOT NULL DEFAULT '',
      `glineup` varchar(249) NOT NULL DEFAULT '',
      `datum` int(20) NOT NULL DEFAULT '0',
      `clantag` varchar(20) NOT NULL DEFAULT '',
      `gegner` varchar(100) NOT NULL DEFAULT '',
      `url` varchar(249) NOT NULL DEFAULT '',
      `xonx` varchar(10) NOT NULL DEFAULT '',
      `liga` varchar(30) NOT NULL DEFAULT '',
      `punkte` int(5) NOT NULL DEFAULT '0',
      `gpunkte` int(5) NOT NULL DEFAULT '0',
      `maps` varchar(30) NOT NULL DEFAULT '',
      `serverip` varchar(50) NOT NULL DEFAULT '',
      `servername` varchar(249) NOT NULL DEFAULT '',
      `serverpwd` varchar(20) NOT NULL DEFAULT '',
      `bericht` text NOT NULL,
      `top` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
			
    //===============================================================
    //-> Clanwarplayers =============================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['cw_player']."`",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['cw_player']."` (
      `cwid` int(5) NOT NULL DEFAULT '0',
      `member` int(5) NOT NULL DEFAULT '0',
      `status` int(5) NOT NULL DEFAULT '0',
      KEY `cwid` (`cwid`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1;",false,false,true);
	
    //===============================================================
    //-> Click IP Counter ===========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['clicks_ips']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['clicks_ips']."` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`ip` varchar(15) NOT NULL DEFAULT '000.000.000.000',
	`uid` int(11) NOT NULL DEFAULT '0',
	`ids` int(11) NOT NULL DEFAULT '0',
	`side` varchar(30) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`),
	KEY `ip` (`ip`)
	) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
			
    //===============================================================
    //-> Clanwarkommentare ==========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['cw_comments']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['cw_comments']."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `cw` int(10) NOT NULL DEFAULT '0',
      `nick` varchar(20) NOT NULL DEFAULT '',
      `datum` int(20) NOT NULL DEFAULT '0',
      `email` varchar(130) NOT NULL DEFAULT '',
      `hp` varchar(50) NOT NULL DEFAULT '',
      `reg` int(5) NOT NULL DEFAULT '0',
      `comment` text NOT NULL,
      `ip` varchar(50) NOT NULL DEFAULT '',
      `editby` text NOT NULL,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
			
    //===============================================================
    //-> Config =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['config']."`;",false,false,true);	
    db("CREATE TABLE IF NOT EXISTS `".$db['config']."` (
      `id` int(1) NOT NULL AUTO_INCREMENT,
      `upicsize` int(5) NOT NULL DEFAULT '100',
      `gallery` int(5) NOT NULL DEFAULT '4',
      `m_usergb` int(5) NOT NULL DEFAULT '10',
      `m_clanwars` int(5) NOT NULL DEFAULT '10',
      `maxshoutarchiv` int(5) NOT NULL DEFAULT '20',
      `m_clankasse` int(5) NOT NULL DEFAULT '20',
      `m_awards` int(5) NOT NULL DEFAULT '15',
      `m_userlist` int(5) NOT NULL DEFAULT '40',
      `m_banned` int(5) NOT NULL DEFAULT '40',
      `maxwidth` int(4) NOT NULL DEFAULT '400',
      `shout_max_zeichen` int(5) NOT NULL DEFAULT '100',
      `l_servernavi` int(5) NOT NULL DEFAULT '22',
      `m_adminnews` int(5) NOT NULL DEFAULT '20',
      `m_shout` int(5) NOT NULL DEFAULT '10',
      `m_comments` int(5) NOT NULL DEFAULT '10',
      `m_archivnews` int(5) NOT NULL DEFAULT '30',
      `m_gb` int(5) NOT NULL DEFAULT '10',
      `m_fthreads` int(5) NOT NULL DEFAULT '20',
      `m_fposts` int(5) NOT NULL DEFAULT '10',
      `m_news` int(5) NOT NULL DEFAULT '5',
      `f_forum` int(5) NOT NULL DEFAULT '20',
      `l_shoutnick` int(5) NOT NULL DEFAULT '20',
      `f_gb` int(5) NOT NULL DEFAULT '20',
      `f_membergb` int(5) NOT NULL DEFAULT '20',
      `f_shout` int(5) NOT NULL DEFAULT '20',
      `f_newscom` int(5) NOT NULL DEFAULT '20',
      `f_cwcom` int(5) NOT NULL DEFAULT '20',
      `f_artikelcom` int(5) NOT NULL DEFAULT '20',
      `l_newsadmin` int(5) NOT NULL DEFAULT '20',
      `l_shouttext` int(5) NOT NULL DEFAULT '22',
      `l_newsarchiv` int(5) NOT NULL DEFAULT '20',
      `l_forumtopic` int(5) NOT NULL DEFAULT '20',
      `l_forumsubtopic` int(5) NOT NULL DEFAULT '20',
      `l_clanwars` int(5) NOT NULL DEFAULT '30',
      `m_gallerypics` int(5) NOT NULL DEFAULT '5',
      `m_lnews` int(5) NOT NULL DEFAULT '6',
      `m_topdl` int(5) NOT NULL DEFAULT '5',
      `m_ftopics` int(5) NOT NULL DEFAULT '6',
      `m_lwars` int(5) NOT NULL DEFAULT '6',
      `m_nwars` int(5) NOT NULL DEFAULT '6',
      `l_topdl` int(5) NOT NULL DEFAULT '20',
      `l_ftopics` int(5) NOT NULL DEFAULT '28',
      `l_lnews` int(5) NOT NULL DEFAULT '22',
      `l_lwars` int(5) NOT NULL DEFAULT '12',
      `l_nwars` int(5) NOT NULL DEFAULT '12',
      `l_lreg` int(5) NOT NULL DEFAULT '12',
      `m_lreg` int(5) NOT NULL DEFAULT '5',
      `m_artikel` int(5) NOT NULL DEFAULT '15',
      `m_cwcomments` int(5) NOT NULL DEFAULT '10',
      `m_adminartikel` int(5) NOT NULL DEFAULT '15',
      `securelogin` int(1) NOT NULL DEFAULT '0',
      `allowhover` int(1) NOT NULL DEFAULT '1',
      `teamrow` int(1) NOT NULL DEFAULT '3',
      `l_lartikel` int(1) NOT NULL DEFAULT '18',
      `m_lartikel` int(1) NOT NULL DEFAULT '5',
      `l_team` int(5) NOT NULL DEFAULT '7',
      `m_events` int(5) NOT NULL DEFAULT '5',
      `m_away` int(5) NOT NULL DEFAULT '10',
      `cache_teamspeak` int(10) NOT NULL DEFAULT '30',
      `cache_server` int(10) NOT NULL DEFAULT '30',
      `direct_refresh` int(1) NOT NULL DEFAULT '0',
      UNIQUE KEY `id` (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
		
    //===============================================================
    //-> Counter ====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['counter']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['counter']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `visitors` int(20) NOT NULL DEFAULT '0',
      `today` varchar(50) NOT NULL DEFAULT '0',
      `maxonline` int(5) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
		
    //===============================================================
    //-> Counter IPs ================================================
    //===============================================================	 
    db("DROP TABLE IF EXISTS `".$db['c_ips']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['c_ips']."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `ip` varchar(30) NOT NULL DEFAULT '0',
      `datum` int(20) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Counter whoison ============================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['c_who']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['c_who']."` (
      `id` int(50) NOT NULL AUTO_INCREMENT,
      `ip` char(50) NOT NULL DEFAULT '',
      `online` int(20) NOT NULL DEFAULT '0',
      `whereami` text NOT NULL,
      `login` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`),
      UNIQUE KEY `ip` (`ip`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
			
    //===============================================================
    //-> Downloads ==================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['downloads']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['downloads']."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `download` varchar(249) NOT NULL DEFAULT '',
      `url` varchar(249) NOT NULL DEFAULT '',
      `beschreibung` varchar(249) DEFAULT NULL,
      `hits` int(50) NOT NULL DEFAULT '0',
      `kat` int(5) NOT NULL DEFAULT '0',
      `date` int(20) NOT NULL DEFAULT '0',
      `last_dl` int(20) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Downloadkategorien =========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['dl_kat']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['dl_kat']."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `name` varchar(249) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
		
    //===============================================================
    //-> Events (Kalender) ==========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['events']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['events']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `datum` int(20) NOT NULL DEFAULT '0',
      `title` varchar(30) NOT NULL DEFAULT '',
      `event` text NOT NULL,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Forum: Access ==============================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['f_access']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['f_access']."` (
      `user` int(10) NOT NULL DEFAULT '0',
      `pos` int(1) NOT NULL,
      `forum` int(10) NOT NULL DEFAULT '0',
      PRIMARY KEY `user` (`user`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1;",false,false,true);
					
    //=============================================================== 
    //-> Forum: Kategorien ==========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['f_kats']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['f_kats']."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `kid` int(10) NOT NULL DEFAULT '0',
      `name` varchar(50) NOT NULL DEFAULT '',
      `intern` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Forumposts =================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['f_posts']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['f_posts']."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `kid` int(2) NOT NULL DEFAULT '0',
      `sid` int(2) NOT NULL DEFAULT '0',
      `date` int(20) NOT NULL DEFAULT '0',
      `nick` varchar(30) NOT NULL DEFAULT '',
      `reg` int(1) NOT NULL DEFAULT '0',
      `email` varchar(130) NOT NULL DEFAULT '',
      `text` text NOT NULL,
      `edited` text NOT NULL,
      `ip` varchar(50) NOT NULL DEFAULT '',
      `hp` varchar(249) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`),
      KEY `sid` (`sid`),
      KEY `date` (`date`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
				 
    //===============================================================
    //-> Forumthreads ===============================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['f_threads']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['f_threads']."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `kid` int(10) NOT NULL DEFAULT '0',
      `t_date` int(20) NOT NULL DEFAULT '0',
      `topic` varchar(249) NOT NULL DEFAULT '',
      `subtopic` varchar(100) NOT NULL DEFAULT '',
      `t_nick` varchar(30) NOT NULL DEFAULT '',
      `t_reg` int(1) NOT NULL DEFAULT '0',
      `t_email` varchar(130) NOT NULL DEFAULT '',
      `t_text` text NOT NULL,
      `hits` int(10) NOT NULL DEFAULT '0',
      `first` int(1) NOT NULL DEFAULT '0',
      `lp` int(20) NOT NULL DEFAULT '0',
      `sticky` int(1) NOT NULL DEFAULT '0',
      `closed` int(1) NOT NULL DEFAULT '0',
      `global` int(1) NOT NULL DEFAULT '0',
      `edited` text,
      `ip` varchar(50) NOT NULL DEFAULT '',
      `t_hp` varchar(249) NOT NULL DEFAULT '',
      `vote` varchar(10) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`),
      KEY `kid` (`kid`),
      KEY `lp` (`lp`),
      KEY `topic` (`topic`),
      KEY `first` (`first`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;",false,false,true);
				 
    //===============================================================	 
    //-> Forum Unterkategorien ======================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['f_skats']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['f_skats']."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `sid` int(10) NOT NULL DEFAULT '0',
      `kattopic` varchar(150) NOT NULL DEFAULT '',
      `subtopic` varchar(150) NOT NULL DEFAULT '',
      `pos` int(5) NOT NULL DEFAULT 1,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Forum ABO ==================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['f_abo']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['f_abo']."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `fid` int(10) NOT NULL,
      `datum` int(20) NOT NULL,
      `user` int(5) NOT NULL,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Galerie ====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['gallery']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['gallery']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `datum` int(20) NOT NULL DEFAULT '0',
      `kat` varchar(200) NOT NULL DEFAULT '',
      `beschreibung` text,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Gaestebuch =================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['gb']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['gb']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `datum` int(20) NOT NULL DEFAULT '0',
      `nick` varchar(30) NOT NULL DEFAULT '',
      `email` varchar(130) NOT NULL DEFAULT '',
      `hp` varchar(130) DEFAULT NULL,
      `reg` int(1) NOT NULL DEFAULT '0',
      `nachricht` text NOT NULL,
      `ip` varchar(50) NOT NULL DEFAULT '',
      `editby` text NOT NULL,
      `public` int(1) NOT NULL,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Glossar ====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['glossar']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['glossar']."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `word` varchar(200) NOT NULL,
      `glossar` text NOT NULL,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Ipcheck & Admin Log ========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['ipcheck']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['ipcheck']."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `ip` varchar(100) NOT NULL DEFAULT '',
      `what` varchar(40) NOT NULL DEFAULT '',
      `time` int(20) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Links ======================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['links']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['links']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `url` varchar(249) NOT NULL DEFAULT '',
      `text` varchar(249) NOT NULL DEFAULT '',
      `banner` int(1) NOT NULL DEFAULT '0',
      `beschreibung` text,
      `hits` int(50) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> LinkUs =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['linkus']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['linkus']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `url` varchar(249) NOT NULL DEFAULT '',
      `text` varchar(249) NOT NULL DEFAULT '',
      `banner` int(1) NOT NULL DEFAULT '0',
      `beschreibung` varchar(249) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
				
    //===============================================================		
    //-> Nachrichten ================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['msg']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['msg']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `datum` int(20) NOT NULL DEFAULT '0',
      `von` int(5) NOT NULL DEFAULT '0',
      `an` int(5) NOT NULL DEFAULT '0',
      `see_u` int(1) NOT NULL DEFAULT '0',
      `page` int(11) NOT NULL DEFAULT '0',
      `titel` varchar(80) NOT NULL DEFAULT '',
      `nachricht` text NOT NULL,
      `see` int(1) NOT NULL DEFAULT '0',
      `readed` int(1) NOT NULL DEFAULT '0',
      `sendmail` int(1) DEFAULT '0',
      `sendnews` int(1) NOT NULL DEFAULT '0',
      `senduser` int(5) NOT NULL DEFAULT '0',
      `sendnewsuser` int(5) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Navigation =================================================
    //===============================================================
     db("DROP TABLE IF EXISTS `".$db['navi']."`;",false,false,true);
     db("CREATE TABLE IF NOT EXISTS `".$db['navi']."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `pos` int(20) NOT NULL DEFAULT '0',
      `kat` varchar(20) DEFAULT '',
      `shown` int(1) NOT NULL DEFAULT '0',
      `name` varchar(249) DEFAULT '',
      `url` varchar(249) DEFAULT '',
      `target` int(1) NOT NULL DEFAULT '0',
      `type` int(1) NOT NULL DEFAULT '0',
      `internal` int(1) NOT NULL DEFAULT '0',
      `wichtig` int(1) NOT NULL DEFAULT '0',
      `editor` int(10) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Navigation Kategorien ======================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['navi_kats']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['navi_kats']."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `name` varchar(200) NOT NULL,
      `placeholder` varchar(200) NOT NULL,
      `level` int(2) NOT NULL,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> News =======================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['news']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['news']."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `autor` varchar(11) NOT NULL DEFAULT '',
      `datum` varchar(20) NOT NULL DEFAULT '',
      `kat` int(2) NOT NULL DEFAULT '0',
      `sticky` int(20) NOT NULL DEFAULT '0',
      `titel` varchar(249) NOT NULL DEFAULT '',
      `intern` int(1) NOT NULL DEFAULT '0',
      `text` text NOT NULL,
      `klapplink` varchar(20) NOT NULL DEFAULT '',
      `klapptext` text NOT NULL,
      `link1` varchar(100) NOT NULL DEFAULT '',
      `url1` varchar(200) NOT NULL DEFAULT '',
      `link2` varchar(100) NOT NULL DEFAULT '',
      `url2` varchar(200) NOT NULL DEFAULT '',
      `link3` varchar(100) NOT NULL DEFAULT '',
      `url3` varchar(200) NOT NULL DEFAULT '',
      `viewed` int(10) NOT NULL DEFAULT '0',
      `public` int(1) NOT NULL DEFAULT '0',
      `timeshift` int(14) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
		
    //===============================================================
    //-> Newskategorien =============================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['newskat']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['newskat']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `katimg` varchar(100) NOT NULL DEFAULT '',
      `kategorie` varchar(60) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
		
    //===============================================================
    //-> Newskommentare =============================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['newscomments']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['newscomments']."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `news` int(10) NOT NULL DEFAULT '0',
      `nick` varchar(20) NOT NULL DEFAULT '',
      `datum` int(20) NOT NULL DEFAULT '0',
      `email` varchar(130) NOT NULL DEFAULT '',
      `hp` varchar(50) NOT NULL DEFAULT '',
      `reg` int(5) NOT NULL DEFAULT '0',
      `comment` text NOT NULL,
      `ip` varchar(50) NOT NULL DEFAULT '',
      `editby` text NOT NULL,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
		
    //===============================================================
    //-> Partnerbuttons =============================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['partners']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['partners']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `link` varchar(100) NOT NULL DEFAULT '',
      `banner` varchar(100) NOT NULL DEFAULT '',
      `textlink` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
					
    //===============================================================
    //-> Rechte =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['permissions']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['permissions']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `user` int(5) NOT NULL DEFAULT '0',
      `pos` int(1) NOT NULL,
      `intforum` int(1) NOT NULL DEFAULT '0',
      `clankasse` int(1) NOT NULL DEFAULT '0',
      `clanwars` int(1) NOT NULL DEFAULT '0',
      `shoutbox` int(1) NOT NULL DEFAULT '0',
      `serverliste` int(1) NOT NULL DEFAULT '0',
      `editusers` int(1) NOT NULL DEFAULT '0',
      `edittactics` int(1) NOT NULL DEFAULT '0',
      `editsquads` int(1) NOT NULL DEFAULT '0',
      `editserver` int(1) NOT NULL DEFAULT '0',
      `editkalender` int(1) NOT NULL DEFAULT '0',
      `news` int(1) NOT NULL DEFAULT '0',
      `gb` int(1) NOT NULL DEFAULT '0',
      `forum` int(1) NOT NULL DEFAULT '0',
      `votes` int(1) NOT NULL DEFAULT '0',
      `gallery` int(1) NOT NULL DEFAULT '0',
      `votesadmin` int(1) NOT NULL DEFAULT '0',
      `links` int(1) NOT NULL DEFAULT '0',
      `downloads` int(1) NOT NULL DEFAULT '0',
      `newsletter` int(1) NOT NULL DEFAULT '0',
      `intnews` int(1) NOT NULL DEFAULT '0',
      `rankings` int(1) NOT NULL DEFAULT '0',
      `contact` int(1) NOT NULL DEFAULT '0',
      `joinus` int(1) NOT NULL DEFAULT '0',
      `awards` int(1) NOT NULL DEFAULT '0',
      `artikel` int(1) NOT NULL DEFAULT '0',
      `receivecws` int(1) NOT NULL DEFAULT '0',
      `editor` int(1) NOT NULL DEFAULT '0',
      `glossar` int(1) NOT NULL DEFAULT '0',
      `gs_showpw` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Positionen =================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['pos']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['pos']."` (
      `id` int(2) NOT NULL AUTO_INCREMENT,
      `pid` int(2) NOT NULL DEFAULT '0',
      `position` varchar(30) NOT NULL DEFAULT '',
      `nletter` int(1) NOT NULL,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
		
    //===============================================================
    //-> Profilfelder ===============================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['profile']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['profile']."` (
      `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
      `kid` int(11) NOT NULL DEFAULT '0',
      `name` varchar(200) NOT NULL,
      `feldname` varchar(20) NOT NULL DEFAULT '',
      `type` int(5) NOT NULL DEFAULT '1',
      `shown` int(5) NOT NULL DEFAULT '1',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
		
    //===============================================================
    //-> Rankings ===================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['rankings']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['rankings']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `league` varchar(50) NOT NULL,
      `lastranking` int(10) NOT NULL,
      `rank` int(10) NOT NULL,
      `squad` varchar(5) NOT NULL,
      `url` varchar(249) NOT NULL,
      `postdate` int(20) NOT NULL,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Server =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['server']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['server']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `status` varchar(100) NOT NULL DEFAULT '',
      `shown` int(1) NOT NULL DEFAULT '1',
      `navi` int(1) NOT NULL DEFAULT '0',
      `name` text,
      `ip` varchar(50) NOT NULL DEFAULT '0',
      `port` int(10) NOT NULL DEFAULT '0',
      `pwd` varchar(20) NOT NULL DEFAULT '',
      `game` varchar(30) NOT NULL DEFAULT '',
      `qport` varchar(10) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
		
    //===============================================================
    //-> Serverliste ================================================
    //===============================================================
    	db("DROP TABLE IF EXISTS `".$db['serverliste']."`;",false,false,true);
    	db("CREATE TABLE IF NOT EXISTS `".$db['serverliste']."` (
      `id` int(20) NOT NULL AUTO_INCREMENT,
      `datum` int(11) NOT NULL DEFAULT '0',
      `clanname` varchar(50) NOT NULL DEFAULT '',
      `clanurl` varchar(255) NOT NULL,
      `ip` varchar(50) NOT NULL DEFAULT '',
      `port` varchar(10) NOT NULL DEFAULT '',
      `pwd` varchar(10) NOT NULL DEFAULT '',
      `checked` int(1) NOT NULL DEFAULT '0',
      `slots` char(2) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Settings ===================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['settings']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['settings']."` (
      `id` int(1) NOT NULL AUTO_INCREMENT,
      `clanname` varchar(50) NOT NULL DEFAULT 'Dein Clanname hier!',
      `balken_vote` varchar(3) NOT NULL DEFAULT '2',
      `reg_forum` int(1) NOT NULL DEFAULT '1',
      `reg_cwcomments` int(1) NOT NULL DEFAULT '1',
      `counter_start` int(10) NOT NULL DEFAULT '0',
      `balken_vote_menu` varchar(3) NOT NULL DEFAULT '0.9',
      `balken_cw` varchar(3) NOT NULL DEFAULT '2.4',
      `reg_dl` int(1) NOT NULL DEFAULT '1',
      `reg_artikel` int(1) NOT NULL DEFAULT '1',
      `reg_newscomments` int(1) NOT NULL DEFAULT '1',
      `tmpdir` varchar(100) NOT NULL DEFAULT 'version1.5',
      `wmodus` int(1) NOT NULL DEFAULT '0',
      `persinfo` int(1) NOT NULL DEFAULT '1',
      `iban` varchar(100) NOT NULL DEFAULT '',
      `bic` varchar(100) NOT NULL DEFAULT '',
      `badwords` text NOT NULL,
      `pagetitel` varchar(50) NOT NULL DEFAULT 'Dein Seitentitel hier!',
      `last_backup` int(20) NOT NULL DEFAULT '0',
      `squadtmpl` int(1) NOT NULL DEFAULT '1',
      `i_domain` varchar(50) NOT NULL DEFAULT 'www.deineUrl.de',
      `i_autor` text,
      `k_nr` varchar(100) NOT NULL DEFAULT '123456789',
      `k_inhaber` varchar(50) NOT NULL DEFAULT 'Max Mustermann',
      `k_blz` varchar(100) NOT NULL DEFAULT '123456789',
      `k_bank` varchar(200) NOT NULL DEFAULT 'Musterbank',
      `k_waehrung` varchar(15) NOT NULL DEFAULT '&euro;',
      `language` varchar(50) NOT NULL DEFAULT 'deutsch',
      `domain` varchar(200) NOT NULL DEFAULT '127.0.0.1',
      `gametiger` varchar(20) NOT NULL DEFAULT 'cstrike',
      `regcode` int(1) NOT NULL DEFAULT '1',
      `mailfrom` varchar(200) NOT NULL DEFAULT 'info@127.0.0.1',
      `ts_ip` varchar(200) NOT NULL DEFAULT '',
      `ts_port` int(10) NOT NULL DEFAULT '0',
      `ts_sport` int(10) NOT NULL DEFAULT '0',
      `ts_version` int(1) NOT NULL DEFAULT '3',
      `ts_customicon` int(1) NOT NULL DEFAULT '1',
      `ts_showchannel` int(1) NOT NULL DEFAULT '0',
      `ts_width` int(10) NOT NULL DEFAULT '0',
      `eml_reg_subj` varchar(200) NOT NULL DEFAULT '',
      `eml_pwd_subj` varchar(200) NOT NULL DEFAULT '',
      `eml_nletter_subj` varchar(200) NOT NULL DEFAULT '',
      `eml_reg` text NOT NULL,
      `eml_pwd` text NOT NULL,
      `eml_nletter` text NOT NULL,
      `reg_shout` int(1) NOT NULL DEFAULT '1',
      `gmaps_key` varchar(200) NOT NULL,
      `gmaps_who` int(1) NOT NULL DEFAULT '1',
      `prev` varchar(3) NOT NULL DEFAULT '',
      `eml_fabo_npost_subj` varchar(200) NOT NULL,
      `eml_fabo_tedit_subj` varchar(200) NOT NULL,
      `eml_fabo_pedit_subj` varchar(200) NOT NULL,
      `eml_pn_subj` varchar(200) NOT NULL,
      `eml_fabo_npost` text NOT NULL,
      `eml_fabo_tedit` text NOT NULL,
      `eml_fabo_pedit` text NOT NULL,
      `eml_pn` text NOT NULL,
      `k_vwz` varchar(200) NOT NULL,
      `double_post` int(1) NOT NULL DEFAULT '1',
      `forum_vote` int(1) NOT NULL DEFAULT '1',
      `gb_activ` int(1) NOT NULL DEFAULT '1',
      `urls_linked` int(1) NOT NULL DEFAULT '1',
      `db_version` varchar(4) NOT NULL DEFAULT '0000',
	  PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Shoutbox ===================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['shout']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['shout']."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `datum` int(30) NOT NULL DEFAULT '0',
      `nick` varchar(30) NOT NULL DEFAULT '',
      `email` varchar(130) NOT NULL DEFAULT '',
      `text` text NOT NULL,
      `ip` varchar(50) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Seiten =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['sites']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['sites']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `titel` varchar(50) NOT NULL DEFAULT '',
      `text` text NOT NULL,
      `html` int(1) NOT NULL,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Sponsoren ==================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['sponsoren']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['sponsoren']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `name` varchar(249) NOT NULL,
      `link` varchar(249) NOT NULL,
      `beschreibung` text NOT NULL,
      `site` int(1) NOT NULL DEFAULT '0',
      `send` varchar(5) NOT NULL,
      `slink` varchar(249) NOT NULL,
      `banner` int(1) NOT NULL DEFAULT '0',
      `bend` varchar(5) NOT NULL,
      `blink` varchar(249) NOT NULL,
      `box` int(1) NOT NULL DEFAULT '0',
      `xend` varchar(5) NOT NULL,
      `xlink` varchar(255) NOT NULL,
      `pos` int(5) NOT NULL,
      `hits` int(50) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
		
    //===============================================================
    //-> Squads =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['squads']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['squads']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `name` varchar(40) NOT NULL DEFAULT '',
      `game` varchar(40) NOT NULL DEFAULT '',
      `icon` varchar(20) NOT NULL DEFAULT '',
      `pos` int(1) NOT NULL DEFAULT '0',
      `shown` int(1) NOT NULL DEFAULT '0',
      `navi` int(1) NOT NULL DEFAULT '1',
      `status` int(1) NOT NULL DEFAULT '1',
      `beschreibung` text,
      `team_show` int(1) NOT NULL DEFAULT '1',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Squadusers =================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['squaduser']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['squaduser']."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user` int(5) NOT NULL DEFAULT '0',
      `squad` int(2) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
			
    //===============================================================
    //-> Taktiken ===================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['taktik']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['taktik']."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `datum` int(20) NOT NULL DEFAULT '0',
      `map` varchar(20) NOT NULL DEFAULT '',
      `spart` text NOT NULL,
      `standardt` text NOT NULL,
      `sparct` text NOT NULL,
      `standardct` text NOT NULL,
      `autor` int(5) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
		
    //===============================================================
    //-> Buddys =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['buddys']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['buddys']."` (
      `id` int(10) NOT NULL AUTO_INCREMENT,
      `user` int(5) NOT NULL DEFAULT '0',
      `buddy` int(5) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
				 
    //===============================================================
    //-> Usergallery ================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['usergallery']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['usergallery']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `user` int(5) NOT NULL DEFAULT '0',
      `beschreibung` text,
      `pic` varchar(200) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
					 
    //===============================================================
    //-> UserGB =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['usergb']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['usergb']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `user` int(5) NOT NULL DEFAULT '0',
      `datum` int(20) NOT NULL DEFAULT '0',
      `nick` varchar(30) NOT NULL DEFAULT '',
      `email` varchar(130) NOT NULL DEFAULT '',
      `hp` varchar(100) NOT NULL DEFAULT '',
      `reg` int(1) NOT NULL DEFAULT '0',
      `nachricht` text NOT NULL,
      `ip` varchar(50) NOT NULL DEFAULT '',
      `editby` text NOT NULL,
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
				
    //===============================================================
    //-> Userposis ==================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['userpos']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['userpos']."` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user` int(5) NOT NULL DEFAULT '0',
      `posi` int(5) NOT NULL DEFAULT '0',
      `squad` int(5) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
		
    //===============================================================				
    //-> Users ======================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['users']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['users']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `user` varchar(200) NOT NULL DEFAULT '',
      `nick` varchar(200) NOT NULL DEFAULT '',
      `pwd` varchar(255) NOT NULL DEFAULT '',
      `sessid` varchar(32) DEFAULT NULL,
      `country` varchar(20) NOT NULL DEFAULT 'de',
      `ip` varchar(50) NOT NULL DEFAULT '',
      `regdatum` int(20) NOT NULL DEFAULT '0',
      `email` varchar(200) NOT NULL DEFAULT '',
      `icq` varchar(20) NOT NULL DEFAULT '',
      `xfire` varchar(100) NOT NULL DEFAULT '',
      `steamid` varchar(30) NOT NULL DEFAULT '',
      `level` varchar(15) NOT NULL DEFAULT '',
      `rlname` varchar(200) NOT NULL DEFAULT '',
      `city` varchar(200) NOT NULL DEFAULT '',
      `sex` int(1) NOT NULL DEFAULT '0',
      `bday` varchar(20) NOT NULL DEFAULT '',
      `hobbys` varchar(249) NOT NULL DEFAULT '',
      `motto` varchar(249) NOT NULL DEFAULT '',
      `hp` varchar(200) NOT NULL DEFAULT '',
      `cpu` varchar(200) NOT NULL DEFAULT '',
      `ram` varchar(200) NOT NULL DEFAULT '',
      `monitor` varchar(200) NOT NULL DEFAULT '',
      `maus` varchar(200) NOT NULL DEFAULT '',
      `mauspad` varchar(200) NOT NULL DEFAULT '',
      `headset` varchar(200) NOT NULL DEFAULT '',
      `board` varchar(200) NOT NULL DEFAULT '',
      `os` varchar(200) NOT NULL DEFAULT '',
      `graka` varchar(200) NOT NULL DEFAULT '',
      `hdd` varchar(200) NOT NULL DEFAULT '',
      `inet` varchar(200) NOT NULL DEFAULT '',
      `signatur` text,
      `position` int(2) NOT NULL DEFAULT '0',
      `status` int(1) NOT NULL DEFAULT '1',
      `ex` varchar(200) NOT NULL DEFAULT '',
      `job` varchar(200) NOT NULL DEFAULT '',
      `time` int(20) NOT NULL DEFAULT '0',
      `listck` int(1) NOT NULL DEFAULT '0',
      `online` int(1) NOT NULL DEFAULT '0',
      `nletter` int(1) NOT NULL DEFAULT '1',
      `whereami` text,
      `drink` varchar(249) NOT NULL DEFAULT '',
      `essen` varchar(249) NOT NULL DEFAULT '',
      `film` varchar(249) NOT NULL DEFAULT '',
      `musik` varchar(249) NOT NULL DEFAULT '',
      `song` varchar(249) NOT NULL DEFAULT '',
      `buch` varchar(249) NOT NULL DEFAULT '',
      `autor` varchar(249) NOT NULL DEFAULT '',
      `person` varchar(249) NOT NULL DEFAULT '',
      `sport` varchar(249) NOT NULL DEFAULT '',
      `sportler` varchar(249) NOT NULL DEFAULT '',
      `auto` varchar(249) NOT NULL DEFAULT '',
      `game` varchar(249) NOT NULL DEFAULT '',
      `favoclan` varchar(249) NOT NULL DEFAULT '',
      `spieler` varchar(249) NOT NULL DEFAULT '',
      `map` varchar(249) NOT NULL DEFAULT '',
      `waffe` varchar(249) NOT NULL DEFAULT '',
      `rasse` varchar(249) NOT NULL DEFAULT '',
      `url2` varchar(249) NOT NULL DEFAULT '',
      `url3` varchar(249) NOT NULL DEFAULT '',
      `beschreibung` text,
      `gmaps_koord` varchar(249) NOT NULL,
      `pnmail` int(1) NOT NULL DEFAULT '1',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
		
    //===============================================================		
    //-> Userstats ==================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['userstats']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['userstats']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `user` int(10) NOT NULL DEFAULT '0',
      `logins` int(100) NOT NULL DEFAULT '0',
      `writtenmsg` int(10) NOT NULL DEFAULT '0',
      `lastvisit` int(20) NOT NULL DEFAULT '0',
      `hits` int(249) NOT NULL DEFAULT '0',
      `votes` int(5) NOT NULL DEFAULT '0',
      `profilhits` int(20) NOT NULL DEFAULT '0',
      `forumposts` int(5) NOT NULL DEFAULT '0',
      `cws` int(5) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
	
    //===============================================================
    //-> Votes ======================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['votes']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['votes']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `datum` int(20) NOT NULL DEFAULT '0',
      `titel` varchar(249) NOT NULL DEFAULT '',
      `intern` int(1) NOT NULL DEFAULT '0',
      `menu` int(1) NOT NULL DEFAULT '0',
      `closed` int(1) NOT NULL DEFAULT '0',
      `von` int(10) NOT NULL DEFAULT '0',
      `forum` int(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
					
    //===============================================================
    //-> Vote Möglichkeit ==========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['vote_results']."`;",false,false,true);
    db("CREATE TABLE IF NOT EXISTS `".$db['vote_results']."` (
      `id` int(5) NOT NULL AUTO_INCREMENT,
      `vid` int(5) NOT NULL DEFAULT '0',
      `what` varchar(5) NOT NULL DEFAULT '',
      `sel` varchar(80) NOT NULL DEFAULT '',
      `stimmen` int(5) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`)
    ) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);
}
?>