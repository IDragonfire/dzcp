<?php
/**
* Fügt eine Mod zur Ueberischt ins Adminmenu hinzu.
* @return boolean
* 
* Hilfe: 'true' wenn Mod erfolgreich installiert, 'false' wenn nicht erfolgreich, z.B. Mod bereits eingetragen
*/
function addMod($author, $modid, $installedversion, $serverurl, $downloadurl) 
{
    global $db;
    if(existsMod($author, $modid)) return false;
    
    if(db('INSERT INTO ' . $db['mods'] . ' (`author`, `modid`, `version`, `serverurl`, `downloadurl`, `installed`) ' .
              'VALUES ("' . mysql_real_escape_string ($author) . '", "' .
                          mysql_real_escape_string ($modid) . '", "' .
                          mysql_real_escape_string ($installedversion) . '", "' .
                          mysql_real_escape_string ($serverurl) . '", "' . 
                          mysql_real_escape_string ($downloadurl) . '",NOW())' ))                          
        return true;
    else
        return false;
}

/**
* Aktualisiert eine Mod Version. Um server, bzw. Download URL zu aktulaisieren muss der Mod erst entfernt werden (@see deleteMod)
* @return boolean
*/
function updateMod($author, $modid, $newversion) 
{
    global $db;
    if(!existsMod($author, $modid)) return false;
    
    if(db('UPDATE ' . $db['mods'] . ' SET  version = "' . mysql_real_escape_string ($newversion) . '", installed = NOW() ' .
       ' WHERE author = "' . mysql_real_escape_string ($author) . '" AND modid = "' . mysql_real_escape_string ($modid) . '"'))
        return true;
    else
        return false;
}

/**
* Entfernt einen Mod aus der Uebersicht
* @return boolean
*/
function deleteMod($author, $modid) 
{
    global $db;
    if(!existsMod($author, $modid)) return false;
    
    if(db('DELETE FROM ' . $db['mods'] .
       ' WHERE author = "' . mysql_real_escape_string ($author) . '" AND modid = "' . mysql_real_escape_string ($modid) . '"'))
        return true;
    else
        return false;
}

function existsMod($author, $modid) 
{
    global $db;
    return (_rows(db('SELECT id FROM ' . $db['mods'] . ' WHERE author = "' . 
                       mysql_real_escape_string ($author) . '" AND modid = "' .
                       mysql_real_escape_string ($modid) . '" LIMIT 1')) == 1);
}

/**
* Eine Liste der Dateien oder Verzeichnisse zusammenstellen, die sich im angegebenen Ordner befinden.
*
* @return array
*/
function get_files($dir=null,$only_dir=false,$only_files=false,$file_ext=array())
{
	$files = array();
	if($handle = @opendir($dir))
	{
		if($only_dir) ## Ordner ##
		{
			while(false !== ($file = readdir($handle)))
			{
				if($file != '.' && $file != '..' && !is_file($dir.'/'.$file))
					$files[] = $file;
			}
		}
		else if($only_files) ## Dateien ##
		{
			while(false !== ($file = readdir($handle)))
			{
				if($file != '.' && $file != '..' && is_file($dir.'/'.$file))
				{
					if(count($file_ext) == 0)
						$files[] = $file;
					else
					{
						## Extension Filter ##
						$exp_string = explode(".", $file);
						if(in_array(strtolower($exp_string[1]), $file_ext))
							$files[] = $file;
					}
				}
			}
		}
		else ## Ordner & Dateien ##
		{
			while(false !== ($file = readdir($handle)))
			{
				if($file != '.' && $file != '..' && is_file($dir.'/'.$file))
				{
					if(count($file_ext) == 0)
						$files[] = $file;
					else
					{
						## Extension Filter ##
						$exp_string = explode(".", $file);
						if(in_array(strtolower($exp_string[1]), $file_ext))
							$files[] = $file;
					}
				}
				else
				{
					if($file != '.' && $file != '..')
						$files[] = $file;
				}
			}
		}
			
		if(!count($files))
			return false;
			
		@closedir($handle);
		return $files;
	}
	else
		return false;
}

/**
* Erkennen welche PHP Version ausgeführt wird.
*
* @return boolean
*/
function is_php($version)
{ return (floatval(phpversion()) >= $version); }

/**
* Erkennen ob eine PHP 5 Version ausgeführt wird.
*
* @return boolean
*/
function is_php_5()
{ return is_php('5.0.0'); }

//PHPInfo in array lesen
/**
 * PHPInfo in ein Array lesen und zurückgeben
 * 
 * @return array
 **/
function parsePHPInfo()
{
    ob_start();
    phpinfo();
        $s = ob_get_contents();
    ob_end_clean();

   $s = strip_tags($s,'<h2><th><td>');
   $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/',"<info>\\1</info>",$s);
   $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/',"<info>\\1</info>",$s);
   $vTmp = preg_split('/(<h2[^>]*>[^<]+<\/h2>)/',$s,-1,PREG_SPLIT_DELIM_CAPTURE);
   
   $vModules = array();
   for ($i=1;$i<count($vTmp);$i++)
   {
        if(preg_match('/<h2[^>]*>([^<]+)<\/h2>/',$vTmp[$i],$vMat))
        {
            $vName = trim($vMat[1]);
            $vTmp2 = explode("\n",$vTmp[$i+1]);
            foreach ($vTmp2 AS $vOne)
            {
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

/**
 * Funktion um eine Datei im Web auf Existenz zu prfen
 * 
 * @return mixed
 **/
function fileExists($url)
{
    $url_p = @parse_url($url);
    $host = $url_p['host'];
    $port = isset($url_p['port']) ? $url_p['port'] : 80;
    unset($url_p);

    if(!ping_port($host,$port,2))
        return false;

    if(!$content = @file_get_contents($url))
		return false;

    return trim($content);
}

/**
 * Pingt einen Server Port
 * 
 * @return boolean
 **/
	## Ports eines Server anpingen ##
	function ping_port($ip='0.0.0.0',$port=0000,$timeout=2)
	{
		if(function_exists('fsockopen'))
		{
			$fp = fsockopen($ip, $port, $errno, $errstr, $timeout);
			if($fp)
			{
				@fclose($fp);
				return true;
			}
		}
		
		return false;
	}

/**
 * Datenbank Connect
 * Todo: Code überarbeiten, Update auf MySQLi
 * 
 * @return resource
 **/
if($db['host'] != '' && $db['user'] != '' && $db['pass'] != '' && $db['db'] != '')
{
	if(!$msql = mysql_connect($db['host'],$db['user'],$db['pass'])) die("<b>Fehler beim Zugriff auf die Datenbank!");
	if(!mysql_select_db($db['db'],$msql)) die("<b>Die angegebene Datenbank <i>".$db['db']."</i> existiert nicht!");
}

/**
 * Datenbank Query senden
 * Todo: Code überarbeiten, Update auf MySQLi + SQL-Inception Schutz
 * 
 * @return resource
 **/
function db($db)
{
  global $prefix;
  if(!$qry = mysql_query($db)) die('<b>MySQL-Query failed:</b><br /><br /><ul>'.
                                   '<li><b>ErrorNo</b> = '.str_replace($prefix,'',mysql_errno()).
                                   '<li><b>Error</b>   = '.str_replace($prefix,'',mysql_error()).
                                   '<li><b>Query</b>   = '.str_replace($prefix,'',$db).'</ul>');
  return $qry;
}

/**
 * Informationen über die MySQL-Datenbank abrufen
 * Todo: Code überarbeiten, Update auf MySQLi
 * 
 * @return resource
 **/
function dbinfo()
{
    $info = array(); $sum = 0; $rows = 0; $entrys = 0;
    $qry = db("Show table status");
    while($data = _fetch($qry))
    {
        $allRows = $data["Rows"];
        $dataLength  = $data["Data_length"];
        $indexLength = $data["Index_length"];

        $tableSum = $dataLength + $indexLength;

        $sum += $tableSum;
        $rows += $allRows;
        $entrys ++;
    }
    
    $info["entrys"] = $entrys;
    $info["rows"] = $rows;
    $info["size"] = @round($sum/1048576,2);

    return $info;
}

/**
 * Liefert die Anzahl der Zeilen im Ergebnis
 * Todo: Code überarbeiten, Update auf MySQLi
 * 
 * @return integer
 **/
function _rows($rows)
{
    return mysql_num_rows($rows);
}

/**
 * Liefert einen Datensatz als assoziatives Array
 * Todo: Code überarbeiten, Update auf MySQLi
 * 
 * @return array
 **/
function _fetch($fetch)
{
    return mysql_fetch_assoc($fetch);
}

/**
 * Funktion um diverse Dinge aus Tabellen auszaehlen zu lassen
 * Todo: Code überarbeiten, Update auf MySQLi
 * 
 * @return integer
 **/
function cnt($count, $where = "", $what = "id")
{
    $cnt = db("SELECT COUNT(".$what.") AS num FROM ".$count." ".$where);
    $cnt = _fetch($cnt);
    return ((int)$cnt['num']);
}

/**
 * Funktion um diverse Dinge aus Tabellen zusammenzaehlen zu lassen
 * Todo: Code überarbeiten, Update auf MySQLi
 * 
 * @return integer
 **/
function sum($db, $where = "", $what)
{
  $cnt = db("SELECT SUM(".$what.") AS num FROM ".$db.$where);
  $cnt = _fetch($cnt);
  return ((int)$cnt['num']);
}

/**
 * Gibt die IP des Besuchers / Users zurück.
 * Proxy Server Fix.
 *
 * @return String
 */
function visitorIp()
{
	$TheIp=$_SERVER['REMOTE_ADDR'];
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
		## IP auf Gültigkeit prüfen ##
		$TheIp_XF=$_SERVER['HTTP_X_FORWARDED_FOR'];
		$TheIp_X = explode('.',$TheIp_XF);
		if(count($TheIp_X) == 4 && $TheIp_X[0]<=255 && $TheIp_X[1]<=255 && $TheIp_X[2]<=255 && $TheIp_X[3]<=255 && preg_match("!^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$!",$TheIp_XF))
			$TheIp = $TheIp_XF;
	}

	return trim($TheIp);
}

/**
* Sucht nach Platzhaltern und ersetzt diese.
*
* @return string
*/
function show($tpl="", $array=array())
{
	global $tmpdir;
	
	if(!empty($tpl) && $tpl != null)
	{
	    $template = basePath."/inc/_templates_/".$tmpdir."/".$tpl;
	    $array['dir'] = '../inc/_templates_/'.$tmpdir;
	  
	    if(file_exists($template.".html"))
			$tpl = file_get_contents($template.".html");
	    
	    if(count($array) >= 1)
	    {
		    foreach($array as $value => $code)
		    {
				$tpl = str_replace('['.$value.']', $code, $tpl);
		    }
	    }
	}
	
	return $tpl;
}

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
		if(!$dzcp_online_v = fileExists("http://www.dzcp.de/version.txt"))
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
		}
		else
		{
			$return['version'] = '<b>'._akt_version.': <a href="" [info]><font color="#FFFF00">'._version.'</font></a></b>';
			$return['version'] = show($return['version'],array('info' => $dzcp_version_info));
			$return['old'] = "";
		}
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
		if($dzcp_news = fileExists("http://www.dzcp.de/dzcp_news.php"))
		{
			if(!empty($dzcp_news))
			{
				$javascript = '<script language="javascript" type="text/javascript">DZCP.addEvent(window, \'load\', function() { DZCP.initTicker(\'dzcpticker\', \'h\', 30); });</script>';
				$news = '<tr><td><div style="padding:3px"><b>DZCP News:</b><br/><div id="dzcpticker">'.$dzcp_news.'</div></div></td></tr>'; unset($dzcp_news);
				return $news.$javascript;
			}
		}
	}
  
   //disabled or empty
    return '';
}
?>