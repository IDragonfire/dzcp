<?php
$versions[3] = array('update_id' => 3, 3 => '1.5.5.x', "version_list" => 'v1.5.5.x', 'call' => '155x_1600', 'dbv' => false); //Update Info

//Update von V1.5.5.x auf V1.6.0.0
function install_155x_1600_update()
{
	global $db, $prefix;
	
	db("ALTER TABLE `".$db['f_threads']."` CHANGE `edited` `edited` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
	db("ALTER TABLE `".$db['users']."` CHANGE `whereami` `whereami` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
	db("ALTER TABLE `".$db['users']."` CHANGE `hlswid` `xfire` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''",false,false,true);
	db("ALTER TABLE `".$db['downloads']."` ADD `last_dl` INT( 20 ) NOT NULL DEFAULT '0' AFTER `date`",false,false,true);
	db("ALTER TABLE `".$db['settings']."` CHANGE `i_autor` `i_autor` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
	db("ALTER TABLE `".$db['gb']."` CHANGE `hp` `hp` VARCHAR(130) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
	db("ALTER TABLE `".$db['permissions']."` ADD `gs_showpw` INT(1) NOT NULL DEFAULT '0'",false,false,true);
	db("ALTER TABLE `".$db['settings']."` ADD `urls_linked` INT(1) NOT NULL DEFAULT '1', ADD `ts_customicon` INT(1) NOT NULL DEFAULT '1' AFTER `ts_version`, ADD `ts_showchannel` INT(1) NOT NULL DEFAULT '0' AFTER `ts_customicon`",false,false,true);
	db("ALTER TABLE `".$db['msg']."` CHANGE `see_u` `see_u` INT( 1 ) NOT NULL DEFAULT '0'",false,false,true);
	db("ALTER TABLE `".$db['msg']."` CHANGE `page` `page` INT( 11 ) NOT NULL DEFAULT '0'",false,false,true);
	db("ALTER TABLE `".$db['away']."` CHANGE `lastedit` `lastedit` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
	db("ALTER TABLE `".$db['settings']."` DROP `pfad`",false,false,true);
	db("ALTER TABLE `".$db['newskat']."` CHANGE `katimg` `katimg` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''",false,false,true);
	db("ALTER TABLE `".$db['newskat']."` CHANGE `kategorie` `kategorie` VARCHAR( 60 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''",false,false,true);
	db("ALTER TABLE `".$db['server']."` CHANGE `name` `name` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL",false,false,true);
	db("ALTER TABLE `".$db['server']."` DROP `bl_file`, DROP `bl_path`, DROP `ftp_pwd`, DROP `ftp_login`, DROP `ftp_host`;",false,false,true);
	db("ALTER TABLE `".$db['serverliste']."` CHANGE `clanname` `clanname` VARCHAR( 50 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''",false,false,true);
	db("ALTER TABLE `".$db['serverliste']."` CHANGE `datum` `datum` INT( 11 ) NOT NULL DEFAULT '0'",false,false,true);
	db("ALTER TABLE `".$db['settings']."` ADD `db_version` VARCHAR( 5 ) NOT NULL DEFAULT '00000'");
	
	// Add UNIQUE INDEX
	if(db("SELECT id FROM `".$db['config']."`",true) >= 2)
	{
	    $get_old = db("SELECT * FROM `".$db['config']."` LIMIT 0 , 1",false,true);
	    db("TRUNCATE TABLE `".$db['config']."`",false,false,true);
	    db("ALTER TABLE `".$db['config']."` ADD UNIQUE (`id`)",false,false,true);
        $count = count($get_old); $i = 1; $set = '';
        foreach ($get_old as $key => $var)
        {
            $i++;
            if($i <= $count)
                $set .= $key." = '".$var."', ";
            else
                $set .= $key." = '".$var."';";
        }
	    
	    db("INSERT INTO `".$db['config']."` SET ".$set,false,false,true);
	}
	else
	    db("ALTER TABLE `".$db['config']."` ADD UNIQUE (`id`)",false,false,true);
	
	// Add UNIQUE INDEX
	if(db("SELECT id FROM `".$db['settings']."`",true) >= 2)
	{
	    $get_old = db("SELECT * FROM `".$db['settings']."` LIMIT 0 , 1",false,true);
	    db("TRUNCATE TABLE `".$db['settings']."`",false,false,true);
	    db("ALTER TABLE `".$db['settings']."` ADD UNIQUE (`id`)",false,false,true);
	    $count = count($get_old); $i = 1; $set = '';
	    foreach ($get_old as $key => $var)
	    {
	        $i++;
	        if($i <= $count)
	            $set .= $key." = '".$var."', ";
	        else
	            $set .= $key." = '".$var."';";
	    }
	     
	    db("INSERT INTO `".$db['settings']."` SET ".$set,false,false,true);
	}
	else
	    db("ALTER TABLE `".$db['settings']."` ADD UNIQUE (`id`)",false,false,true);
	
	// Schreibe DB Version in Datenbank
	db("UPDATE ".$db['settings']." SET `db_version` = '1600' WHERE id = 1",false,false,true);
	
	// Lösche dzcp_banned Tabelle
	db("DROP TABLE `".$prefix."banned"."`",false,false,true);
	
	//-> Forum Sortieren
	db("ALTER TABLE ".$db['f_skats']." ADD `pos` int(5) NOT NULL",false,false,true);
	
	//-> Forum Sortieren funktion: schreibe id von spalte in pos feld um konflikte zu vermeiden!
	$qry = db("SELECT id FROM `".$db['f_skats']."`");
	if(_rows($qry) >= 1)
	{  while($get = _fetch($qry)) { db("UPDATE ".$db['f_skats']." SET `pos` = '".$get['id']."' WHERE `id` = '".$get['id']."';",false,false,true); } }
	
    //===============================================================
    //-> Addons =====================================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['addons']."`;");
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
    //-> Click IP Counter ===========================================
    //===============================================================
    db("DROP TABLE IF EXISTS `".$db['clicks_ips']."`;");
    db("CREATE TABLE IF NOT EXISTS `".$db['clicks_ips']."` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`ip` varchar(15) NOT NULL DEFAULT '000.000.000.000',
	`uid` int(11) NOT NULL DEFAULT '0',
	`ids` int(11) NOT NULL DEFAULT '0',
	`side` varchar(30) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`),
	KEY `ip` (`ip`)
	) ".get_db_engine($_SESSION['mysql_dbengine'])." DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;",false,false,true);

	return true;
}
?>