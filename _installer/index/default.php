<?php
if (!defined('IN_DZCP'))
    exit();

$index = show("welcome",array("lizenz" => file_get_contents(basePath.'/_installer/system/lizenz.txt'))); //Willkommen & AGB
?>