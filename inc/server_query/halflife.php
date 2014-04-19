<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

######## CONFIG ##############################################################################################################

  $server_name       = 'Halflife';
  $server_name_short = 'HL';
  $server_link       = 'steam://connect/{IP}:{S_PORT}';
  $server_name_config = array('cstrike'         => array('Counter-Strike 1.6',                'CS 1.6'),
                              'czero'           => array('Counter-Strike Condition:Zero',     'CS:CZ'),
                              'dod'             => array('Day of Defeat',                     'DoD'),
                              'dcrisis'         => array('Desert Crisis',                     'Desert Crisis'),
                              'firearms'        => array('Firearms',                          'Firearms'),
                              'ns'              => array('Natural Selection',                 'NS'),
                              'tfc'             => array('Team Fortress Classic',             'TFC'),
                              'fortressforever' => array('Team Fortress Forever' ,            'TFF'),
                              'si'              => array('Science and Industry',              'SI'),
                              'crusade'         => array('Dark Messiah of Might &amp; Magic', 'DMoMaM'),
                              'cspromod'        => array('Counter-Strike Pro Mod',            'CS Pro'),
  );

##############################################################################################################################

  function server_query_halflife($ip, $port, $q_port, $request)
  {
    global $server_timeout;
    $q_port = empty($q_port) ? $port : $q_port;

    @set_time_limit(2);
    $fp = @fsockopen("udp://$ip", $q_port, $errno, $errstr, $server_timeout);

    if (!$fp) { return FALSE; }

    stream_set_timeout($fp, 1, 0); stream_set_blocking($fp, true);

    if($request == "players")
    {
      fwrite($fp, "\xFF\xFF\xFF\xFF\x55\xFF\xFF\xFF\xFF");
      $tmp = fread($fp, 4096);

      if(!$tmp) { fclose($fp); return FALSE; }

      $challengenumber = substr($tmp, 5, 4);
    }

    if($request == "info")     { $challenge = "\xFF\xFF\xFF\xFFTSource Engine Query\x00"; }
    if($request == "players")  { $challenge = "\xFF\xFF\xFF\xFFU".$challengenumber;       }

    fwrite($fp, $challenge);
    $buffer = fread($fp, 4096);
    fclose($fp);

    $buffer = trim(substr($buffer, 4));

    if(!trim($buffer)) { return FALSE; }

    if($request == "info")
    {
      $tmp = substr($buffer, 2);
      $tmp = explode("\x00", $tmp);

      $place = strlen($tmp[0].$tmp[1].$tmp[2].$tmp[3]) + 8;

      $data['gamemod']       = $tmp[2];
      $data['hostname']      = $tmp[0];
      $data['mapname']       = $tmp[1];
      $data['players']       = ord($buffer[$place]);
      $data['maxplayers']    = ord($buffer[$place + 1]);
      $data['password']      = ord($buffer[$place + 5]);

      $data['version']       = ord($buffer[1]);
      $data['description']   = $tmp[3];
      $data['botplayers']    = ord($buffer[$place + 2]);
      $data['server_type']   = $buffer[$place + 3];

      return $data;
    }

    if ($request == "players")
    {
      unset($playernumber);

      $position = 2;

      do
      {
        $playernumber++;

        $player[$playernumber]['id'] = ord($buffer[$position]);
        $position ++;

        while($buffer[$position] != "\x00" && $position < 5000)
        {
          $player[$playernumber]['name'] .= $buffer[$position];
          $position ++;
        }

        $player[$playernumber]['score'] = (ord($buffer[$position + 1]))
                                      + (ord($buffer[$position + 2]) * 256)
                                      + (ord($buffer[$position + 3]) * 65536)
                                      + (ord($buffer[$position + 4]) * 16777216);

        if ($player[$playernumber]['score'] > 2147483648) { $player[$playernumber]['score'] -= 4294967296; }

        $tmp = substr($buffer, $position + 5, 4);
        if (strlen($tmp) < 4) { return FALSE; }
        $tmp = unpack("f", $tmp);

        $timestamp = mktime(0, 0, $tmp[1]);
        if (!$tmp[1]) { $timestamp = mktime(0, 0, $tmp[""]); }

        $player[$playernumber]['time'] = date("H:i:s", $timestamp);

        $position += 9;
      }
      while ($position < strlen($buffer));

      return $player;
    }
  }

?>