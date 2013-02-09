<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <td colspan="6" align="justify" class="head"><br /><b>&raquo; MySQL Zugangsdaten</b></td>
  </tr>
  <tr>
    <td colspan="6" class="info"><b>Hinweis:</b> Das Prefix kann optional angegeben werden. 
    Es bietet die M&ouml;glichkeit das Script mehrmals auf einer Datenbank zu installieren!<br /> </td>
  </tr>
  <tr>
    <td height="10"></td>
  </tr>
<form action="update.php?action=install&amp;do=test_mysql" method="POST">
  <tr>
    <td><b>Host:</b></td><td><input type="text" name="host" value="<?php
echo $host;
?>"></td>
    <td><b>Datenbank:</b></td><td><input type="text" name="database" value="<?php
echo $database;
?>"></td>
    <td><b>Prefix:</b></td><td><input type="text" name="prefix" value="<?php
echo $prefix;
?>"></td>
  </tr>
  <tr>
    <td><b>User:</b></td><td><input type="text" name="user" value="<?php
echo $user;
?>"></td>
    <td><b>Passwort:</b></td><td><input type="password" name="pwd" value="<?php
echo $pwd;
?>"></td>
    <td colspan="2"><input style="width:210px;" type="submit" value="MySQL-Daten testen!"></td>
  </tr>
</form>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>