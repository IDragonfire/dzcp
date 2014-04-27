<?php
    ## OUTPUT BUFFER START ##
    include_once("../../../buffer.php");
    ## INCLUDES ##
    include_once(basePath."/inc/debugger.php");
    include_once(basePath."/inc/config.php");
    include_once(basePath."/inc/bbcode.php");
    ## SETTINGS ##
    if(!(permission("downloads") || permission("news") || permission('artikel'))) {
        die('Permission denied');
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
