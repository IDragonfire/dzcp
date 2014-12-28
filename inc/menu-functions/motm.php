<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 * Menu: Member of the Moment
 */
function motm() {
    global $db,$picformat;
    $userpics = get_files(basePath.'/inc/images/uploads/userpics/',false,true,$picformat,false,array(),'minimize'); $member = '';
    if($userpics && count($userpics) >= 1) {
        $qry = db("SELECT `id` FROM ".$db['users']." WHERE level >= 2");
        $a = 0; $temparr = array();
        if(_rows($qry)) {
            while($rs = _fetch($qry)) {
                foreach($userpics AS $userpic) {
                    $tmpId = intval($userpic);
                    if($tmpId == $rs['id']) {
                        $temparr[] = $rs['id'];
                        $a++;
                        break;
                    }
                }
            }

            $arrayID = mt_rand(0, count($temparr) - 1);
            $uid = $temparr[$arrayID];

            $get = db("SELECT `id`,`level`,`status`,`bday` FROM ".$db['users']." WHERE id = '".$uid."'",false,true);
            if(!empty($get) && !empty($temparr)) {
                $status = ($get['status'] == 1 || $get['level'] == 1) ? "aktiv" : "inaktiv";

                if(config('allowhover') == 1)
                    $info = 'onmouseover="DZCP.showInfo(\''.fabo_autor($get['id']).'\', \''._posi.';'._status.';'._age.'\', \''.getrank($get['id']).';'.$status.';'.getAge($get['bday']).'\', \''.hoveruserpic($get['id']).'\')" onmouseout="DZCP.hideInfo()"';

                $member = show("menu/motm", array("uid" => $get['id'],
                                                  "upic" => userpic($get['id'], 130, 161),
                                                  "info" => $info));
            }
        }
    }

    return empty($member) ? '<center style="margin:2px 0">'._no_entrys.'</center>' : '<table class="navContent" cellspacing="0">'.$member.'</table>';
}