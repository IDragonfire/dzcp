########################################
# deV!L`z Clanportal - kurz 'DZCP'
# ====================================
# www.dzcp.de
########################################

DZCP Update von 1.5.5.2 zu 1.5.5.3


================================================================	
1. Info's
================================================================
Bugfix:		Probleme bei dem anlegen von Ordnern oder hochladen von Daten im DZCP-Filemanager behoben.
Bugfix:		PHP Code-Tags [php][/php] werden jezt richtig angezeigt und können erneut bearbeitet werden.
Bugfix:		Daten im "inc/_templates_/" Ordner wurden als Template erkannt.
Bugfix:		Korrekturen an der Datenbank, fixt 2 Fehler die manchmal Probleme machen.
Bugfix:		Die Klappfunktion auf der Server-Seite ist wieder verwendbar.
Bugfix:		Das Datum für "Letzter Download" wurde nicht richtig aktualisiert. (Ein danke an HellBz)
Update:		TinyMCE wurde auf Version 3.4.6 aktualisiert.
Update:		Die GZIP-Kompression von DZCP wurde überarbeitet.
Update:		Internet Explorer 9 wird in der Infobox angezeigt.
New:		Unterstürzung für Google Video, MyVideo, Vimeo Video, XFire Video, GameTrailers, Divx, Golem Video.
New:		Klappfunktion für die Download-Kategorien verfügbar.
		

2. Update
================================================================

################################################
Folgende Dateien komplett ersetzen 
################################################

- inc/_version.php
- inc/buffer.php
- inc/tinymce/*
- inc/_templates_/dein_template/_js/wysiwyg.js
- inc/_templates_/dein_template/_js/wysiwyg_word.js

================================================================
2. Update automatisch
================================================================

================================================
 Folgende Dateien ersetzen 
================================================

- inc/bbcode.php
- inc/lang/global.php
- forum/index.php
- inc/_templates_/dein_template/_js/wysiwyg.js
- inc/_templates_/dein_template/_js/wysiwyg_word.js

 *(Achtung! Wenn ein Server Addon installiert ist, bitte die folgenden Schritte nicht dürchführen!)
 *(Für ein Update wende dich bitte an den Autor des Addons)

- server/index.php
- inc/_templates_/dein_template/server/server_show.html

*(Achtung! Wenn ein Download Addon installiert ist, bitte die folgenden Daten nicht überschreiben!)
*(Für ein Update wende dich bitte an den Autor des Addons)

- inc/lang/languages/deutsch.php
- inc/lang/languages/english.php
- downloads/index.php
- inc/_templates_/dein_template/downloads/download_kats.html

================================================
 Installer ausführen
================================================

http://www.deine_domain.de/_installer/

================================================
 Fertig...
================================================




================================================================	
3. Update manuell
================================================================

================================================
 Folgende Datei ersetzen 
================================================

- inc/_version.php
- inc/buffer.php
- inc/tinymce/*
- inc/_templates_/dein_template/_js/wysiwyg.js
- inc/_templates_/dein_template/_js/wysiwyg_word.js

*(Achtung! Wenn ein Download Addon installiert ist, bitte die folgenden Daten nicht überschreiben!)
*(Für ein Update wende dich bitte an den Autor des Addons)

- inc/_templates_/dein_template/downloads/download_kats.html
- inc/_templates_/dein_template/server/server_show.html

================================================
 öffne folgende Datei...
================================================

 inc/bbcode.php

================================================
 suche nach...
================================================

function replace($txt,$type=0,$no_vid_tag=0)
{
  $txt = str_replace("&#34;","\"",$txt);

  if($type == 1)
    $txt = preg_replace("#<img src=\"(.*?)\" mce_src=\"(.*?)\"(.*?)\>#i","<img src=\"$2\" alt=\"\">",$txt);

  $var = array("/\[url\](.*?)\[\/url\]/",
               "/\[img\](.*?)\[\/img\]/",
               "/\[url\=(http\:\/\/)?(.*?)\](.*?)\[\/url\]/");

	$repl = array("<a href=\"$1\" target=\"_blank\">$1</a>",
                "<img src=\"$1\" class=\"resizeImage\" alt=\"\" />",
                "<a href=\"http://$2\" target=\"_blank\">$3</a>");

	$txt = preg_replace($var,$repl,$txt);

  $txt = preg_replace_callback("#\<img(.*?)\>#",
                create_function(
                                 '$img',
                                 'if(preg_match("#class#i",$img[1]))
                                    return "<img".$img[1].">";
                                  else return "<img class=\"content\"".$img[1].">";
                                '
                               ),
                               $txt);

  if($no_vid_tag == 0)
  {
	  $txt = preg_replace_callback("#\[youtube\]http\:\/\/www.youtube.com\/watch\?v\=(.*)\[\/youtube\]#Uis", #
					create_function(
									 '$yt',
									 '
									  $width = 425; $height = 344;
									  return "<object width=\"".$width."\" height=\"".$height."\"><param name=\"movie\" value=\"http://www.youtube.com/v/".trim($yt[1])."&amp;hl=de&amp;fs=1\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/".trim($yt[1])."&amp;hl=de&amp;fs=1\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"".$width."\" height=\"".$height."\"></embed></object>";
									'
								   ), $txt);
  }
                               
  $txt = str_replace("\"","&#34;",$txt);
  $txt = preg_replace("#(\w){1,1}(&nbsp;)#Uis","$1 ",$txt);

  return $txt;
}

================================================
 ändere es in
================================================

//-> Replaces
function replace($txt,$type=false,$no_vid_tag=false)
{
 	global $chkMe;
 
  	$var = array("/&gt;/i","/&lt;/i","/&quot;/i","/&amp;/i");
	$repl = array(">","<","\"","&");
	$txt = preg_replace($var, $repl, $txt);
  
  	if(!$type)
    	$txt = preg_replace("#<img src=\"(.*?)\" mce_src=\"(.*?)\"(.*?)\>#i","<img src=\"$2\" alt=\"\">",$txt);

 	 $var = array("/\[url\](.*?)\[\/url\]/",
            	   "/\[img\](.*?)\[\/img\]/",
             	  "/\[url\=(http\:\/\/)?(.*?)\](.*?)\[\/url\]/",
			 	  "/\[b\](.*?)\[\/b\]/",
				  "/\[i\](.*?)\[\/i\]/",
				  "/\[u\](.*?)\[\/u\]/",
				  "/\[color\=(.*?)\](.*?)\[\/color\]/",
				  "/\[size\=(.*?)\](.*?)\[\/size\]/",
				  "/\[font\=(.*?)\](.*?)\[\/font\]/",
				  "/\[left\](.*?)\[\/left\]/",
				  "/\[center\](.*?)\[\/center\]/",
				  "/\[right\](.*?)\[\/right\]/",
				  "/\[email\](.*?)\[\/email\]/",
				  "/\[email\=(.*?)\](.*?)\[\/email\]/",
				  "/\[list\](.*?)\[\/list\]/",
				  "#\[center]#si",
				  "#\[/center]#si");

	$repl = array("<a href=\"$1\" target=\"_blank\">$1</a>",
                "<img height=\"400\" width=\"500\" src=\"$1\" class=\"resizeImage\" alt=\"\" />",
                "<a href=\"http://$2\" target=\"_blank\">$3</a>",
				"<b>\"$1\"</b>",
				"<em>\"$1\"</em>",
				"<u>\"$1\"</u>",
				"<font color=\"$1\">$2</font>",
				"<font size=\"$1\">$2</font>",
				"<font face=\"$1\">$2</font>",
				"<div align=\"left\">$1</div>",
				"<div align=\"center\">$1</div>",
				"<div align=\"right\">$1</div>",
				"<a href=\"mailto:$1\">$1</a>",
				"<a href=\"mailto:$1\">$2</a>",
				"<li>\"$1\"</li>",
				"<center>",
				"</center>");

	$txt = preg_replace($var,$repl,$txt);
	
	if(strstr($txt,'[*]'))
			{
			$matches = explode('[*]',$txt);
			
			foreach($matches as $i=>$str)
				{
				$str = trim($str);
				
				if(empty($str))
					{
					unset($matches[$i]);
					continue;
					}
				
				$matches[$i] = '<li>'.$str.'</li>';
				}
			
			$txt = implode('',$matches);
			}
	
	$txt = preg_replace("#(\/li|ul|ol type=\"a\"|ol type=\"1\")>(.*)*<(li|\/ol|\/ul){1}>#sSU",'\\1><\\3>',$txt);
	
	if(!$no_vid_tag)
	{
		$var = array("#\[googlevideo\](.*?)\[\/googlevideo\]#Uis",
					 "#\[myvideo\](.*?)\[\/myvideo\]#Uis",
					 "#\[youtube\]http\:\/\/www.youtube.com\/watch\?v\=(.*)\[\/youtube\]#Uis",
					 "#\[divx\](.*?)\[\/divx\]#Uis",
					 "#\[vimeo\]([0-9]{0,})\[\/vimeo\]#Uis",
					 "#\[xfire\](.*?)\[\/xfire\]#Uis",
					 "#\[gt\](.*?)\[\/gt\]#Uis",
					 "#\[golem\](.*?)\[\/golem\]#Uis");
	
		$repl = array("<embed id=VideoPlayback src=http://video.google.de/googleplayer.swf?docid=-$1&hl=de&fs=true style=width:425px;height:344px allowFullScreen=true allowScriptAccess=always type=application/x-shockwave-flash> </embed>",
			
		"<object wmode=\"opaque\" style=\"width: 425px; height: 344px;\" type=\"application/x-shockwave-flash\" data=\"http://www.myvideo.de/movie/$1\"> </param>
		<param name=\"wmode\" value=\"opaque\">
		<param name=\"movie\" value=\"http://www.myvideo.de/movie/$1\"><param name=\"AllowFullscreen\" value=\"true\"></object>",
						  
		"<object width=\"425\" height=\"344\" wmode=\"opaque\"><param name=\"movie\" value=\"http://www.youtube.com/v/$1&hl=de_DE&fs=1&color1=0x3a3a3a&color2=0x999999&border=0\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param>
		<param name=\"wmode\" value=\"opaque\"><embed src=\"http://www.youtube.com/v/$1&hl=de_DE&fs=1&color1=0x3a3a3a&color2=0x999999&border=0\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"425\" height=\"344\"></embed></object>",
		
						  
		"<object classid=\"clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616\" width=\"425\" height=\"344\" wmode=\"opaque\" codebase=\"http://go.divx.com/plugin/DivXBrowserPlugin.cab\">
		<param name=\"custommode\" value=\"none\" /><param name=\"autoPlay\" value=\"false\" /><param name=\"src\" value=\"$1\" />
		<embed type=\"video/divx\" src=\"$1\" custommode=\"none\" width=\"425\" height=\"344\" autoPlay=\"false\" pluginspage=\"http://go.divx.com/plugin/download/\"></embed></object>",
		
		"<object width=\"425\" height=\"344\" wmode=\"opaque\"><param name=\"allowfullscreen\" value=\"true\" /></param>
		<param name=\"wmode\" value=\"opaque\">
		<param name=\"allowscriptaccess\" value=\"always\" /><param name=\"movie\" value=\"http://www.vimeo.com/moogaloop.swf?clip_id=\\1&server=www.vimeo.com&show_title=1&show_byline=1&show_portrait=0&color=&fullscreen=1\" /><embed src=\"http://www.vimeo.com/moogaloop.swf?clip_id=\\1&server=www.vimeo.com&show_title=1&show_byline=1&show_portrait=0&color=&fullscreen=1\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" allowscriptaccess=\"always\" width=\"425\" height=\"344\"></embed></object>",
		
		"<object width=\"425\" height=\"344\" wmode=\"opaque\"></param>
		<param name=\"wmode\" value=\"opaque\">
		<embed src=\"http://media.xfire.com/swf/embedplayer.swf\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"425\" height=\"344\" flashvars=\"videoid=\\1\"></embed></object>",
		
		"<embed src=\"http://www.gametrailers.com/remote_wrap.php?mid=\\1\" swLiveConnect=\"true\" name=\"gtembed\" align=\"middle\" allowScriptAccess=\"sameDomain\" allowFullScreen=\"true\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"425\" height=\"344\"></embed>",
		
		"<object width=\"480\" height=\"270\" wmode=\"opaque\"></param>
		<param name=\"wmode\" value=\"opaque\">
		<param name=\"movie\" value=\"http://video.golem.de/player/videoplayer.swf?id=$1&autoPl=false\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"AllowScriptAccess\" value=\"always\"><embed src=\"http://video.golem.de/player/videoplayer.swf?id=$1&autoPl=false\" type=\"application/x-shockwave-flash\" allowfullscreen=\"true\" AllowScriptAccess=\"always\" width=\"480\" height=\"270\"></embed></object>");
		
		$txt = preg_replace($var,$repl,$txt);
	}
	
	if($chkMe >= 1)
	{
		$txt=str_replace('[hide]','<br />',$txt);
		$txt=str_replace('[/hide]','<br />',$txt);
	}
	else
	$txt = preg_replace("#\[hide\](.*?)\[\/hide\]#si", "",$txt); 

    $txt = preg_replace_callback("#\<img(.*?)\>#", create_function(
                                 '$img',
                                 'if(preg_match("#class#i",$img[1]))
                                    return "<img".$img[1].">";
                                  else return "<img class=\"content\"".$img[1].">";
                                '
                               ),
                               $txt);
							   
                               
  $txt = str_replace("\"","&#34;",$txt);
  $txt = preg_replace("#(\w){1,1}(&nbsp;)#Uis","$1 ",$txt);

  return $txt;
}


================================================
 suche nach...
================================================

//Diverse BB-Codefunktionen
function bbcode($txt, $tinymce=0, $no_vid=0)
{
  $txt = BadwordFilter($txt);
  $txt = replace($txt,$tinymce,$no_vid); 
  $txt = highlight_text($txt);
  $txt = re_bbcode($txt);
  $txt = strip_tags($txt,"<br><object><em><param><embed><strong><iframe><hr><table><tr><td><div><span><a><b><font><i><u><p><ul><ol><li><br /><img>");
  $txt = smileys($txt);
  
  if($no_vid == 0)
  $txt = glossar($txt);
  
  $txt = str_replace("&#34;","\"",$txt);
  $txt = str_replace('<p></p>', '<p>&nbsp;</p>', $txt);

  return $txt;
}

================================================
 ändere es in
================================================

//Diverse BB-Codefunktionen
function bbcode($txt, $tinymce=0, $no_vid=false)
{
  if(!$no_vid)
  	$txt = highlight_text($txt); # an die erste Stelle #
  		
  $txt = BadwordFilter($txt);
  $txt = replace($txt,$tinymce,$no_vid); 
  $txt = re_bbcode($txt);
  $txt = strip_tags($txt,"<br><object><em><param><embed><strong><iframe><hr><table><tr><td><div><span><a><b><font><i><u><p><ul><ol><li><br /><img>");
  $txt = smileys($txt);
  
  if(!$no_vid)
  	$txt = glossar($txt);
  
  $txt = str_replace("&#34;","\"",$txt);
  $txt = str_replace('<p></p>', '<p>&nbsp;</p>', $txt);

  return $txt;
}

================================================
 suche nach...
================================================

function bbcode_html($txt,$tinymce=0)
{
  $txt = str_replace("&lt;","<",$txt);
  $txt = str_replace("&gt;",">",$txt);
  $txt = str_replace("&quot;","\"",$txt);
  $txt = BadwordFilter($txt);
  $txt = replace($txt,$tinymce);
  $txt = highlight_text($txt);
  $txt = re_bbcode($txt);
  $txt = smileys($txt);
  $txt = glossar($txt);
  $txt = str_replace("&#34;","\"",$txt);

	return $txt;
}

================================================
 ändere es in
================================================

function bbcode_html($txt,$tinymce=0)
{
  $txt = highlight_text($txt); # an die erste Stelle #
  $txt = str_replace("&lt;","<",$txt);
  $txt = str_replace("&gt;",">",$txt);
  $txt = str_replace("&quot;","\"",$txt);
  $txt = BadwordFilter($txt);
  $txt = replace($txt,$tinymce,$no_vid);
  $txt = re_bbcode($txt);
  $txt = smileys($txt);
  $txt = glossar($txt);
  $txt = str_replace("&#34;","\"",$txt);

	return $txt;
}

================================================
 suche nach...
================================================

//-> PHP-Code farbig anzeigen
function highlight_text($txt)
{
  while(preg_match("=\[php\](.*)\[/php\]=Uis",$txt)!=FALSE)
  {
    $res = preg_match("=\[php\](.*)\[/php\]=Uis",$txt,$matches);

    $src = $matches[1];
    $src = str_replace('<?php','',$src);
    $src = str_replace('<?php','',$src);
    $src = str_replace('?>','',$src);
    $src = str_replace("&#39;", "'", $src);
    $src = str_replace("&#34;", "\"", $src);
    $src = str_replace("&amp;","&",$src);
    $src = str_replace("&lt;","<",$src);
    $src = str_replace("&gt;",">",$src);
    $src = str_replace('<?php','&#60;?',$src);
    $src = str_replace('?>','?&#62;',$src);
    $src = str_replace("&quot;","\"",$src);
    $src = str_replace("&nbsp;"," ",$src);
    $src = str_replace("&nbsp;"," ",$src);
    $src = str_replace("<p>","\n",$src);
    $src = str_replace("</p>","",$src);
    $l = explode("<br />", $src);
    $src = preg_replace("#\<br(.*?)\>#is","\n",$src);

    $src = '<?php'.$src.' ?>';

    $colors = array(
      '#111111' => 'string', '#222222' => 'comment', '#333333' => 'keyword',
      '#444444' => 'bg',     '#555555' => 'default', '#666666' => 'html'
    );

    foreach ($colors as $color => $key)
      ini_set('highlight.'.$key, $color);
// Farben ersetzen & highlighten
     $src = preg_replace(
      '!style="color: (#\d{6})"!e',
      '"class=\"".$prefix.$colors["\1"]."\""',
      highlight_string($src, TRUE)
    );

// PHP-Tags komplett entfernen
    $src = str_replace('&lt;?php','',$src);
    $src = str_replace('?&gt;','',$src);
    $src = str_replace('&amp;</span><span class="comment">#60;?','&lt;?',$src);
    $src = str_replace('?&amp;</span><span class="comment">#62;','?&gt;',$src);
    $src = str_replace('&amp;#60;?','&lt;?',$src);
    $src = str_replace('?&amp;#62;','?&gt;',$src);
    $src = str_replace(":", "&#58;", $src);
    $src = str_replace("(", "&#40;", $src);
    $src = str_replace(")", "&#41;", $src);
    $src = str_replace("^", "&#94;", $src);
// Zeilen zaehlen
    for($i=1;$i<=count($l)+1;$i++)
      $lines .= $i.".<br />";
// Ausgabe
    $code = '<div class="codeHead">&nbsp;&nbsp;&nbsp;Code:</div><div class="code"><table style="width:100%;padding:0px" cellspacing="0"><tr><td class="codeLines">'.$lines.'</td><td class="codeContent">'.$src.'</td></table></div>';

    $txt = preg_replace("=\[php\](.*)\[/php\]=Uis",$code,$txt,1);
  }
  return $txt;
}


================================================
 ändere es in
================================================

// PHP-Code farbig anzeigen und Liste erstellen
function printPHPCode($txt)
{
	$txt = explode("\n", str_replace(array("\r\n", "\r"), "\n", $txt));
	$line_count = 1; $formatted_code = "";

	foreach ($txt as $code_line)
	{
		$lines .= "&nbsp;".$line_count.".&nbsp;<br>";
		$line_count++;
          
		if (ereg('<\?(php)?[^[:graph:]]', $code_line))
			$formatted_code .= str_replace(array('<code>', '</code>'), '', highlight_string($code_line, true)).'<br>';
		else
			$formatted_code .= ereg_replace('(&lt;\?php&nbsp;)+', '', str_replace(array('<code>', '</code>'), '', highlight_string('<?php '.$code_line, true))).'<br>';
	}

	return '<table border="0" cellpadding="0" cellspacing="0" style="width:470px; border-style: solid; border-width:1px; padding: 0px; border-color: white black black white">
	<tr>
		<td width="100%" colspan="2"  style="border-style: solid; border-width:1px; border-color: white; background-color: #999999; font-family:Arial; color:white; font-weight:bold;">Php-Code:</td>
	</tr>
	<tr>
		<td width="3%" valign="top" style="background-color: #CCCCCC; border-style: solid; border-width:1px; border-color: white;"><code>'.$lines.'</code></td>
		<td width="97%" valign="top" style="background-color: #F0F0F0;"><div style="white-space: nowrap; width:470px; overflow: auto;"><code>'.$formatted_code.'</code></div></td>
	</tr>
	</table>';
}

function parseTagsRecursive($eingabe)
{
	$regex = '#\[php]((?:[^[]|\[(?!/?php])|(?R))+)\[/php]#';
	$from_php_convert = array("&lt;","&gt;","&#34;","&amp;","&quot;");
	$to_php_convert = array("<",">","\"","&",'"');
	
	if (is_array($eingabe)) 
	{ 
		$eingabe[1] = str_replace($from_php_convert,$to_php_convert,$eingabe[1]);
		$eingabe = printPHPCode($eingabe[1]); 
	}

	return preg_replace_callback($regex, 'parseTagsRecursive', $eingabe);
}

//-> PHP-Code farbig anzeigen
function highlight_text($txt)
{
	return parseTagsRecursive($txt); ## BUG FIX ##
}

================================================
 suche nach...
================================================

elseif(preg_match("/MSIE 8/i",$data))     $browser = "IE 8";

================================================
 Schreibe drunter...
================================================

elseif(preg_match("/MSIE 9/i",$data))     $browser = "IE 9";

================================================
 suche nach...
================================================

//-> Funktion um Dateien aus einem Verzeichnis auszulesen
function get_files($dir)
{
  $dp = @opendir($dir);
  $files = array();
  while($file = @readdir($dp))
  {
    if($file != '.' && $file != '..')
      array_push($files, $file);
  }
  @closedir($dp);
  sort($files);

  return($files);
}

================================================
 ändere es in
================================================

## Funktion um Dateien oder Verzeichnisse auszulesen ##
function get_files($dir,$only_dir=false,$only_files=false)
{
	$dp = @opendir($dir);
	$files = array();
  
	if($only_dir) //Nur Ordner
	{
		while($file = @readdir($dp))
		{
			if($file != '.' && $file != '..' && !is_file($dir.'/'.$file))
				array_push($files, $file);
		}	  
	}
	else if($only_files) //Nur Dateien
	{
		while($file = @readdir($dp))
		{
			if($file != '.' && $file != '..' && is_file($dir.'/'.$file))
				array_push($files, $file);
		}
	}
	else // Ordner & Dateien
	{
		while($file = @readdir($dp))
		{
			if($file != '.' && $file != '..')
			array_push($files, $file);
		}
	}
	  
	@closedir($dp);
	sort($files);
	
	return($files);
}


================================================
 suche nach...
================================================

//-> Templateswitch
$files = get_files('../inc/_templates_/');

================================================
 ändere es in
================================================

//-> Templateswitch
$files = get_files('../inc/_templates_/',true);


================================================
 suche nach...
================================================

//init templateswitch
    $tmps = get_files('../inc/_templates_/');

================================================
 ändere es in
================================================

//init templateswitch
    $tmps = get_files('../inc/_templates_/',true);

================================================
 öffne folgende Datei...
================================================

 forum/index.php

================================================
 suche nach...
================================================

"posteintrag" => bbcode($get['t_text'],0,1)));

================================================
 ändere es in
================================================

"posteintrag" => $get['t_text']));

================================================
 öffne folgende Datei...
================================================

 inc/lang/global.php

================================================
 suche nach...
================================================

## ADDED / REDEFINED FOR 1.5.2

================================================
 schreibe darüber
================================================

## ADDED / REDEFINED FOR 1.5.5.3
define('_klapptext_link','<a href="javascript:DZCP.toggle(\'[id]\')"><img src="../inc/images/[moreicon].gif" alt="" id="img[id]">[link]</a>');

================================================
 öffne folgende Dateien...

 *(Achtung! Wenn ein Download Addon installiert ist, bitte die folgenden Schritte nicht dürchführen!)
 *(Für ein Update wende dich bitte an den Autor des Addons)

================================================

 inc/lang/languages/deutsch.php
 inc/lang/languages/english.php

================================================
 suche jeweils nach...

================================================

define('_dl_titel', '<span class="fontBold">[name]</span> - [cnt] [file]');

================================================
 ändere es in

================================================

define('_dl_titel', '<span class="fontBold">[name]</span> - ');


================================================
 öffne folgende Datei...

 *(Achtung! Wenn ein Server Addon installiert ist, bitte die folgenden Schritte nicht dürchführen!)
 *(Für ein Update wende dich bitte an den Autor des Addons)

================================================

 server/index.php

================================================
 suche nach...
================================================

$player['name'] = htmlentities($player['name'], ENT_QUOTES);

================================================
 ändere es in
================================================

$player['name'] = htmlentities(utf8_decode($player['name']), ENT_QUOTES);


================================================
 suche nach...
================================================

$klapp = show(_klapptext_server_link, array("link" => _server_splayerstats,
                                            "id" => $get['id'],
                                            "moreicon" => $moreicon));

================================================
 ändere es in
================================================

$klapp = show(_klapptext_link, array("link" => _server_splayerstats, "id" => $get['id'], "moreicon" => $moreicon));


================================================
 öffne folgende Datei...

 *(Achtung! Wenn ein Download Addon installiert ist, bitte die folgenden Schritte nicht dürchführen!)
 *(Für ein Update wende dich bitte an den Autor des Addons)
 *(Die Schritte mit der Markierung (Last DL Fix) können ausgeführt werden)

================================================

 downloads/index.php

================================================
 suche nach... (Last DL Fix)
================================================

$lastdate = date("d.m.Y H:i",@fileatime($file))._uhr;

================================================
 ändere es in (Last DL Fix)
================================================

$lastdate = date("d.m.Y H:i",$get['last_dl'])._uhr;

================================================
 suche nach... (Last DL Fix)
================================================

$upd = db("UPDATE ".$db['downloads']." SET `hits` = hits+1 WHERE id = '".intval($_GET['id'])."'");

================================================
 ändere es in (Last DL Fix)
================================================

$upd = db("UPDATE ".$db['downloads']." SET `hits` = hits+1, `last_dl` = '".time()."' WHERE id = '".intval($_GET['id'])."'");

================================================
 suche nach...
================================================

      $kat = show(_dl_titel, array("id" => $get['id'],
                                   "icon" => $moreicon,
                                   "file" => $dltitel,
                                   "cnt" => $cntKat,
                                   "name" => re($get['name'])));

================================================
 ändere es in
================================================

$kat = show(_dl_titel, array("id" => $get['id'], "name" => re($get['name'])));


================================================
 suche nach...
================================================

$class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;

================================================
 schreibe drunter...
================================================

if($_GET['show'] == $get['id'])
{
	$display = "show";
       	$moreicon = "collapse";
} 
else 
{
       	$display = "none";
       	$moreicon = "expand";
}

$klapp = show(_klapptext_link, array("link" => $kat, "id" => $get['id'], "moreicon" => $moreicon));


================================================
 suche nach...
================================================

$kats .= show($dir."/download_kats", array("kat" => $kat,


================================================
 ändere es in
================================================

$kats .= show($dir."/download_kats", array("files" => $cntKat.' '.$dltitel, 
					   "display" => $display, 
					   "klapp" => $klapp, 

================================================
 Updater ausführen
================================================

http://www.deine_domain.de/_installer/

================================================
 Fertig...
================================================