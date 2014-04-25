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
$where = _side_membermap;
$dir = "membermap";

## SECTIONS ##
$level = settings('gmaps_who');
if(!($level == 0 || $level == 1)) {
    $level = 0;
}

$mm_qry = db('SELECT u.`id`, u.`nick`, u.`city`, u.`gmaps_koord` FROM ' .  $db['users'] .
             ' u WHERE u.`gmaps_koord` != "" AND u.`level` > ' . $level . ' ORDER BY u.gmaps_koord, u.id');

$mm_coords = ''; $mm_infos = "'<tr>"; $mm_markerIcon = '';$mm_lastCoord = ''; $i = 0; $mm_users = '';
$realCount = 0;$markerCount = 0;$userListPic = '';$userListName = ''; $userListRank = '';$userListCity = '';

while($mm_get = _fetch($mm_qry)) {
    if($mm_lastCoord != $mm_get['gmaps_koord']) {
        if($i > 0) {
            $mm_coords .= ',';
            $mm_infos .= "</tr>','<tr>";
        }

        $mm_infos .= '<td><b style="font-size:13px">&nbsp;'.re($mm_get['city']).'</td></tr><tr>';
        $mm_coords .= 'new google.maps.LatLng' . $mm_get['gmaps_koord'];
        $realCount++;
    } else {
        if($markerCount > 0) {
            $mm_markerIcon .= ',';
        }

        $mm_markerIcon .= ($realCount - 1) . ':true';
        $markerCount++;
    }

    $userInfos = '<b>'.rawautor($mm_get['id']).'</b><br /><b>'._position.
    ':</b> '.getrank($mm_get['id']).'<br />'.userpic($mm_get['id']);
    $mm_infos .= '<td><div id="memberMapInner">' . $userInfos . '</div></td>';
    $mm_lastCoord = $mm_get['gmaps_koord'];

    $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
    $mm_users .= show($dir.'/membermap_users',array('id' => $mm_get['id'],
                                                    'userListPic' => userpic($mm_get['id'],40,50),
                                                    'userListName' => autor($mm_get['id']),
                                                    'userListRank' => getrank($mm_get['id']),
                                                    'userListCity' => re($mm_get['city']),
                                                    'class' => $class));
    $i++;
}

$mm_infos .= "</tr>'";
$index = show($dir."/membermap", array('mm_coords' => $mm_coords,
                                       'mm_infos' => $mm_infos,
                                       'membermapusers' => $mm_users,
                                       'mm_markerIcon' => $mm_markerIcon));
## INDEX OUTPUT ##
$title = $pagetitle." - ".$where."";
page($index, $title, $where);

## OUTPUT BUFFER END ##
gz_output();