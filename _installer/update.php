<?php
ob_start();
session_start();

define('basePath', dirname(dirname(__FILE__).'../'));

require_once basePath.'/inc/mysql.php';
require_once(basePath.'/inc/_version.php');
require_once basePath.'/_installer/conf/conf.php';
require_once basePath.'/_installer/conf/mysql.php';

include(basePath.'/_installer/html/header_u.php');

if(!isset($_GET['action'])) $action = "";
else $action = $_GET['action'];
switch ($action):
default:
  if($_GET['agb'])
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
  
  include(basePath.'/_installer/html/welcome_u.php');
break;
case 'prepare';
if($_GET['agb'])
{
  header("Location: update.php?agb=false");
} else {
  if($_GET['do'] == "set_chmods" && $_POST['check'] != "dont")
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
        _c('__cache',$pfad,$host,$user,$pwd);
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
        _c('upload/',$pfad,$host,$user,$pwd);
        _c('inc',$pfad,$host,$user,$pwd);
        _c('inc/images',$pfad,$host,$user,$pwd);
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
  $cm .= _i('../_installer/update.php');
//Check Scriptfiles
  $c = _i('../__cache',1);
  $c .= _i('../rss.xml');
  $c .= _i('../admin',1);
  $c .= _i('../banner',1);
  $c .= _i('../banner/partners',1);
  $c .= _i('../downloads',1);
  $c .= _i('../gallery',1);
  $c .= _i('../gallery/images',1);
  $c .= _i('../server',1);
  $c .= _i('../upload',1);
  $c .= _i('../upload/index.php');
  $c .= _i('../inc',1);
  $c .= _i('../inc/images',1);
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
            
  include(basePath.'/_installer/html/prepare_ftp_u.php');
  
  if($check == FALSE)
  {
    echo '<table width="100%" cellpadding="1" cellspacing="1">
            <tr>
              <td align="right"><a href="update.php?action=install">&raquo; Weiter</a></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </table>';
  }
}
break;
case'install';

//-> zur Datenbank connecten
  $con = @mysql_connect($_POST['host'], $_POST['user'], $_POST['pwd']);
  $sel = @mysql_select_db($_POST['database'],$con);
    
  if($_GET['do'] == "test_mysql")
  {
//-> MySQL-Daten testen
    if(!$con)
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
    } elseif(!$sel) 
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
    
    if(!$sel || !$con)
    {
      include(basePath.'/_installer/html/mysql_u.php');
      $prefix = $_POST['prefix'];
      $host = $_POST['host'];
      $user = $_POST['user'];
      $pwd = $_POST['pwd'];
      $database = $_POST['database'];
    
      include(basePath.'/_installer/html/mysql_data_u.php');
    } else {
      echo '<table width="100%" cellpadding="1" cellspacing="1" class="done">
              <tr>
                <td class="error_text"><b>Done!</b></td>
              </tr>
              <tr>
                <td class="error_text">Die MySQL-Verbindung wurde erfolgreich getestet!<br />
                Klicken Sie nun auf \'MySQL-Daten abspeichern\'.</td>
              </tr>
            </table>';
            
      include(basePath.'/_installer/html/mysql_u.php');
      
      echo '<table width="100%" cellpadding="1" cellspacing="1">
              <tr>
                <td>&nbsp;</td>
              </tr>
            <form action="update.php?action=install&amp;do=write_mysql" method="POST">
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
  } elseif($_GET['do'] == "write_mysql")
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
            
      
      include(basePath.'/_installer/html/mysql_u.php');
      echo '<table width="100%" cellpadding="3" cellspacing="1">
              <tr>
                <td height="25"></td>
              </tr>
              <tr>
                <td align="right"><a href="update.php?action=database">&raquo; Weiter</a></td>
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
            
      include(basePath.'/_installer/html/mysql_u.php');
      
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
                <td align="right"><a href="update.php?action=database">&raquo; Weiter</a></td>
              </tr>
            </table>';
    }
  } else {
    include(basePath.'/_installer/html/mysql_u.php');
    
    $prefix = $sql_prefix;
    $host = $sql_host;
    $user = $sql_user;
    $pwd = $sql_pass;
    $database = $sql_db;
    
    include(basePath.'/_installer/html/mysql_data_u.php');
  }
break;
case 'database';

  if($_GET['do'] == "update")
  {
    $con = @mysql_connect($sql_host, $sql_user, $sql_pass);
    $sel = @mysql_select_db($sql_db,$con);
    if($con && $sel)
    {
//Clanwar Screenshots verschieben
  $files = get_files('../inc/images/clanwars');
  for($i=0; $i<count($files); $i++)
  {
    if(is_dir('../inc/images/clanwars/'.$files[$i]))
    {
      $sc = get_files('../inc/images/clanwars/'.$files[$i]);
      for($e=0; $e<count($sc); $e++)
      {
        @copy(
          '../inc/images/clanwars/'.$files[$i].'/'.$sc[$e],
          '../inc/images/clanwars/'.$files[$i].'_'.$sc[$e]
        );
        @unlink('../inc/images/clanwars/'.$files[$i].'/'.$sc[$e]);
      }
      @unlink('../inc/images/clanwars/'.$files[$i]);
      @rmdir('../inc/images/clanwars/'.$files[$i]);
    }
  }
//Bilder aus der Usergalerie verschieben
  $files = get_files('../inc/images/uploads/usergallery/');
  for($i=0; $i<count($files); $i++)
  {
    if(is_dir('../inc/images/uploads/usergallery/'.$files[$i]))
    {
      $sc = get_files('../inc/images/uploads/usergallery/'.$files[$i]);
      for($e=0; $e<count($sc); $e++)
      {
        @copy(
          '../inc/images/uploads/usergallery/'.$files[$i].'/'.$sc[$e],
          '../inc/images/uploads/usergallery/'.$files[$i].'_'.$sc[$e]
        );
        @unlink('../inc/images/uploads/usergallery/'.$files[$i].'/'.$sc[$e]);
      }
      @unlink('../inc/images/uploads/usergallery/'.$files[$i]);
      @rmdir('../inc/images/uploads/usergallery/'.$files[$i]);
    }
  }
  
      if($_POST['version'] == "1.1")
      {
        update_mysql();
        update_mysql_1_3();
        update_mysql_1_4();
        update_mysql_1_5();
				update_mysql_1_5_1();
				update_mysql_1_5_2();
				update_mysql_1_5_4();
				update_mysql_1_5_5_3();
      } elseif($_POST['version'] == "1.2.x") {
        update_mysql_1_3();
        update_mysql_1_4();
        update_mysql_1_5();
				update_mysql_1_5_1();
				update_mysql_1_5_2();
				update_mysql_1_5_4();
				update_mysql_1_5_5_3();
      } elseif($_POST['version'] == "1.3.x") {
        update_mysql_1_4();
        update_mysql_1_5();
				update_mysql_1_5_1();
				update_mysql_1_5_2();
				update_mysql_1_5_4();
				update_mysql_1_5_5_3();
      } elseif($_POST['version'] == "1.4.x") {
        update_mysql_1_5();
				update_mysql_1_5_1();
				update_mysql_1_5_2();
				update_mysql_1_5_4();
				update_mysql_1_5_5_3();
      } elseif($_POST['version'] == "1.5") {
        update_mysql_1_5_1();
				update_mysql_1_5_2();
				update_mysql_1_5_4();
				update_mysql_1_5_5_3();
      } elseif($_POST['version'] == "1.5.1") {
        update_mysql_1_5_2();
				update_mysql_1_5_4();
				update_mysql_1_5_5_3();
      } elseif($_POST['version'] == "1.5.2") {
        update_mysql_1_5_4();
        		update_mysql_1_5_5_3();
      }
    	elseif($_POST['version'] == "1.5.5.2") {
        update_mysql_1_5_5_3();
      }
      
      header("Location: update.php?action=done");
    } else {
      echo '<table width="100%" cellpadding="1" cellspacing="1" class="error">
              <tr>
                <td class="error_text"><b>Fehler:</b></td>
              </tr>
              <tr>
                <td class="error_text">Die Datenbankverbindung wurde unterbrochen!</td>
              </tr>
            </table>';
            
      include basePath.'/_installer/html/update.php';
    }
  } else {
    include basePath.'/_installer/html/update.php';
  }
break;
case 'done';
  include basePath.'/_installer/html/done_u.php';
break;
endswitch;
include basePath.'/_installer/html/footer.php';
ob_end_flush();
?>