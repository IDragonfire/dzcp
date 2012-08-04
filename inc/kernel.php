<?php
/**
* Fügt eine Mod zur Ueberischt ins Adminmenu hinzu.
* @return true wenn Mod erfolgreich installiert, false wenn nicht erfolgreich, z.B. Mod bereits eingetragen
*/
function addMod($author, $modid, $installedversion, $serverurl, $downloadurl) {
    global $db;
    if(existsMod($author, $modid)) {
        return false;
    }
    db('INSERT INTO ' . $db['mods'] . ' (`author`, `modid`, `version`, `serverurl`, `downloadurl`, `installed`) ' .
              'VALUES ("' . mysql_real_escape_string ($author) . '", "' .
                          mysql_real_escape_string ($modid) . '", "' .
                          mysql_real_escape_string ($installedversion) . '", "' .
                          mysql_real_escape_string ($serverurl) . '", "' . 
                          mysql_real_escape_string ($downloadurl) . '",NOW())' );                   
    return true;
}

/**
* Aktualisiert eine Mod Version. Um server, bzw. download url zu aktulaisieren muss der Mod erst
* entfernt werden (@see deleteMod)
* @return true | false (z.B. Mod nicht installiert)
*/
function updateMod($author, $modid, $newversion) {
    global $db;
    if(!existsMod($author, $modid)) {
        return false;
    }
    db('UPDATE ' . $db['mods'] . ' SET  version = "' . mysql_real_escape_string ($newversion) . '", installed = NOW() ' .
       ' WHERE author = "' . mysql_real_escape_string ($author) . '" AND modid = "' . mysql_real_escape_string ($modid) . '"');
    return true;
}

/**
* Entfernt einen Mod aus der Uebersicht
* @return true | false
*/
function deleteMod($author, $modid) {
    global $db;
    if(!existsMod($author, $modid)) {
        return false;
    }
    db('DELETE FROM ' . $db['mods'] .
       ' WHERE author = "' . mysql_real_escape_string ($author) . '" AND modid = "' . mysql_real_escape_string ($modid) . '"');
    return true;
}

function existsMod($author, $modid) {
    global $db;
    $result = _fetch(db('SELECT 1 FROM ' . $db['mods'] . ' WHERE author = "' . 
                       mysql_real_escape_string ($author) . '" AND modid = "' .
                       mysql_real_escape_string ($modid) . '" LIMIT 1'));
    return ($result) ? true : false;
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
?>