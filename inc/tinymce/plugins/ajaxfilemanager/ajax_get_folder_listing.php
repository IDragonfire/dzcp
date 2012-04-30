<?php
    ## OUTPUT BUFFER START ##
	include("../../../buffer.php");
	## INCLUDES ##
	include(basePath."/inc/config.php");
	include(basePath."/inc/bbcode.php");
	## SETTINGS ##
	if(!(permission("downloads") || permission("news") || permission('artikel'))) {
		exit;
	}
    
	require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "inc" . DIRECTORY_SEPARATOR . "config.php");
	echo '{';
	$count = 1;
	foreach(getFolderListing(CONFIG_SYS_ROOT_PATH) as $k=>$v)
	{
		

		echo (($count > 1)?', ':''). "'" . $v . "':'" . $k . "'"; 
		$count++;
	}
	echo "}";
?>
