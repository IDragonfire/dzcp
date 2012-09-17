<?php
ob_start();
session_start();

define('basePath', dirname(dirname(__FILE__).'../'));

require_once(basePath.'/inc/_version.php');
require_once(basePath.'/inc/kernel.php');
require_once(basePath.'/_installer/conf/conf.php');
require_once(basePath.'/_installer/conf/mysql.php');

include(basePath.'/_installer/html/header.php');

if(!isset($_GET['action'])) $action = "";
else $action = $_GET['action'];
switch ($action):
default:
  if(!isset($_GET['agb']) || $_GET['agb'])
  {
    echo '<table width="100%" cellpadding="1" cellspacing="1" class="error">
            <tr>
              <td class="error_text"><b>Fehler:</b></td>
             </tr>
             <tr>
               <td class="error_text">Sie m&uuml;ssen die Lizenzbedingungen akzeptieren um deV!L`z Clanportal nutzen zu d&uuml;rfen!
              </td>
             </tr>
           </table>';
  }
  
  include(basePath.'/_installer/html/welcome.php');
break;
case 'prepare';
if(isset($_GET['agb']) && $_GET['agb'])
{
  header("Location: install.php?agb=false");
} else {
  if(isset($_GET['do']) && $_GET['do'] == "set_chmods" && $_POST['check'] != "dont")
  {
    if(_ex('ftp_connect') && _ex('ftp_login') && _ex('ftp_site'))
    {
      $host = $_POST['host'];
      $user = $_POST['user'];
      $pwd = $_POST['pwd'];
      $pfad = $_POST['pfad'];
      
      $conn = @ftp_connect($host);
      if(!$conn)
      {
        echo '<table width="100%" cellpadding="1" cellspacing="1" class="error">
                <tr>
                  <td class="error_text"><b>Fehler:</b></td>
                </tr>
                <tr>
                  <td class="error_text">Der angegeben FTP-Host ist nicht erreichbar! Bitte &uuml;berpr&uuml;fen Sie ihre Eingaben oder setzen die Dateirechte manuell per FTP-Client.
                  </td>
                </tr>
              </table>';

      } elseif(!@ftp_login($conn, $user, $pwd)) 
      {
        echo '<table width="100%" cellpadding="1" cellspacing="1" class="error">
                <tr>
                  <td class="error_text"><b>Fehler:</b></td>
                </tr>
                <tr>
                  <td class="error_text">Der angegeben Login-Daten wurden zur&uuml;ckgewiesen! Bitte &uuml;berpr&uuml;fen Sie ihre Eingaben oder setzen die Dateirechte manuell per FTP-Client.
                  </td>
                </tr>
              </table>';
      } else {
        _c('_installer',$pfad,$host,$user,$pwd);  
        _c('_installer/update.php',$pfad,$host,$user,$pwd);  
        _c('_installer/install.php',$pfad,$host,$user,$pwd);
        _c('inc/_cache',$pfad,$host,$user,$pwd);
        _c('rss.xml',$pfad,$host,$user,$pwd);
        _c('admin',$pfad,$host,$user,$pwd);
        _c('banner',$pfad,$host,$user,$pwd);
        _c('banner/partners',$pfad,$host,$user,$pwd);
        _c('banner/sponsors',$pfad,$host,$user,$pwd);
        _c('downloads',$pfad,$host,$user,$pwd);
        _c('gallery',$pfad,$host,$user,$pwd);
        _c('gallery/images',$pfad,$host,$user,$pwd);
        _c('server',$pfad,$host,$user,$pwd);
        _c('upload',$pfad,$host,$user,$pwd);
        _c('upload/index.php',$pfad,$host,$user,$pwd);
        _c('inc',$pfad,$host,$user,$pwd);
        _c('inc/images',$pfad,$host,$user,$pwd);
        _c('inc/images/tsicons/',$pfad,$host,$user,$pwd);
        _c('inc/images/tsicons/server/',$pfad,$host,$user,$pwd);
        _c('inc/images/clanwars',$pfad,$host,$user,$pwd);
        _c('inc/images/gameicons',$pfad,$host,$user,$pwd);
        _c('inc/images/maps',$pfad,$host,$user,$pwd);
        _c('inc/images/newskat',$pfad,$host,$user,$pwd);
        _c('inc/images/smileys',$pfad,$host,$user,$pwd);
        _c('inc/images/squads',$pfad,$host,$user,$pwd);
        _c('inc/images/uploads',$pfad,$host,$user,$pwd);
        _c('inc/images/uploads/taktiken',$pfad,$host,$user,$pwd);
        _c('inc/images/uploads/useravatare',$pfad,$host,$user,$pwd);
        _c('inc/images/uploads/usergallery',$pfad,$host,$user,$pwd);
        _c('inc/images/uploads/userpics',$pfad,$host,$user,$pwd);
        _c('inc/tinymce_files',$pfad,$host,$user,$pwd);
        _c('inc/tinymce/plugins/ajaxfilemanager/session',$pfad,$host,$user,$pwd);
        _c('inc/tinymce/plugins/ajaxfilemanager/session/gc_counter.ajax.php',$pfad,$host,$user,$pwd);
        _c('inc/mysql.php',$pfad,$host,$user,$pwd);
        _c('inc/config.php',$pfad,$host,$user,$pwd);
      }
    } else {
      echo '<table width="100%" cellpadding="1" cellspacing="1" class="error">
              <tr>
                <td class="error_text"><b>Fehler:</b></td>
              </tr>
              <tr>
                <td class="error_text">Ihr Webserver unterst&uuml;tz eine der Funktionen <i>ftp_connect()</i>, <i>ftp_login()</i> oder <i>ftp_site()</i> nicht!
                                       Diese sind jedoch notwendig um eine automatische Rechtevergabe der Dateien durchzuf&uuml;hren. Bitte setzen Sie manuell mittels
                                       FTP-Client die notwendigen Rechte und aktualisieren Sie die Seite.
                </td>
              </tr>
            </table>';
    }
  }
//Check Installfiles
  $cm = _i('../_installer',1);
  $cm .= _i('../_installer/install.php');
  $cm .= _i('../_installer/update.php');
//Check Scriptfiles
  $c = _i('../inc/_cache',1);
  $c .= _i('../rss.xml');
  $c .= _i('../admin',1);
  $c .= _i('../banner',1);
  $c .= _i('../banner/partners',1);
  $c .= _i('../gallery',1);
  $c .= _i('../gallery/images',1);
  $c .= _i('../server',1);
  $c .= _i('../upload',1);
  $c .= _i('../upload/index.php');
  $c .= _i('../inc',1);
  $c .= _i('../inc/images',1);
  $c .= _i('../inc/images/tsicons',1);
  $c .= _i('../inc/images/tsicons/server',1);
  $c .= _i('../inc/images/clanwars',1);
  $c .= _i('../inc/images/gameicons',1);
  $c .= _i('../inc/images/maps',1);
  $c .= _i('../inc/images/newskat',1);
  $c .= _i('../inc/images/smileys',1);
  $c .= _i('../inc/images/squads',1);
  $c .= _i('../inc/images/uploads',1);
  $c .= _i('../inc/images/uploads/taktiken',1);
  $c .= _i('../inc/images/uploads/useravatare',1);
  $c .= _i('../inc/images/uploads/usergallery',1);
  $c .= _i('../inc/images/uploads/userpics',1);
  $c .= _i('../inc/tinymce_files',1);
  $c .= _i('../inc/tinymce/plugins/ajaxfilemanager/session',1);
  $c .= _i('../inc/tinymce/plugins/ajaxfilemanager/session/gc_counter.ajax.php',1);
  $c .= _i('../inc/mysql.php',1);
  $c .= _i('../inc/config.php',1);
  
  $check = preg_match("#false#Uis",$c);
  
  if($check == FALSE)
  {
    echo '<table width="100%" cellpadding="1" cellspacing="1" class="done">
            <tr>
              <td class="error_text"><b>Done!</b></td>
            </tr>
            <tr>
              <td class="error_text">Alle notwendigen Dateirechte sind gesetzt. Klicken Sie unten rechts auf Weiter um fortzufahren.
              </td>
            </tr>
          </table>';
    $formcheck = "dont";
  }

  include(basePath.'/_installer/html/prepare.php');

  echo '<table width="100%" cellpadding="3" cellspacing="1" class="emph">
          <tr>
            <td><b>Installationsdateien</b></td>
            <td><b>Scriptdateien</b></td>
          </tr>
          <tr>
            <td valign="top">'.$cm.'</td>
            <td>'.$c.'</td>
          </tr>';
            
  include(basePath.'/_installer/html/prepare_ftp.php');
  
  if($check == FALSE)
  {
    echo '<table width="100%" cellpadding="1" cellspacing="1">
            <tr>
              <td align="right"><a href="install.php?action=install">&raquo; Weiter</a></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </table>';
  }
}
break;
case'install';
  if(isset($_GET['do']) && $_GET['do'] == "test_mysql")
  {
	$sql=false;
	
//-> zur Datenbank connecten
	if(!empty($_POST['host']) && !empty($_POST['user']) && !empty($_POST['database']))
	{
		$con = @mysql_connect($_POST['host'], $_POST['user'], $_POST['pwd']);
		$sel = @mysql_select_db($_POST['database'],$con);
		$sql = true;
	}
//-> MySQL-Daten testen
	if(!$sql)
    {
      echo '<table width="100%" cellpadding="1" cellspacing="1" class="error">
              <tr>
                <td class="error_text"><b>Fehler:</b></td>
              </tr>
              <tr>
                <td class="error_text">MySQL Angaben unvollstndig!<br />
                &Uuml;berpr&uuml;fen Sie die eingegebenen Verbindungsdaten! 
                </td>
              </tr>
            </table>';
    }	
    else if(!$con)
    { 
      echo '<table width="100%" cellpadding="1" cellspacing="1" class="error">
              <tr>
                <td class="error_text"><b>Fehler:</b></td>
              </tr>
              <tr>
                <td class="error_text">Es konnte keine Verbindung zur Datenbank aufgebaut werden! <br />
                &Uuml;berpr&uuml;fen Sie die eingegeben Daten von Host, User und dem Passwort!
                </td>
              </tr>
            </table>';
    } 
	elseif(!$sel) 
    {
      echo '<table width="100%" cellpadding="1" cellspacing="1" class="error">
              <tr>
                <td class="error_text"><b>Fehler:</b></td>
              </tr>
              <tr>
                <td class="error_text">Die angegebene Datenbank konnte nicht gefunden werden!<br />
                &Uuml;berpr&uuml;fen Sie den eingegebenen Datenbanknamen! 
                </td>
              </tr>
            </table>';
    }
    
    if(!$sel || !$con || !$sql)
    {
      include(basePath.'/_installer/html/mysql.php');
      $prefix = $_POST['prefix'];
      $host = $_POST['host'];
      $user = $_POST['user'];
      $pwd = $_POST['pwd'];
      $database = $_POST['database'];
    
      include(basePath.'/_installer/html/mysql_data.php');
    } 
	else 
	{
      echo '<table width="100%" cellpadding="1" cellspacing="1" class="done">
              <tr>
                <td class="error_text"><b>Done!</b></td>
              </tr>
              <tr>
                <td class="error_text">Die MySQL-Verbindung wurde erfolgreich getestet!<br />
                Klicken Sie nun auf \'MySQL-Daten abspeichern\'.</td>
              </tr>
            </table>';
            
      include(basePath.'/_installer/html/mysql.php');
      
      echo '<table width="100%" cellpadding="1" cellspacing="1">
              <tr>
                <td>&nbsp;</td>
              </tr>
            <form action="install.php?action=install&amp;do=write_mysql" method="POST">
              <input type="hidden" name="prefix" value="'.$_POST['prefix'].'">  
              <input type="hidden" name="host" value="'.$_POST['host'].'">  
              <input type="hidden" name="user" value="'.$_POST['user'].'">  
              <input type="hidden" name="pwd" value="'.$_POST['pwd'].'">
              <input type="hidden" name="database" value="'.$_POST['database'].'">    
              <tr>
                <td align="center"><input style="width:210px;" type="submit" value="MySQL-Daten abspeichern!"></td>
              </tr>
            </form>
            </table>';
    }
  } 
  elseif(isset($_GET['do']) && $_GET['do'] == "write_mysql")
  {
//-> MySQL-Daten in mysql.php schreiben
    if(_ex("fopen")) 
    {
      _m ($_POST['prefix'], $_POST['host'], $_POST['user'], $_POST['pwd'], $_POST['database']);
      
      echo '<table width="100%" cellpadding="1" cellspacing="1" class="done">
              <tr>
                <td class="error_text"><b>Done!</b></td>
              </tr>
              <tr>
                <td class="error_text">Die MySQL-Daten wurden erfolgreich gespeichert!<br />
                Klicken Sie auf weiter um mit der Datenbankinstallation zu beginnen!.</td>
              </tr>
            </table>';
            
      
      include (basePath.'/_installer/html/mysql.php');
      echo '<table width="100%" cellpadding="3" cellspacing="1">
              <tr>
                <td height="25"></td>
              </tr>
              <tr>
                <td align="right"><a href="install.php?action=database">&raquo; Weiter</a></td>
              </tr>
            </table>';
    } else {
      echo '<table width="100%" cellpadding="1" cellspacing="1" class="error">
              <tr>
                <td class="error_text"><b>Fehler:</b></td>
              </tr>
              <tr>
                <td class="error_text">Dein Webserver erlaubt die Funktion <i>fopen()</i> nicht!<br />
                Befolgen Sie nun den Abschnitt \'MySQL-Daten manuell speichern\'!
                </td>
              </tr>
            </table>';
            
      include (basePath.'/_installer/html/mysql.php');
      
      echo '<table width="100%" cellpadding="3" cellspacing="1">
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td class="head">&raquo; MySQL-Datenbank manuell speichern</td>
              </tr>
              <tr>
                <td>&Ouml;ffnen Sie nun die Datei <b>/inc/mysql.php</b> in einem Text-Editor Ihrer Wahl.<br />
                Ersetzen Sie nun die den dort befindlichen Code mit folgenden:</td>
              </tr>
            </table>';
            
      echo '<table width="100%" cellpadding="3" cellspacing="1" class="emph">
              <tr>
                <td height="20"></td>
              </tr>
              <tr>
                <td width="100"></td>
                <td class="php">
<textarea cols="60" rows="7"  onfocus="this.select()" style="overflow:hidden;">
<?php
  $sql_prefix = \''.$_POST['prefix'].'\';
  $sql_host = \''.$_POST['host'].'\';
  $sql_user =  \''.$_POST['user'].'\';
  $sql_pass = \''.$_POST['pwd'].'\';
  $sql_db = \''.$_POST['database'].'\';
?></textarea>  
                </td>
              </tr>
              <tr>
                <td height="20"></td>
              </tr>
            </table>';
            
      echo '<table width="100%" cellpadding="3" cellspacing="1">
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>Speichere anschlie&szlig;end die Datei ab und klicke <u>erst dann</u> auf Weiter um die Datenbankinstallation zu bgeinnen!</td>
              </tr>
            </table>';
            
      echo '<table width="100%" cellpadding="3" cellspacing="1">
              <tr>
                <td height="25"></td>
              </tr>
              <tr>
                <td align="right"><a href="install.php?action=database">&raquo; Weiter</a></td>
              </tr>
            </table>';
    }
  } 
  else 
  {
    include(basePath.'/_installer/html/mysql.php');
    include(basePath.'/_installer/html/mysql_data.php');
  }
break;
case 'database';
  if(isset($_GET['do']) && $_GET['do'] == "install")
  {
    if($_POST['login'] && $_POST['nick'] && $_POST['pwd'] && $_POST['email'])
    {
        install_mysql($_POST['login'], $_POST['nick'], $_POST['pwd'], $_POST['email']);
        update_mysql_1_4();
        update_mysql_1_5();
        update_mysql_1_5_1();
        update_mysql_1_5_2();
        update_mysql_1_5_4();
        update_mysql_1_6();
      
      header("Location: install.php?action=done");
    } else {
      echo '<table width="100%" cellpadding="1" cellspacing="1" class="error">
              <tr>
                <td class="error_text"><b>Fehler:</b></td>
              </tr>
              <tr>
                <td class="error_text">Sie haben mindestens ein Feld nicht ausgef&uuml;llt oder die Datenbankverbindung wurde unterbrochen!
                </td>
              </tr>
            </table>';
            
      include(basePath.'/_installer/html/installation.php');
      include(basePath.'/_installer/html/installation_admin.php');
    }
  } else {
    include(basePath.'/_installer/html/installation.php');
    include(basePath.'/_installer/html/installation_admin.php');
  }
break;
case 'done';
  include(basePath.'/_installer/html/done.php');
break;
endswitch;
include(basePath.'/_installer/html/footer.php');
ob_end_flush();
?>