<?php
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
 * Funktion um eine Datei im Web auf Existenz zu prüfen
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
    
    unset($host,$port);

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
		    unset($ip,$port,$errno,$errstr,$timeout);
			@fclose($fp);
			return true;
		}
		
		unset($ip,$port,$errno,$errstr,$timeout);
	}
	
	unset($ip,$port,$timeout);
	return false;
}

/**
 * Gibt die IP des Besuchers / Users zurück
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
 * Filtert Platzhalter
 *
 * @return string
 */
function pholderreplace($pholder)
{
    $search = array('@<script[^>]*?>.*?</script>@si',
            '@<style[^>]*?>.*?</style>@siU',
            '@<[\/\!]*?[^<>]*?>@si',
            '@<![\s\S]*?--[ \t\n\r]*>@');
    //Replace
    $pholder = preg_replace("#<script(.*?)</script>#is","",$pholder);
    $pholder = preg_replace("#<style(.*?)</style>#is","",$pholder);
    $pholder = preg_replace($search, '', $pholder);
    $pholder = str_replace(" ","",$pholder);
    $pholder = preg_replace("#[0-9]#is","",$pholder);
    $pholder = preg_replace("#&(.*?);#s","",$pholder);
    $pholder = str_replace("\r","",$pholder);
    $pholder = str_replace("\n","",$pholder);
    $pholder = preg_replace("#\](.*?)\[#is","][",$pholder);
    $pholder = str_replace("][","^",$pholder);
    $pholder = preg_replace("#^(.*?)\[#s","",$pholder);
    $pholder = preg_replace("#\](.*?)$#s","",$pholder);
    $pholder = str_replace("[","",$pholder);
    return str_replace("]","",$pholder);
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
	    $template = $_SESSION['installer'] ? basePath."/_installer/html/".$tpl : basePath."/inc/_templates_/".$tmpdir."/".$tpl;
	    $array['dir'] = $_SESSION['installer'] ? "html": '../inc/_templates_/'.$tmpdir;;
	  
	    if(file_exists($template.".html"))
			$tpl = file_get_contents($template.".html");
	    
	    //put placeholders in array
	    $pholder = explode("^",pholderreplace($tpl));
	    for($i=0;$i<=count($pholder)-1;$i++)
	    {
	        if(array_key_exists($pholder[$i],$array))
	            continue;
	        
	        if(!strstr($pholder[$i], 'lang_'))
	            continue;
	        
	        $array[$pholder[$i]] = constant(substr($pholder[$i], 4));
	    }
	    
	    unset($pholder);

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
 **/
if(!$_SESSION['installer'] || $_SESSION['db_install']) //For Installer
{
    if(!isset($db)) //tinymce fix
        require_once(basePath."/inc/config.php");
    
    if(!empty($db['host']) && !empty($db['user']) && !empty($db['pass']) && !empty($db['db']))
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
    else
    {
        echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /></head><body><b>';
        if(empty($db['host']))
            echo "Das MySQL-Hostname fehlt in der Configuration!<p>";
        
        if(empty($db['user']))
            echo "Der MySQL-Username fehlt in der Configuration!<p>";
        
        if(empty($db['pass']))
            echo "Das MySQL-Passwort fehlt in der Configuration!<p>";
        
        if(empty($db['db']))
            echo "Der MySQL-Datenbankname fehlt in der Configuration!<p>";
        
        die("Bitte überprüfe deine mysql.php!</b></body></html>");    
    }
}

/**
 * Gibt Datenbank Fehler aus und stoppt die Ausführung des CMS 
 **/
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
function db($query,$rows=false,$fetch=false,$clear_output=false)
{
    if(!$qry = mysql_query($query))
        print_db_error($query);

    if($fetch && $rows)
        return mysql_fetch_array($qry);
    else if($fetch && !$rows)
        return mysql_fetch_assoc($qry);
    else if(!$fetch && $rows)
        return mysql_num_rows($qry);
    else if(!$clear_output)
        return $qry;
    else
        unset($qry); //clear mem
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
 * Generiert Passwörter
 *
 * @return String
 */
function mkpwd($passwordLength=8,$specialcars=true)
{
    global $passwordComponents;
    $componentsCount = count($passwordComponents);
    
    if(!$specialcars && $componentsCount == 4) //Keine Sonderzeichen
    {
        unset($passwordComponents[3]);
        $componentsCount = count($passwordComponents);
    }
    
    shuffle($passwordComponents); $password = '';
    for ($pos = 0; $pos < $passwordLength; $pos++)
    {
        $componentIndex = ($pos % $componentsCount);
        $componentLength = strlen($passwordComponents[$componentIndex]);
        $random = rand(0, $componentLength-1);
        $password .= $passwordComponents[$componentIndex]{ $random };
    }
    
    unset($random,$componentLength,$componentIndex);
	return $password;
}

/**
 * Funktion zum schreiben der Adminlogs
 */
function wire_ipcheck($what='')
{
    global $db;
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
            if(!is_debug || cache_in_debug)
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
            if(is_debug && !cache_in_debug)
                return true;
            
            if(!file_exists(basePath.'/inc/_cache/'.$file_hash.'.cache.php') or !is_file(basePath.'/inc/_cache/'.$file_hash.'.cache.php'))
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

/**
 * Funktion um Sonderzeichen zu konvertieren
 *
 * @return string
 */
function spChars($txt)
{
    $search = array("Ä", "Ö", "Ü", "ä", "ö", "ü", "ß", "€");
    $replace = array("&Auml;", "&Ouml;", "&Uuml;", "&auml;", "&ouml;", "&uuml;", "&szlig;", "&euro;");
    return str_replace($search, $replace, $txt);
}
?>