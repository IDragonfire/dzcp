<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$time_start = generatetime();
lang($language);
$dir = "shout";

## SECTIONS ##
switch (isset($_GET['action']) ? $_GET['action'] : ''):
default:
    if(!ipcheck("shout", $flood_shout))
    {
        if(($_POST['protect'] != 'nospam' || empty($_SESSION['sec_shout']) || $_POST['spam'] != $_SESSION['sec_shout'] || empty($_POST['spam'])) && !isset($userid))
            $index = error(_error_invalid_regcode,1); 
        else if(!isset($userid) && (empty($_POST['name']) || trim($_POST['name']) == '') || $_POST['name'] == "Nick")                                                                       
            $index = error(_empty_nick, 1);
        else if(!isset($userid) && empty($_POST['email']) || $_POST['email'] == "E-Mail") 
            $index = error(_empty_email, 1);
        else if(!isset($userid) && !check_email($_POST['email']))                         
            $index = error(_error_invalid_email, 1);
        else if(empty($_POST['eintrag']))                                                 
            $index = error(_error_empty_shout, 1);
        else if(settings('reg_shout') == 1 && $chkMe == 'unlogged')                       
            $index = error(_error_unregistered, 1);
        else 
        {
            $reg = (!isset($userid) ? $_POST['email'] : $userid);
            db("INSERT INTO ".$db['shout']." SET 
                `datum`  = '".((int)time())."',
                `nick`   = '".up($_POST['name'],'','UTF-8')."',
                `email`  = '".up($reg,'','UTF-8')."',
                `text`   = '".up(substr(str_replace("\n", ' ', $_POST['eintrag']),0,$shout_max_zeichen),'','UTF-8')."',
                `ip`     = '".visitorIp()."'");

             wire_ipcheck('shout');

            if(!isset($_GET['ajax'])) 
                header("Location: ".$_SERVER['HTTP_REFERER'].'#shoutbox');
        }
    }
    else
        $index = error(show(_error_flood_post, array("sek" => $flood_shout)), 1);
  
    if(isset($_GET['ajax']))
    {
        echo str_replace("\n", '', html_entity_decode(strip_tags($index)));
        exit();
    }
break;
case 'admin';
    if(!permission("shoutbox"))
        $index = error(_error_wrong_permissions, 1);
    else 
    {
        if(isset($_GET['do']) ? ($_GET['do'] == "delete") : false)
        {
            db("DELETE FROM ".$db['shout']." WHERE id = '".intval($_GET['id'])."'");
            header("Location: ".$_SERVER['HTTP_REFERER'].'#shoutbox');
        }
    }
break;
case 'archiv';
    $where = _site_shoutbox;
    $title = $pagetitle." - ".$where."";
    $page = (isset($_GET['page']) ? intval($_GET['page']) : 1);
    $entrys = cnt($db['shout']);
    $i = $entrys-($page - 1)*$maxshoutarchiv;

    $show = ''; $color = 1;
    $qry = db("SELECT * FROM ".$db['shout']." ORDER BY datum DESC LIMIT ".($page - 1)*$maxshoutarchiv.",".$maxshoutarchiv."");
    while($get = _fetch($qry))
    {
        $is_num = preg_match("#\d#", $get['email']);
        if($is_num && !check_email($get['email'])) 
            $nick = autor($get['email']);
        else 
            $nick = '<a href="mailto:'.$get['email'].'" title="'.$get['nick'].'">'.cut($get['nick'], $lshoutnick).'</a>';

        $class = ($color % 2) ? "contentMainTop" : "contentMainFirst"; $color++;
        $del = (permission("shoutbox") ? "<a href='../shout/?action=admin&amp;do=delete&amp;id=".$get['id']."'><img src='../inc/images/delete_small.gif' border='0' alt=''></a>" : "");
        $posted_ip = ($chkMe == 4 ? $get['ip'] : _logged);
        
        $show .= show($dir."/shout_part", array("nick" => $nick,
                                                "datum" => date("j.m.Y H:i", $get['datum'])._uhr,
                                                "text" => bbcode($get['text']),
                                                "class" => $class,
                                                "del" => $del,
                                                "ip" => $posted_ip,
                                                "id" => $i,
                                                "email" => re($get['email'])));
        $i--;
    }
    
    $nav = nav($entrys,$maxshoutarchiv,"?action=archiv");
    $index = show($dir."/shout", array("shout_part" => $show, "head" => _shout_archiv_head, "nav" => $nav));
break;
endswitch;

## SETTINGS ##
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where,$time);

## OUTPUT BUFFER END ##
gz_output();
?>