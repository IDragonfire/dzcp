<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

final class cookie {
    private static $cname = "";
    private static $val = array();
    private static $expires;
    private static $dir = '/';
    private static $site = '';

    /**
    * Setzt die Werte f�r ein Cookie und erstellt es.
    */
    public final static function init($cname, $cexpires=false, $cdir="", $csite="") {
        if(array_key_exists('PHPSESSID', $_SESSION)) {
            self::$cname=$cname;
            self::$expires = ($cexpires ? $cexpires : (time()+cookie_expires));
            self::$dir=(empty($cdir) ? '/' : cookie_dir);
            self::$site=(empty($csite) ? '' : cookie_domain);
            self::$val=array();
            self::extract();
        }
    }

    /**
    * Extraktiert ein gespeichertes Cookie
    */
    public final static function extract($cname="") {
        global $cache;
        if(array_key_exists('PHPSESSID', $_SESSION)) {
            $cname=(empty($cname) ? self::$cname : $cname);
            if(!empty($_COOKIE[$cname])) {
                $arr = unserialize($_COOKIE[$cname]);
                if($arr!==false && is_array($arr)) {
                    foreach($arr as $var => $val)
                    { $_COOKIE[$var]=$val; }
                }

                self::$val=$arr;
            }
        }
    }

    /**
    * Liest und gibt einen Wert aus dem Cookie zur�ck
    *
    * @return string
    */
    public final static function get($var) {
        if(array_key_exists('PHPSESSID', $_SESSION)) {
            if(!isset(self::$val) || empty(self::$val)) return false;
            if(!array_key_exists($var, self::$val)) return false;
            return self::$val[$var];
        }

        return false;
    }

    /**
    * Setzt ein neuen Key und Wert im Cookie
    */
    public final static function put($var, $value) {
        if(array_key_exists('PHPSESSID', $_SESSION)) {
            self::$val[$var]=$value;
            $_COOKIE[$var]=self::$val[$var];
            if(empty($value)) unset(self::$val[$var]);
        }
    }

    /**
    * Leert das Cookie
    */
    public final static function clear()
    { self::$val=array(); self::save(); }

    /**
    * Loscht einen Wert aus dem Cookie
    */
    public final static function delete($var)
    { unset(self::$val[$var]); self::save(); }
    
    /**
    * Speichert das Cookie
    */
    public final static function save() {
        global $cache;
        if(array_key_exists('PHPSESSID', $_SESSION)) {
            $cookie_val = (empty(self::$val) ? '' : serialize(self::$val));
            if(strlen($cookie_val)>4*1024)
                trigger_error("The cookie ".self::$cname." exceeds the specification for the maximum cookie size.  Some data may be lost", E_USER_WARNING);

            setcookie(self::$cname, $cookie_val, self::$expires, self::$dir, self::$site);
        }
    }
}