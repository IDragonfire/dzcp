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
 * Medal of Honor Warfighter Protocol Class
 *
 * MOHWF is the same as BF3 minus some quirks in the status query hence the extension
 *
 * @author Austin Bischoff <austin@codebeard.com>
 */
class GameQ_Protocols_Mohwf extends GameQ_Protocols_Bf3
{
    /**
     * The protocol being used
     *
     * @var string
     */
    protected $protocol = 'mohwf';

    /**
     * String name of this protocol class
     *
     * @var string
     */
    protected $name = 'mohwf';

    /**
     * Longer string name of this protocol class
     *
     * @var string
     */
    protected $name_long = "Medal of Honor Warfighter";
    protected $name_short = "MoHW";

    //Basic
    protected $basic_game_long = '';
    protected $basic_game_short = '';
    protected $basic_game_dir = 'mohwf';

    /*
     * Internal Methods
     */
    protected function process_status()
    {
        // Make sure we have a valid response
        if(!$this->hasValidResponse(self::PACKET_STATUS))
        {
            return array();
        }

        // Make buffer for data
        $buf = new GameQ_Buffer($this->preProcess_status($this->packets_response[self::PACKET_STATUS]));

        $buf->skip(8); /* skip header */

        // Decode the words into an array so we can use this data
        $words = $this->decodeWords($buf);

        // Make sure we got OK
        if (!isset ($words[0]) || $words[0] != 'OK')
        {
            throw new GameQ_ProtocolsException('Packet Response was not OK! Buffer:'.$buf->getBuffer());
        }

        // Set the result to a new result instance
        $result = new GameQ_Result();

        // Server is always dedicated
        $result->add('dedicated', TRUE);

        // No mods, as of yet
        $result->add('mod', FALSE);

        // These are the same no matter what mode the server is in
        $result->add('hostname', $words[1]);
        $result->add('numplayers', $words[2]);
        $result->add('maxplayers', $words[3]);
        $result->add('gametype', $words[4]);
        $result->add('map', $words[5]);

        $result->add('roundsplayed', $words[6]);
        $result->add('roundstotal', $words[7]);

        // Figure out the number of teams
        $num_teams = intval($words[8]);

        // Set the current index
        $index_current = 9;

        // Loop for the number of teams found, increment along the way
        for($id=1; $id<=$num_teams; $id++)
        {
            $result->addSub('teams', 'tickets', $words[$index_current]);
            $result->addSub('teams', 'id', $id);

            // Increment
            $index_current++;
        }

        // Get and set the rest of the data points.
        $result->add('targetscore', $words[$index_current]);
        $result->add('online', TRUE); // Forced TRUE, it seems $words[$index_current + 1] is always empty
        $result->add('ranked', $words[$index_current + 2] === 'true');
        $result->add('punkbuster', $words[$index_current + 3] === 'true');
        $result->add('password', $words[$index_current + 4] === 'true');
        $result->add('uptime', $words[$index_current + 5]);
        $result->add('roundtime', $words[$index_current + 6]);

        // The next 3 are empty in MOHWF, kept incase they start to work some day
        $result->add('ip_port', $words[$index_current + 7]);
        $result->add('punkbuster_version', $words[$index_current + 8]);
        $result->add('join_queue', $words[$index_current + 9] === 'true');

        $result->add('region', $words[$index_current + 10]);
        $result->add('pingsite', $words[$index_current + 11]);
        $result->add('country', $words[$index_current + 12]);

        unset($buf, $words);

        return $result->fetch();
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

        $this->server_data_stream['gamevariant'] = empty($this->server_data_stream['mod']) ? $this->basic_game_dir : $this->server_data_stream['mod'];
        $mod_name_long = ''; $mod_name_short = ''; $is_mod_ml = false;
        if(!$this->is_mod && count($this->modlist) && array_key_exists($this->server_data_stream['gamevariant'], $this->modlist))
        {
            $mod_name_long = $this->modlist[$this->server_data_stream['gamevariant']]['name_long'];
            $mod_name_short = $this->modlist[$this->server_data_stream['gamevariant']]['name_short'];
            $is_mod_ml = true;
        }

        if(!$this->is_mod && !$is_mod_ml && $this->basic_game_dir != $this->server_data_stream['gamevariant'])
        {
            DebugConsole::insert_info('GameQ_Protocols_Bf3::process_dzcp_runtime()', 'The basic-gamedir differs from servers gamedir, use a mod?');
            DebugConsole::insert_info('GameQ_Protocols_Bf3::process_dzcp_runtime()', 'Basic: "'.$this->basic_game_dir.'" <=> Server: "'.$this->server_data_stream['gamevariant'].'" on Server '.$this->server_data_stream['gq_address'].':'.$this->server_data_stream['gq_port']);
            $result->add('game_use_mod', true);
            $result->add('game_mod_dir', $this->server_data_stream['gamevariant']);
        }
        else
            $result->add('game_use_mod', false);

        $secure = array('enable' => $this->server_data_stream['punkbuster'] == '1' ? true : false, 'pic' => 'punkbuster', 'name' => 'PunkBuster');
        $game_type = array_key_exists(strtolower(str_ireplace('0', '', $this->server_data_stream['gametype'])), $this->gamemode) ? $this->gamemode[strtolower(str_ireplace('0', '', $this->server_data_stream['gametype']))] : $this->server_data_stream['gametype'];

        // Set the result to a new result instance
        $result->add('game_name_long', $this->is_mod ? $this->basic_game_long : $this->name_long);
        $result->add('game_name_short', $this->is_mod ? $this->basic_game_short : $this->name_short);
        $result->add('game_mod_name_long', $this->is_mod ? $this->name_long : $mod_name_long);
        $result->add('game_mod_name_short', $this->is_mod ? $this->name_short : $mod_name_short);
        $result->add('game_hostname',htmlentities($this->server_data_stream['hostname'], ENT_QUOTES, "UTF-8"));
        $result->add('game_map', re($this->server_data_stream['map']));
        $result->add('game_map_pic_dir', 'frostbite/'.$this->basic_game_dir.'/'.strtolower($this->is_mod || $is_mod_ml ? $this->server_data_stream['gamevariant'] : $this->basic_game_dir).'/'.strtolower(str_ireplace('0', '', $this->server_data_stream['gametype'])));
        $result->add('game_type',ucfirst($game_type));
        $result->add('game_dir', 'frostbite/'.!$this->is_mod && !$is_mod_ml ? $this->server_data_stream['gamevariant'] : $this->basic_game_dir);
        $result->add('game_mod', $this->is_mod || $is_mod_ml ? $this->server_data_stream['gamevariant'] : '');
        $result->add('game_country',$this->server_data_stream['country']);
        $result->add('game_region',$this->server_data_stream['region']);
        $result->add('game_os', ''); //Server OS
        $result->add('game_dedicated', '');
        $result->add('game_hltv', false);
        $result->add('game_num_players', array_key_exists('players', $this->server_data_stream) ? count($this->server_data_stream['players']) : '0');
        $result->add('game_max_players', $this->server_data_stream['maxplayers']);
        $result->add('game_num_bot', '');
        $result->add('game_password', $this->server_data_stream['password'] == '1' ? true : false);
        $result->add('game_secure', $secure);
        $result->add('game_engine', 'bf3');
        $result->add('game_protocol', $this->server_data_stream['gq_protocol']);
        $result->add('game_transport', $this->server_data_stream['gq_transport']);
        $result->add('game_port', $this->server_data_stream['gq_port']);
        $result->add('game_address', $this->server_data_stream['gq_address']);
        $result->add('game_join_link', '');
        $result->add('game_online', $this->server_data_stream['gq_online'] == '1' ? true : false);

        if($this->server_data_stream['gq_online'] == '1')
            GameQ::mkdir_img('maps/frostbite/'.$this->basic_game_dir.'/'.strtolower($this->is_mod || $is_mod_ml ? $this->server_data_stream['gamevariant'] : $this->basic_game_dir).'/'.strtolower(str_ireplace('0', '', $this->server_data_stream['gametype'])));

        /*
         * Custom settings
        */
        $custom_settings = array();
        foreach($this->server_data_stream as $key => $data)
        {
            $split00 = str_split($key, 6);
            if(in_array($key, $this->settings_filter) || in_array($split00[0], $this->settings_filter))
                $custom_settings[$key] = $data;
        }
        $result->add('game_custom',$custom_settings);
        unset($custom_settings);

        /*
         * Teams
        */
        if(!array_key_exists('teams', $this->server_data_stream)) //Timeout FIX
            $this->server_data_stream['teams'] = array(0 => array('tickets' => '0', 'id' => 1), 1 => array('tickets' => '0', 'id' => 2));

        $teams = array();
        $this->server_data_stream['teams'][] = array('tickets' => '0', 'id' => 0);
        foreach($this->server_data_stream['teams'] as $key => $data)
        {
            $teams[$data['id']] = array('team_name' => $this->teamlist[$data['id']], 'team_score' => $data['tickets']);
        }

        $result->add('game_teams',$teams);
        unset($teams);

        $player_list = array(); $player_index = array();
        $players_team1 = array(); $players_team2 = array(); $players_team3 = array(); //Sort to 2 Teams & Filter Players
        if(array_key_exists('players', $this->server_data_stream) && count($this->server_data_stream['players']) >= 1)
        {
            foreach($this->server_data_stream['players'] as $player)
            {
                $playername = htmlentities($player['name'], ENT_QUOTES, "UTF-8");
                if(empty($playername) && !server_show_empty_players || key_exists($playername, $player_index)) { continue; }
                $player_index[$playername] = true; $new_player = array();
                $new_player['player_name'] = $playername;
                $new_player['player_score'] = (string)($player['score']);
                $new_player['player_time'] = '0';
                $new_player['player_team'] = intval(empty($player['teamId']) ? 0 : $player['teamId']);
                $new_player['player_squadid'] = intval($player['squadId']);
                $new_player['player_squad'] = (string)($this->squadlist[$player['squadId']]);
                $new_player['player_kills'] = (string)($player['kills']);
                $new_player['player_deaths'] = (string)($player['deaths']);
                $new_player['player_rank'] = '0';
                $new_player['player_ping'] = '0';
                $new_player['player_honor'] = '0';
                $new_player['player_goal'] = '0';
                $new_player['player_leader'] = '0';
                $new_player['player_stats'] = '0';

                if($player['teamId'] == 1) $players_team1[] = $new_player;
                else if($player['teamId'] == 2) $players_team2[] = $new_player;
                else $players_team3[] = $new_player;
            }

            $players_team1 = GameQ::record_sort($players_team1, 'player_squadid', true); //Sort
            $players_team2 = GameQ::record_sort($players_team2, 'player_squadid', true); //Sort
            $players_team3 = GameQ::record_sort($players_team3, 'player_squadid', true); //Sort

            $player_list = array_merge($players_team1,$players_team2,$players_team3);
            unset($player_index,$players_team1,$players_team2,$players_team3);
        }

        //Player Stats
        $this->stats_options['stats_score']  = array_key_exists(($key_opt='stats_score'), $this->stats_options)  ? $this->stats_options[$key_opt] : true;
        $this->stats_options['stats_time']   = array_key_exists(($key_opt='stats_time'), $this->stats_options)   ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_team']   = array_key_exists(($key_opt='stats_team'), $this->stats_options)   ? $this->stats_options[$key_opt] : true;
        $this->stats_options['stats_squad']  = array_key_exists(($key_opt='stats_squad'), $this->stats_options)  ? $this->stats_options[$key_opt] : true;
        $this->stats_options['stats_kills']  = array_key_exists(($key_opt='stats_kills'), $this->stats_options)  ? $this->stats_options[$key_opt] : true;
        $this->stats_options['stats_deaths'] = array_key_exists(($key_opt='stats_deaths'), $this->stats_options) ? $this->stats_options[$key_opt] : true;
        $this->stats_options['stats_rank']   = array_key_exists(($key_opt='stats_rank'), $this->stats_options)   ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_ping']   = array_key_exists(($key_opt='stats_ping'), $this->stats_options)   ? $this->stats_options[$key_opt] : false;
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
