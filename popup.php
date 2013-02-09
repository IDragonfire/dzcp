<?php
ob_start();

define('basePath', dirname(__FILE__));

include(basePath . "/inc/config.php");
include(basePath . "/inc/bbcode.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="../inc/_templates_/<?php
echo $sdir;
?>/_css/stylesheet.css">
  </head>
  <body style="margin:0"> 
<?php
$pic = str_replace('&', '&amp;', str_replace('img=', '', $_SERVER['QUERY_STRING']));
echo '<a href="javascript:window.close()"><img id="popupPic" src="' . $pic . '" alt="" border="0" /></a>';
?>
  <script language="javascript" type="text/javascript">
    <!--
      function resize()
      {
        var pic = document.getElementById('popupPic');

        if(pic.complete == false)
        {
          window.setTimeout("resize()",800);
        } else {
          var picW = pic.offsetWidth;
          var picH = pic.offsetHeight;
          var screenW = screen.availWidth;
          var screenH = screen.availHeight;
    
          if((picW > screenW && picH > screenH) || picW > screenW)
          {
            pic.style.width = screenW+'px';
            this.window.resizeTo(screenW,parseInt(pic.offsetHeight+51));
          } else if(picH > screenH) {
            pic.style.height = screenH+'px';
            this.window.resizeTo(parseInt(pic.offsetWidth+5),screenH);
          }
        }
      }
      resize();
    -->
  </script>
</body>
</html>
<?php
ob_end_flush();
?> 