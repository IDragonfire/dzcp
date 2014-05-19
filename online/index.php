<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$where = _site_online;
$title = $pagetitle." - ".$where."";
$dir = "online";

## SECTIONS ##
if($chkMe)
    db("UPDATE ".$db['users']." SET `time` = '".time()."', `whereami` = '".up($where)."' WHERE id = '".$userid."'");

//Users
$qry = db("SELECT id,ip,nick,whereami FROM ".$db['users']."
           WHERE time+'".$useronline."'>'".time()."'
           AND online = 1
           ".orderby_sql(array("whereami","ip"), 'ORDER BY nick'));

if(_rows($qry)) {
    while($get = _fetch($qry)) {
        if(!preg_match("#autor_#is",$get['whereami']))
            $whereami = re($get['whereami']);
        else
            $whereami =  preg_replace_callback("#autor_(.*?)$#",create_function('$id', 'return autor("$id[1]");'),$get['whereami']);

        $online_ip = '';
        if($chkMe == 4) {
            $online_ip = $get['ip'];
            $DNS = db("SELECT dns FROM `".$db['ip2dns']."` WHERE `ip` = '".$online_ip."';",false,true);
            $online_host = ($gethostbyaddr=$DNS['dns']);
            $online_ip = ' * '.($get['ip'] == $gethostbyaddr ? $online_ip : $online_ip.' ('.$online_host.')');
        }

        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $show .= show($dir."/online_show", array("nick" => autor($get['id']).$online_ip,
                                                 "whereami" => $whereami,
                                                 "class" => $class));
    }
}

//Gast
$qry = db("SELECT * FROM ".$db['c_who']."
           WHERE online+'".$useronline."'>'".time()."'
           AND login = 0
           ".orderby_sql(array("whereami","ip"), 'ORDER BY whereami'));

if(_rows($qry)) {
    while($get = _fetch($qry)) {
        if(!preg_match("#autor_#is",$get['whereami'])) $whereami = re($get['whereami']);
        else $whereami = preg_replace_callback("#autor_(.*?)$#",create_function('$id', 'return autor("$id[1]");'),$get['whereami']);

        if($chkMe == 4) {
            $online_ip = $get['ip'];
            $DNS = db("SELECT dns FROM `".$db['ip2dns']."` WHERE `ip` = '".$online_ip."';",false,true);
            $online_host = ($gethostbyaddr=$DNS['dns']);
        } else {
            $online_ip = preg_replace("#^(.*)\.(.*)#","$1",$get['ip']);
            $DNS = db("SELECT dns FROM `".$db['ip2dns']."` WHERE `ip` = '".$get['ip']."';",false,true);
            $online_host = preg_replace("#^(.*?)\.(.*)#","$2",($gethostbyaddr=$DNS['dns']));
        }

        $online_ip = ($get['ip'] == $gethostbyaddr ? $online_ip.'.XX' : $online_ip.'.XX (*.'.$online_host.')');
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $show .= show($dir."/online_show", array("nick" => $online_ip,
                                                 "whereami" => $whereami,
                                                 "class" => $class));
    }
}

$index = show($dir."/online", array("show" => $show,
                                    "head" => _online_head,
                                    "user" => _status_user.'/'._server_ip,
                                    "order_user" => orderby('ip'),
                                    "order_where" => orderby('whereami'),
                                    "where" => _online_whereami));

## INDEX OUTPUT ##
page($index, $title, $where);