<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1 Final
 * http://www.dzcp.de
 */

######## CONFIG ##############################################################################################################

  $server_name       = 'FarCry';
  $server_name_short = 'FarCry';
  $server_link       = 'farcry://{IP}:{S_PORT}';

##############################################################################################################################

  function server_query_farcry($ip, $port, $q_port, $request)
  {
    global $server_timeout;
    $q_port = empty($q_port) ? $port + 123 : $q_port;

    @set_time_limit(2);
    $fp = @fsockopen("udp://$ip", $q_port, $errno, $errstr, $server_timeout);

    if (!$fp) { return FALSE; }

    stream_set_timeout($fp, 1, 0); stream_set_blocking($fp, true);

    fwrite($fp, "s");
    $buffer = fread($fp, 4096);
    fclose($fp);

    if (!$buffer) { return FALSE; }

    $buffer = substr($buffer, 4);
    $buffer_part = explode("\x01", $buffer, 2);
    $buffer = $buffer_part[0];
    $position = 0;

    do
    {
      $rawsetting[] = substr($buffer, $position+1, ord($buffer[$position])-1);

      $position = $position + ord($buffer[$position]);
    }
    while ($position < strlen($buffer));

    $setting['game']       = $rawsetting[0];
    $setting['port']       = $rawsetting[1];
    $setting['hostname']   = parse_color(preg_replace("/\\$\d/", "", $rawsetting[2]), 'farcry');
    $setting['mode']       = $rawsetting[3];
    $setting['mapname']    = $rawsetting[4];
    $setting['version']    = $rawsetting[5];
    $setting['password']   = $rawsetting[6];
    $setting['players']    = $rawsetting[7];
    $setting['maxplayers'] = $rawsetting[8];

    for($i=9; $i<=count($rawsetting); $i=$i+2)
    {
      if (!trim($rawsetting[$i])) { continue; }

      $rawsetting[$i] = strtolower("$rawsetting[$i]");
      $setting[$rawsetting[$i]] = $rawsetting[$i+1];
    }

    $data['gamemod']    = $setting['gr_ssmod'];
    $data['hostname']   = $setting['hostname'];
    $data['mapname']    = $setting['mapname'];
    $data['players']    = $setting['players'];
    $data['maxplayers'] = $setting['maxplayers'];
    $data['password']   = $setting['password'];

    if ($request == "info") { return $data; }

    $buffer = $buffer_part[1];

    if (!$buffer[0]) { return FALSE; exit; }

    $player_id  = 0;
    $position   = 0;

    do
    {
      unset($field_list);

      if (ord($buffer[$position]) & 1)  { $field_list[] = "name";         }
      if (ord($buffer[$position]) & 2)  { $field_list[] = "team";         }
      if (ord($buffer[$position]) & 4)  { $field_list[] = "skin_NOTUSED"; }
      if (ord($buffer[$position]) & 8)  { $field_list[] = "score";        }
      if (ord($buffer[$position]) & 16) { $field_list[] = "ping";         }
      if (ord($buffer[$position]) & 32) { $field_list[] = "time";         }

      $player_id++;
      $position++;

      foreach ($field_list as $field)
      {
        $increment = ord($buffer[$position]);

        $player[$player_id][$field] = substr($buffer, $position+1, $increment-1);

        if ($field == "name")
        {
          $player[$player_id] = preg_replace("/\\$\d/", "", $player[$player_id]);
        }

        $position += $increment;
      }
    }
    while ($position < strlen($buffer));

    return $player;
  }

?>