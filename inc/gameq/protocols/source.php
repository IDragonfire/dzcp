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
 * Valve Source Engine Protocol Class
 *
 * This class is used as the basis for all other source based servers
 * that rely on the source protocol for game querying
 *
 * @author Austin Bischoff <austin@codebeard.com>
 */
class GameQ_Protocols_Source extends GameQ_Protocols
{
    /*
     * Source engine type constants
     */
    const SOURCE_ENGINE = 0;
    const GOLDSOURCE_ENGINE = 1;

    /**
     * Array of packets we want to look up.
     * Each key should correspond to a defined method in this or a parent class
     *
     * @var array
     */
    protected $packets = array(
            self::PACKET_CHALLENGE => "\xFF\xFF\xFF\xFF\x56\x00\x00\x00\x00",
            self::PACKET_DETAILS => "\xFF\xFF\xFF\xFFTSource Engine Query\x00",
            self::PACKET_PLAYERS => "\xFF\xFF\xFF\xFF\x55%s",
            self::PACKET_RULES => "\xFF\xFF\xFF\xFF\x56%s",
    );

    /**
     * Methods to be run when processing the response(s)
     *
     * @var array
     */
    protected $process_methods = array(
        "process_details",
        "process_players",
        "process_rules"
    );

    /**
     * Default port for this server type
     *
     * @var int
     */
    protected $port = 27015; // Default port, used if not set when instanced

    /**
     * The query protocol used to make the call
     *
     * @var string
     */
    protected $protocol = 'source';

    /**
     * String name of this protocol class
     *
     * @var string
     */
    protected $name = 'source';

    /**
     * Longer string name of this protocol class
     *
     * @var string
     */
    protected $name_long = 'Source Server';
    protected $name_short = '';

    //Basic
    protected $basic_game_long = '';
    protected $basic_game_short = '';
    protected $basic_game_dir = '';

    /**
     * Define the Source engine type.  By default it is assumed to be Source
     *
     * @var int
     */
    protected $source_engine = self::SOURCE_ENGINE;

    /**
     * Set source or goldsource engine
     *
     * @var boolean
     */
    protected $goldsource = false;

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
    protected $settings_filter = array('sv_','mp_','sm_','sb_','tf_','amx_','cr_');

    /**
     * Show Stats Options
     *
     * @var array
     */
    protected $stats_options = array();

    /**
     * Parse the challenge response and apply it to all the packet types
     * that require it.
     *
     * @see GameQ_Protocols_Core::parseChallengeAndApply()
     */
     protected function parseChallengeAndApply()
    {
        // Skip the header
        $this->challenge_buffer->skip(5);

        // Apply the challenge and return
        return $this->challengeApply($this->challenge_buffer->read(4));
    }

    /*
     * Internal methods
     */

    /**
     * Pre-process the server details data that was returned.
     *
     * @param array $packets
     */
    protected function preProcess_details($packets)
    {
        return $this->process_packets($packets); // Process the packets
    }

    /**
     * Handles processing the details data into a usable format
     *
     * @throws GameQ_ProtocolsException
     */
    protected function process_details()
    {
        // Make sure we have a valid response
        if(!$this->hasValidResponse(self::PACKET_DETAILS))
            return array();

        // Set the result to a new result instance
        $result = new GameQ_Result();

        // Let's preprocess the rules
        $data = $this->preProcess_details($this->packets_response[self::PACKET_DETAILS]);

        // Create a new buffer
        $buf = new GameQ_Buffer($data);

        // Skip the header (0xFF0xFF0xFF0xFF)
        $buf->skip(4);

        // Get the type
        $type = strtolower('0x'.bin2hex($buf->getByte()));

        // Make sure the data is formatted properly
        // Source is 0x49, Goldsource is 0x6d
        if(!in_array($type, array("0x49","0x6d")))
        {
            throw new GameQ_ProtocolsException("Data for ".__METHOD__." does not have the proper header type (should be 0x49|0x6d). Header type: ".$type);
            return array();
        }

        // Update the engine type for other calls and other methods, if necessary
        $this->source_engine = ($type == '0x6d' ? self::GOLDSOURCE_ENGINE : self::SOURCE_ENGINE);
        if($this->source_engine == self::GOLDSOURCE_ENGINE)
        {
            //GOLDSOURCE
            $result->add('address', $buf->readString());
            $result->add('hostname', $buf->getString());
            $result->add('map', $buf->readString());
            $result->add('game_dir', $buf->readString());
            $result->add('game_descr', $buf->readString());
            $result->add('num_players', $buf->readInt8());
            $result->add('max_players', $buf->readInt8());
            $result->add('version', $buf->readInt8());
            $result->add('dedicated', $buf->read());
            $result->add('os', $buf->read());
            $result->add('password', $buf->readInt8());
            $result->add('is_mod', ($is_mod = $buf->readInt8()));

            if($is_mod)
            {
                $result->add( 'Url', $buf->readString());
                $result->add('Download', $buf->readString());
                $buf->read();
                $result->add('Version', $buf->readInt32Signed());
                $result->add('Size', $buf->readInt32Signed());
                $result->add('ServerSide', $buf->readInt8());
                $result->add('CustomDLL', $buf->readInt8());
            }

            $result->add('secure', $buf->readInt8());
            $result->add('num_bots', $buf->readInt8());
        }
        else
        {
            //SOURCE
            $result->add('protocol', $buf->readInt8());
            $result->add('hostname', $buf->getString());
            $result->add('map', $buf->readString());
            $result->add('game_dir', $buf->readString());
            $result->add('game_descr', $buf->readString());
            $result->add('steamappid', $buf->readInt16());
            $result->add('num_players', $buf->readInt8());
            $result->add('max_players', $buf->readInt8());
            $result->add('num_bots', $buf->readInt8());
            $result->add('dedicated', $buf->read());
            $result->add('os', $buf->read());
            $result->add('password', $buf->readInt8());
            $result->add('secure', $buf->readInt8());
            $result->add('version', $buf->readInt8());
        }

        $buf->resetBuffer(); unset($buf);
        return $result->fetch();
    }

    /**
     * Pre-process the player data sent
     *
     * @param array $packets
     */
    protected function preProcess_players($packets)
    {
        // Process the packets
        return $this->process_packets($packets);
    }

    /**
     * Handles processing the player data into a useable format
     *
     * @throws GameQ_ProtocolsException
     */
    protected function process_players()
    {
        // Make sure we have a valid response
        if(!$this->hasValidResponse(self::PACKET_PLAYERS))
            return array();

        // Set the result to a new result instance
        $result = new GameQ_Result();

        // Let's preprocess the rules
        $data = $this->preProcess_players($this->packets_response[self::PACKET_PLAYERS]);

        // Create a new buffer
        $buf = new GameQ_Buffer($data);

        // Skip the header (0xFF0xFF0xFF0xFF)
        $buf->skip(4);

        // Make sure the data is formatted properly
        if(($header = $buf->read()) != "\x44")
        {
            throw new GameQ_ProtocolsException("Data for ".__METHOD__." does not have the proper header (should be 0xFF0xFF0xFF0xFF0x44). Header: ".bin2hex($header));
            return array();
        }

        // Pull out the number of players
        $num_players = $buf->readInt8();

         // Player count
        $result->add('num_players', $num_players);

        // No players so no need to look any further
        if($num_players == 0)
            return $result->fetch();

        // Players list
        while ($buf->getLength())
        {
            $result->addPlayer('id', $buf->readInt8()); //byte
            $result->addPlayer('name', $buf->readString());
            $result->addPlayer('score', $buf->readInt32Signed()); //long
            $result->addPlayer('time', $buf->readFloat32()); //float
        }

        unset($buf);

        return $result->fetch();
    }

    /**
     * Pre process the rules data that was returned.  Make sure the return
     * data is in a single string
     *
     * @param array $packets
     */
    protected function preProcess_rules($packets)
    {
        // Process the packets
        return $this->process_packets($packets);
    }

    /**
     * Handles processing the rules data into a usable format
     *
     * @throws GameQ_ProtocolsException
     */
    protected function process_rules()
    {
        // Make sure we have a valid response
        if(!$this->hasValidResponse(self::PACKET_RULES))
            return array();

        // Set the result to a new result instance
        $result = new GameQ_Result();

        // Let's preprocess the rules
        $data = $this->preProcess_rules($this->packets_response[self::PACKET_RULES]);

        $buf = new GameQ_Buffer($data);

        // Skip the header (0xFF0xFF0xFF0xFF)
        $buf->skip(4);

        // Make sure the data is formatted properly
        if(($header = $buf->read()) != "\x45")
        {
            throw new GameQ_ProtocolsException("Data for ".__METHOD__." does not have the proper header (should be 0xFF0xFF0xFF0xFF0x45). Header: ".bin2hex($header));
            return array();
        }

        // Count the number of rules
        $num_rules = $buf->readInt16Signed();

        // Add the count of the number of rules this server has
        $result->add('num_rules', $num_rules);

        // Rules
        while ($buf->getLength())
        { $result->add($buf->readString(), $buf->readString()); }

        unset($buf);

        return $result->fetch();
    }

    /**
     * Process the packets to make sure we combine and decompress as needed
     *
     * @param array $packets
     * @throws GameQ_ProtocolsException
     * @return string
     */
    protected function process_packets($packets)
    {
        // Make a buffer to see if we should have multiple packets
        $buffer = new GameQ_Buffer($packets[0]);

        // First we need to see if the packet is split
        // -2 = split packets
        // -1 = single packet
        $packet_type = $buffer->readInt32Signed();

        // This is one packet so just return the rest of the buffer
        if($packet_type == -1)
        {
            // Free some memory
            unset($buffer);

            // We always return the packet as expected, with null included
            return $packets[0];
        }

        // Free some memory
        unset($buffer);

        // Init array so we can order
        $packs = array();

        // We have multiple packets so we need to get them and order them
        foreach($packets AS $packet)
        {
            // Make a buffer so we can read this info
            $buffer = new GameQ_Buffer($packet);

            // Pull some info
            $packet_type = $buffer->readInt32Signed();
            $request_id = $buffer->readInt32Signed();

            // Check to see if this is compressed
            if($request_id & 0x80000000)
            {
                // Check to see if we have Bzip2 installed
                if(!function_exists('bzdecompress'))
                {
                    throw new GameQ_ProtocolsException('Bzip2 is not installed.  See http://www.php.net/manual/en/book.bzip2.php for more info.', 0);
                    return FALSE;
                }

                // Get some info
                $num_packets = $buffer->readInt8();
                $cur_packet  = $buffer->readInt8();
                $packet_length = $buffer->readInt32();
                $packet_checksum = $buffer->readInt32();

                // Try to decompress
                $result = bzdecompress($buffer->getBuffer());

                // Now verify the length
                if(strlen($result) != $packet_length)
                {
                    throw new GameQ_ProtocolsException("Checksum for compressed packet failed! Length expected {$packet_length}, length returned".strlen($result));
                }

                // Set the new packs
                $packs[$cur_packet] = $result;
            }
            else // Normal packet
            {
                // Gold source does things a bit different
                if($this->source_engine == self::GOLDSOURCE_ENGINE)
                    $packet_number = $buffer->readInt8();
                else // New source
                {
                    $packet_number = $buffer->readInt16Signed();
                    $split_length = $buffer->readInt16Signed();
                }

                // Now add the rest of the packet to the new array with the packet_number as the id so we can order it
                $packs[$packet_number] = $buffer->getBuffer();
            }

            unset($buffer);
        }

        // Free some memory
        unset($packets, $packet);

        // Sort the packets by packet number
        ksort($packs);

        // Now combine the packs into one and return
        return implode("", $packs);
    }

/*
 * ######################################################################################
 * #################################### DZCP RUNTIME ####################################
 * ######################################################################################
 */
    protected function process_dzcp_runtime()
    {
        //Homefront Hack
       if(strpos($this->server_data_stream['game_dir'], 'homefront.') !== false) //Fix Version
            $this->server_data_stream['game_dir'] = 'homefront';

       //Brink Hack
       if(strpos($this->server_data_stream['game_dir'], 'Brink') !== false) //Fix
       {
           $this->server_data_stream['game_dir'] = strtolower($this->server_data_stream['game_dir']);
           $this->server_data_stream['map'] = str_replace('mp/', 'mp_', $this->server_data_stream['map']);
       }

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
        if(!$this->is_mod && count($this->modlist) && array_key_exists($this->server_data_stream['game_dir'], $this->modlist))
        {
            $mod_name_long = $this->modlist[$this->server_data_stream['game_dir']]['name_long'];
            $mod_name_short = $this->modlist[$this->server_data_stream['game_dir']]['name_short'];
            $is_mod_ml = true;
        }

        if(!$this->is_mod && !$is_mod_ml && $this->basic_game_dir != $this->server_data_stream['game_dir'])
        {
            DebugConsole::insert_info('GameQ_Protocols_Source::process_dzcp_runtime()', 'The basic-gamedir differs from servers gamedir, use a mod?');
            DebugConsole::insert_info('GameQ_Protocols_Source::process_dzcp_runtime()', 'Basic: "'.$this->basic_game_dir.'" <=> Server: "'.$this->server_data_stream['game_dir'].'" on Server '.$this->server_data_stream['gq_address'].':'.$this->server_data_stream['gq_port']);
            $result->add('game_use_mod', true);
            $result->add('game_mod_dir', $this->server_data_stream['game_dir']);
        }
        else
            $result->add('game_use_mod', false);

        $secure = array('enable' => $this->server_data_stream['secure'] == '1' ? true : false, 'pic' => 'shield', 'name' => 'Valve Anti-Cheat');

        // Set the result to a new result instance
        $result->add('game_name_long', $this->is_mod ? $this->basic_game_long : $this->name_long);
        $result->add('game_name_short', $this->is_mod ? $this->basic_game_short : $this->name_short);
        $result->add('game_mod_name_long', $this->is_mod ? $this->name_long : $mod_name_long);
        $result->add('game_mod_name_short', $this->is_mod ? $this->name_short : $mod_name_short);
        $result->add('game_hostname',htmlentities($this->server_data_stream['hostname'], ENT_QUOTES, "UTF-8"));
        $result->add('game_map', re($this->server_data_stream['map']));
        $result->add('game_map_pic_dir', ($this->goldsource ? 'goldsource' : 'source').'/'.($this->is_mod || $is_mod_ml ? $this->server_data_stream['game_dir'] : $this->basic_game_dir) );
        $result->add('game_type','');
        $result->add('game_dir', !$this->is_mod && !$is_mod_ml ? $this->server_data_stream['game_dir'] : $this->basic_game_dir);
        $result->add('game_mod', $this->is_mod || $is_mod_ml ? $this->server_data_stream['game_dir'] : '');
        $result->add('game_country','');
        $result->add('game_region','');
        $result->add('game_os', $this->server_data_stream['os'] == 'w' ? 'windows' : 'linux'); //Server OS
        $result->add('game_dedicated', $this->server_data_stream['dedicated'] == 'd' || $this->server_data_stream['dedicated'] == 'p' ? true : false);
        $result->add('game_hltv', $this->server_data_stream['dedicated'] == 'p' ? true : false);
        $result->add('game_num_players', $this->server_data_stream['num_players']);
        $result->add('game_max_players', $this->server_data_stream['max_players']);
        $result->add('game_num_bot', $this->server_data_stream['num_bots']);
        $result->add('game_password', $this->server_data_stream['password'] == '1' ? true : false);
        $result->add('game_secure', $secure);
        $result->add('game_engine', $this->goldsource ? 'goldsource' : 'source');
        $result->add('game_protocol', $this->server_data_stream['gq_protocol']);
        $result->add('game_transport', $this->server_data_stream['gq_transport']);
        $result->add('game_port', $this->server_data_stream['gq_port']);
        $result->add('game_address', $this->server_data_stream['gq_address']);
        $result->add('game_join_link', 'steam://connect/'.$this->server_data_stream['gq_address'].':'.$this->server_data_stream['gq_port']);
        $result->add('game_online', $this->server_data_stream['gq_online'] == '1' ? true : false);

        if($this->server_data_stream['gq_online'] == '1')
            GameQ::mkdir_img('maps/'.($this->goldsource ? 'goldsource' : 'source').'/'.($this->is_mod || $is_mod_ml ? $this->server_data_stream['game_dir'] : $this->basic_game_dir));

        /*
         * Custom Source & Goldsource settings
         */
        $custom_settings = array();
        foreach($this->server_data_stream as $key => $data)
        {
            // Settings for Source & Goldsource * Half-Life 1/2 & Mods
            $split00 = str_split($key, 3); $split01 = str_split($key, 4);
            if(in_array($split00[0], $this->settings_filter) || in_array($split01[0], $this->settings_filter))
                $custom_settings[$key] = $data;

            if($key == 'metamod_version' || $key == 'sourcemod_version') // Metamod & Sourcemod
                $custom_settings[$key] = $data;
        }
        $result->add('game_custom',$custom_settings);
        unset($custom_settings);

        $result->add('game_teams',array()); //not supported

        $player_list = array();
        if(array_key_exists('players', $this->server_data_stream) && count($this->server_data_stream['players']) >= 1)
        {
            foreach($this->server_data_stream['players'] as $player)
            {
                $player_name = htmlentities($player['name'], ENT_QUOTES, "UTF-8");
                if(empty($player_name) && !server_show_empty_players) continue;
                $new_player = array();
                $new_player['player_name'] = $player_name;
                $new_player['player_score'] = (string)($player['score']);
                $new_player['player_time'] = (string)($player['time']);
                $new_player['player_team'] = '0';
                $new_player['player_squad'] = '0';
                $new_player['player_kills'] = '0';
                $new_player['player_deaths'] = '0';
                $new_player['player_rank'] = '0';
                $new_player['player_ping'] = '0';
                $new_player['player_honor'] = '0';
                $new_player['player_goal'] = '0';
                $new_player['player_leader'] = '0';
                $new_player['player_stats'] = '0';
                $player_list[] = $new_player;
            }
        }

        //Player Stats
        $this->stats_options['stats_score']  = array_key_exists(($key_opt='stats_score'), $this->stats_options)  ? $this->stats_options[$key_opt] : true;
        $this->stats_options['stats_time']   = array_key_exists(($key_opt='stats_time'), $this->stats_options)   ? $this->stats_options[$key_opt] : true;
        $this->stats_options['stats_team']   = array_key_exists(($key_opt='stats_team'), $this->stats_options)   ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_squad']  = array_key_exists(($key_opt='stats_squad'), $this->stats_options)  ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_kills']  = array_key_exists(($key_opt='stats_kills'), $this->stats_options)  ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_deaths'] = array_key_exists(($key_opt='stats_deaths'), $this->stats_options) ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_rank']   = array_key_exists(($key_opt='stats_rank'), $this->stats_options)   ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_ping']   = array_key_exists(($key_opt='stats_ping'), $this->stats_options)   ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_honor']  = array_key_exists(($key_opt='stats_honor'), $this->stats_options)  ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_goal']   = array_key_exists(($key_opt='stats_goal'), $this->stats_options)   ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_leader'] = array_key_exists(($key_opt='stats_leader'), $this->stats_options) ? $this->stats_options[$key_opt] : false;
        $this->stats_options['stats_stats']  = array_key_exists(($key_opt='stats_stats'), $this->stats_options)  ? $this->stats_options[$key_opt] : false;
        $result->add('game_stats_options',$this->stats_options);

        switch(isset($_GET['spsort']) ? $_GET['spsort'] : 'score') {
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