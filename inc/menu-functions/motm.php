<?php
function motm()
{
  global $db, $allowHover;

  $userpics = get_files(basePath.'/inc/images/uploads/userpics/',false,true);
  $qry = db("SELECT * FROM ".$db['users']." WHERE level >= 2"); 
	while($rs = _fetch($qry))
	{
		foreach($userpics AS $userpic)
		{
			$tmpId = intval($userpic);
			if($tmpId == $rs['id'])
			{
				$temparr[] = $rs['id'];
				break;
			}
		}
	}

	$arrayID = rand(0, count($temparr) - 1);
	$uid = $temparr[$arrayID];

  $get = _fetch(db("SELECT * FROM ".$db['users']." WHERE id = '".$uid."'"));
  if(!empty($get) && !empty($temparr))
  {
	  $status = ($get['status'] == 1 || $get['level'] == 1) ? "aktiv" : "inaktiv";

    if($allowHover == 1)
      $info = 'onmouseover="DZCP.showInfo(\''.fabo_autor($get['id']).'\', \''._posi.';'._status.';'._age.'\', \''.getrank($get['id']).';'.$status.';'.getAge($get['bday']).'\', \''.hoveruserpic($get['id']).'\')" onmouseout="DZCP.hideInfo()"';

	  $member = show("menu/motm", array("uid" => $get['id'],
                                      "upic" => userpic($get['id'], 130, 161),
                                      "info" => $info));
	} else $member = '';

  return empty($member) ? '' : '<table class="navContent" cellspacing="0">'.$member.'</table>';
}
?>
