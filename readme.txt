########################################
# deV!L`z Clanportal - kurz 'DZCP'     #
# =====================================#
# www.dzcp.de                          #
########################################


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
        -sicheres automatisches generiertes Passwort #1
        -TinyMCE mit neuem FileUploader + Smartphone tauglich #4 #54
        -Server-Queries  #10
        -Membermap (new Google Maps API) #16 #46
        -Infobox Update #20 #21 #76
        -JQuery #28 #29
        -Lightbox #28
        -News nach Kategorien anzeigen #35
        -Mapdownload (Gameserver) entfernt (Service wurde eingestellt)  #37
        -TS3 Update (support customs icons) #68
        -manuell sortieren der Sub-Forumkategorien #71
        -valid html template #80
        -auto URL Erkennung #82
        -Admin - Navigation nach Kategorie geordnet #108
        -Teamspeak IP ohne Port #114
        -Profilfelderverwaltung k�nnen Profilfelder ausgeblendet werden #119
        -Filter in der Admin Clanwars�bersicht hinzugef�gt # 123 # 124
        -Avatar Upload beim Adminmen� Userhinzuf�gen hinzugef�gt #204
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
	-Error-Report wurde hinzugef�gt und kann �ber die config.php aktiviert / deaktiviert werden
	-Mouseover wurde f�r die EventBox hinzugef�gt
	-[b1] und [b2] wurden entfernt und durch <logged_in></logged_in> und <logged_out></logged_out> ersetzt
	-Gametiger wurde entfernt 
	-Add multiple Root Adminstrator Support  
	

        Bugfixes
        --------

        -unver�ffentlichte Artikel kann man nicht ansehen #2
        -highlight in der Forum Suche wird nicht ressetet #5
        -Man kann als Gast / unregistrierte User Kommentare abgeben #7 #23 #30
        -Serverviewer f�r MOHAA, SH und BT Fix #8
        -Loginname / Username mit Sonderzeichen nicht m�glich #9
        -Datum letzter Download wird nicht richtig aktualisiert #13
        -gel�schte Forenposts werden nicht zur�ckgerechnet #14
        -Nachtragsfunktion wird nicht in der Userlobby angezeigt #15
        -JoinUs Benachrichtigung fehlerhaft #18
        -Galerie Bilder wurden nicht richtig verkleinert und waren zu gro� #32
        -additional languages werden vor additional functions geladen #33
        -Glossarw�rter zerrei�en Text (aus Interesse wird z.b. INT eresse) #34
        -Newsletter interne Links werden nicht richtig kodiert #36
        -Bug beim Ajax Vote
        -Nick in der Shoutbox wird nicht gek�rzt #55
        -G�stebuch Homepage-Links werden gek�rzt #57
        -Gro�geschriebener bbcode ohne Funktion #58
        -Bilder wurden nicht immer verkleinert (auto resized) #60
        -Server Passwort war sichtbar #53 #61 #62
        -FightUs Benachrichtigung fehlerhaft #63
        -interne Votes werden public nach Editierung #65
        -inaktive Squads kann man keine Awards vergeben #66 #67
        -Event Links werden nicht richtig erkannt #66
        -Fehlende defines in Sprachdatei #75
        -Men� f�r Banneradresse hat sich nicht ge�ffnet #85
        -Adminmen� war nicht immer f�r alle Berechtigten sichtbar #90
        -Infomeldungen werden 5 anstatt 2 Sekunden angezeigt #91
        -Emailvorlagen Probleme mit Sonderzeichen #92
        -unver�ffentliche Artikel / News k�nnen von unregistrierten Usern eingesehn werden #105
        -Threadersteller wird nicht als Top Poster gez�hlt
        -bestimmte Zeichen wurden in der Datenbank doppelt codiert #120
        -editprofile BUG #121 #122
        -Backslashes are not supported #125
        -Fix Privatnachrichten (ErrorNo = 1364) #130
        -Loginname wurde nicht richtig formatiert beim Editieren #139
        -TS3 funktionieren keine Sonderzeichen #165
        -Seitenaufteilung: 0 als Wert #166
        -Newskatimage / Galeriebilder werden bei langen Filenamen nicht angezeigt # 198 # 205
        -Forumsuche - internen Threads werden nicht in die suche mit einbezogen #212
        -Sch�nheitsfehler in den Formularen #201
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


