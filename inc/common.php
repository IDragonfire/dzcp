<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

require_once(basePath."/inc/debugger.php");
require_once(basePath."/inc/config.php");

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
            "botlist" =>        $prefix."botlist",
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
            "startpage" =>      $prefix."startpage",
            "taktik" =>         $prefix."taktiken",
            "ts" =>             $prefix."teamspeak",
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
                $tpl = re($cache->get('tpl_'.$cacheHash));
                if(show_dbc_debug)
                    DebugConsole::insert_info('template::show()', 'Get Template-Cache: "'.'tpl_'.$cacheHash.'"');
            }
            else {
                if(file_exists($template.".html")) {
                    $tpl = file_get_contents($template.".html");

                    if(template_cache && $config_cache['use_cache'] && dbc_index::useMem()) {
                        $cache->set('tpl_'.$cacheHash,up($tpl),template_cache_time);

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
        $array['idir'] = '../inc/images';
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

require_once(basePath."/inc/database.php");
require_once(basePath."/inc/bbcode.php");