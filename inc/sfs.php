<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

class sfs {
    private static $endpoint = 'http://www.stopforumspam.com/';
    private static $url = '';
    private static $json = '';
    private static $confidence = 70;
    private static $frequency = 50;
    private static $autoblock = true;
    private static $blockuser = false;
    public static function check() {
        global $db,$userip;
        ## http://de.wikipedia.org/wiki/Private_IP-Adresse ##
        if(!validateIpV4Range($userip, '[192].[168].[0-255].[0-255]') && !validateIpV4Range($userip, '[127].[0].[0-255].[0-255]') && !validateIpV4Range($userip, '[10].[0-255].[0-255].[0-255]') && !validateIpV4Range($userip, '[172].[16-31].[0-255].[0-255]')) {
            $sql = db("SELECT * FROM `".$db['ipban']."` WHERE `ip` = '".$userip."' LIMIT 1");
            if(_rows($sql) >= 1) {
                $get = _fetch($sql);
                if((time()-$get['time']) > (2*86400) && $get['enable']) {
                    self::get(array('ip' => $userip)); //Array ( [success] => 1 [ip] => Array ( [lastseen] => 2013-04-26 19:57:51 [frequency] => 1327 [appears] => 1 [confidence] => 99.89 ) )
                    $stopforumspam = self::$json;
                    if($stopforumspam['success']) {
                        $stopforumspam = $stopforumspam['ip']; // Array ( [lastseen] => 2013-04-26 19:57:51 [frequency] => 1327 [appears] => 1 [confidence] => 99.89 )
                        $stopforumspam_data_db = unserialize($get['data']);
                        if($stopforumspam['appears'] == '1' && ($stopforumspam['confidence'] >= self::$confidence || $stopforumspam['frequency'] >= self::$frequency) && self::$autoblock) {
                            $stopforumspam_data_db['confidence'] = $stopforumspam['confidence'];
                            $stopforumspam_data_db['frequency'] = $stopforumspam['frequency'];
                            $stopforumspam_data_db['lastseen'] = $stopforumspam['lastseen'];
                            $stopforumspam_data_db['banned_msg'] = 'Autoblock by stopforumspam.com';
                            db("UPDATE `".$db['ipban']."` SET `time` = ".time().", `typ` = '1', `data` = '".serialize($stopforumspam_data_db)."' WHERE `id` = '".$get['id']."';");
                            db("DELETE FROM `".$db['c_ips']."` WHERE `ip` = '".$userip."';");
                            db("DELETE FROM `".$db['c_who']."` WHERE `ip` = '".$userip."';");
                            db("DELETE FROM `".$db['ip2dns']."` WHERE `ip` = '".$userip."';");
                            self::$blockuser = true;
                        } else {
                            $stopforumspam_data_db['appears'] = $stopforumspam['appears'];
                            db("UPDATE `".$db['ipban']."` SET `time` = ".time().", `typ` = '0', `data` = '".serialize($stopforumspam_data_db)."' WHERE `id` = '".$get['id']."';");
                            self::$blockuser = false;
                        }
                    }
                }
                else if($get['typ'] == 1)
                    self::$blockuser = true;
                else
                    self::$blockuser = false;
            } else {
                //typ: 0 = Off, 1 = GSL, 2 = SysBan, 3 = Ipban
                self::get(array('ip' => $userip)); //Array ( [success] => 1 [ip] => Array ( [lastseen] => 2013-04-26 19:57:51 [frequency] => 1327 [appears] => 1 [confidence] => 99.89 ) )
                $stopforumspam = self::$json;
                if($stopforumspam['success']) {
                    $stopforumspam = $stopforumspam['ip']; // Array ( [lastseen] => 2013-04-26 19:57:51 [frequency] => 1327 [appears] => 1 [confidence] => 99.89 )
                    if($stopforumspam['appears'] == '1' && $stopforumspam['confidence'] >= self::$confidence && $stopforumspam['frequency'] >= self::$frequency && self::$autoblock) {
                        $stopforumspam['banned_msg'] = 'Autoblock by stopforumspam.com';
                        db("DELETE FROM `".$db['c_ips']."` WHERE `ip` = '".$userip."';");
                        db("DELETE FROM `".$db['c_who']."` WHERE `ip` = '".$userip."';");
                        db("DELETE FROM `".$db['ip2dns']."` WHERE `ip` = '".$userip."';");
                        db("INSERT INTO `".$db['ipban']."` SET `ip` = '".$userip."', `time` = ".time().", `typ` = '1', `data` = '".serialize($stopforumspam)."';"); //Banned
                        self::$blockuser = true;
                    } else {
                        $stopforumspam['banned_msg'] = '';
                        db("INSERT INTO `".$db['ipban']."` SET `ip` = '".$userip."', `time` = ".time().",`typ` = '0', `data` = '".serialize($stopforumspam)."';"); //Add to DB
                        self::$blockuser = false;
                    }
                }
            }
        }
    }

    public static function is_spammer()
    { return self::$blockuser; }

    public static function get( $args = array() ) {
        self::$url = self::$endpoint.'api?f=json&'.http_build_query($args, '', '&');
        if(!self::call_json()) return array('data' => array('success' => '0'));
    }

    protected static function call_json() {
        $ctx = stream_context_create(array('http'=>array('timeout' => file_get_contents_timeout)));
        if(view_error_reporting && debug_save_to_file) {
            $fp = fopen(basePath."/inc/_logs/fsf_ips.log", "a+");
            fwrite($fp, self::$url); 
            fclose($fp);
        }

        if(!(self::$json = file_get_contents(self::$url, false, $ctx))) return false;
        if(empty(self::$json)) return false;

        self::$json = json_decode(self::$json,true);
        if(!self::$json) false;
        return true;
    }
}