<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1 Final
 * http://www.dzcp.de
 */

if(defined('_Clanwars')) {
    $sum_punkte = sum($db['cw'], '', 'punkte');
    $sum_gpunkte = sum($db['cw'], '', 'gpunkte');
    $anz_ges_points = show(_cw_stats_ges_points, array("ges_won" => $sum_punkte, "ges_lost" => $sum_gpunkte));

    if(cnt($db['cw'], " WHERE datum < ".time()."") != "0") {
        $anz_wo_wars = cnt($db['cw'], " WHERE punkte > gpunkte");
        $anz_lo_wars = cnt($db['cw'], " WHERE punkte < gpunkte");
        $anz_dr_wars = cnt($db['cw'], " WHERE datum < ".time()." && punkte = gpunkte");
        $anz_ge_wars = cnt($db['cw'], " WHERE datum < ".time()."");

        $wo_percent = @round($anz_wo_wars*100/$anz_ge_wars, 1);
        $lo_percent = @round($anz_lo_wars*100/$anz_ge_wars, 1);
        $dr_percent = @round($anz_dr_wars*100/$anz_ge_wars, 1);

        $wo_rawpercent = @round($anz_wo_wars*100/$anz_ge_wars, 0);
        $lo_rawpercent = @round($anz_lo_wars*100/$anz_ge_wars, 0);
        $dr_rawpercent = @round($anz_dr_wars*100/$anz_ge_wars, 0);

        if($anz_wo_wars != "0") $wo_balken = show(_votes_balken, array("width" => $wo_rawpercent));
        else                    $wo_balken = show(_votes_balken, array("width" => 1));

        if($anz_lo_wars != "0") $lo_balken = show(_votes_balken, array("width" => $lo_rawpercent));
        else                    $lo_balken = show(_votes_balken, array("width" => 1));

        if($anz_dr_wars != "0") $dr_balken = show(_votes_balken, array("width" => $dr_rawpercent));
        else                    $dr_balken = show(_votes_balken, array("width" => 1));
    }

    $anz_ges_wars = show(_cw_stats_ges_wars, array("ge_wars" => $anz_ge_wars));
    $stats_all = show($dir."/stats", array("wo_wars" => $anz_wo_wars,
                                           "lo_wars" => $anz_lo_wars,
                                           "dr_wars" => $anz_dr_wars,
                                           "dr_percent" => $dr_percent,
                                           "lo_percent" => $lo_percent,
                                           "ges_punkte" => _cw_gespunkte,
                                           "wo_percent" => $wo_percent,
                                           "won_icon" => _cw_stats_won_icon,
                                           "lost_icon" => _cw_stats_lost_icon,
                                           "draw_icon" => _cw_stats_draw_icon,
                                           "won_balken" => $wo_balken,
                                           "lost_balken" => $lo_balken,
                                           "draw_balken" => $dr_balken,
                                           "head_stat" => _cw_head_statstik,
                                           "won_stat" => _cw_stats_won_head,
                                           "lost_stat" => _cw_stats_lost_head,
                                           "draw_stat" => _cw_stats_draw_head,
                                           "ges_wars" => $anz_ges_wars,
                                           "ges_points" => $anz_ges_points));

    $qry = db("SELECT * FROM `".$db['squads']."` WHERE `status` = 1 ORDER BY `pos`;");
    while($get = _fetch($qry)) {
        if(isset($_GET['showsquad']) && $_GET['showsquad'] == $get['id'] ||
           isset($_GET['show']) && $_GET['show'] == $get['id']) {
            $shown = show(_klapptext_show, array("id" => $get['id']));
                $display = "";
        } else {
                $shown = show(_klapptext_dont_show, array("id" => $get['id']));
                $display = "none";
        }

        $img = show(_gameicon, array("icon" => $get['icon']));
        $qrym = db("SELECT s1.`id`,s1.`datum`,s1.`clantag`,s1.`gegner`,s1.`url`,s1.`xonx`,s1.`liga`,s1.`punkte`,s1.`gpunkte`,s1.`maps`,s1.`serverip`,
                    s1.`servername`,s1.`serverpwd`,s1.`bericht`,s1.`squad_id`,s1.`gametype`,s1.`gcountry`,s2.`icon`,s2.`name`
                    FROM `".$db['cw']."` AS `s1`
                    LEFT JOIN `".$db['squads']."` AS `s2` ON s1.`squad_id` = s2.`id`
                    WHERE s1.`squad_id` = ".$get['id']."
                    AND s1.`datum` < ".time()."
                    ORDER BY s1.`datum` DESC
                    LIMIT ".config('m_clanwars').";");

        $wars = "";
        while($getm = _fetch($qrym)) {
            $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $game = squad($getm['icon']);

            $flagge = flag($getm['gcountry']);
            $gegner = show(_cw_details_gegner, array("gegner" => re(cut($getm['clantag']." - ".$getm['gegner'], config('l_clanwars'))),
                                                         "url" => '?action=details&amp;id='.$getm['id']));

            $details = show(_cw_show_details, array("id" => $getm['id']));
            $squad = show(_member_squad_squadlink, array("squad" => re($get['name']),
                                                         "id" => $get['id'],
                                                         "shown" => $shown));

            $wars .= show($dir."/clanwars_show2", array("datum" => date("d.m.Y", $getm['datum']),
                                                        "img" => $img,
                                                        "flagge" => $flagge,
                                                        "gegner" => $gegner,
                                                        "xonx" => re($getm['xonx']),
                                                        "liga" => re($getm['liga']),
                                                        "gametype" => re($getm['gametype']),
                                                        "class" => $class,
                                                        "result" => cw_result_nopic($getm['punkte'], $getm['gpunkte']),
                                                        "details" => $details));
        }

        $sum_punkte_get = sum_multi($db['cw'], "WHERE `squad_id` = ".$get['id'], array('punkte','gpunkte'));
        $sum_punkte = $sum_punkte_get['sum_punkte'];
        $sum_gpunkte = $sum_punkte_get['sum_gpunkte'];
        unset($sum_punkte_get);
        $anz_ges_points = show(_cw_stats_ges_points, array("ges_won" => $sum_punkte,
                                                           "ges_lost" => $sum_gpunkte));

        if(cnt($db['cw'], " WHERE squad_id = ".$get['id']." AND datum < ".time()."") != "0") {
            $anz_wo_wars = cnt($db['cw'], " WHERE punkte > gpunkte AND squad_id = ".$get['id']."");
            $anz_lo_wars = cnt($db['cw'], " WHERE punkte < gpunkte AND squad_id = ".$get['id']."");
            $anz_dr_wars = cnt($db['cw'], " WHERE datum < ".time()." && punkte = gpunkte AND squad_id = ".$get['id']."");
            $anz_ge_wars = cnt($db['cw'], " WHERE datum < ".time()." AND squad_id = ".$get['id']."");

            $wo_percent = @round($anz_wo_wars*100/$anz_ge_wars, 1);
            $lo_percent = @round($anz_lo_wars*100/$anz_ge_wars, 1);
            $dr_percent = @round($anz_dr_wars*100/$anz_ge_wars, 1);

            $wo_rawpercent = @round($anz_wo_wars*100/$anz_ge_wars, 0);
            $lo_rawpercent = @round($anz_lo_wars*100/$anz_ge_wars, 0);
            $dr_rawpercent = @round($anz_dr_wars*100/$anz_ge_wars, 0);

            if($anz_wo_wars != "0") $wo_balken = show(_votes_balken, array("width" => $wo_rawpercent));
            else                    $wo_balken = show(_votes_balken, array("width" => 1));

            if($anz_lo_wars != "0") $lo_balken = show(_votes_balken, array("width" => $lo_rawpercent));
            else                    $lo_balken = show(_votes_balken, array("width" => 1));

            if($anz_dr_wars != "0") $dr_balken = show(_votes_balken, array("width" => $dr_rawpercent));
            else                    $dr_balken = show(_votes_balken, array("width" => 1));
        }

        $anz_ges_wars = show(_cw_stats_ges_wars_sq, array("ge_wars" => $anz_ge_wars));
        $stats = show($dir."/stats", array("wo_wars" => $anz_wo_wars,
                                          "lo_wars" => $anz_lo_wars,
                                          "dr_wars" => $anz_dr_wars,
                                          "dr_percent" => $dr_percent,
                                          "lo_percent" => $lo_percent,
                                          "ges_punkte" => _cw_gespunkte,
                                          "wo_percent" => $wo_percent,
                                          "won_icon" => _cw_stats_won_icon,
                                          "lost_icon" => _cw_stats_lost_icon,
                                          "draw_icon" => _cw_stats_draw_icon,
                                          "won_balken" => $wo_balken,
                                          "lost_balken" => $lo_balken,
                                          "draw_balken" => $dr_balken,
                                          "head_stat" => _cw_head_statstik,
                                          "won_stat" => _cw_stats_won_head,
                                          "lost_stat" => _cw_stats_lost_head,
                                          "draw_stat" => _cw_stats_draw_head,
                                          "ges_wars" => $anz_ges_wars,
                                          "ges_points" => $anz_ges_points));

        $more = "";
        if(cnt($db['cw'], " WHERE squad_id = ".$get['id']." AND datum < ".time()."") > config('m_clanwars'))
            $more = show(_cw_show_all, array("id" => $get['id']));

        if(cnt($db['cw'], " WHERE squad_id = ".$get['id']." AND datum < ".time()."") > 0) {
            $show .= show($dir."/squads_show", array("id" => $get['id'],
                                                     "shown" => $shown,
                                                     "display" => $display,
                                                     "wars" => $wars,
                                                     "squad" => $squad." [".cnt($db['cw'], " WHERE squad_id = ".$get['id']." AND datum < ".time()."")."]",
                                                     "img" => $img,
                                                     "stats" => $stats,
                                                     "game" => _cw_head_game,
                                                     "datum" => _cw_head_datum,
                                                     "liga" => _cw_head_liga,
                                                     "gametype" => _cw_head_gametype,
                                                     "xonx" => _cw_head_xonx,
                                                     "gegner" => _cw_head_gegner,
                                                     "details" => _cw_head_details_show,
                                                     "result" => _cw_head_result,
                                                     "more" => $more));
        }
    }

    $qry = db("SELECT game,icon FROM ".$db['squads']."
              WHERE status = '1'
              GROUP BY game
              ORDER BY game ASC");
    $legende = '';
    while($get = _fetch($qry)) {
        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $img = squad($get['icon']);
        $legende .= show(_cw_legende, array("game" => re($get['game']),
                                            "img" => $img,
                                            "class" => $class));
    }

    $legende = show($dir."/legende", array("legende" => $legende,
                                           "legende_head" => _cw_head_legende));

    $index = show($dir."/squads", array("head" => _cw_head_statstik,
                                        "headwars" => _cw_head_clanwars,
                                        "stats" => $stats,
                                        "stats_all" => $stats_all,
                                        "legende" => $legende,
                                        "show" => $show));
}