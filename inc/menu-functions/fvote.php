<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 * Menu: Forum Vote
 */
function fvote($id, $ajax=false) {
    global $db;

    $qry = db("SELECT `id`,`closed`,`titel` FROM ".$db['votes']." WHERE `id` = '".$id."' ".(permission("votes") ? "" : " AND `intern` = 0")."");
    if(_rows($qry)) {
        $get = _fetch($qry); $results = ''; $votebutton = '';
        $qryv = db("SELECT `id`,`stimmen`,`sel` FROM ".$db['vote_results']." WHERE `vid` = '".$get['id']."' ORDER BY id ASC");
        if(_rows($qryv)) {
            while($getv = _fetch($qryv)) {
                $stimmen = sum($db['vote_results'], " WHERE `vid` = '".$get['id']."'", "stimmen");
                if($stimmen != 0) {
                    if(ipcheck("vid_".$get['id']) || cookie::get('vid_'.$get['id']) != false || $get['closed'] == 1) {
                        $percent = round($getv['stimmen']/$stimmen*100,1);
                        $rawpercent = round($getv['stimmen']/$stimmen*100,0);
                        $balken = show(_votes_balken, array("width" => $rawpercent));

                        $votebutton = "";
                        $results .= show("forum/vote_results", array("answer" => re($getv['sel']),
                                                                     "percent" => $percent,
                                                                     "stimmen" => $getv['stimmen'],
                                                                     "balken" => $balken));
                    } else {
                        $votebutton = '<input id="contentSubmitFVote" type="submit" value="'._button_value_vote.'" class="voteSubmit" />';
                        $results .= show("forum/vote_vote", array("id" => $getv['id'], "answer" => re($getv['sel'])));
                    }
                } else {
                    $votebutton = '<input id="contentSubmitFVote" type="submit" value="'._button_value_vote.'" class="voteSubmit" />';
                    $results .= show("forum/vote_vote", array("id" => $getv['id'], "answer" => re($getv['sel'])));
                }
            }
        }

        $getf = db("SELECT `id`,`kid` FROM ".$db['f_threads']." WHERE `vote` = '".$get['id']."'",false,true);
        $vote = show("forum/vote", array("titel" => re($get['titel']),
                                         "vid" => $get['id'],
                                         "fid" => $getf['id'],
                                         "kid" => $getf['kid'],
                                         "umfrage" => _forum_vote,
                                         "results" => $results,
                                         "votebutton" => $votebutton,
                                         "stimmen" => $stimmen));
    }

    return empty($vote) ? '<center style="margin:2px 0">'._no_entrys.'</center>' : ($ajax ? $vote : '<div id="navFVote">'.$vote.'</div>');
}