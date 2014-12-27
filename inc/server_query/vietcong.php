<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

######## CONFIG ##############################################################################################################

  $server_name       = 'Vietcong';
  $server_name_short = 'Vietcong';
  $server_link       = 'vietcong://{IP}:{S_PORT}';

##############################################################################################################################

  function server_query_vietcong($ip, $port, $q_port, $request)
  {
    global $server_timeout;
    $q_port = empty($q_port) ? 15425 : $q_port;

    if ($request == "info")    { $challenge = "\\basic\\\\info\\\\rules\\"; }
    if ($request == "players") { $challenge = "\\players\\"; }

    @set_time_limit(2);
    $fp = @fsockopen("udp://$ip", $q_port, $errno, $errstr, $server_timeout);

    if (!$fp) { return FALSE; }

    stream_set_timeout($fp, 1, 0); stream_set_blocking($fp, true);

    fwrite($fp, $challenge);

    $buffer = fread($fp, 4096);

    if (!$buffer) { fclose($fp); return FALSE; }

    if (!strstr($buffer, "\\final\\"))
    {
      $buffer .= fread($fp, 4096);
    }

    if ($request == "players" && !strstr($buffer, "\\player_0\\") && strstr($buffer, "\\final\\") )
    {
      $buffer = fread($fp, 4096) . $buffer;
    }

    fclose($fp);

    $buffer = trim($buffer);

    if ($request == "info")
    {
      $buffer = explode("\\player_0", $buffer);
      $buffer = $buffer[0];

      $buffer = explode("\\leader_0", $buffer);
      $buffer = $buffer[0];

      $rawsetting = explode("\\", $buffer);

      for($i=1; $i<count($rawsetting); $i++)
      {
        $rawsetting[$i] = strtolower("$rawsetting[$i]");

        if ($rawsetting[$i] != "final" && $rawsetting[$i] != "queryid")
        {
          $setting[$rawsetting[$i]] = $rawsetting[$i+1];
        }

        $i++;
      }

      unset($data);

      $data['gamemod'] = $setting['gamename'];
      $data['hostname'] = $setting['sv_hostname'];

      if (!$data['hostname']) { $data['hostname'] = $setting['hostname']; }

      $data['mapname']    = str_replace("_"," ",$setting['mapname']);
      $data['players']    = $setting['numplayers'];
      $data['maxplayers'] = $setting['maxplayers'];
      $data['password']   = $setting['password'];

      return $data;
    }

    if ($request == "players")
    {
      $rawsetting = explode("\\", $buffer);

      for($i=1; $i<count($rawsetting); $i++)
      {
        if (!strstr($rawsetting[$i], "_")) { $i++; continue; }

        $rawsetting[$i] = strtolower("$rawsetting[$i]");

        $buffer = explode("_", $rawsetting[$i], 2);

        if ($buffer[0] == "player")     { $buffer[0] = "name";  }
        if ($buffer[0] == "playername") { $buffer[0] = "name";  }
        if ($buffer[0] == "frags")      { $buffer[0] = "score"; }
        if ($buffer[0] == "ngsecret")   { $buffer[0] = "stats"; }

        if ($buffer[0] == "ping" && !$rawsetting[$i+1]) { $buffer[0] = "null"; }

        if (is_numeric($buffer[1]))
        {
          $player[$buffer[1]+1][$buffer[0]] = $rawsetting[$i+1];
        }

        $i++;
      }

      return $player;
    }
  }

?>