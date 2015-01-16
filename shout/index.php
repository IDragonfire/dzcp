<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/common.php");

## SETTINGS ##
$dir = "shout";

## SECTIONS ##
switch ($action):
default:
  $securimage->namespace = 'menu_shout';
  if(!ipcheck("shout", config('f_shout')))
  {
    if(($_POST['protect'] != 'nospam' || !$securimage->check($_POST['secure']) || empty($_POST['spam'])) && !$userid)
        $index = error(html_entity_decode(_error_invalid_regcode, ENT_COMPAT | ENT_HTML401,'ISO-8859-1'),1);
    elseif(!$userid && (empty($_POST['name']) || trim($_POST['name']) == '') || $_POST['name'] == "Nick")
        $index = error(_empty_nick, 1);
    elseif(!$userid && empty($_POST['email']) || $_POST['email'] == "E-Mail")
        $index = error(_empty_email, 1);
    elseif(!$userid && !check_email($_POST['email']))
        $index = error(_error_invalid_email, 1);
    elseif(empty($_POST['eintrag']))
        $index = error(_error_empty_shout, 1);
    elseif(settings('reg_shout') && !$chkMe)                       $index = error(_error_unregistered, 1);
    else {
      if(!$userid) $reg = $_POST['email'];
      else $reg = $userid;

      $qry = db("INSERT INTO ".$db['shout']."
                 SET `datum`  = '".time()."',
                     `nick`   = '".up($_POST['name'])."',
                     `email`  = '".up($reg)."',
                     `text`   = '".up(substr(str_replace("\n", ' ', $_POST['eintrag']),0,config('shout_max_zeichen')))."',
                     `ip`     = '".$userip."'");

      setIpcheck("shout");
      if(!isset($_GET['ajax'])) header("Location: ".$_SERVER['HTTP_REFERER'].'#shoutbox');
    }
  } else {
    $index = error(show(_error_flood_post, array("sek" => config('f_shout'))), 1);
  }

  if(isset($_GET['ajax'])) {
    echo str_replace("\n", '', html_entity_decode(strip_tags($index)));

    if(!mysqli_persistconns)
        $mysql->close(); //MySQL

    exit();
  }
break;
case 'admin';
  if(!permission("shoutbox"))
  {
    $index = error(_error_wrong_permissions, 1);
  } else {
    if($do == "delete")
    {
      $qry = db("DELETE FROM ".$db['shout']."
                 WHERE id = '".intval($_GET['id'])."'");

      header("Location: ".$_SERVER['HTTP_REFERER'].'#shoutbox');
    }
  }
break;
case 'archiv';
  $where = _site_shoutbox;
  $entrys = cnt($db['shout']);
  $i = $entrys-($page - 1)*config('maxshoutarchiv');

  $qry = db("SELECT * FROM ".$db['shout']."
             ORDER BY datum DESC
             LIMIT ".($page - 1)*config('maxshoutarchiv').",".config('maxshoutarchiv')."");
  while($get = _fetch($qry))
  {
    $is_num = preg_match("#\d#", re($get['email']));

    if($is_num && !check_email(re($get['email']))) $nick = autor(re($get['email']));
    else $nick = '<a href="mailto:'.re($get['email']).'" title="'.$get['nick'].'">'.cut($get['nick'], config('l_shoutnick')).'</a>';

    $class = ($color % 2) ? "contentMainTop" : "contentMainFirst"; $color++;

    if(permission("shoutbox"))
    {
      $del = "<a href='../shout/?action=admin&amp;do=delete&amp;id=".$get['id']."'>
              <img src='../inc/images/delete_small.gif' border='0' alt=''></a>";
    } else {
      $del = "";
    }

    if($chkMe == "4") $posted_ip = $get['ip'];
    else $posted_ip = _logged;

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
  $nav = nav($entrys,config('maxshoutarchiv'),"?action=archiv");
  $index = show($dir."/shout", array("shout_part" => $show,
                                     "head" => _shout_archiv_head,
                                     "nav" => $nav));
break;
endswitch;

## INDEX OUTPUT ##
$title = $pagetitle." - ".$where;
page($index, $title, $where);