<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

ob_start();
    define('basePath', dirname(__FILE__));
    $thumbgen = true;
    include(basePath."/inc/debugger.php");
    include(basePath."/inc/config.php");

    if(!isset($_GET['img']) || empty($_GET['img']) || !extension_loaded('gd'))
        die('"gd" extension not loaded or "img" is empty');

    if(!file_exists(basePath.'/'.$_GET['img']))
        die('"'.basePath.'/'.$_GET['img'].'" file is not exists');

    $size       = getimagesize(basePath.'/'.$_GET['img']);
    $file_exp   = explode('.',$_GET['img']);
    $breite     = $size[0];
    $hoehe      = $size[1];

    $neueBreite = empty($_GET['width']) ? 100 : intval($_GET['width']);
    $neueHoehe = empty($_GET['height']) ? intval($hoehe*$neueBreite/$breite) : intval($_GET['height']);
    $file_cache = basePath.'/'.$file_exp[0].'_minimize_'.$neueBreite.'x'.$neueHoehe;
    $picture_build = false;

    switch($size[2]) {
        case 1: ## GIF ##
            header("Content-Type: image/gif");
            $file_cache = $file_cache.'.gif';
            if(!thumbgen_cache || !file_exists($file_cache) || time() - filemtime($file_cache) > thumbgen_cache_time) {
                $altesBild = imagecreatefromgif(basePath.'/'.$_GET['img']);
                $neuesBild = imagecreatetruecolor($neueBreite,$neueHoehe);
                imagecopyresampled($neuesBild, $altesBild,0,0,0,0, $neueBreite, $neueHoehe, $breite, $hoehe);
                thumbgen_cache ? imagegif($neuesBild,$file_cache) : imagegif($neuesBild);
                $picture_build = true;
            }
        break;
        default:
        case 2: ## JPEG ##
            header("Content-Type: image/jpeg");
            $file_cache = $file_cache.'.jpg';
            if(!thumbgen_cache || !file_exists($file_cache) || time() - @filemtime($file_cache) > thumbgen_cache_time) {
                $altesBild = imagecreatefromjpeg(basePath.'/'.$_GET['img']);
                $neuesBild = imagecreatetruecolor($neueBreite,$neueHoehe);
                imagecopyresampled($neuesBild, $altesBild,0,0,0,0, $neueBreite, $neueHoehe, $breite, $hoehe);
                thumbgen_cache ? imagejpeg($neuesBild, $file_cache, 100) : imagejpeg($neuesBild, null, 100);
                $picture_build = true;
            }
        break;
        case 3: ## PNG ##
            header("Content-Type: image/png");
            $file_cache = $file_cache.'.png';
            if(!thumbgen_cache || !file_exists($file_cache) || time() - @filemtime($file_cache) > thumbgen_cache_time) {
                header("Content-Type: image/png");
                $altesBild = imagecreatefrompng(basePath.'/'.$_GET['img']);
                $neuesBild = imagecreatetruecolor($neueBreite,$neueHoehe);
                imagecopyresampled($neuesBild, $altesBild,0,0,0,0, $neueBreite, $neueHoehe, $breite, $hoehe);
                thumbgen_cache ? imagepng($neuesBild,$file_cache) : imagepng($neuesBild);
                $picture_build = true;
            }
        break;
    }

    if($picture_build && is_resource($altesBild))
        imagedestroy($altesBild);

    if($picture_build && is_resource($neuesBild))
        imagedestroy($neuesBild);

    if(thumbgen_cache && file_exists($file_cache))
        echo file_get_contents($file_cache);

ob_end_flush();