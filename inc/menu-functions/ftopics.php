<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 * Menu: Forum Topics
 */
function ftopics() {
    global $db;

    $qry = db("SELECT s1.*,s2.id AS subid FROM ".$db['f_threads']." s1, ".$db['f_skats']." s2, ".$db['f_kats']." s3
               WHERE s1.kid = s2.id AND s2.sid = s3.id ORDER BY s1.lp DESC LIMIT 100");

    $f = 0; $ftopics = '';
    if(_rows($qry)) {
        while($get = _fetch($qry)) {
            if($f == config('m_ftopics'))  break;

            if(fintern($get['kid']))
            {
                $lp = cnt($db['f_posts'], " WHERE `sid` = '".$get['id']."'");
                $pagenr = ceil($lp/config('m_ftopics'));

                if($pagenr == 0) $page = 1;
                else $page = $pagenr;

                if(config('allowhover') == 1)
                $info = 'onmouseover="DZCP.showInfo(\''.jsconvert(re($get['topic'])).'\', \''._forum_posts.';'._forum_lpost.'\', \''.$lp.';'.date("d.m.Y H:i", $get['lp'])._uhr.'\')" onmouseout="DZCP.hideInfo()"';

                $ftopics .= show("menu/forum_topics", array("id" => $get['id'],
                                                            "pagenr" => $page,
                                                            "p" => $lp +1,
                                                            "titel" => cut(re($get['topic']),config('l_ftopics')),
                                                            "info" => $info,
                                                            "kid" => $get['kid']));
                $f++;
            }
        }
    }

    return empty($ftopics) ? '' : '<table class="navContent" cellspacing="0">'.$ftopics.'</table>';
}