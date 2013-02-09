<?php
$size   = getimagesize($_GET['img']);
$breite = $size[0];
$hoehe  = $size[1];

$neueBreite = empty($_GET['width']) ? 100 : intval($_GET['width']);
$neueHoehe  = intval($hoehe * $neueBreite / $breite);

$neuesBild = imagecreatetruecolor($neueBreite, $neueHoehe);
if ($size[2] == 1) {
    header("Content-Type: image/gif");
    $altesBild = imagecreatefromgif($_GET['img']);
    imagecopyresampled($neuesBild, $altesBild, 0, 0, 0, 0, $neueBreite, $neueHoehe, $breite, $hoehe);
    imagegif($neuesBild);
} elseif ($size[2] == 2) {
    header("Content-Type: image/jpeg");
    $altesBild = imagecreatefromjpeg($_GET['img']);
    imagecopyresampled($neuesBild, $altesBild, 0, 0, 0, 0, $neueBreite, $neueHoehe, $breite, $hoehe);
    imagejpeg($neuesBild, '', 100);
} elseif ($size[2] == 3) {
    header("Content-Type: image/png");
    $altesBild = imagecreatefrompng($_GET['img']);
    imagecopyresampled($neuesBild, $altesBild, 0, 0, 0, 0, $neueBreite, $neueHoehe, $breite, $hoehe);
    imagepng($neuesBild);
}

@imagedestroy($altesBild);
@imagedestroy($neuesBild);
?>