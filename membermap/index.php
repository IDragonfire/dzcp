<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$where = 'Mitgliederkarte';
$dir = "membermap";
## SECTIONS ##
 
$mm_qry = db('SELECT u.`id`, u.`nick`, u.`city`, u.`gmaps_koord` FROM ' .  $db['users'] . ' u WHERE u.`gmaps_koord` != ""');
$mm_coords = '';
$mm_infos = '';
$i = 0;

while($mm_get = _fetch($mm_qry)) {
    if($i > 0) {
        $mm_coords .= ',';
        $mm_infos .= ',';
    }
    #TODO: use re function 
    $mm_coords .= 'new google.maps.LatLng' . $mm_get['gmaps_koord'];
    $userInfos = '<td><div id="memberMapInner"><b>' . rawautor($mm_get['id']).'</b><br /><b>' . _position . 
                   ':</b> ' . getrank($mm_get['id']) . '<br />' . userpic($mm_get['id']) . '</div></td>';
    $tmp = '<tr><td><b style="font-size:13px">&nbsp;' . re($mm_get['city']) . '</b></td></tr><tr>' . $userInfos . '</tr>';
    $mm_infos .= "'" . $tmp . "'";
    $i++;
}

$index = show($dir."/membermap", array('head' => _membermap,
                                     'mm_coords' => $mm_coords,
                                     'mm_infos' => $mm_infos
                                    ));
## SETTINGS ##
$title = $pagetitle." - ".$where."";
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where,$time);
## OUTPUT BUFFER END ##
gz_output();
?>