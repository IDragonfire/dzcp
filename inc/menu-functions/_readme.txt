Menüboxen wie Lastwars, Nextwars usw. wurden nun in diedem Ordner (inc/menu-functions/) zu besseren modifikation ausgelagert. 
Es werden von nun an auch nur die Funktionen in die bbcode geladen, welche auch als Platzhalter in der index.html vorhanden sind. 
Dies beschleunigt das CMS nochmals zusätzlich.

Wichtig!

Der Dateiname und die Funktion selbst müssen den gleichen Namen haben, wie der Platzhalter aus der index.html.

Beispiel:

Die Box Last Wars hat den Platzhalter namen [l_wars]
Demnach muss auch der Dateinamen l_wars.php heißen.
Auch die Funktion selbst muss ebenfals l_wars() heißen.

Codeauszug:

<?php
function l_wars()
{
	//->Code
}
?>


######################################################################################################################################

Menu boxes such as last wars, next wars, etc have been outsourced to the folder (inc/menu-functions/) for better modification works. 
Now only the functions will be dynamically loaded to the bbcode.php, which placeholders are defined in the index.html. 
This will boost the CMS additionally! 

Important! 

The file's name and the name of the function have to be the same name as the place holder in the index.html.  
  
Example: 
  
The box "last wars" have a placeholder namend [l_wars] 
So the file name must be "l_wars.php". 
The function itself must l_wars() called, too. 
  
Code snippet:

<?php
function l_wars()
{
	//->Code
}
?>