<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

if (!defined('_Server')) exit();

function server_show($sID = 0, $showID = 0) {
    global $dir,$picformat,$charset,$db,$cache,$config_cache;
    $no_ajax = !empty($sID) && $sID != 0 ? true : false;

    if(!$no_ajax)
        header("Content-Type: text/xml; charset=".$charset);

    $sID = (!empty($_GET['sID']) && $sID == 0 ? intval($_GET['sID']) : $sID);
    $get = db("SELECT * FROM ".$db['server']." WHERE `id` = ".$sID,false,true);
    $cache_hash = md5($get['ip'].':'.$get['port'].'_'.$get['game']);
    $static_server = ($get['game'] == 'nope');

    if(!$static_server) {
        if(!$config_cache['use_cache'] || !$cache->isExisting('server_'.$cache_hash)) {
            $get['ip'] = str_replace(' ', '', $get['ip']);
            DebugConsole::insert_info('server/case_ajax.php', 'Summon data from host: "'.$get['ip'].':'.$get['port'].'"');
            GameQ::addServers(array(array('id' => 'gs' ,'type' => $get['game'], 'host' => $get['ip'].':'.$get['port'], 'query_port' => empty($get['qport']) ? false : $get['qport'])));
            GameQ::setOption('timeout', 6);
            $server = GameQ::requestData();
            $server = $server['gs'];

            if(!empty($server) && $server && $server['game_online'])
                $cache->set('server_'.$cache_hash,$server,config('cache_server'));
        } else {
            $get['ip'] = str_replace(' ', '', $get['ip']);
            DebugConsole::insert_successful('server/case_ajax.php', 'Summon data from DZCP-Cache, Server: "'.$get['ip'].':'.$get['port'].'"');
            $server = $cache->get('server_'.$cache_hash);
        }
    }
    else
        $server = false;

    $show_score_td = ''; $show_deaths_td = ''; $show_skill_td = ''; $show_ranks_td = ''; $show_goal_td = ''; $show_honor_td = ''; $show_squad_td = ''; $cteam = '';
    $show_leader_td = ''; $show_ping_td = ''; $show_stats_td = ''; $show_team_td = ''; $show_time_td = ''; $klapp_show = true; $image_map_swf = '';
    if(!empty($server) && $server && $server['game_online']) {
        $admin_msg = ''; $playerstats = ''; $custom_teams = false;
        $image_status = '../inc/images/online.png'; //Server Status
        $image_secure = ''; $icon_mod = '';

        // Use protocol
        switch($server['game_protocol']) {
            case 'source': //HL2,HL1,Brink,CODW3 etc. * Source & Goldsource
                $icon_basic = $server['game_engine'].'/'.$server['game_dir'];
                $icon_mod = $server['game_engine'].'/'.$server['game_mod'];
                GameQ::mkdir_img('gameicons/'.$server['game_engine']);
            break;
            case 'gamespy': //BF1942,BF2,BF2142,etc
                    $icon_basic = $server['game_protocol'].'/'.$server['game_engine'].'/'.$server['game_dir'];
                    $icon_mod = $server['game_protocol'].'/'.$server['game_engine'].'/'.$server['game_mod'];
                    GameQ::mkdir_img('gameicons/'.$server['game_protocol'].'/'.$server['game_engine']);
            break;
            case 'gamespy2': //Arma 2
                $icon_basic = $server['game_protocol'].'/'.$server['game_engine'].'/'.$server['game_dir'];
                $icon_mod = $server['game_protocol'].'/'.$server['game_engine'].'/'.$server['game_mod'];
                GameQ::mkdir_img('gameicons/'.$server['game_protocol'].'/'.$server['game_engine']);
            break;
            case 'gamespy3': //Arma 3,BF2,UT3
                $icon_basic = $server['game_protocol'].'/'.$server['game_engine'].'/'.$server['game_dir'];
                $icon_mod = $server['game_protocol'].'/'.$server['game_engine'].'/'.$server['game_mod'];
                GameQ::mkdir_img('gameicons/'.$server['game_protocol'].'/'.$server['game_engine']);
            break;
            case 'bfbc2': //BFBC2
            case 'bf4': //BF4
            case 'bf3': //BF3
                $icon_basic = $server['game_engine'].'/'.$server['game_protocol'].'/'.$server['game_dir'];
                $icon_mod = $server['game_engine'].'/'.$server['game_protocol'].'/'.$server['game_mod'];
                GameQ::mkdir_img('gameicons/'.$server['game_engine'].'/'.$server['game_protocol']);
            break;
            case 'etqw':
            case 'doom3':
            case 'quake2': //Quake 2
            case 'quake3': //Quake 3
            case 'quake4': //Quake 4
                $icon_basic = $server['game_protocol'].'/'.$server['game_dir'];
                $icon_mod = $server['game_protocol'].'/'.$server['game_mod'];
                GameQ::mkdir_img('gameicons/'.$server['game_protocol']);
            break;
        }

        $image_secure = ($server['game_secure']['enable'] ? '<img src="../inc/images/'.$server['game_secure']['pic'].'.png" alt="" title="'.$server['game_secure']['name'].'" class="icon" />' : '');

        //Image * Maps
        $image_map = 'no_map.gif'; $pic_found = false; $flash_found = false;
        foreach($picformat AS $end) {
            if(file_exists(basePath.'/inc/images/maps/'.$server['game_map_pic_dir'].'/'.strtolower(str_ireplace(' ', '_', $server['game_map'])).'.'.$end)) {
                $pic_found = true;
                $image_map = $server['game_map_pic_dir'].'/'.strtolower(str_ireplace(' ', '_', $server['game_map'])).'.'.$end;
                break;
            }
        }

        //Detect Flash * Maps
        if(file_exists(basePath.'/inc/images/maps/'.$server['game_map_pic_dir'].'/'.strtolower(str_ireplace(' ', '_', $server['game_map'])).'.swf')) {
            $flash_found = true;
            $image_map = '../inc/images/maps/'.$server['game_map_pic_dir'].'/'.strtolower(str_ireplace(' ', '_', $server['game_map'])).'.swf';
        }

        //Admin MSG
        if((checkme() == 4 || permission('editserver')) && $server['game_use_mod']) {
            $admin_msg .= '<p><span style="color:#000;background-color:#FFF"><b style="color:red">Admin:</b> <b>Der Server scheint eine Mod zu verwenden, Mod: "'.$server['game_dir'].'" </b><br />';
            $protocols_array = GameQ::getGames();
            $block = array('teamspeak3','gamespy','gamespy2','gamespy3');
            foreach ($protocols_array AS $gameq => $info) {
                if(in_array($gameq,$block)) continue;
                if($gameq == $server['game_dir']) {
                    $admin_msg .= '<p><span style="color:#000;background-color:#FFF"><b style="color:red">Admin:</b> <b>Die Mod ist bereits bekannt, "Live-Status" auf: "'.htmlentities($info['name']).'" stellen</b><br />';
                    break;
                }
            }
        }

        //Admin MSG
        if((checkme() == 4 || permission('editserver')) && !$pic_found && !$flash_found) {
            $admin_msg = (empty($admin_msg) ? '<p>' : $admin_msg);
            $admin_msg .= '<p><span style="color:#000;background-color:#FFF"><b style="color:red">Admin:</b> <b>Mappath:</b> "inc/images/maps/'.$server['game_map_pic_dir'].'/'.strtolower(str_ireplace(' ', '_', $server['game_map'])).'.jpg"'.' oder <br />';
            $admin_msg .= '<span style="color:#000;background-color:#FFF"><b style="color:red">Admin:</b> <b>Mappath:</b> "inc/images/maps/'.$server['game_map_pic_dir'].'/'.strtolower(str_ireplace(' ', '_', $server['game_map'])).'.png"'.' oder <br />';
            $admin_msg .= '<span style="color:#000;background-color:#FFF"><b style="color:red">Admin:</b> <b>Mappath:</b> "inc/images/maps/'.$server['game_map_pic_dir'].'/'.strtolower(str_ireplace(' ', '_', $server['game_map'])).'.gif"'.' oder <br />';
            $admin_msg .= '<span style="color:#000;background-color:#FFF"><b style="color:red">Admin:</b> <b>Mappath:</b> "inc/images/maps/'.$server['game_map_pic_dir'].'/'.strtolower(str_ireplace(' ', '_', $server['game_map'])).'.swf"'.'<br />';
        }

        //Admin MSG
        $icon_basic_inp = GameQ::search_game_icon($icon_basic);
        if((checkme() == 4 || permission('editserver')) && !$icon_basic_inp['found']) {
            $admin_msg = (empty($admin_msg) ? '<p>' : $admin_msg);
            $admin_msg .= '<span style="color:#000;background-color:#FFF"><b style="color:red">Admin:</b> <b>Game-Iconpath:</b> "inc/images/gameicons/'.$icon_basic.'.jpg"'.' oder <br />';
            $admin_msg .= '<span style="color:#000;background-color:#FFF"><b style="color:red">Admin:</b> <b>Game-Iconpath:</b> "inc/images/gameicons/'.$icon_basic.'.png"'.' oder <br />';
            $admin_msg .= '<span style="color:#000;background-color:#FFF"><b style="color:red">Admin:</b> <b>Game-Iconpath:</b> "inc/images/gameicons/'.$icon_basic.'.gif"'.'<br />';
        }
        $icon_basic = $icon_basic_inp['image'];
        unset($icon_basic_inp);

        //Admin MSG
        if(!empty($server['game_mod'])) {
            $icon_mod_inp = GameQ::search_game_icon($icon_mod);
            if((checkme() == 4 || permission('editserver')) && !$icon_mod_inp['found']) {
                $admin_msg = (empty($admin_msg) ? '<p>' : $admin_msg);
                $admin_msg .= '<span style="color:#000;background-color:#FFF"><b style="color:red">Admin:</b> <b>GameMod-Iconpath:</b> "inc/images/gameicons/'.$icon_mod.'.jpg"'.' oder <br />';
                $admin_msg .= '<span style="color:#000;background-color:#FFF"><b style="color:red">Admin:</b> <b>GameMod-Iconpath:</b> "inc/images/gameicons/'.$icon_mod.'.png"'.' oder <br />';
                $admin_msg .= '<span style="color:#000;background-color:#FFF"><b style="color:red">Admin:</b> <b>GameMod-Iconpath:</b> "inc/images/gameicons/'.$icon_mod.'.gif"'.'<br />';
            }
            $icon_mod = $icon_mod_inp['image'];
            unset($icon_mod_inp);
        }
        else $icon_mod = '';

        // Userliste & Stats
        $colspan = 1; $show_score_td = ''; $show_deaths_td = ''; $show_ranks_td = ''; $show_skill_td = ''; $show_goal_td = ''; $show_honor_td = '';
        $show_leader_td = ''; $show_ping_td = ''; $show_stats_td = ''; $show_team_td = ''; $show_squad_td = ''; $show_time_td = '';
        if($server['game_stats_options']['stats_score'] )   { $colspan++; $show_score_td = '<td width="60" class="contentHead"><span class="fontBold">Score</span></td>'; }
        if($server['game_stats_options']['stats_deaths'] )  { $colspan++; $show_deaths_td = '<td width="60" class="contentHead"><span class="fontBold">Deaths</span></td>'; }
        if($server['game_stats_options']['stats_rank'] )    { $colspan++; $show_ranks_td = '<td width="60" class="contentHead"><span class="fontBold">Rank</span></td>'; }
        if($server['game_stats_options']['stats_kills'] )   { $colspan++; $show_skill_td = '<td width="60" class="contentHead"><span class="fontBold">Kills</span></td>'; }
        if($server['game_stats_options']['stats_goal'] )    { $colspan++; $show_goal_td = '<td width="60" class="contentHead"><span class="fontBold">Goal</span></td>'; }
        if($server['game_stats_options']['stats_honor'] )   { $colspan++; $show_honor_td = '<td width="60" class="contentHead"><span class="fontBold">Honor</span></td>'; }
        if($server['game_stats_options']['stats_leader'] )  { $colspan++; $show_leader_td = '<td width="60" class="contentHead"><span class="fontBold">Leader</span></td>'; }
        if($server['game_stats_options']['stats_ping'] )    { $colspan++; $show_ping_td = '<td width="60" class="contentHead"><span class="fontBold">Ping</span></td>'; }
        if($server['game_stats_options']['stats_stats'] )   { $colspan++; $show_stats_td = '<td width="60" class="contentHead"><span class="fontBold">Stats</span></td>'; }
        if($server['game_stats_options']['stats_team'] )    { $colspan++; $show_team_td = '<td width="60" class="contentHead"><span class="fontBold">Team</span></td>'; }
        if($server['game_stats_options']['stats_squad'] )   { $colspan++; $show_squad_td = '<td width="60" class="contentHead"><span class="fontBold">Squad</span></td>'; }
        if($server['game_stats_options']['stats_time'] )    { $colspan++; $show_time_td = '<td width="220" class="contentHead"><span class="fontBold">'._server_time.'</span></td>'; }

        $playerstats = _server_noplayers;
        if(!empty($server['game_players']) && count($server['game_players']) >= 1) {
            $playerstats = ''; $color = 1;  $i = 0;
            foreach($server['game_players'] as $player) {
                $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
                $show_score  = (!empty($show_score_td)  ? '<td class="'.$class.'" align="center">'.$player['player_score'].'</td>' : '');
                $show_deaths = (!empty($show_deaths_td) ? '<td class="'.$class.'" align="center">'.$player['player_deaths'].'</td>' : '');
                $show_ranks  = (!empty($show_ranks_td)  ? '<td class="'.$class.'" align="center">'.$player['player_rank'].'</td>' : '');
                $show_skill  = (!empty($show_skill_td)  ? '<td class="'.$class.'" align="center">'.$player['player_kills'].'</td>' : '');
                $show_goal   = (!empty($show_goal_td)   ? '<td class="'.$class.'" align="center">'.$player['player_goal'].'</td>' : '');
                $show_honor  = (!empty($show_honor_td)  ? '<td class="'.$class.'" align="center">'.$player['player_honor'].'</td>' : '');
                $show_leader = (!empty($show_leader_td) ? '<td class="'.$class.'" align="center">'.$player['player_leader'].'</td>' : '');
                $show_ping   = (!empty($show_ping_td)   ? '<td class="'.$class.'" align="center">'.$player['player_ping'].'ms</td>' : '');
                $show_stats  = (!empty($show_stats_td)  ? '<td class="'.$class.'" align="center">'.$player['player_stats'].'</td>' : '');
                $show_team   = (!empty($show_team_td)   ? '<td class="'.$class.'" align="center">'.ucfirst($server['game_teams'][$player['player_team']]['team_name']).'</td>' : $cteam);
                $show_squads = (!empty($show_squad_td)  ? '<td class="'.$class.'" align="center">'.$player['player_squad'].'</td>' : '');
                $show_time   = (!empty($show_time_td)   ? '<td class="'.$class.'" align="center">'.TS3Renderer::time_convert($player['player_time']).'</td>' : '');

                $playerstats .= show($dir."/playerstats", array("name" => $player['player_name'], "class" => $class, "show_score" =>  $show_score, "show_deaths" => $show_deaths,
                "show_skill" =>  $show_skill, "show_goal" => $show_goal, "show_honor" => $show_honor, "show_leader" => $show_leader, "show_ping" => $show_ping, "show_team" => $show_team,
                "show_stats" => $show_stats, "show_time" => $show_time, "show_ranks" => $show_ranks, "show_squads" => $show_squads));
                if(!empty($player['player_name'])) $i++;
            }

            if(!$i) $playerstats = _server_noplayers;
        }

        if(!empty($server['game_hostname']))
            db("UPDATE `".$db['server']."` SET `name` = '".up($server['game_hostname'])."' WHERE `id` = ".$get['id'].";"); //Update Hostname to DB
    } else {
        //Server Status
        $server['game_hostname'] =  $get['name'];
        $server['game_current_players'] = '0';
        $server['game_max_players'] = '0';
        $server['game_num_bot'] = '0';
        $server['game_password'] = false;
        $server['game_map_name'] = '';
        $server['game_join_link'] = '';
        $server['game_name'] = '';
        $server['game_name_long'] = '';
        $server['game_mod_name'] = '';
        $server['game_num_players'] = '0';
        $server['game_dedicated'] = false;
        $server['game_pwd'] = false;
        $server['game_os'] = false;

        $image_secure = ''; $playerstats = '';
        $image_status = '../inc/images/offline.png'; //Server Status
        $image_map = 'offline.gif'; //Map Image
        $admin_msg = ''; $klapp_show = false; $flash_found = false;
        $icon_basic = '../inc/images/gameicons/unknown.gif';
    }

    if($static_server) {
        $image_map = 'no_map.gif'; //Map Image
        $image_status = '../inc/images/static.png'; //Server Status
    }

    //Custom Icon
    if(!empty($get['custom_icon'])) {
        if(file_exists(basePath.'/inc/images/gameicons/custom/'.$get['custom_icon']))
            $icon_basic = '../inc/images/gameicons/custom/'.$get['custom_icon'];
    }

    $display = "none"; $moreicon = "expand";
    if(isset($_GET['showID']) && $showID == 0 ? $_GET['showID'] == $get['id'] : false)
    { $display = "show"; $moreicon = "collapse"; }
    else if($showID != 0 ? $showID == $get['id'] : false)
    { $display = "show"; $moreicon = "collapse"; }

    if($flash_found) {
        $s = strtoupper(md5(uniqid(rand(),true)));
        $clsid =  substr($s,0,8) .'-'.substr($s,8,4).'-'.substr($s,12,4).'-'.substr($s,16,4).'-'. substr($s,20);
        $image_map  = '<script type="text/javascript">swfobject.embedSWF("'.$image_map.'", "'.$clsid.'", "160", "120", "9.0.0", "../inc/images/flash/expressInstall.swf");</script>';
        $image_map .= '<div id="'.$clsid.'"><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></div>';
    } else
        $image_map = '<a href="../inc/images/maps/'.$image_map.'" rel="lightbox[server_'.$get['id'].']"><img src="../thumbgen.php?img=inc/images/maps/'.$image_map.'&width=160&height=120" class="ServerPic" alt="" /></a>';

    $image_pwd = ($server['game_password'] ? '<img src="../inc/images/closed.png" alt="" alt="" title="Server Password" class="icon" />' : ''); //Server Password
    $dedicated = ($server['game_dedicated'] ? '<img src="../inc/images/dedicated.png" alt="" title="Dedicated Server" class="icon" />' : ''); //Dedicated Server
    $os = ($server['game_os'] ? '<img src="../inc/images/info/'.$server['game_os'].'_os.png" alt="" title="'.($server['game_os'] == 'windows' ? 'Windows' : 'Linux').' Server" class="icon" />' : ''); //Server OS
    $mod = (!empty($server['game_mod_name_long']) ? '<span class="fontBold">Mod:</span> '.$server['game_mod_name_long'].' <img src="'.$icon_mod.'" alt="" class="icon" /><br />' : '');
    $pwds = (!empty($get['pwd']) && permission("gs_showpw") && $server['game_password'] ? show(_server_pwd, array("pwd" => re($get['pwd']))) : '');
    $gtype = (!empty($server['game_type']) ? show(_server_gtype, array("type" => re($server['game_type']))) : '');
    $bots = (!empty($server['game_num_bot']) ? show(_server_bots, array("bots" => re($server['game_num_bot']))) : '');
    $klapp = show(_klapptext_server_link, array("link" => _server_splayerstats, "id" => $get['id'], "moreicon" => $moreicon));

    $index = show($dir."/server_show", array("showscore" => $show_score_td,
                                             "showdeaths" => $show_deaths_td,
                                             "showskill" => $show_skill_td,
                                             "showgoal" => $show_goal_td,
                                             "showhonor" => $show_honor_td,
                                             "showleader" => $show_leader_td,
                                             "showping" => $show_ping_td,
                                             "showteam" => $show_team_td,
                                             "showstats" => $show_stats_td,
                                             "showtime" => $show_time_td,
                                             "showsquad" => $show_squad_td,
                                             "showranks" => $show_ranks_td,
                                             "status_img" => $image_status,
                                             "mod" => $mod,
                                             "pwd_img" => $image_pwd,
                                             "colspan" => (empty($colspan) ? '' : ' colspan="'.$colspan.'"'),
                                             "game_descr" => $server['game_name_long'],
                                             "admin_msg" => $admin_msg,
                                             "port" => $get['port'],
                                             "aktplayers" => $server['game_num_players'],
                                             "maxplayers" => $server['game_max_players'],
                                             "botplayers" => $bots,
                                             "map" =>  (array_key_exists('game_maptitle', $server) ? $server['game_maptitle'] : (empty($server['game_map']) ? '-' : $server['game_map'])),
                                             "launch" => (empty($server['game_join_link']) ? '-' : $server['game_join_link']),
                                             "icon" => $icon_basic,
                                             "id" => $get['id'],
                                             "display" => $display,
                                             "pwd" => $pwds,
                                             "gtype" => $gtype,
                                             "klapp" => $klapp,
                                             "ip" => $get['ip'],
                                             "playerstats" => $playerstats,
                                             "name" => cut(re($server['game_hostname']),70,true),
                                             "av_icons" => $image_secure.$dedicated.$os,
                                             "image_map" => $image_map,
                                             "klapp_show_start" => (!$klapp_show ? '<!--' : ''),
                                             "klapp_show_end" => (!$klapp_show ? '-->' : '')));
    if($no_ajax) return $index; else exit($index);
}

if(isset($_GET['sID'])) server_show();