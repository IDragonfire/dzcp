<?php
//-> Teamspeak statusscript
function teamspeak()
{
    global $db, $ajaxJob, $language;

    header('Content-Type: text/html; charset=iso-8859-1');
    if(!$ajaxJob)
    {
        return '<div id="navTeamspeakServer">
        <div style="width:100%;padding:10px 0;text-align:center"><img src="../inc/images/ajax_loading.gif" alt="" /></div>
        <script language="javascript" type="text/javascript">DZCP.initTeamspeakServer();</script></div>';
    } 
    else
    {
        $settings = settings(array('ts_ip','ts_sport','ts_port','ts_version'));
        if(!empty($settings['ts_ip']) && !empty($settings['ts_sport']) && !empty($settings['ts_port'])) 
        {
            if(cache('nav_teamspeak_'.$language, config('cache_teamspeak'), 'c'))
            {
                $teamspeak = teamspeakViewer($settings);
                cache('nav_teamspeak_'.$language, $teamspeak, 'w');
                return $teamspeak;
            } 
            else
                return cache('nav_teamspeak_'.$language, null, 'r');
        } 
        else 
            return '<br /><center>'._no_ts.'</center><br />';
    }
}
?>