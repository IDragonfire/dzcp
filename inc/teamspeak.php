<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
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

    /**
     * Setzt einige Einstellungen für das Rendern
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
     * Setzt den Input für den Renderer
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
        $out = '<div class="tstree_left"'.$style.'><img src="../inc/images/tsviewer/'.($server_full ? 'server_full' : 'server_open').'.png" alt="" class="tsicon" /> <span class="fontBold">'.self::$data['virtualserver_name'].'</span></div>'."\n";
        $out .= '<div class="tstree_right">'.self::icon(self::$data['virtualserver_icon_id']).'</div>'."\n";
        $out .= '<div class="tstree_clear"></div>'."\n";
        foreach($channels as $channel) {
            //Nur Hauptchannel, subchannel filtern + Filter für 'Show only channels with users'
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
                        $moreshow = "<img id=\"img_".($tpl ? 'page_' : 'box_')."cid".$sub_channel['cid']."\" src=\"../inc/images/toggle_normal.png\" alt=\"\" class=\"tsicons\" onclick=\"DZCP.fadetoggle('".($tpl ? 'page_' : 'box_')."cid".$sub_channel['cid']."')\" />";
                        $style = 0;
                        $div_first = "<div id=\"more_".($tpl ? 'page_' : 'box_')."cid".$sub_channel['cid']."\">\n";
                        $div_sec = "</div>";
                    }

                    $left = $i*20+$style;
                    $join_ts = $joints."/".$sub_channel['channel_name'];
                    $out .= '<div class="tstree_left" style="text-indent:'.$left.'px;">'.$moreshow.'<img src="../inc/images/tsviewer/trenner.gif" alt="" class="tsicon" />'.
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
    public static function renderFlags($channel) {
        $flags = array(); $out = "";
        if($channel["channel_flag_default"] == 1) $flags[] = 'channel_flag_default.png';
        if($channel["channel_needed_talk_power"] > 0) $flags[] = 'channel_flag_moderated.png';
        if($channel["channel_flag_password"] == 1) $flags[] = 'channel_flag_password.png';
        foreach ($flags as $flag) $out .= '<img src="../inc/images/tsviewer/' . $flag . '" alt="" class="icon" />';
        return $out;
    }

    /**
     * Fügt die Channel Name hinzu.
     * @param array $channel
     * @param boolean $tpl
     * @param string $joints
     * @return string
     */
    public static function channel_name($channel=array(),$tpl=false,$joints='',$ebene=1,$cut=false) {
        if($cut) $channel['channel_name'] = (mb_strlen($channel['channel_name']) > (30 - ($ebene * 2)) ? cut($channel['channel_name'],(30 - ($ebene * 2)),true) : $channel['channel_name'] );
        return '<a href="javascript:DZCP.popup(\'../teamspeak/login.php?ts3&amp;cName='.rawurlencode($joints).'\', \'600\', \'100\')"
        class="navTeamspeak" style="font-weight:bold;white-space:nowrap" title="'.$channel['channel_name'].'">'.self::rep($channel['channel_name']).'</a>'."\n";
    }

    /**
     * Fügt die Channel Icons hinzu.
     * @param array $channel
     * @return string
     */
    public static function channel_icon($channel=array()) {
        $icon = "channel_open.png";
        if($channel["channel_maxclients"] > -1 && ($channel["total_clients"] >= $channel["channel_maxclients"])) $icon = "channel_full.png";
        else if($channel["channel_maxfamilyclients"] > -1 && ($channel["total_clients_family"] >= $channel["channel_maxfamilyclients"])) $icon = "channel_full.png";
        else if($channel["channel_flag_password"] == 1) $icon = "channel_pass.png";
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
            $player_status_icon = $player['client_is_channel_commander'] ? "client_cc_idle.png" : "client_idle.png";
            $player_status_icon = $player['client_is_channel_commander'] && $player['client_flag_talking'] ? "client_cc_talk.png" : $player_status_icon;
            $player_status_icon = $player['client_away'] ? "client_away.png" : $player_status_icon;
            $player_status_icon = $player['client_flag_talking'] && !$player['client_is_channel_commander'] ? "client_talk.png" : $player_status_icon;
            $player_status_icon = !$player['client_output_hardware'] ? "client_snd_disabled.png" : $player_status_icon;
            $player_status_icon = $player['client_output_muted'] ? "client_snd_muted.png" : $player_status_icon;
            $player_status_icon = !$player['client_input_hardware'] ? "client_mic_disabled.png" : $player_status_icon;
            $player_status_icon = $player['client_input_muted'] ? "client_mic_muted.png" : $player_status_icon;
            $priority_speaker = $player['client_is_priority_speaker'] ? '<img src="../inc/images/tsviewer/client_priority.png" alt="" class="tsicon" />' : '';
            $out = '<div style="text-indent:'.((string)(!$i ? '0' : $i*20+12)).'px;float:left; width:80%;"><img src="../inc/images/tsviewer/trenner.gif" alt="" class="tsicon" /><img src="../inc/images/tsviewer/'.$player_status_icon.'" alt="" class="tsicon" /> '.$player['client_nickname'].'</div>'."\n";
            if($template) $out .= '<div style="float:right; width:20%; text-align:right;">'.$priority_speaker.self::user_groups_icons($player).'</div>'."\n";
            $out .= '<div style="clear:both;"></div>'."\n";
        }

        return $out;
    }

    /**
     * Fügt die Guppen und User Icons hinzu.
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

        return $out.'<img src="'.$country.'" alt="" class="tsicon" />';
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

        if(in_array($id, array('100','200','300','400','500','600')))
            $image = "../inc/images/tsviewer/group_icon_".$id.".png";
        else if(self::$AllowDownloadIcons) {
            if(!$cache->isExisting('ts_icon_'.$id) && !in_array($id, self::$nf_pic_ids)) {
                // Sende Download-Anforderung zum TS3 Server
                if(show_teamspeak_debug)
                    DebugConsole::insert_info('TS3Renderer::icon()', 'Download Icon: "icon_'.$id.'"');

                $ftInitDownload = self::ftInitDownload(((string)('/icon_'.$id)),$cid);
                if(is_array($ftInitDownload) && array_key_exists('ftkey', $ftInitDownload) && $ftInitDownload['size']) {
                    if(show_teamspeak_debug)
                        DebugConsole::insert_info('TS3Renderer::icon()', 'Download Icon: "icon_'.$id.'" with FTKey: "'.$ftInitDownload['ftkey'].'"');

                    $file_stream=self::ftDownloadFile($ftInitDownload);
                    if(!empty($file_stream) && $file_stream != false) {
                        if(show_teamspeak_debug)
                            DebugConsole::insert_successful('TS3Renderer::icon()', 'Icon: "icon_'.$id.'" Downloaded');

                        $cache->set('ts_icon_'.$id,bin2hex($file_stream),(24*60*60));//24h
                        $image = 'data:image/png;base64,'.base64_encode($file_stream);
                    } else
                        self::$nf_pic_ids[$id] = true;
                } else
                    self::$nf_pic_ids[$id] = true;
            } else
                $image = 'data:image/png;base64,'.base64_encode(hextobin($cache->get('ts_icon_'.$id)));
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
        $server = self::gethost();
        if(!fsockopen_support() || !ping_port($server['host'],self::$data['sql']['query_port'],4) || empty($name))
            return false;

        DebugConsole::insert_info('TS3Renderer::ftInitDownload()', 'Connect to TS3 Server on "'.$server['host'].':'.self::$data['sql']['query_port'].'" for Download');
        if($fp = @fsockopen($server['host'], self::$data['sql']['query_port'], $errnum, $errstr, 10)) {
            if(show_teamspeak_debug)
                DebugConsole::insert_info('TS3Renderer::ftInitDownload()', 'Connected to TS3 Server on "'.$server['host'].':'.self::$data['sql']['query_port'].'"');

            $find = array('\\\\',"\/","\s","\p","\a","\b","\f","\n","\r","\t","\v");
            $rplc = array(chr(92),chr(47),chr(32),chr(124),chr(7),chr(8),chr(12),chr(10),chr(3),chr(9),chr(11));
            $packet = "use port=%d\x0Aftinitdownload clientftfid=%d name=\%s cid=%d cpw=%s seekpos=%d\x0A";
            $packet = sprintf($packet, $server['port'], rand(1,99), $name, $cid, $cpw, $seekpos);

            if(show_teamspeak_debug)
                DebugConsole::insert_info('TS3Renderer::ftInitDownload()', 'Send Query Command: '.$packet);

            @fputs($fp, $packet); $content = '';

            while(strpos($content, 'msg=') === false) { 
                $content .= @fread($fp, 8096); 
            }

            if(show_teamspeak_debug) {
                $ext = explode('specific command.', $content);
                DebugConsole::insert_info('TS3Renderer::ftInitDownload()', 'Reserved: '.$ext[1]);
            }

            if(!empty($content) && !strstr($content, 'error id=0'))
                return false;

            $datas = array();
            $rawItems = explode("|", $content);
            foreach ($rawItems as $rawItem) {
                $rawDatas = explode(" ", $rawItem);
                $tempDatas = array();
                foreach($rawDatas as $rawData) {
                    $ar = explode("=", $rawData, 2);
                    $tempDatas[$ar[0]] = isset($ar[1]) ? str_replace($find, $rplc, $ar[1]) : "";
                }

                $datas[] = $tempDatas;
            }

            $content = array(); //Filter
            foreach ($datas[0] as $key => $data) {
                if($key == 'ftkey' || $key == 'port' || $key == 'size' )
                    $content[$key] = ($key == 'size' || $key == 'port' ? intval(str_replace('error', '', $data)) : $data);
            }

            return $content;
        }

        return false;
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
        return strtr($var, array(chr(194) => '','\/' => '/','\s' => ' ','\p' => '|','Ã¶' => '','<' => '&lt;','>' => '&gt;','[URL]' => '','[/URL]' => ''));
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
        if(!ping_port($dns,41144,1)) {
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
     * TS3 Server Host/Port zurückgeben.
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
}