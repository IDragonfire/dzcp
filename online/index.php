<?php
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
    db("UPDATE ".$db['users']." SET `time` = '".((int)time())."', `whereami` = '".up($where)."' WHERE id = '".$userid."'");

//Users
if(!empty($_GET['orderby']) && in_array($_GET['orderby'],array("whereami","ip"))) {
    $qry = db("SELECT id,ip,nick,whereami FROM ".$db['users']."
               WHERE time+'".$useronline."'>'".time()."'
               AND online = 1
               ORDER BY ".mysqli_real_escape_string($mysql, $_GET['orderby']." ".$_GET['order'])."");
} else {
    $qry = db("SELECT id,ip,nick,whereami FROM ".$db['users']."
               WHERE time+'".$useronline."'>'".time()."'
               AND online = 1
               ORDER BY nick");
}

if(_rows($qry)) {
    while($get = _fetch($qry)) {
        if(!preg_match("#autor_#is",$get['whereami']))
            $whereami = re($get['whereami']);
        else
            $whereami =  preg_replace_callback("#autor_(.*?)$#",create_function('$id', 'return autor("$id[1]");'),$get['whereami']);

        $online_ip = '';
        if($chkMe == 4) {
            $online_ip = $get['ip'];
            $online_host = ($gethostbyaddr=gethostbyaddr($get['ip']));
            $online_ip = ' * '.($get['ip'] == $gethostbyaddr ? $online_ip : $online_ip.' ('.$online_host.')');
        }

        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $show .= show($dir."/online_show", array("nick" => autor($get['id']).$online_ip,
                                                 "whereami" => $whereami,
                                                 "class" => $class));
    }
}

//Gast
if(!empty($_GET['orderby']) && in_array($_GET['orderby'],array("whereami","ip"))) {
    $qry = db("SELECT * FROM ".$db['c_who']."
               WHERE online+'".$useronline."'>'".time()."'
               AND login = 0
               ORDER BY ".mysqli_real_escape_string($mysql, $_GET['orderby']." ".$_GET['order'])."");
} else {
    $qry = db("SELECT * FROM ".$db['c_who']."
               WHERE online+'".$useronline."'>'".time()."'
               AND login = 0
               ORDER BY whereami");
}

if(_rows($qry)) {
    while($get = _fetch($qry)) {
        if(!preg_match("#autor_#is",$get['whereami'])) $whereami = re($get['whereami']);
        else $whereami = preg_replace_callback("#autor_(.*?)$#",create_function('$id', 'return autor("$id[1]");'),$get['whereami']);

        if($chkMe == 4) {
            $online_ip = $get['ip'];
            $online_host = ($gethostbyaddr=gethostbyaddr($get['ip']));
        } else {
            $online_ip = preg_replace("#^(.*)\.(.*)#","$1",$get['ip']);
            $online_host = preg_replace("#^(.*?)\.(.*)#","$2",($gethostbyaddr=gethostbyaddr($get['ip'])));
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

## OUTPUT BUFFER END ##
gz_output();