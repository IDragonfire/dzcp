<?php
define('_true', '<img src="img/true.gif" border="0" alt="" vspace="0" align="center"> ');
define('_false', '<img src="img/false.gif" border="0" alt="" vspace="0" align="center"> ');
define('version_input', "<tr><td><table class=\"info\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr>
<td width=\"0\" height=\"0\" valign=\"middle\"><input type=\"radio\" [disabled] [checked] name=\"version\" id=\"version\" value=\"[version_num]\" /> DZCP-[version_num_view]</td></tr></table></td></tr>");
define('_step', '<td></td><td>[text]</td></tr>');

define('_link_start', '<font class="enabled">&raquo; Lizenz</font>');
define('_link_start_1', '<font class="disabled">1. Lizenz</font>');

define('_link_type', '<font class="enabled">&raquo; Setup Type</font>');
define('_link_type_1', '<font class="disabled">2. Setup Type</font>');

define('_link_prepare', '<font class="enabled">&raquo; Vorbereitung</font>');
define('_link_prepare_1', '<font class="disabled">3. Vorbereitung</font>');

define('_link_mysql', '<font class="enabled">&raquo; MySQL</font>');
define('_link_mysql_1', '<font class="disabled">4. MySQL</font>');

define('_link_db', '<font class="enabled">&raquo; Speichern</font>');
define('_link_db_1', '<font class="disabled">5. Speichern</font>');

define('_link_update', '<font class="enabled">&raquo; Update</font>');
define('_link_update_1', '<font class="disabled">6. Update</font>');

define('_link_adminacc', '<font class="enabled">&raquo; Adminaccount</font>');
define('_link_adminacc_1', '<font class="disabled">6. Adminaccount</font>');

define('_link_done', '<font class="enabled">&raquo; Fertig</font>');
define('_link_done_1', '<font class="disabled">7. Fertig</font>');

//Texte
define('_error', 'Fehler');
define('_successful', 'Erfolgreich');
define('_warn', 'Hinweis');
define('prepare_no_ftp', 'Ihr Webserver unterst&uuml;tz eine der Funktionen <i>ftp_connect()</i>, <i>ftp_login()</i> oder <i>ftp_site()</i> nicht!
                               Diese sind jedoch notwendig um eine automatische Rechtevergabe der Dateien durchzuf&uuml;hren. Bitte aktiviere Sie diese oder setzen Sie manuell mittels
                               FTP-Client die notwendigen Rechte und aktualisieren Sie die Seite.');
define('prepare_no_ftp_connect', 'Der angegeben FTP-Host ist nicht erreichbar! Bitte &uuml;berpr&uuml;fen Sie ihre Eingaben oder setzen die Dateirechte manuell per FTP-Client.');
define('prepare_no_ftp_login', 'Der angegeben Login-Daten wurden zur&uuml;ckgewiesen! Bitte &uuml;berpr&uuml;fen Sie ihre Eingaben oder setzen die Dateirechte manuell per FTP-Client.');
define('prepare_files_error', 'Nicht alle notwendigen Dateirechte sind gesetzt, bitte verwenden Sie unsere "Automatische Rechtevergabe" oder setzen Sie manuell mittels FTP-Client die notwendigen Rechte und aktualisieren Sie die Seite');
define('no_webmail', 'Sie haben keine Page E-Mail Adresse eingetragen!<br />&Uuml;berpr&uuml;fen Sie ihre Eingaben und wiederholen Sie den Vorgang.');
define('no_username', 'Sie haben keinen Usernamen eingegeben!<br />&Uuml;berpr&uuml;fen Sie ihre Eingaben und wiederholen Sie den Vorgang.');
define('no_pwd', 'Sie haben kein Passwort eingegeben!<br />&Uuml;berpr&uuml;fen Sie ihre Eingaben und wiederholen Sie den Vorgang.');
define('no_nick', 'Sie haben keinen Nicknamen eingegeben!<br />&Uuml;berpr&uuml;fen Sie ihre Eingaben und wiederholen Sie den Vorgang.');
define('no_email', 'Sie haben keine E-Mail Adresse eingetragen!<br />&Uuml;berpr&uuml;fen Sie ihre Eingaben und wiederholen Sie den Vorgang.');
define('no_clanname', 'Sie haben keinen Clannamen eingetragen!<br />&Uuml;berpr&uuml;fen Sie ihre Eingaben und wiederholen Sie den Vorgang.');
define('mysql_no_prefix', 'Der SQL-Prefix muss angegeben werden!');
define('mysql_no_login', 'Es konnte keine Verbindung zur Datenbank aufgebaut werden!<br />&Uuml;berpr&uuml;fen Sie User und Passwort!');
define('mysql_no_db', 'Die angegebene Datenbank konnte nicht gefunden werden!<br />&Uuml;berpr&uuml;fen Sie den eingegebenen Datenbanknamen!');
define('mysql_no_con_server', 'Es konnte keine Verbindung zur Datenbank aufgebaut werden!<br />&Uuml;berpr&uuml;fen Sie Host und Port des Servers.');
define('mysql_ok', 'Die MySQL-Verbindung wurde erfolgreich getestet!<br />Klicken Sie nun auf \'Weiter\'.');
define('mysql_no_ndb', 'Der MySQL Server ist kein Cluster!<br />Die NDB Engine kann nur auf einem MySQL Cluster verwendet werden.<br /><br />Sehe <a href="http://www.mysql.de/products/cluster/" target="_blank">MySQL Cluster</a>');
define('mysql_setup_saved', 'Die MySQL-Daten wurden erfolgreich gespeichert!<br />Klicken Sie auf weiter um mit der Datenbankinstallation zu beginnen!.');
define('prepare_files_success', 'Alle notwendigen Dateirechte sind gesetzt. Klicken Sie unten rechts auf Weiter um fortzufahren.');
define('saved_user', 'Die Datenbank Informationen wurden erfolgreich gespeichert!<br />Klicken Sie auf &quot;Weiter&quot;.');
define('no_db_update', 'Die Datenbank ist bereits aktuell, es ist kein Update deiner Datenbank notwendig.');
define('no_db_update_selected', 'Du musst die zuvor installierte Version von DZCP auswählen um mit dem Update zu beginnen!');
?>