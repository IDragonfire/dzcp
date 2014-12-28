<?php
ob_start();
/*
  Sets the icons before content in a <select>-Tag (Mozilla/Netscape only!)
*/
## SET CONTENT TYPE
  header("Content-type: text/css");
## SETTINGS
  function getIcons($dir)
  {
    $dp = @opendir($dir);
    $allicons = array();
    while($icons = @readdir($dp))
    {
      if($icons != '.' && $icons != '..')
        array_push($allicons, $icons);
    }
    @closedir($dp);
    sort($allicons);

    return($allicons);
  }
## SECTIONS
//Flaggen
  $flags = getIcons('../../../images/flaggen/');
  for($i=0; $i<count($flags); $i++)
  {
    echo " option[value=".preg_replace("#\.gif|.jpg#Uis","",$flags[$i])."]:before {";
    echo " content: url(\"../../../images/flaggen/".$flags[$i]."\");";
    echo "}";
  }
//Gameicons
  $games = getIcons('../../../images/gameicons/custom/');
  for($i=0; $i<count($games); $i++)
  {
    if(preg_match("=\.gif|.jpg=Uis",$games[$i]))
    {
      echo "option[value=".preg_replace("#\.#","\.",$games[$i])."]:before {";
      echo "  content: url(\"../../../images/gameicons/custom/".$games[$i]."\");";
      echo "}";
    }
  }
ob_end_flush();
?>
