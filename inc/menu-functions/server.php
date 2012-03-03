<?php
function server($serverID = 0)
{
  global $db, $servermenu, $language;
  
  if(empty($serverID))
  {
    $qry = db("SELECT id FROM ".$db['server']." WHERE navi = '1'");
    while($get = _fetch($qry))
    {
      $st++;
      $servernavi .= '
        <div class="navGameServer" id="navGameServer_'.$get['id'].'">
          <div style="width:100%;padding:10px 0;text-align:center"><img src="../inc/images/ajax_loading.gif" alt="" /></div>
          <script language="javascript" type="text/javascript">
            <!--
              DZCP.initGameServer('.$get['id'].');
            //-->
          </script>
        </div>
      ';
    }
  } else {
    if(time() - @filemtime(basePath.'/__cache/nav_gameserver_'.intval($serverID).'_'.$language.'.html') > $c['cache_server'])
    {
      $get = _fetch(db("SELECT * FROM ".$db['server']." WHERE navi = '1' AND `id` = '".intval($serverID)."'"));
  
      if(!function_exists('server_query_'.$get['status']) && file_exists(basePath.'/inc/server_query/'.strtolower($get['status']).'.php'))
      {
        include(basePath.'/inc/server_query/'.strtolower($get['status']).'.php');
      }
  
      $server = @call_user_func('server_query_'.$get['status'], $get['ip'], $get['port'], $get['qport'], 'info');
  
      if(!$server)
      {
        $image_map = "../inc/images/maps/offline.gif";
        $server["hostname"] = "Server Offline";
        $server["mapname"] = "unknown";
        $server["players"] = "0";
        $server["maxplayers"] = "0";
      } else {
        $server["mapname"] = preg_replace("/[^A-Za-z0-9 \&\_\-]/", "_", $server["mapname"]);
        $map_low = str_replace(' ','_', strtolower($server["mapname"]));
    
        $server["gamemod"] = preg_replace("/[^A-Za-z0-9 \&\_\-]/", "_", $server["gamemod"]);
    
        $server["hostname"] = htmlentities($server["hostname"], ENT_QUOTES);
        $game_icon = file_exists(basePath.'/inc/images/gameicons/'.$get['status'].'/'.$server['gamemod'].'.gif')
                   ? '<img src="../inc/images/gameicons/'.$get['status'].'/'.$server['gamemod'].'.gif" alt="" />' : '';
                   
        $server['gamemod'] = strtolower((empty($server['gamemod']) ? $get['status'] : $server['gamemod']));
        $image_map = "../inc/images/maps/".$get['status']."/".$server['gamemod']."/".$map_low.".jpg";
  
        if(!file_exists($image_map)) $image_map = "../inc/images/maps/no_map.gif";
      }
  
      $pwd = empty($get['pwd']) ? '' : show(_server_pwd, array("pwd" => $get['pwd']));
      $players = '<a class="navServerStats" href="../server/?show='.$get['id'].'">Players</a>';
  
      if(!empty($server_name_config[$server['gamemod']])) $server_name_short = $server_name_config[$server['gamemod']][1];
      if(!empty($server_link_config[$server['gamemod']])) $server_link = $server_link_config[$server['gamemod']];
  
      $servernavi .= show("menu/server", array("host" => re(cut($server['hostname'],$servermenu)),
                                               "ip" => $get['ip'],
                                               "map" => (empty($server['mapname']) ? '-' : re($server['mapname'])),
                                               "mappic" => $image_map,
                                               "data_gamemod" => $server_name_short,
                                               "icon" => $game_icon,
                                               "pwd" => $pwd,
                                               "game" => $game,
                                               "players" => $players,
     	 					                               "launch" => strtr($server_link, array('{IP}' => $get['ip'], '{S_PORT}' => $get['port'])),
                                               "port" => $get['port'],
                                               "aktplayers" => $server['players'],
                                               "maxplayers" => $server['maxplayers']));

        $fp = @fopen(basePath.'/__cache/nav_gameserver_'.intval($serverID).'_'.$language.'.html', 'w');
              @fwrite($fp, $servernavi);
        @fclose($fp);
      } else {
        $servernavi = @file_get_contents(basePath.'/__cache/nav_gameserver_'.intval($serverID).'_'.$language.'.html');
      }
  }

  return empty($servernavi) ? '<center style="margin:2px 0">'._no_server_navi.'</center>'
                            : (empty($st) ? '<table class="navContent" cellspacing="0">'.$servernavi.'</table>' : $servernavi);
}
?>
