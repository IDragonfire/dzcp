<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 * Menu: Rotationsbanner
 */
function rotationsbanner() {
    global $db;

    $qry = db("SELECT `id`,`link`,`bend`,`blink` FROM ".$db['sponsoren']." WHERE `banner` = 1 ORDER BY RAND() LIMIT 1");
    $rotationbanner = '';
    if(_rows($qry)) {
        while($get = _fetch($qry)) {
          $rotationbanner .= show(_sponsors_bannerlink, array("id" => $get['id'],
                                                              "title" => htmlspecialchars(str_replace('http://', '', re($get['link']))),
                                                              "banner" => (empty($get['blink']) ? "../banner/sponsors/banner_".$get['id'].".".$get['bend'] : re($get['blink']))));
        }
    }

    return empty($rotationbanner) ? '' : $rotationbanner;
}