<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1 Final
 * http://www.dzcp.de
 */

######## CONFIG ##############################################################################################################

  $server_name        = 'Call of Duty';
  $server_name_short  = 'CoD';
  $server_link        = 'cod4://{IP}:{S_PORT}';
  $server_name_config = array('callofduty2'          => array('Call of Duty 2',                    'CoD 2'),
                              'callofduty4'          => array('Call of Duty 4',                    'CoD 4'),
                              'codunitedoffensive'   => array('Call of Duty United Offensive',     'CoD UO'),
                              'callofdutyworldatwar' => array('Call of Duty World at War',         'CoD WW'),
  );

  $server_link_config = array('callofduty4' => 'cod4://{IP}:{S_PORT}',
  );

##############################################################################################################################

  function server_query_cod($ip, $port, $q_port, $request)
  {
    global $server_timeout;
    $q_port = empty($q_port) ? $port : $q_port;

    @set_time_limit(2);
    $fp = @fsockopen("udp://$ip", $port, $errno, $errstr, $server_timeout);
    if (!$fp) { return FALSE; }

    stream_set_timeout($fp, 1, 0); stream_set_blocking($fp, true);

    fwrite($fp, "\xFF\xFF\xFF\xFFgetstatus\x00");
    $tmp = fread($fp, 4096);
    fclose($fp);

    $tmp = trim($tmp);

    if (!$tmp) { return FALSE; }

    $rawdata = explode("\n", $tmp);
    $rawsetting = explode("\\", $rawdata[1]);

    for($i= 1; $i<count($rawsetting); $i++)
    {
      $rawsetting[$i] = strtolower($rawsetting[$i]);
      $rawsetting[$i] = preg_replace("/\^./", "", $rawsetting[$i]);
      $rawsetting[$i+1] = preg_replace("/\^./", "", $rawsetting[$i+1]);
      $setting[$rawsetting[$i]] = $rawsetting[$i+1];
      $i++;
    }

    unset($data);

    $data['gamemod']    = validate($setting['gamename']);

    $data['hostname']   = $setting['sv_hostname'];
    $data['mapname']    = strtolower($setting['mapname']);
    $data['players']    = count($rawdata) - 2;
    $data['maxplayers'] = $setting['sv_maxclients'];
    $data['password']   = $setting['g_needpass'];

    if (isset($setting['pswrd'])) { $data['password'] = $setting['pswrd']; }

    if ($request == "info") { return $data; }

    for($i=2; $i<count($rawdata); $i++)
    {
      if ($game == "sof2")
      {
        $tmp = explode(" ", $rawdata[$i], 4);
        $player[$i-1]['score']  = $tmp[0];
        $player[$i-1]['ping']   = $tmp[1];
        $player[$i-1]['deaths'] = $tmp[2];
        $player[$i-1]['name']   = substr(preg_replace("/\^./", "", $tmp[3]) , 1, -1);
      }
      else if ($game == "mohq3")
      {
        $tmp = explode(" ", $rawdata[$i], 2);
        $player[$i-1]['ping']   = $tmp[0];
        $player[$i-1]['name']   = substr(preg_replace("/\^./", "", $tmp[1]) , 1, -1);
      }
      else
      {
        $tmp = explode(" ", $rawdata[$i], 3);
        $player[$i-1]['score'] = $tmp[0];
        $player[$i-1]['ping']  = $tmp[1];
        $player[$i-1]['name']  = substr(preg_replace("/\^./", "", $tmp[2]) , 1, -1);
      }
    }

    if ($request == "players") { return $player; }

  }
?>