########################################
# deV!L`z Clanportal - kurz 'DZCP' 2012
# ====================================
# www.dzcp.de
########################################


Installation
=============================
Die Installation gestaltet sich recht einfach.
Lade alle Dateien aus dem Archiv per FTP auf deinen Webserver und öffne anschließend in deinem Web-Browser das Installationsprogramm unter z.B. www.url.de/_installer/.
Folge hier den Anweisungen, die dich durch die Installationsroutine begleiten. 

Anschließend bitte unbedingt den Ordner _installer/ vom webspace löschen.


Update von 1.5.5.1 zu 1.5.5.2
=============================

	1. Info's
	================================================================
	Security Fix: 		Gäste konnten alle Gästebucheintragungen löschen
	Security Fix: 		Gäste & Normale User konnten die Public Einstellungen der Gästebucheintragungen ändern
	Bugfix:				Globale Variable $c fehlte im Teamspeak Server Viewer
	Bugfix:				Bei dem bearbeiten von Posts, etc. wurden die Youtube Videos aus dem Editor entfernt, so das man diese nicht mehr bearbeiten oder löschen konnte.
	Bugfix:				In den TOP5 Forum Posts wurde die Datensatz ID ausgelesen, statt der User ID *Danke geht an Aviator & LG Hellbz
	
	Kompatibilitäts Fix:	Probleme bei der Ausführung von DZCP auf Zend Servern oder änlichen die wegen der basePath definition eine Notice oder Error ausgegeben haben
	

2. Update manuell
================================================================

################################################
Folgende Dateien komplett ersetzen 
################################################

- inc/_version.php

################################################
Öffne folgende Datei
################################################

- gb/index.php

################################################
suche nach...
################################################

} elseif($_GET['what'] == 'set') {

################################################
Ersetze alles inclusive der Zeile
################################################

if($get['reg'] == $userid || permission('gb'))

################################################
Gegen folgenden Code:
################################################

}
## V1.5.5.2 FIX ##
elseif($_GET['what'] == 'set') 
{
 	if(permission('gb'))
	{
		db("UPDATE ".$db['gb']." SET `public` = '1' WHERE id = '".intval($_GET['id'])."'");
		header("Location: ../gb/");
	}
	else
		$index = error(_error_edit_post,1);
}
elseif($_GET['what'] == 'unset') 
{
	if(permission('gb'))
	{
		db("UPDATE ".$db['gb']." SET `public` = '0' WHERE id = '".intval($_GET['id'])."'");
		header("Location: ../gb/");
	}
	else
		$index = error(_error_edit_post,1);
} 
elseif($_GET['what'] == "delete") 
{
	$qry = db("SELECT * FROM ".$db['gb']." WHERE id = '".intval($_GET['id'])."'");
	$get = _fetch($qry);
      
	if($get['reg'] == $userid && $chkMe != "unlogged" or permission('gb'))
	{
		db("DELETE FROM ".$db['gb']." WHERE id = '".intval($_GET['id'])."'");
		$index = info(_gb_delete_successful, "../gb/");
	}
	else 
		$index = error(_error_edit_post,1);
} 
elseif($_GET['what'] == "edit") 
{
	$qry = db("SELECT * FROM ".$db['gb']."  WHERE id = '".intval($_GET['id'])."'");
	$get = _fetch($qry);
      
	if($get['reg'] == $userid && $chkMe != "unlogged" or permission('gb'))
## V1.5.5.2 FIX END ##

################################################
 öffne folgende Datei 
################################################

- inc/menu-functions/teamspeak.php

################################################
suche nach...
################################################

global $db, $settings, $language;

################################################
Ändere es in
################################################

global $db, $settings, $language, $c;

################################################
Öffne folgende Datei...
################################################

 index.php

################################################
 suche nach...
################################################

 define(basePath, dirname(__FILE__));

################################################
 Ändere es in
################################################

 define('basePath', dirname(__FILE__));
 
################################################
 Öffne folgende Datei...
################################################

 forum/index.php 
 
################################################
 Suche nach
################################################
 
 "posteintrag" => bbcode($get['t_text'])));
 
################################################
 Ändere es in
################################################
 
 "posteintrag" => bbcode($get['t_text'],0,1)));
 
################################################
Forum Posts TOP 5 FIX START
################################################

################################################
suche nach...
################################################

$qrytp = db("SELECT id,forumposts FROM ".$db['userstats']."
               ORDER BY forumposts DESC, id
               LIMIT 5");

################################################
 Ändere es in
################################################

 $qrytp = db("SELECT id,user,forumposts FROM ".$db['userstats']."
               ORDER BY forumposts DESC, id
               LIMIT 5");

################################################
 suche nach...
################################################

$show_top .= show($dir."/top_posts_show", array("nick" => autor($gettp['id']),

################################################
 Ändere es in
################################################

$show_top .= show($dir."/top_posts_show", array("nick" => autor($gettp['user']),

################################################
Forum Posts TOP 5 FIX END
################################################
 
################################################
 öffne folgende Datei...
################################################

inc/bbcode.php
 
################################################
Suche nach
################################################

function replace($txt,$type=0)

################################################
 Ändere es in
################################################

function replace($txt,$type=0,$no_vid_tag=0)

################################################
Suche nach
################################################

	  $txt = preg_replace_callback("#\[youtube\]http\:\/\/www.youtube.com\/watch\?v\=(.*)\[\/youtube\]#Uis", #
					create_function(
									 '$yt',
									 '
									  $width = 425; $height = 344;
									  return "<object width=\"".$width."\" height=\"".$height."\"><param name=\"movie\" value=\"http://www.youtube.com/v/".trim($yt[1])."&amp;hl=de&amp;fs=1\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"http://www.youtube.com/v/".trim($yt[1])."&amp;hl=de&amp;fs=1\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"".$width."\" height=\"".$height."\"></embed></object>";
									'
								   ), $txt);

################################################
 Ändere es in
################################################

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

################################################
Suche nach
################################################

function bbcode($txt, $tinymce=0)

################################################
 Ändere es in
################################################

function bbcode($txt, $tinymce=0, $no_vid=0)

################################################
Suche nach "In der Function bbcode($txt, $tinymce=0, $no_vid=0)"
################################################

$txt = replace($txt,$tinymce);

################################################
 Ändere es in
################################################

$txt = replace($txt,$tinymce,$no_vid); 

################################################
Suche nach "In der Function bbcode($txt, $tinymce=0, $no_vid=0)"
################################################

$txt = glossar($txt);

################################################
 Ändere es in
################################################

if($no_vid == 0)
$txt = glossar($txt);




Update von 1.5.5 zu 1.5.5.1
=============================


	1. Info's
	================================================================
	Security Fix: 	User Delete Manipulation
	Bugfix:		Profil editieren: Formular konnte nicht abgesendet werden

2. Update manuell
================================================================

################################################
 Folgende Dateien komplett ersetzen 
################################################

- inc/_version.php
- inc/teamspeak_query.php

################################################
 öffne folgende Datei
################################################

- forum/index.php

################################################
suche nach...
################################################

} elseif($_GET['do'] == "addpost") {

################################################
Direkt darunter mit einer neuen Zeile folgendes einfügen...
################################################

  if(settings("reg_forum") == "1" && $chkMe == "unlogged")
  {
    $index = error(_error_unregistered,1);
  } else {

################################################
suche weiter nach folgenden Code... 
################################################

$index = info(_forum_newpost_successful, $lpost);

################################################
Direkt darunter mit einer neuen Zeile folgendes einfügen...
################################################

}


################################################
 öffne folgende Datei
################################################

- user/index.php

################################################

################################################
suchen nach...
################################################


				$qrydel = db("SELECT id,nick,email,hp FROM ".$db['users']."
											WHERE id = '".intval($_GET['id'])."'");
				$getdel = _fetch($qrydel);

################################################
Diesen Code mit folgenden austauschen...
################################################

				$qrydel = db("SELECT id,nick,email,hp FROM ".$db['users']."
											WHERE id = '".intval($userid)."'");
				$getdel = _fetch($qrydel);

################################################
suche weiter nach...
################################################


//$('form#editprofil').submit();

################################################
Diesen Code mit folgenden ersetzten...
################################################

$('form#editprofil').submit();



Update von 1.5.4 zu 1.5.5
=============================


	1. Info's
	================================================================
	Security Fix: 	Language Cookie Manipulation
	Bugfix:		Profil editieren: Nach Wohnorteingabe wurde das Formular automatisch abgesendet
	Bugfix: 	Clanwar Scrennshots konnten im IE nicht hochgeladen werden
	Edit: 		Neuer HL2 Serverquery
	Bugfix:		Beim User hinzufügen wurden die Coordinaten nicht übernommen, 
			sofern ein Wohnort eingetragen wurde
	Bugfix:		Kalenderevents im Menu wurden falsch sortiert
	Edit:		Useronlinezeit 
	Edit:		diverse Fehler in den Sprachdateien behoben	
	Bugfix:		PHP-Fehler beim Bilderupload entfernt
	Bugfix:		Bilder beim User löschen ebenfalls entfernen (Userpic, Avatar)
	Edit: 		Beim User editieren zurück zur Userliste weiterleiten anstatt ins Profil
	Edit:		Galerie: Bildnamen werden mit vorangestellten Nullen abgespeichert, 
			um eine korrekte Sortierung zu ermöglichen

	
2. Update manuell
================================================================

################################################
 Folgende Dateien komplett ersetzen 
################################################

- inc/_version.php
- inc/buffer.php
- inc/teamspeak_query.php
- inc/server_query/halflife2.php
 
################################################



################################################
- admin/menu/gallery.php
################################################
	
   @copy($tmp, basePath."/gallery/images/".$galid."_".$i.".".strtolower($end));
   
ersetzen durch:   
   
   @copy($tmp, basePath."/gallery/images/".$galid."_".str_pad($i, 3, '0', STR_PAD_LEFT).".".strtolower($end));

################################################
		
   @copy($tmp, basePath."/gallery/images/".$galid."_".($i+$cnt).".".strtolower($end));
   
ersetzen durch:   
   
   @copy($tmp, basePath."/gallery/images/".$galid."_".str_pad($i+$cnt, 3, '0', STR_PAD_LEFT).".".strtolower($end));

################################################


    
################################################
- admin/menu/cw.php
################################################

Folgende Zeilen gegen die alten ersetzen:

227 - 297
344 - 455

################################################



################################################
- admin/menu/adduser.php
################################################

Zeile: 38

$('form#editprofil').submit();

ersetzen durch:

$('form#adduser').submit();

################################################



################################################
- upload/index.php
################################################

nach allen Einträge wie folgt suchen und entfernen:

$imageinfo = getimagesize($tmpname);

################################################



################################################
- user/index.php
################################################

suchen nach...

				$qrydel = db("SELECT id,nick,email,hp FROM ".$db['users']."
											WHERE id = '".intval($_GET['id'])."'");
				$getdel = _fetch($qrydel);

Diesen Code mit folgenden austauschen...

				$qrydel = db("SELECT id,nick,email,hp FROM ".$db['users']."
											WHERE id = '".intval($userid)."'");
				$getdel = _fetch($qrydel);

################################################

suchen weiter nach...

 $del = db("DELETE FROM ".$db['userstats']."
 WHERE user = '".intval($getdel['id'])."'");

direkt darunter mit einer neuen Zeile folgendes einfügen...

				foreach($picformat as $tmpendung)
				{
					if(file_exists(basePath."/inc/images/uploads/userpics/".intval($getdel['id']).".".$tmpendung))
					{
						@unlink(basePath."/inc/images/uploads/userpics/".intval($getdel['id']).".".$tmpendung);
					}
					if(file_exists(basePath."/inc/images/uploads/useravatare/".intval($getdel['id']).".".$tmpendung))
					{
						@unlink(basePath."/inc/images/uploads/useravatare/".intval($getdel['id']).".".$tmpendung);
					}
				}

################################################

suche weiter nach...

//$('form#editprofil').submit();

Diesen Code mit folgenden ersetzten...

$('form#editprofil').submit();

################################################
				
				
				
################################################
- inc/bbcode.php
################################################

Zeilen: 2283 - 2293

$addonlang = !empty($_COOKIE[$prev.'language']) ? $_COOKIE[$prev.'language'] : $settings["language"];
if($l = get_files(basePath.'/inc/additional-languages/'.$addonlang.'/'))
{
	foreach($l AS $languages)
	{
		$extl = explode('.', strtolower($languages));
		$extl = $extl[count($extl) - 1];
		if($extl == 'php') {
			include(basePath.'/inc/additional-languages/'.$addonlang.'/'.$languages);
		}
	}
}

ersetzen durch:

if($l = get_files(basePath.'/inc/additional-languages/'.$language.'/'))
{
	foreach($l AS $languages)
	{
		$extl = explode('.', strtolower($languages));
		$extl = $extl[count($extl) - 1];
		if($extl == 'php') {
			include(basePath.'/inc/additional-languages/'.$language.'/'.$languages);
		}
	}
}

################################################

Zeile: 40

$useronline = 300;

ersetzen durch:

$useronline = 1800;

################################################



################################################
- inc/menu-functions/events.php
################################################

Zeile: 8

ORDER BY datum > ".time()."

ersetzen durch:

ORDER BY datum

################################################



################################################
- inc/lang/languages/deutsch.php
################################################

Zeile: 6

define(_confirm_del_account, 'Moechtest du wirklich dein Benutzeraccount auf dzcp.de loeschen');

ersetzen durch:

define(_confirm_del_account, 'Moechtest du wirklich dein Benutzeraccount loeschen');

################################################



################################################
- inc/lang/languages/english.php
################################################

Zeile: 6

define(_confirm_del_account, 'You really want to delete your Account on dzcp.de');

ersetzen durch:

define(_confirm_del_account, 'You really want to delete your Account');

################################################



################################################
- inc/templates/TEMPLATE/user/edit_profil.html
################################################

Zeile: 116

onblur="getCord();this.className='inputField_dis_profil';" />

ersetzen durch: 

onblur="this.className='inputField_dis_profil';" />

################################################


Update von 1.5.3 zu 1.5.4
=============================
	
	
	1. Info's
	================================================================
	Bugfix: getimagesize() Funktionen falsch gesetzt
	Bugfix: Falsche Funktion im Forum
	Bugfix: FightUs wurde nicht den zuständigen Rechten verteilt
  	Bugfix: Vorbereitung auf PHP 6: ereg austauschen mit preg / preg_match ausgetauscht
  	Bugfix: Automatisch verkleinerte Bilder wurden nach klick nicht in der Lightbox angezeigt
  	Bugfix: automatisch erkannter URL Pfad hatte nicht gestimmt
  	Bugfix: Menu Serverviewer: Umbruch beim Mapnamen wurde nicht getätigt
  	Bugfix: Login mit Sonderzeichen im Loginnamen oder Passwortes war nicht möglich
  	Bugfix: Badword Filter hatte nicht funktioniert
  	Bugfix: Forum: Klick auf Postnummer brachte SQL Fehler, sofern &page= nicht übergeben wurde
  	Bugfix: Membermap: Koordinaten wurden nicht immer übernommen bei Profil editieren bzw. User anlegen
  	Bugfix: Vote über die Navigation / Ajaxfunktion brachte Fehler in Zusammenhang mit der direkten Weiterleitung
  	New: Letzten 3 DZCP News im Adminmenu
  	New: Hintergrundfarbe des Copyrightlinks durch angabe des Farbcodes selber bestimmen (in der Datei /inc/_version.php)
  	New: BFBC2 Serverviewer: Spielernamen werden ab R9 angezeigt

	2. Aktualisierte Dateien (einfach ersetzen, sofern keine Mods installiert)
	================================================================
	
	Einfach folgende Dateien neu hochladen und ersetzen:

	- /inc/bbcode.php
  - /admin/index.php
	- /admin/menu/cw.php
	- /admin/menu/adduser.php
	- /context/index.php
  - /inc/menu-functions/ftopics.php
  - /inc/menu-functions/vote.php
  - /inc/server_query/bfbc2.php
  - /inc/tinymce/filemanager/connectors/php/connector.php
  - /inc/_version.php
  - /inc/buffer.php
  - /user/index.php
  - /vote/index.php
  
  - /inc/_templates_/[TEMPLATE]/_js/dzcp.js
  - /inc/_templates_/[TEMPLATE]/admin/admin.html
  - /inc/_templates_/[TEMPLATE]/menu/server.html
  - /inc/_templates_/[TEMPLATE]/user/edit_profil.html
	
	3. Update manuell
	================================================================

	================================================================
	ersetze folgende Dateien komplett...
	================================

	/admin/index.php
	/admin/menu/sponsors.php
  /inc/menu-functions/ftopics.php
  /inc/menu-functions/vote.php
  /inc/server_query/bfbc2.php
  
	/inc/_templates_/[TEMPLATE]/admin/admin.html
	/inc/_templates_/[TEMPLATE]/admin/register.html
  
	================================================================
	öffne folgende Datei...
	================================

	/inc/bbcode.php

	================================
	suche nach folgenden Code...
	================================

  @error_reporting(E_ALL & ~E_NOTICE);

	================================
	ersetzte ihn mit folgenden Code...
	================================

	error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);

	================================
	suche weiter nach folgenden Code...
	================================

	preg_replace("#".$word."#i", str_repeat("*", strlen($word)), $txt);

	================================
	ersetzte ihn mit folgenden Code...
	================================

	$txt = preg_replace("#".$word."#i", str_repeat("*", strlen($word)), $txt);

	================================
	suche weiter nach folgenden Code...
	================================

	$txt = preg_replace("# ".$w." #is"," <tmp|".$w."|tmp> ",$txt);

	================================
	ersetzte ihn mit folgenden Code...
	================================

	$txt = str_ireplace(' '.$w, ' <tmp|'.$w.'|tmp> ', $txt);

	================================
	suche weiter nach folgenden Code...
	================================

	$txt = preg_replace("#\<tmp\|".$w."\|tmp\>#is",$r,$txt);

	================================
	ersetzte ihn mit folgenden Code...
	================================

	$txt = str_ireplace('<tmp|'.$w.'|tmp>', $r, $txt);

	================================
	suche weiter nach folgenden Code...
	================================

	$txt = strip_tags($txt,"<br><object><em><param><embed><strong><hr><table><tr><td><div><span><a><b><font><i><u><p><ul><ol><li><br /><img>");

	================================
	ersetzte ihn mit folgenden Code...
	================================

	$txt = strip_tags($txt,"<br><object><em><param><embed><strong><iframe><hr><table><tr><td><div><span><a><b><font><i><u><p><ul><ol><li><br /><img>");

	================================
	suche weiter nach folgenden Code...
	================================
  
  if(!ereg("^([a-zA-Z0-9\.\_\-]+)@([a-zA-Z0-9\.\-]+\.[A-Za-z][A-Za-z]+)$", $email)) return false;
  
	================================
	ersetzte ihn mit folgenden Code...
	================================
  
  if(!preg_match("#^([a-zA-Z0-9\.\_\-]+)@([a-zA-Z0-9\.\-]+\.[A-Za-z][A-Za-z]+)$#", $email)) return false;

	================================
	suche weiter nach folgenden Code...
	================================
  
  $subfolder = basename(dirname(dirname(__FILE__).'../'));
  
	================================
	ersetzte ihn mit folgenden Code...
	================================
  
  $subfolder = basename(dirname(dirname($_SERVER['PHP_SELF']).'../'));

	================================================================
	öffne folgende Datei...
	================================

	/admin/menu/cw.php

	================================
	suche nach folgenden Code...
	================================

	$img = @getimagesize($tmp);

	Diese und ähnliche Zeilen bitte 3 Zeilen nach unten setzten
	Beispiel

	Aus...

          $end = strtolower($end[count($end)-1]);
          $img = @getimagesize($tmp);
          if(!empty($tmp))
          {
            if($type == "image/gif" || $type == "image/png" || $type == "image/jpeg" || !$img[0])

	wird...

          $end = strtolower($end[count($end)-1]);
          
          if(!empty($tmp))
          {
            $img = @getimagesize($tmp);
	    if($type == "image/gif" || $type == "image/png" || $type == "image/jpeg" || !$img[0])

	
	Dies dann bitten bei den 10 weiteren änlichen Einträgen machen.

	================================================================
	öffne folgende Datei...
	================================

	/admin/menu/adduser.php

	================================
	suche nach folgenden Code...
	================================


      $gmaps_key = settings('gmaps_key');
      if(!empty($gmaps_key))
      {
        $gmaps = "<script language=\"javascript\" src=\"http://maps.google.com/maps?file=api&amp;v=2&amp;key=".$gmaps_key."\" type=\"text/javascript\"></script>
  				<script language=\"javascript\" type=\"text/javascript\">
  				  <!--
  					function getCord()
  					{
  					  var geocoder = new GClientGeocoder();
  						  geocoder.setCache(null);
  					  var city = $('city').value;
  					  var country = $('land').value;
  					  var address = city+', '+country;
  				
  					  geocoder.getLatLng(address,
  						function(point)
  						{
  						  if(point)
  						  {
  							$('gmaps_koord').value = point;
  						  }
  						}
  					  );
  					}
  				  //-->
  				</script>";
        }

	================================
	ersetzte ihn mit folgenden Code...
	================================
  
			$gmaps_key = settings('gmaps_key');
			if(!empty($gmaps_key))
			{
				$gmaps = "
					<script language=\"javascript\" src=\"http://maps.google.com/maps?file=api&amp;v=2&amp;key=".$gmaps_key."\" type=\"text/javascript\"></script>
					<script language=\"javascript\" type=\"text/javascript\">
					<!--
            function getCord()
            {
              var address = $('#city').attr('value') + ', ' + $('#land').attr('value');
              var geocoder = new GClientGeocoder();
                  geocoder.setCache(null);
                  geocoder.getLatLng(address,
                    function(point)
                    {
                      if(point)
                      {
                        $('#gmaps_koord').attr('value', point);
                      }
                      
                      $('form#editprofil').submit();
                    }
                );
                
              DZCP.submitButton();
              return false;
            }
					//-->
					</script>";
			} else {
				$gmaps = "
					<script language=\"javascript\" type=\"text/javascript\">
					<!--
						function getCord()
						{
							return true;
						}
					//-->
					</script>";
			}


	================================
	suche weiter nach folgenden Code...
	================================

	$imageinfo = getimagesize($tmpname);

	Diesen Code bitte markieren, löschen und direkt über... mit einer neuen Zeile einfügen...

	foreach($picformat as $tmpendung)

	================================================================
	öffne folgende Datei...
	================================

	/inc/_templates_/[TEMPLATE]/news/news.html

	================================
	suche nach folgenden Code...
	================================
	
	[show]

	================================
	direkt darunter mit einer neuen Zeile folgenden Code einfügen...
	================================

	<center>[nav]</center>

	================================================================
	öffne folgende Datei...
	================================

	/forum/index.php

	================================
	suche nach folgenden Code...
	================================

	news_autor

	================================
	ersetzte ihn mit folgenden Code...
	================================

	fabo_autor

	================================
	suche nochmal nach folgenden Code...
	================================

	news_autor

	================================
	ersetzte ihn nochmal mit folgenden Code...
	================================

	fabo_autor

	================================
	suche nach folgenden Code...
	================================

	"url" => '?action=showthread&amp;id='.intval($_GET['id']).'&amp;page='.intval($_GET['page']).'#p'.($i+($page-1)*$maxfposts),

	================================
	ersetzte ihn mit folgenden Code...
	================================

	"url" => '?action=showthread&amp;id='.intval($_GET['id']).'&amp;page='.intval(empty($_GET['page']) ? 1 : $_GET['page']).'#p'.($i+($page-1)*$maxfposts),

	================================
	suche nach folgenden Code...
	================================

	"status" => getrank($userid),
        "avatar" => useravatar($userid),
	
	================================
	ersetzte ihn mit folgenden Code...
	================================

	"status" => getrank($pUId),
	"avatar" => useravatar($pUId),

	================================================================
	öffne folgende Datei...
	================================

	/user/index.php

	================================
	suche nach folgenden Code...
	================================

			$qry = db("INSERT INTO ".$db['users']."
                 SET `user`     = '".$_POST['user']."',
                     `nick`     = '".$_POST['nick']."',
                     `email`    = '".$_POST['email']."',
                     `pwd`      = '".$pwd."',
                     `regdatum` = '".((int)time())."',
                     `level`    = '1',
                     `time`     = '".time()."',
                     `status`   = '1'");

	================================
	ersetzte ihn mit folgenden Code...
	================================

			$qry = db("INSERT INTO ".$db['users']."
                 SET `user`     = '".up($_POST['user'])."',
                     `nick`     = '".up($_POST['nick'])."',
                     `email`    = '".up($_POST['email'])."',
                     `pwd`      = '".$pwd."',
                     `regdatum` = '".((int)time())."',
                     `level`    = '1',
                     `time`     = '".time()."',
                     `status`   = '1'");

	================================
	suche nach folgenden Code...
	================================
  
  		$message = show(settings('eml_reg'), array("user" => $_POST['user'],
											       "pwd" => $mkpwd));

	================================
	ersetzte ihn mit folgenden Code...
	================================

  		$message = show(settings('eml_reg'), array("user" => up($_POST['user']),
											       "pwd" => $mkpwd));

	================================
	suche nach folgenden Code...
	================================
  
    function getCord()
    {
      var address = $('#city').attr('value') + ', ' + $('#land').attr('value');
      var geocoder = new GClientGeocoder();
          geocoder.setCache(null);
          geocoder.getLatLng(address,
            function(point)
            {
              if(point)
              {
                $('#gmaps_koord').attr('value', point);
              }
            }
        );
    }

	================================
	ersetzte ihn mit folgenden Code...
	================================
  
    function getCord()
    {
      var address = $('#city').attr('value') + ', ' + $('#land').attr('value');
      var geocoder = new GClientGeocoder();
          geocoder.setCache(null);
          geocoder.getLatLng(address,
            function(point)
            {
              if(point)
              {
                $('#gmaps_koord').attr('value', point);
              }
              
              $('form#editprofil').submit();
            }
        );
        
      return false;
    }
  
	================================================================
	öffne folgende Datei...
	================================

	/contact/index.php

	================================
	suche nach folgenden Code...
	================================

	$sqlAnd = " AND s3.`user` != '".intval($get['user'])."'";

	================================
	ersetzte ihn mit folgenden Code...
	================================

	$sqlAnd = " AND s2.`user` != '".intval($get['user'])."'";

	================================
	suche weiter nach folgenden Code...
	================================
	
      $qry = db("SELECT s3.`user` FROM ".$db['permissions']." AS s1
                 LEFT JOIN ".$db['squaduser']." AS s2 ON s1.user = s2.user
                 LEFT JOIN ".$db['userpos']." AS s3 ON s1.`pos` = s3.`posi`
                 WHERE s1.`receivecws` = '1' AND s3.`posi` != '0'".$sqlAnd.$add." GROUP BY s3.`user`");

	
	================================
	ersetzte ihn mit folgenden Code...
	================================

      $qry = db("SELECT s3.`user` FROM ".$db['permissions']." AS s1
                 LEFT JOIN ".$db['userpos']." AS s2 ON s1.`pos` = s2.`posi`
                 LEFT JOIN ".$db['squaduser']." AS s3 ON s2.user = s3.user
		 WHERE s1.`receivecws` = '1' AND s2.`posi` != '0'".$sqlAnd.$add." GROUP BY s2.`user`");

	================================================================
	öffne folgende Datei...
	================================
  
  /inc/_templates_/[TEMPLATE]/menu/server.html

	================================
	suche nach folgenden Code...
	================================
  
  <td class="navServerStatsContent" nowrap="nowrap"><span class="fontBold">Map:</span> [map]</td>
	
	================================
	ersetzte ihn mit folgenden Code...
	================================
  
  <td class="navServerStatsContent"><span class="fontBold">Map:</span> [map]</td>

	================================================================
	öffne folgende Datei...
	================================
  
  /inc/_templates_/[TEMPLATE]/user/edit_profil.html

	================================
	suche nach folgenden Code...
	================================
  
  <form name="editprofil" action="../user/?action=editprofile&amp;do=edit" method="post" onsubmit="return(DZCP.submitButton())">
	
	================================
	ersetzte ihn mit folgenden Code...
	================================
  
  <form id="editprofil" name="editprofil" action="../user/?action=editprofile&amp;do=edit" method="post" onsubmit="return(DZCP.submitButton())">

	================================
	suche weiter nach folgenden Code...
	================================
  
	<input id="contentSubmit" class="submit" type="submit" value="[value]" />
  
	================================
	ersetzte ihn mit folgenden Code...
	================================
  
  <input id="contentSubmit" class="submit" onclick="return(getCord())" type="submit" value="[value]" />
  
	================================================================
	öffne folgende Datei...
	================================
  
  /inc/_templates_/[TEMPLATE]/admin/register.html

	================================
	suche nach folgenden Code...
	================================
  
  <form name="adduser" enctype="multipart/form-data" action="?admin=adduser&amp;do=add" method="post" onsubmit="return(DZCP.submitButton())">
	
	================================
	ersetzte ihn mit folgenden Code...
	================================
  
  <form id="adduser" name="adduser" enctype="multipart/form-data" action="?admin=adduser&amp;do=add" method="post" onsubmit="return(DZCP.submitButton())">

	================================
	suche weiter nach folgenden Code...
	================================
  
	<input id="contentSubmit" type="submit" value="[value]" class="submit" />
  
	================================
	ersetzte ihn mit folgenden Code...
	================================
  
  <input id="contentSubmit" type="submit" value="[value]" onclick="return(getCord())" class="submit" />


Update von 1.5.2 zu 1.5.3
=============================

	1. Info's
	================================
	BugFix: Sicherheitslücke entfernt, welche auftritt, wenn die PHP Funktionen register_globals und allow_url_fopen aktiv sind


	2. Update automatisch
	================================
	
	Eichfach alle php Dateien austauschen.


	3. Update manuell
	================================

	Wenn bereits modifikationen vorgenommen wurden am besten mittels einem Editor in allen PHP - Dateien nach die Variable
	$basePath suchen und mit basePath ersetzen.


Update von 1.5.1 zu 1.5.2
=============================

	1. Info's
	================================
	BugFix: Fehler im RSS - Feed 
	BugFix: Refreshintervalle bei Shoutbox und Teamspeak korrigiert
	Bugfix: Tippfehler im Quellcode für Gästebucheinträge in der Userlobby
	New: Im Adminmenü kann man nun einstellen, ob ein Admin erst die Gästebucheinträge freischalten muss. 
	Bugfix: Tippfehler in der english.php
	Bugfix: mehrere Youtube Videos im Forum jetzt möglich
	Bugfix: Forenabbo, Linkausgabe bei Email korregiert
	New: Languagesordner für Mods hinzugefügt
	New: zeitversetzte News veröffentlichen
	New: User können nun Ihren Account selbst unter "Profil editieren" löschen
	New: Menüboxen wie Lastwars, Nextwars usw. wurden nun in den Ordner inc/menu-functions/ zu besseren 
	     modifikation ausgelagert. Es werden von nun an auch nur die Funktionen in die bbcode geladen, 
	     welche auch als Platzhalter in der index.html vorhanden sind. Dies beschleunigt das CMS nochmals zusätzlich.
	New: Teamspeak3 viewer
	Bugfix: falsche Javascriptdefinitionen in der Forensuche
	ReNew: prototype.js / lightbox.js ersetzt durch jQuery + plugins (dadurch halbiert sich die Dateigröße der JS-Dateien)
	Bugfix: Interne Links konnten von unregistrierten und normalen Usern im Menu gesehen werden
	Bugfix: PNG-Dateien auch bei Clanwarscreenshots
	Bugfix: User konnten sich mit leeren Anmeldedaten anmelden (Leerzeichen im Login- / Usernamen)
	Bugfix: Bei manchen Webservern wurde der eingegebene Antispam-Code nicht erkannt
	Bugfix: Membermap wurde nicht angezeigt, wenn mind. 1 Member Anführungszeichen im Nicknamen hatte (" bzw. ')
	Bugfix: Teams anlegen / editieren: Editorbox sprengt Content
	Bugfix: Undefiniertes Javascript bei der Registrierung
	Bugfix: Beiträge im Forum von unregistrierten Usern / Gästen konnten nicht editiert werden
	Bugfix: fehlendes onsubmit event bei den Löschbuttons für Posteingang / -ausgang 
	New: Download von Gameserver Map-Screenshots im Adminmenu zum jeweiligen Server
	Bugfix: Rechtevergabe über Userränge funktionierte bei Formularen (Kontakt, JoinUs, FightUs) nicht
	Bugfix: Formular (Kontakt, JoinUs, FightUs) wurde ggf. mehrmals an einen User geschickt
	New: Menukategorien im Adminmenu verwaltbar
	New: Gameserverstatus (Definitionen) für Left4Dead 1 & 2 und Tactical Operations Crossfire
	New: Im Auswahlfeld für die Gameserver Live-Status werden nun auch die Mods angezeigt,
	     sofern diese zusammengefasst sind (z.B. Halflife 1 (CS 1.6, CS:CZ, etc))
	New: "Zurück" - Button in der Teamansicht
	Bugfix: Bei Andwendung des Forum-Doppelpost beim Thread erstellen wurden die Informationen "Letzter Beitrag von"
		in der Forenansicht zurückgesetzt
	Bugfix: Im TinyMCE wurden Smileys & Flaggen im IE immer am Anfang des Posts platziert
	Bugfix: Im TinyMCE wurden bei den Smileys im IE keine Scrollbalken angezeigt (bei meheren Smileys)
	New: Stautsmeldungen können ausgeblendet werden für eine direkte Weiterleitung
	New: Cachefunktion für Teamspeak- und Gameserverabfragen
	New: Reset Button für Clanwar Spielerstatus (kann spielen / vielleicht / etc)
	Bugfix: Wenn man in der Clanwarübersicht auf einen Gegner geklickt hat, öffnete sich der Inhalt in einer neuen Seite
	New: Forenthreads können einzeln angezeigt bzw. darauf verlinkt werden
	Bugfix: unveröffentlichte News hatte in der Newsansicht Fehlermeldungen bei dem Datum
	Bugfix: unveröffentlichte News wurden in den RSS-Feed eingetragen
	Bugfix: Auf Unix-Servern konnte der Dateimanager nicht mit Umlauten im Dateinamen umgehen
	New: Gameserver, die nicht erreichbar sind, werden als Offline angezeigt, mit einem separatem Offline-Bild


	1. Update automatisch
	================================

	Wenn du keine Modifikationen vorgenommen hast, kannst du einfach folgende Datei hochladen 
	und gegebenfals ersetzten:


	Geänderte bzw. neu hinzugefügte Zeilen in folgenden PHP Dateien: 
	
	- antispam.php
	- admin/menu.js
	- admin/menu/config.php
	- admin/menu/cw.php
	- admin/menu/editor.php
	- admin/menu/navi.php
	- admin/menu/newsadmin.php
	- admin/menu/server.php
	- clanwars/index.php
	- contact/index.php
	- forum/index.php
	- gb/index.php
	- inc/ajax.php
	- inc/bbcode.php
	- inc/buffer.php
	- inc/config.php
	- inc/lang/languages/deutsch.php
	- inc/lang/languages/english.php
	- inc/lang/global.php
	- inc/tinymce/plugins/dzcp/jscripts/smileys.js
	- inc/secure.php
	- inc/teamspeak_query.php
	- membermap/index.php
	- news/index.php
	- search/index.php
	- server/index.php
	- squads/index.php
	- teamspeak/index.php
	- teamspeak/login.php
	- user/index.php
	- votes/index.php
	

	Geänderte Template Dateien: 
	
	- _css/stylesheet.css
	- _js/dzcp.js
	- _js/lib.js
	- _js/lightbox.js
	- admin/form_config.html
	- admin/form_cw.html
	- admin/form_editor.html
	- admin/news_form.html
	- admin/form_links.html
	- admin/form_navi.html
	- admin/form_navi_edit.html
	- admin/form_navi_kats.html
	- admin/navi.html
	- admin/navi_kats.html
	- admin/navi_show.html
	- admin/server.html
	- admin/server_show.html
	- admin/squads_add.html
	- admin/squads_edit.html
	- clanwars/players.html
	- menu/teamspeak.html
	- squads/squads_full.html	
	- teamspeak/userstats.html
	- user/admin.html
	- user/edit_profil.html
	- user/msg.html
	- user/register.html
	

	Hinzugefügte Ordner und Dateien:
	
	- __cache/
	- inc/additional-languages/deutsch/
	- inc/additional-languages/english/
	- inc/additional-languages/readme.txt
	- inc/menu-functions/*.php (alle)
	- inc/images/tsicons (alle)

	Hinzugefügte Templatedateien:

	- menu/nav_link.html
	- page/button_delete_account.html

	
	Platzhalter in der index.html umbenennen

	- [umenu] in [nav_user]
	- [tmenu] in [nav_trial]
	- [mmenu] in [nav_member]
	- [amenu] in [nav_admin]


	CSS-Datei aktualisieren (~/_css/stylesheet.css):
	
	Aus der CSS-Datei sind die Zeilen 1360 - 1450 zu übernehmen, sonst funktioniert die neue Lightbox nicht.
	

	Datenbank updaten:
	
	Bitte rufe nachdem du alle Dateien ausgetauscht hast einmal das Updatescript mittels folgender URL auf und folge den Anweisungen.
	
	http://www.Deine-Domain.de/_installer/update.php

	Nach erfolgreichem Update bitte unbedingt den Ordner _installer/ vom Webspace löschen


	2. Update manuell
	================================

	Wenn bereits Modifikationen gemacht wurden emfpiehlt es sich das Update manuell durchzuführen.
	Hierzu mit einem Editor die unten stehenden Dateien aufrufen und von oben an die angegebenen Zeile austauschen.
	Bitte immer die kompletten Zeilen austauschen.
	
	Solltest du mit dem Update nicht klar kommen, kannst du gern unseren Updateservive in anspruch nehmen.
	Den Link zum Updateservice findest du auf www.dzcp.de in der rechten Navigation.

		
	Geänderte bzw. neu hinzugefügte Zeilen in folgenden PHP Dateien: 
	
	- antispam.php				Zeile: 14
	- admin/menu.js				Zeilen: 88, 113, 212 - 213, 222 - 231, 235 - 266
	- admin/menu/config.php			Zeilen: 101, 211, 422, 423, 427
	- admin/menu/cw.php  			Zeilen: 223 - 291, 334 - 442
	- admin/menu/editor.php			komplett tauschen
	- admin/menu/navi.php			komplett tauschen
	- admin/menu/newsadmin.php		komplett tauschen
	- admin/menu/server.php			komplett tauschen
	- contact/index.php			Zeilen: 109 - 135, 160 - 186, 231 - 259
	- clanwars/index.php			Zeilen: 595, 619, 639 - 647, 1079, 1189 - 1195
	- forum/index.php			komplett tauschen
	- gb/index.php				Zeilen: 23 - 26, 62
	- inc/ajax.php				komplett tauschen
	- inc/bbcode.php			komplett tauschen
	- inc/buffer.php			komplett tauschen
	- inc/config.php			Zeile: 61
	- inc/lang/languages/deutsch.php	Zeilen: 4 - 42
	- inc/lang/languages/english.php	Zeilen: 4 - 42
	- inc/lang/global.php			Zeilen: 2 - 6
	- inc/antispam.php			Zeile: 12
	- inc/teamspeak_query.php		komplett tauschen
	- news/index.php			Zeilen: 24, 128, 434, 750, 753, 767 - 768, 780 
	- membermap/index.php			Zeilen: 41, 59, 68 - 69
	- search/index.php			Zeilen: 48, 64
	- server/index.php			komplett tauschen
	- squads/index.php			Zeile: 97
	- teamspeak/index.php			komplett tauschen
	- teamspeak/login.php			komplett tauschen
	- user/index.php			Zeilen: 28, 218, 432 - 439, 446, 504 - 513, 519, 530, 684, 700, 
						1692 - 1764, 1943 - 1947, 2016 - 2017
	- votes/index.php			Zeilen: 283, 291 - 297


	Geänderte bzw. neu hinzugefügte Zeilen in folgenden Template Dateien: 

	- _css/stylesheet.css			Zeilen: 1363 - 1450, 1029
	- _js/dzcp.js				komplett tauschen
	- _js/lightbox.js			komplett tauschen (Dummy-Datei, ohne Funktion)
	- _js/lib.js				komplett tauschen
	- admin/form_config.html		Zeilen: 195 - 203
	- admin/form_editor.html		komplett tauschen
	- admin/form_links.html			Zeilen: 44 - 47
	- admin/form_navi.html			komplett tauschen
	- admin/form_navi_edit.html		komplett tauschen
	- admin/form_navi_kats.html		komplett tauschen
	- admin/navi.html			komplett tauschen
	- admin/navi_kats.html			komplett tauschen
	- admin/navi_show.html			komplett tauschen
	- admin/news_form.html			Zeilen: 106 - 112
	- admin/server.html			komplett tauschen
	- admin/server_show.html		komplett tauschen
	- admin/squads_add.html			Zeile: 33
	- admin/squads_edit.html		Zeile: 33
	- clanwars/players.html			komplett tauschen
	- menu/teamspeak.html			komplett tauschen
	- squads/squads_full.html		Zeilen: 6 - 12
	- teamspeak/userstats.html		komplett tauschen
	- user/admin.html			Zeilen: 93 - 94
	- user/edit_profil.html			Zeilen: 14 - 19, 119 - 124
	- user/msg.html				Zeilen: 25, 28, 43, 46
	- user/register.html		 	Zeilen: 128 - 134 entfernen

	Hinzugefügte Ordner und Dateien:

	- inc/additional-languages/deutsch/
	- inc/additional-languages/english/
	- inc/additional-languages/readme.txt
	- inc/menu-functions/*.php (alle)
	- inc/images/tsicons (alle)

	Hinzugefügte Templatedateien:

	- menu/nav_link.html
	- page/button_delete_account.html
	
	Platzhalter in der index.html umbenennen

	- [umenu] in [nav_user]
	- [tmenu] in [nav_trial]
	- [mmenu] in [nav_member]
	- [amenu] in [nav_admin]


	CSS-Datei editieren (_css/stylesheet.css):

	Suchen nach...
	-----------------------

		textarea.editorStyleMini {
  		  height: 100px;
		}

	Ändern in...
	----------------------

		textarea.editorStyleMini {
  		  height: 100px; width: 300px;
		}

	
	Ebenso sind aus der CSS-Datei sind die Zeilen 1360 - 1450 zu übernehmen, sonst funktioniert die neue Lightbox nicht.


	Datenbank updaten:
	
	Bitte rufe nachdem du alle Dateien ausgetauscht und verändert hast einmal das Updatescript mittels 
	folgender URL auf und folge den Anweisungen.
	
	http://www.Deine-Domain.de/_installer/update.php
	
	Nach erfolgreichem Update bitte unbedingt den Ordner _installer/ vom Webspace löschen
