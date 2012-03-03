<?php
function motm()
{
  global $db, $allowHover;

  $userpics = get_files(basePath.'/inc/images/uploads/userpics/');
  $qry = db("SELECT * FROM ".$db['users']." WHERE level >= 2");
	while($rs = _fetch($qry))
	{
		foreach($userpics AS $userpic)
		{
      $tmpId = intval($userpic);
			if($tmpId == $rs['id'])
			{
				$temparr[] = $rs['id'];
				$a++;
        break;;
			}
		}
	}

	$arrayID = rand(0, count($temparr) - 1);
	$uid = $temparr[$arrayID];

  $get = _fetch(db("SELECT * FROM ".$db['users']." WHERE id = '".$uid."'"));
  if(!empty($get) && !empty($temparr))
  {
	  $status = ($get['status'] == 1 || $get['level'] == 1) ? _aktiv : _inaktiv;

    if($allowHover == 1)
      $info = 'onmouseover="DZCP.showInfo(\'<tr><td colspan=2 align=center padding=3 class=infoTop>'.rawautor($get['id']).'</td></tr><tr><td width=80px><b>'._posi.':</b></td><td>'.getrank($get['id']).'</td></tr><tr><td><b>'._status.':</b></td><td>'.$status.'</td></tr><tr><td><b>'._age.':</b></td><td>'.getAge($get['bday']).'</td></tr><tr><td colspan=2 align=center>'.jsconvert(userpic($get['id'])).'</td></tr>\')" onmouseout="DZCP.hideInfo()"';

	  $member = show("menu/motm", array("uid" => $get['id'],
                                      "upic" => userpic($get['id'], 130, 161),
                                      "info" => $info));
	} else $member = '';

  return empty($member) ? '' : '<table class="navContent" cellspacing="0">'.$member.'</table>';
}
?>
