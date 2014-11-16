<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1 Final
 * http://www.dzcp.de
 */

######## CONFIG ##############################################################################################################

  $server_name       = 'Vietcong 2';
  $server_name_short = 'Vietcong 2';
  $server_link       = 'vietcong2://{IP}:{S_PORT}';

##############################################################################################################################

  function server_query_vietcong2($ip, $port, $q_port, $request)
  {
    global $server_timeout;
    $q_port = empty($q_port) ? 19967 : $q_port;

    if ($request == "info")     { $challenge = "\xFE\xFD\x00\xAA\xAA\xAA\xAA\xFF\x00\x00"; }
    if ($request == "players")  { $challenge = "\xFE\xFD\x00\xAA\xAA\xAA\xAA\x00\xFF\x00"; }

    @set_time_limit(2);
    $fp = @fsockopen("udp://$ip", $q_port, $errno, $errstr, $server_timeout);

    if (!$fp) { return FALSE; }

    stream_set_timeout($fp, 1, 0); stream_set_blocking($fp, true);

    fwrite($fp, $challenge);
    $buffer = fread($fp, 4096);
    fclose($fp);

    if (!$buffer) { return FALSE; }

    $buffer = trim(substr($buffer, 5));

    if ($request == "info")
    {
      $rawsetting = explode("\x00",$buffer);

      for($i=0; $i<count($rawsetting); $i=$i+2)
      {
        if (!trim($rawsetting[$i])) { continue; }

        $rawsetting[$i] = strtolower("$rawsetting[$i]");
        $setting[$rawsetting[$i]] = $rawsetting[$i+1];
      }
    }

    if ($request == "info")
    {
      $data['hostname']   = $setting['hostname'];
      $data['mapname']    = $setting['mapname'];
      $data['players']    = $setting['numplayers'];
      $data['maxplayers'] = $setting['maxplayers'];
      $data['password']   = $setting['password'];

      return $data;
    }

    if ($request == "players")
    {
      $tmp = explode("\x00\x00",$buffer);

      $rawsetting = explode("\x00",$tmp[1]);

      $player_number = 0;

      for($i=0; $i<count($rawsetting); $i=$i+3)
      {
        $player_number++;
        $player[$player_number]['name']  = $rawsetting[$i];
        $player[$player_number]['score'] = $rawsetting[$i+1];
        $player[$player_number]['ping']  = $rawsetting[$i+2];
      }

      return $player;
    }
  }

?>