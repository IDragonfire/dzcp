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
 * Unreal Tournament Protocol Class
 *
 * @author Austin Bischoff <austin@codebeard.com>
 */
class GameQ_Protocols_Ut extends GameQ_Protocols_Gamespy
{
    protected $name = "ut";
    protected $name_long = "Unreal Tournament";
    protected $name_short = 'UT';

    /**
     * Show Stats Options
     *
     * @var array
    */
    protected $stats_options = array();

    /**
     * Set settings filter
     *
     * @var array
     */
    protected $settings_filter = array(
    'mutators',
    'maptitle',
    'gamemode',
    'timelimit',
    'goalteamscore',
    'minplayers',
    'changelevels',
    'maxteams',
    'balanceteams',
    'playersbalanceteams',
    'friendlyfire',
    'tournament',
    'gamestyle',
    'botskill');

    protected $ut_gamemodes = array(
    'CTFGame' => 'Capture the Flag',
    'LastManStanding' => 'Last Man Standing',
    'DeathMatchPlus' => 'Deathmatch',
    'BounceDeathMatchPlus' => 'Bounce Deathmatch',
    'TeamGamePlus' => 'Team Deathmatch',
    'RocketArenaGame' => 'Rocket Arena',
    'AnyMapRocketArenaGame' => 'Rocket Arena',
    );

    protected $port = 7778;

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

        $game_type = $this->server_data_stream['gametype'];
        if(array_key_exists($this->server_data_stream['gametype'], $this->ut_gamemodes))
            $game_type = $this->ut_gamemodes[$this->server_data_stream['gametype']];

        $secure = array('enable' => false, 'pic' => '', 'name' => '');

        // Set the result to a new result instance
        $result->add('game_name_long', $this->name_long);
        $result->add('game_name_short', $this->name_short);
        $result->add('game_mod_name_long', '');
        $result->add('game_mod_name_short', '');
        $result->add('game_hostname',htmlentities($this->server_data_stream['hostname'], ENT_QUOTES, "UTF-8"));
        $result->add('game_map', re($this->server_data_stream['mapname']));
        $result->add('game_map_pic_dir', $this->server_data_stream['gq_protocol'].'/unreal/ut');
        $result->add('game_type', $game_type);
        $result->add('game_dir', 'ut');
        $result->add('game_mod', '');
        $result->add('game_country','');
        $result->add('game_region','');
        $result->add('game_os', ''); //Server OS
        $result->add('game_dedicated', $this->server_data_stream['listenserver'] == 'False' ? true : false);
        $result->add('game_hltv', false);
        $result->add('game_num_players', $this->server_data_stream['numplayers']);
        $result->add('game_max_players', $this->server_data_stream['maxplayers']);
        $result->add('game_num_bot', '');
        $result->add('game_password', $this->server_data_stream['password'] == 'True' ? true : false);
        $result->add('game_secure', $secure);
        $result->add('game_use_mod', false);
        $result->add('game_engine', 'unreal');
        $result->add('game_protocol', $this->server_data_stream['gq_protocol']);
        $result->add('game_transport', $this->server_data_stream['gq_transport']);
        $result->add('game_port', $this->server_data_stream['gq_port']);
        $result->add('game_address', $this->server_data_stream['gq_address']);
        $result->add('game_join_link', 'steam://connect/'.$this->server_data_stream['gq_address'].':'.$this->server_data_stream['hostport']);
        $result->add('game_online', $this->server_data_stream['gq_online'] == '1' ? true : false);

        if($this->server_data_stream['gq_online'] == '1')
            GameQ::mkdir_img('maps/'.$this->server_data_stream['gq_protocol'].'/unreal/ut');

        /*
         * Custom settings
        */
        $custom_settings = array();
        foreach($this->server_data_stream as $key => $data)
        {
            if(in_array($key, $this->settings_filter)) // Mutators, etc.
                $custom_settings[$key] = $data;
        }
        $result->add('game_custom',$custom_settings);
        unset($custom_settings);

        /*
         * Teams * Dummy
        */
        $this->server_data_stream['teams'] = array(array(),array());
        $teams = array();
        foreach($this->server_data_stream['teams'] as $key => $data)
        {
            $teams[$key] = array('team_name' => (!$key ? 'Rot' : 'Blau'), 'team_score' => '');
        }
        $result->add('game_teams',$teams);
        unset($teams);

        $player_list = array();
        if(array_key_exists('players', $this->server_data_stream) && count($this->server_data_stream['players']) >= 1)
        {
            foreach($this->server_data_stream['players'] as $player)
            {

                if(empty($player['player']) && !server_show_empty_players) continue;
                $new_player = array();
                $new_player['player_name'] = htmlentities($player['player'], ENT_QUOTES, "UTF-8");
                $new_player['player_score'] = '0';
                $new_player['player_time'] = '0';
                $new_player['player_team'] = (string)(empty($player['team']) ? '0' : $player['team']);
                $new_player['player_squad'] = '0';
                $new_player['player_kills'] = (string)(empty($player['frags']) ? '0' : $player['frags']);
                $new_player['player_deaths'] = '0';
                $new_player['player_rank'] = '0';
                $new_player['player_ping'] = (string)(empty($player['ping']) ? '0' : $player['ping']);
                $new_player['player_honor'] = '0';
                $new_player['player_goal'] = '0';
                $new_player['player_leader'] = '0';
                $new_player['player_stats'] = '0';
                $player_list[] = $new_player;
            }
        }

        //Player Stats
        $this->stats_options['stats_score']  = array_key_exists(($key_opt='stats_score'), $this->stats_options)  ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_time']   = array_key_exists(($key_opt='stats_time'), $this->stats_options)   ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_team']   = array_key_exists(($key_opt='stats_team'), $this->stats_options)   ? $this->stats_options[$key_opt] : true;
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
        $this->server_data_stream = $result->fetch();
    }
}
