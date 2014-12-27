<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.1
 * http://www.dzcp.de
 */

if(defined('_Clanwars')) {
  if(!$chkMe)
  {
    $index = error(_error_have_to_be_logged, 1);
  } else {
    $qry = db("SELECT * FROM ".$db['cw_player']."
               WHERE cwid = '".intval($_GET['id'])."'
               AND member = '".$userid."'");
    if(_rows($qry))
    {
      $upd = db("UPDATE ".$db['cw_player']."
                 SET `status` = '".intval($_POST['status'])."'
                 WHERE cwid = '".intval($_GET['id'])."'
                 AND member = '".$userid."'");
    } else {
      $ins = db("INSERT INTO ".$db['cw_player']."
                 SET `cwid`   = '".intval($_GET['id'])."',
                     `member` = '".intval($userid)."',
                     `status` = '".intval($_POST['status'])."'");
    }

    $index = info(_cw_status_set, "?action=details&amp;id=".$_GET['id']."");
  }
}
