<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/common.php");

## SETTINGS ##
$dir = "user";
$where = _site_user;
define('_UserMenu', true);

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
                        $custom_content .= show(_profil_custom_mail, array("name" => re(pfields_name($getcustom['name'])), "value" => CryptMailto(re($getcontent[$getcustom['feldname']]),_link_mailto)));
                        break;
                    default:
                        $custom_content .= show(_profil_custom, array("name" => re(pfields_name($getcustom['name'])), "value" => re($getcontent[$getcustom['feldname']])));
                        break;
                }

                $i++;
            }
        }
    }

    return array('count' => $i, 'content' => $custom_content);
}

function getcustom($kid=1,$user=0) {
    global $db,$userid;
    if (!$kid) { return ""; }
    $set_id = ($user != 0 ? intval($user) : $userid);
    $qrycustom = db("SELECT `feldname`,`name` FROM `" . $db['profile'] . "` WHERE `kid` = " . intval($kid) . " AND `shown` = 1 ORDER BY id ASC"); $custom = "";
    while ($getcustom = _fetch($qrycustom)) {
        $getcontent = db("SELECT `" . $getcustom['feldname'] . "` FROM `" . $db['users'] . "` WHERE `id` = " . $set_id . " LIMIT 1",false,true);
        $custom .= show(_profil_edit_custom, array("name" => re(pfields_name($getcustom['name'])) . ":",
                                                   "feldname" => $getcustom['feldname'],
                                                   "value" => re($getcontent[$getcustom['feldname']])));
    }
                            
    return $custom;
}

if(file_exists(basePath."/user/case_".$action.".php"))
    require_once(basePath."/user/case_".$action.".php");

## INDEX OUTPUT ##
$whereami = preg_replace_callback("#autor_(.*?)$#",create_function('$id', 'return re(data("nick","$id[1]"));'),$where);
$title = $pagetitle." - ".$whereami."";
page($index, $title, $where);