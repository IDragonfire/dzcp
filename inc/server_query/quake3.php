<?php
######## CONFIG ##############################################################################################################

$server_name        = 'Quake 3';
$server_name_short  = 'Q3';
$server_link        = 'quake://{IP}:{S_PORT}';
$server_name_config = array(
    'c3ut3' => array(
        'Quake 3 Urban Terror 3',
        'Q3 UT3'
    ),
    'c3ut4' => array(
        'Quake 3 Urban Terror 4',
        'Q3 UT4'
    ),
    'cpma' => array(
        'Quake 3 Challenge Pro Mode',
        'Q3 CPMA'
    )
);

##############################################################################################################################

function server_query_quake3($ip, $port, $game, $request) {
    global $server_timeout;
    $q_port = empty($q_port) ? 29900 : $q_port;
    
    $fp = @fsockopen("udp://$ip", $port, $errno, $errstr, $server_timeout);
    
    if (!$fp) {
        return FALSE;
    }
    
    @set_time_limit(2);
    stream_set_timeout($fp, 1, 0);
    stream_set_blocking($fp, true);
    
    fwrite($fp, "\xFF\xFF\xFF\xFFgetstatus\x00");
    
    $tmp = fread($fp, 4096);
    
    fclose($fp);
    
    $tmp = trim($tmp);
    
    if (!$tmp) {
        return FALSE;
    }
    
    $rawdata = explode("\n", $tmp);
    
    $rawsetting = explode("\\", $rawdata[1]);
    
    for ($i = 1; $i < count($rawsetting); $i++) {
        $rawsetting[$i]           = strtolower($rawsetting[$i]);
        $rawsetting[$i]           = preg_replace("/\^./", "", $rawsetting[$i]);
        $rawsetting[$i + 1]       = preg_replace("/\^./", "", $rawsetting[$i + 1]);
        $setting[$rawsetting[$i]] = $rawsetting[$i + 1];
        $i++;
    }
    
    unset($data);
    
    $data['gamemod']    = $setting['gamename'];
    $data['hostname']   = $setting['sv_hostname'];
    $data['mapname']    = strtolower($setting['mapname']);
    $data['players']    = count($rawdata) - 2;
    $data['maxplayers'] = $setting['sv_maxclients'];
    $data['password']   = $setting['g_needpass'];
    
    if (isset($setting['pswrd'])) {
        $data['password'] = $setting['pswrd'];
    }
    
    if ($request == "info") {
        return $data;
    }
    
    for ($i = 2; $i < count($rawdata); $i++) {
        if ($game == "sof2") {
            $tmp                      = explode(" ", $rawdata[$i], 4);
            $player[$i - 1]['score']  = $tmp[0];
            $player[$i - 1]['ping']   = $tmp[1];
            $player[$i - 1]['deaths'] = $tmp[2];
            $player[$i - 1]['name']   = substr(preg_replace("/\^./", "", $tmp[3]), 1, -1);
        } else if ($game == "mohq3") {
            $tmp                    = explode(" ", $rawdata[$i], 2);
            $player[$i - 1]['ping'] = $tmp[0];
            $player[$i - 1]['name'] = substr(preg_replace("/\^./", "", $tmp[1]), 1, -1);
        } else {
            $tmp                     = explode(" ", $rawdata[$i], 3);
            $player[$i - 1]['score'] = $tmp[0];
            $player[$i - 1]['ping']  = $tmp[1];
            $player[$i - 1]['name']  = substr(preg_replace("/\^./", "", $tmp[2]), 1, -1);
        }
    }
    
    if ($request == "players") {
        return $player;
    }
    
}

?>