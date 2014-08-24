<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_UserMenu')) {
  header("Content-type: text/html; charset=utf-8");
  if($do == 'edit')
  {
    $qry = db("SELECT * FROM ".$db['usergb']."
               WHERE id = '".intval($_GET['gbid'])."'");
    $get = _fetch($qry);

    $get_id = '?';
    $get_userid = $get['reg'];
    $get_date = $get['datum'];

    if($get['reg'] == 0) $regCheck = true;
    $editby = show(_edited_by, array("autor" => cleanautor($userid),
                                     "time" => date("d.m.Y H:i", time())._uhr));
  } else {
    $get_id = cnt($db['usergb'], "WHERE user = ".intval($_GET['uid']))+1;
    $get_userid = $userid;
    $get_date = time();

    if(!$chkMe) $regCheck = true;
  }

  if($regCheck)
    {
    $get_hp = $_POST['hp'];
    $get_email = $_POST['email'];
    $get_nick = $_POST['nick'];

    $onoff = ""; $avatar = "";
    $nick = CryptMailto($get_email,_link_mailto,array("nick" => re($get_nick)));
  } else {
    $get_hp = data('hp');
    $email = data('email');
    $onoff = onlinecheck($userid);
    $get_nick = autor($userid);
  }

  if($get_hp) $gbhp = show(_hpicon, array("hp" => links($get_hp)));
  else $gbhp = "";

  if($get_email) $gbemail = CryptMailto($get_email,_emailicon);
  else $gbemail = "";

  $titel = show(_eintrag_titel, array("postid" => $get_id,
                                                                          "datum" => date("d.m.Y", time()),
                                                                        "zeit" => date("H:i", time())._uhr,
                                      "edit" => $edit,
                                      "delete" => $delete));

  if($chkMe == 4) $posted_ip = $ip;
  else            $posted_ip = _logged;

    $index .= show("page/comments_show", array("titel" => $titel,
                                                                                "comment" => bbcode(re($_POST['eintrag']),1),
                                             "nick" => $get_nick,
                                             "hp" => $gbhp,
                                             "editby" => $editby,
                                             "email" => $gbemail,
                                             "avatar" => useravatar(),
                                             "onoff" => $onoff,
                                             "rank" => getrank($userid),
                                             "ip" => $posted_ip));

  echo utf8_encode('<table class="mainContent" cellspacing="1">'.$index.'</table>');

  if(!mysqli_persistconns)
    $mysql->close(); //MySQL

  exit();
}
