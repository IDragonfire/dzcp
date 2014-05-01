<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 *
 * The Steam APIClass is exported from DZCP-Extended Edition
 */

class SteamAPI {
    static private $api_key = ''; //See http://steamcommunity.com/dev/apikey/
    static private $api_host = 'http://api.steampowered.com';
    static private $api_com = 'http://steamcommunity.com';
    static private $profile_url = '';
    static private $send_data_api = array();
    static private $user_data = array();
    static private $api_data = array();
    static private $community_data = array();
    static private $games_data = array();

    /**
     * Setzt einige Einstellungen für die Steam API
     *
     * @param string $key
     * @param string $var
    */
    public static function set($key='',$var='') {
        switch($key) {
            case 'apikey': self::$api_key = $var; return true; break;
            case 'apiurl': self::$api_host = $var; return true; break;
            case 'profileurl': self::$api_com = $var; return true; break;
        }

        return false;
    }

    /**
     * Abrufen der Informationen eines Steam Users
     *
     * @param string $custom_profile_url
     * @return boolean|array:
     */
    public static function getUserInfos($custom_profile_url) {
        if(empty($custom_profile_url)) {
            DebugConsole::insert_warning('SteamAPI::getUserInfos()', 'There was no specified Steam Profile-URL');
            return false;
        }

        self::$user_data = array(); self::$api_data = array(); self::$community_data = array(); $return = array();
        self::$profile_url = $custom_profile_url; //Benutzerdefinierte URL

        //User
        if(self::get_steamcommunity()) {
            self::$user_data['steamID'] = self::$community_data['profile']['steamID64'];
            self::$user_data['nickname'] = htmlentities(self::$community_data['profile']['steamID'], ENT_QUOTES, "UTF-8");
            self::$user_data['privacyState'] = self::$community_data['profile']['privacyState']; //public,private
            self::$user_data['onlineState'] = 'offline'; //Static * very slowly see *Status Hack
            self::$user_data['avatarIcon_url'] = self::$community_data['profile']['avatarIcon'];
            self::$user_data['avatarMedium_url'] = self::$community_data['profile']['avatarMedium'];
            self::$user_data['avatarFull_url'] = self::$community_data['profile']['avatarFull'];
            self::$user_data['memberSince'] = self::$community_data['profile']['privacyState'] != 'private' ? self::$community_data['profile']['memberSince'] : '';
            self::$user_data['location'] =  self::$community_data['profile']['privacyState'] != 'private' ? self::$community_data['profile']['location'] : '';
            self::$user_data['mostPlayedGame'] =  self::$community_data['profile']['privacyState'] != 'private' ? self::$community_data['profile']['mostPlayedGames'] : array();
            self::$user_data['vacBanned'] =  self::$community_data['profile']['vacBanned']; // 0 or 1
            self::$user_data['tradeBanState'] =  self::$community_data['profile']['tradeBanState']; // None
            self::$user_data['isLimitedAccount'] =  self::$community_data['profile']['isLimitedAccount']; // 0 or 1
            self::$user_data['runnedSteamAPI'] =  false;

            if(self::get_api('ISteamUser','GetPlayerSummaries')) {
                self::$user_data['lastlogoff'] = self::$api_data['lastlogoff'];
                self::$user_data['profile_url'] = self::$api_data['profileurl'];
                self::$user_data['communityvisibilitystate'] = self::$api_data['communityvisibilitystate'] == '3' ? true : false;
                self::$user_data['personastate'] = self::$api_data['personastate']; //0 - Offline, 1 - Online, 2 - Busy, 3 - Away, 4 - Snooze, 5 - looking to trade, 6 - looking to play
                self::$user_data['timecreated'] =  array_key_exists('timecreated', self::$api_data) ? self::$api_data['timecreated'] : '';
                self::$user_data['gameextrainfo'] = array_key_exists('gameextrainfo', self::$api_data) && self::$api_data['personastate'] >= 1 ? htmlentities(self::$api_data['gameextrainfo'], ENT_QUOTES, "UTF-8") : '';
                self::$user_data['gameid'] = array_key_exists('gameid', self::$api_data) && self::$api_data['personastate'] >= 1 ? self::$api_data['gameid'] : '';
                self::$user_data['loccountrycode'] = array_key_exists('loccountrycode', self::$api_data) ? self::$api_data['loccountrycode'] : ''; //DE
                self::$user_data['runnedSteamAPI'] =  true;

                //Status Hack for faster update * in-game, online, offline
                if(self::$user_data['personastate'] >= 1) self::$user_data['onlineState'] = 'online';
                if(!empty(self::$user_data['gameextrainfo'])) self::$user_data['onlineState'] = 'in-game';
            } else {
                //API Offline
                self::$user_data['onlineState'] = self::$community_data['profile']['onlineState'];
                self::$user_data['lastlogoff'] = '';
                self::$user_data['profile_url'] = 'http://steamcommunity.com/id/'.$custom_profile_url.'/';
                self::$user_data['communityvisibilitystate'] = false;
                self::$user_data['personastate'] = self::$user_data['onlineState'] == 'online' || self::$user_data['onlineState'] == 'in-game' ? '1' : '0';
                self::$user_data['timecreated'] =  '';
                self::$user_data['gameextrainfo'] = '';
                self::$user_data['gameid'] = '';
                self::$user_data['loccountrycode'] = '';
            }

            $return['user'] = self::$user_data; //User Data
        }
        else return false;

        //User Games
        if(self::$user_data['privacyState'] != 'private' && self::get_steamcommunity('games','gamesList/games'))
            $return['games'] = self::$community_data['games']['game']; //User Data

        return $return;
    }

    /* ############ Private ############ */

    /**
     * Ruft die aktuellen Status Informationen des Users ab
     *
     * @return boolean
     */
    private static final function get_api($interface='ISteamUser',$method='GetPlayerSummaries',$version='v0002')
    {
        global $cache;
        if(empty(self::$api_key) || empty(self::$user_data['steamID'])) return false;
        if(!$cache->isExisting('steam_api_'.$interface.'_'.$method.'_'.self::$profile_url)) {
            self::$send_data_api['format'] = 'xml';
            self::$send_data_api['key'] = self::$api_key;
            self::$send_data_api['steamids'] = self::$user_data['steamID'];
            if(!($xml_stream = file_get_contents(self::$api_host.'/'.$interface.'/'.$method.'/'.$version.'/?'.http_build_query(self::$send_data_api)))) {
                DebugConsole::insert_error('SteamAPI::get_api()', 'No connection to the API interface');
                return false;
            }

            if(strpos($xml_stream, 'Unauthorized') !== false) {
                DebugConsole::insert_error('SteamAPI::get_api()', 'The Steam Web API key is invalid');
                return false;
            }

            $cache->set('steam_api_'.$interface.'_'.$method.'_'.self::$profile_url,$xml_stream,steam_api_refresh);
        }
        else $xml_stream = $cache->get('steam_api_'.$interface.'_'.$method.'_'.self::$profile_url);

        $xml = self::objectToArray($xml_stream);
        if(array_key_exists('error',$xml) || empty($xml_stream)) return false;

        if(!$xml = simplexml_load_string($xml_stream, 'SimpleXMLElement', LIBXML_NOCDATA)) return false;
        self::$send_data_api = array();
        self::$api_data = self::objectToArray($xml->players->player);
        return is_array(self::$api_data);
    }

    /**
     * Gibt die Steam Community Informationen zurück
     *
     * @return boolean
     */
    private static final function get_steamcommunity($zone='',$xml='profile') {
        global $cache;
        $zone_url = !empty($zone) ? '/'.$zone.'/' : ''; $zone_tag = !empty($zone) ? $zone.'_' : 'profile';
        if(!$cache->isExisting('steam_'.$zone_url) || !steam_infos_cache)
        {
            $xml_stream = file_get_contents(self::$api_com.'/id/'.self::$profile_url.$zone_url.'?xml=1');
            if(empty($xml_stream)) {
                DebugConsole::insert_error('SteamAPI::get_steamcommunity()', 'No connection to the community interface!');
                DebugConsole::insert_warning('SteamAPI::get_steamcommunity()', 'URL: '.self::$api_com.'/id/'.self::$profile_url.'/'.$zone_url.'?xml=1');
                return false;
            }

            if(!$xml = simplexml_load_string($xml_stream, 'SimpleXMLElement', LIBXML_NOCDATA)) return false;
            $xml = self::objectToArray($xml);
            if(array_key_exists('error',$xml))
            {
                $xml_stream = file_get_contents(self::$api_com.'/profiles/'.self::$profile_url.'?xml=1');
                if(empty($xml_stream)) {
                    DebugConsole::insert_error('SteamAPI::get_steamcommunity()', 'No connection to the community interface!');
                    DebugConsole::insert_warning('SteamAPI::get_steamcommunity()', 'URL: '.self::$api_com.'/profiles/'.self::$profile_url.'?xml=1');
                    return false;
                }
            }

            $cache->set('steam_'.$zone_url, $xml_stream, steam_refresh);
        } else $xml_stream = $cache->get('steam_'.$zone_url);

        $xml = self::objectToArray($xml_stream);
        if(array_key_exists('error',$xml) || empty($xml_stream)) return false;

        if(!$xml = simplexml_load_string($xml_stream, 'SimpleXMLElement', LIBXML_NOCDATA)) return false;
        self::$community_data[str_replace('_', '', $zone_tag)] = self::objectToArray($xml);
        return true;
    }

    public static final function objectToArray($d)
    { return json_decode(json_encode($d, JSON_FORCE_OBJECT), true); }
}