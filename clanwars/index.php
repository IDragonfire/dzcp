<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$dir = "clanwars";
$where = _site_clanwars;
## SECTIONS ##
switch ($action):
default:
  $cnt = db("SELECT SUM(punkte) AS num
             FROM ".$db['cw']."");
  $cnt = _fetch($cnt);
  $sum_punkte = $cnt['num'];

  $cnt = db("SELECT SUM(gpunkte) AS num
             FROM ".$db['cw']."");
  $cnt = _fetch($cnt);
  $sum_gpunkte = $cnt['num'];

  $anz_ges_points = show(_cw_stats_ges_points, array("ges_won" => $sum_punkte,
                                                                              "ges_lost" => $sum_gpunkte));

  if(cnt($db['cw'], " WHERE datum < ".time()."") != "0")
  {
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

  $qry = db("SELECT * FROM ".$db['squads']."
             WHERE status = '1'
             ORDER BY pos");
  while($get = _fetch($qry))
  {

      if($_GET['showsquad'] == $get['id'] || $_GET['show'] == $get['id'])
      {
        $shown = show(_klapptext_show, array("id" => $get['id']));
        $display = "";
      } else {
        $shown = show(_klapptext_dont_show, array("id" => $get['id']));
        $display = "none";
      }

    $img = show(_gameicon, array("icon" => $get['icon']));
    $wars = "";

    $qrym = db("SELECT s1.id,s1.datum,s1.clantag,s1.gegner,s1.url,s1.xonx,s1.liga,s1.punkte,s1.gpunkte,s1.maps,s1.serverip,
                       s1.servername,s1.serverpwd,s1.bericht,s1.squad_id,s1.gametype,s1.gcountry,s2.icon,s2.name
                FROM ".$db['cw']." AS s1
                LEFT JOIN ".$db['squads']." AS s2 ON s1.squad_id = s2.id
                WHERE s1.squad_id='".$get['id']."'
                  AND s1.datum < ".time()."
                ORDER BY s1.datum DESC
                LIMIT ".$maxcw."");
     while($getm = _fetch($qrym))
    {
      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
      $game = squad($getm['icon']);

      $flagge = flag($getm['gcountry']);
      $gegner = show(_cw_details_gegner, array("gegner" => re(cut($getm['clantag']." - ".$getm['gegner'], $lcwgegner)),
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

    $cnt = db("SELECT SUM(punkte) AS num FROM ".$db['cw']."
                  WHERE squad_id = ".$get['id']."");
    $cnt = _fetch($cnt);
    $sum_punkte = $cnt['num'];

    $cnt = db("SELECT SUM(gpunkte) AS num FROM ".$db['cw']."
                  WHERE squad_id = ".$get['id']."");
    $cnt = _fetch($cnt);
    $sum_gpunkte = $cnt['num'];

    $anz_ges_points = show(_cw_stats_ges_points, array("ges_won" => $sum_punkte,
                                                                                 "ges_lost" => $sum_gpunkte));

    if(cnt($db['cw'], " WHERE squad_id = ".$get['id']." AND datum < ".time()."") != "0")
    {
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

    if(cnt($db['cw'], " WHERE squad_id = ".$get['id']." AND datum < ".time()."") > $maxcw)
      $more = show(_cw_show_all, array("id" => $get['id'])); else $more = "";

    if(cnt($db['cw'], " WHERE squad_id = ".$get['id']." AND datum < ".time()."") > 0)
    {
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
    } else {
      $show .= "";
    }
  }

    if(permission("clanwars")) $add = _clanwars_admin_add;
    else $add = "&nbsp;";

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

  $index = show($dir."/squads", array("head" => _cw_head_statstik,
                                                          "headwars" => _cw_head_clanwars,
                                                       "stats" => $stats,
                                      "stats_all" => $stats_all,
                                                        "legende" => $legende,
                                                "show" => $show));

break;
case 'showall';
    $qry = db("SELECT s1.id,s1.datum,s1.clantag,s1.gegner,s1.url,s1.xonx,s1.liga,s1.punkte,s1.gpunkte,s1.maps,s1.serverip,
                    s1.servername,s1.serverpwd,s1.bericht,s1.squad_id,s1.gametype,s1.gcountry,s2.icon,s2.name
             FROM ".$db['cw']." AS s1
             LEFT JOIN ".$db['squads']." AS s2 ON s1.squad_id = s2.id
             WHERE s1.datum < ".time()." AND s1.squad_id = ".intval($_GET['id'])."
             ORDER BY s1.datum DESC
             LIMIT ".($page - 1)*$maxcw.",".$maxcw."");

  $i = $entrys-($page - 1)*$maxcw;
  $entrys = cnt($db['cw'], "  WHERE datum < ".time()." AND squad_id = ".intval($_GET['id'])."");
  if(_rows($qry))
  {
      $show = "";
    while($get = _fetch($qry))
    {
      $img = squad($get['icon']);
      $flagge = flag($get['gcountry']);
      $gegner = show(_cw_details_gegner, array("gegner" => re(cut($get['clantag']." - ".$get['gegner'], $lcwgegner)),
                                               "url" => '?action=details&amp;id='.$get['id']));

      $details = show(_cw_show_details, array("id" => $get['id']));
      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
      $squad = show(_member_squad_squadlink, array("squad" => re($get['name']),
                                                   "id" => intval($_GET['id'])));
      $icon = show(_gameicon, array("icon" => $get['icon']));

      $show .= show($dir."/clanwars_show", array("datum" => date("d.m.Y", $get['datum']),
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
      $anz_wo_wars = cnt($db['cw'], " WHERE punkte > gpunkte AND squad_id = ".intval($_GET['id'])."");
      $anz_lo_wars = cnt($db['cw'], " WHERE punkte < gpunkte AND squad_id = ".intval($_GET['id'])."");
      $anz_dr_wars = cnt($db['cw'], " WHERE datum < ".time()." && punkte = gpunkte AND squad_id = ".intval($_GET['id'])."");
      $anz_ge_wars = cnt($db['cw'], "  WHERE datum < ".time()." AND squad_id = ".intval($_GET['id'])."");

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

      $cnt = db("SELECT SUM(punkte) AS num FROM ".$db['cw']."
                   WHERE squad_id = ".intval($_GET['id'])."");
      $cnt = _fetch($cnt);
      $sum_punkte = $cnt['num'];

      $cnt = db("SELECT SUM(gpunkte) AS num FROM ".$db['cw']."
                   WHERE squad_id = ".intval($_GET['id'])."");
      $cnt = _fetch($cnt);
      $sum_gpunkte = $cnt['num'];

      $anz_ges_points = show(_cw_stats_ges_points, array("ges_won" => $sum_punkte,
                                                                     "ges_lost" => $sum_gpunkte));

      $anz_squads = cnt($db['squads'], " WHERE status = '1'");

      $qry = db("SELECT game FROM ".$db['squads']."");
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
                                                           "ges_points" => $anz_ges_points));

      }
    } else {
      $show = show($dir."/clanwars_no_show", array("clanwars_no_show" => _clanwars_no_show));
    }

    $nav = nav($entrys,$maxcw,"?action=showall&amp;id=".$_GET['id']."");
    $show = show($dir."/clanwars", array("head" => _cw_head_clanwars,
                                                         "game" => _cw_head_game,
                                         "datum" => _cw_head_datum,
                                         "liga" => _cw_head_liga,
                                                         "gametype" => _cw_head_gametype,
                                         "xonx" => _cw_head_xonx,
                                         "result" => _cw_head_result,
                                         "stats" => $stats,
                                                         "legende" => "",
                                                         "page" => _cw_head_page,
                                         "nav" => $nav,
                                         "squad" => $squad." - ",
                                         "icon" => $icon,
                                         "gegner" => _cw_head_gegner,
                                         "show" => $show,
                                                         "details" => _cw_head_details_show));

      $qry = db("SELECT game,icon FROM ".$db['squads']."
               WHERE status = '1'
               GROUP BY game
               ORDER BY game ASC");
      while($get = _fetch($qry))
    {
          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $img = squad($get['icon']);
          $legende .= show(_awards_legende, array("game" => re($get['game']),
                                                                      "img" => $img,
                                                                      "class" => $class));
      }
    $legende = show($dir."/legende", array("legende_head" => _awards_head_legende,
                                                               "legende" => $legende));

    $index = show($dir."/main", array("head" => _awards_head,
                                                        "stats" => $stats,
                                                        "legende" => $legende,
                                      "show" => $show));
break;
case 'kalender';
    $qry = db("SELECT s1.id,s1.datum,s1.clantag,s1.gegner,s1.url,s1.xonx,s1.liga,s1.punkte,s1.gpunkte,s1.maps,s1.serverip,
                    s1.servername,s1.serverpwd,s1.bericht,s1.squad_id,s1.gametype,s1.gcountry,s2.icon,s2.name
             FROM ".$db['cw']." AS s1
             LEFT JOIN ".$db['squads']." AS s2 ON s1.squad_id = s2.id
             WHERE DATE_FORMAT(FROM_UNIXTIME(s1.datum), '%d.%m.%Y') = '".date("d.m.Y",intval($_GET['time']))."'
             ORDER BY s1.datum DESC
             LIMIT ".($page - 1)*$maxcw.",".$maxcw."");

  $i = $entrys-($page - 1)*$maxcw;
  $entrys = cnt($db['cw'], " WHERE DATE_FORMAT(FROM_UNIXTIME('".$get['datum']."'), '%d.%m.%Y') = '".date("d.m.Y",intval($_GET['time']))."'");

  if(_rows($qry))
  {
      $show = "";
    while($get = _fetch($qry))
    {
      $img = squad($get['icon']);
      $flagge = flag($get['gcountry']);
      $gegner = show(_cw_details_gegner, array("gegner" => re(cut($get['clantag']." - ".$get['gegner'], $lcwgegner)),
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

    $nav = nav($entrys,$maxcw,"?action=nav");
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
break;
case 'details';
  $qry = db("SELECT s1.id,s1.datum,s1.clantag,s1.gegner,s1.url,s1.xonx,s1.liga,s1.punkte,s1.gpunkte,s1.maps,s1.serverip,s1.servername,
                    s1.serverpwd,s1.bericht,s1.squad_id,s1.gametype,s1.gcountry,s1.lineup,s1.glineup,s1.matchadmins,s2.icon,s2.name,s2.game
           FROM ".$db['cw']." AS s1
           LEFT JOIN ".$db['squads']." AS s2 ON s1.squad_id = s2.id
           WHERE s1.id = '".intval($_GET['id'])."'");
  $get = _fetch($qry);

  if($chkMe != 1 && $chkMe >= 2 && $get['punkte'] == "0" && $get['gpunkte'] == "0")
  {
    if($get['datum'] > time())
    {
      $qryp = db("SELECT * FROM ".$db['cw_player']."
                  WHERE cwid = '".intval($_GET['id'])."'
                  ORDER BY status");
      while($getp = _fetch($qryp))
      {
        if($getp['status'] == "0") $status = _cw_player_want;
        elseif($getp['status'] == "1") $status = _cw_player_dont_want;
        else $status = _cw_player_dont_know;

        if($getp['member'] == $userid)
        {
          if($getp['status'] == "0") $sely = "checked=\"checked\"";
          elseif($getp['status'] == "1") $seln = "checked=\"checked\"";
          elseif($getp['status'] == "2") $selm = "checked=\"checked\"";
        } else {
          $sely = "";
          $seln = "";
          $selm = "";
        }

        $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
        $show_players .= show($dir."/players_show", array("nick" => autor($getp['member']),
                                                          "class" => $class,
                                                          "status" => $status));
      }

      $cntPlayers = cnt($db['cw_player'], " WHERE cwid = '".intval($_GET['id'])."' AND member = '".$userid."'", "cwid");

      if($cntPlayers) $value = _button_value_edit;
      else            $value = _button_value_add;

      $players = show($dir."/players", array("show_players" => $show_players,
                                             "nick" => _nick,
                                             "play" => _cw_players_play,
                                             "yes" => _yes,
                                             "no" => _no,
                                             "admin" => (permission('clanwars') ? '<input id="contentSubmitAdmin" type="button" value="'._cw_reset_button.'" class="submit" onclick="DZCP.submitButton(\'contentSubmitAdmin\');DZCP.goTo(\'?action=resetplayers&amp;id='.intval($_GET['id']).'\')" />' : ''),
                                             "sely" => (empty($sely) && empty($seln) && empty($selm) ? 'checked="checked"' : $sely),
                                             "seln" => $seln,
                                             "selm" => $selm,
                                             "maybe" => _maybe,
                                             "id" => intval($_GET['id']),
                                             "value" => $value,
                                             "status" => _status,
                                             "head" => _cw_players_head));

      $serverpwd = show(_cw_serverpwd, array("cw_serverpwd" => re($get['serverpwd'])));
    } else {
      $serverpwd = "";
    }
  } else {
    $serverpwd = "";
    $players = "";
  }
  $img = squad($get['icon']);
  $show = show(_cw_details_squad, array("game" => re($get['game']),
                                                          "name" => re($get['name']),
                                        "id" => $get['squad_id'],
                                                          "img" => $img));
  $flagge = flag($get['gcountry']);
  $gegner = show(_cw_details_gegner_blank, array("gegner" => re($get['clantag']." - ".$get['gegner']),
                                                 "url" => !empty($get['url']) ? re($get['url']) : "#"));
  $server = show(_cw_details_server, array("servername" => re($get['servername']),
                                           "serverip" => re($get['serverip'])));

  if($get['punkte'] == "0" && $get['gpunkte'] == "0") $result = _cw_no_results;
  else $result = cw_result_details($get['punkte'], $get['gpunkte']);

  if(permission("clanwars"))
  {
    $editcw = show("page/button_edit_single", array("id" => $get['id'],
                                                   "action" => "action=admin&amp;do=edit",
                                                   "title" => _button_title_edit));
  } else {
    $editcw = "";
  }

  if($get['bericht']) $bericht = bbcode($get['bericht']);
  else $bericht = "&nbsp;";

  $libPath = "inc/images/clanwars/".intval($_GET['id']);
  $screen1 = ''; $screen2 = ''; $screen3 = ''; $screen4 = '';
  foreach($picformat AS $end)
  {
    if(file_exists(basePath."/inc/images/clanwars/".intval($_GET['id']).'_1.'.$end)) $screen1 = img_cw($libPath, '1.'.$end);
    if(file_exists(basePath."/inc/images/clanwars/".intval($_GET['id']).'_2.'.$end)) $screen2 = img_cw($libPath, '2.'.$end);
    if(file_exists(basePath."/inc/images/clanwars/".intval($_GET['id']).'_3.'.$end)) $screen3 = img_cw($libPath, '3.'.$end);
    if(file_exists(basePath."/inc/images/clanwars/".intval($_GET['id']).'_4.'.$end)) $screen4 = img_cw($libPath, '4.'.$end);
  }


  if(!empty($screen1) || !empty($screen2) || !empty($screen3) || !empty($screen4))
  {
    $screens = show($dir."/screenshots", array("head" => _cw_screens,
                                               "screenshot1" => _cw_screenshot." 1",
                                               "screenshot2" => _cw_screenshot." 2",
                                               "screenshot3" => _cw_screenshot." 3",
                                               "screenshot4" => _cw_screenshot." 4",
                                               "screen1" => $screen1,
                                               "screen2" => $screen2,
                                               "screen3" => $screen3,
                                               "screen4" => $screen4));
  }

    $qryc = db("SELECT * FROM ".$db['cw_comments']."
                            WHERE cw = ".intval($_GET['id'])."
                            ORDER BY datum DESC
              LIMIT ".($page - 1)*$maxcwcomments.",".$maxcwcomments."");

  $entrys = cnt($db['cw_comments'], " WHERE cw = ".intval($_GET['id']));
  $i = $entrys-($page - 1)*$maxcwcomments;

    while($getc = _fetch($qryc))
    {
    if($getc['hp']) $hp = show(_hpicon, array("hp" => $getc['hp']));
    else $hp = "";

    if(($chkMe >= 1 && $getc['reg'] == $userid) || permission("clanwars"))
    {
      $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                    "action" => "action=details&amp;do=edit&amp;cid=".$getc['id'],
                                                    "title" => _button_title_edit));
      $delete = show("page/button_delete_single", array("id" => $_GET['id'],
                                                       "action" => "action=details&amp;do=delete&amp;cid=".$getc['id'],
                                                       "title" => _button_title_del,
                                                       "del" => convSpace(_confirm_del_entry)));
    } else {
      $edit = "";
      $delete = "";
    }

        if($getc['reg'] == "0")
        {
      if($getc['hp']) $hp = show(_hpicon_forum, array("hp" => $getc['hp']));
      else $hp = "";
      if($getc['email']) $email = '<br />'.show(_emailicon_forum, array("email" => eMailAddr($getc['email'])));
      else $email = "";
      $onoff = "";
      $avatar = "";
      $nick = show(_link_mailto, array("nick" => re($getc['nick']),
                                       "email" => $getc['email']));
        } else {
      $hp = "";
      $email = "";
      $onoff = onlinecheck($getc['reg']);
      $nick = autor($getc['reg']);

        }

    $titel = show(_eintrag_titel, array("postid" => $i,
                                                                            "datum" => date("d.m.Y", $getc['datum']),
                                                                            "zeit" => date("H:i", $getc['datum'])._uhr,
                                        "edit" => $edit,
                                        "delete" => $delete));

    if($chkMe == "4") $posted_ip = $getc['ip'];
    else $posted_ip = _logged;

        $comments .= show("page/comments_show", array("titel" => $titel,
                                                                                          "comment" => bbcode($getc['comment']),
                                                  "editby" => bbcode($getc['editby']),
                                                  "nick" => $nick,
                                                  "hp" => $hp,
                                                  "email" => $email,
                                                  "avatar" => useravatar($getc['reg']),
                                                  "onoff" => $onoff,
                                                  "rank" => getrank($getc['reg']),
                                                  "ip" => $posted_ip));
      $i--;
    }

  if(settings("reg_cwcomments") && !$chkMe)
  {
    $add = _error_unregistered_nc;
  } else {
    if(!ipcheck("cwid(".$_GET['id'].")", $flood_cwcom))
    {
      if($userid >= 1)
        {
          $form = show("page/editor_regged", array("nick" => autor($userid),
                                                 "von" => _autor));
        } else {
        $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                    "emailhead" => _email,
                                                    "hphead" => _hp,
                                                    "postemail" => $postemail,
                                                                                              "posthp" => $posthp,
                                                                                              "postnick" => $postnick,));
      }

        $add = show("page/comments_add", array("titel" => _cw_comments_add,
                                                                                     "nickhead" => _nick,
                                                                                     "bbcodehead" => _bbcode,
                                                                                     "emailhead" => _email,
                                                                                     "hphead" => _hp,
                                             "security" => _register_confirm,
                                             "sec" => $dir,
                                             "security" => _register_confirm,
                                             "sec" => $dir,
                                             "show" => "none",
                                             "ip" => _iplog_info,
                                             "preview" => _preview,
                                             "action" => '?action=details&amp;do=add&amp;id='.$_GET['id'],
                                             "prevurl" => '../clanwars/?action=compreview&amp;id='.$_GET['id'],
                                                                                     "id" => $_GET['id'],
                                             "what" => _button_value_add,
                                             "form" => $form,
                                                                                     "posteintrag" => "",
                                                                                     "error" => "",
                                                                                     "eintraghead" => _eintrag));
    } else {
      $add = "";
    }
  }

  $seiten = nav($entrys,$maxcwcomments,"?action=details&amp;id=".$_GET['id']."");

  $comments = show($dir."/comments",array("head" => _cw_comments_head,
                                                                               "show" => $comments,
                                          "seiten" => $seiten,
                                          "add" => $add));

  $logo_squad = '_defaultlogo.jpg'; $logo_gegner = '_defaultlogo.jpg';
  foreach($picformat AS $end)
  {
       if(file_exists(basePath.'/inc/images/clanwars/'.$get['id'].'_logo.'.$end)) $logo_gegner = $get['id'].'_logo.'.$end;
    if(file_exists(basePath.'/inc/images/squads/'.$get['squad_id'].'_logo.'.$end))$logo_squad = $get['squad_id'].'_logo.'.$end;
  }

  $logos = ($logo_squad == '_defaultlogo.jpg') && ($logo_gegner == '_defaultlogo.jpg');
  $pagetitle = re($get['name']).' vs. '.re($get['gegner']).' - '.$pagetitle;

  $index = show($dir."/details", array("head" => _cw_head_details,
                                                         "result_head" => _cw_head_results,
                                                         "lineup_head" => _cw_head_lineup,
                                                         "admin_head" => _cw_head_admin,
                                                         "gametype_head" => _cw_head_gametype,
                                                         "squad_head" => _cw_head_squad,
                                                         "flagge" => $flagge,
                                       "br1" => ($logos ? '<!--' : ''),
                                       "br2" => ($logos ? '-->' : ''),
                                       "logo_squad" => $logo_squad,
                                       "logo_gegner" => $logo_gegner,
                                                         "squad" => $show,
                                                         "squad_name" => re($get['name']),
                                                         "gametype" => empty($get['gametype']) ? '-' : re($get['gametype']),
                                                         "lineup" => preg_replace("#\,#","<br />",re($get['lineup'])),
                                                         "glineup" => preg_replace("#\,#","<br />",re($get['glineup'])),
                                                         "match_admins" => empty($get['matchadmins']) ? '-' : re($get['matchadmins']),
                                       "datum" => _datum,
                                       "gegner" => _cw_head_gegner,
                                       "xonx" => _cw_head_xonx,
                                       "liga" => _cw_head_liga,
                                       "maps" => _cw_maps,
                                       "server" => _server,
                                       "result" => _cw_head_result,
                                       "players" => $players,
                                       "edit" => $editcw,
                                       "comments" => $comments,
                                       "bericht" => _cw_bericht,
                                       "serverpwd" => $serverpwd,
                                       "cw_datum" => date("d.m.Y H:i", $get['datum'])._uhr,
                                       "cw_gegner" => $gegner,
                                       "cw_xonx" => empty($get['xonx']) ? '-' : re($get['xonx']),
                                       "cw_liga" => empty($get['liga']) ? '-' : re($get['liga']),
                                       "cw_maps" => empty($get['maps']) ? '-' : re($get['maps']),
                                       "cw_server" => $server,
                                       "cw_result" => $result,
                                       "cw_bericht" => $bericht,
                                       "screenshots" => $screens));

  if($_GET['do'] == "add")
  {
        if(_rows(db("SELECT `id` FROM ".$db['cw']." WHERE `id` = '".(int)$_GET['id']."'")) != 0)
        {
            if(settings("reg_cwcomments") && !$chkMe )
            {
                $index = error(_error_have_to_be_logged, 1);
            } else {
                if(!ipcheck("cwid(".$_GET['id'].")", $flood_cwcom))
                {
                    if($userid >= 1)
                        $toCheck = empty($_POST['comment']);
                    else
                        $toCheck = empty($_POST['nick']) || empty($_POST['email']) || empty($_POST['comment']) || !check_email($_POST['email']) || $_POST['secure'] != $_SESSION['sec_'.$dir] || empty($_SESSION['sec_'.$dir]);

                    if($toCheck)
                    {
                        if($userid >= 1)
                        {
                            if(empty($_POST['comment'])) $error = _empty_eintrag;
                            $form = show("page/editor_regged", array("nick" => autor($userid),
                                                                                                             "von" => _autor));
                        } else {
                            if(($_POST['secure'] != $_SESSION['sec_'.$dir]) || empty($_SESSION['sec_'.$dir])) $error = _error_invalid_regcode;
                            elseif(empty($_POST['nick'])) $error = _empty_nick;
                            elseif(empty($_POST['email'])) $error = _empty_email;
                            elseif(!check_email($_POST['email'])) $error = _error_invalid_email;
                            elseif(empty($_POST['comment'])) $error = _empty_eintrag;
                            $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                                                                                    "emailhead" => _email,
                                                                                                                    "hphead" => _hp));
                        }

                        $error = show("errors/errortable", array("error" => $error));
                        $index = show("page/comments_add", array("titel" => _cw_comments_add,
                                                                                                         "nickhead" => _nick,
                                                                                                         "bbcodehead" => _bbcode,
                                                                                                         "emailhead" => _email,
                                                                                                         "hphead" => _hp,
                                                                                                         "ip" => _iplog_info,
                                                                                                         "security" => _register_confirm,
                                                                                                         "what" => _button_value_add,
                                                                                                         "sec" => $dir,
                                                                                                         "form" => $form,
                                                                                                         "preview" => _preview,
                                                                                                         "action" => '?action=details&amp;do=add&amp;id='.$_GET['id'],
                                                                                                         "prevurl" => '../clanwars/?action=compreview&id='.$_GET['id'],
                                                                                                         "id" => $_GET['id'],
                                                                                                         "show" => "",
                                                                                                         "postemail" => $_POST['email'],
                                                                                                         "posthp" => links($_POST['hp']),
                                                                                                         "postnick" => re($_POST['nick']),
                                                                                                         "posteintrag" => re_bbcode($_POST['comment']),
                                                                                                         "error" => $error,
                                                                                                         "eintraghead" => _eintrag));
                    } else {
                        $qry = db("INSERT INTO ".$db['cw_comments']."
                                             SET `cw`       = '".((int)$_GET['id'])."',
                                                     `datum`    = '".((int)time())."',
                                                     `nick`     = '".up($_POST['nick'])."',
                                                     `email`    = '".up($_POST['email'])."',
                                                     `hp`       = '".links($_POST['hp'])."',
                                                     `reg`      = '".((int)$userid)."',
                                                     `comment`  = '".up($_POST['comment'],1)."',
                                                     `ip`       = '".$userip."'");

                        setIpcheck("cwid(".$_GET['id'].")");

                        $index = info(_comment_added, "?action=details&amp;id=".$_GET['id']."");
                    }
                } else {
                    $index = error(show(_error_flood_post, array("sek" => $flood_cwcom)), 1);
                }
            }
        } else{
            $index = error(_id_dont_exist,1);
        }
  }

  if($_GET['do'] == "delete")
  {
    $qry = db("SELECT reg FROM ".$db['cw_comments']."
               WHERE id = '".intval($_GET['cid'])."'");
    $get = _fetch($qry);

      if($get['reg'] == $userid || permission('clanwars'))
      {
      $qry = db("DELETE FROM ".$db['cw_comments']."
                 WHERE id = '".intval($_GET['cid'])."'");

      $index = info(_comment_deleted, "?action=details&amp;id=".intval($_GET['id'])."");
    } else {
      $index = error(_error_wrong_permissions, 1);
    }
  } elseif($_GET['do'] == "editcom") {
    $qry = db("SELECT * FROM ".$db['cw_comments']."
               WHERE id = '".intval($_GET['cid'])."'");
    $get = _fetch($qry);

      if($get['reg'] == $userid || permission('clanwars'))
      {
        $editedby = show(_edited_by, array("autor" => autor($userid),
                                           "time" => date("d.m.Y H:i", time())._uhr));
        $qry = db("UPDATE ".$db['cw_comments']."
                   SET `nick`     = '".up($_POST['nick'])."',
                       `email`    = '".up($_POST['email'])."',
                       `hp`       = '".links($_POST['hp'])."',
                       `comment`  = '".up($_POST['comment'],1)."',
                       `editby`   = '".addslashes($editedby)."'
                   WHERE id = '".intval($_GET['cid'])."'");

        $index = info(_comment_edited, "?action=details&amp;id=".$_GET['id']."");
      } else {
        $index = error(_error_edit_post,1);
      }
    } elseif($_GET['do'] == "edit") {
      $qry = db("SELECT * FROM ".$db['cw_comments']."
                 WHERE id = '".intval($_GET['cid'])."'");
      $get = _fetch($qry);

      if($get['reg'] == $userid || permission('clanwars'))
      {
        if($get['reg'] != 0)
          {
              $form = show("page/editor_regged", array("nick" => autor($get['reg']),
                                                   "von" => _autor));
          } else {
          $form = show("page/editor_notregged", array("nickhead" => _nick,
                                                      "emailhead" => _email,
                                                      "hphead" => _hp,
                                                      "postemail" => $get['email'],
                                                                                              "posthp" => links($get['hp']),
                                                                                                  "postnick" => re($get['nick']),
                                                      ));
        }

            $index = show("page/comments_add", array("titel" => _comments_edit,
                                                                                           "nickhead" => _nick,
                                                                                         "bbcodehead" => _bbcode,
                                                                                         "emailhead" => _email,
                                                                                         "hphead" => _hp,
                                                 "security" => _register_confirm,
                                                 "sec" => $dir,
                                                 "form" => $form,
                                                 "preview" => _preview,
                                                 "prevurl" => '../clanwars/?action=compreview&do=edit&id='.$_GET['id'].'&amp;cid='.$_GET['cid'],
                                                 "action" => '?action=details&amp;do=editcom&amp;id='.$_GET['id'].'&amp;cid='.$_GET['cid'],
                                                 "ip" => _iplog_info,
                                                 "lang" => $language,
                                                                                         "id" => $_GET['id'],
                                                 "what" => _button_value_edit,
                                                 "show" => "",
                                                                                         "posteintrag" => re_bbcode($get['comment']),
                                                                                         "error" => "",
                                                                                         "eintraghead" => _eintrag));
      } else {
        $index = error(_error_edit_post,1);
      }
    }
break;
case 'compreview';
  header("Content-type: text/html; charset=utf-8");
  if($_GET['do'] == 'edit')
  {
    $qry = db("SELECT * FROM ".$db['cw_comments']."
               WHERE id = '".intval($_GET['cid'])."'");
    $get = _fetch($qry);

    $get_id = '?';
    $get_userid = $get['reg'];
    $get_date = $get['datum'];

    if($get['reg'] == 0) $regCheck = false;
    else {
      $regCheck = true;
      $pUId = $get['reg'];
    }

    $editedby = show(_edited_by, array("autor" => cleanautor($userid),
                                       "time" => date("d.m.Y H:i", time())._uhr));
  } else {

    $get_id = cnt($db['cw_comments'], " WHERE cw = ".intval($_GET['id'])."")+1;
    $get_userid = $userid;
    $get_date = time();

    if(!$chkMe) $regCheck = false;
    else {
      $regCheck = true;
      $pUId = $userid;
    }
  }

  $get_hp = $_POST['hp'];
  $get_email = $_POST['email'];
  $get_nick = $_POST['nick'];

  if(!$regCheck)
    {
    if($get_hp) $hp = show(_hpicon_forum, array("hp" => links($get_hp)));
    if($get_email) $email = '<br />'.show(_emailicon_forum, array("email" => eMailAddr($get_email)));
    $onoff = "";
    $avatar = "";
    $nick = show(_link_mailto, array("nick" => re($get_nick),
                                     "email" => $get_email));
  } else {
    $hp = "";
    $email = "";
    $onoff = onlinecheck($get_userid);
    $nick = cleanautor($get_userid);
  }

  $titel = show(_eintrag_titel, array("postid" => $get_id,
                                                                          "datum" => date("d.m.Y", $get_date),
                                                                          "zeit" => date("H:i", $get_date)._uhr,
                                      "edit" => $edit,
                                      "delete" => $delete));

  $index = show("page/comments_show", array("titel" => $titel,
                                                                                    "comment" => bbcode(re($_POST['comment']),1),
                                            "nick" => $nick,
                                            "editby" => bbcode($editedby,1),
                                            "email" => $email,
                                            "hp" => $hp,
                                            "avatar" => useravatar($get_userid),
                                            "onoff" => $onoff,
                                            "rank" => getrank($get_userid),
                                            "ip" => $userip._only_for_admins));

  echo '<table class="mainContent" cellspacing="1">'.$index.'</table>';
  exit;
break;
case 'preview';
  header("Content-type: text/html; charset=utf-8");
  $qry = db("SELECT * FROM ".$db['squads']."
             WHERE id = '".intval($_POST['squad'])."'");
  $get = _fetch($qry);

  $serverpwd = show(_cw_serverpwd, array("cw_serverpwd" => re($_POST['serverpwd'])));

  $img = squad($get['icon']);
  $show = show(_cw_details_squad, array("game" => re($get['game']),
                                                          "name" => re($get['name']),
                                        "id" => $_POST['squad'],
                                                          "img" => $img));
  $flagge = flag($get['gcountry']);
  $gegner = show(_cw_details_gegner_blank, array("gegner" => re($_POST['clantag']." - ".$_POST['gegner']),
                                                 "url" => links($_POST['url'])));
  $server = show(_cw_details_server, array("servername" => re($_POST['servername']),
                                           "serverip" => re($_POST['serverip'])));

  if($_POST['punkte'] == "0" && $_POST['gpunkte'] == "0") $result = _cw_no_results;
  else $result = cw_result_details($_POST['punkte'], $_POST['gpunkte']);

 $editcw = "";

  if($_POST['bericht']) $bericht = bbcode(re($_POST['bericht']),1);
  else $bericht = "&nbsp;";

  if(!empty($_POST['s1']))     $screen1 = '<img src="../inc/images/admin/cwscreen.jpg" alt="" />';
  else $screen1 = "";

  if(!empty($_POST['s2']))     $screen2 = '<img src="../inc/images/admin/cwscreen.jpg" alt="" />';
  else $screen2 = "";

  if(!empty($_POST['s3']))     $screen3 = '<img src="../inc/images/admin/cwscreen.jpg" alt="" />';
  else $screen3 = "";

  if(!empty($_POST['s4']))     $screen4 = '<img src="../inc/images/admin/cwscreen.jpg" alt="" />';
  else $screen4 = "";

  if(!empty($screen1) || !empty($screen2) || !empty($screen3) || !empty($screen4))
  {
    $screens = show($dir."/screenshots", array("head" => _cw_screens,
                                               "screenshot1" => _cw_screenshot." 1",
                                               "screenshot2" => _cw_screenshot." 2",
                                               "screenshot3" => _cw_screenshot." 3",
                                               "screenshot4" => _cw_screenshot." 4",
                                               "screen1" => $screen1,
                                               "screen2" => $screen2,
                                               "screen3" => $screen3,
                                               "screen4" => $screen4));
  }

  $datum = mktime($_POST['h'],$_POST['min'],0,$_POST['m'],$_POST['t'],$_POST['j']);
  if(empty($_POST['xonx1']) && empty($_POST['xonx2'])) $xonx = "";
  else $xonx = $_POST['xonx1']."on".$_POST['xonx2'];

  $index = show($dir."/details", array("head" => _cw_head_details,
                                                         "result_head" => _cw_head_results,
                                                         "lineup_head" => _cw_head_lineup,
                                                         "admin_head" => _cw_head_admin,
                                                         "gametype_head" => _cw_head_gametype,
                                                         "squad_head" => _cw_head_squad,
                                                         "flagge" => $flagge,
                                       "br1" => '',
                                       "br2" => '',
                                       "logo_squad" => '_defaultlogo.jpg',
                                       "logo_gegner" => '_defaultlogo.jpg',
                                                         "squad" => $show,
                                                         "squad_name" => re($get['name']),
                                                         "gametype" => re($_POST['gametype']),
                                                         "lineup" => preg_replace("#\,#","<br />", re($_POST['lineup'])),
                                                         "glineup" => preg_replace("#\,#","<br />", re($_POST['glineup'])),
                                                         "match_admins" => re($_POST['match_admins']),
                                       "datum" => _datum,
                                       "gegner" => _cw_head_gegner,
                                       "xonx" => _cw_head_xonx,
                                       "liga" => _cw_head_liga,
                                       "maps" => _cw_maps,
                                       "server" => _server,
                                       "result" => _cw_head_result,
                                       "players" => $players,
                                       "edit" => $editcw,
                                       "comments" => $comments,
                                       "bericht" => _cw_bericht,
                                       "serverpwd" => $serverpwd,
                                       "cw_datum" => date("d.m.Y H:i",$datum)._uhr,
                                       "cw_gegner" => $gegner,
                                       "cw_xonx" => re($xonx),
                                       "cw_liga" => re($_POST['liga']),
                                       "cw_maps" => re($_POST['maps']),
                                       "cw_server" => $server,
                                       "cw_result" => $result,
                                       "cw_bericht" => $bericht,
                                       "screenshots" => $screens));
    echo '<table class="mainContent" cellspacing="1">'.$index.'</table>';
  exit;
break;
case 'update';
  if(!$chkMe)
  {
    $index = error(_error_have_to_be_logged, 1);
  } else {
    $qry = db("SELECT * FROM ".$db['cw_player']."
               WHERE cwid = '".intval($_GET['id'])."'
               AND member = '".$userid."'");
    if(_rows($qry))
    {
      $upd = db("UPDATE ".$db['cw_player']."
                 SET `status` = '".((int)$_POST['status'])."'
                 WHERE cwid = '".intval($_GET['id'])."'
                 AND member = '".$userid."'");
    } else {
      $ins = db("INSERT INTO ".$db['cw_player']."
                 SET `cwid`   = '".((int)$_GET['id'])."',
                     `member` = '".((int)$userid)."',
                     `status` = '".((int)$_POST['status'])."'");
    }

    $index = info(_cw_status_set, "?action=details&amp;id=".$_GET['id']."");
  }
break;
case 'admin';
  if($_GET['do'] == 'edit') header("Location: ../admin/?admin=cw&do=edit&id=".$_GET['id']);
break;
case 'resetplayers';
  if(permission("clanwars")) {
    db("DELETE FROM ".$db['cw_player']." WHERE `cwid` = '".intval($_GET['id'])."'");
  }

  $index = info(_cw_players_reset, '?action=details&id='.intval($_GET['id']));
break;
endswitch;
## SETTINGS ##
$title = $pagetitle." - ".$where."";
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where,$time);
## OUTPUT BUFFER END ##
gz_output();
?>