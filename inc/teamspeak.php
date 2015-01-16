<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 *
 * This class is only Icon-Downloader, TSDNS & TS3 Tree Renderer
 * TS3 Query by GameQ-V2 <https://github.com/Austinb/GameQ>
 */

class TS3Renderer {
    private static $data = array();
    private static $nf_pic_ids = array();
    private static $showOnlyChannelsWithUsers = false;
    private static $AllowDownloadIcons = true;
    public static $skin_pholder = array();
    
    public static function init() {
        //-> Load Skin
        self::loadskin();
    }

    /**
     * Setzt einige Einstellungen f�r das Rendern
     * @param string $key
     * @param mixed $var
     */
    public static function setConfig($key='',$var='') {
        switch ($key) {
            case 'IconDownload': self::$AllowDownloadIcons = $var; return true; break;
            case 'OnlyChannelsWithUsers': self::$showOnlyChannelsWithUsers = $var; return true; break;
        }

        return false;
    }

    /**
     * Setzt den Input f�r den Renderer
     * @param array $gameq_data
     * @param array $sql_data
     */
    public static function set_data($gameq_data,$sql_data) { 
        self::$data = array_merge($gameq_data['ts3'],array('sql' => $sql_data)); 
    }

    /**
     * Rendert die Baumstrucktur des Teamspeak Server + User
     * @param boolean $template
     * @return string|boolean
     */
    public static function render($template=false) {
        if(!array_key_exists('channels', self::$data)) {
            return false;
        }

        $channels = self::$data['channels'];
        if(!count($channels)) {
            return false;
        }
        
        $style = ' style="text-indent:12px;"';
        $server_full = (self::$data['virtualserver_maxclients'] <= self::$data['virtualserver_clientsonline'] ? true : false); //Server Voll
        $out = '<div class="tstree_left"'.$style.'><img src="../inc/images/tsviewer/'.($server_full ? self::$skin_pholder['INFO'] : self::$skin_pholder['SERVER_GREEN']).'" alt="" class="tsicon" /> <span class="fontBold">'.self::$data['virtualserver_name'].'</span></div>'."\n";
        $out .= '<div class="tstree_right">'.self::icon(self::$data['virtualserver_icon_id']).'</div>'."\n";
        $out .= '<div class="tstree_clear"></div>'."\n";
        foreach($channels as $channel) {
            //Nur Hauptchannel, subchannel filtern + Filter f�r 'Show only channels with users'
            if(!$channel['pid'] && ((self::$showOnlyChannelsWithUsers && (($channel['total_clients_family'] > 0 && $channel['channel_flag_default'] == 0) || ($channel['total_clients_family'] > 1 && $channel['channel_flag_default']))) || !self::$showOnlyChannelsWithUsers)) {
                $players = self::renderPlayers($channel['cid'],0.05,$template); //Render Users in Channel
                $subchannel = self::sub_channel($channels,$channel['cid'],0,$template,$channel['channel_name']); //SubChannel Rendern
                $moreshow = ''; $style = ' style="text-indent:12px;"'; $div_first = ''; $div_sec = '';
                if(!empty($players) || !empty($subchannel)) {
                    $moreshow = "<img id=\"img_".($template ? 'page_' : 'box_')."cid".$channel['cid']."\" src=\"../inc/images/toggle_normal.png\" alt=\"\" class=\"tsicons\" onclick=\"DZCP.fadetoggle('".($template ? 'page_' : 'box_')."cid".$channel['cid']."')\" />";
                    $style = '';
                    $div_first = "<div id=\"more_".($template ? 'page_' : 'box_')."cid".$channel['cid']."\">\n";
                    $div_sec = "</div>";
                }

                $out .= '<div class="tstree_left"'.$style.'>'.$moreshow.'<img src="'.self::channel_icon($channel).'" alt="" class="tsicon" />'.self::channel_name($channel,$template,$channel['channel_name'],1).'</div>'."\n";
                if($template) $out .= "<div class=\"tstree_right\">".self::renderFlags($channel).self::icon($channel['channel_icon_id'])."</div>\n";
                $out .= '<div class="tstree_clear"></div>'."\n";
                $out .= $div_first;
                $out .= $players;
                $out .= $subchannel;
                $out .= $div_sec;
            }
        }

        return $out;
    }

    /**
     * Rendert die SubChannels des Teamspeak Servers
     * @param array $channels
     * @param array $channel
     * @param int $i
     * @param boolean $tpl
     * @param string $joints
     * @return string
     */
    private static function sub_channel($channels,$channel,$i,$tpl,$joints,$ebene=2) {
        if(!count($channels)) return null;
        $out = ''; $join_ts = ''; $counter=1; $sum_channels = count($channels);
        foreach($channels as $sub_channel) {
            if($channel == $sub_channel['pid']) {
                if((self::$showOnlyChannelsWithUsers && (($sub_channel['total_clients_family'] > 0 && $sub_channel['channel_flag_default'] == 0) || ($sub_channel['total_clients_family'] > 1 && $sub_channel['channel_flag_default']))) || !self::$showOnlyChannelsWithUsers) {
                    $players = self::renderPlayers($sub_channel['cid'],$i+1,$tpl);
                    $subchannel = self::sub_channel($channels,$sub_channel['cid'],$i+1,$tpl,$join_ts,($ebene+1));

                    $moreshow = ''; $style = 12; $div_first = ''; $div_sec = '';
                    if(!empty($players) || !empty($subchannel)) {
                        $moreshow = "<img id=\"img_".($tpl ? 'page_' : 'box_')."cid".$sub_channel['cid']."\" src=\"../inc/images/toggle_normal.png\" alt=\"\" class=\"tstoggle\" onclick=\"DZCP.fadetoggle('".($tpl ? 'page_' : 'box_')."cid".$sub_channel['cid']."')\" />";
                        $style = 0;
                        $div_first = "<div id=\"more_".($tpl ? 'page_' : 'box_')."cid".$sub_channel['cid']."\">\n";
                        $div_sec = "</div>";
                    }

                    $left = $i*20+$style;
                    $join_ts = $joints."/".$sub_channel['channel_name'];
                    $out .= '<div class="tstree_left" style="text-indent:'.$left.'px;">'.$moreshow.'<img src="../inc/images/tsviewer/trenner.gif" alt="" class="tstrenner" />'.
                            '<img src="'.self::channel_icon($sub_channel).'" alt="" class="tsicon" />'.self::channel_name($sub_channel,$tpl,$join_ts,$ebene).'</div>'."\n";

                    if($tpl) $out .= "<div class=\"tstree_right\">".self::renderFlags($sub_channel).self::icon($sub_channel['channel_icon_id'])."</div>\n";
                    $out .= '<div class="tstree_clear"></div>'."\n";
                    $out .= $div_first;
                    $out .= $players;
                    $out .= $subchannel;
                    $out .= $div_sec;
                }

                $counter++;
            }
        }

        return $out;
    }

    /**
     * Rendert die Channel Flags ob moderated etc.
     * @param string $channel
     * @return string | HTML Objekt
     */
    
            //self::$skin_pholder['MODERATED']
    
    public static function renderFlags($channel) {
        $flags = array(); $out = "";
        if($channel["channel_flag_default"] == 1) $flags[] = self::$skin_pholder['DEFAULT'];
        if($channel["channel_needed_talk_power"] > 0) $flags[] = self::$skin_pholder['MODERATED'];
        if($channel["channel_flag_password"] == 1) $flags[] = self::$skin_pholder['REGISTER'];
        foreach ($flags as $flag) { $out .= '<img src="../inc/images/tsviewer/' . $flag . '" alt="" class="tsicon" />'; }
        return $out;
    }

    /**
     * F�gt die Channel Name hinzu.
     * @param array $channel
     * @param boolean $tpl
     * @param string $joints
     * @return string
     */
    public static function channel_name($channel=array(),$tpl=false,$joints='',$ebene=1,$cut=false) {
        if($cut) $channel['channel_name'] = (mb_strlen($channel['channel_name']) > (30 - ($ebene * 2)) ? cut($channel['channel_name'],(30 - ($ebene * 2)),true) : $channel['channel_name'] );
        return '<a href="javascript:DZCP.popup(\'../teamspeak/login.php?ts3&amp;cName='.rawurlencode($joints).'\', \'600\', \'100\')"
        class="navTeamspeak" style="font-weight:bold;white-space:nowrap" title="'.spChars($channel['channel_name']).'">'.self::rep($channel['channel_name']).'</a>'."\n";
    }

    /**
     * F�gt die Channel Icons hinzu.
     * @param array $channel
     * @return string
     */
    public static function channel_icon($channel=array()) {
        $icon = self::$skin_pholder['CHANNEL_GREEN_SUBSCRIBED'];
        if($channel["channel_maxclients"] > -1 && ($channel["total_clients"] >= $channel["channel_maxclients"])) $icon = self::$skin_pholder['CHANNEL_RED_SUBSCRIBED'];
        else if($channel["channel_maxfamilyclients"] > -1 && ($channel["total_clients_family"] >= $channel["channel_maxfamilyclients"])) $icon = self::$skin_pholder['CHANNEL_RED_SUBSCRIBED'];
        else if($channel["channel_flag_password"]) $icon = self::$skin_pholder['CHANNEL_YELLOW_SUBSCRIBED'];
        return "../inc/images/tsviewer/".$icon;
    }

    /**
     * Render die User in einem bestimmten Channel.
     * @param int $parentId
     * @param int $i
     * @return string | Players * HTML Objekt
     */
    private static function renderPlayers($parentId,$i=0,$template=false) {
        $out = "";
        if(count(self::$data['players']) >= 1) {
            foreach(self::$data['players'] as $player) {
                if($player['cid'] == $parentId) //User befindet sich in Channel x
                    $out .= self::renderUserFlags($player,$i,$template);
            }
        }

        return $out;
    }

    /**
     * Rendert einen User + Icons
     * @param unknown_type $player
     * @return string | HTML Objekt
     */
    private static function renderUserFlags($player,$i,$template) {
        $out = '';
        if(!$player["client_type"]) {
            $player_status_icon = $player['client_is_channel_commander'] ? self::$skin_pholder['PLAYER_COMMANDER_OFF'] : self::$skin_pholder['PLAYER_OFF'];
            $player_status_icon = $player['client_is_channel_commander'] && $player['client_flag_talking'] ? self::$skin_pholder['PLAYER_COMMANDER_ON'] : $player_status_icon;
            $player_status_icon = $player['client_away'] ? self::$skin_pholder['AWAY'] : $player_status_icon;
            $player_status_icon = $player['client_flag_talking'] && !$player['client_is_channel_commander'] ? self::$skin_pholder['PLAYER_ON'] : $player_status_icon;
            $player_status_icon = !$player['client_input_hardware'] ? self::$skin_pholder['HARDWARE_INPUT_MUTED'] : $player_status_icon;
            $player_status_icon = $player['client_input_muted'] ? self::$skin_pholder['INPUT_MUTED'] : $player_status_icon;
            $player_status_icon = !$player['client_output_hardware'] ? self::$skin_pholder['HARDWARE_OUTPUT_MUTED'] : $player_status_icon;
            $player_status_icon = $player['client_output_muted'] ? self::$skin_pholder['OUTPUT_MUTED'] : $player_status_icon;
            $priority_speaker = $player['client_is_priority_speaker'] ? '<img src="../inc/images/tsviewer/'.self::$skin_pholder['CAPTURE'].'" alt="" class="tsicon" />' : '';
            $out = '<div style="text-indent:'.((string)(!$i ? '0' : $i*20+12)).'px;float:left; width:80%;"><img src="../inc/images/tsviewer/trenner.gif" alt="" class="tstrenner" /><img src="../inc/images/tsviewer/'.$player_status_icon.'" alt="" class="tsicon" /> '.$player['client_nickname'].'</div>'."\n";
            if($template) $out .= '<div style="float:right; width:20%; text-align:right;">'.$priority_speaker.self::user_groups_icons($player).'</div>'."\n";
            $out .= '<div style="clear:both;"></div>'."\n";
        }

        return $out;
    }

    /**
     * F�gt die Guppen und User Icons hinzu.
     * @param array $player
     * @return string | HTML Objekt
     */
    public static function user_groups_icons($player) {
        global $picformat; $out = '';
        $server = ($explode=explode(",",$player['client_servergroups'])) >= 1 ? $explode : array();
        $channel = ($explode=explode(",",$player['client_channel_group_id'])) >= 1 ? $explode : array();

        // Channel Gruppen
        foreach($channel as $cgroup) {
            if(array_key_exists('channel_grouplist', self::$data) && array_key_exists($cgroup, self::$data['channel_grouplist'])) {
                $channel_group_info = self::$data['channel_grouplist'][$cgroup];
                $out .= self::icon($channel_group_info['iconid'],$channel_group_info['name']);
            }
        }

        // Server Gruppen
        foreach($server as $sgroup) {
            if(array_key_exists('server_grouplist', self::$data) && array_key_exists($sgroup, self::$data['server_grouplist'])) {
                $server_group_info = self::$data['server_grouplist'][$sgroup];
                $out .= self::icon($server_group_info['iconid'],$server_group_info['name']);
            }
        }

        if($player['client_icon_id'] && self::$AllowDownloadIcons) { // User ICON
            $out .= self::icon($player['client_icon_id']);
        }

        // User Flagge
        $country = "../inc/images/flaggen/nocountry.gif";
        if(!empty($player['client_country'])) {
            foreach($picformat AS $end) {
                if(file_exists(basePath.'/inc/images/flaggen/'.strtolower($player['client_country']).'.'.$end)) { 
                    $country = '../inc/images/flaggen/'.strtolower($player['client_country']).'.'.$end; 
                    break; 
                }
            }
        }

        return $out.'<img src="'.$country.'" alt="" class="tsflag" />';
    }

    /**
     * Guppen Icons liefern und/oder runterladen.
     * @param int $id
     * @param string $title
     * @param int $cid
     * @return string | HTML Objekt
     */
    private static function icon($id,$title="",$cid=0) {
        global $cache;
        if($id == 0) return ''; $image = '';
        if($id < 0) $id = $id+4294967296;

        if(array_key_exists('GROUP_'.$id, self::$skin_pholder)) {
            $image = "../inc/images/tsviewer/".self::$skin_pholder['GROUP_'.$id];
        } else if(self::$AllowDownloadIcons) {
            $svid = md5(self::$data['sql']['host_ip_dns'].self::$data['sql']['server_port']);
            if(ts3viewer_icon_to_drive) {
                if(file_exists(basePath.'/inc/images/tsviewer/custom_icons/'.$svid.'_'.$id.'.bin')) {
                    self::$nf_pic_ids[$id] = true;
                    $file_stream = file_get_contents(basePath.'/inc/images/tsviewer/custom_icons/'.$svid.'_'.$id.'.bin');
                    $image = 'data:image/png;base64,'.base64_encode(hextobin($file_stream));
                }
            }
            
            if(!$cache->isExisting($svid.'_'.$id) && !array_key_exists($id, self::$nf_pic_ids) && empty($image)) {
                // Sende Download-Anforderung zum TS3 Server
                if(show_teamspeak_debug) {
                    DebugConsole::insert_info('TS3Renderer::icon()', 'Download Icon: "icon_'.$id.'"');
                }

                $ftInitDownload = self::ftInitDownload('/icon_'.$id,$cid);
                if(!$ftInitDownload) { self::$nf_pic_ids[$id] = true; return ''; }
                if(is_array($ftInitDownload) && array_key_exists('ftkey', $ftInitDownload) && $ftInitDownload['size']) {
                    if(show_teamspeak_debug)
                        DebugConsole::insert_info('TS3Renderer::icon()', 'Download Icon: "icon_'.$id.'" with FTKey: "'.$ftInitDownload['ftkey'].'"');

                    $file_stream=self::ftDownloadFile($ftInitDownload);
                    if(!empty($file_stream) && $file_stream != false) {
                        if(show_teamspeak_debug)
                            DebugConsole::insert_successful('TS3Renderer::icon()', 'Icon: "icon_'.$id.'" Downloaded');

                        $cache->set($svid.'_'.$id,bin2hex($file_stream),(24*60*60));//24h
                        if(ts3viewer_icon_to_drive)
                            file_put_contents(basePath.'/inc/images/tsviewer/custom_icons/'.$svid.'_'.$id.'.bin', bin2hex($file_stream));
                        
                        $image = 'data:image/png;base64,'.base64_encode($file_stream);
                        self::$nf_pic_ids[$id] = true;
                    } else
                        self::$nf_pic_ids[$id] = true;
                } else
                    self::$nf_pic_ids[$id] = true;
            } else {
                $image = ($cache->isExisting($svid.'_'.$id) ? 'data:image/png;base64,'.base64_encode(hextobin($cache->get($svid.'_'.$id))) : '');
                if(ts3viewer_icon_to_drive)
                    file_put_contents(basePath.'/inc/images/tsviewer/custom_icons/'.$svid.'_'.$id.'.bin', bin2hex($cache->get($svid.'_'.$id)));
            }
        }

        return empty($image) ? '' : '<img src="'.$image.'" alt="" class="tsicon"'.(empty($title) ? '' : ' title="'.$title.'"').' />';
    }

    /**
     * Gibt eine Liste an Server Infos aus.
     * @return string
     */
    public static function welcome() {
        $out = "<tr><td class=\"contentMainSecond\"><span class=\"fontBold\">Server Name:</span></td></tr>\n";
        $out .= "<tr><td class=\"contentMainFirst\">".(!empty(self::$data['virtualserver_name']) ? self::$data['virtualserver_name'] : '-')."<br /><br /></td></tr>\n";
        $out .= "<tr><td class=\"contentMainSecond\"><span class=\"fontBold\">Server IP/DNS:</span></td></tr>\n";
        $out .= "<tr><td class=\"contentMainFirst\">".(self::tsdns(self::$data['sql']['host_ip_dns']) ? self::$data['sql']['host_ip_dns'] : self::$data['sql']['host_ip_dns'].":".self::$data['sql']['server_port'])."<br /><br /></td></tr>\n";
        $out .= "<tr><td class=\"contentMainSecond\"><span class=\"fontBold\">Server Version:</span></td></tr>\n";

        if(array_key_exists('virtualserver_platform', self::$data)) {
            $os = '<img src="../inc/images/info/'.(self::$data['virtualserver_platform'] == 'Linux' ? 'linux' : 'windows').'_os.png" alt="" title="Server OS" class="icon" />'; //Server OS
        }

        $out .= array_key_exists('virtualserver_version', self::$data) && array_key_exists('virtualserver_platform', self::$data) ? "<tr><td class=\"contentMainFirst\">".$os." ".self::$data['virtualserver_version']."<br /><br /></td></tr>\n" : '<tr><td class="contentMainFirst">-</td></tr>';
        $out .= "<tr><td class=\"contentMainSecond\"><span class=\"fontBold\">Server Uptime:</span></td></tr>\n";
        $out .= "<tr><td class=\"contentMainFirst\">".(array_key_exists('virtualserver_uptime', self::$data) ? self::time_convert(self::$data['virtualserver_uptime']) : '-')."<br /><br /></td></tr>\n";
        $out .= "<tr><td class=\"contentMainSecond\"><span class=\"fontBold\">Channels:</span></td></tr>\n";
        $out .= "<tr><td class=\"contentMainFirst\">".(!empty(self::$data['virtualserver_channelsonline']) ? self::$data['virtualserver_channelsonline'] : '-')."<br /><br /></td></tr>\n";
        $out .= "<tr><td class=\"contentMainSecond\"><span class=\"fontBold\">Users:</span></td></tr>\n";
        $out .= "<tr><td class=\"contentMainFirst\">".(array_key_exists('players', self::$data) ? count(self::$data['players']) : '-')."<br /><br /></td></tr>\n";
        $out .= "<tr><td class=\"contentMainSecond\"><span class=\"fontBold\">Welcome Message:</span></td></tr>\n";
        $out .= "<tr><td class=\"contentMainFirst\">".(!empty(self::$data['virtualserver_welcomemessage']) ? self::rep(self::$data['virtualserver_welcomemessage']) : '-')."<br /><br /></td></tr>";
        return $out;
    }

    /**
     * Sendet eine Download-Anforderung zum Teamspeak Server
     * @param sting $name
     * @param int $cid
     * @param sting $cpw
     * @param int $seekpos
     * @return Ambigous <multitype:Ambigous, boolean, multitype:Ambigous <multitype:, boolean, multitype:boolean string mixed > >
     */
    private static function ftInitDownload($name, $cid=0, $cpw='', $seekpos=0) {
        $server = self::gethost(); $content = null;
        if(!fsockopen_support() || !ping_port($server['host'],self::$data['sql']['query_port'],4) || empty($name))
            return false;

        DebugConsole::insert_info('TS3Renderer::ftInitDownload()', 'Connect to TS3 Server on "'.$server['host'].':'.self::$data['sql']['query_port'].'" for Download');
        if($fp = @fsockopen($server['host'], self::$data['sql']['query_port'], $errnum, $errstr, 10)) {
            if(strpos(fgets($fp), 'TS3') === false) {
                DebugConsole::insert_error('TS3Renderer::ftInitDownload()', 'No connect to TS3 Server on "'.$server['host'].':'.self::$data['sql']['query_port'].'"');
                return false;
            } else {
                if(show_teamspeak_debug) {
                    DebugConsole::insert_successful('TS3Renderer::ftInitDownload()', 'Connected to TS3 Server on "'.$server['host'].':'.self::$data['sql']['query_port'].'"');
                }
            }

            $packet = "use port=%d\x0Aftinitdownload clientftfid=%d name=%s cid=%d cpw=%s seekpos=%d\x0A";
            $packet = sprintf($packet, $server['port'], rand(1,99), self::escape($name), $cid, self::escape($cpw), $seekpos);

            if(show_teamspeak_debug) {
                DebugConsole::insert_info('TS3Renderer::ftInitDownload()', 'Send Query Command: '.$packet);
            }

            ## Send Command ##
            $splittedPacket = str_split($packet, 1024);
            $splittedPacket[(count($splittedPacket) - 1)] .= "\n";
            foreach($splittedPacket as $PacketPart) {
                @fputs($fp, $PacketPart);
            } 
            
            ## Get Response ##
            do {
                $content .= @fread($fp, 4096);
                if(strpos($content, 'error id=3329 msg=connection') !== false) {
                    DebugConsole::insert_error('TS3Renderer::ftInitDownload()', 'You got banned from server on "'.$server['host'].':'.self::$data['sql']['query_port'].'"');
                    return false;
                }
            } while(strpos($content, 'msg=') === false || strpos($content, 'error id=') === false);

            if(strpos($content, 'error id=0 msg=ok') === false) {
                $splittedResponse = explode('error id=', $content);
                $chooseEnd = count($splittedResponse) - 1;
                $cutIdAndMsg = explode(' msg=', $splittedResponse[$chooseEnd]);
		DebugConsole::insert_error('TS3Renderer::ftInitDownload()', 'ErrorID: '.$cutIdAndMsg[0].' | Message: '.$this->unEscapeText($cutIdAndMsg[1]));
		return false;
            }
                
            $content = str_replace(array('error id=0 msg=ok', chr('01')), '', $content);
            $content = explode('command.', $content);
            $datasets = explode(' ', trim($content[1]));
            if(show_teamspeak_debug) {
                DebugConsole::insert_info('TS3Renderer::ftInitDownload()', 'Reserved: '.trim($content[1]));
            }
            
            $output = array();
            foreach($datasets as $dataset) {
                $dataset = explode('=', $dataset);
                if(count($dataset) > 2) {
                    for($i = 2; $i < count($dataset); $i++) {
                        $dataset[1] .= '='.$dataset[$i];
                    }
                    
                    $output[self::unescape($dataset[0])] = self::unescape($dataset[1]);
                } else {
                    if(count($dataset) == 1)
                        $output[self::unescape($dataset[0])] = '';
                    else
                        $output[self::unescape($dataset[0])] = self::unescape($dataset[1]);		
                }
            }

            if(array_key_exists('status', $output) && intval($output['status']) != 0) {
                DebugConsole::insert_error('TS3Renderer::ftInitDownload()', 'ErrorID: '.$output['status'].' | Message: '.$output['msg']. ' | Packet: '.$packet);
                return false;
            }

            $content = array(); //Filter
            foreach ($output as $key => $data) {
                if($key == 'ftkey' || $key == 'port' || $key == 'size' ) {
                    $content[$key] = $data;
                }
            }

            return $content;
        }

        return false;
    }
    
    private static function escape($text) {
        $text = str_replace("\t", '\t', $text);
        $text = str_replace("\v", '\v', $text);
        $text = str_replace("\r", '\r', $text);
        $text = str_replace("\n", '\n', $text);
        $text = str_replace("\f", '\f', $text);
        $text = str_replace(' ', '\s', $text);
        $text = str_replace('|', '\p', $text);
        return str_replace('/', '\/', $text);
    }
    
    private static function unescape($text) {
        $escapedChars = array("\t", "\v", "\r", "\n", "\f", "\s", "\p", "\/");
        $unEscapedChars = array('', '', '', '', '', ' ', '|', '/');
        return str_replace($escapedChars, $unEscapedChars, $text);
    }

    /**
     * Download eines Icons vom Teamspeak Server
     * @param unknown_type $ftInitDownload
     * @return binary|boolean
     */
    private static function ftDownloadFile($ftInitDownload) {
        $server = self::gethost();
        if(!fsockopen_support() || !$ftInitDownload || !ping_port($server['host'],$ftInitDownload['port'],1) || empty($ftInitDownload['ftkey']))
            return false;

        if(show_teamspeak_debug)
            DebugConsole::insert_info('TS3Renderer::ftDownloadFile()', 'Connect TS3 - Download Server on "'.$server['host'].':'.$ftInitDownload['port'].'"');

        if($fp=@fsockopen($server['host'], $ftInitDownload['port'], $errnum, $errstr, 4)) {
            if(show_teamspeak_debug) {
                DebugConsole::insert_info('TS3Renderer::ftDownloadFile()', 'Connected TS3 - Download Server on "'.$server['host'].':'.$ftInitDownload['port'].'"');
                DebugConsole::insert_info('TS3Renderer::ftDownloadFile()', 'Send FTKey: "'.$ftInitDownload['ftkey'].'"');
            }

            fputs($fp, $ftInitDownload['ftkey']); $content = '';
            while(strlen($content) < $ftInitDownload['size']) { 
                $content .= fgets($fp, 4096); 
            } 
            @fclose($fp);

            if(show_teamspeak_debug)
                DebugConsole::insert_successful('TS3Renderer::ftDownloadFile()', 'Downloaded: "'.strlen($content).'" Bytes from "'.$ftInitDownload['size'].'" Bytes');

            return $content;
        }

        return false;
    }

    public static function rep($var) {
        $var = preg_replace("/\[(.*?)spacer(.*?)\]/","",$var);
        return strtr($var, array(chr(194) => '','\/' => '/','\s' => ' ','\p' => '|','ö' => '','<' => '&lt;','>' => '&gt;','[URL]' => '','[/URL]' => ''));
    }

    public static function time_convert($time, $ms = false) {
        if($ms) $time = $time / 1000;

        $day = floor($time/86400);
        $hours = floor(($time%86400)/3600);
        $minutes = floor(($time%3600)/60);
        $seconds = floor($time%60);

        if($day>0)
            return $day." ".($day >= 2 ? _days : _day ).", ".$hours." ".($hours >= 2 ? _hours : _hour ).", ".$minutes." ".($minutes >= 2 ? _minutes : _minute ).", ".$seconds." ".($seconds >= 2 ? _seconds : _second );
        elseif($hours>0)
            return $hours." ".($hours >= 2 ? _hours : _hour ).", ".$minutes." ".($minutes >= 2 ? _minutes : _minute ).", ".$seconds." ".($seconds >= 2 ? _seconds : _second );
        elseif($minutes>0)
            return $minutes." ".($minutes >= 2 ? _minutes : _minute ).", ".$seconds." ".($seconds >= 2 ? _seconds : _second );
        else
            return $seconds." ".($seconds >= 2 ? _seconds : _second );
    }

    /**
     * TS3 DNS Server abfragen.
     * @param string $dns
     * @return boolean|ip
     */
    public static function tsdns($dns) {
        global $cache;
        $hash = md5('ts3dns_'.$dns);
        if(!$cache->isExisting($hash)) {
            $tsdns = self::get_tsdns($dns);
            if(is_array($tsdns)) {
                $cache->set($hash,serialize($tsdns),(30*30));
            }
            return $tsdns;
        } else
            return unserialize($cache->get($hash));
    }

    private static function get_tsdns($dns) {
        if(!ts3viewer_dns_dissolve || !ping_port($dns,41144,1)) {
            return false;
        }

        if(show_teamspeak_debug) {
            DebugConsole::insert_info('TS3Renderer::tsdns()', 'Connect to TS3 - DNS Server on "'.$dns.':41144"');
        }

        if($fp = @fsockopen($dns, 41144, $errnum, $errstr, 2)) {
            if(show_teamspeak_debug)
                DebugConsole::insert_info('TS3Renderer::tsdns()', 'Connected TS3 - DNS Server "'.$dns.':41144"');

            fputs($fp, $dns); $content = '';
            while (!feof($fp)) { $content .= fgets($fp, 1024); }
            @fclose($fp);
        } else {
            if(show_teamspeak_debug)
                DebugConsole::insert_error('TS3Renderer::tsdns()', 'Connected to TS3 - DNS Server "'.$dns.':41144" failed');

            return false;
        }
        
        if(!empty($content) && $content != false) {
            if(show_teamspeak_debug) {
                DebugConsole::insert_successful('TS3Renderer::tsdns()', 'Name resolution from DNS:"'.$dns.'" to IP:"'.$content.'"');
            }

            $epl = explode(':', $content);
            $epl[1] = str_replace('$PORT', '9987', $epl[1]);
            return array('ip' => $epl[0], 'port' => $epl[1]);
        }

        return false;
    }

    /**
     * TS3 Server Host/Port zur�ckgeben.
     * @return array|ip|port
     */
    public static function gethost() {
        $ip_port = TS3Renderer::tsdns(self::$data['sql']['host_ip_dns']);
        $host = ($ip_port != false && is_array($ip_port) ? $ip_port['ip'] : self::$data['sql']['host_ip_dns']);
        $port = ($ip_port != false && is_array($ip_port) ? $ip_port['port'] : self::$data['sql']['server_port']);
        return array('host' => $host, 'port' => $port);
    }
    
    public static function array_search_channel($key, $var, $array=array()) {
        foreach ($array as $id => $data) { 
            if($data[$key] == $var) return $array[$id]; 
        } 
        return false;
    }
    
    private static function loadskin() {
        global $picformat;
        
        if(!dbc_index::issetIndex('ts3_skin')) {
            self::$skin_pholder['AWAY'] = 'away.png';
            self::$skin_pholder['CAPTURE'] = 'capture.png';
            self::$skin_pholder['CHANNEL_GREEN_SUBSCRIBED'] = 'channel_green_subscribed.png';
            self::$skin_pholder['CHANNEL_RED_SUBSCRIBED'] = 'channel_red_subscribed.png';
            self::$skin_pholder['CHANNEL_YELLOW_SUBSCRIBED'] = 'channel_yellow_subscribed.png';
            self::$skin_pholder['GROUP_100'] = 'group_100.png';
            self::$skin_pholder['GROUP_200'] = 'group_200.png';
            self::$skin_pholder['GROUP_300'] = 'group_300.png';
            self::$skin_pholder['GROUP_400'] = 'group_400.png';
            self::$skin_pholder['GROUP_500'] = 'group_500.png';
            self::$skin_pholder['GROUP_600'] = 'group_600.png';
            self::$skin_pholder['HARDWARE_INPUT_MUTED'] = 'hardware_input_muted.png';
            self::$skin_pholder['HARDWARE_OUTPUT_MUTED'] = 'hardware_output_muted.png';
            self::$skin_pholder['INPUT_MUTED'] = 'input_muted.png';
            self::$skin_pholder['OUTPUT_MUTED'] = 'output_muted.png';
            self::$skin_pholder['MODERATED'] = 'moderated.png';
            self::$skin_pholder['PLAYER_COMMANDER_OFF'] = 'player_commander_off.png';
            self::$skin_pholder['PLAYER_COMMANDER_ON'] = 'player_commander_on.png';
            self::$skin_pholder['PLAYER_OFF'] = 'player_off.png';
            self::$skin_pholder['PLAYER_ON'] = 'player_on.png';
            self::$skin_pholder['REGISTER'] = 'register.png';
            self::$skin_pholder['SERVER_GREEN'] = 'server_green.png';
            self::$skin_pholder['DEFAULT'] = 'default.png';

            $FALLBACK = array();
            if(file_exists(basePath."/inc/images/tsviewer/".ts3viewer_skin."/settings.ini")) {
                $ini_array = parse_ini_file(basePath."/inc/images/tsviewer/".ts3viewer_skin."/settings.ini",true);
                foreach($ini_array['gfxfiles'] as $key => $var) {
                    $dir_skin = 'inc/images/tsviewer/'.ts3viewer_skin;
                    if(file_exists(basePath.'/'.$dir_skin.'/'.$var)) {
                        self::$skin_pholder[$key] = ts3viewer_skin.'/'.$var;
                    } else if ($ini_array['options']['FALLBACK']) {
                        $FALLBACK[$key] = $var;
                    } else {
                        self::$skin_pholder[$key] = 'unknown.gif';
                        if(show_teamspeak_debug) {
                            DebugConsole::insert_warning('TS3Renderer::loadskin()', "File not found: ".'/'.$dir_skin.'/'.$var);
                        }
                    }
                }
            } else { $FALLBACK = self::$skin_pholder; }

            if(count($FALLBACK) >= 1) {
                foreach($FALLBACK as $key => $var) {
                    $dir_default = 'inc/images/tsviewer/default';
                    $pic_non_format = explode('.', $var); $found = false;
                    foreach($picformat AS $end) {
                        if(file_exists(basePath.'/'.$dir_default.'/'.$pic_non_format[0].'.'.$end)) {
                            self::$skin_pholder[$key] = 'default/'.$pic_non_format[0].'.'.$end;
                            unset($FALLBACK[$key]);
                            $found = true;
                            break;
                        }
                    }

                    if(!$found) { self::$skin_pholder[$key] = 'unknown.gif'; }
                }
            }
            
            dbc_index::setIndex('ts3_skin', self::$skin_pholder);
        } else {
            self::$skin_pholder = dbc_index::getIndex('ts3_skin');
        }
    }
}
