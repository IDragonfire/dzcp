<?php
function server($serverID = 0)
{
    global $db, $servermenu, $language, $picformat;
  
    $servernavi=''; $st = 0;
    if(empty($serverID))
    {
        $qry = db("SELECT id FROM ".$db['server']." WHERE navi = '1'");
        while($get = _fetch($qry))
        {
            $servernavi .= '
                <div class="navGameServer" id="navGameServer_'.$get['id'].'">
                <div style="width:100%;padding:10px 0;text-align:center"><img src="../inc/images/ajax_loading.gif" alt="" /></div>
                <script language="javascript" type="text/javascript">DZCP.initGameServer('.$get['id'].');</script>
                </div>';
            $st++;
        }
    } 
    else
    {
        $pwd = ''; $pwd_info = ''; $pwd_txt = ''; $game_icon = ''; $players = _navi_gsv_no_players_available;
        $image_map = '../inc/images/maps/no_map.gif'; ## NoMap Picture ##
        
        $get = _fetch(db("SELECT * FROM ".$db['server']." WHERE navi = '1' AND `id` = '".intval($serverID)."'"));
        if(!function_exists('server_query_'.$get['status']) && file_exists(basePath.'/inc/server_query/'.strtolower($get['status']).'.php'))
            include(basePath.'/inc/server_query/'.strtolower($get['status']).'.php');
        else
            return 'Error "'.strtolower($get['status']).'.php" not found!';
            
        unset($server, $player_list);
        $server = @call_user_func('server_query_'.$get['status'], $get['ip'], $get['port'], $get['qport'], 'info');
        $player_list = @call_user_func('server_query_'.$get['status'], $get['ip'], $get['port'], $get['qport'], 'players');
        
        ## Check Keys ##
        $server = (empty($server) || !$server || !is_array($server) ? array() : $server);
        $player_list = (empty($player_list) || !$player_list || !is_array($player_list) ? array() : $player_list);
        $server_online = (empty($server) || !$server || !is_array($server) ? false : true);
        $server["gamemod"] = (array_key_exists('gamemod',$server) ? preg_replace("/[^A-Za-z0-9 \&\_\-]/", "_", $server["gamemod"]) : '' );
        $server["hostname"] = (array_key_exists('hostname',$server) ? htmlentities($server["hostname"], ENT_QUOTES) : $get['name']);
        $server['players'] = (array_key_exists('players',$server) ? $server["players"] : '0' );
        $server['maxplayers'] = (array_key_exists('maxplayers',$server) ? $server["maxplayers"] : '0' );
        $server['mapname'] = (array_key_exists('mapname',$server) ? preg_replace("/[^A-Za-z0-9 \&\_\-]/", "_", $server["mapname"]) : '-' );
        $map_low = str_replace(' ','_', strtolower($server["mapname"]));
        $server_name_short = (!empty($server_name_config[$server['gamemod']]) ? $server_name_config[$server['gamemod']] : (isset($server_name_short) ? $server_name_short : ''));
        $server_link = (!empty($server_link_config[$server['gamemod']]) ? $server_link_config[$server['gamemod']] : (isset($server_link) ? $server_link : ''));        
        
        ## Check GameIcons ##
		foreach($picformat AS $end)
		{
			if(file_exists(basePath.'/inc/images/gameicons/'.$get['status'].'/'.$server['gamemod'].'.'.$end))
            {
                $game_icon = '<img src="../inc/images/gameicons/'.$get['status'].'/'.$server['gamemod'].'.'.$end.'" alt="" />';
				break;
            }
		}

        ## Server Online ##
        if($server_online)
        {
            $players = '<a class="navServerStats" href="../server/?show='.$get['id'].'">'._navi_gsv_on_the_game.'</a>';
            $server['gamemod'] = strtolower((empty($server['gamemod']) ? $get['status'] : $server['gamemod']));
            
            ## Map Picture ##
            foreach($picformat AS $end)
            {
                if(file_exists(basePath.'/inc/images/maps/'.$get['status'].'/'.$server['gamemod']."/".$map_low.'.'.$end))
                {
                    $image_map = '<img src="../inc/images/maps/'.$get['status'].'/'.$server['gamemod'].'/'.$map_low.'.'.$end.'" alt="" />';
                    break;
                }
            }
            
            ## List Player ##
            if(!empty($player_list) && count($player_list) >= 1)
            {
                $players = "";
                foreach($player_list as $key=>$player)
                { $players .= str_replace("'", '', htmlentities($player['name'])) . ', '; }
            }
        }

        ## Server Passwort gesetzt ##
        if(!empty($get['pwd']) && permission("gs_showpw")) 
        {
            $pwd =  show(_server_pwd, array("pwd" => $get['pwd']));
            $pwd_info = _navi_gsv_password.':';
            $pwd_txt = $get['pwd'];
        }
        
        ## Show ##
        $servername = jsconvert(re(cut($server['hostname'],$servermenu)));
        $servernameout = (!empty($servername)) ? $servername : _navi_gsv_no_name_available;
        $info = 'onmouseover="DZCP.showInfo(\''.$servernameout.'\', \'IP/Port:;'.$pwd_info.';'._navi_gsv_game.':;Map:;'._navi_gsv_players_online.':;'._navi_gsv_on_the_game.':\', \''.$get['ip'].':'.$get['port'].';'.$pwd_txt.';'.jsconvert(re($game_icon)).''.$server_name_short.';'.(empty($server['mapname']) ? '-' : re($server['mapname'])).';'.$server['players'].' / '.$server['maxplayers'].';'.$players.'\')" onmouseout="DZCP.hideInfo()"';
        $servernavi .= show("menu/server", array("host" => re(cut($server['hostname'],$servermenu)),
                                                "ip" => $get['ip'],
                                                "map" => (empty($server['mapname']) ? '-' : re($server['mapname'])),
                                                "mappic" => $image_map,
                                                "data_gamemod" => $server_name_short,
                                                "icon" => $game_icon,
                                                "pwd" => $pwd,
                                                "launch" => strtr($server_link, array('{IP}' => $get['ip'], '{S_PORT}' => $get['port'])),
                                                "port" => $get['port'],
                                                "aktplayers" => $server['players'],
                                                "info" => $info,
                                                "maxplayers" => $server['maxplayers'],
                                                "txt_players" => _navi_gsv_on_the_game,
                                                "txt_players_view" => _navi_gsv_view_players,
                                                "txt_game" => _navi_gsv_game));
    }

    return empty($servernavi) ? '<center style="margin:2px 0">'._no_server_navi.'</center>' : ($st == 0 ? '<table class="navContent" cellspacing="0">'.$servernavi.'</table>' : $servernavi);
}
?>
