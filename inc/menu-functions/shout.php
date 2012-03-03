<?php
//-> Shoutbox
function shout($ajax = 0)
{
  global $db,$maxshout,$lshouttext,$lshoutnick,$shout_max_zeichen,$userid,$chkMe;
    $qry = db("SELECT * FROM ".$db['shout']."
               ORDER BY id DESC LIMIT ".$maxshout."");
    $i = 1;
    while ($get = _fetch($qry))
    {
      $class = ($color % 2) ? "navShoutContentFirst" : "navShoutContentSecond"; $color++;

      if(permission("shoutbox"))
      {
        $delete = '<a href="../shout/?action=admin&amp;do=delete&amp;id='.$get['id'].'" onclick="return(DZCP.del(\''._confirm_del_shout.'\'))"><img src="../inc/images/delete_small.gif" title="'._button_title_del.'" alt="'._button_title_del.'" /></a>';
      } else {
        $delete = "";
      }

      $is_num = preg_match("#\d#", $get['email']);

      if($is_num && !check_email($get['email'])) $nick = autor($get['email'], "navShout");
      else $nick = '<a class="navShout" href="mailto:'.eMailAddr($get['email']).'" title="'.$get['nick'].'">'.cut($get['nick'], $lshoutnick).'</a>';

      $show .= show("menu/shout_part", array("nick" => $nick,
                                             "datum" => date("j.m.Y H:i", $get['datum'])._uhr,
                                             "text" => bbcode(wrap(re($get['text']), $lshouttext)),
                                             "class" => $class,
                                             "del" => $delete));
      $i++;
    }

    if(settings('reg_shout') == 1 && $chkMe == 'unlogged')
    {
      $dis = ' style="text-align:center;cursor:wait" disabled="disabled"';
      $dis1 = ' style="cursor:wait;color:#888" disabled="disabled"';
      $only4reg = _shout_must_reg;
    } else {

    if($chkMe == "unlogged")
    {
      $form = show("menu/shout_form", array("dis" => $dis));
      $sec = show("menu/shout_antispam", array("help" => _login_secure_help,
                                               "dis" => $dis
                                               ));
    } else $form = autor($userid, "navShout");
    }

    $add = show("menu/shout_add", array("form" => $form,
                                        "t_zeichen" => _zeichen,
                                        "noch" => _noch,
                                        "dis1" => $dis1,
                                        "dis" => $dis,
                                        "only4reg" => $only4reg,
                                        "security" => $sec,
                                        "zeichen" => $shout_max_zeichen));

    $shout = show("menu/shout", array("shout" => $show,
                                      "shoutbox" => _shoutbox_head,
                                      "archiv" => _shoutbox_archiv,
                                      "add" => $add));

  return empty($ajax) ? '<table class="navContent" cellspacing="0">'.$shout.'</table>' : $show;
}
?>
