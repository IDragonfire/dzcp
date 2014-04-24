<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 *
 * The Steam APIClass is exported from DZCP-Extended Edition
 */

class SteamAPI {
    static private $api_com = 'http://steamcommunity.com';
    static private $profile_url = '';
    static private $send_data_api = array();
    static private $user_data = array();
    static private $api_data = array();
    static private $community_data = array();
    static private $games_data = array();

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
     * Gibt die Steam Community Informationen zurück
     *
     * @return boolean
     */
    private static final function get_steamcommunity($zone='',$xml='profile') {
        global $cache;
        $zone_url = !empty($zone) ? $zone.'/' : ''; $zone_tag = !empty($zone) ? $zone.'_' : 'profile';
        if(!$cache->isExisting('steam_'.$zone_tag.'_'.self::$profile_url)) {
            $xml_stream = file_get_contents(self::$api_com.'/id/'.self::$profile_url.'/'.$zone_url.'?xml=1');
            if(empty($xml_stream)) {
                DebugConsole::insert_error('SteamAPI::get_steamcommunity()', 'No connection to the community interface!');
                DebugConsole::insert_warning('SteamAPI::get_steamcommunity()', 'URL: '.self::$api_com.'/id/'.self::$profile_url.'/'.$zone_url.'?xml=1');
                return false;
            }

            $cache->set('steam_'.$zone_tag.'_'.self::$profile_url,$xml_stream,3600);
        }
        else
            $xml_stream = $cache->get('steam_'.$zone_tag.'_'.self::$profile_url);

        if(empty($xml_stream)) return false;
        if(!$xml = simplexml_load_string($xml_stream, 'SimpleXMLElement', LIBXML_NOCDATA)) return false;
        self::$community_data[str_replace('_', '', $zone_tag)] = self::objectToArray($xml);
        $array_check = self::$community_data[str_replace('_', '', $zone_tag)];
        if(key_exists('error', $array_check)) return false;
        return true;
    }

    private static final function objectToArray($d)
    { return json_decode(json_encode($d, JSON_FORCE_OBJECT), true); }
}