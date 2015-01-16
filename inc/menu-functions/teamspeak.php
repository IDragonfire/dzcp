<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 * Menu: Teamspeak
 */
function teamspeak($js = 0) {
    global $db,$cache;
    header('Content-Type: text/html; charset=iso-8859-1');

    if(!fsockopen_support())
        return '<center style="margin:2px 0">'.error(_fopen,'0',false).'</center>';

    if(!$js) {
        return "<div id=\"navTeamspeakServer\">
        <div style=\"width:100%;padding:10px 0;text-align:center\"><img src=\"../inc/images/ajax_loading.gif\" alt=\"\" /></div>
        <script language=\"javascript\" type=\"text/javascript\">DZCP.initDynLoader('navTeamspeakServer','teamspeak','');</script></div>";
    } else {
        $qry = db("SELECT * FROM `".$db['ts']."` WHERE `show_navi` = 1 LIMIT 1;");
        if(!_rows($qry)) return '<br /><center>'._no_ts.'</center><br />';
            $get = _fetch($qry);

        if(!empty($get['host_ip_dns']) && !empty($get['server_port']) && !empty($get['query_port'])) {
            $ip_port = TS3Renderer::tsdns($get['host_ip_dns']);
            $host = ($ip_port != false && is_array($ip_port) ? $ip_port['ip'] : $get['host_ip_dns']);
            $port = ($ip_port != false && is_array($ip_port) ? $ip_port['port'] : $get['server_port']);

            if(!ping_port($host,$get['query_port'],1)) {
                return '<br /><center>'._no_connect_to_ts.'</center><br />';
            }

            $cache_hash = md5($host.':'.$port);
            if(!$cache->isExisting('teamspeak_'.$cache_hash)) {
                GameQ::addServers(array(array('id' => 'ts3' ,'type' => 'teamspeak3', 'host' => $host.':'.$port, 'query_port' => $get['query_port'])));
                GameQ::setOption('timeout', 6);
                $results = GameQ::requestData();
                if(!empty($results) && $results) {
                    $cache->set('teamspeak_'.$cache_hash, $results, config('cache_teamspeak'));
                }
            }
            else
                $results = $cache->get('teamspeak_'.$cache_hash);

            TS3Renderer::init();
            TS3Renderer::set_data($results,$get);
            TS3Renderer::setConfig('OnlyChannelsWithUsers',$get['showchannel']);

            unset($results,$get);
            return show("menu/teamspeak", array("hostname" => '',"channels" => TS3Renderer::render()));
        }
        else
            return '<br /><center>'._no_ts.'</center><br />';
    }
}