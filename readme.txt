########################################
# deV!L`z Clanportal - kurz 'DZCP'     #
# =====================================#
# www.dzcp.de                          #
########################################


 ______     ______  ______   ______     __   __      ____           ______      
/_____/\   /_____/\/_____/\ /_____/\   /_/\ /_/\    /___/\         /_____/\     
\:::_ \ \  \:::__\/\:::__\/ \:::_ \ \  \:\ \\ \ \   \_::\ \        \:::__\/     
 \:\ \ \ \    /: /  \:\ \  __\:(_) \ \  \:\ \\ \ \    \::\ \     ___\:\ \____   
  \:\ \ \ \  /::/___ \:\ \/_/\\: ___\/   \:\_/.:\ \   _\: \ \__ /__/\\::__::/\  
   \:\/.:| |/_:/____/\\:\_\ \ \\ \ \      \ ..::/ /  /__\: \__/\\::\ \\:\_\:\ \ 
    \____/_/\_______\/ \_____\/ \_\/       \___/_(   \________\/ \:_\/ \_____\/ 


1. Installation
===============
Die Installation gestaltet sich recht einfach.

Lade alle Dateien aus dem Archiv per FTP auf deinen Webserver und �ffne anschlie�end in deinem Web-Browser
das Installationsprogramm mit folgender URL auf.

http://www.Deine-Domain.de/_installer

Folge hier den Anweisungen, die dich durch die Installationsroutine begleiten.
Anschlie�end bitte unbedingt den Ordner _installer/ vom webspace l�schen und CHMOD der inc/mysql.php auf 644 setzen.

2. Update automatisch
=====================

Einfach alle Dateien austauschen und das Datenbank Updatescript ausf�hren.

Bitte rufe nachdem du alle Dateien ausgetauscht und ver�ndert hast einmal das Updatescript mittels
folgender URL auf und folge den Anweisungen.

http://www.Deine-Domain.de/_installer/update.php

Nach erfolgreichem Update bitte unbedingt den Ordner _installer/ vom Webspace l�schen

3. Update manuell
=================

Aufgrund der vielzahl an �nderungen, wird kein manuelles Update f�r diese Version aufgef�hrt.

4. Bugs, Updates und Neuerungen
================================

        Update / New
        ------------
        -sicheres automatisches generiertes Passwort
        -TinyMCE mit neuem FileUploader + Smartphone tauglich
        -Server-Queries hinzugefügt zbsp. Battlefield 4
        -Membermap (new Google Maps API)
        -Infobox Update
        -JQuery Upadte v1.11.0
        -Lightbox
        -News nach Kategorien anzeigen
        -Mapdownload (Gameserver) entfernt (Service wurde eingestellt)  
        -TS3 Update (support customs icons) 
        -manuell sortieren der Sub-Forumkategorien
        -valid html template 
        -auto URL Erkennung 
        -Admin - Navigation nach Kategorie geordnet 
        -Teamspeak IP ohne Port 
        -Profilfelderverwaltung k�nnen Profilfelder ausgeblendet werden 
        -Filter in der Admin Clanwars�bersicht hinzugef�gt 
        -Avatar Upload beim Adminmen� Userhinzuf�gen hinzugef�gt 
        -Vorschaubild in der Galerie�bersicht wurde hinzugef�gt
        -neues Template v1.6 ( Danke an esport Designs f�r das Design Template v1.6 )
        -Template version1.6 inkl. erweiterte Platzhalter f�r die Boxen (Boxentitel werden beim �ndern der Sprache mit �bersetzt)
        -Platzhalter [where] f�r die Ausgabe des Seitentitels innerhalb des Templates hinzugef�gt
        -Dynamische Platzhalter [lang_xxxx] f�r die Ausgabe beliebiger Definitionen aus der language file innerhalb des Templates hinzugef�gt
        -Slideshow wurde hinzugef�gt - Platzhalter [slideshow] (Template Version 1.5 wurde der Platzhalter Sildeshow nicht in die index.html �bernommen)
        -eigenes Newsbild kann als Alternative zum Kat.-Banner beim erstellen der News mit angegeben werden
        -Interne Galerie und Downloads
        -in mehrere Bereiche lassen sich die Inhalte Ordnen
        -Xfire-Icon w�rde in der Userlist durch Steam ersetzt und Skype hinzugef�gt
        -Profil wurde um folgende Kontakte erweiter: Steam (Thanks Tune389 & Nitro), Skype, Xbox Live, Playstation Network, Origin, Battlenet
        -Antispam Update
        -neuer Platzhalter [avatar] (siehe Template v1.6) dieser zeigt nach dem User-Login den zugeh�rige Avatar an
        -Option Anzeige Usergalerie im Profil wurde hinzugef�gt (�ffentlich / nur User / nur Member)
        -Option Userg�stebuch Posts Speeren / zulassen wurde hinzugef�gt
        -JoinUs Formular kann nun das Teams mit ausgew�hlt werden; Teams k�nnen selber entscheiden ob Sie im Formular mit aufgef�hrt werden m�chten
        -FightUs Formular kann nun das Teams mit ausgew�hlt werden; Teams k�nnen selber entscheiden ob Sie im Formular mit aufgef�hrt werden m�chten
	-Backupfunktion wurde �berarbeitet
        -MySQL Backend wurde gegen ein MySQLi Backend ausgetauscht um die Lauff�higkeit f�r Sp�tere PHP Versionen zu sichern
        -Der Newstricker in der DZCP Administration kann optional in der config.php abgeschaltet werden
        -Der alte DZCP Cache wurde gegen einen neuen ersetzt, dieser unterst�tzt jetzt Files,SQL-Lite, APC, Memcache, WinCache und XCache
        -Verschiedene Optimierungen und Ausbesserungen im PHP und HTML Code
	-Skype und Steam ID wurde als Formularfeld in FightUs, JoinUs und Konatkt hinzugef�gt
	-Error-Report wurde hinzugef�gt und kann �ber die config.php aktiviert / deaktiviert werden (logfils werden unter inc/_logs gespeichert)
	-Mouseover wurde f�r die EventBox hinzugef�gt
	-[b1] und [b2] wurden entfernt und durch <logged_in></logged_in> und <logged_out></logged_out> ersetzt
	-Gametiger wurde entfernt 
	-Add multiple Root Adminstrator Support  
	

        Bugfixes
        --------

        -unver�ffentlichte Artikel kann man nicht ansehen 
        -highlight in der Forum Suche wird nicht resetet 
        -Man kann als Gast / unregistrierte User Kommentare abgeben 
        -Serverviewer f�r MOHAA, SH und BT Fix 
        -Loginname / Username mit Sonderzeichen nicht m�glich 
        -Datum letzter Download wird nicht richtig aktualisiert 
        -gel�schte Forenposts werden nicht zur�ckgerechnet 
        -Nachtragsfunktion wird nicht in der Userlobby angezeigt 
        -JoinUs Benachrichtigung fehlerhaft 
        -Galerie Bilder wurden nicht richtig verkleinert und waren zu gro� 
        -additional languages werden vor additional functions geladen 
        -Glossarw�rter zerrei�en Text (aus Interesse wird z.b. INT eresse) 
        -Newsletter interne Links werden nicht richtig kodiert 
        -Bug beim Ajax Vote
        -Nick in der Shoutbox wird nicht gek�rzt 
        -G�stebuch Homepage-Links werden gek�rzt 
        -Gro�geschriebener bbcode ohne Funktion 
        -Bilder wurden nicht immer verkleinert (auto resized) 
        -Server Passwort war sichtbar 
        -FightUs Benachrichtigung fehlerhaft 
        -interne Votes werden public nach Editierung 
        -inaktive Squads kann man keine Awards vergeben
        -Event Links werden nicht richtig erkannt
        -Fehlende defines in Sprachdatei 
        -Men� f�r Banneradresse hat sich nicht ge�ffnet 
        -Adminmen� war nicht immer f�r alle Berechtigten sichtbar 
        -Infomeldungen werden 5 anstatt 2 Sekunden angezeigt
        -Emailvorlagen Probleme mit Sonderzeichen 
        -unver�ffentliche Artikel / News k�nnen von unregistrierten Usern eingesehn werden 
        -Threadersteller wird nicht als Top Poster gez�hlt
        -bestimmte Zeichen wurden in der Datenbank doppelt codiert 
        -editprofile BUG 
        -Backslashes are not supported 
        -Fix Privatnachrichten (ErrorNo = 1364) 
        -Loginname wurde nicht richtig formatiert beim Editieren
        -TS3 funktionieren keine Sonderzeichen 
        -Seitenaufteilung: 0 als Wert 
        -Newskatimage / Galeriebilder werden bei langen Filenamen nicht angezeigt 
        -Forumsuche - internen Threads werden nicht in die suche mit einbezogen 
        -Sch�nheitsfehler in den Formularen 
        -Navigation Interne Seiten waren �ber Link f�r jeden aufrufbar
        -Glossarnavigation Alle ohne Funktion
        -mit aktiven Autologin wurden Pagebesuche nicht gez�hlt
        -mehrere kleiner Optimierungen am Code
        -User IPs werden auch bei vorgeschaltete Proxyserver oder Load Balancer richtig erkannt
        -Smile Bug Fix TinyMC
        -E-Mail Fix HTML-Bug
	-Newstitel L�ngenfix
	-[clanname] wurde im Template nicht richtig ausgegeben
	-Online status von Usern wurde nicht richtig dargestellt (Online / Offline)
	-fehlerhafte Weiterleitung nach GameIcon Uploads Team

	



5. Thanks
=========

Wir bedanken uns bei allen die an der Fertigstellung der v1.6 mitgearbeitet haben.

Dragonfire, Godkiller_NT(Hammermaps), xDGeForcexD, Hypernate, Koma, Alper Cino (eSport-Designs.de),  Makke,
Tune389, Acecom, Sk!ller


