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
 * Battlefield 1942 Protocol Class
 *
 * @author Austin Bischoff <austin@codebeard.com>
 */
class GameQ_Protocols_Bf1942 extends GameQ_Protocols_Gamespy
{
    protected $name = "bf1942";
    protected $name_long = "Battlefield 1942";
    protected $name_short = "BF1942";
    protected $port = 23000;

    //Basic
    protected $basic_game_long = '';
    protected $basic_game_short = '';
    protected $basic_game_dir = 'bf1942';

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
    protected $modlist = array('dc_final' => array('name_long' => 'Desert Combat Final','name_short' => 'DCF','game_dir' => 'desertcombat'));

    /**
     * Set settings filter
     *
     * @var array
    */
    protected $settings_filter = array(
    'gamemode',
    'reservedslots',
    'roundTime',
    'roundTimeRemain',
    'version',
    'allied_team_ratio',
    'allow_nose_cam',
    'auto_balance_teams',
    'axis_team_ratio',
    'game_start_delay',
    'number_of_rounds',
    'soldier_friendly_fire',
    'soldier_friendly_fire_on_splash',
    'spawn_delay',
    'spawn_wave_time',
    'ticket_ratio',
    'time_limit',
    'tk_mode',
    'vehicle_friendly_fire',
    'vehicle_friendly_fire_on_splash');

    /**
     * Show Stats Options
     *
     * @var array
    */
    protected $stats_options = array();

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
            $result->add('game_name_long', $this->name_long);
            $result->add('game_name_short', $this->name_short);
            $result->add('game_online', false);
            $this->server_data_stream = $result->fetch();
            return;
        }

        $mod_name_long = ''; $mod_name_short = ''; $is_mod_ml = false;
        if(!$this->is_mod && count($this->modlist) && array_key_exists($this->server_data_stream['active_mods'], $this->modlist))
        {
            $mod_name_long = $this->modlist[$this->server_data_stream['active_mods']]['name_long'];
            $mod_name_short = $this->modlist[$this->server_data_stream['active_mods']]['name_short'];
            $is_mod_ml = true;
        }

        if(!$this->is_mod && !$is_mod_ml && $this->basic_game_dir != $this->server_data_stream['active_mods'])
        {
            DebugConsole::insert_info('GameQ_Protocols_Bf1942::process_dzcp_runtime()', 'The basic-gamedir differs from servers gamedir, use a mod?');
            DebugConsole::insert_info('GameQ_Protocols_Bf1942::process_dzcp_runtime()', 'Basic: "'.$this->basic_game_dir.'" <=> Server: "'.$this->server_data_stream['active_mods'].'" on Server '.$this->server_data_stream['gq_address'].':'.$this->server_data_stream['gq_port']);
            $result->add('game_use_mod', true);
            $result->add('game_mod_dir', $this->server_data_stream['active_mods']);
        }
        else
            $result->add('game_use_mod', false);

        $secure = array('enable' => $this->server_data_stream['sv_punkbuster'] == '1' ? true : false, 'pic' => 'punkbuster', 'name' => 'PunkBuster');

        // Set the result to a new result instance
        $result->add('game_name_long', $this->is_mod ? $this->basic_game_long : $this->name_long);
        $result->add('game_name_short', $this->is_mod ? $this->basic_game_short : $this->name_short);
        $result->add('game_mod_name_long', $this->is_mod ? $this->name_long : $mod_name_long);
        $result->add('game_mod_name_short', $this->is_mod ? $this->name_short : $mod_name_short);
        $result->add('game_hostname',htmlentities($this->server_data_stream['hostname'], ENT_QUOTES, "UTF-8"));
        $result->add('game_map', re($this->server_data_stream['mapname']));
        $result->add('game_map_pic_dir', 'refractor/'.$this->basic_game_dir.'/'.($this->is_mod || $is_mod_ml ? $this->server_data_stream['active_mods'] : $this->basic_game_dir) );
        $result->add('game_type',ucfirst($this->server_data_stream['gametype']));
        $result->add('game_dir', !$this->is_mod && !$is_mod_ml ? $this->server_data_stream['active_mods'] : $this->basic_game_dir);
        $result->add('game_mod', $this->is_mod || $is_mod_ml ? $this->server_data_stream['active_mods'] : '');
        $result->add('game_country','');
        $result->add('game_region','');
        $result->add('game_os', ''); //Server OS
        $result->add('game_dedicated', $this->server_data_stream['dedicated'] == '2' || $this->server_data_stream['dedicated'] == 'p' ? true : false);
        $result->add('game_hltv', false);
        $result->add('game_num_players', $this->server_data_stream['numplayers']);
        $result->add('game_max_players', $this->server_data_stream['maxplayers']);
        $result->add('game_num_bot', '');
        $result->add('game_password', $this->server_data_stream['password'] == '1' ? true : false);
        $result->add('game_secure', $secure);
        $result->add('game_engine', 'bf1942');
        $result->add('game_protocol', $this->server_data_stream['gq_protocol']);
        $result->add('game_transport', $this->server_data_stream['gq_transport']);
        $result->add('game_port', $this->server_data_stream['gq_port']);
        $result->add('game_address', $this->server_data_stream['gq_address']);
        $result->add('game_join_link', '');
        $result->add('game_online', $this->server_data_stream['gq_online'] == '1' ? true : false);

        if($this->server_data_stream['gq_online'] == '1')
            GameQ::mkdir_img('maps/refractor/'.$this->basic_game_dir.'/'.($this->is_mod || $is_mod_ml ? $this->server_data_stream['active_mods'] : $this->basic_game_dir));

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
        $teams = array();
        foreach($this->server_data_stream['teams'] as $key => $data)
        {
            $teams[$key+1] = array('team_name' => $data['teamname'], 'team_score' => ($key+1 == '1' ? $this->server_data_stream['tickets1'] : $this->server_data_stream['tickets2']));
        }
        $result->add('game_teams',$teams);
        unset($teams);

        $player_list = array(); $player_index = array();
        if(array_key_exists('players', $this->server_data_stream) && count($this->server_data_stream['players']) >= 1)
        {
            foreach($this->server_data_stream['players'] as $player)
            {
                $playername = htmlentities($player['playername'], ENT_QUOTES, "UTF-8");
                if(empty($playername) && !server_show_empty_players || key_exists($playername, $player_index)) { continue; }
                $player_index[$playername] = true;
                $new_player = array();
                $new_player['player_name'] = $playername;
                $new_player['player_score'] = (string)($player['score']);
                $new_player['player_time'] = '0';
                $new_player['player_team'] = (string)($player['team']);
                $new_player['player_squad'] = '0';
                $new_player['player_kills'] = (string)($player['kills']);
                $new_player['player_deaths'] = (string)($player['deaths']);
                $new_player['player_rank'] = '0';
                $new_player['player_ping'] = (string)($player['ping']);
                $new_player['player_honor'] = '0';
                $new_player['player_goal'] = '0';
                $new_player['player_leader'] = '0';
                $new_player['player_stats'] = '0';
                $player_list[] = $new_player;
            }
            unset($player_index);
        }

        //Player Stats
        $this->stats_options['stats_score']  = array_key_exists(($key_opt='stats_score'), $this->stats_options)  ? $this->stats_options[$key_opt] : true;
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

        switch(isset($_GET['spsort']) ? $_GET['spsort'] : 'score') //Sort
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

        #print_r($result->fetch());
        $this->server_data_stream = $result->fetch();
    }
}