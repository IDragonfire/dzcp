<?php
/**
 * DZCP - deV!L`z ClanPortal 1.7.0
 * http://www.dzcp.de
 */

if(defined('_Forum')) {
  if($do == "fabo")
  {
    if(isset($_POST['f_abo']))
    {
      $f_abo = db("INSERT INTO ".$db['f_abo']."
                    SET `user` = '".intval($userid)."',
                        `fid`  = '".intval($_GET['id'])."',
                        `datum`  = '".time()."'");
    } else {
      $f_abo = db("DELETE FROM ".$db['f_abo']."
                   WHERE user = '".intval($userid)."'
                   AND fid = '".intval($_GET['id'])."'");
    }
    $index = info(_forum_fabo_do, "?action=showthread&amp;id=".$_GET['id']."");
  }
}