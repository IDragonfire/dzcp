Um einen neuen Menu punkt im Adminmenu hinzuzuf�gen, m�sst ihr in diesem Ordner zwei neue Dateien erstellen, mit mindestens folgendem Inhalt:

Die erste Datei euredatei.php

<?php
if(_adminMenu != 'true') exit;

Euer PHP Code......


Die zweite Datei euredatei.xml

<?xml version="1.0"?>
<settings>
	<Menu>settingsmenu</Menu>
	<Rights>editusers</Rights>
	<Only_Admin>0</Only_Admin>
	<Only_Root>0</Only_Root>
</settings>


In der xml Datei wird jeweils die Typenkategorie (Adminmenu, Einstellungen, Content) und die ben�tigten Rechte festgelegt, welche dann automatisch ausgelesen werden.

In diesem Beispiel ben�tigt man Newsrechte, damit der Unterpunkt im Bereich "Einstellungen" angezeigt wird.
Je nach Dateiname wird dann auch die Definition f�r die Sprachdatei erstellt, welche dann noch definiert werden muss.


Als "Menu" kann man folgende Bereiche festlegen:

rootmenu	-> "Seitenadmin"
settingsmenu	-> "Einstellungen"
contentmenu	-> "Content"

Als Rechte "Rights" k�nnen jegliche Rechte abgefragt werden, z.B.:

news

Der Punkt "Only_Admin" steht auf '1' oder '0', wird es auf '1' gestellt k�nnen nur Admins mit dem Level 4 das Men� sehen und betreten.

Der Punkt "Only_Root" steht auf '1' oder '0', wird es auf '1' gestellt k�nnen nur der Root Admin das Men� sehen und betreten.

Am besten mal durch die vorhandenen Dateien durchbl�ttern, dann sollte es eigentlich recht einfach zu verstehen sein.
