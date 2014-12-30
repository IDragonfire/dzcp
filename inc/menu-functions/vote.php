<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 * Menu: Votes
 */
function vote($ajax = false) {
    global $db;

    $qry = db("SELECT `id`,`closed`,`titel` FROM ".$db['votes']." WHERE `menu` = '1' AND `forum` = 0"); $vote = '';
    if(_rows($qry)) {
        $get = _fetch($qry);

        $qryv = db("SELECT `id`,`stimmen`,`sel` FROM ".$db['vote_results']." WHERE `vid` = '".$get['id']."' ORDER BY what");
        $results = '';
        while ($getv = _fetch($qryv)) {
            $ipcheck = !count_clicks('vote',$get['id'],0,false);
            $stimmen = sum($db['vote_results'], " WHERE `vid` = '".$get['id']."'", "stimmen");
            if($stimmen != 0) {
                if($ipcheck || cookie::get('vid_'.$get['id']) != false || $get['closed'] == 1) {
                    $percent = round($getv['stimmen']/$stimmen*100,1);
                    $rawpercent = round($getv['stimmen']/$stimmen*100,0);

                    $balken = show(_votes_balken, array("width" => $rawpercent));

                    $votebutton = "";
                    $results .= show("menu/vote_results", array("answer" => re($getv['sel']),
                                                                "percent" => $percent,
                                                                "stimmen" => $getv['stimmen'],
                                                                "balken" => $balken));
                } else {
                    $votebutton = '<input id="contentSubmitVote" type="submit" value="'._button_value_vote.'" class="voteSubmit" />';
                    $results .= show("menu/vote_vote", array("id" => $getv['id'], "answer" => re($getv['sel'])));
                }
            } else {
                $votebutton = '<input id="contentSubmitVote" type="submit" value="'._button_value_vote.'" class="voteSubmit" />';
                $results .= show("menu/vote_vote", array("id" => $getv['id'], "answer" => re($getv['sel'])));
            }
        }

        $vote = show("menu/vote", array("titel" => re($get['titel']),
                                        "vid" => $get['id'],
                                        "results" => $results,
                                        "votebutton" => $votebutton,
                                        "stimmen" => $stimmen));
    }

    return empty($vote) ? '<center style="margin:2px 0">'._vote_menu_no_vote.'</center>' : ($ajax ? $vote : '<div id="navVote">'.$vote.'</div>');
}