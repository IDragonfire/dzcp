<?php
## OUTPUT BUFFER START ##
include("../inc/buffer.php");
## INCLUDES ##
include(basePath."/inc/config.php");
include(basePath."/inc/bbcode.php");
## SETTINGS ##
$time_start = generatetime();
lang($language);
$where = 'Mitgliederkarte';
$dir = "membermap";
## SECTIONS ##
  $icon = getimagesize('../inc/images/mappin.png');

  if(settings('gmaps_who') == 1) $w = 'WHERE level > 1 AND level != 0';
  else                           $w = 'WHERE level != 0';

  $qry = db("SELECT id,city,gmaps_koord,country FROM ".$db['users']."
             ".$w."
             AND city != ''
             AND gmaps_koord != ''
             GROUP BY gmaps_koord");
  while($get = _fetch($qry))
  {
    $userInfos="";
    $chk = db("SELECT id,city,gmaps_koord,country FROM ".$db['users']."
              ".$w." AND gmaps_koord = '".$get['gmaps_koord']."'");
    if(_rows($chk) > 1)
    {
      $qryn = db("SELECT id,city,gmaps_koord,country FROM ".$db['users']."
                  ".$w." AND gmaps_koord = '".$get['gmaps_koord']."'
                  ORDER BY level DESC");
      $i=0;
      while($getn = _fetch($qryn))
      {
        $tr="";
        $koord = re($getn['gmaps_koord']);
        $koord = str_replace('&#40;','',str_replace('&#41;','',$koord));
        $koord = str_replace('(','',str_replace(')','',$koord));
        $koord = explode(",",$koord);
        $ort = re($getn['city']);
        if($i == 3){
          $i = 0;
          $tr = '</tr><tr>';
        }
        $userInfos .= $tr.'<td><div id="memberMapInner"><b>'.rawautor($getn['id']).'</b><br /><b>'._position.':</b> '.getrank($getn['id']).'<br />'.userpic($getn['id']).'</div></td>';
        $i++;
      }

      $members .= 'initMember(new GLatLng('.$koord[0].','.$koord[1].'), \'<tr><td><b style="font-size:13px">&nbsp;'.$ort.'</b></td></tr><tr>'.$userInfos.'</tr>\', 0);';
    } else {                
      if($get['level'] != 1) $team = 1;
      else                   $team = 0;

      $koord = re($get['gmaps_koord']);
      $koord = str_replace('&#40;','',str_replace('&#41;','',$koord));
      $koord = str_replace('(','',str_replace(')','',$koord));
      $koord = explode(",",$koord);
      $ort = re($get['city']);

      $members .= 'initMember(new GLatLng('.$koord[0].','.$koord[1].'), \'<tr><td><b style="font-size:13px">&nbsp;'.$ort.'</b></td></tr><tr><td><div id="memberMapInner"><b>'.rawautor($get['id']).'</b><br /><b>'._position.':</b> '.getrank($get['id']).'<br />'.userpic($get['id']).'</div></td></tr>\', '.$team.');';
    }
  }

  $index = show($dir."/membermap", array("key" => settings('gmaps_key'),
                                         "iconsizex" => $icon[0],
                                         "iconsizey" => $icon[1],
                                         "members" => addslashes($members),
                                         "head" => _membermap
                                        ));
## SETTINGS ##
$title = $pagetitle." - ".$where."";
$time_end = generatetime();
$time = round($time_end - $time_start,4);
page($index, $title, $where,$time);
## OUTPUT BUFFER END ##
gz_output();
?>