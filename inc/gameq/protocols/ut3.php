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
 * Unreal Tournament 3 Protocol Class
 *
 * NOTE:  The return from UT3 via the GameSpy 3 protocol is anything but consistent.  You may
 * notice different results even on the same server queried at different times.  No real way to fix
 * this problem currently.
 *
 * @author Austin Bischoff <austin@codebeard.com>
 */
class GameQ_Protocols_Ut3 extends GameQ_Protocols_Gamespy3
{
    protected $name = "ut3";
    protected $name_long = "Unreal Tournament 3";

    /**
     * Set settings filter
     *
     * @var array
     */
    protected $settings_filter = array('custom_mutators','bot_skill','pure_server','frag_limit','time_limit','gamemode');

    /**
     * Show Stats Options
     *
     * @var array
     */
    protected $stats_options = array();

    protected $port = 6500;

    protected $ut_mutators = array(
        'UTGame.UTMutator_Instagib' => 'Instagib ',
        'UTGame.UTMutator_BigHead ' => 'BigHead',
        'UTGame.UTMutator_LowGrav' => 'Low Gravity',
        'UTGame.UTMutator_SuperBerserk' => 'Super Berserk',
        'UTGame.UTMutator_FriendlyFire' => 'Friendly Fire',
        'UTGame.UTMutator_NoTranslocator' => 'No Translocator',
        'UTGame.UTMutator_Handicap' => 'Handicap ',
        'UTGame.UTMutator_NoPowerups' => 'No Powerups',
        'UTGame.UTMutator_Slomo' => 'Slomo',
        'UTGame.UTMutator_WeaponReplacement' => 'Weapon Replacement',
        'UTGame.UTMutator_WeaponsRespawn' => 'Weapon Respawn'
      );

    protected $ut_gamemodes = array(
            'UTGame.UTDeathmatch' => 'Deathmatch',
            'UTGameContent.UTCTFGame_Content' => 'Capture the Flag',
            'UTGameContent.UTOnslaughtGame_Content' => 'Warfare',
            'UTGameContent.UTVehicleCTFGame_Content' => 'Vehicle Capture the Flag',
            'UTGame.UTTeamGame' => 'Team Deathmatch',
            'BattleTeamArena.BattleTeamArena' => 'Battle Team Arena',
            'UTGame.UTTeamGame' => 'Duel'
    );

    /**
     * Process all the data at once
     * @see GameQ_Protocols_Gamespy3::process_all()
     */
    protected function process_all()
    {
        // Run the parent but we need to change some data
        $result = parent::process_all();

        // Move some stuff around
        $this->move_result($result, 'hostname',    'OwningPlayerName');
        $this->move_result($result, 'p1073741825', 'mapname');
        $this->move_result($result, 'p1073741826', 'gametype');
        $this->move_result($result, 'p1073741827', 'servername');
        $this->move_result($result, 'p1073741828', 'custom_mutators');
        $this->move_result($result, 'gamemode',    'open');
        $this->move_result($result, 's32779',      'gamemode');
        $this->move_result($result, 's0',          'bot_skill');
        $this->move_result($result, 's6',          'pure_server');
        $this->move_result($result, 's7',          'password');
        $this->move_result($result, 's8',          'vs_bots');
        $this->move_result($result, 's10',         'force_respawn');
        $this->move_result($result, 'p268435704',  'frag_limit');
        $this->move_result($result, 'p268435705',  'time_limit');
        $this->move_result($result, 'p268435703',  'numbots');
        $this->move_result($result, 'p268435717',  'stock_mutators');

        // Put custom mutators into an array
        if(isset($result['custom_mutators']))
            $result['custom_mutators'] = explode("\x1c", $result['custom_mutators']);

        // Delete some unknown stuff
        $this->delete_result($result, array('s1','s9','s11','s12','s13','s14'));

        // Return the result
        return $result;
    }

    // UT3 Hack, yea I know it doesnt belong here. UT3 is such a mess it needs its own version of GSv3
    //$data = str_replace(array("\x00p1073741829\x00", "p1073741829\x00", "p268435968\x00"), '', $data);

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

        $game_type = $this->server_data_stream['gametype'];
        if(array_key_exists($this->server_data_stream['gametype'], $this->ut_gamemodes))
            $game_type = $this->ut_gamemodes[$this->server_data_stream['gametype']];

        // Set the result to a new result instance
        $result->add('game_name_long', $this->name_long);
        $result->add('game_name_short', 'UT3');
        $result->add('game_mod_name_long', '');
        $result->add('game_mod_name_short', '');
        $result->add('game_hostname',htmlentities(!empty($this->server_data_stream['servername']) ? $this->server_data_stream['servername'] : $this->server_data_stream['OwningPlayerName'], ENT_QUOTES, "UTF-8"));
        $result->add('game_map', re($this->server_data_stream['mapname']));
        $result->add('game_map_pic_dir', $this->server_data_stream['gq_protocol'].'/unreal/ut3');
        $result->add('game_type', $game_type);
        $result->add('game_dir', 'ut3');
        $result->add('game_mod', '');
        $result->add('game_country','');
        $result->add('game_region','');
        $result->add('game_os', ''); //Server OS
        $result->add('game_dedicated', $this->server_data_stream['bIsDedicated'] == 'True' ? true : false);
        $result->add('game_hltv', false);
        $result->add('game_num_players', $this->server_data_stream['numplayers']);
        $result->add('game_max_players', $this->server_data_stream['maxplayers']);
        $result->add('game_num_bot', $this->server_data_stream['numbots'] == '-1' ? '0' : $this->server_data_stream['numbots']);
        $result->add('game_password', $this->server_data_stream['password'] == '1' ? true : false);
        $result->add('game_secure', false);
        $result->add('game_use_mod', false);
        $result->add('game_engine', 'unreal');
        $result->add('game_protocol', $this->server_data_stream['gq_protocol']);
        $result->add('game_transport', $this->server_data_stream['gq_transport']);
        $result->add('game_port', $this->server_data_stream['gq_port']);
        $result->add('game_address', $this->server_data_stream['gq_address']);
        $result->add('game_join_link', 'steam://connect/'.$this->server_data_stream['gq_address'].':'.$this->server_data_stream['hostport']);
        $result->add('game_online', $this->server_data_stream['gq_online'] == '1' ? true : false);

        if($this->server_data_stream['gq_online'] == '1')
            GameQ::mkdir_img('maps/'.$this->server_data_stream['gq_protocol'].'/unreal/ut3');

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
         * Teams
        */
        $teams = array();
        foreach($this->server_data_stream['teams'] as $key => $data)
        {
            $teams[$key] = array('team_name' => (!$key ? 'Rot' : 'Blau'), 'team_score' => $data['score']);
        }
        $result->add('game_teams',$teams);
        unset($teams);

        $player_list = array();
        if(array_key_exists('players', $this->server_data_stream) && count($this->server_data_stream['players']) >= 1)
        {
            foreach($this->server_data_stream['players'] as $player)
            {
                /*
                 * START BUGFIX
                 * Fucking UT3 crap servers!
                 */
                if(array_key_exists('pping', $player))
                {
                    $player['ping'] = $player['pping'];
                    unset($player['pping']);
                }

                if(array_key_exists('teteam', $player))
                {
                    $player['team'] = $player['teteam'];
                   unset($player['teteam']);
                }

                if(array_key_exists('tteam', $player))
                {
                    $player['team'] = $player['tteam'];
                    unset($player['tteam']);
                }
                /*
                 * END
                 */

                if(empty($player['player']) && !server_show_empty_players) continue;
                $new_player = array();
                $new_player['player_name'] = htmlentities($player['player'], ENT_QUOTES, "UTF-8");
                $new_player['player_score'] = (string)(empty($player['score']) ? '0' : $player['score']);
                $new_player['player_time'] = '0';
                $new_player['player_team'] = (string)(empty($player['team']) ? '0' : $player['team']);
                $new_player['player_squad'] = '0';
                $new_player['player_kills'] = '0';
                $new_player['player_deaths'] = (string)(empty($player['deaths']) ? '0' : $player['deaths']);
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
        $this->stats_options['stats_score']  = array_key_exists(($key_opt='stats_score'), $this->stats_options)  ? $this->stats_options[$key_opt] : true;
        $this->stats_options['stats_time']   = array_key_exists(($key_opt='stats_time'), $this->stats_options)   ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_team']   = array_key_exists(($key_opt='stats_team'), $this->stats_options)   ? $this->stats_options[$key_opt] : true;
        $this->stats_options['stats_squad']  = array_key_exists(($key_opt='stats_squad'), $this->stats_options)  ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_kills']  = array_key_exists(($key_opt='stats_kills'), $this->stats_options)  ? $this->stats_options[$key_opt] : false;
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
        $this->server_data_stream = $result->fetch();
    }
}