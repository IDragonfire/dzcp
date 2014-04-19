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
$where = _site_serverlist;
$title = $pagetitle." - ".$where."";
$dir = "serverliste";

## SECTIONS ##
switch ($action):
default:
  if(!empty($_GET['orderby']) && in_array($_GET['orderby'],array("clanname","slots"))) {
  $qry = db("SELECT ip,port,clanname,clanurl,pwd,checked,slots
                   FROM ".$db['serverliste']."
                   WHERE checked = 1
                   ORDER BY ".mysqli_real_escape_string($mysql, $_GET['orderby']." ".$_GET['order'])."");
  }
  else{
  $qry = db("SELECT ip,port,clanname,clanurl,pwd,checked,slots
             FROM ".$db['serverliste']."
             WHERE checked = 1");
  }
  if(_rows($qry))
  {
    while ($get = _fetch($qry))
    {
      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
      $serverlist .= show($dir."/serverliste_show", array("aktplayers" => $aktplayers,
                                                          "maxplayers" => $maxplayers,
                                                          "clanurl" => re($get['clanurl']),
                                                          "slots" => $get['slots'],
                                                          "class" => $class,
                                                          "serverip" => $get['ip'],
                                                          "serverport" => $get['port'],
                                                          "clanname" => re($get['clanname']),
                                                          "serverpwd" => re($get['pwd']),
                                                          "map" => $map));
    }
  } else {
        $orderby = empty($_GET['orderby']) ? "" : "&orderby".$_GET['orderby'];
        $orderby .= empty($_GET['order']) ? "" : "&order=".$_GET['order'];
        $serverlist = show(_no_entrys_yet, array("colspan" => "4").$_GET['show']."".$orderby);

    #$serverlist = show(_no_entrys_yet, array("colspan" => "4"));
  }

  $index = show($dir."/serverliste", array("serverlist" => $serverlist,
                                           "slist_head" => _slist_head,
                                           "clan" => _profil_clan,
                                           "serverip" => _slist_serverip,
                                           "slots" => _slist_slots,
                                           "pwd" => _pwd,
                                           "eintragen" => _slist_add,
                                           "order_clan" => orderby('clanname'),
                                           "order_slots" => orderby('slots'),
                                           "hlswip" => _gt_addip));
break;
case 'add':
  $index = show($dir."/add", array("add_head" => _slist_add,
                                   "clan" => _profil_clan,
                                   "hp" => _profil_hp,
                                   "what" => "slist",
                                   "security" => _register_confirm,
                                   "serverpasswort" => _server_password,
                                   "serverip" => _slist_serverip,
                                   "serverport" => _slist_serverport,
                                   "value" => _button_value_add,
                                   "slots" => _slist_slots,
                                   "serverpassword" => _server_password));

break;
case 'addserver':
  if($_POST['secure'] != $_SESSION['sec_slist'] || empty($_SESSION['sec_slist']))
    $index = error(_error_invalid_regcode,1);
  elseif(empty($_POST['clanname']))
    $index = error(_error_empty_clanname, 1);
  elseif(empty($_POST['ip']))
    $index = error(_error_empty_ip, 1);
  elseif(empty($_POST['port']))
    $index = error(_error_empty_port, 1);
  elseif(empty($_POST['slots']))
    $index = error(_error_empty_slots, 1);
  else {
    $msg = _slist_added_msg;
    $title = _slist_title;
    $send = db("INSERT INTO ".$db['msg']."
                SET `datum`     = '".((int)time())."',
                    `von`       = '0',
                    `an`        = '1',
                    `titel`     = '".up($title)."',
                    `nachricht` = '".up($msg)."'");

    $insert = db("INSERT INTO ".$db['serverliste']."
                  SET `datum`     = '".((int)time())."',
                      `clanname`  = '".up($_POST['clanname'])."',
                      `clanurl`   = '".links($_POST['clanurl'])."',
                      `ip`        = '".up($_POST['ip'])."',
                      `port`      = '".((int)$_POST['port'])."',
                      `pwd`       = '".up($_POST['pwd'])."',
                      `slots`     = '".((int)$_POST['slots'])."'");

    $index = info(_error_server_saved, "../serverliste/");
  }
break;
endswitch;

## INDEX OUTPUT ##
page($index, $title, $where);

## OUTPUT BUFFER END ##
gz_output();