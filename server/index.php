<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$where = _site_server;
$dir = "server";

## SECTIONS ##
switch ($action):
default:
  if(fsockopen_support())
  {
    $qry = db("SELECT * FROM ".$db['server']." ORDER BY game");
    while($get = _fetch($qry))
    {
        $player_list = '';
        if($get['status'] != "nope" && file_exists(basePath.'/inc/server_query/'.$get['status'].'.php'))
        {
          if(!$config_cache['use_cache'] || !$cache->isExisting('gameserver_'.intval($get['id']).'_'.$language))
          {
          if(!function_exists('server_query_'.$get['status']))
          {
            include(basePath.'/inc/server_query/'.strtolower($get['status']).'.php');
          }

          $server = call_user_func('server_query_'.$get['status'], $get['ip'], $get['port'], $get['qport'], 'info');

          if(!$server)
          {
            $image_status         = "../inc/images/offline.gif";
            $image_map            = "../inc/images/maps/offline.gif";
            $image_pwd            = "";
            $server['hostname']   = "Server Offline";
            $server['mapname']    = "unknown";
            $server['players']    = 0;
            $server['maxplayers'] = 0;
          } else {
            $server["mapname"] = preg_replace("/[^A-Za-z0-9 \&\_\-]/", "_", $server["mapname"]);
            $map_low = str_replace(' ','_', strtolower($server["mapname"]));
            $image_map = "../inc/images/maps/".$get['status']."/".$server['gamemod']."/".$map_low.".jpg";

            if(!file_exists($image_map))
            {
              if($chkMe == 4) $mappath = '<span style="color:#000;background-color:#FFF"><b style="color:red">Admin:</b> <b>Mappath:</b> '.str_replace(basePath, '', $image_map).'<br />';
              $image_map = "../inc/images/maps/no_map.gif";
            }

            $image_status = "../inc/images/online.gif";
            $image_pwd = "";
            $server['gamemod'] = strtolower((empty($server['gamemod']) ? $get['status'] : $server['gamemod']));
            if($server['password'])
            {
              $image_pwd = "<img src=\"../inc/images/closed.gif\" alt=\"\" class=\"icon\" /> ";
              $server['status'] = "ONLINE WITH PASSWORD";
            }
          }

          $server['hostname'] = htmlentities($server['hostname'], ENT_QUOTES);
          $game_icon = file_exists(basePath.'/inc/images/gameicons/'.$get['status'].'/'.$server['gamemod'].'.gif')
                     ? '<img src="../inc/images/gameicons/'.$get['status'].'/'.$server['gamemod'].'.gif" alt="" />' : '';

          unset($player_list);
          $player_list = call_user_func('server_query_'.$get['status'], $get['ip'], $get['port'], $get['qport'], 'players');

          if(empty($player_list))
          {
            $playerstats = _server_noplayers;
          } else {
            if(isset($player_list[1]['score']))  $score  = 1; else $score   = 0;
            if(isset($player_list[1]['deaths'])) $deaths = 1; else $deaths  = 0;
            if(isset($player_list[1]['skill']))  $skill  = 1; else $skill   = 0;
            if(isset($player_list[1]['goal']))   $goal   = 1; else $goal    = 0;
            if(isset($player_list[1]['honor']))  $honor  = 1; else $honor   = 0;
            if(isset($player_list[1]['leader'])) $leader = 1; else $leader  = 0;
            if(isset($player_list[1]['ping']))   $ping   = 1; else $ping    = 0;
            if(isset($player_list[1]['stats']))  $stats  = 1; else $stats   = 0;
            if(isset($player_list[1]['time']))   $time   = 1; else $time    = 0;

            if($score == 1)  $showscore   = '<td width="60" class="contentHead"><span class="fontBold">Score</span></td>';
            else             $showscore   = '';
            if($deaths == 1) $showdeaths  = '<td width="60" class="contentHead"><span class="fontBold">Deaths</span></td>';
            else             $showdeaths  = '';
            if($skill == 1)  $showskill   = '<td width="60" class="contentHead"><span class="fontBold">Skill</span></td>';
            else             $showskill   = '';
            if($goal == 1)   $showgoal    = '<td width="60" class="contentHead"><span class="fontBold">Goal</span></td>';
            else             $showgoal    = '';
            if($honor == 1)  $showhonor   = '<td width="60" class="contentHead"><span class="fontBold">Honor</span></td>';
            else             $showhonor   = '';
            if($leader == 1) $showleader  = '<td width="60" class="contentHead"><span class="fontBold">Leader</span></td>';
            else             $showleader  = '';
            if($ping == 1)   $showping    = '<td width="60" class="contentHead"><span class="fontBold">Ping</span></td>';
            else             $showping    = '';
            if($stats == 1)  $showstats   = '<td width="60" class="contentHead"><span class="fontBold">Stats</span></td>';
            else             $showstats   = '';
            if($time == 1)   $showtime    = '<td width="90" class="contentHead"><span class="fontBold">'._server_time.'</span></td>';
            else             $showtime   = '';

            unset($playerstats);
            foreach($player_list as $key=>$player)
            {
              $player['name'] = htmlentities($player['name'], ENT_QUOTES);
              $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

              if($score == 1)  { $colspan++; $show_score   = '<td class="'.$class.'" align="center">'.$player['score'].'</td>'; }
              else             $show_score   = '';
              if($deaths == 1) { $colspan++; $show_deaths  = '<td class="'.$class.'" align="center">'.$player['deaths'].'</td>'; }
              else             $show_deaths  = '';
              if($skill == 1)  { $colspan++; $show_skill   = '<td class="'.$class.'" align="center">'.$player['skill'].'</td>'; }
              else             $show_skill   = '';
              if($goal == 1)   { $colspan++; $show_goal    = '<td class="'.$class.'" align="center">'.$player['goal'].'</td>'; }
              else             $show_goal    = '';
              if($honor == 1)  { $colspan++; $show_honor   = '<td class="'.$class.'" align="center">'.$player['honor'].'</td>'; }
              else             $show_honor   = '';
              if($leader == 1) { $colspan++; $show_leader  = '<td class="'.$class.'" align="center">'.$player['leader'].'</td>'; }
              else             $show_leader  = '';
              if($ping == 1)   { $colspan++; $show_ping    = '<td class="'.$class.'" align="center">'.$player['ping'].'</td>'; }
              else             $show_ping    = '';
              if($stats == 1)  { $colspan++; $show_stats   = '<td class="'.$class.'" align="center">'.$player['stats'].'</td>'; }
              else             $show_stats   = '';
              if($time == 1)   { $colspan++; $show_time    = '<td class="'.$class.'" align="center">'.$player['time'].'</td>'; }
              else             $show_time    = '';

              $playerstats .= show($dir."/playerstats", array("name" => $player['name'],
                                                              "class" => $class,
                                                              "id" => $get['id'],
                                                              "show_score" => $show_score,
                                                              "show_deaths" => $show_deaths,
                                                              "show_skill" => $show_skill,
                                                              "show_goal" => $show_goal,
                                                              "show_honor" => $show_honor,
                                                              "show_leader" => $show_leader,
                                                              "show_ping" => $show_ping,
                                                              "show_team" => $show_team,
                                                              "show_stats" => $show_stats,
                                                              "show_time" => $show_time,
                                                              "show_skin" => $show_skin));
            }
          }

          if(!empty($server_name_config[$server['gamemod']]))
            $server_name = $server_name_config[$server['gamemod']][0];

          if(!empty($server_link_config[$server['gamemod']]))
            $server_link = $server_link_config[$server['gamemod']];

          if(!empty($get['pwd']) && permission("gs_showpw")) $pwds = show(_server_pwd, array("pwd" => re($get['pwd'])));
          else $pwds = "";

          if($_GET['show'] == $get['id'])
          {
            $display = "show";
            $moreicon = "collapse";
          } else {
            $display = "none";
            $moreicon = "expand";
          }

          $klapp = show(_klapptext_server_link, array("link" => _server_splayerstats,
                                                      "id" => $get['id'],
                                                      "moreicon" => $moreicon));

          $index .= show($dir."/server_show", array("showscore" => $showscore,
                                                    "showdeaths" => $showdeaths,
                                                    "showskill" => $showskill,
                                                    "showgoal" => $showgoal,
                                                    "showhonor" => $showhonor,
                                                    "showleader" => $showleader,
                                                    "showping" => $showping,
                                                    "showteam" => $showteam,
                                                    "showstats" => $showstats,
                                                    "showtime" => $showtime,
                                                    "showskin" => $showskin,
                                                    "status_img" => $image_status,
                                                    "pwd_img" => $image_pwd,
                                                    "colspan" => (empty($colspan) ? '' : ' colspan="'.$colspan.'"'),
                                                                        "data_status" => $server['status'],
                                                                        "data_gametype" => $server['gametype'],
                                                                        "data_gamemod" => re($server_name),
                                                                        "launch" => strtr($server_link, array('{IP}' => $get['ip'], '{S_PORT}' => $get['port'])),
                                                                        "port" => $get['port'],
                                                    "aktplayers" => $server['players'],
                                                    "maxplayers" => $server['maxplayers'],
                                                    "map" => (empty($server['mapname']) ? '-' : re($server['mapname'])),
                                                    "rawmap" => re($server['mapname']),
                                                    "icon" => $game_icon,
                                                    "gamename" => $gamename,
                                                    "game" => _game,
                                                    "mapfolder" => $mapfolder,
                                                    "id" => $get['id'],
                                                    "display" => $display,
                                                    "pwd" => $pwds,
                                                    "shown" => $shown,
                                                    "sip" => _server_ip,
                                                    "players" => _server_players,
                                                    "aktmap" => _server_aktmap,
                                                    "nick" => _nick,
                                                    "klapp" => $klapp,
                                                    "frags" => _server_frags,
                                                    "time" => _server_time,
                                                    "ip" => $get['ip'],
                                                    "playerstats" => $playerstats,
                                                    "name" => re($server['hostname']),
                                                    "mappath" => $mappath,
                                                    "image_map" => $image_map));

          if($config_cache['use_cache'])
              $cache->set('gameserver_'.intval($get['id']).'_'.$language, $index, config('cache_server'));
        } else {
            $index = $cache->get('gameserver_'.intval($get['id']).'_'.$language);
        }
      } else {
        if(!empty($get['pwd'])) $pwds = show(_server_pwd, array("pwd" => re($get['pwd'])));
        else $pwds = "";

        $gameicon = show(_gameicon, array("icon" => $get['game']));
        $index .= show($dir."/server_show_nope", array("name" => re($get['name']),
                                                       "ip" => $get['ip'],
                                                       "icon" => $gameicon,
                                                       "pwd" => $pwds,
                                                       "port" => $get['port']));
      }
    }
  } else {
    $index = error(_fopen);
  }
break;
endswitch;

## INDEX OUTPUT ##
$title = $pagetitle." - ".$where;
page($index, $title, $where);