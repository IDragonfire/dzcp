<?php
######## CONFIG ##############################################################################################################

  $server_name       = 'Command &amp; Conquer Renegade';
  $server_name_short = 'C&amp;C';
  $server_link       = 'cnc://{IP}:{S_PORT}';

##############################################################################################################################

  function server_query_cnc($ip, $port, $q_port, $request)
  {
    global $server_timeout;
    $q_port = empty($q_port) ? $port : $q_port;
    
    $i = 0;
    $list = array();
    @set_time_limit(2);
    $connect = @fsockopen("udp://".$ip, $q_port, $errno, $errstr, $server_timeout);
    if($connect)
    {
      socket_set_timeout ($connect, 1, 000000);
      $send = "\\info\\";
      fputs($connect, $send);
      fwrite ($connect, $send);
      $output = fread ($connect, 1);

      if(!empty($output)) {
       do {
         $status_pre = socket_get_status ($connect);
         $out = fread ($connect, 1);

    	 if ($out=="\\") { if(empty ($b)) $b=1;

    	  $list[$b++]=$output;
    	  $output="";
         } else {
          $output = $output . $out;
         }
         $status_post = socket_get_status ($connect);
       } while ($status_pre['unread_bytes'] != $status_post['unread_bytes']);
      };
      fclose($connect);
      $data = ($output);
      $temp = array();
      $players = array();
      $stat = array();
      $stat2 = array();
      $temp = preg_split('/\n/', $data);
      for($i=0;$i<count($temp);$i++) {
        if ($i>0) {
         $players[$i-1]=$temp[$i];
        }
      }
  
      for($i=0;$i<count($list);$i++)
      {
        if($i>0)
        {
          if($list[$i] == "\gamename")  $gamename = $list[$i+1];
          if($list[$i] == "gametype")   $g_gametype = $list[$i+1];
          if($list[$i] == "mapname")    $mapname = $list[$i+1];
          if($list[$i] == "password")   $pswrd = $list[$i+1];
          if($list[$i] == "maxplayers") $sv_maxclients = $list[$i+1];
          if($list[$i] == "hostname")   $servername = $list[$i+1];
          if($list[$i] == "numplayers") $numplayers = $list[$i+1];
          if($list[$i] == "timeleft")   $timeleft = $list[$i+1];
  
       	  if(empty($p)) $p=0;
  
      	  if($list[$i] == "player_$p") 
          {	
            $p++;
      		  $player_name[$p]  = $list[$i+1];
      		  $player_score[$p] = $list[$i-1];
      		  $player_ping[$p]  = $list[$i-3] ;
      	  }
        }
      }
  
      if($request == "info")
      {
        $data['gametype']   = "cnc";
        $data['gamemod']    = $gamename;
        $data['hostname']   = $servername;
        $data['mapname']    = $mapname;
        $data['players']    = $numplayers;
        $data['maxplayers'] = $sv_maxclients;
        $data['password']   = $pswrd;
  
        return $data;
      }
      
      if($request == "players")
      {
        for($i=0;$i<count($player_name);$i++)
        {
        	$player[$i+1]['name']  = 	$player_name[$i+1];
        	$player[$i+1]['score'] = 	$player_score[$i+1];
          $player[$i+1]['ping']  =	$player_ping[$i+1];
        }
  
        return $player;
      }
    }
  }
  
?>