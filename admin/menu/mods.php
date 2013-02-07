<?php
/////////// ADMINNAVI \\\\\\\\\
// Typ:       rootmenu
// Rechte:    $chkMe == 4
///////////////////////////////
	
/*
* @see inc/kernel.php
* Mod hinzufuegen:
* addMod('myName', 'myModID', 'versionNumber', 'serverURL', 'downloadURL')
* updateMod('myName', 'myModID', 'newVersionNumber')
* deleteMod('myName', 'myModID') 
*/
    
if(_adminMenu != 'true') exit;
$where = $where.': '._config_mods;
if($chkMe != 4) {
    $show = error(_error_wrong_permissions, 1);
} else {
    $i = 0;
    $qry = db ( 'SELECT * FROM ' . $db['mods'] . ' ORDER BY author, modid');
    $rows = '';
    $text = '';
    if (_rows ( $qry ) > 0) {
        while ( $get = _fetch ( $qry ) ) {
            $modData = array (                   'author' => $get[ 'author' ] , 
                   'modid' => $get[ 'modid' ],
                   'version' => $get[ 'version' ] , 
                   'server_version' => '?',
                   'installed' => $get[ 'installed' ],
                   'updated' => $get[ 'updated' ],
                   'dl_link' => $get[ 'downloadurl'] );
            $text .= implode(PHP_EOL, $modData);
            $text .= PHP_EOL . $get['serverurl'] . PHP_EOL . '###';
            $modData['style'] = ($i % 2 == 0) ? 'contentMainFirst' : 'contentMainSecond';
            $modData['number'] = $i + 1;
            $rows .= show ( $dir . '/form_mods_row', $modData );
            $i ++;
        }
    }
    $replace = array( 'rows' => $rows, 'support' => $text);
    $replace['_mod_head'] = _mod_head;
    $replace['_mod_name'] = _mod_name;
    $replace['_mod_author'] = _mod_author;
    $replace['_mod_yourVersion'] = _mod_yourVersion;
    $replace['_mod_link'] = _mod_link;
    $replace['_mod_installed'] = _mod_installed;
    $replace['_mod_updated'] = _mod_updated;
    $replace['_mod_copyAndPasteInfo'] = _mod_copyAndPasteInfo;
    $replace['_mod_clickMe'] = _mod_clickMe;
    
	$show = show ( $dir . '/form_mods', $replace);
}    
?>