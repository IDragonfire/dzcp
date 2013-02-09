<?php
######## CONFIG ##############################################################################################################

$server_name       = 'Americas Army';
$server_name_short = 'AArmy';
$server_link       = 'aarmy://{IP}:{S_PORT}';

##############################################################################################################################

function server_query_aarmy($ip, $port, $q_port, $request) {
    global $server_timeout;
    $q_port = empty($q_port) ? 1717 : $q_port;
    
    if ($request == "info") {
        $challenge = "\xFE\xFD\x00\xAA\xAA\xAA\xAA\xFF\x00\x00";
    }
    if ($request == "players") {
        $challenge = "\xFE\xFD\x00\x21\x21\x21\x21\x00\xFF\x00\x00";
    }
    
    @set_time_limit(2);
    $fp = @fsockopen("udp://$ip", $q_port, $errno, $errstr, $server_timeout);
    
    if (!$fp) {
        return FALSE;
    }
    
    stream_set_timeout($fp, 1, 0);
    stream_set_blocking($fp, true);
    
    fwrite($fp, $challenge);
    
    $buffer = fread($fp, 4096);
    
    fclose($fp);
    
    if (!$buffer) {
        return FALSE;
    }
    
    $buffer = trim(substr($buffer, 5));
    
    if ($request == "info") {
        $rawsetting = explode("\x00", $buffer);
        
        for ($i = 0; $i < count($rawsetting); $i = $i + 2) {
            if (!trim($rawsetting[$i])) {
                continue;
            }
            
            $rawsetting[$i]           = strtolower("$rawsetting[$i]");
            $setting[$rawsetting[$i]] = $rawsetting[$i + 1];
        }
    }
    
    if ($request == "info") {
        $data['gamemod']    = 'aarmy';
        $data['hostname']   = $setting['hostname'];
        $data['mapname']    = $setting['mapname'];
        $data['players']    = $setting['numplayers'];
        $data['maxplayers'] = $setting['maxplayers'];
        $data['password']   = $setting['password'];
        
        return $data;
    }
    
    if ($request == "players") {
        $buffer = substr($buffer, 7, -1);
        
        $player = array();
        
        if (strpos($buffer, "\x00\x00") === FALSE) {
            return $server;
        }
        
        $buffer     = explode("\x00\x00", $buffer, 2);
        $buffer[0]  = str_replace("_", "", $buffer[0]);
        $buffer[0]  = str_replace("player", "name", $buffer[0]);
        $field_list = explode("\x00", $buffer[0]);
        $item       = explode("\x00", $buffer[1]);
        
        $item_position = 0;
        $item_total    = count($item);
        $player_key    = 0;
        
        do {
            foreach ($field_list as $field) {
                $player[$player_key][$field] = $item[$item_position];
                
                $item_position++;
            }
            
            $player_key++;
        } while ($item_position < $item_total);
        
        return $player;
    }
}
?>