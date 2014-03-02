<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 * Menu: Sponsors
 */
function sponsors() {
    global $db;

    $qry = db("SELECT `id`,`xlink`,`xend`,`link` FROM ".$db['sponsoren']." WHERE `box` = 1 ORDER BY pos");
    $sponsors = '';
    if(_rows($qry)) {
        while($get = _fetch($qry))
        {
            $banner = show(_sponsors_bannerlink, array("id" => $get['id'],
                                                     "title" => htmlspecialchars(str_replace('http://', '', re($get['link']))),
                                                     "banner" => (empty($get['xlink']) ? "../banner/sponsors/box_".$get['id'].".".$get['xend'] : re($get['xlink']))));

            $sponsors .= show("menu/sponsors", array("banner" => $banner));
        }
    }

    return empty($sponsors) ? '' : '<table class="navContent" cellspacing="0">'.$sponsors.'</table>';
}