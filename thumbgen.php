<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

ob_start();
    define('basePath', dirname(__FILE__));

    if(!isset($_GET['img']) || empty($_GET['img']) || !extension_loaded('gd'))
        die('"gd" extension not loaded or "img" is empty');

    if(!file_exists(basePath.'/'.$_GET['img']))
        die('"'.basePath.'/'.$_GET['img'].'" file is not exists');

    $size   = getimagesize(basePath.'/'.$_GET['img']);
    $breite = $size[0];
    $hoehe  = $size[1];

    $neueBreite = empty($_GET['width']) ? 100 : intval($_GET['width']);
    $neueHoehe = intval($hoehe*$neueBreite/$breite);

    $neuesBild = imagecreatetruecolor($neueBreite,$neueHoehe);
    switch($size[2])
    {
        case 1: ## GIF ##
            header("Content-Type: image/gif");
            $altesBild = imagecreatefromgif(basePath.'/'.$_GET['img']);
            imagecopyresampled($neuesBild, $altesBild,0,0,0,0, $neueBreite, $neueHoehe, $breite, $hoehe);
            imagegif($neuesBild);
        break;
        default:
        case 2: ## JPEG ##
            header("Content-Type: image/jpeg");
            $altesBild = imagecreatefromjpeg(basePath.'/'.$_GET['img']);
            imagecopyresampled($neuesBild, $altesBild,0,0,0,0, $neueBreite, $neueHoehe, $breite, $hoehe);
            imagejpeg($neuesBild, null, 100);
        break;
        case 3: ## PNG ##
            header("Content-Type: image/png");
            $altesBild = imagecreatefrompng(basePath.'/'.$_GET['img']);
            imagecopyresampled($neuesBild, $altesBild,0,0,0,0, $neueBreite, $neueHoehe, $breite, $hoehe);
            imagepng($neuesBild);
        break;
    }

    if(is_resource($altesBild))
        imagedestroy($altesBild);

    if(is_resource($neuesBild))
        imagedestroy($neuesBild);
ob_end_flush();