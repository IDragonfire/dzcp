<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

if(defined('_Votes')) {
    $get = db("SELECT `intern`,`id` FROM `".$db['votes']."` WHERE id = ".intval($_GET['id']).";",false,true);
    if(!$get['intern'] || $chkMe >= 1) {
        $qryv = db("SELECT user_id,time FROM ".$db['ipcheck']." WHERE what = 'vid_".$get['id']."' ORDER BY time DESC");
        while($getv = _fetch($qryv)) {
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $show .= show($dir."/voted_show", array("user" => autor($getv['user_id']),
                                                    "date" => date("d.m.y H:i",$getv['time'])._uhr,
                                                    "class" => $class));
        }

        if(empty($show))
            $show = show(_no_entrys_yet, array("colspan" => "2"));

        $index = show($dir."/voted", array("head" => _voted_head,
                                           "user" => _user,
                                           "date" => _datum,
                                           "show" => $show));
    }
    else
        $index = error(_error_vote_show,1);
}