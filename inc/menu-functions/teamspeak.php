<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 * Menu: Teamspeak
 */
function teamspeak($js = 0) {
    global $db, $language, $cache, $config_cache;

    header('Content-Type: text/html; charset=iso-8859-1');
    if(!fsockopen_support()) return _fopen;

    if(empty($js))
    {
        $teamspeak = '
          <div id="navTeamspeakServer">
            <div style="width:100%;padding:10px 0;text-align:center"><img src="../inc/images/ajax_loading.gif" alt="" /></div>
            <script language="javascript" type="text/javascript">
              <!--
                DZCP.initTeamspeakServer();
              //-->
            </script>
          </div>';

    } else {
        $ts_ip = settings('ts_ip');
        $ts_sport = settings('ts_sport');
        $ts_port = settings('ts_port');

        if(!empty($ts_ip) && !empty($ts_port) && !empty($ts_port)) {
            if(!$config_cache['use_cache'] || !$cache->isExisting('nav_teamspeak_'.$language)) {
                $teamspeak = teamspeakViewer();
                if($config_cache['use_cache'])
                    $cache->set('nav_teamspeak_'.$language, $teamspeak, config('cache_teamspeak'));
            } else {
                $teamspeak = $cache->get('nav_teamspeak_'.$language);
            }
        } else {
            $teamspeak = '<br /><center>'._no_ts.'</center><br />';
        }
    }

    return $teamspeak;
}
