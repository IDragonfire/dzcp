<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

######## CONFIG ##############################################################################################################

  $server_name       = 'S.T.A.L.K.E.R';
  $server_name_short = 'STALKER';
  $server_link       = 'stalker://{IP}:{S_PORT}';

##############################################################################################################################

  function server_query_stalker($ip, $port, $q_port, $request)
  {
    global $server_timeout;
    $q_port = empty($q_port) ? $port + 2 : $q_port;

    @set_time_limit(2);
    $fp = @fsockopen("udp://$ip", $q_port, $errno, $errstr, $server_timeout);
    if (!$fp) { return FALSE; }
    stream_set_timeout($fp, 1, 0); stream_set_blocking($fp, true);

    fwrite($fp, "\xFE\xFD\x00\x21\x21\x21\x21\xFF\xFF\xFF\x01");
    $packet_check = "/(hostname\\x00|player_\\x00|score_\\x00|ping_\\x00|deaths_\\x00|pid_\\x00|skill_\\x00|team_\\x00|team_t\\x00|score_t\\x00)/U";

    $packet_check_expected = 6;

    $packet1 = fread($fp, 1400);
    if (!trim($packet1)) { fclose($fp); return FALSE; }

    preg_match_all($packet_check, $packet1, $matches);
    if (count(array_unique($matches[1])) < $packet_check_expected)
    {
      $packet2 = fread($fp, 1400);
      if (!trim($packet2)) { fclose($fp); return FALSE; }

      preg_match_all($packet_check, $packet1.$packet2, $matches);
      if (count(array_unique($matches[1])) < $packet_check_expected)
      {
        $packet3 = fread($fp, 1400);
        if (!trim($packet3)) { fclose($fp); return FALSE; }

        preg_match_all($packet_check, $packet1.$packet2.$packet3, $matches);
        if (count(array_unique($matches[1])) < $packet_check_expected)
        {
          fclose($fp); return FALSE;
        }
      }
    }

    fclose($fp); // CLOSE CONNECTION;

    if ( strstr($packet3, "hostname\x00") ) { $tmp = $packet3; $packet3 = $packet1; $packet1 = $tmp; }
    if ( strstr($packet2, "hostname\x00") ) { $tmp = $packet2; $packet2 = $packet1; $packet1 = $tmp; }
    if ( strstr($packet2, "score_t") )      { $tmp = $packet3; $packet3 = $packet2; $packet2 = $tmp; }

    if ($packet2 && substr($packet1, -1) != "\x00\x00")
    {
      $tmp = explode("\x00", $packet1);
      array_pop($tmp);
      array_pop($tmp);
      $tmp = implode("\x00", $tmp);
      $tmp .= "\x00\x00";
      $packet1 = $tmp;
    }

    if ($packet3 && substr($packet2, -2) != "\x00\x00")
    {
      $tmp = explode("\x00", $packet2);
      array_pop($tmp);
      array_pop($tmp);
      $tmp = implode("\x00", $tmp);
      $tmp .= "\x00\x00";
      $packet2 = $tmp;
    }

    $buffer = $packet1.$packet2.$packet3;
    $buffer = preg_replace("/\\x00\\x00....splitnum/U", "", $buffer);

    $server = substr($buffer, 16);
    $server = explode("\x01", $server, 2);
    $server = explode("\x00", $server[0]);

    for($i=0; $i<count($server); $i=$i+2)
    {
      if (!trim($server[$i])) { continue; }

      $server[$i] = strtolower("$server[$i]");
      $setting[$server[$i]] = $server[$i+1];
    }

    if ($request == "settings") { return $setting; }


    if ($request == "info")
    {
      unset($data);

      $data['gamemod']    = $setting['gamename'];
      $data['hostname']   = $setting['hostname'];
      $data['mapname']    = $setting['mapname'];
      $data['players']    = $setting['numplayers'];
      $data['maxplayers'] = $setting['maxplayers'];
      $data['password']   = $setting['password'];

      return $data;
    }

    if ($request == "players")
    {
      $buffer = explode("\x01", $buffer, 2);
      $buffer = $buffer[1];

      $name     = preg_match_all("/player_\\x00.(.*)\\x00\\x00/U", $buffer, $match);
      $name     = explode("\x00", $match[1][0]."\x00".$match[1][1]);

      if (!$name[0]) { return FALSE; }

      $score    = preg_match_all(" /score_\\x00.(.*)\\x00\\x00/U", $buffer, $match);
      $score    = explode("\x00", $match[1][0]."\x00".$match[1][1]);
      $ping     = preg_match_all("  /ping_\\x00.(.*)\\x00\\x00/U", $buffer, $match);
      $ping     = explode("\x00", $match[1][0]."\x00".$match[1][1]);
      $deaths   = preg_match_all("/deaths_\\x00.(.*)\\x00\\x00/U", $buffer, $match);
      $deaths   = explode("\x00", $match[1][0]."\x00".$match[1][1]);
      $pid      = preg_match_all("   /pid_\\x00.(.*)\\x00\\x00/U", $buffer, $match);
      $pid      = explode("\x00", $match[1][0]."\x00".$match[1][1]);
      $skill    = preg_match_all(" /skill_\\x00.(.*)\\x00\\x00/U", $buffer, $match);
      $skill    = explode("\x00", $match[1][0]."\x00".$match[1][1]);
      $team     = preg_match_all("  /team_\\x00.(.*)\\x00\\x00/U", $buffer, $match);
      $team     = explode("\x00", $match[1][0]."\x00".$match[1][1]);
      $teamname = preg_match_all(" /team_t\\x00.(.*)\\x00\\x00/U", $buffer, $match);
      $teamname = explode("\x00", $match[1][0]."\x00".$match[1][1]);

      for($i=0; $i<count($name); $i++)
      {
        if (!$name[$i]) { continue; }

        $player[$i+1]['name']   = $name[$i];
        $player[$i+1]['score']  = $score[$i];
        $player[$i+1]['ping']   = $ping[$i];
        $player[$i+1]['deaths'] = $deaths[$i];
        $player[$i+1]['pid']    = $pid[$i];
        $player[$i+1]['skill']  = $skill[$i];
        $player[$i+1]['team']   = $teamname[$team[$i]-1];
      }
      return $player;
    }
  }

?>