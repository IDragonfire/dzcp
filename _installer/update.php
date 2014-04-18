<?php
## Check PHP Version ##
if(version_compare(PHP_VERSION, '5.2.0', '>=') === false)
    die('DZCP required PHP 5.2.0 or newer!<p> Found PHP ' . PHP_VERSION);

ob_start();
session_start();
define('basePath', dirname(dirname(__FILE__).'../'));
$action = isset($_GET['action']) ? $_GET['action'] : '';
$do = isset($_GET['do']) ? $_GET['do'] : '';

require_once(basePath.'/inc/_version.php');
require_once(basePath."/inc/debugger.php");
require_once(basePath.'/_installer/conf/conf.php');
require_once(basePath.'/_installer/conf/mysql.php');

include(basePath.'/_installer/html/header_u.php');

switch ($action):
default:
  if(isset($_GET['agb']) && $_GET['agb'])
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
if(isset($_GET['agb']) && $_GET['agb'])
{
  header("Location: update.php?agb=false");
} else {
  if($do == "set_chmods" && $_POST['check'] != "dont")
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
        _c('inc/_cache_',$pfad,$host,$user,$pwd);
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
  $c =  _i('../inc/_cache_',1);
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
              <td align="right"><a href="update.php?action=database">&raquo; Weiter</a></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </table>';
  }
}
break;
case 'database';
  if($do == "update")
  {
    if($mysql)
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
        update_mysql_1_6();
      } elseif($_POST['version'] == "1.2.x") {
        update_mysql_1_3();
        update_mysql_1_4();
        update_mysql_1_5();
        update_mysql_1_5_1();
        update_mysql_1_5_2();
        update_mysql_1_5_4();
        update_mysql_1_6();
      } elseif($_POST['version'] == "1.3.x") {
        update_mysql_1_4();
        update_mysql_1_5();
        update_mysql_1_5_1();
        update_mysql_1_5_2();
        update_mysql_1_5_4();
        update_mysql_1_6();
      } elseif($_POST['version'] == "1.4.x") {
        update_mysql_1_5();
        update_mysql_1_5_1();
        update_mysql_1_5_2();
        update_mysql_1_5_4();
        update_mysql_1_6();
      } elseif($_POST['version'] == "1.5") {
        update_mysql_1_5_1();
        update_mysql_1_5_2();
        update_mysql_1_5_4();
        update_mysql_1_6();
      } elseif($_POST['version'] == "1.5.1") {
        update_mysql_1_5_2();
        update_mysql_1_5_4();
        update_mysql_1_6();
      } elseif($_POST['version'] == "1.5.2") {
        update_mysql_1_5_4();
        update_mysql_1_6();
      } elseif($_POST['version'] == "ab 1.5.4 bis 1.5.5.4") {
        update_mysql_1_6();
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
