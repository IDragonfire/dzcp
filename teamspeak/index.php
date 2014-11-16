<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1 Final
 * http://www.dzcp.de
 */

## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$where = _site_teamspeak;
$dir = "teamspeak";

## SECTIONS ##
  if(fsockopen_support())
  {
    if(!$config_cache['use_cache'] || !$cache->isExisting('teamspeak_'.$language) || isset($_GET['cID']))
    {
    switch(settings('ts_version')):
    default; case '2';
    $uip      = settings('ts_ip');
    $tPort = settings('ts_sport');
    $port  = settings('ts_port');
    @set_time_limit(2);
      $fp = @fsockopen($uip, $tPort, $errno, $errstr, 2);

      if(!$fp)
    {
      $index = error(_error_no_teamspeak, 1);
    } else {
        $out = "";

            fputs($fp, "sel ".$port."\n");
            fputs($fp, "si\n");
            fputs($fp, "quit\n");
            while(!feof($fp))
        {
                $out .= fgets($fp, 1024);
            }
            $out = str_replace("[TS]", "", $out);
            $out = str_replace("OK", "", $out);
            $out = trim($out);

          $name=substr($out,indexOf($out,"server_name="),strlen($out));
          $name=substr($name,0,indexOf($name,"server_platform=")-strlen("server_platform="));
          $os=substr($out,indexOf($out,"server_platform="),strlen($out));
          $os=substr($os,0,indexOf($os,"server_welcomemessage=")-strlen("server_welcomemessage="));
          $uptime=substr($out,indexOf($out,"server_uptime="),strlen($out));
          $uptime=substr($uptime,0,indexOf($uptime,"server_currrentusers=")-strlen("server_currrentusers="));
          $cAmount=substr($out,indexOf($out,"server_currentchannels="),strlen($out));
          $cAmount=substr($cAmount,0,indexOf($cAmount,"server_bwinlastsec=")-strlen("server_bwinlastsec="));
          $user=substr($out,indexOf($out,"server_currentusers="),strlen($out));
          $user=substr($user,0,indexOf($user,"server_currentchannels=")-strlen("server_currentchannels="));
          $max=substr($out,indexOf($out,"server_maxusers="),strlen($out));
          $max=substr($max,0,indexOf($max,"server_allow_codec_celp51=")-strlen("server_allow_codec_celp51="));
      fclose($fp);
      }

      $uArray = array();
      $innerArray = array();
      $out = "";
      $j = 0;
      $k = 0;

      $fp = fsockopen($uip, $tPort, $errno, $errstr, 30);
      if($fp)
    {
          fputs($fp, "pl ".$port."\n");
          fputs($fp, "quit\n");
          while(!feof($fp))
      {
              $out .= fgets($fp, 1024);
          }
          $out = str_replace("[TS]", "", $out);
          $out = str_replace("loginname", "loginname\t", $out);
          $data    = explode("\t", $out);

          for($i=0;$i<count($data);$i++)
      {
              $innerArray[$j] = $data[$i];
              if($j>=15)
              {
                  $uArray[$k]=$innerArray;
                  $j = 0;
                  $k = $k+1;
              } else {
                  $j++;
              }
          }
          fclose($fp);
      }
      $debug = false;

    for($i=1;$i<count($uArray);$i++)
    {
        $innerArray=$uArray[$i];
      $p = '<img src="../inc/images/tsicons/channel.gif" alt="" class="tsicon" /> '.setUserStatus($innerArray[12])."&nbsp;<span class=\"fontBold\">".removeChar($innerArray[14])."</span>
           &nbsp;(".setPPriv($innerArray[11])."".setCPriv($innerArray[10]).")";

      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
      $userstats .= show($dir."/userstats", array("player" => $p,
                                                  "channel" => getChannelName($innerArray[1],$uip,$port,$tPort),
                                                  "misc1" => $innerArray[6],
                                                  "class" => $class,
                                                  "misc2" => $innerArray[7],
                                                  "misc3" => time_convert($innerArray[8]),
                                                  "misc4" => time_convert($innerArray[9])));
      }

      $uArr = getTSChannelUsers($uip,$port,$tPort);
      $pcArr = Array();
      $ccArr = Array();
      $thisArr = Array();
      $listArr = Array();
      $usedArr = Array();
      $cArr    = getChannels($uip,$port,$tPort);
      $z = 0;
      $x = 0;

      for($i=0;$i<count($cArr);$i++)
      {
          $innerArr=$cArr[$i];
          $listArr[$i]=$innerArr[3];
      }
      sort($listArr);
      for($i=0;$i<count($listArr);$i++)
      {
          for($j=0;$j<count($cArr);$j++)
          {
              $innArr=$cArr[$j];

              if($innArr[3]==$listArr[$i] && usedID($usedArr,$innArr[0]))
              {
                  if($innArr[2]==-1)
                  {
                      $thisArr[0] = $innArr[0];
                      $thisArr[1] = $innArr[5];
                      $thisArr[2] = $innArr[2];
                      $pcArr[$z] = $thisArr;
                      $usedArr[count($usedArr)] = $innArr[0];
                      $z++;
                  } else {
                      $thisArr[0] = $innArr[0];
                      $thisArr[1] = $innArr[5];
                      $thisArr[2] = $innArr[2];
                      $ccArr[$x] = $thisArr;
                      $usedArr[count($usedArr)] = $innArr[0];
                      $x++;
                  }
              }
          }
      }

      for($i=0;$i<count($pcArr);$i++)
    {
        $innerArr=$pcArr[$i];

      $subchan = "";
        for($j=0;$j<count($ccArr);$j++)
      {
          $innerCCArray=$ccArr[$j];
          if($innerArr[0]==$innerCCArray[2])
        {
             for($p=1;$p<count($uArr);$p++)
          {
            $subusers = "";
            for($p=1;$p<count($uArr);$p++)
            {
                    $innerUArray=$uArr[$p];
                    if($innerCCArray[0]==$innerUArray[1])
                    {
                $subusers .= "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"../inc/images/tsicons/trenner.gif\" alt=\"\" class=\"tsicon\" />".setUserStatus($innerUArray[12])."&nbsp;<span class=\"fontBold\">".removeChar($innerUArray[14])."</span>&nbsp;(".setPPriv($innerUArray[11])."".setCPriv($innerUArray[10]).")<br />";
                }
                }
              }
          $subchannels = "<img src=\"../inc/images/tsicons/trenner.gif\" alt=\"\" class=\"tsicon\" /><img src=\"../inc/images/tsicons/channel.gif\" alt=\"\" class=\"tsicon\" /><a style=\"font-weight:normal\" href=\"?cID=".$innerCCArray[0]."&amp;type=1\">&nbsp;".removeChar($innerCCArray[1])."&nbsp;</a><br /> ".$subusers."";
          $subchan .= show($dir."/subchannels", array("subchannels" => $subchannels));
          }
      }
      $users = "";
      for($k=1;$k<count($uArr);$k++)
      {
            $innerUArray=$uArr[$k];
            if($innerArr[0]==$innerUArray[1])
        {
          $users .= "<img src=\"../inc/images/tsicons/trenner.gif\" alt=\"\" class=\"tsicon\" />".setUserStatus($innerUArray[12])."<span class=\"fontBold\">".removeChar($innerUArray[14])."</span>&nbsp;(".setPPriv($innerUArray[11])."".setCPriv($innerUArray[10]).")<br />";
            }
        }

      $channels = "<img src=\"../inc/images/tsicons/channel.gif\" alt=\"\" class=\"tsicon\" />&nbsp;<a style=\"font-weight:bold\" href=\"?cID=".trim($innerArr[0])."&amp;type=1\">".removeChar($innerArr[1])."&nbsp;</a><br /> ".$users."";
      $chan .= show($dir."/channel", array("channel" => $channels,
                                           "subchannels" => $subchan));
    }

    if(isset($_GET['cID']))
    {
        $cID     = $_GET['cID'];
        $type    = $_GET['type'];
    } else {
        $cID     = 0;
        $type    = 0;
    }

    if($type==0)     $info = defaultInfo($uip,$tPort,$port);
    elseif($type==1) $info = channelInfo($uip,$tPort,$port,$cID);

    $index = show($dir."/teamspeak", array("name" => $name,
                                           "os" => $os,
                                           "uptime" => time_convert($uptime),
                                           "user" => $user,
                                           "t_name" => _ts_name,
                                           "t_os" => _ts_os,
                                           "uchannels" => $chan,
                                           "info" => $info,
                                           "t_uptime" => _ts_uptime,
                                           "t_channels" => _ts_channels,
                                           "t_user" => _ts_user,
                                           "head" => _ts_head,
                                           "users_head" => _ts_users_head,
                                           "player" => _ts_player,
                                           "channel" => _ts_channel,
                                           "channel_head" => _ts_channel_head,
                                           "max" => $max,
                                           "channels" => $cAmount,
                                           "logintime" => _ts_logintime,
                                           "idletime" => _ts_idletime,
                                           "channelstats" => $channelstats,
                                           "userstats" => $userstats));
    break;
    case '3';
      $ip_port = ts3dns_server ? tsdns(settings('ts_ip')) : false;
      $host = ($ip_port != false && is_array($ip_port) ? $ip_port['ip'] : settings('ts_ip'));
      $port = ($ip_port != false && is_array($ip_port) ? $ip_port['port'] : settings('ts_port'));
      $tsstatus = new TSStatus($host, $port, settings('ts_sport'), settings('ts_customicon'), settings('ts_showchannel'));
      $tstree = $tsstatus->render(true);

      $users = 0;
      foreach($tsstatus->_userDatas AS $user)
      {
              if($user["client_type"] == 0)
              {
          $users++;
                                                                $icon = "16x16_player_off.png";
                  if($user["client_away"] == 1)                 $icon = "16x16_away.png";
                  else if($user["client_flag_talking"] == 1)    $icon = "16x16_player_on.png";
                  else if($user["client_output_hardware"] == 0) $icon = "16x16_hardware_output_muted.png";
                  else if($user["client_output_muted"] == 1)    $icon = "16x16_output_muted.png";
                  else if($user["client_input_hardware"] == 0)  $icon = "16x16_hardware_input_muted.png";
                  else if($user["client_input_muted"] == 1)     $icon = "16x16_input_muted.png";

                  $flags = array();
                  if(isset($tsstatus->_channelGroupFlags[$user['client_channel_group_id']])) $flags[] = $tsstatus->_channelGroupFlags[$user['client_channel_group_id']];
                  $serverGroups = explode(",", $user['client_servergroups']);
                  foreach ($serverGroups as $serverGroup) if(isset($tsstatus->_serverGroupFlags[$serverGroup])) $flags[] = $tsstatus->_serverGroupFlags[$serverGroup];

          $p = '<img src="../inc/images/tsicons/'.$icon.'" alt="" class="tsicon" />'.rep2($user['client_nickname']).'&nbsp;'.$tsstatus->renderFlags($flags);

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $userstats .= show($dir."/userstats", array("player" => $p,
                                                      "channel" => rep2($tsstatus->getChannelInfos($user['cid'])),
                                                      "misc1" => '',
                                                      "class" => $class,
                                                      "misc2" => '',
                                                      "misc3" => time_convert(time()-$user['client_lastconnected']),
                                                      "misc4" => time_convert($user['client_idle_time'],true)));
          }
      }

      $index = show($dir."/teamspeak", array("name" => $tsstatus->_serverDatas['virtualserver_name'],
                                             "os" => $tsstatus->_serverDatas['virtualserver_platform'],
                                             "uptime" => time_convert($tsstatus->_serverDatas['virtualserver_uptime']),
                                             "user" => $users,
                                             "t_name" => _ts_name,
                                             "t_os" => _ts_os,
                                             "uchannels" => $tstree,
                                             "info" => bbcode($tsstatus->welcome(intval($_GET['cID']),$_GET['cName']),false,false,true),
                                             "t_uptime" => _ts_uptime,
                                             "t_channels" => _ts_channels,
                                             "t_user" => _ts_user,
                                             "head" => _ts_head,
                                             "users_head" => _ts_users_head,
                                             "player" => _ts_player,
                                             "channel" => _ts_channel,
                                             "channel_head" => _ts_channel_head,
                                             "max" => $max,
                                             "channels" => $tsstatus->_serverDatas['virtualserver_channelsonline'],
                                             "logintime" => _ts_logintime,
                                             "idletime" => _ts_idletime,
                                             "channelstats" => $channelstats,
                                             "userstats" => $userstats));
    break;
    endswitch;
        if($config_cache['use_cache'])
            $cache->set('teamspeak_'.$language, $index, config('cache_teamspeak'));
    } else {
        $index = $cache->get('teamspeak_'.$language);
    }
  } else {
    $index = error(_fopen,1);
  }

## INDEX OUTPUT ##
$title = $pagetitle." - ".$where;
page($index, $title, $where);