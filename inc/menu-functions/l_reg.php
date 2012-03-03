<?php
//zuletzt registrierte User
function l_reg()
{
  global $db,$llreg,$maxlreg;
    $qry = db("SELECT id,nick,country,regdatum FROM ".$db['users']."
               ORDER BY regdatum DESC
               LIMIT ".$maxlreg."");
    while($get = _fetch($qry))
    {
      $lreg .= show("menu/last_reg", array("nick" => re(cut($get['nick'], $llreg)),
                                           "country" => flag($get['country']),
                                           "reg" => date("d.m.", $get['regdatum']),
                                           "id" => $get['id']));
    }

  return empty($lreg) ? '' : '<table class="navContent" cellspacing="0">'.$lreg.'</table>';
}
?>
