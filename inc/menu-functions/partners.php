<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 * Menu: Partners
 */
function partners() {
    global $db;

    $qry = db("SELECT `textlink`,`link`,`banner` FROM ".$db['partners']." ORDER BY `textlink` ASC");
    $partners = '';
    if(_rows($qry)) {
        while($get = _fetch($qry)) {
            if($get['textlink']) {
                $partners .= show("menu/partners_textlink", array("link" => $get['link'],
                                                                  "name" => re($get['banner'])));
            } else {
                $partners .= show("menu/partners", array("link" => re($get['link']),
                                                         "title" => htmlspecialchars(str_replace('http://', '', re($get['link']))),
                                                         "banner" => re($get['banner'])));
            }

            $table = strstr($partners, '<tr>') ? true : false;
        }
    }

    return empty($partners) ? '<center style="margin:2px 0">'._no_entrys.'</center>' : ($table ? '<table class="navContent" cellspacing="0">'.$partners.'</table>' : $partners);
}