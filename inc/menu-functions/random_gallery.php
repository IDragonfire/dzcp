<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 * Menu: Zufallsgalerie
 */
function random_gallery() {
    global $db,$picformat;

    $gallery = '';
    $files = get_files(basePath.'/gallery/images/',false,true,$picformat,false,array(),'minimize');
    if(count($files) >= 1) {
        $get = db("SELECT `id`,`kat` FROM ".$db['gallery']." ORDER BY RAND()",false,true);

        $imgArr = array();
        foreach($files AS $file) {
            if(intval($file) == $get['id'])
                array_push($imgArr, $file);
        }

        shuffle($imgArr);
        if(!empty($imgArr[0])) {
            $gallery = show("menu/random_gallery", array("image" => $imgArr[0],
                                                         "id"    => $get['id'],
                                                         "kat"   => re($get['kat'])));
        }
    }

    return empty($gallery) ? '<center style="margin:2px 0">'._no_entrys.'</center>' : '<table class="navContent" cellspacing="0">'.$gallery.'</table>';
}