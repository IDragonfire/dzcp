<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;

$m = parsePHPInfo();

$support = "#####################\r\n";
$support .= "# Support Informationen\r\n";
$support .= "#####################\r\n";
$support .= "# Allgemein\r\n";
$support .= "#########\r\n";
$support .= " DZCP Version: "._version."\r\n";
$support .= " DZCP Release: "._release."\r\n";
$support .= " DZCP Build: "._build."\r\n";
$support .= " Domain: http://".$_SERVER['HTTP_HOST'].str_replace('/admin','/',dirname($_SERVER['PHP_SELF']))."\r\n";
$support .= " System/Browser: ".$_SERVER['HTTP_USER_AGENT']."\r\n";
$support .= "#########\r\n";
$support .= "# Versionen\r\n";
$support .= "#########\r\n";
$support .= " Server OS: ".@php_uname()."\r\n";
$support .= " Apache Version: ".$m['apache2handler']['Apache Version']."\r\n";
$support .= " PHP-Version: ".phpversion()."\r\n";
$support .= " MySQL-Version: ".mysqli_get_server_info($mysql)."\r\n";
$support .= "#########\r\n";
$support .= "# Servereinstellungen\r\n";
$support .= "#########\r\n";
$support .= " fopen(): ".(function_exists('fopen')==true?'On':'Off')."\r\n";
$support .= " fsockopen(): ".(fsockopen_support() ? 'On':'Off')."\r\n";
$support .= " allow_url_fopen: ".$m['Core']['allow_url_fopen'][0]."\r\n";
$support .= " register_globals: ".$m['Core']['register_globals'][0]."\r\n";
$support .= " safe_mode: ".$m['Core']['safe_mode'][0]."\r\n";
$support .= " safe_mode_exec_dir: ".$m['Core']['safe_mode_exec_dir'][0]."\r\n";
$support .= " safe_mode_gid: ".$m['Core']['safe_mode_gid'][0]."\r\n";
$support .= " safe_mode_include_dir: ".$m['Core']['safe_mode_include_dir'][0]."\r\n";
$support .= " open_basedir: ".$m['Core']['open_basedir'][0]."\r\n";
$support .= " GD-Version: ".$m['gd']['GD Version']."\r\n";
$support .= " imagettftext(): ".(function_exists('imagettftext')==true?'exists':'don\'t exists')."\r\n";
$support .= " HTTP_ACCEPT_ENCODING: ".$_SERVER['HTTP_ACCEPT_ENCODING']."\r\n";
$support .= " magic_quotes_gpc: ".$m['Core']['magic_quotes_gpc'][0]."\r\n";
$support .= " file_uploads: ".$m['Core']['file_uploads'][0]."\r\n";
$support .= " upload_max_filesize: ".$m['Core']['upload_max_filesize'][0]."\r\n";
$support .= " sendmail_from: ".$m['Core']['sendmail_from'][0]."\r\n";
$support .= " sendmail_path: ".$m['Core']['sendmail_path'][0];

    $show = show($dir."/support", array("info" => _admin_support_info,
                                        "head" => _admin_support_head,
                                        "support" => txtArea($support)
                                        ));