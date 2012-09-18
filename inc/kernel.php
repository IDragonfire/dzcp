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
    {
        wire_ipcheck('mod_add('.$modid.')');
        return true;
    }
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
    
    if(!existsMod($author, $modid)) 
        return false;
    
    if(db('UPDATE ' . $db['mods'] . ' SET  version = "' . mysql_real_escape_string ($newversion) . '", installed = NOW() ' .
       ' WHERE author = "' . mysql_real_escape_string ($author) . '" AND modid = "' . mysql_real_escape_string ($modid) . '"'))
    {
        wire_ipcheck('mod_upd('.$modid.')');
        return true;
    }
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
    
    if(!existsMod($author, $modid)) 
        return false;
    
    if(db('DELETE FROM ' . $db['mods'] .
       ' WHERE author = "' . mysql_real_escape_string ($author) . '" AND modid = "' . mysql_real_escape_string ($modid) . '"'))
    {
        wire_ipcheck('mod_del('.$modid.')');
        return true;
    }
    else
        return false;
}

function existsMod($author, $modid) 
{
    global $db;
    return (db('SELECT id FROM ' . $db['mods'] . ' WHERE author = "'.mysql_real_escape_string($author).'" AND modid = "'.mysql_real_escape_string($modid).'" LIMIT 1',true));
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
function is_php($version='5.0.0')
{ return (floatval(phpversion()) >= $version); }

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
 * Prüft wie PHP ausgeführt wird
 *
 * @return string
 **/
function php_sapi_type()
{
    $sapi_type = php_sapi_name();
    $sapi_types = array("apache" => 'Apache HTTP Server', "apache2filter" => 'Apache 2: Filter',
            "apache2handler" => 'Apache 2: Handler', "cgi" => 'CGI', "cgi-fcgi" => 'Fast-CGI',
            "cli" => 'CLI', "isapi" => 'ISAPI', "nsapi" => 'NSAPI');
    return(empty($sapi_types[substr($sapi_type, 0, 3)]) ? substr($sapi_type, 0, 3) : $sapi_types[substr($sapi_type, 0, 3)]);
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
 * Gibt die IP des Besuchers / Users zurück.
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
	
	$TheIp_X = explode('.',$TheIp);
	if(count($TheIp_X) == 4 && $TheIp_X[0]<=255 && $TheIp_X[1]<=255 && $TheIp_X[2]<=255 && $TheIp_X[3]<=255 && preg_match("!^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$!",$TheIp))
	    return trim($TheIp);

	return '0.0.0.0';
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
 * Datenbank Connect
 * Todo: Code überarbeiten, Update auf MySQLi
 *
 * @return resource
 **/
if(!isset($db)) //tinymce fix
    require_once(basePath."/inc/config.php");

if($db['host'] != '' && $db['user'] != '' && $db['db'] != '')
{
    if(!$msql = @mysql_connect($db['host'], $db['user'], $db['pass']))
    {
        echo "<b>Fehler beim Zugriff auf die Datenbank!<p>";
        print_db_error(false);
    }

    if(!@mysql_select_db($db['db'],$msql))
    {
        echo "<b>Die angegebene Datenbank <i>".$db['db']."</i> existiert nicht!<p>";
        print_db_error(false);
    }
}

function print_db_error($query=false)
{
    global $prefix;
    die('<b>MySQL-Query failed:</b><br /><br /><ul>'.
            '<li><b>ErrorNo</b> = '.str_replace($prefix,'',mysql_errno()).
            '<li><b>Error</b>   = '.str_replace($prefix,'',mysql_error()).
            ($query ? '<li><b>Query</b>   = '.str_replace($prefix,'',$query).'</ul>' : ''));
}

/**
 * Datenbank Query senden
 * Todo: Code überarbeiten, Update auf MySQLi + SQL-Inception Schutz
 *
 * @return resource/array/int
 **/
function db($query,$rows=false,$fetch=false)
{
    if(!$qry = mysql_query($query))
        print_db_error($query);

    if($fetch && $rows)
        return mysql_fetch_array($qry);
    else if($fetch && !$rows)
        return mysql_fetch_assoc($qry);
    else if(!$fetch && $rows)
        return mysql_num_rows($qry);
    else
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
    $cnt = db("SELECT COUNT(".$what.") AS num FROM ".$count." ".$where,false,true);
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
    $cnt = db("SELECT SUM(".$what.") AS num FROM ".$db.$where,false,true);
    return ((int)$cnt['num']);
}

/**
 * Funktion um Settings aus der Datenbank auslesen
 * Todo: Code überarbeiten, Update auf MySQLi
 *
 * @return mixed/array
 **/
function settings($what)
{
    global $db;
    if(is_array($what))
    {
        $sql='';
        foreach($what as $qy)
        { $sql .= $qy.", "; }
        $sql = substr($sql, 0, -2);
        return db("SELECT ".$sql." FROM `".$db['settings']."`",false,true);
    }
    else
    {
        $get = db("SELECT ".$what." FROM `".$db['settings']."`",false,true);
        return $get[$what];
    }

}

/**
 * Funktion um Config aus der Datenbank auslesen
 * Todo: Code überarbeiten, Update auf MySQLi
 *
 * @return mixed/array
 **/
function config($what)
{
    global $db;
    if(is_array($what))
    {
        $sql='';
        foreach($what as $qy)
        { $sql .= $qy.", "; }
        $sql = substr($sql, 0, -2);
        return db("SELECT ".$sql." FROM `".$db['config']."`",false,true);
    }
    else
    {
        $get = db("SELECT ".$what." FROM `".$db['config']."`",false,true);
        return $get[$what];
    }
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

/**
 * Generiert Passwörter
 *
 * @return String
 */
function mkpwd($passwordLength=8)
{
    global $passwordComponents;
    
    $password = "";
    shuffle($passwordComponents);
    $componentsCount = count($passwordComponents);

    for ($pos = 0; $pos < $passwordLength; $pos++)
    {
        $componentIndex = ($pos % $componentsCount);
        $componentLength = strlen($passwordComponents[$componentIndex]);
        $random = rand(0, $componentLength-1);
        $password .= $passwordComponents[$componentIndex]{ $random };
    }

	return $password;
}

/**
 * Funktion zum schreiben der Adminlogs
 */
function wire_ipcheck($what='')
{
    global $db,$userip;
    db("INSERT INTO ".$db['ipcheck']." SET `ip` = '".visitorIp()."',`what` = '".$what."',`time` = '".time()."'");
}

/**
 * Checkt versch. Dinge anhand der Hostmaske eines Users
 * 
* @return boolean
 */
function ipcheck($what,$time = "")
{
    global $db;
    $get = db("SELECT time,what FROM ".$db['ipcheck']." WHERE what = '".$what."' AND ip = '".visitorIp()."' ORDER BY time DESC",false,true);
    if(preg_match("#vid#", $get['what'])) 
        return true;
    else 
    {
        if($get['time']+$time<time())
            db("DELETE FROM ".$db['ipcheck']." WHERE what = '".$what."' AND ip = '".visitorIp()."' AND time+'".$time."'<'".time()."'");

        if($get['time']+$time>time()) 
            return true;
        else 
            return false;
    }
}

/**
 * Wandelt einen Boolean zu einem Boolean-String um.
 *
 * @return String
 */
function Bool_to_StringConverter($bool)
{
    return ($bool ? "+#bool#+" : "-#bool#-");
}

/**
 * Wandelt einen Boolean-String zu Boolean um.
 *
 * @return boolean
 */
function String_to_boolConverter($bool_coded)
{
    return ($bool_coded == "+#bool#+" ? true : false);
}

/**
 * Wandelt einen Array-String zu einem Array um.
 *
 * @return array
 */
function string_to_array($str,$counter=1)
{
    $arr=array(); $temparr=array();
    $temparr=explode("|$counter|",$str);
    foreach( $temparr as $key => $value )
    {
        $t1=explode("=$counter>",$value);
        $kk=$t1[0];
        	
        if($t1[1] == "+#bool#+" or $t1[1] == "-#bool#-")
            $vv=String_to_boolConverter($t1[1]);
        else
            $vv=utf8_decode($t1[1]);

        if(isset($t1[2]) && $t1[2]=="~Y~")
            $arr[$kk]=string_to_array($vv,($counter+1));
        else
            $arr[$kk]=$vv;
    }

    return $arr;
}

/**
 * Wandelt einen Array zu einem Array-String um.
 *
 * @return String
 */
function array_to_string($arr,$counter=1)
{
    $str="";
    foreach( $arr as $key => $value)
    {
        if(is_array($value))
            $str.= $key."=$counter>".array_to_string($value,($counter+1))."=".$counter.">~Y~|".$counter."|";
        else
        {
            if(is_bool($value))
                $value = Bool_to_StringConverter($value);
            
            $str.=$key."=$counter>".utf8_encode($value)."|$counter|";
        }
    }
    	
    return rtrim($str,"|$counter|");
}

/**
 * Cache Verwaltung des CMS
 * Mode: (r)Read,(w)Wire,(c)Check
 *
 * @return mixed/boolean
 */
function cache($file='', $data='', $mode='w')
{
    $file_hash = md5($file);
    switch(strtolower($mode)) 
    {
        case 'w':
            if(!is_debug)
            {
                $is_array = 'n';
                if(is_array($data))
                { $is_array = 'y'; $data = array_to_string($data); }
                $cache_data = (function_exists('gzcompress') && function_exists('gzuncompress') && cache_gzip_compress ? gzcompress(base64_encode($data)) : base64_encode($data));
                $cache_data = "<?php /* +".$is_array."+ *** ".$cache_data." */ ?>";
                @file_put_contents(basePath.'/inc/_cache/'.$file_hash.'.cache.php', $cache_data);
                return (file_exists(basePath.'/inc/_cache/'.$file_hash.'.cache.php') && is_file(basePath.'/inc/_cache/'.$file_hash.'.cache.php') ? true : false);
            }
        break;
        case 'r':
            if(file_exists(basePath.'/inc/_cache/'.$file_hash.'.cache.php') && is_file(basePath.'/inc/_cache/'.$file_hash.'.cache.php'))
            {
                $stream = @file_get_contents(basePath.'/inc/_cache/'.$file_hash.'.cache.php');
                if(!$stream) return false;
                $stream = @explode('<?php /* +',$stream); $stream = @explode('+ *** ',$stream[1]);
                $is_array = ($stream[0] == 'y' ? true : false); $stream = @explode(' */ ?>',$stream[1]); $stream = @$stream[0];
                if(!$stream || empty($stream)) return false;
                
                if(function_exists('gzcompress') && function_exists('gzuncompress') && cache_gzip_compress)
                   if(!$stream = @gzuncompress($stream)) return false;
                    
                if(!$stream = @base64_decode($stream)) return false;
                if(empty($stream)) return false;
                return ($is_array ? string_to_array($stream) : $stream);
            }
            else
               return false;
        break;
        case 'c':
            if(!file_exists(basePath.'/inc/_cache/'.$file_hash.'.cache.php') or !is_file(basePath.'/inc/_cache/'.$file_hash.'.cache.php') || is_debug)
                return true;
            
            return (time()-@filemtime(basePath.'/inc/_cache/'.$file_hash.'.cache.php') > $data);
        break;
    }
}

/**
 * Wird verwendet um die Ladezeit der Seite zu errechnen.
 *
 * @return float
 */
function generatetime()
{
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}

/**
 * Erkennt Spider und Crawler um sie von der Besucherstatistik auszuschliessen.
 *
 * @return boolean
 */
function isSpider()
{
    $uagent = $_SERVER['HTTP_USER_AGENT'];
    $ex = explode("\n", file_get_contents(basePath.'/inc/_spiders.txt'));
    for($i=0;$i<=count($ex)-1;$i++)
    {
        if(stristr($uagent, trim($ex[$i])))
            return true;
    }
    
    return false;
}
?>