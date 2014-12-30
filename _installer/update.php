<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

## Check PHP Version ##
if(version_compare(PHP_VERSION, '5.2.0', '>=') === false)
    die('DZCP required PHP 5.2.0 or newer!<p> Found PHP ' . PHP_VERSION);

ob_start();
session_start();

define('basePath', dirname(dirname(__FILE__).'../'));
$action = isset($_GET['action']) ? $_GET['action'] : '';
$do = isset($_GET['do']) ? $_GET['do'] : '';
$installer = true; $updater = true;

require_once(basePath.'/inc/_version.php');
require_once(basePath."/inc/debugger.php");
require_once(basePath.'/inc/cookie.php');
require_once(basePath.'/inc/crypt.php');
require_once(basePath.'/inc/sessions.php');
require_once(basePath.'/_installer/conf/conf.php');
require_once(basePath.'/_installer/conf/mysql.php');

include(basePath.'/_installer/html/header_u.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
DebugConsole::initCon();

if(debug_dzcp_handler)
    set_error_handler('dzcp_error_handler');

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
  if($do == "set_chmods" && $_POST['check'] != "dont")
  {
    if(function_exists('ftp_connect') && function_exists('ftp_login') && function_exists('ftp_site'))
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
        set_ftp_chmod('_installer',$pfad,$host,$user,$pwd);
        set_ftp_chmod('_installer/update.php',$pfad,$host,$user,$pwd);
        set_ftp_chmod('_installer/install.php',$pfad,$host,$user,$pwd);
        set_ftp_chmod('rss.xml',$pfad,$host,$user,$pwd);
        set_ftp_chmod('admin',$pfad,$host,$user,$pwd);
        set_ftp_chmod('banner',$pfad,$host,$user,$pwd);
        set_ftp_chmod('banner/partners',$pfad,$host,$user,$pwd);
        set_ftp_chmod('banner/sponsors',$pfad,$host,$user,$pwd);
        set_ftp_chmod('downloads',$pfad,$host,$user,$pwd);
        set_ftp_chmod('gallery',$pfad,$host,$user,$pwd);
        set_ftp_chmod('gallery/images',$pfad,$host,$user,$pwd);
        set_ftp_chmod('server',$pfad,$host,$user,$pwd);
        set_ftp_chmod('upload',$pfad,$host,$user,$pwd);
        set_ftp_chmod('upload/',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc/_cache_',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc/images',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc/images/clanwars',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc/images/gameicons',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc/images/maps',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc/images/newskat',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc/images/smileys',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc/images/squads',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc/images/uploads',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc/images/uploads/taktiken',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc/images/uploads/useravatare',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc/images/uploads/usergallery',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc/images/uploads/userpics',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc/tinymce_files',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc/tinymce/plugins/ajaxfilemanager/session',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc/tinymce/plugins/ajaxfilemanager/session/gc_counter.ajax.php',$pfad,$host,$user,$pwd);
        set_ftp_chmod('inc/config.php',$pfad,$host,$user,$pwd);
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
  $cm  = check_file_dir('../_installer',1);
  $cm .= check_file_dir('../_installer/install.php');
  $cm .= check_file_dir('../_installer/update.php');

//Check Scriptfiles
  $c  = check_file_dir('../rss.xml');
  $c .= check_file_dir('../admin',1);
  $c .= check_file_dir('../banner',1);
  $c .= check_file_dir('../banner/partners',1);
  $c .= check_file_dir('../downloads',1);
  $c .= check_file_dir('../gallery',1);
  $c .= check_file_dir('../gallery/images',1);
  $c .= check_file_dir('../server',1);
  $c .= check_file_dir('../upload',1);
  $c .= check_file_dir('../upload/index.php');
  $c .= check_file_dir('../inc',1);
  $c .=  check_file_dir('../inc/_cache_',1);
  $c .= check_file_dir('../inc/images',1);
  $c .= check_file_dir('../inc/images/clanwars',1);
  $c .= check_file_dir('../inc/images/gameicons',1);
  $c .= check_file_dir('../inc/images/maps',1);
  $c .= check_file_dir('../inc/images/newskat',1);
  $c .= check_file_dir('../inc/images/smileys',1);
  $c .= check_file_dir('../inc/images/squads',1);
  $c .= check_file_dir('../inc/images/uploads',1);
  $c .= check_file_dir('../inc/images/uploads/taktiken',1);
  $c .= check_file_dir('../inc/images/uploads/useravatare',1);
  $c .= check_file_dir('../inc/images/uploads/usergallery',1);
  $c .= check_file_dir('../inc/images/uploads/userpics',1);
  $c .= check_file_dir('../inc/tinymce_files',1);
  $c .= check_file_dir('../inc/tinymce/plugins/ajaxfilemanager/session',1);
  $c .= check_file_dir('../inc/tinymce/plugins/ajaxfilemanager/session/gc_counter.ajax.php',1);
  $c .= check_file_dir('../inc/config.php',1);

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
break;
case 'require';
if(isset($_GET['agb']) && $_GET['agb']) {
    header("Location: install.php?agb=false");
} else {
    include(basePath.'/_installer/html/require.php');
    $mcrypt_decrypt = extension_loaded('mcrypt') ? _true."<font color='green'><b>" : _false."<font color='red'><b>";
    $mcrypt_decrypt.= "</b>&nbsp;&nbsp;&nbsp; Mcrypt</font><br />";
    $apc = extension_loaded('apc') ? _true."<font color='green'><b>" : _false."<font color='red'><b>";
    $apc.= "</b>&nbsp;&nbsp;&nbsp; Alternative PHP Cache (APC)</font><br />";

    $opt = '';
    $opt .= $mcrypt_decrypt;
    $opt .= $apc;
    $check = preg_match("#false#Uis",$opt);

    if($check == FALSE)
    {
        echo '<table width="100%" cellpadding="1" cellspacing="1" class="done">
            <tr>
              <td class="error_text"><b>Optimal</b></td>
            </tr>
            <tr>
              <td class="error_text">Klicken Sie unten rechts auf Weiter um fortzufahren.
              </td>
            </tr>
          </table>';
        $formcheck = "dont";
    }

    echo '<table width="100%" cellpadding="3" cellspacing="1" class="emph">
          <tr>
            <td><b>Optionale PHP-Erweiterungen</b></td>
            <td></td>
          </tr>
          <tr>
            <td valign="top">'.$opt.'</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td valign="top">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          </table>';

        echo '<table width="100%" cellpadding="1" cellspacing="1">
            <tr>
              <td align="right"><a href="update.php?action=prepare">&raquo; Weiter</a></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </table>';
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
        update_mysql_1_7();
      } elseif($_POST['version'] == "1.2.x") {
        update_mysql_1_3();
        update_mysql_1_4();
        update_mysql_1_5();
        update_mysql_1_5_1();
        update_mysql_1_5_2();
        update_mysql_1_5_4();
        update_mysql_1_6();
        update_mysql_1_7();
      } elseif($_POST['version'] == "1.3.x") {
        update_mysql_1_4();
        update_mysql_1_5();
        update_mysql_1_5_1();
        update_mysql_1_5_2();
        update_mysql_1_5_4();
        update_mysql_1_6();
        update_mysql_1_7();
      } elseif($_POST['version'] == "1.4.x") {
        update_mysql_1_5();
        update_mysql_1_5_1();
        update_mysql_1_5_2();
        update_mysql_1_5_4();
        update_mysql_1_6();
        update_mysql_1_7();
      } elseif($_POST['version'] == "1.5") {
        update_mysql_1_5_1();
        update_mysql_1_5_2();
        update_mysql_1_5_4();
        update_mysql_1_6();
        update_mysql_1_7();
      } elseif($_POST['version'] == "1.5.1") {
        update_mysql_1_5_2();
        update_mysql_1_5_4();
        update_mysql_1_6();
        update_mysql_1_7();
      } elseif($_POST['version'] == "1.5.2") {
        update_mysql_1_5_4();
        update_mysql_1_6();
        update_mysql_1_7();
      } elseif($_POST['version'] == "ab 1.5.4 bis 1.5.5.4") {
        update_mysql_1_6();
        update_mysql_1_7();
      } elseif($_POST['version'] == "1.6.0.x") {
        update_mysql_1_7();
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
$installer_out = ob_get_contents();
ob_end_clean();
echo DebugConsole::show_logs().$installer_out;