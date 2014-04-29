<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

ob_start();
ob_implicit_flush(false);
define('basePath', dirname(dirname(__FILE__).'../'));

function getmicrotime() {
    list($usec,$sec) = explode(" ",microtime());
    return((float)$usec+(float)$sec);
}

$time_start=getmicrotime();

function gz_output($output='') {
    $gzip_compress_level = (!defined('buffer_gzip_compress_level') ? 4 : buffer_gzip_compress_level);
    if(function_exists('ini_set'))
        ini_set('zlib.output_compression_level', $gzip_compress_level);

    if(buffer_show_licence_bar) {
        $licence_bar = '<div style="width:100%;text-align:center;padding:7px 0;z-index:9999"> <table style="width:100%;margin:auto" cellspacing="0"> <tr> <td style="vertical-align:middle;text-align:center;" nowrap="nowrap">Powered by <a style="font-weight:normal" href="http://www.dzcp.de" target="_blank" title="deV!L`z Clanportal">DZCP - deV!L`z&nbsp;Clanportal V'._version.'</a></td></tr> </table> </div>';

        if(!file_exists(basePath.'/_codeking.licence'))
            $output = str_ireplace('</body>',$licence_bar."\r\n</body>",$output);
    }

    ob_end_clean();
    ob_start('ob_gzhandler');
             $output .= "\r\n<!--This CMS is powered by deV!L`z Clanportal V"._version." - www.dzcp.de-->";
        echo $output."\r\n"."<!-- [GZIP => Level ".$gzip_compress_level."] ".sprintf("%01.2f",((strlen(gzcompress($output,$gzip_compress_level)))/1024))." kBytes | uncompressed: ".sprintf("%01.2f",((strlen($output))/1024 ))." kBytes -->";
    ob_end_flush();
    exit();
}