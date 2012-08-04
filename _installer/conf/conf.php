<?php
###############
## Variablen ##
###############
define('_disabled_fopen', 'Dein Webserver unterst&uuml;tzt die Funktion <i>fopen</i> nicht!');
define('_do_config', 'Du musst die Konfiguration erfolgreich abschlie&szlig;en, um die Datenbank installieren zu k&ouml;nnen!');
define('_true', '<img src="img/true.gif" border="0" alt="" vspace="0" align="center"> ');
define('_false', '<img src="img/false.gif" border="0" alt="" vspace="0" align="center"> ');
define('_link_start', '<font class="enabled">&raquo; Lizenz</font>');
define('_link_start_1', '<font class="disabled">1. Lizenz</font>');
define('_link_prepare', '<font class="enabled">&raquo; Vorbereitung</font>');
define('_link_prepare_1', '<font class="disabled">2. Vorbereitung</font>');
define('_link_install', '<font class="enabled">&raquo; MySQL</font>');
define('_link_install_1', '<font class="disabled">3. MySQL</font>');
define('_link_db', '<font class="enabled">&raquo; Installation</font>');
define('_link_db_1', '<font class="disabled">4. Installation</font>');
define('_link_dbu', '<font class="enabled">&raquo; Update</font>');
define('_link_dbu_1', '<font class="disabled">4. Update</font>');
define('_link_done', '<font class="enabled">&raquo; Done</font>');
define('_link_done_1', '<font class="disabled">5. Done</font>');
$b = "<br />";
################
## Funktionen ##
################
function _ex ($function)
{
  if(function_exists($function))
    return TRUE;
  else
    return FALSE;
}

function _is ($var)
{
  if(is_writable($var))
    return TRUE;
  else
    return FALSE;
}

function _i ($file, $var="0")
{
  global $b;
  
  if($var == 1) $what = "Dir:&nbsp;";
  else $what = "File:";
    
  $_file = preg_replace("#\.\.#Uis", "", $file);
  $c = '';
  if(_is($file))
    $c .= _true."<font color='green'><b>".$what."</b>&nbsp;&nbsp;&nbsp; ".$_file."</font>".$b;
  else
    $c .= _false."<font color='red'><b>".$what."</b>&nbsp;&nbsp;&nbsp; ".$_file."</font>".$b;
    
  return $c;
}

function _c ($file,$pfad,$host,$user,$pwd)
{
  $conn = @ftp_connect($host);
  @ftp_login($conn, $user, $pwd);
  
  ftp_site($conn, 'CHMOD 0777 '.$pfad.'/'.$file);
}

function _s ($e)
{
  return;
}
function _m ($prefix, $host, $user, $pwd, $db)
{
  $fp = @fopen("../inc/mysql.php","w");
  @fwrite($fp,"<?php
                 \$sql_prefix = '".$prefix."';
                 \$sql_host = '".$host."';
                 \$sql_user =  '".$user."'; 
                 \$sql_pass = '".$pwd."'; 
                 \$sql_db = '".$db."';
               ?>");
  @fclose($fp);
}
?>