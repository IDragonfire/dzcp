<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

######## CONFIG ##############################################################################################################

  $server_name       = 'Battlefield 4';
  $server_name_short = 'BF 4';
  $server_link       = 'bf4://{IP}:{S_PORT}';

##############################################################################################################################

  function server_query_bf4($ip, $port, $q_port, $request)
  {
    global $server_timeout;

    $q_port = empty($q_port) ? 47200 : $q_port;

    @set_time_limit(20);
    $fp = @fsockopen("tcp://$ip", $q_port, $errno, $errstr, $server_timeout);

    if (!$fp) return false;
    stream_set_timeout($fp, 1, 0); stream_set_blocking($fp, true);

    if($request == 'info') fwrite($fp, "\x00\x00\x00\x00\x1B\x00\x00\x00\x01\x00\x00\x00\x0A\x00\x00\x00serverInfo\x00");
    else if($request == 'players') fwrite($fp, "\x00\x00\x00\x00\x24\x00\x00\x00\x02\x00\x00\x00\x0B\x00\x00\x00listPlayers\x00\x03\x00\x00\x00all\x00");

    $buffer = fread($fp, 4096);
    $buffer = substr($buffer, 12);
    $response = cut_pascal($buffer, 4, 0, 1);
    if($response != 'OK') return false;

    if($request == 'info') {
      $data['hostname']   = cut_pascal($buffer, 4, 0, 1);
      $data['players']    = cut_pascal($buffer, 4, 0, 1);
      $data['maxplayers'] = cut_pascal($buffer, 4, 0, 1);
      cut_pascal($buffer, 4, 0, 1);
      $data['gamemod']    = 'bf4';
	  switch (cut_pascal($buffer, 4, 0, 1)) {
		        // Battlefield 4 Maps
     			default:             $nmap = '-'; break;
				case 'MP_Abandoned': $nmap = 'Zavod 311'; break;
				case 'MP_Damage': $nmap = 'Lancang Dam'; break;
				case 'MP_Flooded': $nmap = 'Flood Zone'; break;
				case 'MP_Journey': $nmap = 'Golmud Railway'; break;
				case 'MP_Naval': $nmap = 'Paracel Storm'; break;
				case 'MP_Prison': $nmap = 'Operation Locker'; break;
				case 'MP_Resort': $nmap = 'Hainan Resort'; break;
				case 'MP_Siege': $nmap = 'Siege of Shanghai'; break;
				case 'MP_TheDish': $nmap = 'Rogue Transmission'; break;
				case 'MP_Tremors': $nmap = 'Dawnbreaker'; break;
				case 'XP1_001': $nmap = 'Silk Road'; break;
				case 'XP1_002': $nmap = 'Altai Range'; break;
				case 'XP1_003': $nmap = 'Guilin Peaks'; break;
				case 'XP1_004': $nmap = 'Dragon Pass'; break;
				case 'XP0_Metro': $nmap = 'Operation Metro 2014'; break;
				case 'XP0_Caspian': $nmap = 'Caspian Border 2014'; break;
				case 'XP0_Oman': $nmap = 'Gulf of Oman 2014'; break;
				case 'XP0_Firestorm': $nmap = 'Operation Firestorm 2014'; break;
				case 'XP2_001': $nmap = 'Lost Islands'; break;
				case 'XP2_002': $nmap = 'Nansha Strike'; break;
				case 'XP2_003': $nmap = 'Wave Breaker'; break;
				case 'XP2_004': $nmap = 'Operation Mortar'; break;
      }
	  $data['mapname']    = $nmap;
    } else if($request == 'players') {
      $field_total = cut_pascal($buffer, 4, 0, 1);
      $field_list  = array();

      for($i=0; $i<$field_total; $i++)  {
        $field_list[] = strtolower(cut_pascal($buffer, 4, 0, 1));
      }

      $player_total = cut_pascal($buffer, 4, 0, 1);

      for($i=0; $i<$player_total; $i++)
      {
        foreach($field_list AS $field)
        {
          $value = cut_pascal($buffer, 4, 0, 1);

          switch ($field)
          {
            case 'clantag': $data[$i]['name']  = $value; break;
            case 'name':    $data[$i]['name']  = empty($data[$i]['name']) ? $value : '['.$data[$i]['name'].'] '.$data[$i]['name'].' '.$value; break;
            default:        $data[$i][$field]  = $value; break;
          }
        }
      }
    }


    return $data;
  }
?>