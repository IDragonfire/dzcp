<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$time_start = generatetime();
lang($language);
$where = _site_config;
$dir = "admin";
$rootmenu = null;
$settingsmenu = null;
$contentmenu = null;
$amenu = array();
$wysiwyg = false;
$use_glossar = false;

## SECTIONS ##
$check = db("SELECT s1.user FROM ".$db['permissions']." s1, ".$db['users']." s2
             WHERE s1.user = '".$userid."'
             AND s2.id = '".intval($userid)."'
             AND s2.pwd = '".$_SESSION['pwd']."'");

if(!admin_perms($_SESSION['id']))
    $index = error(_error_wrong_permissions, 1);
else {
    if(isset($_GET['admin']) && file_exists(basePath.'/admin/menu/'.strtolower($_GET['admin']).'.php') &&
                                file_exists(basePath.'/admin/menu/'.strtolower($_GET['admin']).'.xml')) {
        $permission = false; define('_adminMenu', true);
        $xml = simplexml_load_file(basePath.'/admin/menu/'.strtolower($_GET['admin']).'.xml');
        $rights = (string)$xml->Rights; $oa = (int)$xml->Only_Admin; $ora = (int)$xml->Only_Root;
        if(permission($rights) && !$oa && !$ora) $permission = true;
        if($oa && !$ora && $chkMe == 4) $permission = true;
        if($ora && $chkMe == 4 && userid() == $rootAdmin) $permission = true;

        if($permission)
            include(basePath.'/admin/menu/'.strtolower($_GET['admin']).'.php');
        else
            $show = error(_error_wrong_permissions, 1);
    }

    //Site Permissions
    $files = get_files(basePath.'/admin/menu/',false,true,array('xml'));
    if(count($files)) {
        foreach($files AS $file_xml) {
            if(file_exists(basePath.'/admin/menu/'.str_replace('.xml','.php',$file_xml))) {
                $permission = false;
                $xml = simplexml_load_file(basePath.'/admin/menu/'.$file_xml);
                $rights = (string)$xml->Rights; $oa = (int)$xml->Only_Admin; $ora = (int)$xml->Only_Root;
                if(permission($rights) && !$oa && !$ora) $permission = true;
                if($oa && !$ora && $chkMe == 4) $permission = true;
                if($ora && $chkMe == 4 && userid() == $rootAdmin) $permission = true;

                foreach($picformat AS $end) {
                    if(file_exists(basePath.'/admin/menu/'.str_replace('.xml','',$file_xml).'.'.$end))
                        break;
                }

                $link = constant("_config_".str_replace('.xml','',$file_xml));
                $menu = (string)$xml->Menu; $type = str_replace('.xml','',$file_xml);
                if(!empty($menu) && !empty($rights) && $permission)
                    $amenu[$menu][$type] = show("['[link]','?admin=[name]','background-image:url(menu/[name].".$end.");'],\n", array("link" => $link, 'name' => $type));
            }
        }
    }

    foreach($amenu AS $m => $k) {
        natcasesort($k);
        foreach($k AS $l) $$m .= $l;
    }

    $radmin1 = ''; $radmin2 = '';
    if(empty($rootmenu)) {
        $radmin1 = '/*'; $radmin2 = '*/';
    }

    $adminc1 = ''; $adminc2 = '';
    if(empty($settingsmenu)) {
        $adminc1 = '/*'; $adminc2 = '*/';
    }

    $cdminc1 = ''; $cdminc2 = '';
    if(empty($contentmenu)) {
        $cdminc1 = '/*'; $cdminc2 = '*/';
    }

    $version = '<b>'._akt_version.': '._version.'</b>'; $dzcp_news = '';
    if(fsockopen_support()) {
        if($cache->check("admin_version")) {
            $dzcp_v = fileExists("http://www.dzcp.de/version.txt");

            if(!empty($dzcp_v))
                $cache->set("admin_version", $dzcp_v, 1200);
            else
                $dzcp_v = false;
        }
        else
            $dzcp_v = $cache->get("admin_version");

        if($dzcp_v && $dzcp_v <= _version) {
            $version = '<b>'._akt_version.': <span class="fontGreen">'._version.'</span></b>';
            $old = "";
        } else {
            $version = "<a href=\"http://www.dzcp.de\" target=\"_blank\" title=\"external Link: www.dzcp.de\"><b>"._akt_version.":</b> <span class=\"fontRed\">"._version."</span></a>";
            $old = "_old";
        }

        if($cache->check("admin_news")) {
            $dzcp_news = fileExists("http://www.dzcp.de/dzcp_news.php");
            if(!empty($dzcp_news))
                $cache->set("admin_news", $dzcp_news, 1200);
            else
                $dzcp_news = false;
        }
        else
            $dzcp_news = $cache->get("admin_news");
    }

    if(@file_exists(basePath."/_installer") && $chkMe == 4 && !view_error_reporting)
        $index = _installdir;
    else {
        $index = show($dir."/admin", array("head" => _config_head,
                                           "version" => $version,
                                           "old" => $old,
                                           "dbase" => _stats_mysql,
                                           "einst" => _config_einst,
                                           "content" => _content,
                                           "newsticker" => '<div style="padding:3px">'.(empty($dzcp_news) ? '' : '<b>DZCP News:</b><br />').'<div id="dzcpticker">'.$dzcp_news.'</div></div>',
                                           "rootadmin" => _rootadmin,
                                           "rootmenu" => $rootmenu,
                                           "settingsmenu" => $settingsmenu,
                                           "contentmenu" => $contentmenu,
                                           "radmin1" => $radmin1,
                                           "radmin2" => $radmin2,
                                           "adminc1" => $adminc1,
                                           "adminc2" => $adminc2,
                                           "cdminc1" => $cdminc1,
                                           "cdminc2" => $cdminc2,
                                           "show" => $show));
    }
}

## SETTINGS ##
$time_end = generatetime();
$time = round($time_end - $time_start,4);
$title = $pagetitle." - ".$where."";
page($index, $title, $where ,$time,$wysiwyg);

## OUTPUT BUFFER END ##
gz_output();