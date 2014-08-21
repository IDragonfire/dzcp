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
$dir = "sites";

## SECTIONS ##
switch ($action):
default:
  $qry = db("SELECT s1.*,s2.internal FROM ".$db['sites']." AS s1
             LEFT JOIN ".$db['navi']." AS s2
             ON s1.id = s2.editor
             WHERE s1.id = '".intval($_GET['show'])."'");
  $get = _fetch($qry);

  if(_rows($qry))
  {
    if($get['internal'] == 1 && ($chkMe == 1 || !$chkMe))
      $index = error(_error_wrong_permissions, 1);
    else {
      $where = re($get['titel']);
      $title = $pagetitle." - ".$where."";

      if($get['html'] == "1") $inhalt = bbcode_html($get['text']);
      else $inhalt = bbcode($get['text']);

      $index = show($dir."/sites", array("titel" => re($get['titel']),
                                         "inhalt" => $inhalt));
    }
  } else $index = error(_sites_not_available,1);
break;
case 'preview';
  header("Content-type: text/html; charset=utf-8");
  if($_POST['html'] == "1") $inhalt = bbcode_html(re($_POST['inhalt']),1);
  else $inhalt = bbcode(re($_POST['inhalt']),true);

  $index = show($dir."/sites", array("titel" => re($_POST['titel']),
                                     "inhalt" => $inhalt));

  echo utf8_encode('<table class="mainContent" cellspacing="1"'.$index.'</table>');

  if(!mysqli_persistconns)
      $mysql->close(); //MySQL

  exit();
break;
endswitch;
## INDEX OUTPUT ##
page($index, $title, $where);