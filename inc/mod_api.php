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
    
    if(self::existsMod($author, $modid)) 
        return false;
        
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
* 
* @return boolean
*/
function updateMod($author, $modid, $newversion)
{
    global $db;
        
    if(!self::existsMod($author, $modid))
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
* 
* @return boolean
*/
function deleteMod($author, $modid)
{
    global $db;
        
    if(!existsMod($author, $modid))
        return false;
        
    if(db('DELETE FROM ' . $db['mods'] . ' WHERE author = "' . mysql_real_escape_string ($author) . '" AND modid = "' . mysql_real_escape_string ($modid) . '"'))
    {
        wire_ipcheck('mod_del('.$modid.')');
        return true;
    }
    else
        return false;
}
    
/**
* Entfernt einen Mod aus der Uebersicht
* 
* @return boolean
*/
function existsMod($author, $modid)
{
    global $db;
    return (db('SELECT id FROM ' . $db['mods'] . ' WHERE author = "'.mysql_real_escape_string($author).'" AND modid = "'.mysql_real_escape_string($modid).'" LIMIT 1',true));
}

/**
 *  Neue Languages & Neue Funktionen einbinden *Last
 */
if($l = get_files(basePath.'/inc/additional-languages/'.$language.'/',false,true,array('php')))
{
    foreach($l AS $languages)
    { include(basePath.'/inc/additional-languages/'.$language.'/'.$languages); }
}

if($f = get_files(basePath.'/inc/additional-functions/',false,true,array('php')))
{
    foreach($f AS $func)
    { include(basePath.'/inc/additional-functions/'.$func); }
}
?>