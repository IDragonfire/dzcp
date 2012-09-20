<?php
/**
* Prüft online ob DZCP aktuell ist.
*
* @return array
*/
function show_dzcp_version()
{
	$dzcp_version_info = 'onmouseover="DZCP.showInfo(\'<tr><td colspan=2 align=center padding=3 class=infoTop>DZCP Versions Checker</td></tr><tr><td>'._dzcp_vcheck.'</td></tr>\')" onmouseout="DZCP.hideInfo()"';
    $return = array();
	if(dzcp_version_checker)
	{
	    if(cache('dzcp_version', dzcp_version_checker_refresh, 'c'))
	    {
    		if($dzcp_online_v = fileExists("http://www.dzcp.de/version.txt"))
    		{
    			if($dzcp_online_v <= _version)
    			{
    				$return['version'] = '<b>'._akt_version.': <a href="" [info]><span class="fontGreen">'._version.'</span></a></b>';
    				$return['version'] = show($return['version'],array('info' => $dzcp_version_info));
    				$return['old'] = "";
    			}
    			else
    			{
    				$return['version'] = '<a href="http://www.dzcp.de/" target="_blank" title="external Link: www.dzcp.de"><b>'._akt_version.':</b> <span class="fontRed">'._version.'</span> / <span class="fontGreen">'.$dzcp_online_v.'</span></a>';
    				$return['old'] = "_old";
    			}
    			
    			cache('dzcp_version', $return, 'w');
    		}
    		else
    		{
    			$return['version'] = '<b>'._akt_version.': <a href="" [info]><font color="#FFFF00">'._version.'</font></a></b>';
    			$return['version'] = show($return['version'],array('info' => $dzcp_version_info));
    			$return['old'] = "";
    		}
	    }
	    else
	        $return = cache('dzcp_version', null, 'r');
    }
	else
	{
		//check disabled
		$return['version'] = '<b><font color="#999999">'._akt_version.': '._version.'</font></b>';
		$return['old'] = "";
	}

    return $return;
}

/**
* Funktion für den DZCP.de Newsticker
*
* @return string
*/
function show_dzcp_news()
{
	if(dzcp_newsticker)
	{
	    if(cache('dzcp_newsticker', dzcp_newsticker_refresh, 'c'))
	    {
    		if($dzcp_news = fileExists("http://www.dzcp.de/dzcp_news.php"))
    		{
    			if(!empty($dzcp_news))
    			{
    				$javascript = '<script language="javascript" type="text/javascript">DZCP.addEvent(window, \'load\', function() { DZCP.initTicker(\'dzcpticker\', \'h\', 30); });</script>';
    				$news = '<tr><td><div style="padding:3px"><b>DZCP News:</b><br/><div id="dzcpticker">'.$dzcp_news.'</div></div></td></tr>'; unset($dzcp_news);
    				cache('dzcp_newsticker', $news.$javascript, 'w');
    				return $news.$javascript;
    			}
    		}
	    }
	    else
	        return cache('dzcp_newsticker', null, 'r');
	}
  
    return '';
}
?>