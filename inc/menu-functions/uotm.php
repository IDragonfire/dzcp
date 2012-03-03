<?php
//User of the Moment
function uotm()
{
  global $db, $allowHover;

    $imgFiles = array();
    $folder = get_files('../inc/images/uploads/userpics');
    foreach($folder AS $file) array_push($imgFiles, $file);

    if(count($imgFiles) != 0)
    {
      $userid = intval($imgFiles[rand(0, count($imgFiles) - 1)]);
      $get = _fetch(db("SELECT id,nick,country,bday FROM ".$db['users']." WHERE id = '".$userid."'"));

      if(!empty($get))
      {
        if($allowHover == 1)
          $info = 'onmouseover="DZCP.showInfo(\'<tr><td colspan=2 align=center padding=3 class=infoTop>'.rawautor($get['id']).'</td></tr><tr><td width=50%><b>'._age.':</b></td><td>'.getAge($get['bday']).'</td></tr><tr><td colspan=2 align=center>'.jsconvert(userpic($get['id'])).'</td></tr>\')" onmouseout="DZCP.hideInfo()"';
  
  
        $uotm = show("menu/uotm", array("uid" => $userid,
                                        "upic" => userpic($get['id'], 130, 161),
                                        "info" => $info));
      }
    }

  return empty($uotm) ? '' : '<table class="navContent" cellspacing="0">'.$uotm.'</table>';
}
?>
