<table width="100%" cellpadding="3" cellspacing="1">
  <tr>
    <td class="head" colspan="4">&raquo; Update</td>
  </tr>
  <tr>
    <td colspan="4">
      Kommen wir zum nun zum letzten und abschlie&szlig;enden Teil des Updates.<br />
      Mit einem Klick auf 'Datenbank updaten' wird die Datenbank auf den aktuellen Stand gebracht.<br /><br />
    </td>
  </tr>
  <tr>
    <td colspan="2" class="info"><b>Hinweis:</b> Bitte achten Sie darauf das Sie diesen Vorgang nur einmal abschlie&szlig;en, da sonst Sch&auml;den in der Datenbank auftreten k&ouml;nnten.<br /> <br />
    Dieser Vorgang kann ggf. einige Sekunden dauern, achten Sie also bitte darauf, die Seite w&auml;hrend dessen nicht neu zu laden!</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <form action="update.php?action=database&amp;do=update" method="POST">
  <tr>
    <td class="head" colspan="4">&raquo; Aktuelle Version</td>
  </tr>
  <tr>
    <td>
      <input type="radio" name="version" value="1.1"> <b>1.1</b><br />
      <input type="radio" name="version" value="1.2.x"> <b>1.2.x</b><br />
      <input type="radio" name="version" value="1.3.x"> <b>1.3.x</b><br />
      <input type="radio" name="version" value="1.4.x"> <b>1.4.x</b><br />
      <input type="radio" name="version" value="1.5"> <b>1.5</b><br />
      <input type="radio" name="version" value="1.5.1"> <b>1.5.1</b><br />
      <input type="radio" name="version" value="1.5.2"> <b>1.5.2</b><br />
      <input type="radio" name="version" value="ab 1.5.4 bis 1.5.5.4"> <b>ab 1.5.4 bis 1.5.5.4</b><br />
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><input type="submit" value="Datenbank updaten!"></td>
  </tr>
  </form>
</table>