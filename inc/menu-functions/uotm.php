<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 * Menu: User of the Moment
 */
function uotm() {
    global $db,$picformat;
    $files = get_files(basePath.'/inc/images/uploads/userpics',false,true,$picformat,false,array(),'minimize'); $uotm = '';
    if(count($files) >= 1 && $files) {
        shuffle($files);
        $userid = intval($files[mt_rand(0, count($files) - 1)]);
        $qry = db("SELECT `id`,`bday` FROM ".$db['users']." WHERE `id` = '".$userid."'");
        if(_rows($qry)) {
            $get = _fetch($qry);
            if(config('allowhover') == 1)
                $info = 'onmouseover="DZCP.showInfo(\''.fabo_autor($get['id']).'\', \''._age.'\', \''.getAge($get['bday']).'\', \''.hoveruserpic($get['id']).'\')" onmouseout="DZCP.hideInfo()"';


            $uotm = show("menu/uotm", array("uid" => $userid,
                                            "upic" => userpic($get['id'], 130, 161),
                                            "info" => $info));
        }
    }

    return empty($uotm) ? '' : '<table class="navContent" cellspacing="0">'.$uotm.'</table>';
}
