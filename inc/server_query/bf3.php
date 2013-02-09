<?php
######## CONFIG ##############################################################################################################

$server_name       = 'Battlefield 3';
$server_name_short = 'BF 3';
$server_link       = 'bf3://{IP}:{S_PORT}';

##############################################################################################################################

function server_query_bf3($ip, $port, $q_port, $request) {
    global $server_timeout;
    
    $q_port = empty($q_port) ? 47200 : $q_port;
    
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
    
    $buffer   = fread($fp, 4096);
    $buffer   = substr($buffer, 12);
    $response = cut_pascal($buffer, 4, 0, 1);
    if ($response != 'OK')
        return false;
    
    if ($request == 'info') {
        $data['hostname']   = cut_pascal($buffer, 4, 0, 1);
        $data['players']    = cut_pascal($buffer, 4, 0, 1);
        $data['maxplayers'] = cut_pascal($buffer, 4, 0, 1);
        cut_pascal($buffer, 4, 0, 1);
        $data['gamemod'] = 'bf3';
        switch (cut_pascal($buffer, 4, 0, 1)) {
            default:
                $nmap = '-';
                break;
            case 'MP_001':
                $nmap = 'Grand Bazaar';
                break;
            case 'MP_003':
                $nmap = 'Teheran Highway';
                break;
            case 'MP_007':
                $nmap = 'Caspian Border';
                break;
            case 'MP_011':
                $nmap = 'Seine Crossing';
                break;
            case 'MP_012':
                $nmap = 'Operation Firestorm';
                break;
            case 'MP_013':
                $nmap = 'Damavand Peak';
                break;
            case 'MP_017':
                $nmap = 'Noshahar Canals';
                break;
            case 'MP_018':
                $nmap = 'Kharg Island';
                break;
            case 'MP_Subway':
                $nmap = 'Operation Metro';
                break;
            case 'XP1_001':
                $nmap = 'Strike at Karkand';
                break;
            case 'XP1_002':
                $nmap = 'Gulf of Oman';
                break;
            case 'XP1_003':
                $nmap = 'Sharqi Peninsula';
                break;
            case 'XP1_004':
                $nmap = 'Wake Island';
                break;
            case 'XP2_Factory':
                $nmap = 'Altmetall';
                break;
            case 'XP2_Office':
                $nmap = 'Operation 925';
                break;
            case 'XP2_Palace':
                $nmap = 'Donya-Festung';
                break;
            case 'XP2_Skybar':
                $nmap = 'Ziba-Turm';
                break;
            case 'XP3_Alborz':
                $nmap = 'Elburs-Gebirge';
                break;
            case 'XP3_Desert':
                $nmap = 'Bandar-Wüste';
                break;
            case 'XP3_Shield':
                $nmap = 'Armored Shield';
                break;
            case 'XP3_Valley':
                $nmap = 'Tal des Todes';
                break;
        }
        $data['mapname'] = $nmap;
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