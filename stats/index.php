<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
include(basePath."/stats/helper.php");

## SETTINGS ##
$where = _site_stats;
$dir = "stats";

## SECTIONS ##
  if($action == "gb")
  {
    $qry = db("SELECT email,reg,nick,datum FROM ".$db['gb']."
               ORDER BY datum ASC LIMIT 1");
    $get = _fetch($qry);

    if($get['reg'] != "0") $first = date("d.m.Y H:i", $get['datum'])."h "._from." ".autor($get['reg']);
    else $first = date("d.m.Y H:i", $get['datum'])."h "._from." ".autor($get['reg'],'',$get['nick'],re($get['email']));

    $qry = db("SELECT email,reg,nick,datum FROM ".$db['gb']."
               ORDER BY datum DESC
               LIMIT 1");
    $get = _fetch($qry);

    if($get['reg'] != "0") $last = date("d.m.Y H:i", $get['datum'])."h "._from." ".autor($get['reg']);
    else $last = date("d.m.Y H:i", $get['datum'])."h "._from." ".autor($get['reg'],'',$get['nick'],re($get['email']));

    $stats = show($dir."/gb", array("head" => _site_gb,
                                    "all" => _stats_gb_all,
                                    "poster" => _stats_gb_poster,
                                    "nposter" => cnt($db['gb']," WHERE reg = 0")."/".cnt($db['gb']," WHERE reg != 0"),
                                    "nall" => cnt($db['gb']),
                                    "first" => _stats_gb_first,
                                    "nfirst" => $first,
                                    "last" => _stats_gb_last,
                                    "nlast" => $last));
  } elseif($action == "forum") {
    $allthreads = cnt($db['f_threads']);
    $allposts = cnt($db['f_posts']);
    if($allthreads > 0 && $allposts >= 0)
    {
      $ppert = round($allposts/$allthreads,2);

      $qry = db("SELECT id,forumposts FROM ".$db['userstats']."
                 ORDER BY forumposts DESC");
      $get = _fetch($qry);

      $topposter = autor($get['id'])." (".$get['forumposts']." Posts)";

      $qry = db("SELECT t_date FROM ".$db['f_threads']."
                 ORDER BY t_date ASC");
      $get = _fetch($qry);

      $time = time()-$get['t_date'];
      $days = @round($time/86400);

      $ges = $allposts+$allthreads;
      $pperd = @round($ges/$days,2);
    }

    $stats = show($dir."/forum", array("head" => _site_forum,
                                       "threads" => _forum_threads,
                                       "nthreads" => $allthreads,
                                       "posts" => _forum_posts,
                                       "nposts" => $allposts,
                                       "ppert" => _stats_forum_ppert,
                                       "nppert" => $ppert,
                                       "pperd" => _stats_forum_pperd,
                                       "npperd" => $pperd,
                                       "topposter" => _stats_forum_top,
                                       "ntopposter" => $topposter));
  } elseif($action == "user") {
    $stats = show($dir."/user", array("head" => _site_user,
                                      "users" => _stats_users_regged,
                                      "member" => _stats_users_regged_member,
                                      "nmember" => cnt($db['users'], " WHERE level != 1"),
                                      "logins" => _stats_users_logins,
                                      "nlogins" => sum($db['userstats'],"", "logins"),
                                      "msg" => _stats_users_msg,
                                      "nmsg" => sum($db['userstats'],"", "writtenmsg"),
                                      "votes" => _stats_users_votes,
                                      "nvotes" => sum($db['userstats'],"","votes"),
                                      "aktmsg" => _stats_users_aktmsg,
                                      "naktmsg" => cnt($db['msg'], " WHERE `von` != '0'"),
                                      "buddys" => _stats_users_buddys,
                                      "nbuddys" => cnt($db['buddys']),
                                      "nusers" => cnt($db['users'])));
  } elseif($action == "cw") {
    if(cnt($db['cw'], " WHERE datum < ".time()."") != "0")
    {
      $won = cnt($db['cw'], " WHERE punkte > gpunkte");
      $lost = cnt($db['cw'], " WHERE punkte < gpunkte");
      $draw = cnt($db['cw'], " WHERE datum < ".time()." && punkte = gpunkte");
      $ges = cnt($db['cw'], " WHERE datum < ".time()."");

      $wo_p = @round($won*100/$ges, 1);
      $lo_p = @round($lost*100/$ges, 1);
      $dr_p = @round($draw*100/$ges, 1);
    }

    $allp = '<span class="CwWon">'.sum($db['cw'],'',"punkte").'</span>'.' : '.'
             <span class="CwLost">'.sum($db['cw'],'',"gpunkte").'</span>';

    $stats = show($dir."/cw", array("head" => _site_clanwars,
                                    "played" => _stats_cw_played,
                                    "nplayed" => $ges,
                                    "won" => _stats_cw_won,
                                    "draw" => _stats_cw_draw,
                                    "lost" => _stats_cw_lost,
                                    "nwon" => $won." (".$wo_p."%)",
                                    "ndraw" => $draw." (".$dr_p."%)",
                                    "nlost" => $lost." (".$lo_p."%)",
                                    "points" => _stats_cw_points,
                                    "npoints" => $allp));
  } elseif($action == "awards") {
    $ges = cnt($db['awards']);
      $place_1 = cnt($db['awards'], " WHERE place = 1 ");
      $place_2 = cnt($db['awards'], " WHERE place = 2 ");
      $place_3 = cnt($db['awards'], " WHERE place = 3 ");


    $stats = show($dir."/awards", array("head" => _site_awards,
                                        "p1" => _stats_place." 1",
                                        "p2" => _stats_place." 2",
                                        "p3" => _stats_place." 3",
                                        "p" => _stats_place_misc,
                                        "awards" => _stats_awards,
                                        "nawards" => $ges,
                                        "np1" => $place_1,
                                        "np2" => $place_2,
                                        "np3" => $place_3,
                                        "np" => $ges-$place_1-$place_2-$place_3));
  } elseif($action == "mysql") {
    $dbinfo = dbinfo();
    $stats = show($dir."/mysql", array("head" => _stats_mysql,
                                       "size" => _stats_mysql_size,
                                       "nsize" => $dbinfo["size"],
                                       "entrys" => _stats_mysql_entrys,
                                       "nentrys" => $dbinfo["entrys"],
                                       "rows" => _stats_mysql_rows,
                                       "nrows" => $dbinfo["rows"]));
  } elseif($action == "downloads") {
    $qry = db("SELECT * FROM ".$db['downloads']."");
    $allhits = 0; $allsize = 0;
    while($get = _fetch($qry))
    {
      $file = preg_replace("#added...#Uis", "../downloads/files/", $get['url']);
      if(strpos($get['url'],"http://") != 0)
          $rawfile = @basename($file);
      else
          $rawfile = re($get['download']);

      $size = 0;
      if(file_exists($file))
          $size = filesize($file);

      $hits = $get['hits'];
      $allhits += $hits;
      $allsize += $size;
    }

    if(strlen(@round(($allsize/1048576)*$allhits,0)) >= 4)
        $alltraffic = @round(($allsize/1073741824)*$allhits,2).' GB';
    else
        $alltraffic = @round(($allsize/1048576)*$allhits,2).' MB';

    if(strlen(@round(($allsize/1048576),0)) >= 4)
        $allsize = @round(($allsize/1073741824),2).' GB';
    else
        $allsize = @round(($allsize/1048576),2).' MB';

    $stats = show($dir."/downloads", array("head" => _site_dl,
                                           "files" => _site_stats_files,
                                           "nfiles" => cnt($db['downloads']),
                                           "size" => _stats_dl_size,
                                           "hosted" => _stats_hosted,
                                           "allsize" => $allsize,
                                           "traffic" => _stats_dl_traffic,
                                           "ntraffic" => $alltraffic,
                                           "hits" => _stats_dl_hits,
                                           "nhits" => $allhits));
  } else {
    $allcomments = cnt($db['newscomments']);
    $allnews = cnt($db['news']);
    $allkats = cnt($db['newskat']);

    $qry = db("SELECT * FROM ".$db['newskat']."");
    $i = 1; $kats = '';
    while($get = _fetch($qry))
    {
      if($i == $allkats) $end = "";
      else $end = ",";

      $kats .= re($get['kategorie']).$end." ";
      $i++;
    }
    $qry = db("SELECT datum FROM ".$db['news']."
               ORDER BY datum ASC");
    $get = _fetch($qry);

    $time = time()-$get['datum'];
    $days = @round($time/86400);

    $cpern = @round($allcomments/$allnews,2);
    $npert = @round($allnews/$days,2);

    $stats = show($dir."/news", array("head" => _site_news,
                                      "kats" => _stats_nkats,
                                      "nkats" => $kats,
                                      "npert" => _stats_npert,
                                      "nnpert" => $npert,
                                      "cpern" => _stats_cpern,
                                      "ncpern" => $cpern,
                                      "comments" => _stats_comments,
                                      "ncomments" => $allcomments,
                                      "news" => _stats_news,
                                      "nnews" => $allnews,
                                      "cnt" => $allkats));
  }

  $index = show($dir."/stats", array("head" => _stats,
                                     "news" => _site_news,
                                     "stats" => $stats,
                                     "user" => _user,
                                     "dl" => _site_dl,
                                     "mysql" => _stats_mysql,
                                     "awards" => _site_awards,
                                     "cw" => _site_clanwars,
                                     "gb" =>  _site_gb,
                                     "forum" => _site_forum));

## INDEX OUTPUT ##
$title = $pagetitle." - ".$where;
page($index, $title, $where);