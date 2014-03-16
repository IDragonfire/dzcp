<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

ob_start();

// Start session if no headers were sent
  if(!headers_sent())
  {
    @session_start();
  # Patch by David Vieira-Kurz of majorsecurity.de
    #@session_regenerate_id();
    if(!isset($_SESSION['PHPSESSID']) || !isset($_COOKIE['PHPSESSID']))
    {
      @session_destroy();
      @session_start();
     # @session_regenerate_id();
      $_SESSION['PHPSESSID'] = true;
      $_COOKIE['PHPSESSID']  = true;
    }
  }

## COLORS
    $backgroundColor  = '#444444';
    $textColor        = '#000000';
    $noiseColor       = '#AAAAAA';
    $lineColor        = '#555555';
## /COLORS

  if(function_exists('gd_info'))
  {
    if(isset($_GET['num']) && !empty($_GET['num']))
    {
      $num = $_GET['num'];
      $x = 100; $y = 30;
      $space = 10;
    } else {
      $num = 2;
      $x = 40; $y = 23;
      $space = 6;
    }

    $sizeMin = 13;
    $sizeMax = 19;
    $rectMin = -20;
    $rectMax = 20;

    function hex2rgb($color,$type)
    {
      if($type == 'r')     $r = hexdec(substr($color, 1, 2));
      elseif($type == 'g') $r = hexdec(substr($color, 3, 2));
      elseif($type == 'b') $r = hexdec(substr($color, 5, 2));

      return $r;
    }

    $im = imagecreate($x, $y);

    $backgroundColor = imagecolorallocate($im, hex2rgb($backgroundColor,'r')   , hex2rgb($backgroundColor,'g')   , hex2rgb($backgroundColor,'b'));
                       imagecolortransparent ($im, $backgroundColor);
      $noiseColor      = imagecolorallocate($im, hex2rgb($noiseColor,'r'), hex2rgb($noiseColor,'g'), hex2rgb($noiseColor,'b'));
      $lineColor       = imagecolorallocate($im, hex2rgb($lineColor,'r') , hex2rgb($lineColor,'g') , hex2rgb($lineColor,'b'));

// Pixel einfügen
    if(function_exists('imagesetpixel'))
    {
      $noise = $x * $y / 10;
        for($i = 0; $i < $noise; $i++)
            imagesetpixel($im, mt_rand(0, $x), mt_rand(0, $y), $noiseColor);
    }

// Linien zeichnen
    if(function_exists('imagesetpixel')) imagesetthickness($im, 1);
    if(function_exists('imageline'))
    {
        $anz = mt_rand(4, 9);
      for($i = 1; $i <= $anz; $i++)
          imageline($im, mt_rand(0, $x), mt_rand(0, $y), $x - mt_rand(0, 0), mt_rand(0, $y), $lineColor);
    }
// Zahlencode einfuegen
    $code = '';
    $z = array("1","2","3","4","5","6","7","8","9","0","A","C","D","E","F","G","H","J","K","M","N","P","R","S","T","U","V","W","X","Y","Z");
    for($f=0; $f<$num; $f++)
    {
      $spamcode = $z[rand(0,30)];
      $w = (16 * $f) + $space;

      if(function_exists('imagettftext'))
        imagettftext($im, rand($sizeMin,$sizeMax), rand($rectMin,$rectMax), $w, 20,
          imagecolorallocate ($im,
                              hex2rgb($textColor,'r'),
                              hex2rgb($textColor,'g'),
                              hex2rgb($textColor,'b')), "./inc/images/fonts/verdana.ttf", $spamcode);
      $code .= $spamcode;
    }
    if(!function_exists('imagettftext'))
    {
      for($i=0;$i<=strlen($code);$i++) $strcode .= $code[$i].' ';
      $text_color = imagecolorallocate ($im, hex2rgb($textColor,'r'), hex2rgb($textColor,'g'), hex2rgb($textColor,'b'));
      imagestring($im, 12, $x/10, $y/4, $strcode, $text_color);
    }

//Bild ausgeben & bildcache zerstoeren
    imagegif($im);
    imagedestroy($im);
//Code in Session abspeichern
    $_SESSION["sec_$_GET[secure]"] = $code;

    header ("Content-type: image/gif");
  } else echo '<a href="http://www.libgd.org" target="_blank">GDLib</a> is not installed!';
## OUTPUT BUFFER END ##
ob_end_flush();