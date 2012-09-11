<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
$ajaxJob = true;
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
## FUNCTIONS ##
require_once(basePath."/inc/menu-functions/server.php");
require_once(basePath."/inc/menu-functions/shout.php");
require_once(basePath."/inc/menu-functions/teamspeak.php");
require_once(basePath."/inc/menu-functions/kalender.php");
require_once(basePath."/inc/menu-functions/team.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$dir = "sites";
## SECTIONS ##
if(!isset($_GET['i'])) $action = "";
else $action = $_GET['i'];

switch ($action):
  case 'kalender';
    echo kalender($_GET['month'],$_GET['year']);
  break;
  case 'teams';
    echo team($_GET['tID']);
  break;
  case 'server';
    echo '<table class="hperc" cellspacing="0">'.server($_GET['serverID']).'</table>';
  break;
  case 'shoutbox';
    echo '<table class="hperc" cellspacing="1">'.shout(1).'</table>';
  break;
  case 'teamspeak';
    echo '<table class="hperc" cellspacing="0">'.teamspeak().'</table>';
  break;
  case 'xfire';
      echo xfire($_GET['username']);
  break;
endswitch;
?>