<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1 Final
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;

$where = $where.': '._backup_head;
$version = str_replace(" ","_",_version);
$file_name = 'backup_dzcp_v.'.$version.'_'._build.'_'.date("d.m.y").'.sql';
if(file_exists($file_name)) {
    header('Cache-Control:  must-revalidate, post-check=0, pre-check=0');
    header("Content-type: application/txt");
    header('Content-Length: '.filesize($file_name));
    header("Content-Disposition: attachment; filename=".$file_name);
    readfile($file_name);
    @unlink($file_name);

    if(!mysqli_persistconns)
        $mysql->close(); //MySQL

    exit();
}

$show = show($dir."/backup", array("head" => _backup_head,
                                   "backup" => _backup_link,
                                   "info_head" => _backup_info_head,
                                   "lastbackup" => _backup_last_head,
                                   "info" => _backup_info));
if($do == "backup") {
    file_put_contents($file_name,sql_backup());
    die();
    header("Location: ?admin=backup");
}