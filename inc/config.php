<?php
## REQUIRES ##
require_once(basePath."/inc/mysql.php");

//DZCP-Install default variable
if(!isset($installation))
$installation = false;

function show($tpl, $array)
{
  global $tmpdir;
    $template = "../inc/_templates_/".$tmpdir."/".$tpl;
  
    if($fp = @fopen($template.".".html, "r"))
      $tpl = @fread($fp, filesize($template.".".html));
    
    $array['dir'] = '../inc/_templates_/'.$tmpdir;
    foreach($array as $value => $code)
    {
      $tpl = str_replace('['.$value.']', $code, $tpl);
    }
  return $tpl;
}

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
            "vote_results" =>   $prefix."vote_results"
            );

if($db['host'] != '' && $db['user'] != '' && $db['pass'] != '' && $db['db'] != '')
{
	if(!$msql = mysql_connect($db['host'],$db['user'],$db['pass'])) die("<b>Fehler beim Zugriff auf die Datenbank!");
	if(!mysql_select_db($db['db'],$msql)) die("<b>Die angegebene Datenbank <i>".$db['db']."</i> existiert nicht!");
}

function db($db)
{
  global $prefix;
  if(!$qry = mysql_query($db)) die('<b>MySQL-Query failed:</b><br /><br /><ul>'.
                                   '<li><b>ErrorNo</b> = '.str_replace($prefix,'',mysql_errno()).
                                   '<li><b>Error</b>   = '.str_replace($prefix,'',mysql_error()).
                                   '<li><b>Query</b>   = '.str_replace($prefix,'',$db).'</ul>');
  return $qry;
}
?>