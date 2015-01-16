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
      Vielen Dank, das Sie sich f&uuml;r deV!L`z Clanportal entschieden haben. Die nachfolgenden Stationen werden Sie durch das Update von deV!L`z Clanportal navigieren.
      Nachfolgend sind die Lizenzbstimmungen, die Sie bei weiterer Nutzung akzeptieren m&uuml;ssen.<br /><br />

      Viel Spass mit deV!L`z Clanportal w&uuml;nscht Ihnen das geamte Team von DZCP.de.<br /><br />
    </div>
    <font class="head">&raquo; Lizenzbestimmungen:</font>
    </td>
  </tr>
  <tr>
    <td align="center">
<form action="update.php?action=require&agb=false" method="post">
<textarea name="lizenz" style="width:100%;height:400px;overflow:auto" readonly>
<?php echo utf8_encode(file_get_contents("conf/lizenz.txt")); ?>
</textarea><br /><br /><b>Ich bin  mit den Lizenzbestimmungen einverstanden</b><br /><br />
<script language="JavaScript1.2">
   document.writeln('<input type="button" value="Ja" class="button" onclick="document.forms[0].action=\'update.php?action=require\';document.forms[0].submit()" tabindex="6">');
   </script> <input type="submit" value="Nein"></td>
      </form>
    </td>
  </tr>
</table>