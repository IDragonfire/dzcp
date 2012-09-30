<?php
//===============================================================
//Insert DZCP-Database MySQL Installer
//===============================================================
function install_mysql_insert($db_infos)
{
	global $db;
	
	//===============================================================
	//-> Downloadkategorien =========================================
	//===============================================================	
	db("INSERT INTO ".$db['dl_kat']." (`id`, `name`) VALUES
	(NULL, 'Downloads'),
	(NULL, 'Demos'),
	(NULL, 'Stuff');",false,false,true);
	
	//===============================================================
	//-> Downloads ==================================================
	//===============================================================	
	db("INSERT INTO ".$db['downloads']." (`id`, `download`, `url`, `beschreibung`, `hits`, `kat`, `date`, `last_dl`) VALUES
	(NULL, 'Testdownload', 'http://www.url.de/test.zip', '<p>Das ist ein Testdownload</p>', 0, 1, 1298817168, 0);",false,false,true);
	
	//===============================================================
	//-> Forum ======================================================
	//===============================================================
	db("INSERT INTO ".$db['f_kats']." (`id`, `kid`, `name`, `intern`) VALUES
	(NULL, 1, 'Hauptforum', 0),
	(NULL, 2, 'OFFtopic', 0),
	(NULL, 3, 'Clanforum', 1);",false,false,true);
	
	//===============================================================
	//-> Newskategorien =============================================
	//===============================================================		
	db("INSERT INTO ".$db['newskat']." (`id`, `katimg`, `kategorie`) VALUES (NULL, 'hp.jpg', 'Homepage');",false,false,true);
	
	//===============================================================
	//-> Event ======================================================
	//===============================================================		
	db("INSERT INTO ".$db['events']." (`id`, `datum`, `title`, `event`) VALUES
	(NULL, ".(time()+90000).", 'Testevent', '<p>Das ist nur ein Testevent! :)</p>');",false,false,true);
	
	//===============================================================
	//-> Settings ===================================================
	//===============================================================
	db("INSERT INTO `".$db['settings']."` (`id`, `clanname`, `balken_vote`, `reg_forum`, `reg_cwcomments`, `counter_start`, `balken_vote_menu`, `balken_cw`, `reg_dl`, `reg_artikel`, `reg_newscomments`, `tmpdir`, `wmodus`, `persinfo`, `iban`, 
	`bic`, `badwords`, `pagetitel`, `last_backup`, `squadtmpl`, `i_domain`, `i_autor`, `k_nr`, `k_inhaber`, `k_blz`, `k_bank`, `k_waehrung`, `language`,  `domain`, `gametiger`, `regcode`,  `mailfrom`,  `ts_ip`, `ts_port`, `ts_sport`, `ts_version`, 
    `ts_customicon`, `ts_showchannel`, `ts_width`, `eml_reg_subj`, `eml_pwd_subj`, `eml_nletter_subj`, `eml_reg`, `eml_pwd`, `eml_nletter`, `reg_shout`, `gmaps_key`, `gmaps_who`, `prev`, `eml_fabo_npost_subj`, `eml_fabo_tedit_subj`, `eml_fabo_pedit_subj`, 
	`eml_pn_subj`, `eml_fabo_npost`, `eml_fabo_tedit`, `eml_fabo_pedit`, `eml_pn`, `k_vwz`, `double_post`, `forum_vote`, `gb_activ`, `urls_linked`,`db_version`) VALUES (NULL, 
	'".up($db_infos['clanname'])."', '2', '1', '1', '0', '0.9', '2.4', '1', '1', '1', 'version1.5', '0', '1', '', '', 'arsch,Arsch,arschloch,Arschloch,hure,Hure', '".up($db_infos['seitentitel'])."', '0', '1', '".$_SERVER['SERVER_NAME']."', 'Max Mustermann', '123456789', 
	'Max Mustermann', '123456789', 'Musterbank', '&euro;', 'deutsch', '".$_SERVER['SERVER_ADDR']."', 'cstrike', '1', '".$db_infos['emailweb']."', '80.190.204.164', '7000', '10011', '3', '1', '0', '0', 'Deine Registrierung', 'Deine Zugangsdaten', 
    'Newsletter', 'Du hast dich erfolgreich auf unserer Seite registriert!\r\nDeine Logindaten lauten:\r\n\r\n##########\r\nLoginname: [user]\r\nPasswort: [pwd]\r\n##########\r\n\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]', 
	'Ein neues Passwort wurde f&uuml;r deinen Account generiert!\r\n\r\n#########\r\nLogin-Name: [user]\r\nPasswort: [pwd]\r\n#########\r\n\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]', '[text]\r\n\r\n\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]', 
	'1', '', '1', '".strtolower(mkpwd(3,false))."', 'Neuer Beitrag auf abonniertes Thema im [titel]', 'Thread auf abonniertes Thema im [titel] wurde editiert', 'Beitrag auf abonniertes Thema im [titel] wurde editiert', 'Neue PN auf [domain]', 
	'Hallo [nick],<br />\r\n<br />\r\n[postuser] hat auf das Thema: [topic] auf der Website: &#34;[titel]&#34; geantwortet.<br />\r\n<br />\r\nDen neuen Beitrag erreichst Du ber folgenden Link:<br />\r\n<a href=&#34;http://[domain]/forum/?action=showthread&id=[id]&page=[page]#p[entrys]&#34;>http://[domain]/forum/?action=showthread&id=[id]&page=[page]#p[entrys]</a><br />\r\n<br />\r\n[postuser] hat folgenden Text geschrieben:<br />\r\n---------------------------------<br />\r\n[text]<br />\r\n---------------------------------<br />\r\n<br />\r\nMit freundlichen Gr&uuml;&szlig;en,<br />\r\n<br />\r\nDein [clan]<br />\r\n<br />\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]', 
    'Hallo [nick],<br />\r\n		 <br />\r\nDer Thread mit dem Titel: [topic] auf der Website: &#34;[titel]&#34; wurde soeben von [postuser] editiert.<br />\r\n<br />\r\nDen editierten Beitrag erreichst Du ber folgenden Link:<br />\r\n<a href=&#34;http://[domain]/forum/?action=showthread&id=[id]&#34;>http://[domain]/forum/?action=showthread&id=[id]</a><br />\r\n	<br />\r\n[postuser] hat folgenden neuen Text geschrieben:<br />\r\n---------------------------------<br />\r\n[text]<br />\r\n---------------------------------<br />\r\n	<br />\r\nMit freundlichen Gr&uuml;&szlig;en,<br />\r\n<br />\r\nDein [clan]<br />\r\n<br />\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]', 
	'Hallo [nick],<br />\r\n<br />\r\nEin Beitrag im Thread mit dem Titel: [topic] auf der Website: &#34;[titel]&#34; wurde soeben von [postuser] editiert.<br />\r\n<br />\r\nDen editierten Beitrag erreichst Du ber folgenden Link:<br />\r\n<a href=&#34;http://[domain]/forum/?action=showthread&id=[id]&page=[page]#p[entrys]&#34;>http://[domain]/forum/?action=showthread&id=[id]&page=[page]#p[entrys]</a><br />\r\n<br />\r\n[postuser] hat folgenden neuen Text geschrieben:<br />\r\n---------------------------------<br />\r\n[text]<br />\r\n---------------------------------<br />\r\n<br />\r\nMit freundlichen Gr&uuml;&szlig;en,<br />\r\n<br />\r\nDein [clan]<br />\r\n<br />\r\n[ Diese Email wurde automatisch generiert, bitte nicht antworten! ]', 
	'---------------------------------<br />\r\n<br />\r\nHallo [nick],<br />\r\n<br />\r\nDu hast eine neue Nachricht in deinem Postfach.<br />\r\n<br />\r\nTitel: [titel]<br />\r\n<br />\r\n<a href=&#34;http://[domain]/user/index.php?action=msg&#34;>Zum Nachrichten-Center</a><br />\r\n<br />\r\nVG<br />\r\n<br />\r\n[clan]<br />\r\n<br />\r\n---------------------------------', '', 1, 1, 1, 1, '1600');",false,false,true);
	
	//=============================================================== 
	//-> Forum: Kategorien ==========================================
	//===============================================================
	db("INSERT INTO ".$db['f_skats']." (`id`, `sid`, `kattopic`, `subtopic`, `pos`) VALUES
	(NULL, 1, 'Allgemein', 'Allgemeines...', '2'),
	(NULL, 1, 'Homepage', 'Kritiken/Anregungen/Bugs', '3'),
	(NULL, 1, 'Server', 'Serverseitige Themen...', '4'),
	(NULL, 1, 'Spam', 'Spamt die Bude voll ;)', '5'),
	(NULL, 2, 'Sonstiges', '', '6'),
	(NULL, 2, 'OFFtopic', '', '7'),
	(NULL, 3, 'internes Forum', 'interne Angelegenheiten', '1'),
	(NULL, 3, 'Server intern', 'interne Serverangelegenheiten', '8'),
	(NULL, 3, 'War Forum', 'Alles &uuml;ber und rundum Clanwars', '9');",false,false,true);
	
	//=============================================================== 
	//-> Forum: Threads =============================================
	//===============================================================
	db("INSERT INTO ".$db['f_threads']." (`id`, `kid`, `t_date`, `topic`, `subtopic`, `t_nick`, `t_reg`, `t_email`, `t_text`, `hits`, `first`, `lp`, `sticky`, `closed`, `global`, `edited`, `ip`, `t_hp`, `vote`) VALUES
	(NULL, 1, ".(time() - 9000).", 'Testeintrag', '', '', 1, '', '<p>Testeintrag</p>', 6, 1, 1280627795, 0, 0, 0, NULL, '123.123.123.123', '', '');",false,false,true);
	
	//===============================================================
	//-> Galerie ====================================================
	//===============================================================
	db("INSERT INTO ".$db['gallery']." (`id`, `datum`, `kat`, `beschreibung`) VALUES
	(NULL, ".time().", 'Testgalerie', '<p>Das ist die erste Testgalerie.</p>\r\n<p>Hier seht ihr ein paar Bilder die eigentlich nur als Platzhalter dienen :)</p>');",false,false,true);
	
	//===============================================================
	//-> Links ======================================================
	//===============================================================
	db("INSERT INTO ".$db['links']." (`id`, `url`, `text`, `banner`, `beschreibung`, `hits`) VALUES
	(NULL, 'http://www.dzcp.de', 'http://www.dzcp.de/banner/dzcp.gif', 1, 'deV!L`z Clanportal', 0),
	(NULL, 'http://www.my-starmedia.de', 'http://www.my-starmedia.de/extern/b3/b3.gif', 1, '<b>my-STARMEDIA</b><br />my-STARMEDIA.de - DZCP Mods and Coding', 0);",false,false,true);

	//===============================================================
	//-> LinkUs =====================================================
	//===============================================================
	db("INSERT INTO ".$db['linkus']." (`id`, `url`, `text`, `banner`, `beschreibung`) VALUES
	(NULL, 'http://www.dzcp.de', 'http://www.dzcp.de/banner/button.gif', 1, 'deV!L`z Clanportal');",false,false,true);
	
	//===============================================================
	//-> Navigation =================================================
	//===============================================================
	db("INSERT INTO `".$db['navi']."` (`id`, `pos`, `kat`, `shown`, `name`, `url`, `target`, `type`, `internal`, `wichtig`, `editor`) VALUES
	(NULL, 1, 'nav_main', 1, '_news_', '../news/', 0, 1, 0, 0, 0),
	(NULL, 2, 'nav_main', 1, '_newsarchiv_', '../news/?action=archiv', 0, 1, 0, 0, 0),
	(NULL, 3, 'nav_main', 1, '_artikel_', '../artikel/', 0, 1, 0, 0, 0),
	(NULL, 4, 'nav_main', 1, '_forum_', '../forum/', 0, 1, 0, 0, 0),
	(NULL, 5, 'nav_main', 1, '_gb_', '../gb/', 0, 1, 0, 0, 0),
	(NULL, 1, 'nav_server', 1, '_server_', '../server/', 0, 1, 0, 0, 0),
	(NULL, 6, 'nav_main', 1, '_kalender_', '../kalender/', 0, 1, 0, 0, 0),
	(NULL, 7, 'nav_main', 1, '_votes_', '../votes/', 0, 1, 0, 0, 0),
	(NULL, 8, 'nav_main', 1, '_links_', '../links/', 0, 1, 0, 0, 0),
	(NULL, 9, 'nav_main', 1, '_sponsoren_', '../sponsors/', 0, 1, 0, 0, 0),
	(NULL, 10, 'nav_main', 1, '_downloads_', '../downloads/', 0, 1, 0, 0, 0),
	(NULL, 11, 'nav_main', 1, '_userlist_', '../user/?action=userlist', 0, 1, 0, 0, 0),
	(NULL, 12, 'nav_main', 1, '_glossar_', '../glossar/', 0, 1, 0, 0, 0),
	(NULL, 1, 'nav_clan', 1, '_squads_', '../squads/', 0, 1, 0, 0, 0),
	(NULL, 2, 'nav_clan', 1, '_membermap_', '../membermap/', 0, 1, 0, 0, 0),
	(NULL, 3, 'nav_clan', 1, '_cw_', '../clanwars/', 0, 1, 0, 0, 0),
	(NULL, 4, 'nav_clan', 1, '_awards_', '../awards/', 0, 1, 0, 0, 0),
	(NULL, 5, 'nav_clan', 1, '_rankings_', '../rankings/', 0, 1, 0, 0, 0),
	(NULL, 2, 'nav_server', 1, '_serverlist_', '../serverliste/', 0, 1, 0, 0, 0),
	(NULL, 3, 'nav_server', 1, '_ts_', '../teamspeak/', 0, 1, 0, 0, 0),
	(NULL, 1, 'nav_misc', 1, '_gametiger_', '../gametiger/', 0, 1, 0, 0, 0),
	(NULL, 2, 'nav_misc', 1, '_galerie_', '../gallery/', 0, 1, 0, 0, 0),
	(NULL, 3, 'nav_misc', 1, '_kontakt_', '../contact/', 0, 1, 0, 0, 0),
	(NULL, 4, 'nav_misc', 1, '_joinus_', '../contact/?action=joinus', 0, 1, 0, 0, 0),
	(NULL, 5, 'nav_misc', 1, '_fightus_', '../contact/?action=fightus', 0, 1, 0, 0, 0),
	(NULL, 6, 'nav_misc', 1, '_linkus_', '../linkus/', 0, 1, 0, 0, 0),
	(NULL, 7, 'nav_misc', 1, '_stats_', '../stats/', 0, 1, 0, 0, 0),
	(NULL, 8, 'nav_misc', 1, '_impressum_', '../impressum/', 0, 1, 0, 0, 0),
	(NULL, 1, 'nav_admin', 1, '_admin_', '../admin/', 0, 1, 1, 1, 0),
	(NULL, 1, 'nav_user', 1, '_lobby_', '../user/?action=userlobby', 0, 1, 0, 0, 0),
	(NULL, 2, 'nav_user', 1, '_nachrichten_', '../user/?action=msg', 0, 1, 0, 0, 0),
	(NULL, 3, 'nav_user', 1, '_buddys_', '../user/?action=buddys', 0, 1, 0, 0, 0),
	(NULL, 4, 'nav_user', 1, '_edit_profile_', '../user/?action=editprofile', 0, 1, 0, 0, 0),
	(NULL, 5, 'nav_user', 1, '_logout_', '../user/?action=logout', 0, 1, 0, 1, 0),
	(NULL, 1, 'nav_member', 1, '_clankasse_', '../clankasse/', 0, 1, 0, 0, 0),
	(NULL, 2, 'nav_member', 1, '_taktiken_', '../taktik/', 0, 1, 0, 0, 0),
	(NULL, 0, 'nav_main', 1, '_news_send_', '../news/?action=send', 0, 1, 0, 0, 0),
	(NULL, 1, 'nav_trial', 1, '_awaycal_', '../away/', 0, 2, 1, 0, 0);",false,false,true);
	
	//===============================================================
	//-> News =======================================================
	//===============================================================
	db("INSERT INTO ".$db['news']." (`id`, `autor`, `datum`, `kat`, `sticky`, `titel`, `intern`, `text`, `klapplink`, `klapptext`, `link1`, `url1`, `link2`, `url2`, `link3`, `url3`, `viewed`, `public`, `timeshift`) VALUES
	(NULL, '1', '".time()."', 1, 0, 'deV!L`z Clanportal', 0, '<p>deV!L`z Clanportal wurde erfolgreich installiert!</p><p>Bei Fragen oder Problemen kannst du gerne das Forum unter <a href=\"http://www.dzcp.de/\" target=\"_blank\">www.dzcp.de</a> kontaktieren.</p><p>Mehr Designtemplates und Modifikationen findest du unter <a href=\"http://www.templatebar.de/\" target=\"_blank\" title=\"Templates, Designs &amp; Modifikationen\">www.templatebar.de</a>.</p><p><br /></p><p>Viel Spass mit dem DZCP w&uuml;nscht dir das Team von www.dzcp.de.</p>', '', '', 'www.dzcp.de', 'http://www.dzcp.de', 'TEMPLATEbar.de', 'http://www.templatebar.de', '', '', 0, 1, 0);",false,false,true);
	
	//===============================================================
	//-> Artikel ====================================================
	//===============================================================
	db("INSERT INTO ".$db['artikel']." (`id`, `autor`, `datum`, `kat`, `titel`, `text`, `link1`, `url1`, `link2`, `url2`, `link3`, `url3`, `public`) VALUES
	(NULL, '1', '".time()."', 1, 'Testartikel', '<p>Hier k&ouml;nnte dein Artikel stehen!</p>\r\n<p> </p>', '', '', '', '', '', '', 1);",false,false,true);
	
	//===============================================================
	//-> Profilfelder ===============================================
	//===============================================================
	db("INSERT INTO ".$db['profile']." (`id`, `kid`, `name`, `feldname`, `type`, `shown`) VALUES
	(NULL, 1, '_job_', 'job', 1, 1),
	(NULL, 1, '_hobbys_', 'hobbys', 1, 1),
	(NULL, 1, '_motto_', 'motto', 1, 1),
	(NULL, 2, '_exclans_', 'ex', 1, 1),
	(NULL, 4, '_drink_', 'drink', 1, 1),
	(NULL, 4, '_essen_', 'essen', 1, 1),
	(NULL, 4, '_film_', 'film', 1, 1),
	(NULL, 4, '_musik_', 'musik', 1, 1),
	(NULL, 4, '_song_', 'song', 1, 1),
	(NULL, 4, '_buch_', 'buch', 1, 1),
	(NULL, 4, '_autor_', 'autor', 1, 1),
	(NULL, 4, '_person_', 'person', 1, 1),
	(NULL, 4, '_sport_', 'sport', 1, 1),
	(NULL, 4, '_sportler_', 'sportler', 1, 1),
	(NULL, 4, '_auto_', 'auto', 1, 1),
	(NULL, 4, '_game_', 'game', 1, 1),
	(NULL, 4, '_favoclan_', 'favoclan', 1, 1),
	(NULL, 4, '_spieler_', 'spieler', 1, 1),
	(NULL, 4, '_map_', 'map', 1, 1),
	(NULL, 4, '_waffe_', 'waffe', 1, 1),
	(NULL, 5, '_system_', 'os', 1, 1),
	(NULL, 5, '_board_', 'board', 1, 1),
	(NULL, 5, '_cpu_', 'cpu', 1, 1),
	(NULL, 5, '_ram_', 'ram', 1, 1),
	(NULL, 5, '_graka_', 'graka', 1, 1),
	(NULL, 5, '_hdd_', 'hdd', 1, 1),
	(NULL, 5, '_monitor_', 'monitor', 1, 1),
	(NULL, 5, '_maus_', 'maus', 1, 1),
	(NULL, 5, '_mauspad_', 'mauspad', 1, 1),
	(NULL, 5, '_headset_', 'headset', 1, 1),
	(NULL, 5, '_inet_', 'inet', 1, 1);",false,false,true);
	
	//===============================================================
	//-> Partnerbuttons =============================================
	//===============================================================
	db("INSERT INTO `".$db['partners']."` (`id`, `link`, `banner`, `textlink`) VALUES
	(NULL, 'http://www.my-starmedia.de', 'my-starmedia.gif', 0),
	(NULL, 'http://www.hogibo.net', 'hogibo.gif', 0),
	(NULL, 'http://www.codeking.eu', 'codeking.gif', 0),
	(NULL, 'http://www.dzcp.de', 'dzcp.gif', 0),
	(NULL, 'http://spenden.dzcp.de', 'spenden.gif', 0),
	(NULL, 'http://www.freunde.org', 'Freunde finden', 1),
	(NULL, 'http://www.modsbar.de', 'mb_88x32.gif', 0),
	(NULL, 'http://www.templatebar.de', 'tb_88x32.gif', 0);",false,false,true);
	
	//===============================================================
	//-> Rechte =====================================================
	//===============================================================
	db("INSERT INTO `".$db['permissions']."` (`id`, `user`, `pos`, `intforum`, `clankasse`, `clanwars`, `shoutbox`, `serverliste`, `editusers`, `edittactics`, `editsquads`, `editserver`, `editkalender`, `news`, `gb`, `forum`, `votes`, `gallery`, `votesadmin`, `links`, `downloads`, `newsletter`, `intnews`, `rankings`, `contact`, `joinus`, `awards`, `artikel`, `receivecws`, `editor`, `glossar`, `gs_showpw`) VALUES
	(NULL, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0);",false,false,true);

	//===============================================================
	//-> Positionen =================================================
	//===============================================================
	db("INSERT INTO ".$db['pos']." (`id`, `pid`, `position`, `nletter`) VALUES
	(NULL, 1, 'Leader', 0),
	(NULL, 2, 'Co-Leader', 0),
	(NULL, 3, 'Webmaster', 0),
	(NULL, 4, 'Member', 0);",false,false,true);

	//===============================================================
	//-> Server =====================================================
	//===============================================================
	db("INSERT INTO `".$db['server']."` (`id`, `status`, `shown`, `navi`, `name`, `ip`, `port`, `pwd`, `game`, `qport`) VALUES
	(NULL, 'bf2', 1, 1, 'Battlefield-Basis.de II von Hogibo.net', '80.190.178.115', 9260, '', 'bf2.gif', '');",false,false,true);
	
	//===============================================================
	//-> Server List ================================================
	//===============================================================
	db("INSERT INTO ".$db['serverliste']." (`id`, `datum`, `clanname`, `clanurl`, `ip`, `port`, `pwd`, `checked`, `slots`) VALUES
	(NULL, 1298817167, '[-tHu-] teamHanau', 'http://www.thu-clan.de', '82.98.216.10', '27015', '', 1, '17');",false,false,true);
	
	//===============================================================
	//-> Shoutbox ===================================================
	//===============================================================
	db("INSERT INTO ".$db['shout']." (`id`, `datum`, `nick`, `email`, `text`, `ip`) VALUES (NULL, 1298817167, 'deV!L', 'webmaster@dzcp.de', 'Viel Gl&uuml;ck und Erfolg mit eurem Clan!', '');",false,false,true);

	//===============================================================
	//-> Squads =====================================================
	//===============================================================
	db("INSERT INTO ".$db['squads']." (`id`, `name`, `game`, `icon`, `pos`, `shown`, `navi`, `status`, `beschreibung`, `team_show`) VALUES (NULL, 'Testsquad', 'Counter-Strike', 'cs.gif', 1, 1, 1, 1, NULL, 1);",false,false,true);

	//===============================================================
	//-> Squadusers =================================================
	//===============================================================
	db("INSERT INTO ".$db['squaduser']." (`id`, `user`, `squad`) VALUES (NULL, 1, 1)",false,false,true);

	//===============================================================
	//-> Userstats ==================================================
	//===============================================================
	db("INSERT INTO ".$db['userstats']." (`id`, `user`, `logins`, `writtenmsg`, `lastvisit`, `hits`, `votes`, `profilhits`, `forumposts`, `cws`) VALUES (NULL, 1, 0, 0, 0, 1, 0, 0, 0, 0);",false,false,true);
	
	//===============================================================				
	//-> Users ======================================================
	//===============================================================
	db("INSERT INTO `".$db['users']."` (`id`, `user`, `nick`, `pwd`, `sessid`, `country`, `ip`, `regdatum`, `email`, `icq`, `xfire`, `steamid`, `level`, `rlname`, `city`, `sex`, `bday`, `hobbys`, `motto`, `hp`, `cpu`, `ram`, `monitor`, `maus`, `mauspad`, `headset`, `board`, `os`, `graka`, `hdd`, `inet`, `signatur`, `position`, `status`, `ex`, `job`, `time`, `listck`, `online`, `nletter`, `whereami`, `drink`, `essen`, `film`, `musik`, `song`, `buch`, `autor`, `person`, `sport`, `sportler`, `auto`, `game`, `favoclan`, `spieler`, `map`, `waffe`, `rasse`, `url2`, `url3`, `beschreibung`, `gmaps_koord`, `pnmail`) VALUES
	(NULL, '".$db_infos['login']."', '".up($db_infos['nick'])."', '".md5($db_infos['pwd'])."', '', 'de', '', 0, '".$db_infos['email']."', '', '', '', '4', '', '', 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, 1, 1, '', '', ".time().", 0, 0, 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', 1);",false,false,true);
	
	//Login NOW
	if($db_infos['loginnow'])
	{
	    $_SESSION['id']         = ($userid=mysql_insert_id());
	    $_SESSION['pwd']        = md5($db_infos['pwd']);
	    $_SESSION['lastvisit']  = 0;
	    $_SESSION['ip']         = ($userip=visitorIp());
	    db("UPDATE ".$db['userstats']." SET `logins` = logins+1 WHERE user = ".$userid);
	    db("UPDATE ".$db['users']." SET `online` = '1', `sessid` = '".session_id()."', `ip` = '".$userip."' WHERE id = ".$userid);
	    wire_ipcheck("login(".$userid.")");
	}
	
	//===============================================================
	//-> Votes ======================================================
	//===============================================================
	db("INSERT INTO ".$db['votes']." (`id`, `datum`, `titel`, `intern`, `menu`, `closed`, `von`, `forum`) VALUES (NULL, ".time().", 'Wie findet ihr unsere Seite?', 0, 1, 0, 1, 0);",false,false,true);
	
	//===============================================================
	//-> Vote Möglichkeit ===========================================
	//===============================================================
	db("INSERT INTO ".$db['vote_results']." (`id`, `vid`, `what`, `sel`, `stimmen`) VALUES
	(NULL, 1, 'a1', 'Gut', 0), (2, 1, 'a2', 'Schlecht', 0);",false,false,true);
	
	//===============================================================
	//-> Navigation Kategorien ======================================
	//===============================================================
	db("INSERT INTO ".$db['navi_kats']." (`id`, `name`, `placeholder`, `level`) VALUES
	(NULL, 'Clan Navigation', 'nav_clan', 0),
	(NULL, 'Main Navigation', 'nav_main', 0),
	(NULL, 'Server Navigation', 'nav_server', 0),
	(NULL, 'Misc Navigation', 'nav_misc', 0),
	(NULL, 'Trial Navigation', 'nav_trial', 2),
	(NULL, 'Admin Navigation', 'nav_admin', 4),
	(NULL, 'User Navigation', 'nav_user', 1),
	(NULL, 'Member Navigation', 'nav_member', 3);",false,false,true);
	
	//===============================================================
	//-> Clanwars ===================================================
	//===============================================================
	db("INSERT INTO ".$db['cw']." (`id`, `squad_id`, `gametype`, `gcountry`, `matchadmins`, `lineup`, `glineup`, `datum`, `clantag`, `gegner`, `url`, `xonx`, `liga`, `punkte`, `gpunkte`, `maps`, `serverip`, `servername`, `serverpwd`, `bericht`, `top`) VALUES
	(NULL, 1, '', 'de', '', '', '', ".(time()-90000).", 'DZCP', 'deV!L`z Clanportal', 'http://www.dzcp.de', '5on5', 'DZCP', 0, 21, 'de_dzcp', '', '', '', '', 1);",false,false,true);

	//===============================================================
	//-> Clankassenkategorien =======================================
	//===============================================================
	db("INSERT INTO ".$db['c_kats']." (`id`, `kat`) VALUES
	(NULL, 'Servermiete'),
	(NULL, 'Serverbeitrag');",false,false,true);
	
	//===============================================================
	//-> Config =====================================================
	//===============================================================
	db("INSERT INTO `".$db['config']."` (`id`, `upicsize`, `gallery`, `m_usergb`, `m_clanwars`, `maxshoutarchiv`, `m_clankasse`, `m_awards`, `m_userlist`, `m_banned`, `maxwidth`, `shout_max_zeichen`, `l_servernavi`, `m_adminnews`, `m_shout`, `m_comments`, `m_archivnews`, `m_gb`, `m_fthreads`, `m_fposts`, `m_news`, `f_forum`, `l_shoutnick`, `f_gb`, `f_membergb`, `f_shout`, `f_newscom`, `f_cwcom`, `f_artikelcom`, `l_newsadmin`, `l_shouttext`, `l_newsarchiv`, `l_forumtopic`, `l_forumsubtopic`, `l_clanwars`, `m_gallerypics`, `m_lnews`, `m_topdl`, `m_ftopics`, `m_lwars`, `m_nwars`, `l_topdl`, `l_ftopics`, `l_lnews`, `l_lwars`, `l_nwars`, `l_lreg`, `m_lreg`, `m_artikel`, `m_cwcomments`, `m_adminartikel`, `securelogin`, `allowhover`, `teamrow`, `l_lartikel`, `m_lartikel`, `l_team`, `m_events`, `m_away`, `cache_teamspeak`, `cache_server`, `direct_refresh`) VALUES
	(NULL, 100, 4, 10, 10, 20, 20, 15, 40, 40, 400, 100, 22, 20, 10, 10, 30, 10, 20, 10, 5, 20, 20, 20, 20, 20, 20, 20, 20, 20, 22, 20, 20, 20, 30, 5, 6, 5, 6, 6, 6, 20, 28, 22, 12, 12, 12, 5, 15, 10, 15, ".$db_infos['loginsec'].", 1, 3, 18, 5, 7, 5, 10, 30, 30, 0);",false,false,true);
	
	//===============================================================
	//-> Sponsoren ==================================================
	//===============================================================
	db("INSERT INTO ".$db['sponsoren']." (`id`, `name`, `link`, `beschreibung`, `site`, `send`, `slink`, `banner`, `bend`, `blink`, `box`, `xend`, `xlink`, `pos`, `hits`) VALUES
	(1, 'DZCP', 'http://www.dzcp.de', '<p>deV!L''z Clanportal, das CMS for Online-Clans!</p>', 0, '', '', 0, '', '', 1, 'gif', '', 7, 0),
	(2, 'DZCP Rotationsbanner', 'http://www.dzcp.de', '<p>deV!L`z Clanportal</p>', 0, '', '', 1, '', 'http://www.dzcp.de/banner/dzcp.gif', 0, '', '', 5, 0),
	(3, 'TEMPLATEbar', 'http://www.templatebar.de', '<p>Auf TEMPLATEbar.de kannst du dir kosteng&uuml;nstige Clandesigns und/oder Templates von Top Designer erwerben.</p>', 1, '', 
	'http://www.templatebar.de/___FILES/TBbanner/tb_468x60_2.gif', 1, '', 'http://www.templatebar.de/___FILES/TBbanner/tb_468x60_2.gif', 1, '', 'http://www.templatebar.de/___FILES/TBbanner/tb_88x32.gif', 1, 0),
	(4, 'MODSbar.de', 'http://www.modsbar.de', '<p>Auf MODSbar.de kannst du dir kosteng&uuml;nstige Modifikationen und/oder Dienstleistungen von Top Codern erwerben.</p>', 1, '', 
	'http://www.templatebar.de/___FILES/MBbanner/mb_468x60.gif', 1, '', 'http://www.templatebar.de/___FILES/MBbanner/mb_468x60.gif', 1, '', 'http://www.templatebar.de/___FILES/MBbanner/mb_88x32.gif', 2, 0);",false,false,true);
	
	//===============================================================
	//-> Glossar ====================================================
	//===============================================================
	db("INSERT INTO `".$db['glossar']."` (`id`, `word`, `glossar`) VALUES
	(NULL, 'DZCP', '<p>deV!L`z Clanportal - kurz DZCP - ist ein CMS-System speziell f&uuml;r Onlinegaming Clans.</p>\r\n<p>Viele schon in der Grundinstallation vorhandene Module erleichtern die Verwaltung einer Clan-Homepage ungemein.</p>');",false,false,true);
}
?>