<?php
######## CONFIG ##############################################################################################################

$server_name       = 'Battlefield Bad Company';
$server_name_short = 'BF:BC';
$server_link       = 'bfbc2://{IP}:{S_PORT}';

##############################################################################################################################

function server_query_bfbc2($ip, $port, $q_port, $request) {
    global $server_timeout;
    
    $q_port = empty($q_port) ? 48888 : $q_port;
    
    @set_time_limit(20);
    $fp = @fsockopen("tcp://$ip", $q_port, $errno, $errstr, $server_timeout);
    
    if (!$fp)
        return false;
    stream_set_timeout($fp, 1, 0);
    stream_set_blocking($fp, true);
    
    if ($request == 'info')
        fwrite($fp, "\x00\x00\x00\x00\x1B\x00\x00\x00\x01\x00\x00\x00\x0A\x00\x00\x00serverInfo\x00");
    else if ($request == 'players')
        fwrite($fp, "\x00\x00\x00\x00\x24\x00\x00\x00\x02\x00\x00\x00\x0B\x00\x00\x00listPlayers\x00\x03\x00\x00\x00all\x00");
    
    $buffer = fread($fp, 4096);
    $buffer = substr($buffer, 12);
    
    $response = cut_pascal($buffer, 4, 0, 1);
    if ($response != 'OK')
        return false;
    
    if ($request == 'info') {
        $data['hostname']   = cut_pascal($buffer, 4, 0, 1);
        $data['players']    = cut_pascal($buffer, 4, 0, 1);
        $data['maxplayers'] = cut_pascal($buffer, 4, 0, 1);
        $data['gamemod']    = strtolower(cut_pascal($buffer, 4, 0, 1));
        $data['mapname']    = cut_pascal($buffer, 4, 0, 1);
    } else if ($request == 'players') {
        $field_total = cut_pascal($buffer, 4, 0, 1);
        $field_list  = array();
        
        for ($i = 0; $i < $field_total; $i++) {
            $field_list[] = strtolower(cut_pascal($buffer, 4, 0, 1));
        }
        
        $player_total = cut_pascal($buffer, 4, 0, 1);
        
        for ($i = 0; $i < $player_total; $i++) {
            foreach ($field_list AS $field) {
                $value = cut_pascal($buffer, 4, 0, 1);
                
                switch ($field) {
                    case 'clantag':
                        $data[$i]['name'] = $value;
                        break;
                    case 'name':
                        $data[$i]['name'] = empty($data[$i]['name']) ? $value : '[' . $data[$i]['name'] . '] ' . $data[$i]['name'] . ' ' . $value;
                        break;
                    default:
                        $data[$i][$field] = $value;
                        break;
                }
            }
        }
    }
    
    
    return $data;
}
?>