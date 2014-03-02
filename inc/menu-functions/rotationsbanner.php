<?php
//-> Menu: Rotationsbanner
function rotationsbanner() {
    global $db;

    $qry = db("SELECT * FROM ".$db['sponsoren']." WHERE banner = 1 ORDER BY RAND() LIMIT 1");
    $rotationbanner = '';
    while($get = _fetch($qry)) {
      $rotationbanner .= show(_sponsors_bannerlink, array("id" => $get['id'],
                                                          "title" => htmlspecialchars(str_replace('http://', '', re($get['link']))),
                                                          "banner" => (empty($get['blink']) ? "../banner/sponsors/banner_".$get['id'].".".$get['bend'] : re($get['blink']))));
    }

    return empty($rotationbanner) ? '' : $rotationbanner;
}