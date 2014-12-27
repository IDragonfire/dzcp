<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

######## CONFIG ##############################################################################################################

  $server_name       = 'Frontlines: Fuel of War';
  $server_name_short = 'Frontlines';
  $server_link       = 'ffow://{IP}:{S_PORT}?game=FrontlinesFuelofWar&amp;action=show';

##############################################################################################################################

  function server_query_frontlines($ip, $port, $q_port, $request)
  {
    global $server_timeout;
    $q_port = empty($q_port) ? 5478 : $q_port;

    @set_time_limit(2);
    $fp = @fsockopen("udp://".$ip, $q_port, $errno, $errstr, $server_timeout);

    if($request == "players")
    {
      fwrite($fp, "\xFF\xFF\xFF\xFF\x57");

      $challenge_packet = fread($fp, 4096);
      if (!$challenge_packet) { return FALSE; }
      $challenge_code = substr($challenge_packet, 5, 4);
    }

    if($request == "players") fwrite($fp, "\xFF\xFF\xFF\xFF\x55{$challenge_code}");
    else                      fwrite($fp, "\xFF\xFF\xFF\xFFFLSQ");

    $buffer = fread($fp, 4096);

    if (!$buffer) { return FALSE; }

    if($request == "info")
    {
      $buffer = substr($buffer, 6); // REMOVE PACKET HEADER

      $data['hostname']   = cut_string($buffer);
      $data['mapname']    = cut_string($buffer);
      $data['gamemod']    = strtolower(cut_string($buffer));
      $data['gametype']   = cut_string($buffer);
      $data['players']    = _unpack(cut_byte($buffer, 1), "C");
      $data['maxplayers'] = _unpack(cut_byte($buffer, 1), "C");

      return $data;
    }


    if($request == "players")
    {
      $buffer = substr($buffer, 4); // REMOVE PACKET HEADER

      $response_type = cut_byte($buffer, 1);
      $returned = ord(cut_byte($buffer, 1));

      $player = array();
      $player_key = 0;

      while($buffer)
      {
        $player[$player_key]['pid']   = ord(cut_byte($buffer, 1));
        $player[$player_key]['name']  = cut_string($buffer);
        $player[$player_key]['score'] = _unpack(cut_byte($buffer, 4), "N");
        $player[$player_key]['time']  = _time(_unpack(strrev(cut_byte($buffer, 4)), "f"));
        $player[$player_key]['ping']  = _unpack(cut_byte($buffer, 2), "n");
        $player[$player_key]['uid']   = _unpack(cut_byte($buffer, 4), "N");
        $player[$player_key]['team']  = ord(cut_byte($buffer, 1));

        $player_key ++;
      }

      $player = array_reverse($player);

      return $player;
    }
  }

?>