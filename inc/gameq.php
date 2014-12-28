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
 * Base GameQ Class
 *
 * This class should be the only one that is included when you use GameQ to query
 * any games servers.  All necessary sub-classes are loaded as needed.
 *
 * Requirements: See wiki or README for more information on the requirements
 *  - PHP 5.2+ (Recommended 5.3+)
 *  	* Bzip2 - http://www.php.net/manual/en/book.bzip2.php
 *  	* Zlib - http://www.php.net/manual/en/book.zlib.php
 *
 * @author Austin Bischoff <austin@codebeard.com>
 * Modified by Godkiller_NT (Hammermaps.de)
 *
 */

final class GameQ {
    const VERSION = '2.0.0.0';
    protected static $options = array('debug' => FALSE, 'timeout' => 3, 'filters' => array(), 'stream_timeout' => 400000, 'write_wait' => 500);
    protected static $debug = false;
    protected static $timeout = 3;
    protected static $filters = array();
    protected static $stream_timeout = 400000;
    protected static $write_wait = 500;
    protected static $servers = array();
    protected static $sockets = array();

    public static function init() {
        $cores = get_files(basePath.'/inc/gameq/',false,true,array('php'));
        foreach($cores as $core) {
            if(file_exists(basePath.'/inc/gameq/'.$core.'.php')) {
                include_once(basePath.'/inc/gameq/'.$core.'.php');
            }
        }

        $filters = get_files(basePath.'/inc/gameq/filters/',false,true,array('php'));
        foreach($filters as $filter) {
            if(file_exists(basePath.'/inc/gameq/filters/'.$filter.'.php')) {
                include_once(basePath.'/inc/gameq/filters/'.$filter.'.php');
            }
        }
     
        $protocols = get_files(basePath.'/inc/gameq/protocols/',false,true,array('php'));
        foreach($protocols as $protocol) {
            if(file_exists(basePath.'/inc/gameq/protocols/'.$protocol.'.php')) {
                include_once(basePath.'/inc/gameq/protocols/'.$protocol.'.php');
            }
        }
    }

    /**
     * Attempt to auto-load a class based on the name
     *
     * @param string $class
     */
    public static function auto_load($class) {
        // Transform the class name into a path
        $file = str_replace('_', '/', strtolower($class));

        // Find the file and return the full path, if it exists
        if($path = self::find_file($file))
        { require_once $path; }// Load the class file

        return true;
    }

    /**
     * Try to find the file based on the class passed.
     *
     * @param string $file
     */
    public static function find_file($file) {
        $found = false; // By default we did not find anything
        $path = basePath.'/inc/'.$file.'.php';
        if(is_file($path))
            $found = $path;

        return $found;
    }

    /**
     * Chainable call to __set, uses set as the actual setter
     *
     * @param string $var
     * @param mixed $value
     * @return GameQ
     */

    public static function setOption($var, $value) {
        self::$options[$var] = $value;
        switch ($var) {
            case 'debug': self::$debug=$value; break;
            case 'timeout': self::$timeout=$value; break;
            case 'filters': self::$filters=$value; break;
            case 'stream_timeout': self::$stream_timeout=$value; break;
            case 'write_wait': self::$write_wait=$value; break;
        }

        return true;
     }

    /**
     * Set an output filter.
     *
     * @param string $name
     * @param array $params
     * @return GameQ
     */
    public static function setFilter($name, $params = array()) {
        $filter_class = 'GameQ_Filters_'.$name; // Create the proper filter class name

        try {
            // Pass any parameters and make the class
            self::$options['filters'][$name] = new $filter_class($params);
        } catch (GameQ_FiltersException $e) {
            // We catch the exception here, thus the filter is not applied
            // but we issue a warning
            error_log($e->getMessage(), E_USER_WARNING);
        }

        return true;
    }

    /**
     * Remove a global output filter.
     *
     * @param string $name
     * @return GameQ
     */
    public static function removeFilter($name) { 
        unset(self::$options['filters'][$name]); return true; 
    }

    /**
     * Get Supported Games
     * @return array
     */
    public static function getGames() {
        $protocols_array = array();
        $protocols = get_files(basePath.'/inc/gameq/protocols/',false,true,array('php'));
        foreach($protocols as $protocol) {
            $class_name = 'GameQ_Protocols_'.ucfirst(pathinfo($protocol, PATHINFO_FILENAME));
            $reflection = new ReflectionClass($class_name);
            if(!$reflection->IsInstantiable()) {
                continue;
            }

            $class = new $class_name;
            $protocols_array[$class->name()] = array('name' => $class->name_long(),'port' => $class->port(),'state' => $class->state());
            unset($class);
        }

        ksort($protocols_array);
        return $protocols_array;
    }

    /**
     * Add a server to be queried
     *
     * @param array $server_info
     * @throws GameQException
     * @return boolean|GameQ
     */
    public static function addServer(Array $server_info=NULL) {
        // Check for server type
        if(!key_exists('type', $server_info) || empty($server_info['type']))
        { DebugConsole::insert_warning('inc\gameq.php', "Fehlender Server-Infokey 'type'"); return false; }

        // Check for server host
        if(!key_exists('host', $server_info) || empty($server_info['host']))
        { DebugConsole::insert_warning('inc\gameq.php', "Fehlender Server-Infokey 'host'"); return false; }

        // Check for server id
        if(!key_exists('id', $server_info) || empty($server_info['id']))
            $server_info['id'] = $server_info['host']; // Make an id so each server has an id when returned

        // Define these
        $server_id = $server_info['id'];
        $server_ip = '127.0.0.1';
        $server_port = false;

        // We have an IPv6 address (and maybe a port)
        if(substr_count($server_info['host'], ':') > 1) {
            // See if we have a port, input should be in the format [::1]:27015 or similar
            if(strstr($server_info['host'], ']:')) {
                $server_addr = explode(':', $server_info['host']); // Explode to get port
                $server_port = array_pop($server_addr); // Port is the last item in the array, remove it and save
                $server_ip = implode(':', $server_addr); // The rest is the address, recombine
                unset($server_addr);
            }
            else // Just the IPv6 address, no port defined
                $server_ip = $server_info['host'];

            // Now let's validate the IPv6 value sent, remove the square brackets ([]) first
            if(!filter_var(trim($server_ip, '[]'), FILTER_VALIDATE_IP, array('flags' => FILTER_FLAG_IPV6)))
            { DebugConsole::insert_warning('inc\gameq.php', "Die IPv6 Adresse '{$server_ip}' ist ung&uuml;ltig!"); return false; }
        } else  {
            if(strstr($server_info['host'], ':')) // We have a port defined
                list($server_ip, $server_port) = explode(':', $server_info['host']);
            else // No port, just IPv4
                $server_ip = $server_info['host'];

            // Validate the IPv4 value, if FALSE is not a valid IP, maybe a hostname.  Try to resolve
            if(!filter_var($server_ip, FILTER_VALIDATE_IP, array('flags' => FILTER_FLAG_IPV4))) {
                // When gethostbyname() fails it returns the original string
                // so if ip and the result from gethostbyname() are equal this failed.
                if($server_ip === DNSToIp($server_ip))
                { DebugConsole::insert_warning('inc\gameq.php', "Der Host '{$server_ip}' konnte nicht zur IP Adresse aufgel&ouml;st werden"); return false; }
            }
        }

        $protocol_class = 'GameQ_Protocols_'.ucfirst($server_info['type']); // Create the class so we can reference it properly later
        self::$servers[$server_id] = new $protocol_class($server_ip, $server_port, array_merge(self::$options, $server_info)); // Create the new instance and add it to the servers list
        return true;
    }

    /**
     * Add multiple servers at once
     *
     * @param array $servers
     * @return GameQ
     */
    public static function addServers(Array $servers=NULL) {
        // Loop thru all the servers and add them
        foreach($servers AS $server_info)
        { self::addServer($server_info); }
    }

    /**
     * Clear all the added servers.  Creates clean instance.
     *
     * @return GameQ
     */
    public static function clearServers() {
        // Reset all the servers
        self::$servers = array();
        self::$sockets = array();
        return true;
    }

    /**
     * Make all the data requests (i.e. challenges, queries, etc...)
     *
     * @return multitype:Ambigous <multitype:, multitype:boolean string mixed >
     */
    public static function requestData() {
        if(!fsockopen_support()) {
            return false;
        }

        // Init the query and returned array
        $queries = array('multi' => array('challenges' => array(),'info' => array()),'linear' => array()); $data = array();

        // Loop thru all of the servers added and categorize them
        foreach(self::$servers AS $server_id => $instance) {
            // Check to see what kind of server this is and how we can send packets
            if($instance->packet_mode() == GameQ_Protocols::PACKET_MODE_LINEAR)
                $queries['linear'][$server_id] = $instance;
            else // We can send this out in a multi request
            {
                // Check to see if we should issue a challenge first
                if($instance->hasChallenge())
                    $queries['multi']['challenges'][$server_id] = $instance;

                // Add this instance to do info query
                $queries['multi']['info'][$server_id] = $instance;
            }
        }

        // First lets do the faster, multi queries
        if(count($queries['multi']['info']) > 0)
        { self::requestMulti($queries['multi']); }

        // Now lets do the slower linear queries.
        if(count($queries['linear']) > 0)
        { self::requestLinear($queries['linear']); }

        // Now let's loop the servers and process the response data
        foreach(self::$servers AS $server_id => $instance)
        { $data[$server_id] = self::filterResponse($instance); } // Lets process this and filter

        //CleanUP
        self::$filters = array();
        self::$servers = array();
        self::$sockets = array();

        // Send back the data array, could be empty if nothing went to plan
        return $data;
    }

    /* Working Methods */

    /**
     * Apply all set filters to the data returned by gameservers.
     *
     * @param GameQ_Protocols $protocol_instance
     * @return array
     */
    protected static function filterResponse(GameQ_Protocols $protocol_instance) {
        // Let's pull out the "raw" data we are going to filter
        $data = $protocol_instance->processResponse();

        // Loop each of the filters we have attached
        foreach(self::$options['filters'] AS $filter_name => $filter_instance)
        { $data = $filter_instance->filter($data, $protocol_instance); }

        return $data;
    }

    /**
     * Process "linear" servers.  Servers that do not support multiple packet calls at once.  So Slow!
     * This method also blocks the socket, you have been warned!!
     *
     * @param array $servers
     * @return boolean
     */
    protected static function requestLinear($servers=array()) {
        // Loop thru all the linear servers
        foreach($servers AS $server_id => $instance) {
            // First we need to get a socket and we need to block because this is linear
            if(($socket = self::socket_open($instance, TRUE)) === FALSE)
                continue;

            $socket_id = (int) $socket; // Socket id

            // See if we have challenges to send off
            if($instance->hasChallenge()) {
                fwrite($socket, $instance->getPacket('challenge')); // Now send off the challenge packet
                $instance->challengeResponse(array(fread($socket, 4096))); // Read in the challenge response
                $instance->challengeVerifyAndParse(); // Now we need to parse and apply the challenge response to all the packets that require it
            }

            // Invoke the beforeSend method
            $instance->beforeSend();

            // Grab the packets we need to send, minus the challenge packet
            $packets = $instance->getPacket('!challenge');

            // Now loop the packets, begin the slowness
            foreach($packets AS $packet_type => $packet) {
                // Add the socket information so we can retreive it easily
                self::$sockets = array($socket_id => array('server_id' => $server_id, 'packet_type' => $packet_type, 'socket' => $socket));

                // Write the packet
                fwrite($socket, $packet);

                // Get the responses from the query
                $responses = self::sockets_listen();

                // Lets look at our responses
                foreach($responses AS $socket_id => $response)
                { $instance->packetResponse($packet_type, $response); }
            }
        }

        // Now close all the socket(s) and clean up any data
        self::sockets_close();
        return TRUE;
    }

    /**
     * Process the servers that support multi requests. That means multiple packets can be sent out at once.
     *
     * @param array $servers
     * @return boolean
     */
    protected static function requestMulti($servers=array()) {
        // See if we have any challenges to send off
        if(count($servers['challenges']) > 0) {
            // Now lets send off all the challenges
            self::sendChallenge($servers['challenges']);

            // Now let's process the challenges
            // Loop thru all the instances
            foreach($servers['challenges'] AS $server_id => $instance)
            { $instance->challengeVerifyAndParse(); }
        }

        // Send out all the query packets to get data for
        self::queryServerInfo($servers['info']);

        return TRUE;
    }

    /**
     * Send off needed challenges and get the response
     *
     * @param array $instances
     * @return boolean
     */
    protected static function sendChallenge(Array $instances=NULL) {
        // Loop thru all the instances we need to send out challenges for
        foreach($instances AS $server_id => $instance) {
            // Make a new socket
            if(($socket = self::socket_open($instance)) === FALSE)
                continue;

            // Now write the challenge packet to the socket.
            fwrite($socket, $instance->getPacket(GameQ_Protocols::PACKET_CHALLENGE));

            // Add the socket information so we can retreive it easily
            self::$sockets[(int) $socket] = array('server_id' => $server_id, 'packet_type' => GameQ_Protocols::PACKET_CHALLENGE, 'socket' => $socket);

            // Let's sleep shortly so we are not hammering out calls rapid fire style hogging cpu
            usleep(self::$write_wait);
        }

        // Now we need to listen for challenge response(s)
        $responses = self::sockets_listen();

        // Lets look at our responses
        foreach($responses AS $socket_id => $response) {
            $server_id = self::$sockets[$socket_id]['server_id']; // Back out the server_id we need to update the challenge response for
            self::$servers[$server_id]->challengeResponse($response); // Now set the proper response for the challenge because we will need it later
        }

        // Now close all the socket(s) and clean up any data
        self::sockets_close();

        return TRUE;
    }

    /**
     * Query the server for actual server information (i.e. info, players, rules, etc...)
     *
     * @param array $instances
     * @return boolean
     */
    protected static function queryServerInfo(Array $instances=NULL) {
        // Loop all the server instances
        foreach($instances AS $server_id => $instance) {
            // Invoke the beforeSend method
            $instance->beforeSend();

            // Get all the non-challenge packets we need to send
            $packets = $instance->getPacket('!challenge');

            if(count($packets) == 0)
                continue;

            // Now lets send off the packets
            foreach($packets AS $packet_type => $packet) {
                // Make a new socket
                if(($socket = self::socket_open($instance)) === FALSE)
                    continue;

                stream_socket_sendto($socket, $packet); // Now write the packet to the socket.
                self::$sockets[(int) $socket] = array('server_id' => $server_id, 'packet_type' => $packet_type, 'socket' => $socket); // Add the socket information so we can retreive it easily
                usleep(self::$write_wait); // Let's sleep shortly so we are not hammering out calls raipd fire style
            }
        }

        $responses = self::sockets_listen(); // Now we need to listen for packet response(s)

        // Lets look at our responses
        foreach($responses AS $socket_id => $response) {
            $server_id = self::$sockets[$socket_id]['server_id']; // Back out the server_id
            $packet_type = self::$sockets[$socket_id]['packet_type']; // Back out the packet type
            self::$servers[$server_id]->packetResponse($packet_type, $response); // Save the response from this packet
        }

        // Now close all the socket(s) and clean up any data
        self::sockets_close();
        return true;
    }

    /* Sockets/streams stuff */

    /**
     * Open a new socket based on the instance information
     *
     * @param GameQ_Protocols $instance
     * @param bool $blocking
     * @throws GameQException
     * @return boolean|resource
     */
    protected static function socket_open(GameQ_Protocols $instance, $blocking=FALSE) {
        $remote_addr = sprintf("%s://%s:%d", $instance->transport(), $instance->ip(), $instance->port()); // Create the remote address
        $context = stream_context_create(array('socket' => array('bindto' => '0:0'))); // Create context

        DebugConsole::insert_info('GameQ::socket_open()', 'Connect to '.$instance->ip().':'.$instance->port().' via '.$instance->transport());

        if(!ping_port($instance->ip(), $instance->port(), 1, $instance->transport() == 'udp' ? true : false))
            return false;

        DebugConsole::insert_info('GameQ::socket_open()', 'Connected to '.$instance->ip().':'.$instance->port());

        // Create the socket
        $errno = NULL; $errstr = NULL;
        if(($socket = @stream_socket_client($remote_addr, $errno, $errstr, self::$timeout, STREAM_CLIENT_CONNECT, $context)) !== FALSE) {
            stream_set_timeout($socket, self::$timeout); // Set the read timeout on the streams
            stream_set_blocking($socket, $blocking); // Set blocking mode
            DebugConsole::insert_successful('GameQ::socket_open()', 'Server Transport available');
            DebugConsole::insert_successful('GameQ::socket_open()', $socket.' is available');
        } else {
            // Check to see if we are in debug mode, if so throw the exception
            DebugConsole::insert_warning('GameQ::socket_open()', "&Ouml;ffnen eines Sockets zum Server '{$remote_addr}' konnte nicht ausgef&uuml;hrt werden!");
            DebugConsole::insert_error('GameQ::socket_open()', "Error: ".$errstr.' | '.$errno);
            return false;
        }

        unset($context, $remote_addr);

        // return the socket
        return $socket;
    }

    /**
     * Listen to all the created sockets and return the responses
     *
     * @return array
     */
    protected static function sockets_listen() {
        $loop_active = true;
        $responses = array();
        $sockets = array();

        // Loop and pull out all the actual sockets we need to listen on
        foreach(self::$sockets AS $socket_id => $socket_data)
        { $sockets[$socket_id] = $socket_data['socket']; }

        // Init some variables
        $read = $sockets;
        $write = NULL;
        $except = NULL;
        
        if(empty($read)) {
            return $responses;
        }

        // This is when it should stop
        $time_stop = microtime(true) + self::$timeout;

        // Let's loop until we break something.
        while ($loop_active && microtime(true) < $time_stop) {
            // Now lets listen for some streams, but do not cross the streams!
            $streams = stream_select($read, $write, $except, 0, self::$stream_timeout);

            // We had error or no streams left, kill the loop
            if($streams === false || ($streams <= 0))
            { $loop_active = false; break; }

            // Loop the sockets that received data back
            foreach($read AS $socket) {
                // See if we have a response
                if(($response = stream_socket_recvfrom($socket, 8192)) === false)
                    continue; // No response yet so lets continue.

                // Initial testing showed this change did not affect any of the other protocols
                if(strlen($response) == 0) {
                    // End the while loop
                    $loop_active = false;
                    break;
                }

                // Add the response we got back
                $responses[(int) $socket][] = $response;
            }
            // Because stream_select modifies read we need to reset it each
            // time to the original array of sockets
            $read = $sockets;
        }

        // Free up some memory
        unset($streams, $read, $write, $except, $sockets, $time_stop, $response);
        return $responses;
    }

    /**
     * Close all the open sockets
     */
    protected static function sockets_close() {
        // Loop all the existing sockets, valid or not
        foreach(self::$sockets AS $socket_id => $data) {
            DebugConsole::insert_info('GameQ::sockets_close()', 'Close Resource id #'.$socket_id);
            fclose($data['socket']);
            unset(self::$sockets[$socket_id]);
        }

        return true;
    }

    /**
     * Sucht nach Game Icons
     *
     * @param string $icon
     * @return array
     */
    public static function search_game_icon($icon='') {
        global $picformat;
        $image = '../inc/images/gameicons/unknown.gif'; $found = false;
        foreach($picformat AS $end) {
            if(file_exists(basePath.'/inc/images/gameicons/'.$icon.'.'.$end)) {
                $found = true;
                $image = '../inc/images/gameicons/'.$icon.'.'.$end;
                break;
            }
        }

        return array('image'=> $image, 'found'=> $found);
    }

    /**
     * Legt fehlende Ordner an
     *
     * @param string $dir * test/test/test
     */
    public static function mkdir_img($dir='') {
        $dirs = explode('/', $dir); $goDir='';
        foreach ($dirs as $dir) {
            $goDir .= (empty($goDir) ? $dir : '/'.$dir);
            if(!is_dir(basePath.'/inc/images/'.$goDir))
                mkdir(basePath.'/inc/images/'.$goDir);
        }
    }
    
    /**
     * Sortiert ein Array anhand eines Keys
     *
     * @param array $records
     * @param strin $field
     * @param boolean $reverse
     * @return array
     */
    public static function record_sort($named_recs, $order_by, $rev=false, $flags=0) {
        if(is_array($named_recs) && !empty($order_by)) {
            $named_hash = array();
            foreach($named_recs as $key=>$fields)
                $named_hash["$key"] = $fields[$order_by];

            $rev ? arsort($named_hash,$flags=0) : asort($named_hash, $flags=0);

            $sorted_records = array();
            foreach($named_hash as $key=>$val)
                $sorted_records["$key"]= $named_recs[$key];

            return $sorted_records;
        }

        return $named_recs;
    }
}

/**
 * GameQ Exception Class
 *
 * Thrown when there is any kind of internal configuration error or
 * some unhandled or unexpected error or response.
 *
 * @author Austin Bischoff <austin@codebeard.com>
 */
class GameQException extends Exception {}
