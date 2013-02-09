<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath . "/inc/config.php");
include(basePath . "/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$where = _site_online;
$title = $pagetitle . " - " . $where . "";
$dir   = "online";
## SECTIONS ##

$qry = db("SELECT id,nick,whereami FROM " . $db['users'] . "
             WHERE time+'" . $useronline . "'>'" . time() . "'
             AND online = 1
             ORDER BY nick");
while ($get = _fetch($qry)) {
    if (!preg_match("#autor_#is", $get['whereami']))
        $whereami = re($get['whereami']);
    else
        $whereami = preg_replace_callback("#autor_(.*?)$#", create_function('$id', 'return autor("$id[1]");'), $get['whereami']);
    
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst";
    $color++;
    $show .= show($dir . "/online_show", array(
        "nick" => autor($get['id']),
        "whereami" => $whereami,
        "class" => $class
    ));
}

$qry = db("SELECT * FROM " . $db['c_who'] . "
             WHERE online+'" . $useronline . "'>'" . time() . "'
             AND login = 0
             ORDER BY whereami");
while ($get = _fetch($qry)) {
    if (!preg_match("#autor_#is", $get['whereami']))
        $whereami = re($get['whereami']);
    else
        $whereami = preg_replace_callback("#autor_(.*?)$#", create_function('$id', 'return autor("$id[1]");'), $get['whereami']);
    
    $online_ip   = preg_replace("#^(.*)\.(.*)#", "$1", $get['ip']);
    $online_host = preg_replace("#^(.*?)\.(.*)#", "$2", gethostbyaddr($get['ip']));
    $online_ip   = $online_ip . '.XX (*.' . $online_host . ')';
    
    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst";
    $color++;
    $show .= show($dir . "/online_show", array(
        "nick" => $online_ip,
        "whereami" => $whereami,
        "class" => $class
    ));
}

$index = show($dir . "/online", array(
    "show" => $show,
    "head" => _online_head,
    "user" => _status_user . '/' . _server_ip,
    "where" => _online_whereami
));

## SETTINGS ##
$time_end = generatetime();
$time     = round($time_end - $time_start, 4);
page($index, $title, $where, $time);
## OUTPUT BUFFER END ##
gz_output();
?>