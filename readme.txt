########################################
# deV!L`z Clanportal - kurz 'DZCP'     #
# =====================================#
# www.dzcp.de                          #
########################################


Installation
=============================
Die Installation gestaltet sich recht einfach.
Lade alle Dateien aus dem Archiv per FTP auf deinen Webserver und öffne anschließend in deinem Web-Browser das Installationsprogramm unter z.B. www.url.de/_installer/.
Folge hier den Anweisungen, die dich durch die Installationsroutine begleiten. 

Anschließend bitte unbedingt den Ordner _installer/ vom webspace löschen.


Update von 1.5.5.4 zu 1.6
=============================

	1. Info's
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
        -Mapdownload (Gameserver) entfernt da dieser Service eingestellt wurde #37
        -TS3 Update (support customs icons) #68
        -Sub-Forumkategorien kann man manuell sortieren #71
        -valid html template #80
        -auto URL Erkennung #82
        -Navigation nach Kategorie ordnet #108
        -Teamspeak IP ohne Port #114
        -Profilfelderverwaltung können Profilfelder ausgeblendet werden #119
        -Filter in der Admin Clanwarsübersicht hinzugefügt # 123 # 124
        -Avatar Upload beim Adminmenü Userhinzufügen hinzugefügt #204 
        -Vorschaubilder in der Galerieübersicht wurde hinzugefügt
        -neues Template v1.6 ( Danke an esport Designs für das Design Template v1.6 ) #ec43082
        -Template version1.6 inkl. erweiterte Platzhalter für die Boxen (Boxentitel werden beim ändern der Sprache mit übersetzt)
        -Platzhalter [where] für die Ausgabe des Seitentitels innerhalb des Templates hinzugefügt
        -Dynamische Platzhalter [lang_xxxx] für die Ausgabe des beliebiger Definitionen aus der language file innerhalb des Templates hinzugefügt
	-Slideshow wurde hinzugefügt Platzhalter [slideshow] (wurde ins Template Version 1.5 in der index.html nicht mit übernommen)
        -Eigenes Newsbild kann als Alternative zum Kat.-Banner beim erstellen der News mit angegeben werden
        -Interne Galerie und Downloads
        -in mehrere Bereiche lassen sich die Inhalte Ordnen
        -Xfire würde in der Userlist durch Steam ersetzt und Skype hinzugefügt
        -Profil wurde um folgende Kontakte erweiter: Steam (Thanks Nitro), Skype, Xbox Live, Playstation Network, Origin, Battlenet


        Bugfixes
        --------
       
        -unveröffentlichte Artikel kann man nicht ansehen #2 
        -highlight in der Forum Suche wird nicht ressetet #5
        -Man kann als Gast / unregistrierter User Kommentare abgeben #7 #23 #30
        -Serverviewer für MOHAA, SH und BT Fix #8
        -Loginname / Username mit Sonderzeichen nicht möglich #9
        -Datum letzter Download wird nicht richtig aktualisiert #13
        -gelöschte Forenposts werden nicht zurückgerechnet #14
        -Nachtragsfunktion wird nicht in der Userlobby angezeigt #15
        -JoinUs Benachrichtigung fehlerhaft #18
        -Galerie Bilder wurden nicht richtig verkleinert und waren zu groß #32
        -additional languages werden vor additional functions geladen #33
        -Glossarwörter zerreißen Text (aus Interesse wird z.b. INT eresse) #34
        -Newsletter interne Links werden nicht richtig kodiert #36
        -Bug beim Ajax Vote
        -Nick in der Shoutbox wird nicht gekürzt #55
        -Gästebuch Homepage-Links werden gekürzt #57
        -Großgeschriebener bbcode ohne Funktion #58
        -Bilder wurden nicht immer verkleinert (auto resized) #60
        -Server Passwort war sichtbar #53 #61 #62
        -FightUs Benachrichtigung fehlerhaft #63
        -interne Votes werden public nach einer Editierung #65
        -inaktive Squads kann man keine Awards vergeben #66 #67
        -Event Links werden nicht richtig erkannt #66
        -Fehlende defines in Sprachdatei #75
        -Menü für Banneradresse hat sich nicht geöffnet #85
        -Adminmenü war nicht immer für alle Berechtigten sichtbar #90
        -Infomeldungen werden 5 anstatt 2 Sekunden angezeigt #91
        -Emailvorlagen hatten Probleme mit Sonderzeichen #92
        -unveröffentliche Artikel / News können von unregistrierten Usern eingesehn werden #105
        -Threadersteller wird nicht als Top Poster gezählt
        -bestimmte Zeichen wurden in der Datenbank doppelt codiert #120
        -editprofile BUG #121 #122
        -Backslashes are not supported #125
        -Fix Privatnachrichten (ErrorNo = 1364) #130
        -Loginname wurde nicht richtig formatiert beim Editieren #139
        -TS3 funktionieren keine Sonderzeichen #165
        -Seitenaufteilung: 0 als Wert #166
        -Newskatimage / Galeriebilder werden bei langen Filenamen nicht angezeigt # 198 # 205
        -Forumsuche - internen Threads werden nicht in die suche mit einbezogen #212
        -Schönheitsfehler in den Formularen #201 
        -Navigation Interne Seiten waren über Link für jeden aufrufbar
        -Glossarnavigation Alle ohne Funktion
        -mit Autologin würden Pagebesuche nicht gezählt
        -mehrere kleiner Optimierungen am Code
        -User IPs werden auch bei vorgeschaltete Proxyserver oder Load Balancer richtig erkannt


	2. Update automatisch
	================================
	
	Einfach alle Dateien austauschen.

        3. Update manuell
	================================
  
        Aufgrund der vielzahl an Änderungen, wird kein manuelles Update für diese Version aufgeführt.


	Datenbank updaten:
	
	Bitte rufe nachdem du alle Dateien ausgetauscht und verändert hast, AUSGENOMMEN der MYSQL.PHP (inc/mysql.php), einmal das Updatescript mittels 
	folgender URL auf und folge den Anweisungen.
	
	http://www.Deine-Domain.de/_installer/update.php
	
	Nach erfolgreichem Update bitte unbedingt den Ordner _installer/ vom Webspace löschen

        Thanks
	=======
        Wir bedanken uns bei allen die an der Fertigstellung der v1.6 mitgearbeitet haben.
        
        Dragonfire, Godkiller_NT(Hammermaps), xDGeForcexD, Hypernate, Koma, Lord Alpha (esport Design),  Makke,
        Acecom, Sk!ller ....