<?php
//-> Teamspeak statusscript
function teamspeak($js = 0)
{
  global $db, $settings, $language, $config;
  if(!fsockopen_support()) return _fopen;

  header('Content-Type: text/html; charset=iso-8859-1');
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
      </div>
    ';

  } else {
    if(!empty($settings['ts_ip']) && !empty($settings['ts_sport']) && !empty($settings['ts_port'])) {
      if(time() - @filemtime(basePath.'/__cache/'.md5('nav_teamspeak_'.$language).'.cache') > $config['cache_teamspeak']) {
        $teamspeak = teamspeakViewer($settings);

        $fp = @fopen(basePath.'/__cache/'.md5('nav_teamspeak_'.$language).'.cache', 'w'); @fwrite($fp, $teamspeak);
        @fclose($fp);
      }
      else
		$teamspeak = @file_get_contents(basePath.'/__cache/'.md5('nav_teamspeak_'.$language).'.cache');
    } else $teamspeak = '<br /><center>'._no_ts.'</center><br />';
  }

  return $teamspeak;
}