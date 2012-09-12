<?php
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
$show = "";

## SECTIONS ##
$check = db("SELECT s1.user FROM ".$db['permissions']." s1, ".$db['users']." s2 WHERE s1.user = '".$userid."' AND s2.id = '".intval($userid)."' AND s2.pwd = '".$_SESSION['pwd']."'");

if(!admin_perms($_SESSION['id']))
    $index = error(_error_wrong_permissions, 1);
else 
{
    define('_adminMenu', true);
    $settingsmenu = ""; $contentmenu = ""; $rootmenu = ""; $wysiwyg = false;
    $radmin1 = ''; $radmin2 = ''; $adminc1 = ''; $adminc2 = '';
    
    if(isset($_GET['admin']))
    {
        if(file_exists(basePath.'/admin/menu/'.strtolower($_GET['admin']).'.php'))
            include(basePath.'/admin/menu/'.strtolower($_GET['admin']).'.php');
    }
    
    //Site Permissions
    $check = db("SELECT * FROM ".$db['permissions']." WHERE user = '".intval($userid)."'",false,true);
    
    $amenu = array();
    $files = get_files(basePath.'/admin/menu/',false,true,array('php'));
    foreach($files AS $file)
    {
        $nav = file(basePath.'/admin/menu/'.$file);
        $navType = trim(str_replace('// Typ:', '', $nav[2]));
        $navPerm = trim(str_replace('// Rechte:', '', $nav[3]));
        
        $file = str_replace('.php', '', $file);
        @eval("\$link = _config_".$file.";");
        @eval("\$permission = ".$navPerm.";");
            
        foreach($picformat AS $end)
        {
            if(file_exists(basePath.'/admin/menu/'.$file.'.'.$end))
                break;
        }
    
        if(!empty($navType) && !empty($navPerm) && $permission)
            $amenu[$navType][$link] = show("['[link]','?admin=[name]','background-image:url(menu/[name].".$end.");'],\n", array("link" => $link, 'name' => $file));
        
        $file = null;
    }
    
    foreach($amenu AS $m => $k)
    {
        natcasesort($k);
        foreach($k AS $l) $$m .= $l;
    }
    
    if(empty($rootmenu))
    {
        $radmin1 = '/*'; $radmin2 = '*/';
    }
    
    if(empty($settingsmenu))
    {
        $adminc1 = '/*'; $adminc2 = '*/';
    }
    
    $dzcp_version = show_dzcp_version();
    $index = show($dir."/admin", array( "head" => _config_head,
                                        "version" => $dzcp_version['version'],
                                        "old" => $dzcp_version['old'],
                                        "dbase" => _stats_mysql,
                                        "einst" => _config_einst,
                                        "content" => _content,
                                        "newsticker" => show_dzcp_news(),
                                        "rootadmin" => _rootadmin,
                                        "rootmenu" => $rootmenu,
                                        "settingsmenu" => $settingsmenu,
                                        "contentmenu" => $contentmenu,
                                        "radmin1" => $radmin1,
                                        "radmin2" => $radmin2,
                                        "adminc1" => $adminc1,
                                        "adminc2" => $adminc2,
                                        "show" => $show));
}

## SETTINGS ##
$time_end = generatetime();
$time = round($time_end - $time_start,4);
$title = $pagetitle." - ".$where."";
page($index, $title, $where ,$time,$wysiwyg);

## OUTPUT BUFFER END ##
gz_output();
?>