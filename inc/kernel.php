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

  $fp = @fsockopen($url_p['host'], $port, $errno, $errstr, 5);
  if(!$fp) return false;

  @fputs($fp, 'GET '.$url_p['path'].' HTTP/1.1'.chr(10));
  @fputs($fp, 'HOST: '.$url_p['host'].chr(10));
  @fputs($fp, 'Connection: close'.chr(10).chr(10));

  $response = @fgets($fp, 1024);
  $content = @fread($fp,1024);
  $ex = explode("\n",$content);
  $content = $ex[count($ex)-1];
  @fclose ($fp);

  if(preg_match("#404#",$response)) return false;
  else return trim($content);
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
function VisitorIP()
{
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		$TheIp=$_SERVER['HTTP_X_FORWARDED_FOR'];
	else $TheIp=$_SERVER['REMOTE_ADDR'];

	return trim($TheIp);
}
?>