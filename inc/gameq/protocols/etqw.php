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
 * Enemy Territory: Quake Wars Protocol Class
 *
 * @author Austin Bischoff <austin@codebeard.com>
 */
class GameQ_Protocols_Etqw extends GameQ_Protocols
{
    /**
     * Array of packets we want to look up.
     * Each key should correspond to a defined method in this or a parent class
     *
     * @var array
     */
    protected $packets = array(
    self::PACKET_STATUS => "\xFF\xFFgetInfoEx\x00\x00\x00\x00",
    //self::PACKET_STATUS => "\xFF\xFFgetInfo\x00\x00\x00\x00\x00",
    );

    /**
     * Methods to be run when processing the response(s)
     *
     * @var array
     */
    protected $process_methods = array(
        "process_status",
    );

    /**
     * Default port for this server type
     *
     * @var int
     */
    protected $port = 27733; // Default port, used if not set when instanced

    /**
     * The protocol being used
     *
     * @var string
     */
    protected $protocol = 'etqw';

    /**
     * String name of this protocol class
     *
     * @var string
     */
    protected $name = 'etqw';

    /**
     * Longer string name of this protocol class
     *
     * @var string
     */
    protected $name_long = "Enemy Territory: Quake Wars";
    protected $name_short = "ETQW";
    protected $basic_game_dir = 'baseETQW-1';

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
    protected $settings_filter = array();

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
            throw new GameQ_ProtocolsException('Enemy Territor: Quake Wars status has more than 1 packet');
        }

        // Make buffer so we can check this out
        $buf = new GameQ_Buffer($packets[0]);

        // Grab the header
        $header = $buf->readString();

        // Now lets verify the header
        if(!strstr($header, 'infoExResponse'))
        {
            throw new GameQ_ProtocolsException('Unable to match Enemy Territor: Quake Wars response header. Header: '. $header);
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

        // Now burn the challenge, version and size
        $buf->skip(16);

        // Key / value pairs
        while ($buf->getLength())
        {
            $var = str_replace('si_', '', $buf->readString());
            $val = $buf->readString();

            if (empty($var) && empty($val))
            {
                break;
            }

            // Add the server prop
            $result->add($var, $val);
        }

        // Now let's do the basic player info
        $this->parsePlayers($buf, $result);

        // Now grab the rest of the server info
        $result->add('osmask',     $buf->readInt32());
        $result->add('ranked',     $buf->readInt8());
        $result->add('timeleft',   $buf->readInt32());
        $result->add('gamestate',  $buf->readInt8());
        $result->add('servertype', $buf->readInt8());

        // 0: regular server
        if ($result->get('servertype') == 0)
        {
            $result->add('interested_clients', $buf->readInt8());
        }
        // 1: tv server
        else
        {
            $result->add('connected_clients', $buf->readInt32());
            $result->add('max_clients',       $buf->readInt32());
        }

        // Now let's parse the extended player info
        $this->parsePlayersExtra($buf, $result);

        // Free some memory
        unset($sections, $buf, $data);

        // Return the result
        return $result->fetch();
    }

    /**
     * Parse the players and add them to the return.
     *
     * @param GameQ_Buffer $buf
     * @param GameQ_Result $result
     */
    protected function parsePlayers(GameQ_Buffer &$buf, GameQ_Result &$result)
    {
        $players = 0;

        while (($id = $buf->readInt8()) != 32)
        {
            $result->addPlayer('id',           $id);
            $result->addPlayer('ping',         $buf->readInt16());
            $result->addPlayer('name',         $buf->readString());
            $result->addPlayer('clantag_pos',  $buf->readInt8());
            $result->addPlayer('clantag',      $buf->readString());
            $result->addPlayer('bot',          $buf->readInt8());

            $players++;
        }

        // Let's add in the current players as a result
        $result->add('numplayers', $players);

        // Free some memory
        unset($id);
    }

    /**
     * Parse the players extra info and add them to the return.
     *
     * @param GameQ_Buffer $buf
     * @param GameQ_Result $result
     */
    protected function parsePlayersExtra(GameQ_Buffer &$buf, GameQ_Result &$result)
    {
        while (($id = $buf->readInt8()) != 32)
        {
            $result->addPlayer('total_xp',     $buf->readFloat32());
            $result->addPlayer('teamname',     $buf->readString());
            $result->addPlayer('total_kills',  $buf->readInt32());
            $result->addPlayer('total_deaths', $buf->readInt32());
        }

        // @todo: Add team stuff

        // Free some memory
        unset($id);
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
            DebugConsole::insert_info('GameQ_Protocols_Etqw::process_dzcp_runtime()', 'The basic-gamedir differs from servers gamedir, use a mod?');
            DebugConsole::insert_info('GameQ_Protocols_Etqw::process_dzcp_runtime()', 'Basic: "'.$this->basic_game_dir.'" <=> Server: "'.$this->server_data_stream['gamename'].'" on Server '.$this->server_data_stream['gq_address'].':'.$this->server_data_stream['gq_port']);
            $result->add('game_use_mod', true);
            $result->add('game_mod_dir', $this->server_data_stream['gamename']);
        }
        else
            $result->add('game_use_mod', false);

        $secure = array('enable' => $this->server_data_stream['net_serverPunkbusterEnabled'] == '1' ? true : false, 'pic' => 'punkbuster', 'name' => 'PunkBuster');
        $this->server_data_stream['name'] = preg_replace("/\^./", "", $this->server_data_stream['name']);

        // Set the result to a new result instance
        $result->add('game_name_long', $this->is_mod ? $this->basic_game_long : $this->name_long);
        $result->add('game_name_short', $this->is_mod ? $this->basic_game_short : $this->name_short);
        $result->add('game_mod_name_long', $this->is_mod ? $this->name_long : $mod_name_long);
        $result->add('game_mod_name_short', $this->is_mod ? $this->name_short : $mod_name_short);
        $result->add('game_hostname',htmlentities($this->server_data_stream['name'], ENT_QUOTES, "UTF-8"));
        $result->add('game_map', re(str_replace('maps/', '', $this->server_data_stream['map'])));
        $result->add('game_map_pic_dir', 'id_tech_4/etqw'.($this->is_mod || $is_mod_ml ? '/'.$this->server_data_stream['gamename'] : '/'.$this->basic_game_dir) );
        $result->add('game_type',ucfirst($this->server_data_stream['si_gameType']));
        $result->add('game_dir', !$this->is_mod && !$is_mod_ml ? 'etqw' : $this->server_data_stream['gamename']);
        $result->add('game_mod', $this->is_mod || $is_mod_ml ? $this->server_data_stream['gamename'] : '');
        $result->add('game_country',$this->server_data_stream['.Location']);
        $result->add('game_region','');
        $result->add('game_os', ''); //Server OS
        $result->add('game_dedicated', $this->server_data_stream['net_serverDedicated'] == '1' ? true : false);
        $result->add('game_hltv', false);
        $result->add('game_num_players', array_key_exists('players', $this->server_data_stream) ? count($this->server_data_stream['players']) : '0');
        $result->add('game_max_players', $this->server_data_stream['maxPlayers']);
        $result->add('game_num_bot', '');
        $result->add('game_password', $this->server_data_stream['si_usepass'] == '1' ? true : false);
        $result->add('game_secure', $secure);
        $result->add('game_engine', 'id_tech_4');
        $result->add('game_protocol', $this->server_data_stream['gq_protocol']);
        $result->add('game_transport', $this->server_data_stream['gq_transport']);
        $result->add('game_port', $this->server_data_stream['gq_port']);
        $result->add('game_address', $this->server_data_stream['gq_address']);
        $result->add('game_join_link', '');
        $result->add('game_online', $this->server_data_stream['gq_online'] == '1' ? true : false);

        if($this->server_data_stream['gq_online'] == '1')
            GameQ::mkdir_img('maps/id_tech_4/etqw/'.($this->is_mod || $is_mod_ml ? $this->server_data_stream['gamename'] : $this->basic_game_dir));

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

        $player_list = array(); $player_index = array(); $teams = array();
        if(array_key_exists('players', $this->server_data_stream) && count($this->server_data_stream['players']) >= 1)
        {
            foreach($this->server_data_stream['players'] as $player)
            {
                $player['name'] = preg_replace("/\^./", "", $player['name']);
                $playername = htmlentities($player['name'], ENT_QUOTES, "UTF-8");
                if(empty($playername) && !server_show_empty_players || key_exists($playername, $player_index) || $player['bot']) { continue; }
                $player_index[$playername] = true;
                $new_player = array();
                $new_player['player_name'] = $playername;
                $new_player['player_score'] = '0';
                $new_player['player_time'] = '0';
                $new_player['player_team'] = (string)(empty($player['teamname']) ? '0' : $player['teamname']);
                $new_player['player_squad'] = '0';
                $new_player['player_kills'] = (string)(empty($player['total_kills']) ? '0' : $player['total_kills']);
                $new_player['player_deaths'] = (string)(empty($player['total_deaths']) ? '0' : $player['total_deaths']);
                $new_player['player_rank'] = '0';
                $new_player['player_ping'] = (string)(empty($player['ping']) ? '0' : $player['ping']);
                $new_player['player_honor'] = '0';
                $new_player['player_goal'] = '0';
                $new_player['player_leader'] = '0';
                $new_player['player_stats'] = '0';
                $player_list[] = $new_player;

                $teams[(string)(empty($player['teamname']) ? '0' : $player['teamname'])] = array('team_name' => (string)(empty($player['teamname']) ? '0' : $player['teamname']), 'team_score' => 0);
            }
            unset($player_index);
        }

        //Player Stats
        $this->stats_options['stats_score']  = array_key_exists(($key_opt='stats_score'), $this->stats_options)  ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_time']   = array_key_exists(($key_opt='stats_time'), $this->stats_options)   ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_team']   = array_key_exists(($key_opt='stats_team'), $this->stats_options)   ? $this->stats_options[$key_opt] : true;
        $this->stats_options['stats_squad']  = array_key_exists(($key_opt='stats_squad'), $this->stats_options)  ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_kills']  = array_key_exists(($key_opt='stats_kills'), $this->stats_options)  ? $this->stats_options[$key_opt] : true;
        $this->stats_options['stats_deaths'] = array_key_exists(($key_opt='stats_deaths'), $this->stats_options) ? $this->stats_options[$key_opt] : true;
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

        /*
         * Teams
        */
        $result->add('game_teams',$teams);

        $this->server_data_stream = $result->fetch();
    }
}
