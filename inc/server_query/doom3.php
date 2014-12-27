<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

######## CONFIG ##############################################################################################################

  $server_name       = 'Doom 3';
  $server_name_short = 'Doom 3';
  $server_link       = 'doom3://{IP}:{S_PORT}?game=Doom3&amp;action=show';

##############################################################################################################################

  function server_query_doom3($ip, $port, $q_port, $request)
  {
    global $server_timeout;
    $q_port = empty($q_port) ? $port : $q_port;

    @set_time_limit(2);
    $fp = @fsockopen("udp://$ip", $q_port, $errno, $errstr, $server_timeout);

    if (!$fp) { return FALSE; }

    stream_set_timeout($fp, 1, 0); stream_set_blocking($fp, true);

    fwrite($fp, "\xFF\xFFgetInfo");

    $buffer = fread($fp, 4096);

    fclose($fp);

    if (!$buffer) { return FALSE; }

    $buffer = substr($buffer, 23);
    $buffer = explode("\x00\x00\x00", $buffer);
    $rawsetting = explode("\x00", $buffer[0]);

    for($i=0; $i<count($rawsetting); $i=$i+2)
    {
      $rawsetting[$i] = strtolower($rawsetting[$i]);
      $rawsetting[$i] = preg_replace("/\^./", "", $rawsetting[$i]);
      $rawsetting[$i+1] = preg_replace("/\^./", "", $rawsetting[$i+1]);
      $setting[$rawsetting[$i]] = $rawsetting[$i+1];
    }

    preg_match_all("/(.)(..)(..)(..)(.*)\\x00/U", $buffer[1], $matches); // DOOM3

    for($i=0; $i<count($matches[5]); $i++)
    {
      $player[$i+1]['id']          = ord($matches[1][$i]);
      list(,$player[$i+1]['ping']) = unpack("s", $matches[2][$i]);
      list(,$player[$i+1]['rate']) = unpack("s", $matches[3][$i]);
      $player[$i+1]['name']        = preg_replace("/\^./", "", $matches[5][$i]);
      $player[$i+1]['tag']         = preg_replace("/\^./", "", $matches[6][$i]); // QUAKE4

      if ($player[$i+1]['tag']) { $player[$i+1]['name'] = $player[$i+1]['tag']." ".$player[$i+1]['name']; }
    }

    if($request == "players") { return $player;  }

    $data['gamemod']    = $setting['gamename'];
    $data['hostname']   = $setting['si_name'];
    $data['mapname']    = basename($setting['si_map']);
    $data['players']    = count($player);
    $data['maxplayers'] = $setting['si_maxplayers'];
    $data['password']   = $setting['si_usepass'];

    return $data;
  }

?>