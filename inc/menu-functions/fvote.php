<?php
//-> Forum Vote
function fvote($id, $ajax = false) {
    global $db, $balken_vote_menu, $prev;
    
    if (!permission("votes"))
        $intern = ' AND intern = 0';
    $qry = db("SELECT * FROM " . $db['votes'] . "  WHERE id = '" . $id . "' " . $intern . "");
    $get = _fetch($qry);
    
    if (_rows($qry)) {
        $qryv = db("SELECT * FROM " . $db['vote_results'] . " WHERE vid = '" . $get['id'] . "' ORDER BY id ASC");
        while ($getv = _fetch($qryv)) {
            $stimmen = sum($db['vote_results'], " WHERE vid = '" . $get['id'] . "'", "stimmen");
            
            if ($stimmen != 0) {
                if (ipcheck("vid_" . $get['id']) || isset($_COOKIE[$prev . "vid_" . $get['id']]) || $get['closed'] == 1) {
                    $percent    = round($getv['stimmen'] / $stimmen * 100, 1);
                    $rawpercent = round($getv['stimmen'] / $stimmen * 100, 0);
                    
                    $balken = show(_votes_balken, array(
                        "width" => $rawpercent
                    ));
                    
                    $votebutton = "";
                    $results .= show("forum/vote_results", array(
                        "answer" => re($getv['sel']),
                        "percent" => $percent,
                        "stimmen" => $getv['stimmen'],
                        "balken" => $balken
                    ));
                } else {
                    $votebutton = '<input id="contentSubmitFVote" type="submit" value="' . _button_value_vote . '" class="voteSubmit" />';
                    $results .= show("forum/vote_vote", array(
                        "id" => $getv['id'],
                        "answer" => re($getv['sel'])
                    ));
                }
            } else {
                $votebutton = '<input id="contentSubmitFVote" type="submit" value="' . _button_value_vote . '" class="voteSubmit" />';
                $results .= show("forum/vote_vote", array(
                    "id" => $getv['id'],
                    "answer" => re($getv['sel'])
                ));
            }
        }
        
        $qryf = db("SELECT id,kid FROM " . $db['f_threads'] . " WHERE vote = '" . $get['id'] . "'");
        $getf = _fetch($qryf);
        
        $vote = show("forum/vote", array(
            "titel" => re($get['titel']),
            "vid" => $get['id'],
            "fid" => $getf['id'],
            "kid" => $getf['kid'],
            "umfrage" => _forum_vote,
            "results" => $results,
            "votebutton" => $votebutton,
            "stimmen" => $stimmen
        ));
    }
    
    return empty($vote) ? '' : ($ajax ? $vote : '<div id="navFVote">' . $vote . '</div>');
}
?>
