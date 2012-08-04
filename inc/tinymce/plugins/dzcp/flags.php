<?php
ob_start();
define('basePath', dirname(__FILE__));
require_once(basePath.'/inc/kernel.php');
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{#dzcp.fltitle}</title>
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
  
      window.resizeTo(winB+20,winH+70);
    }
  </script>
</head>
<body onload="resizeMe()">
	<div align="center">
		<table id="smileys" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td style="border:1px solid #888;padding:0" colspan="16">
          <table style="width:100%;padding:4px" cellspacing="1">
            <tr>
              <td><a href="javascript:insertFlag('eu.gif')"><img src="../../../images/flaggen/eu.gif" border="0" alt="" /></a></td>
              <td><a href="javascript:insertFlag('de.gif')"><img src="../../../images/flaggen/de.gif" border="0" alt="" /></a></td>
              <td><a href="javascript:insertFlag('at.gif')"><img src="../../../images/flaggen/at.gif" border="0" alt="" /></a></td>
              <td><a href="javascript:insertFlag('ch.gif')"><img src="../../../images/flaggen/ch.gif" border="0" alt="" /></a></td>
              <td><a href="javascript:insertFlag('us.gif')"><img src="../../../images/flaggen/us.gif" border="0" alt="" /></a></td>
              <td><a href="javascript:insertFlag('ca.gif')"><img src="../../../images/flaggen/ca.gif" border="0" alt="" /></a></td>
              <td><a href="javascript:insertFlag('uk.gif')"><img src="../../../images/flaggen/uk.gif" border="0" alt="" /></a></td>
              <td><a href="javascript:insertFlag('fr.gif')"><img src="../../../images/flaggen/fr.gif" border="0" alt="" /></a></td>
              <td><a href="javascript:insertFlag('it.gif')"><img src="../../../images/flaggen/it.gif" border="0" alt="" /></a></td>
              <td><a href="javascript:insertFlag('es.gif')"><img src="../../../images/flaggen/es.gif" border="0" alt="" /></a></td>
              <td><a href="javascript:insertFlag('tr.gif')"><img src="../../../images/flaggen/tr.gif" border="0" alt="" /></a></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td style="height:6px"></td>
      </tr>
<?php
    $files = get_files(basePath.'/inc/images/flaggen',false,true,array('gif'));

    $t=1;
    for($i=0; $i<count($files); $i++) 
    {
      $tr1 = '';
      $tr2 = '';
      
      if(
        $files[$i] != 'de.gif' && $files[$i] != 'en.gif' && $files[$i] != 'eu.gif' && $files[$i] != 'at.gif' && $files[$i] != 'ch.gif'
     && $files[$i] != 'us.gif' && $files[$i] != 'ca.gif' && $files[$i] != 'uk.gif' && $files[$i] != 'fr.gif' && $files[$i] != 'it.gif'
     && $files[$i] != 'es.gif' && $files[$i] != 'tr.gif'
      ) {
        
        if($t == 0 || $t == 1) $tr1 = "<tr>";
        if($t == 11)
        {
          $tr2 = "</tr>";
          $t = 0;
        }
        $t++;    
       
  echo $tr1; ?><td><a href="javascript:insertFlag('<?php echo$files[$i]; ?>')"><img src="../../../images/flaggen/<?php echo $files[$i]; ?>" border="0" alt="" /></a></td><?php echo $tr2;
      }
    }

?>
		</table>
	</div>
</body>
</html>
<?php
ob_end_flush();
?>