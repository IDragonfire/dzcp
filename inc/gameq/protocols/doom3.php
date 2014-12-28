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
 * Doom3 Protocol Class
 *
 * @author Austin Bischoff <austin@codebeard.com>
 */
class GameQ_Protocols_Doom3 extends GameQ_Protocols
{
    /**
     * Array of packets we want to look up.
     * Each key should correspond to a defined method in this or a parent class
     *
     * @var array
     */
    protected $packets = array(self::PACKET_ALL => "\xFF\xFFgetInfo\x00PiNGPoNG\x00");

    /**
     * Methods to be run when processing the response(s)
     *
     * @var array
     */
    protected $process_methods = array("process_all");

    /**
     * Default port for this server type
     *
     * @var int
     */
    protected $port = 27666; // Default port, used if not set when instanced

    /**
     * The protocol being used
     *
     * @var string
     */
    protected $protocol = 'doom3';

    /**
     * String name of this protocol class
     *
     * @var string
     */
    protected $name = 'doom3';

    /**
     * Longer string name of this protocol class
     *
     * @var string
     */
    protected $name_long = "Doom 3";
    protected $name_short = "Doom 3";
    protected $basic_game_dir = 'baseDOOM-1';

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

    protected function preProcess_all($packets=array())
    {
        // Implode and return
        return implode('', $packets);
    }

    protected function process_all()
    {
        // Make sure we have a valid response
        if(!$this->hasValidResponse(self::PACKET_ALL))
        {
            return array();
        }

        // Set the result to a new result instance
        $result = new GameQ_Result();

        // Parse the response
        $data = $this->preProcess_all($this->packets_response[self::PACKET_ALL]);

        // Create a new buffer
        $buf = new GameQ_Buffer($data);

        // Header
        if ($buf->readInt16() !== 65535 or $buf->readString() !== 'infoResponse')
        {
            throw new GameQ_ProtocolsException('Header for response does not match. Buffer:'.$this->packets_response[self::PACKET_ALL]);
            return array();
        }

        $result->add('version', $buf->readInt8() . '.' . $buf->readInt8());

        // Var / value pairs, delimited by an empty pair
        while ($buf->getLength())
        {
            $var = $buf->readString();
            $val = $buf->readString();

            // Something is empty so we are done
            if (empty($var) && empty($val))
            {
                break;
            }

            $result->add($var, $val);
        }

        // Now lets parse the players
        $this->parsePlayers($buf, $result);

        unset($buf, $data);

        // Return the result
        return $result->fetch();
    }

    /**
     * Parse the players.  Set as its own method so it can be overloaded.
     *
     * @param GameQ_Buffer $buf
     * @param GameQ_Result $result
     */
    protected function parsePlayers(GameQ_Buffer &$buf, GameQ_Result &$result)
    {
        // There is no way to see the number of players so we have to increment
        // a variable and do it that way.
        $players = 0;


        // Loop thru the buffer until we run out of data
        while (($id = $buf->readInt8()) != 32)
        {
            $result->addPlayer('id',   $id);
            $result->addPlayer('ping', $buf->readInt16());
            $result->addPlayer('rate', $buf->readInt32());
            $result->addPlayer('name', $buf->readString());
            $players++;
        }

        // Add the number of players to the result
        $result->add('numplayers', $players);

        return TRUE;
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
            DebugConsole::insert_info('GameQ_Protocols_Doom3::process_dzcp_runtime()', 'The basic-gamedir differs from servers gamedir, use a mod?');
            DebugConsole::insert_info('GameQ_Protocols_Doom3::process_dzcp_runtime()', 'Basic: "'.$this->basic_game_dir.'" <=> Server: "'.$this->server_data_stream['gamename'].'" on Server '.$this->server_data_stream['gq_address'].':'.$this->server_data_stream['gq_port']);
            $result->add('game_use_mod', true);
            $result->add('game_mod_dir', $this->server_data_stream['gamename']);
        }
        else
            $result->add('game_use_mod', false);

        $secure = array('enable' => $this->server_data_stream['sv_punkbuster'] == '1' ? true : false, 'pic' => 'punkbuster', 'name' => 'PunkBuster');
        $this->server_data_stream['si_name'] = preg_replace("/\^./", "", $this->server_data_stream['si_name']);

        // Set the result to a new result instance
        $result->add('game_name_long', $this->is_mod ? $this->basic_game_long : $this->name_long);
        $result->add('game_name_short', $this->is_mod ? $this->basic_game_short : $this->name_short);
        $result->add('game_mod_name_long', $this->is_mod ? $this->name_long : $mod_name_long);
        $result->add('game_mod_name_short', $this->is_mod ? $this->name_short : $mod_name_short);
        $result->add('game_hostname',htmlentities($this->server_data_stream['si_name'], ENT_QUOTES, "UTF-8"));
        $result->add('game_map', re(str_replace('game/mp/', '', $this->server_data_stream['si_map'])));
        $result->add('game_map_pic_dir', 'id_tech_4/doom3'.($this->is_mod || $is_mod_ml ? '/'.$this->server_data_stream['gamename'] : '') );
        $result->add('game_type',ucfirst($this->server_data_stream['si_gameType']));
        $result->add('game_dir', !$this->is_mod && !$is_mod_ml ? 'doom3' : $this->server_data_stream['gamename']);
        $result->add('game_mod', $this->is_mod || $is_mod_ml ? $this->server_data_stream['gamename'] : '');
        $result->add('game_country',$this->server_data_stream['.Location']);
        $result->add('game_region','');
        $result->add('game_os', ''); //Server OS
        $result->add('game_dedicated', $this->server_data_stream['net_serverDedicated'] == '1' ? true : false);
        $result->add('game_hltv', false);
        $result->add('game_num_players', array_key_exists('players', $this->server_data_stream) ? count($this->server_data_stream['players']) : '0');
        $result->add('game_max_players', $this->server_data_stream['si_maxPlayers']);
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
            GameQ::mkdir_img('maps/id_tech_4/doom3/'.($this->is_mod || $is_mod_ml ? $this->server_data_stream['gamename'] : $this->basic_game_dir));

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
                $new_player['player_kills'] = '0';
                $new_player['player_deaths'] = '0';
                $new_player['player_rank'] = '0';
                $new_player['player_ping'] = (string)(empty($player['ping']) ? '0' : $player['ping']);;
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
        $this->stats_options['stats_kills']  = array_key_exists(($key_opt='stats_kills'), $this->stats_options)  ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_deaths'] = array_key_exists(($key_opt='stats_deaths'), $this->stats_options) ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_rank']   = array_key_exists(($key_opt='stats_rank'), $this->stats_options)   ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_ping']   = array_key_exists(($key_opt='stats_ping'), $this->stats_options)   ? $this->stats_options[$key_opt] : true;
        $this->stats_options['stats_honor']  = array_key_exists(($key_opt='stats_honor'), $this->stats_options)  ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_goal']   = array_key_exists(($key_opt='stats_goal'), $this->stats_options)   ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_leader'] = array_key_exists(($key_opt='stats_leader'), $this->stats_options) ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_stats']  = array_key_exists(($key_opt='stats_stats'), $this->stats_options)  ? $this->stats_options[$key_opt] : false;
        $result->add('game_stats_options',$this->stats_options);

        switch(isset($_GET['spsort']) ? $_GET['spsort'] : '') //Sort
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