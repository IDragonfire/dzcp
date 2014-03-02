<?php
//-> Menu: User of the Moment
function uotm() {
    global $db, $allowHover;

    $imgFiles = array();
    $folder = get_files('../inc/images/uploads/userpics',false,true);
    foreach($folder AS $file) array_push($imgFiles, $file);

    if(count($imgFiles) != 0)
    {
      $userid = intval($imgFiles[rand(0, count($imgFiles) - 1)]);
      $get = _fetch(db("SELECT id,nick,country,bday FROM ".$db['users']." WHERE id = '".$userid."'"));

      if(!empty($get))
      {
        if($allowHover == 1)
          $info = 'onmouseover="DZCP.showInfo(\''.fabo_autor($get['id']).'\', \''._age.'\', \''.getAge($get['bday']).'\', \''.hoveruserpic($get['id']).'\')" onmouseout="DZCP.hideInfo()"';


        $uotm = show("menu/uotm", array("uid" => $userid,
                                        "upic" => userpic($get['id'], 130, 161),
                                        "info" => $info));
      }
    }

  return empty($uotm) ? '' : '<table class="navContent" cellspacing="0">'.$uotm.'</table>';
}