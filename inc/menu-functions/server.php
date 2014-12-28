<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 * Menu: Gameserver
 */

function server($serverID = 0) {
    global $picformat,$cache,$db;
    if(!fsockopen_support())
        return '<center style="margin:2px 0">'.error(_fopen,'0',false).'</center>';

    $servernavi=''; $st = 0;
    if(empty($serverID)) {
        $qry = db("SELECT id FROM ".$db['server']." WHERE navi = '1' AND game != 'nope'");
        while($get = _fetch($qry)) {
            $servernavi .= "
                <div class=\"navGameServer\" id=\"navGameServer_".$get['id']."\">
                <div style=\"width:100%;padding:10px 0;text-align:center\"><img src=\"../inc/images/ajax_loading.gif\" alt=\"\" /></div>
                <script language=\"javascript\" type=\"text/javascript\">DZCP.initDynLoader('navGameServer_".$get['id']."','server','&serverID=".$get['id']."');</script></div>";
            $st++;
        }

        return empty($servernavi) ? '<center style="margin:2px 0">'._no_server_navi.'</center>' : (!$st ? '<table class="navContent" cellspacing="0">'.$servernavi.'</table>' : $servernavi);
    } else {
        if(fsockopen_support()) {
            $sID = $_GET['serverID'];
            $get = db("SELECT * FROM ".$db['server']." WHERE `id` = ".$sID,false,true);
            $cache_hash = md5($get['ip'].':'.$get['port'].'_'.$get['game']);
            if(!$cache->isExisting('server_'.$cache_hash)) {
                $get['ip'] = str_replace(' ', '', $get['ip']);
                GameQ::addServers(array(array('id' => 'gs' ,'type' => $get['game'], 'host' => $get['ip'].':'.$get['port'], 'query_port' => empty($get['qport']) ? false : $get['qport'])));
                GameQ::setOption('timeout', 6);
                $server = GameQ::requestData();
                $server = $server['gs'];
                
                if(!empty($server) && $server && $server['game_online'] && !show_gameserver_debug)
                    $cache->set('server_'.$cache_hash, $server, config('cache_server'));
            }
            else
                $server = $cache->get('server_'.$cache_hash);

            if(!empty($server) && $server && $server['game_online']) {
                $image_status = '../inc/images/online.png'; //Server Status
                $image_secure = ''; $icon_mod = '';

                // Use protocol
                switch($server['game_protocol']) {
                    case 'source': //HL2,HL1,Brink,CODW3 etc. * Source & Goldsource
                        $game_icon = $server['game_engine'].'/'.$server['game_dir'];
                        $icon_mod = $server['game_engine'].'/'.$server['game_mod'];
                        GameQ::mkdir_img('gameicons/'.$server['game_engine']);
                    break;
                    case 'gamespy': //BF1942,BF2,BF2142,etc
                        $game_icon = $server['game_protocol'].'/'.$server['game_engine'].'/'.$server['game_dir'];
                        $icon_mod = $server['game_protocol'].'/'.$server['game_engine'].'/'.$server['game_mod'];
                        GameQ::mkdir_img('gameicons/'.$server['game_protocol'].'/'.$server['game_engine']);
                    break;
                    case 'gamespy2': //Arma 2
                        $game_icon = $server['game_protocol'].'/'.$server['game_engine'].'/'.$server['game_dir'];
                        $icon_mod = $server['game_protocol'].'/'.$server['game_engine'].'/'.$server['game_mod'];
                        GameQ::mkdir_img('gameicons/'.$server['game_protocol'].'/'.$server['game_engine']);
                    break;
                    case 'gamespy3': //Arma 3,BF2,UT3
                        $game_icon = $server['game_protocol'].'/'.$server['game_engine'].'/'.$server['game_dir'];
                        $icon_mod = $server['game_protocol'].'/'.$server['game_engine'].'/'.$server['game_mod'];
                        GameQ::mkdir_img('gameicons/'.$server['game_protocol'].'/'.$server['game_engine']);
                    break;
                    case 'bfbc2': //BFBC2
                    case 'bf4': //BF4
                    case 'bf3': //BF3
                        $game_icon = $server['game_engine'].'/'.$server['game_protocol'].'/'.$server['game_dir'];
                        $icon_mod = $server['game_engine'].'/'.$server['game_protocol'].'/'.$server['game_mod'];
                        GameQ::mkdir_img('gameicons/'.$server['game_engine'].'/'.$server['game_protocol']);
                    break;
                    case 'etqw':
                    case 'doom3':
                    case 'quake2': //Quake 2
                    case 'quake3': //Quake 3
                    case 'quake4': //Quake 4
                        $game_icon = $server['game_protocol'].'/'.$server['game_dir'];
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

                $game_icon_inp = GameQ::search_game_icon($game_icon);
                $game_icon = $game_icon_inp['image'];
                unset($game_icon_inp);

                ## List Player ##
                $players = '-';
                if(!empty($server['game_players']) && count($server['game_players']) >= 1) {
                    $players = ''; $i = 0;
                    foreach($server['game_players'] as $player)
                    { $players .= str_replace("'", '', $player['player_name']).' | '; if(!empty($player['player_name'])) $i++; }
                    $players = substr($players, 0, -3);
                    if(!$i) $players = '-';
                }

                if(!empty($server['game_hostname']))
                    db("UPDATE `".$db['server']."` SET `name` = '".up($server['game_hostname'])."' WHERE `id` = ".$get['id'].";"); //Update Hostname to DB
            } else  {
                //Server Status
                $server['game_hostname'] =  $get['name'];
                $server['game_current_players'] = '0';
                $server['game_max_players'] = '0';
                $server['game_num_bot'] = '0';
                $server['game_password'] = false;
                $server['game_map_name'] = '';
                $server['game_join_link'] = '';
                $server['game_name'] = '';
                $server['game_mod_name'] = '';
                $server['game_num_players'] = '0';
                $server['game_dedicated'] = false;
                $server['game_pwd'] = false;
                $server['game_os'] = false;

                $image_secure = ''; $players = '-';
                $image_status = '../inc/images/offline.png'; //Server Status
                $image_map = 'offline.gif'; //Map Image
                $game_icon = '../inc/images/gameicons/unknown.gif';
            }

            //Custom Icon
            if(!empty($get['custom_icon'])) {
                if(file_exists(basePath.'/inc/images/gameicons/custom/'.$get['custom_icon']))
                    $game_icon = '../inc/images/gameicons/custom/'.$get['custom_icon'];
            }

            $image_map = '<img src="../thumbgen.php?img=inc/images/maps/'.$image_map.'&width=160&height=120" class="navServerPic" alt="" />';
            $image_pwd = ($server['game_password'] ? '<img src="../inc/images/closed.png" alt="" alt="" title="Server Password" class="icon" />' : ''); //Server Password
            $dedicated = ($server['game_dedicated'] ? '<img src="../inc/images/dedicated.png" alt="" title="Dedicated Server" class="icon" />' : ''); //Dedicated Server
            $os = ($server['game_os'] ? '<img src="../inc/images/'.$server['game_os'].'_os.png" alt="" title="'.($server['game_os'] == 'windows' ? 'Windows' : 'Linux').' Server" class="icon" />' : ''); //Server OS
            $mod = (!empty($server['game_mod_name_long']) ? '<span class="fontBold">Mod:</span> '.$server['game_mod_name_long'].' <img src="'.$icon_mod.'" alt="" class="icon" /><br />' : '');
            $pwds = (!empty($get['pwd']) && permission("gs_showpw") && $server['game_password'] ? show(_server_pwd, array("pwd" => re($get['pwd']))) : '');
            $gtype = (!empty($server['game_type']) ? show(_server_gtype, array("type" => re($server['game_type']))) : '');
            $bots = (!empty($server['game_num_bot']) ? show(_server_bots, array("bots" => re($server['game_num_bot']))) : '');

            $servername = jsconvert(re(cut($server['hostname'],($servermenu=config('l_servernavi')))));
            $servernameout = (!empty($server['game_hostname'])) ? $server['game_hostname'] : _navi_gsv_no_name_available;

            $info = 'onmouseover="DZCP.showInfo(\''.$servernameout.'\', \'IP/Port:;;'._navi_gsv_game.':;Map:;'._navi_gsv_players_online.':;'._navi_gsv_on_the_game.':\', \''.$get['ip'].':'.$get['port'].';;'.jsconvert(re('<img src="'.$game_icon.'" alt=""  class="icon" />')).' '.$server['game_name_long'].';'.(array_key_exists('game_maptitle', $server) ? $server['game_maptitle'] : (empty($server['game_map']) ? '-' : $server['game_map'])).';'.$server['game_num_players'].' / '.$server['game_max_players'].';'.jsconvert(re($players)).'\')" onmouseout="DZCP.hideInfo()"';
            return show("menu/server", array("host" => cut(re($server['game_hostname']),$servermenu,true),
                                             "ip" => $get['ip'],
                                             "map" =>  (array_key_exists('game_maptitle', $server) ? $server['game_maptitle'] : (empty($server['game_map']) ? '-' : $server['game_map'])),
                                             "image_map" => $image_map,
                                             "data_gamemod" => $server['game_name_short'],
                                             "icon" => $game_icon,
                                             "pwd" => $pwds,
                                             "launch" => (empty($server['game_join_link']) ? '-' : $server['game_join_link']),
                                             "port" => $get['port'],
                                             "id" => $get['id'],
                                             "aktplayers" => $server['game_num_players'],
                                             "maxplayers" => $server['game_max_players'],
                                             "info" => $info));

        }
    }
}