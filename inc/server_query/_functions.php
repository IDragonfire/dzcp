<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

$server_timeout = 5;

function validate($string, $pattern = 'a-zA-Z0-9') {
    return strtolower(str_replace(' ', '', preg_replace("#[^".$pattern."+]#Uis", '', $string)));
}

function cut_string(&$buffer, $end_marker = "\x00") {
    $length = strpos($buffer, $end_marker);

    if ($length === FALSE)
      $length = strlen($buffer);

    $string = substr($buffer, 0, $length);
    $buffer = substr($buffer, $length + strlen($end_marker));
    return $string;
}

function _unpack($string, $format) {
    list(,$string) = unpack($format, $string);
    return $string;
}

function cut_byte(&$buffer, $length) {
    $string = substr($buffer, 0, $length);
    $buffer = substr($buffer, $length);
    return $string;
}

function _time($seconds) {
    if ($seconds < 0) { return ""; }

    $h = intval(intval($seconds) / 3600);
    $m = intval(($seconds / 60) % 60);
    $s = intval($seconds % 60);

    $h = str_pad($h, "2", "0", STR_PAD_LEFT);
    $m = str_pad($m, "2", "0", STR_PAD_LEFT);
    $s = str_pad($s, "2", "0", STR_PAD_LEFT);

    return "{$h}:{$m}:{$s}";
}

function parse_color($string, $type) {
    switch($type)
    {
        case "swat4": $string = preg_replace("/\[c=......\]/Usi", "", $string); break;
        case "farcry": $string = preg_replace("/\\$\d/", "", $string); break;
    }

    return $string;
}

function cut_pascal(&$buffer, $start_byte = 1, $length_adjust = 0, $end_byte = 0) {
    $length = ord(substr($buffer, 0, $start_byte)) + $length_adjust;
    $string = substr($buffer, $start_byte, $length);
    $buffer = substr($buffer, $start_byte + $length + $end_byte);

    return $string;
}

function gs_normalise($server) {
    $keys = array('gamemod'=>'','hostname'=>'','maxplayers'=>'0',
                  'players'=>'0','mapname'=>'','game'=>'');
    foreach($keys as $key => $default) {
        if(!isset($server[$key]))
            $server[$key] = $default;
    }

    return $server;
}

function ping_port($address='',$port=0000,$timeout=2,$udp=false)
{
    if(!fsockopen_support())
        return false;

    $errstr = NULL; $errno = NULL;
    if(!$ip = DNSToIp($address))
        return false;

    if($fp = @fsockopen(($udp ? "udp://".$ip : $ip), $port, $errno, $errstr, $timeout))
    {
        unset($ip,$port,$errno,$errstr,$timeout);
        @fclose($fp);
        return true;
    }

    return false;
}

function DNSToIp($address='')
{
    if(!preg_match('#^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$#', $address))
    {
        if(!($result = gethostbyname($address)))
            return false;

        if ($result === $address)
            $result = false;
    }
    else
        $result = $address;

    return $result;
}