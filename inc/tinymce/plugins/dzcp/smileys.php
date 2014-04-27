<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

ob_start();
define('basePath', '../../../../');

## INCLUDES ##
$ajaxJob = true;
include_once(basePath."/inc/debugger.php");
include_once(basePath."/inc/config.php");
include_once(basePath."/inc/bbcode.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{#dzcp.title}</title>
    <script language="javascript" type="text/javascript" src="../../tiny_mce_popup.js"></script>
    <script language="javascript" type="text/javascript" src="jscripts/smileys.js"></script>
    <base target="_self" />
  <script language="javascript" type="text/javascript">
    function resizeMe()
    {
      var smDiv = document.getElementById('smileys');
      if (navigator.appName.indexOf('Netscape') != -1)
      {
        winB = smDiv.clientWidth;
        winH = smDiv.clientHeight;
      } else if(navigator.appName.indexOf('Opera') != -1) {
        winB = smDiv.offsetWidth+30;
        winH = smDiv.offsetHeight+10;
      } else {
        winB = smDiv.offsetWidth+30;
        winH = smDiv.offsetHeight+30;
      }

      window.resizeTo(winB+40,winH+80);
    }
  </script>
</head>
<body>
    <div align="center">
        <table id="smileys" border="0" cellspacing="0" cellpadding="1">
<?php
    $files = get_files(basePath.'/inc/images/smileys',false,true,$picformat);

    $t=1;
    $b=0;
    $h=0;
    $d=0;
    for($i=0; $i<count($files); $i++)
    {
      $tr1 = "";
      $tr2 = "";

      $constraints = getimagesize(basePath.'/inc/images/smileys/'.$files[$i]);
      $x = $constraints[0]+6;
      $y = $constraints[1]+15;

      $b = $b+$x;
      $h = $h+$y;

      if($t == 0 || $t == 1)
        $tr1 = "<tr>";
      if($t == 8)
      {
        $tr2 = "</tr>";
        $t = 0;
        $d++;
      }
      $t++;

echo $tr1; ?><td><a href="javascript:insertEmotion('<?php echo$files[$i]; ?>')"><img src="../../../images/smileys/<?php echo $files[$i]; ?>" border="0" alt="" /></a></td><?php echo $tr2;
    }

?>
        </table>
    </div>
</body>
</html>
<?php
ob_end_flush();
?>