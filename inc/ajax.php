<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START #
define('basePath', dirname(dirname(__FILE__).'../'));
ob_start();
    $ajaxJob = true;

    ## INCLUDES ##
    include(basePath."/inc/debugger.php");
    include(basePath."/inc/config.php");
    include(basePath."/inc/bbcode.php");

    ## FUNCTIONS ##
    require_once(basePath."/inc/menu-functions/server.php");
    require_once(basePath."/inc/menu-functions/shout.php");
    require_once(basePath."/inc/menu-functions/teamspeak.php");
    require_once(basePath."/inc/menu-functions/kalender.php");
    require_once(basePath."/inc/menu-functions/team.php");

    ## SETTINGS ##
    $dir = "sites";

    ## SECTIONS ##
    switch (isset($_GET['i']) ? $_GET['i'] : ''):
        case 'kalender';  echo kalender($_GET['month'],$_GET['year']); break;
        case 'teams';     echo team($_GET['tID']); break;
        case 'server';    echo '<table class="hperc" cellspacing="0">'.server($_GET['serverID']).'</table>'; break;
        case 'shoutbox';  echo '<table class="hperc" cellspacing="1">'.shout(1).'</table>'; break;
        case 'teamspeak'; echo '<table class="hperc" cellspacing="0">'.teamspeak(1).'</table>'; break;
        case 'steam';
            $data = steamIMG($_GET['steam_id']);
            if($data['send_header'])
               header('Content-Type: image/png');

            echo $data['img'];
        break;
    endswitch;

    if(!mysqli_persistconns)
        $mysql->close(); //MySQL

ob_end_flush();