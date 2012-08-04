<?php
	if(!isset($_GET['img']) || !extension_loaded('gd'))
		die();
		
	$filename = $_GET['img'];
	list($breite, $hoehe, $type) = getimagesize($filename);
	$neueBreite = empty($_GET['width']) ? 100 : intval($_GET['width']); 
	$neueHoehe = intval($hoehe*$neueBreite/$breite); 
	$neuesBild = imagecreatetruecolor($neueBreite, $neueHoehe);
	
	switch($type) 
	{
		case 1: ## GIF ##
			header("Content-Type: image/gif");
			$altesBild = imagecreatefromgif($filename); 
			imagecopyresampled($neuesBild, $altesBild,0,0,0,0, $neueBreite, $neueHoehe, $breite, $hoehe); 
			imagegif($neuesBild);
		break;
		case 2: ## JPEG ##
			header("Content-Type: image/jpeg");
			$altesBild = imagecreatefromjpeg($filename); 
			imagecopyresampled($neuesBild, $altesBild,0,0,0,0, $neueBreite, $neueHoehe, $breite, $hoehe); 
			imagejpeg($neuesBild, null, 100);
		break;
		case 3: ## PNG ##
			header("Content-Type: image/png");
			$altesBild = imagecreatefrompng($filename); 
			imagecopyresampled($neuesBild, $altesBild,0,0,0,0, $neueBreite, $neueHoehe, $breite, $hoehe); 
			imagepng($neuesBild);
		break;
	}

	if(is_resource($altesBild))
		imagedestroy($altesBild);
		
	if(is_resource($neuesBild))
		imagedestroy($neuesBild);
		
	exit();
?>