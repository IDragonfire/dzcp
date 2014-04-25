<?php
/**
 * Prüft online ob DZCP aktuell ist.
 *
 * @return array
 */
function show_dzcp_version() {
    global $cache;
    $dzcp_version_info = 'onmouseover="DZCP.showInfo(\'<tr><td colspan=2 align=center padding=3 class=infoTop>DZCP Versions Checker</td></tr><tr><td>'._dzcp_vcheck.'</td></tr>\')" onmouseout="DZCP.hideInfo()"';
    $return = array();
    if(dzcp_version_checker || !fsockopen_support()) {
        if(!$cache->isExisting('dzcp_version')) {
            if($dzcp_online_v = file_get_contents("https://raw.githubusercontent.com/DZCP-Community/dzcp/final/dzcp_version.xml"))
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