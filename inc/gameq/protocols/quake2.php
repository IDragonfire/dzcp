<?php
/**
 * This file is part of GameQ.
 *
 * GameQ is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * GameQ is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Quake 2 Protocol Class
 *
 * This class is used as the basis for all game servers
 * that use the Quake 2 protocol for querying
 * server status.
 *
 * @author Austin Bischoff <austin@codebeard.com>
 */
class GameQ_Protocols_Quake2 extends GameQ_Protocols
{
    /**
     * Array of packets we want to look up.
     * Each key should correspond to a defined method in this or a parent class
     *
     * @var array
     */
    protected $packets = array(self::PACKET_STATUS => "\xFF\xFF\xFF\xFFstatus\x00");

    /**
     * Methods to be run when processing the response(s)
     *
     * @var array
     */
    protected $process_methods = array("process_status");

    /**
     * Default port for this server type
     *
     * @var int
     */
    protected $port = 27910; // Default port, used if not set when instanced

    /**
     * The protocol being used
     *
     * @var string
     */
    protected $protocol = 'quake2';

    /**
     * String name of this protocol class
     *
     * @var string
     */
    protected $name = 'quake2';

    /**
     * Longer string name of this protocol class
     *
     * @var string
     */
    protected $name_long = "Quake 2";
    protected $name_short = "Q2";

    /**
     * Longer string name of this protocol class
     *
     * @var string
     */
    protected $basic_game_dir = 'baseq2';

    /**
     * Handelt es sich um eine Mod
     *
     * @var boolean
     */
    protected $is_mod = false;

    /**
     * Mod List
     *
     * @var array
     */
    protected $modlist = array();

    /**
     * Set settings filter
     *
     * @var array
    */
    protected $settings_filter = array('gamedate','sv_noobquad','maxspectators','sv_handicap','INFO2','INFO1','cheats','timelimit','fraglimit','dmflags','version');

    /**
     * Show Stats Options
     *
     * @var array
    */
    protected $stats_options = array();

    /*
     * Internal methods
     */

    protected function preProcess_status($packets)
    {
        // Should only be one packet
        if (count($packets) > 1)
        {
            throw new GameQ_ProtocolsException('Quake 2 status has more than 1 packet');
        }

        // Make buffer so we can check this out
        $buf = new GameQ_Buffer($packets[0]);

        // Grab the header
        $header = $buf->read(11);

        // Now lets verify the header
        if($header != "\xFF\xFF\xFF\xFFprint\x0a\\")
        {
            throw new GameQ_ProtocolsException('Unable to match Gamespy 2 status response header. Header: '. $header);
            return FALSE;
        }

        // Return the data with the header stripped, ready to go.
        return $buf->getBuffer();
    }

    /**
     * Process the server status
     *
     * @throws GameQ_ProtocolsException
     */
    protected function process_status()
    {
        // Make sure we have a valid response
        if(!$this->hasValidResponse(self::PACKET_STATUS))
        {
            return array();
        }

        // Set the result to a new result instance
        $result = new GameQ_Result();

        // Lets pre process and make sure these things are in the proper order by id
        $data = $this->preProcess_status($this->packets_response[self::PACKET_STATUS]);

        // Make buffer
        $buf = new GameQ_Buffer($data);

        // First section is the server info, the rest is player info
        $server_info = $buf->readString("\x0A");
        $player_info = $buf->getBuffer();

        unset($buf);

        // Make a new buffer for the server info
        $buf_server = new GameQ_Buffer($server_info);

        // Key / value pairs
        while ($buf_server->getLength())
        {
            $result->add($buf_server->readString('\\'),$buf_server->readStringMulti(array('\\', "\x0a"), $delimfound));

            if ($delimfound === "\x0a")
            {
                break;
            }
        }

        // Now send the rest to players
        $this->parsePlayers($result, $player_info);

        // Free some memory
        unset($sections, $player_info, $server_info, $delimfound, $buf_server, $data);

        // Return the result
        return $result->fetch();
    }

    /**
     * Parse the players and add them to the return.
     *
     * This is overloadable because it seems that different games return differen info.
     *
     * @param GameQ_Result $result
     * @param string $players_info
     */
    protected function parsePlayers(GameQ_Result &$result, $players_info)
    {
        // Explode the arrays out
        $players = explode("\x0A", $players_info);

        // Remove the last array item as it is junk
        array_pop($players);

        // Add total number of players
        $result->add('num_players', count($players));

        // Loop the players
        foreach($players AS $player_info)
        {
            $buf = new GameQ_Buffer($player_info);

            // Add player info
            $result->addPlayer('frags', $buf->readString("\x20"));
            $result->addPlayer('ping',  $buf->readString("\x20"));

            // Skip first "
            $buf->skip(1);

            // Add player name
            $result->addPlayer('name', trim($buf->readString('"')));

            // Skip first "
            $buf->skip(2);

            // Add address
            $result->addPlayer('address', trim($buf->readString('"')));
        }

        // Free some memory
        unset($buf, $players, $player_info);
    }

    /*
     * ######################################################################################
    * #################################### DZCP RUNTIME ####################################
    * ######################################################################################
    */
    protected function process_dzcp_runtime()
    {
        $result = new GameQ_Result();
        if(!$this->server_data_stream['gq_online'])
        {
            $result->add('game_online', false);
            $this->server_data_stream = $result->fetch();
            return;
        }

        $mod_name_long = ''; $mod_name_short = ''; $is_mod_ml = false;
        if(!$this->is_mod && count($this->modlist) && array_key_exists($this->server_data_stream['gamename'], $this->modlist))
        {
            $mod_name_long = $this->modlist[$this->server_data_stream['gamename']]['name_long'];
            $mod_name_short = $this->modlist[$this->server_data_stream['gamename']]['name_short'];
            $is_mod_ml = true;
        }

        if(!$this->is_mod && !$is_mod_ml && $this->basic_game_dir != $this->server_data_stream['gamename'])
        {
            DebugConsole::insert_info('GameQ_Protocols_Quake2::process_dzcp_runtime()', 'The basic-gamedir differs from servers gamedir, use a mod?');
            DebugConsole::insert_info('GameQ_Protocols_Quake2::process_dzcp_runtime()', 'Basic: "'.$this->basic_game_dir.'" <=> Server: "'.$this->server_data_stream['gamename'].'" on Server '.$this->server_data_stream['gq_address'].':'.$this->server_data_stream['gq_port']);
            $result->add('game_use_mod', true);
            $result->add('game_mod_dir', $this->server_data_stream['gamename']);
        }
        else
            $result->add('game_use_mod', false);

        $secure = array('enable' => $this->server_data_stream['anticheat'] == '1' ? true : false, 'pic' => 'shield', 'name' => 'R1Q2 Anticheat');

        // Set the result to a new result instance
        $result->add('game_name_long', $this->is_mod ? $this->basic_game_long : $this->name_long);
        $result->add('game_name_short', $this->is_mod ? $this->basic_game_short : $this->name_short);
        $result->add('game_mod_name_long', $this->is_mod ? $this->name_long : $mod_name_long);
        $result->add('game_mod_name_short', $this->is_mod ? $this->name_short : $mod_name_short);
        $result->add('game_hostname',htmlentities($this->server_data_stream['hostname'], ENT_QUOTES, "UTF-8"));
        $result->add('game_map', re($this->server_data_stream['mapname']));
        $result->add('game_map_pic_dir', 'quake2/'.($this->is_mod || $is_mod_ml ? '/'.$this->server_data_stream['gamename'] : $this->basic_game_dir) );
        $result->add('game_type','');//ucfirst($this->server_data_stream['deathmatch']));
        $result->add('game_dir', !$this->is_mod && !$is_mod_ml ? 'quake2' : $this->server_data_stream['gamename']);
        $result->add('game_mod', $this->is_mod || $is_mod_ml ? $this->server_data_stream['gamename'] : '');
        $result->add('game_country','');
        $result->add('game_region','');
        $result->add('game_os', stripos($this->server_data_stream['version'], 'Linux') === false ? 'windows' : 'linux'); //Server OS
        $result->add('game_dedicated', false);
        $result->add('game_hltv', false);
        $result->add('game_num_players', $this->server_data_stream['num_players']);
        $result->add('game_max_players', $this->server_data_stream['maxclients']);
        $result->add('game_num_bot', '');
        $result->add('game_password', false);
        $result->add('game_secure', $secure);
        $result->add('game_engine', 'quake');
        $result->add('game_protocol', $this->server_data_stream['gq_protocol']);
        $result->add('game_transport', $this->server_data_stream['gq_transport']);
        $result->add('game_port', $this->server_data_stream['gq_port']);
        $result->add('game_address', $this->server_data_stream['gq_address']);
        $result->add('game_join_link', '');
        $result->add('game_online', $this->server_data_stream['gq_online'] == '1' ? true : false);

        if($this->server_data_stream['gq_online'] == '1')
            GameQ::mkdir_img('maps/quake2/'.($this->is_mod || $is_mod_ml ? $this->server_data_stream['gamename'] : $this->basic_game_dir));

        /*
         * Custom settings
        */
        $custom_settings = array();
        foreach($this->server_data_stream as $key => $data)
        {
            if(in_array($key, $this->settings_filter))
                $custom_settings[$key] = $data;
        }
        $result->add('game_custom',$custom_settings);
        unset($custom_settings);

        /*
         * Teams
        */
        $result->add('game_teams',array());

        $player_list = array(); $player_index = array();
        if(array_key_exists('players', $this->server_data_stream) && count($this->server_data_stream['players']) >= 1)
        {
            foreach($this->server_data_stream['players'] as $player)
            {
                $player['name'] = preg_replace("/\^./", "", $player['name']);
                $playername = htmlentities($player['name'], ENT_QUOTES, "UTF-8");
                if(empty($playername) && !server_show_empty_players || key_exists($playername, $player_index)) { continue; }
                $player_index[$playername] = true;
                $new_player = array();
                $new_player['player_name'] = $playername;
                $new_player['player_score'] = '0';
                $new_player['player_time'] = '0';
                $new_player['player_team'] = '0';
                $new_player['player_squad'] = '0';
                $new_player['player_kills'] = (string)(empty($player['ping']) ? '0' : $player['frags']);
                $new_player['player_deaths'] = '0';
                $new_player['player_rank'] = '0';
                $new_player['player_ping'] = (string)(empty($player['ping']) ? '0' : $player['ping']);
                $new_player['player_honor'] = '0';
                $new_player['player_goal'] = '0';
                $new_player['player_leader'] = '0';
                $new_player['player_stats'] = '0';
                $player_list[] = $new_player;
            }
            unset($player_index);
        }

        //Player Stats
        $this->stats_options['stats_score']  = array_key_exists(($key_opt='stats_score'), $this->stats_options)  ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_time']   = array_key_exists(($key_opt='stats_time'), $this->stats_options)   ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_team']   = array_key_exists(($key_opt='stats_team'), $this->stats_options)   ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_squad']  = array_key_exists(($key_opt='stats_squad'), $this->stats_options)  ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_kills']  = array_key_exists(($key_opt='stats_kills'), $this->stats_options)  ? $this->stats_options[$key_opt] : true;
        $this->stats_options['stats_deaths'] = array_key_exists(($key_opt='stats_deaths'), $this->stats_options) ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_rank']   = array_key_exists(($key_opt='stats_rank'), $this->stats_options)   ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_ping']   = array_key_exists(($key_opt='stats_ping'), $this->stats_options)   ? $this->stats_options[$key_opt] : true;
        $this->stats_options['stats_honor']  = array_key_exists(($key_opt='stats_honor'), $this->stats_options)  ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_goal']   = array_key_exists(($key_opt='stats_goal'), $this->stats_options)   ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_leader'] = array_key_exists(($key_opt='stats_leader'), $this->stats_options) ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_stats']  = array_key_exists(($key_opt='stats_stats'), $this->stats_options)  ? $this->stats_options[$key_opt] : false;
        $result->add('game_stats_options',$this->stats_options);

        switch(isset($_GET['spsort']) ? $_GET['spsort'] : 'kills') //Sort
        {
            case 'score': if($this->stats_options['stats_score']) $player_list = GameQ::record_sort($player_list, 'player_score', true); break;
            case 'time': if($this->stats_options['stats_time']) $player_list = GameQ::record_sort($player_list, 'player_time', true); break;
            case 'team': if($this->stats_options['stats_team']) $player_list = GameQ::record_sort($player_list, 'player_team', true); break;
            case 'squad': if($this->stats_options['stats_squad']) $player_list = GameQ::record_sort($player_list, 'player_squad', true); break;
            case 'kills': if($this->stats_options['stats_kills']) $player_list = GameQ::record_sort($player_list, 'player_kills', true); break;
            case 'deaths': if($this->stats_options['stats_deaths']) $player_list = GameQ::record_sort($player_list, 'player_deaths', true); break;
            case 'rank': if($this->stats_options['stats_rank']) $player_list = GameQ::record_sort($player_list, 'player_rank', true); break;
            case 'ping': if($this->stats_options['stats_ping']) $player_list = GameQ::record_sort($player_list, 'player_ping', true); break;
            case 'honor': if($this->stats_options['stats_honor']) $player_list = GameQ::record_sort($player_list, 'player_honor', true); break;
            case 'goal': if($this->stats_options['stats_goal']) $player_list = GameQ::record_sort($player_list, 'player_goal', true); break;
            case 'leader': if($this->stats_options['stats_leader']) $player_list = GameQ::record_sort($player_list, 'player_leader', true); break;
            case 'stats': if($this->stats_options['stats_stats']) $player_list = GameQ::record_sort($player_list, 'player_stats', true); break;
        }

        $result->add('game_players',$player_list);
        unset($player_list);

        $this->server_data_stream = $result->fetch();
    }
}
