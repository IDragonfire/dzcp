<table width="100%" cellpadding="3" cellspacing="1">  
  <tr>
    <td class="head" colspan="4">&raquo; Adminaccount</td>
  </tr>
  <form action="install.php?action=database&amp;do=install" method="POST">
  <tr>
    <td><b>Loginname:</b></td><td><input type="text" name="login" value="<?php echo $_POST['login']; ?>"></td>
    <td><b>Nickname:</b></td><td><input type="text" name="nick" value="<?php echo $_POST['nick']; ?>"></td>
  </tr>
  <tr>
    <td><b>E-Mail:</b></td><td><input type="text" name="email" value="<?php echo $_POST['email']; ?>"></td>
    <td><b>Passwort:</b></td><td><input type="password" name="pwd" value="<?php echo $_POST['pwd']; ?>"></td>
  </tr>
  <tr>
    <td height="20"></td>
  </tr>
  <tr>
    <td colspan="4" class="info"><b>Hinweis:</b> Dieser Vorgang kann ggf. einige Sekunden dauern, achten Sie also bitte darauf, die Seite w&auml;hrend dessen nicht neu zu laden!</td>
  </tr>
  <tr>
    <td height="20"></td>
  </tr>
  <tr>
    <td colspan="4" align="center"><input type="submit" value="deV!L`z Clanportal installieren!"></td>
  </tr>
  </form>
</table>