<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <td class="head">&raquo; Willkommen</td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td>
    <div align="justify">
      Vielen Dank, das Sie sich f&uuml;r deV!L`z Clanportal entschieden haben. Die nachfolgenden Stationen werden Sie durch die Installation von deV!L`z Clanportal navigieren. 
      Das Installationsscript ist so aufgebaut, das man nur Punkt f&uuml;r Punkt alles abarbeiten muss. Sollte ein Fehler vorliegen wird Ihnen dieser sofort mitgeteilt.<br /><br />
      Viel Spass mit deV!L`z Clanportal w&uuml;nscht Ihnen das gesamte Team von DZCP.de.<br /><br />
    </div>
    <font class="head">&raquo; Lizenzbestimmungen:</font>
    </td>
  </tr>
  <tr>
    <td align="center">
<form action="install.php?action=prepare&agb=false" method="post">
<textarea name="lizenz" style="width:100%;height:400px;overflow:auto" readonly>
<?php
$fp  = fopen("conf/lizenz.txt", "r");
$lic = fread($fp, 9999);
fclose($fp);
echo $lic;
?>
</textarea><br /><br /><b>Ich bin  mit den Lizenzbestimmungen einverstanden</b><br /><br />
<script language="JavaScript1.2">
   document.writeln('<input type="button" value="Ja" class="button" onclick="document.forms[0].action=\'install.php?action=prepare\';document.forms[0].submit()" tabindex="6">');
   </script> <input type="submit" value="Nein"></td>
      </form>
    </td>
  </tr>
</table>