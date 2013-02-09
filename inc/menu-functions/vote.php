<?php
//-> Votemenu
function vote($ajax = false)
{
    global $db, $balken_vote_menu, $prev;
    $qry = db("SELECT * FROM " . $db['votes'] . " WHERE menu = '1' AND forum = 0");
    $get = _fetch($qry);
    
    if (_rows($qry)) {
        $qryv = db("SELECT * FROM " . $db['vote_results'] . " WHERE vid = '" . $get['id'] . "' ORDER BY what");
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
                    $results .= show("menu/vote_results", array(
                        "answer" => re($getv['sel']),
                        "percent" => $percent,
                        "stimmen" => $getv['stimmen'],
                        "balken" => $balken
                    ));
                } else {
                    $votebutton = '<input id="contentSubmitVote" type="submit" value="' . _button_value_vote . '" class="voteSubmit" />';
                    $results .= show("menu/vote_vote", array(
                        "id" => $getv['id'],
                        "answer" => re($getv['sel'])
                    ));
                }
            } else {
                $votebutton = '<input id="contentSubmitVote" type="submit" value="' . _button_value_vote . '" class="voteSubmit" />';
                $results .= show("menu/vote_vote", array(
                    "id" => $getv['id'],
                    "answer" => re($getv['sel'])
                ));
            }
        }
        
        $vote = show("menu/vote", array(
            "titel" => re($get['titel']),
            "vid" => $get['id'],
            "results" => $results,
            "votebutton" => $votebutton,
            "stimmen" => $stimmen
        ));
    }
    
    return empty($vote) ? '<center style="margin:2px 0">' . _vote_menu_no_vote . '</center>' : ($ajax ? $vote : '<div id="navVote">' . $vote . '</div>');
}

?>
