<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_Clanwars')) {
  header("Content-type: text/html; charset=utf-8");
  if($do == 'edit')
  {
    $qry = db("SELECT * FROM ".$db['cw_comments']."
               WHERE id = '".intval($_GET['cid'])."'");
    $get = _fetch($qry);

    $get_id = '?';
    $get_userid = $get['reg'];
    $get_date = $get['datum'];

    if($get['reg'] == 0) $regCheck = false;
    else {
      $regCheck = true;
      $pUId = $get['reg'];
    }

    $editedby = show(_edited_by, array("autor" => cleanautor($userid),
                                       "time" => date("d.m.Y H:i", time())._uhr));
  } else {

    $get_id = cnt($db['cw_comments'], " WHERE cw = ".intval($_GET['id'])."")+1;
    $get_userid = $userid;
    $get_date = time();

    if(!$chkMe) $regCheck = false;
    else {
      $regCheck = true;
      $pUId = $userid;
    }
  }

  $get_hp = $_POST['hp'];
  $get_email = $_POST['email'];
  $get_nick = $_POST['nick'];

  if(!$regCheck)
    {
    if($get_hp) $hp = show(_hpicon_forum, array("hp" => links($get_hp)));
    if($get_email) $email = '<br />'.show(_emailicon_forum, array("email" => eMailAddr($get_email)));
    $onoff = "";
    $avatar = "";
    $nick = show(_link_mailto, array("nick" => re($get_nick),
                                     "email" => $get_email));
  } else {
    $hp = "";
    $email = "";
    $onoff = onlinecheck($get_userid);
    $nick = cleanautor($get_userid);
  }

  $titel = show(_eintrag_titel, array("postid" => $get_id,
                                                                          "datum" => date("d.m.Y", $get_date),
                                                                          "zeit" => date("H:i", $get_date)._uhr,
                                      "edit" => $edit,
                                      "delete" => $delete));

  $index = show("page/comments_show", array("titel" => $titel,
                                            "comment" => bbcode(re($_POST['comment']),true),
                                            "nick" => $nick,
                                            "editby" => bbcode($editedby,true),
                                            "email" => $email,
                                            "hp" => $hp,
                                            "avatar" => useravatar($get_userid),
                                            "onoff" => $onoff,
                                            "rank" => getrank($get_userid),
                                            "ip" => $userip._only_for_admins));

  echo '<table class="mainContent" cellspacing="1">'.$index.'</table>';

  if(!mysqli_persistconns)
      $mysql->close(); //MySQL

  exit();
}