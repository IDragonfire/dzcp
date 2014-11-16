<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1 Final
 * http://www.dzcp.de
 */

######## CONFIG ##############################################################################################################

  $server_name       = 'Halflife 2';
  $server_name_short = 'HL 2';
  $server_link       = 'steam://connect/{IP}:{S_PORT}';
  $server_name_config = array('cstrike'    => array('Counter-Strike Source',             'CS:S'),
                              'dod'        => array('Day of Defeat Source',              'DoD:S'),
                              'cspromod'   => array('Counter-Strike Source Pro Mod',     'CS:S Pro'),
                              'tf'         => array('Team Fortress 2',                   'TF 2'),
                              'left4dead'  => array('Left 4 Dead',                       'LFD'),
                              'left4dead2' => array('Left 4 Dead 2',                     'LFD2'),
                              'insurgency' => array('Insurgency',                        'INS'),
                              'csgo'       => array('Counter-Strike: Global Offensive',  'CS:GO'),
                              'garrysmod'  => array('Garry\'s Mod',                      'GMOD'),
  );

##############################################################################################################################

  function server_query_halflife2($ip, $port, $q_port, $request)
  {
    global $server_timeout;
    $q_port = empty($q_port) ? $port : $q_port;

    $fp = @fsockopen("udp://$ip", $port, $errno, $errstr, $server_timeout);

    if (!$fp) { return FALSE; }

    stream_set_timeout($fp, 1, 0); stream_set_blocking($fp, true);

    if ($request == "players")
    {
      fwrite($fp, "\xFF\xFF\xFF\xFF\x55\x00\x00\x00\x00");

      $tmp = fread($fp, 4096);

      if (!$tmp) { fclose($fp); return FALSE; }

      $challengenumber = substr($tmp, 5, 4);
    }

    if ($request == "info")     { $challenge = "\xFF\xFF\xFF\xFFTSource Engine Query\x00"; }
    if ($request == "players")  { $challenge = "\xFF\xFF\xFF\xFFU".$challengenumber;       }

    fwrite($fp, $challenge);

    $buffer = fread($fp, 4096);

    fclose($fp);

    $buffer = trim(substr($buffer, 4));

    if (!trim($buffer)) { return FALSE; }


    if ($request == "info")
    {
      $data = array();

      $tmp = substr($buffer, 2);
      $tmp = explode("\x00", $tmp);

      $place = strlen($tmp[0].$tmp[1].$tmp[2].$tmp[3]) + 8;

      $data['gamemod']       = $tmp[2];
      $data['hostname']      = $tmp[0];
      $data['mapname']       = $tmp[1];
      $data['players']       = ord($buffer[$place]);
      $data['maxplayers']    = ord($buffer[$place + 1]);
      $data['password']      = ord($buffer[$place + 5]);

      $data['datatype']      = $buffer[0];
      $data['version']       = ord($buffer[1]);
      $data['description']   = $tmp[3];
      $data['botplayers']    = ord($buffer[$place + 2]);
      $data['server_type']   = $buffer[$place + 3];
      $data['server_os']     = $buffer[$place + 4];
      $data['server_bots']   = ord($buffer[$place + 2]);
      $data['server_secure'] = ord($buffer[$place + 6]);

      return $data;
    }

    if($request == "players")
    {
      $player = array();

      unset($player_key);

      $buffer = substr($buffer, 2);
      $player_key = 0;
      while ($buffer)
      {
        $player[$player_key]['id']   = ord(cut_byte($buffer, 1));
        $player[$player_key]['name']  = cut_string($buffer);
        $player[$player_key]['score'] = @_unpack(cut_byte($buffer, 4), "l");
        $player[$player_key]['time']  = _time(@_unpack(cut_byte($buffer, 4), "f"));

        $player_key ++;
      }

      return $player;
    }
  }

?>
