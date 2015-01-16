<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */
if (!defined('_Teamspeak')) exit();

function teamspeak_show($sID = 0, $showID = 0) {
    global $dir,$db,$charset,$config_cache,$cache;
    $no_ajax = !empty($sID) && $sID != 0 ? true : false;

    if(!$no_ajax)
        header("Content-Type: text/xml; charset=".$charset);

    $sID = (!empty($_GET['sID']) && $sID == 0 ? intval($_GET['sID']) : $sID);
    $Show_sID = (!empty($_GET['show']) && $showID == 0 ? intval($_GET['show']) : $showID);
    $get = db("SELECT * FROM `".$db['ts']."` WHERE `id` = ".$sID." LIMIT 1",false,true);
    $ip_port = TS3Renderer::tsdns($get['host_ip_dns']);

    $host = ($ip_port != false && is_array($ip_port) ? $ip_port['ip'] : $get['host_ip_dns']);
    $port = ($ip_port != false && is_array($ip_port) ? $ip_port['port'] : $get['server_port']);

    if(!ping_port($host,$get['query_port'],2)) {
        if(!ping_port($host,$get['query_port'],2) && !$no_ajax)
            die('<br /><center>'._no_connect_to_ts.'</center><br />');
        else
            return '<tr><td class="contentMainTop"><br /><center>'._no_connect_to_ts.'</center><br /></td></tr>';
    }

    $cache_hash = md5($host.':'.$port);
    if(!$config_cache['use_cache'] || !$cache->isExisting('teamspeak_'.$cache_hash)) {
        GameQ::addServers(array(array('id' => 'ts3' ,'type' => 'teamspeak3', 'host' => $host.':'.$port, 'query_port' => $get['query_port'])));
        GameQ::setOption('timeout', 6);
        $results = GameQ::requestData();
        if(!empty($results) && $results)
            $cache->set('teamspeak_'.$cache_hash,$results,config('cache_teamspeak'));
    } else
        $results = $cache->get('teamspeak_'.$cache_hash);
    
    //Put to Renderer
    TS3Renderer::init();
    TS3Renderer::set_data($results,$get);
    TS3Renderer::setConfig('IconDownload',$get['customicon']);
    TS3Renderer::setConfig('OnlyChannelsWithUsers',$get['showchannel']);

    $userstats = ''; $users = 0; $color = 1;
    if(isset($results['ts3']['players']) && count($results['ts3']['players']) >= 1 && count($results['ts3']['channels']) >= 1) {
        foreach($results['ts3']['players'] AS $player) {
            if(!$player["client_type"]) { $users++;
                $player_status_icon = $player['client_is_channel_commander'] ? TS3Renderer::$skin_pholder['PLAYER_COMMANDER_OFF'] : TS3Renderer::$skin_pholder['PLAYER_OFF'];
                $player_status_icon = $player['client_is_channel_commander'] && $player['client_flag_talking'] ? TS3Renderer::$skin_pholder['PLAYER_COMMANDER_ON'] : $player_status_icon;
                $player_status_icon = $player['client_away'] ? TS3Renderer::$skin_pholder['AWAY'] : $player_status_icon;
                $player_status_icon = $player['client_flag_talking'] && !$player['client_is_channel_commander'] ? TS3Renderer::$skin_pholder['PLAYER_ON'] : $player_status_icon;
                $player_status_icon = !$player['client_input_hardware'] ? TS3Renderer::$skin_pholder['HARDWARE_INPUT_MUTED'] : $player_status_icon;
                $player_status_icon = $player['client_input_muted'] ? TS3Renderer::$skin_pholder['INPUT_MUTED'] : $player_status_icon;
                $player_status_icon = !$player['client_output_hardware'] ? TS3Renderer::$skin_pholder['HARDWARE_OUTPUT_MUTED'] : $player_status_icon;
                $player_status_icon = $player['client_output_muted'] ? TS3Renderer::$skin_pholder['OUTPUT_MUTED'] : $player_status_icon;
                $priority_speaker = $player['client_is_priority_speaker'] ? '<img src="../inc/images/tsviewer/'.TS3Renderer::$skin_pholder['CAPTURE'].'" alt="" class="tsicon" />' : '';
                $player['client_nickname'] = (mb_strlen(html_entity_decode($player['client_nickname'])) > 20 ? cut($player['client_nickname'],20,true) : $player['client_nickname'] );
                $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

                $channel = TS3Renderer::array_search_channel('cid', $player['cid'], $results['ts3']['channels']);
                $userstats .= show($dir."/userstats", array("player" => '<img src="../inc/images/tsviewer/'.$player_status_icon.'" alt="" class="tsicon" />&nbsp;'.TS3Renderer::rep($player['client_nickname']),
                                                            "player_icons" => $priority_speaker.TS3Renderer::user_groups_icons($player),
                                                            "channel" => '<img src="'.TS3Renderer::channel_icon($channel).'" alt="" class="tsicon" /> '.TS3Renderer::channel_name($channel,true,'',1,true),
                                                            "class" => $class,
                                                            "misc1" => TS3Renderer::time_convert(time()-$player['client_lastconnected']),
                                                            "misc2" => TS3Renderer::time_convert($player['client_idle_time'],true)));
            }
        }
    }

    if(!$users) $userstats = _server_nousers;
    if(!empty($Show_sID) && $Show_sID != 0 && $Show_sID == $get['id'])
    { $display = "show"; $moreicon = "collapse"; } else { $display = "none"; $moreicon = "expand"; }
    $klapp = show(_klapptext_server_link, array("link" => empty($results['ts3']['virtualserver_name']) ? 'Error on this V-Server!' : $results['ts3']['virtualserver_name'], "id" => $get['id'], "moreicon" => $moreicon));
    $index = show($dir."/servers", array("id" => $get['id'], "user" => $users, "display" => $display, "klapp" => $klapp, "uchannels" => TS3Renderer::render(true), "info" => parse_ts3(TS3Renderer::welcome()), "userstats" => $userstats));
    if($no_ajax) return $index; else exit($index);
}

if(isset($_GET['sID'])) teamspeak_show();