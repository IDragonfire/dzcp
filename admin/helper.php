<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

/**
 * Pruft online ob DZCP aktuell ist.
 * @return array
 */
function show_dzcp_version() {
    global $cache,$config_cache;
    $dzcp_version_info = 'onmouseover="DZCP.showInfo(\'<tr><td colspan=2 align=center padding=3 class=infoTop>DZCP Versions Checker</td></tr><tr><td>'._dzcp_vcheck.'</td></tr>\')" onmouseout="DZCP.hideInfo()"';
    $return = array();
    if(dzcp_version_checker || allow_url_fopen_support()) {
        if(!$config_cache['use_cache'] || !$cache->isExisting('dzcp_version')) {
            switch (_edition) {
                case 'dev': $url = 'bugfree'; break;
                case 'society': $url = 'society'; break;
                default: $url = 'final'; break;
            }
            if($dzcp_online_v = fileExists("https://raw.githubusercontent.com/DZCP-Community/dzcp/".$url."/dzcp_version.xml"))
                if($config_cache['use_cache'] && $dzcp_online_v && !empty($dzcp_online_v))
                    $cache->set('dzcp_version', $dzcp_online_v, dzcp_version_checker_refresh);
        }
        else
            $dzcp_online_v = $cache->get('dzcp_version');

        if($dzcp_online_v && !empty($dzcp_online_v) && strpos($dzcp_online_v, 'not found') === false) {
            $xml = simplexml_load_string($dzcp_online_v, 'SimpleXMLElement', LIBXML_NOCDATA); $_build = _build;
            $xml = SteamAPI::objectToArray($xml);
            if($xml['build'] > _build) $_build = '<font color="#FF0000">'._build.'</font> => <font color="#00FF00">'.$xml['build'].'</font>';

            if($xml['version'] <= _version) {
                $return['version'] = '<b>'._akt_version.': <a href="" [info]><span class="fontGreen">'._version.'</span></a> / Release: '._release.' / Build: '.$_build.'</b>';
                $return['version'] = show($return['version'],array('info' => $dzcp_version_info));
                $return['version_img'] = '<img src="../inc/images/admin/version.gif" align="absmiddle" width="111" height="14" />';
            } else {
                $return['version'] = '<a href="http://www.dzcp.de/" target="_blank" title="external Link: www.dzcp.de"><b>'._akt_version.':</b> <span class="fontRed">'._version.'</span> / Update Version: <span class="fontGreen">'.$xml['version'].'</span></a> / Release: <span class="fontGreen">'.$xml['release'].'</span> / Build: <span class="fontGreen">'.$xml['build'].'</span>';
                $return['version_img'] = '<img src="../inc/images/admin/version_old.gif" align="absmiddle" width="111" height="14" />';
            }
        } else {
            $return['version'] = '<b>'._akt_version.': <a href="" [info]><font color="#FFFF00">'._version.'</font></a> / Release: '._release.' / Build: '._build.'</b>';
            $return['version'] = show($return['version'],array('info' => $dzcp_version_info));
            $return['version_img'] = '<img src="../inc/images/admin/version.gif" align="absmiddle" width="111" height="14" />';
        }
    } else {
        //check disabled
        $return['version'] = '<b><font color="#999999">'._akt_version.': '._version.'</font> / Release: '._release.' / Build: '._build.'</b>';
        $return['version_img'] = '<img src="../inc/images/admin/version.gif" align="absmiddle" width="111" height="14" />';
    }

    return $return;
}

//PHPInfo in ein Array einlesen
function parsePHPInfo() {
    ob_start();
    phpinfo();
    $s = ob_get_contents();
    ob_end_clean();

    $s = strip_tags($s,'<h2><th><td>');
    $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/',"<info>\\1</info>",$s);
    $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/',"<info>\\1</info>",$s);
    $vTmp = preg_split('/(<h2[^>]*>[^<]+<\/h2>)/',$s,-1,PREG_SPLIT_DELIM_CAPTURE);
    $vModules = array();
    for ($i=1;$i<count($vTmp);$i++) {
        if(preg_match('/<h2[^>]*>([^<]+)<\/h2>/',$vTmp[$i],$vMat)) {
            $vName = trim($vMat[1]);
            $vTmp2 = explode("\n",$vTmp[$i+1]);
            foreach ($vTmp2 AS $vOne) {
                $vPat = '<info>([^<]+)<\/info>';
                $vPat3 = "/$vPat\s*$vPat\s*$vPat/";
                $vPat2 = "/$vPat\s*$vPat/";

                if(preg_match($vPat3,$vOne,$vMat))
                    $vModules[$vName][trim($vMat[1])] = array(trim($vMat[2]),trim($vMat[3]));
                else if(preg_match($vPat2,$vOne,$vMat))
                    $vModules[$vName][trim($vMat[1])] = trim($vMat[2]);
            }
        }
    }

    return $vModules;
}

function php_sapi_type() {
    $sapi_type = php_sapi_name();
    $sapi_types = array("apache" => 'Apache HTTP Server', "apache2filter" => 'Apache 2: Filter',
            "apache2handler" => 'Apache 2: Handler', "cgi" => 'CGI', "cgi-fcgi" => 'Fast-CGI', "cli" => 'CLI', "isapi" => 'ISAPI', "nsapi" => 'NSAPI');
    return(empty($sapi_types[substr($sapi_type, 0, 3)]) ? substr($sapi_type, 0, 3) : $sapi_types[substr($sapi_type, 0, 3)]);
}

/**
* Gibt eine Liste der Live Games aus
* @return string/options
*/
function listgames($game = '') {
    $protocols_array = GameQ::getGames(); $games = '';
    $block = array('teamspeak3','gamespy','gamespy2','gamespy3','source');
    foreach ($protocols_array AS $gameq => $info) {
        if(in_array($gameq,$block)) { continue; }
        $selected = (!empty($game) && $game != false && $game == $gameq ? 'selected="selected" ' : '');
        $games .= '<option '.$selected.'value="'.$gameq.'">'.htmlentities($info['name']).'</option>';
    }

    return $games;
}