<?php
## REQUIRES ##
require_once(basePath."/inc/mysql.php");

//DZCP-Install default variable
if(!isset($installation))
    $installation = false;

//DZCP Settings
define('is_debug', false);
define('cache_in_debug', false);

define('buffer_gzip_compress', true);
define('buffer_gzip_compress_level', 4);

define('dzcp_newsticker', true);
define('dzcp_newsticker_refresh', (15*60));

define('dzcp_version_checker', true);
define('dzcp_version_checker_refresh', (30*60));

define('cache_gzip_compress', true);
define('cache_gzip_compress_level', 2);

define('xfire_preloader', true);
define('xfire_skin', 'shadow'); //shadow,kampf,scifi,fantasy,wow,default
define('xfire_refresh', (10*60));

$picformat = array("jpg", "gif", "png");
$passwordComponents = array("ABCDEFGHIJKLMNOPQRSTUVWXYZ","abcdefghijklmnopqrstuvwxyz","0123456789","#$@!");

//MySQL Settings
define('sql_autodetect_mysqli', true);
define('sql_use_mysqli', true);

//-> MySQL-Datenbankangaben
$prefix = $sql_prefix;
$db = array("host" =>           $sql_host,
            "user" =>           $sql_user,
            "pass" =>           $sql_pass,
            "db" =>             $sql_db,
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
            "taktik" =>         $prefix."taktiken",
            "users" =>          $prefix."users", 
            "usergallery" =>    $prefix."usergallery",
            "usergb" =>         $prefix."usergb",
            "userpos" =>        $prefix."userposis",    
            "userstats" =>      $prefix."userstats",
            "versions" =>       $prefix."versions",
            "votes" =>          $prefix."votes",
            "vote_results" =>   $prefix."vote_results",
            'mods' =>           $prefix.'mods'
            );
?>