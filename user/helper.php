<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

function custom_content($kid=1) {
    global $db;
    $custom_content = ''; $i = 0;
    $qrycustom = db("SELECT * FROM ".$db['profile']." WHERE kid = '".intval($kid)."' AND shown = '1' ORDER BY id ASC");
    if(_rows($qrycustom) >= 1) {
        while($getcustom = _fetch($qrycustom)) {
            $getcontent = db("SELECT ".$getcustom['feldname']." FROM ".$db['users']." WHERE id = '".intval($_GET['id'])."' LIMIT 1",false,true);
            if(!empty($getcontent[$getcustom['feldname']])) {
                switch($getcustom['type']) {
                    case 2:
                        $custom_content .= show(_profil_custom_url, array("name" => re(pfields_name($getcustom['name'])), "value" => re($getcontent[$getcustom['feldname']])));
                        break;
                    case 3:
                        $custom_content .= show(_profil_custom_mail, array("name" => re(pfields_name($getcustom['name'])), "value" => eMailAddr(re($getcontent[$getcustom['feldname']]))));
                        break;
                    default:
                        $custom_content .= show(_profil_custom, array("name" => re(pfields_name($getcustom['name'])), "value" => re($getcontent[$getcustom['feldname']])));
                        break;
                }
            }

            $i++;
        }
    }

    return array('count' => $i, 'content' => $custom_content);
}