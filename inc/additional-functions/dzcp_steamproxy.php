<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

#########################################
//-> Proxy Settings Start
#########################################
$proxy_servers = array(); //Servers
$proxy_servers[] = array('host' => 'steamproxy.hammermaps.de', 'port' => 80);
#$proxy_servers[] = array('host' => 'api.meinserver.de', 'port' => 80);


#########################################
//-> Proxy Settings End
#########################################
function SteamAPI_Proxy($steamID='',$call='',$data0=array()) {
    global $proxy_servers;
    if(!allow_url_fopen_support()) return false;
    if(is_array($data0)) $send = array_merge(array('steamid' => $steamID,'call' => $call), $data0);
    else $send = array_merge(array('steamid' => $steamID,'call' => $call));
    $ctx = stream_context_create(array('http'=>array('timeout' => file_get_contents_timeout)));
    $send = bin2hex(gzcompress(base64_encode(serialize($send))));
    $proxy_servers = shuffle_assoc($proxy_servers);
    foreach ($proxy_servers as $server) {
        if(ping_port($server['host'],$server['port'],0.5)) break;
    }

    $ctx = stream_context_create(array('http'=>array('timeout' => file_get_contents_timeout)));
    if(!($stream = file_get_contents('http://'.$server['host'].':'.$server['port'].'/api.php?proxy=1&data='.$send, false, $ctx))) return false;
    if(empty($stream)) return false;
    $ret = unserialize(base64_decode(gzuncompress(hextobin($stream))));
    return array('status' => $ret['status'],'data' => $ret['data']);
}

function shuffle_assoc($list) {
    if (!is_array($list)) return $list;
    $keys = array_keys($list);
    shuffle($keys);
    $random = array();
    foreach ($keys as $key)
        $random[$key] = $list[$key];
    return $random;
}