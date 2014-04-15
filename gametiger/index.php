<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");

## INCLUDES ##
include(basePath."/inc/debugger.php");
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");

## SETTINGS ##
$where = _site_gametiger;
$title = $pagetitle." - ".$where."";
$dir = "gametiger";

## SECTIONS ##
if(settings('gametiger') == "lazy") $gametiger_game = "all";
elseif(settings('gametiger') == "bf1942") $gametiger_game = "bf";
else $gametiger_game = settings('gametiger');

switch ($action):
default:
  $player = "";
  $fp=@fopen('http://www.gametiger.com/search?player='.$_POST['search'].'&game='.$gametiger_game.'','r');
  while($line = @fread($fp,4096))
  {
    $player .= $line;
  }

  $player = preg_replace("#<html>.*<!-- cstiger results -->\n#miUs","",$player);
  $player = preg_replace("#</table>\n<!-- /cstiger results -->.*</html>#miUs","",$player);

  $line = explode("\n",$player);

  unset($player);

  for($i=0;$i<count($line);$i++)
  {
    if(preg_match('#<td>([^>]+)</td><td><a href=/search\?address=([^>]+)>#i',$line[$i],$match))
    {
      $player[] = $match[1];
      $server[] = $match[2];
    }
  }

  if(is_array($player) && count($player)>0)
  {
    for($i =0;$i<count($player);$i++)
    {
      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
      $gametiger .= show( _gametiger_player_results, array("players" => $player[$i],
                                                           "servers" => $server[$i],
                                                           "class" => $class));

    }
  } else {
    $gametiger = show(_gt_not_found, array());
  }

  $gsearch = show($dir."/footer", array("gametiger" => $gametiger,
                                        "id" => count($player),
                                        "found" => _gt_found));

  $search = show($dir."/psearch", array("show" => $gsearch,
                                        "psearch" => _gt_psearchhead,
                                        "nick" => _nick,
                                        "value" => _button_value_search,
                                        "serverip" => _gt_sip));

  $index = show($dir."/gametiger", array("show" => $search,
                                         "gametigerhead" => _gametiger,
                                         "search" => _gt_search,
                                         "player" => _gt_player,
                                         "addip" => _gt_addip,
                                         "server" => _gt_server,
                                         "map" => _gt_maps));
break;
case 'server':
  $server = "";
  $fp=@fopen('http://www.gametiger.com/search?server='.$_POST['search'].'&game='.$gametiger_game.'','r');
  while($line = @fread($fp,4096))
  {
    $server .= $line;
  }

  $server = preg_replace("#<html>.*<!-- cstiger results -->\n#miUs","",$server);
  $server = preg_replace("#</table>\n<!-- /cstiger results -->.*</html>#miUs","",$server);

  $line = explode("\n",$server);

  unset($server);

  for($i=0;$i<count($line);$i++)
  {
    if(preg_match('#<td><a href=/search\?address=([^>]+)>([^>]+)</a></td>#i',$line[$i],$match))
    {
      $serverip[] = $match[1];
      $servername[] = $match[2];
    }
    if(preg_match('#<td align=right>([^>]+)</td><td align=right>([^>]+)</td><td align=right><font color=[^>]+>([^>]+)</td>#i',$line[$i],$match))
    {
      $map[] = $match[2];
      $players[] = $match[3];
    }
  }

  if(is_array($serverip) && count($serverip)>0)
  {
    for($i =0;$i<count($serverip);$i++)
    {
      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
      $gametiger .= show( _gametiger_server_results, array("servername" => $servername[$i],
                                                           "serverip" => $serverip[$i],
                                                           "map" => $map[$i],
                                                           "class" => $class,
                                                           "players" => $players[$i]));
    }
  } else {
    $gametiger = show(_gt_not_found, array());
  }

  $gsearch = show($dir."/footer", array("gametiger" => $gametiger,
                                        "found" => _gt_found,
                                        "id" => count($servername)));

  $search = show($dir."/ssearch", array("show" => $gsearch,
                                        "addname" => _gt_addname,
                                        "value" => _button_value_search,
                                        "ssearchhead" => _gt_ssearchhead,
                                        "servername" => _gt_server_ip,
                                        "players" => _gt_player,
                                        "map" => _gt_map));

  $index = show($dir."/gametiger", array("show" => $search,
                                         "gametigerhead" => _gametiger,
                                         "search" => _gt_search,
                                         "player" => _gt_player,
                                         "server" => _gt_server,
                                         "map" => _gt_maps));
break;
case 'map':
  $map = "";

 $fp=@fopen('http://www.gametiger.com/search?map='.$_POST['search'].'&game='.$gametiger_game.'','r');
  while($line = @fread($fp,4096))
  {
    $map .= $line;
  }

  $map = preg_replace("#<html>.*<!-- cstiger results -->\n#miUs","",$map);
  $map = preg_replace("#</table>\n<!-- /cstiger results -->.*</html>#miUs","",$map);

  $line = explode("\n",$map);

  unset($map);

  for($i=0;$i<count($line);$i++)
  {
    if(preg_match('#<td align=right>([^>]+)</td><td align=right>([^>]+)</td><td align=right><font color=[^>]+>([^>]+)</td>#i',$line[$i],$match))
    {
      $aktmap[] = $match[2];
      $player[] = $match[3];
    }

    if(preg_match('#<td><a href=/search\?address=([^>]+)>([^>]+)</a></td>#i',$line[$i],$match))
    {
       $sip[] = $match[1];
       $name[] = $match[2];
    }
  }

  if(is_array($aktmap) && count($aktmap)>0)
  {
    for($i =0;$i<count($aktmap);$i++)
    {
      $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
      $gametiger .= show( _gametiger_map_results, array("name" => $name[$i],
                                                        "ip" => $sip[$i],
                                                        "class" => $class,
                                                        "aktmap" => $aktmap[$i],
                                                        "player" => $player[$i]));
    }
  } else {
    $gametiger = show(_gt_not_found, array());
  }

  $gsearch = show($dir."/footer", array("gametiger" => $gametiger,
                                        "found" => _gt_found,
                                        "id" => count($aktmap)));

  $search = show($dir."/msearch", array("show" => $gsearch,
                                        "msearchhead" => _gt_msearchhead,
                                        "addname" => _gt_addname,
                                        "player" => _gt_player,
                                        "servername" => _gt_server_ip,
                                        "value" => _button_value_search,
                                        "map" => _gt_map));

  $index = show($dir."/gametiger", array("show" => $search,
                                         "gametigerhead" => _gametiger,
                                         "search" => _gt_search,
                                         "player" => _gt_player,
                                         "server" => _gt_server,
                                         "map" => _gt_maps));
break;
endswitch;

## INDEX OUTPUT ##
page($index, $title, $where);

## OUTPUT BUFFER END ##
gz_output();