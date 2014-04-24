<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_Clanwars')) {
    $qry = db("SELECT s1.id,s1.datum,s1.clantag,s1.gegner,s1.url,s1.xonx,s1.liga,s1.punkte,s1.gpunkte,s1.maps,s1.serverip,
                    s1.servername,s1.serverpwd,s1.bericht,s1.squad_id,s1.gametype,s1.gcountry,s2.icon,s2.name
             FROM ".$db['cw']." AS s1
             LEFT JOIN ".$db['squads']." AS s2 ON s1.squad_id = s2.id
             WHERE DATE_FORMAT(FROM_UNIXTIME(s1.datum), '%d.%m.%Y') = '".date("d.m.Y",intval($_GET['time']))."'
             ORDER BY s1.datum DESC
             LIMIT ".($page - 1)*config('m_clanwars').",".config('m_clanwars')."");

  $i = $entrys-($page - 1)*config('m_clanwars');
  $entrys = cnt($db['cw'], " WHERE DATE_FORMAT(FROM_UNIXTIME('".$get['datum']."'), '%d.%m.%Y') = '".date("d.m.Y",intval($_GET['time']))."'");

  if(_rows($qry))
  {
      $show = "";
    while($get = _fetch($qry))
    {
      $img = squad($get['icon']);
      $flagge = flag($get['gcountry']);
      $gegner = show(_cw_details_gegner, array("gegner" => re(cut($get['clantag']." - ".$get['gegner'], config('l_clanwars'))),
                                               "url" => '?action=details&amp;id='.$get['id']));

      $details = show(_cw_show_details, array("id" => $get['id']));
      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

      $show .= show($dir."/clanwars_show", array("datum" => date("d.m.y", $get['datum']),
                                                                       "img" => $img,
                                                                       "flagge" => $flagge,
                                                   "gegner" => $gegner,
                                                 "xonx" => re($get['xonx']),
                                                 "liga" => re($get['liga']),
                                                                       "gametype" => re($get['gametype']),
                                                 "class" => $class,
                                                 "result" => cw_result_nopic($get['punkte'], $get['gpunkte']),
                                                                       "details" => $details));
    }
    if(_rows($qry))
    {
      $anz_wo_wars = cnt($db['cw'], " WHERE punkte > gpunkte");
      $anz_lo_wars = cnt($db['cw'], " WHERE punkte < gpunkte");
      $anz_dr_wars = cnt($db['cw'], " WHERE datum < ".time()." && punkte = gpunkte");
      $anz_ge_wars = cnt($db['cw'], "  WHERE datum < ".time()."");

      if(!$_GET['time'])
      {
        $wo_percent = round($anz_wo_wars*100/$anz_ge_wars, 1);
        $lo_percent = round($anz_lo_wars*100/$anz_ge_wars, 1);
        $dr_percent = round($anz_dr_wars*100/$anz_ge_wars, 1);

        $wo_rawpercent = round($anz_wo_wars*100/$anz_ge_wars, 0);
        $lo_rawpercent = round($anz_lo_wars*100/$anz_ge_wars, 0);
        $dr_rawpercent = round($anz_dr_wars*100/$anz_ge_wars, 0);
      }

      $anz_ges_wars = show(_cw_stats_ges_wars, array("ge_wars" => $anz_ge_wars));
      $anz_ges_points = show(_cw_stats_ges_points, array("ges_won" => sum($db['cw'],"","punkte"),
                                                                                  "ges_lost" => sum($db['cw'],"","gpunkte")));

      $anz_squads = cnt($db['squads'], " WHERE status = '1'");

      $qry = db("SELECT game FROM ".$db['squads']."
                 WHERE status = '1'");
      while($row = mysqli_fetch_object($qry))
      {
        $cwid = $row->id; }
        $results = _rows($qry);
        $anz_games= $results;

        $anz_spiele_squads = show(_cw_stats_spiele_squads, array("anz_squads" => $anz_squads,
                                                                                             "anz_games" => $anz_games));
        if($anz_wo_wars != "0") $wo_balken = show(_votes_balken, array("width" => $wo_rawpercent));
        else                    $wo_balken = show(_votes_balken, array("width" => 1));

        if($anz_lo_wars != "0") $lo_balken = show(_votes_balken, array("width" => $lo_rawpercent));
        else                    $lo_balken = show(_votes_balken, array("width" => 1));

        if($anz_dr_wars != "0") $dr_balken = show(_votes_balken, array("width" => $dr_rawpercent));
        else                    $dr_balken = show(_votes_balken, array("width" => 1));
      }

      if(!$_GET['time'])
      {
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
                                                           "ges_points" => $anz_ges_points,
                                                           "anz_spiele_squads" => $anz_spiele_squads));
      }

      $qry = db("SELECT game,icon FROM ".$db['squads']."
                 WHERE status = '1'
                 GROUP BY game
                 ORDER BY game ASC");
      while($get = _fetch($qry))
      {
          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
            $img = squad($get['icon']);
            $legende .= show(_cw_legende, array("game" => re($get['game']),
                                                                  "img" => $img,
                                                                  "class" => $class));
        }

      $legende = show($dir."/legende", array("legende" => $legende,
                                             "legende_head" => _cw_head_legende));
    } else {
      $show = show($dir."/clanwars_no_show", array("clanwars_no_show" => _clanwars_no_show));
    }

    $nav = nav($entrys,config('m_clanwars'),"?action=nav");
    $index = show($dir."/clanwars", array("head" => _cw_head_clanwars,
                                                            "game" => _cw_head_game,
                                          "datum" => _cw_head_datum,
                                          "liga" => _cw_head_liga,
                                                            "gametype" => _cw_head_gametype,
                                          "xonx" => _cw_head_xonx,
                                          "result" => _cw_head_result,
                                          "stats" => $stats,
                                          "squad" => "",
                                          "icon" => "",
                                                            "legende" => $legende,
                                                            "page" => _cw_head_page,
                                          "nav" => $nav,
                                          "gegner" => _cw_head_gegner,
                                          "show" => $show,
                                                            "details" => _cw_head_details_show));
}