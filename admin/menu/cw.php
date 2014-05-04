<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;

    $where = $where.': '._clanwars;
      if($do == "new")
      {
        $qry = db("SELECT * FROM ".$db['squads']."
WHERE status = '1'
ORDER BY game ASC");
        while($get = _fetch($qry))
        {
          $squads .= show(_cw_add_select_field_squads, array("name" => re($get['name']),
                                                            "game" => re($get['game']),
   "id" => $get['id'],
   "icon" => $get['icon']));
        }

        $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",time())),
   "month" => dropdown("month",date("m",time())),
                                             "year" => dropdown("year",date("Y",time()))));

        $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",time())),
                                               "minute" => dropdown("minute",date("i",time())),
                                                    "uhr" => _uhr));

        $show = show($dir."/form_cw", array("head" => _cw_admin_head,
                                           "datum" => _datum,
                                           "gegner" => _cw_head_gegner,
                                           "xonx" => _cw_head_xonx,
                                           "liga" => _cw_head_liga,
                                           "screen_info" => _cw_screens_info,
                                           "nothing" => "",
                                           "preview" => _preview,
                                           "screenshot1" => _cw_screenshot." 1",
                                           "screenshot2" => _cw_screenshot." 2",
                                           "screenshot3" => _cw_screenshot." 3",
                                           "screenshot4" => _cw_screenshot." 4",
										   "screenshot5" => _cw_screenshot." 5",
                                           "screenshot6" => _cw_screenshot." 6",
                                           "screenshot7" => _cw_screenshot." 7",
                                           "screenshot8" => _cw_screenshot." 8",
										   "screenshot9" => _cw_screenshot." 9",
                                           "screenshot10" => _cw_screenshot." 10",
                                           "screens" => _cw_screens,
   "gametype" => _cw_head_gametype,
                                           "url" => _url,
                                           "clantag" => _cw_admin_clantag,
                                           "lineup_info" => _cw_admin_lineup_info,
                                           "bericht" => _cw_bericht,
                                           "result" => _cw_head_result,
                                           "info" => _cw_admin_info,
                                           "gegnerstuff" => _cw_admin_gegnerstuff,
                                           "warstuff" => _cw_admin_warstuff,
                                           "maps" => _cw_admin_maps,
                                           "serverip" => _cw_admin_serverip,
                                           "servername" => _server_name,
                                           "serverpwd" => _server_password,
   "match_admins" => _cw_head_admin,
   "lineup" => _cw_head_lineup,
   "glineup" => _cw_head_glineup,
                                           "do" => "add",
                                           "what" => _button_value_add,
                                           "cw_clantag" => "",
                                           "cw_gegner" => "",
                                           "cw_url" => "",
                                           "logo" => _cw_logo,
                                           "cw_xonx1" => "",
                                           "cw_xonx2" => "",
                                           "cw_maps" => "",
                                           "cw_servername" => "",
                                           "cw_serverip" => "",
                                           "cw_serverpwd" => "",
                                           "cw_punkte" => "",
                                           "cw_gpunkte" => "",
   "cw_matchadmins" => "",
   "cw_lineup" => "",
   "cw_glineup" => "",
                                           "cw_bericht" => "",
   "dropdown_date" => $dropdown_date,
   "dropdown_time" => $dropdown_time,
                                           "hour" => "",
                                           "minute" => "",
                                           "name" => _member_admin_squad,
   "squad_info" => _cw_admin_head_squads,
                                           "game" => _member_admin_game,
                                           "squads" => $squads,
                                           "cw_liga" => "",
   "country" => _cw_admin_head_country,
   "countrys" => show_countrys(),
   "cw_gametype" => ""));
      } elseif($do == "edit") {

        $qry = db("SELECT * FROM ".$db['cw']."
WHERE id = '".intval($_GET['id'])."'");
        $get = _fetch($qry);

        list($xonx1,$xonx2) = explode('on', $get['xonx']);
   $qrym = db("SELECT * FROM ".$db['squads']."
WHERE status = '1'
ORDER BY game");
        while($gets = _fetch($qrym))
        {
   if($get['squad_id'] == $gets['id']) $sel = 'selected="selected"';
          else $sel = "";

          $squads .= show(_cw_edit_select_field_squads, array("id" => $gets['id'],
   "name" => re($gets['name']),
                                                          "game" => re($gets['game']),
   "sel" => $sel,
   "icon" => $gets['icon']));
   }

        $dropdown_date = show(_dropdown_date, array("day" => dropdown("day",date("d",$get['datum'])),
   "month" => dropdown("month",date("m",$get['datum'])),
                                             "year" => dropdown("year",date("Y",$get['datum']))));

        $dropdown_time = show(_dropdown_time, array("hour" => dropdown("hour",date("H",$get['datum'])),
                                             "minute" => dropdown("minute",date("i",$get['datum'])),
                                                    "uhr" => _uhr));

        $show = show($dir."/form_cw", array("head" => _cw_admin_head_edit,
                                           "datum" => _datum,
                                           "gegner" => _cw_head_gegner,
                                           "xonx" => _cw_head_xonx,
                                           "preview" => _preview,
                                           "nothing" => _cw_nothing,
                                           "screenshot1" => _cw_new." "._cw_screenshot." 1",
                                           "screenshot2" => _cw_new." "._cw_screenshot." 2",
                                           "screenshot3" => _cw_new." "._cw_screenshot." 3",
                                           "screenshot4" => _cw_new." "._cw_screenshot." 4",
										   "screenshot5" => _cw_new." "._cw_screenshot." 5",
                                           "screenshot6" => _cw_new." "._cw_screenshot." 6",
                                           "screenshot7" => _cw_new." "._cw_screenshot." 7",
                                           "screenshot8" => _cw_new." "._cw_screenshot." 8",
										   "screenshot9" => _cw_new." "._cw_screenshot." 9",
                                           "screenshot10" => _cw_new." "._cw_screenshot." 10",
                                           "screens" => _cw_screens,
                                           "liga" => _cw_head_liga,
                                           "screen_info" => _cw_screens_info,
   "gametype" => _cw_head_gametype,
                                           "url" => _url,
                                           "clantag" => _cw_admin_clantag,
                                           "bericht" => _cw_bericht,
                                           "result" => _cw_head_result,
                                           "info" => _cw_admin_info,
                                           "gegnerstuff" => _cw_admin_gegnerstuff,
                                           "warstuff" => _cw_admin_warstuff,
                                           "maps" => _cw_admin_maps,
   "match_admins" => _cw_head_admin,
   "lineup" => _cw_head_lineup,
   "glineup" => _cw_head_glineup,
                                           "serverip" => _cw_admin_serverip,
                                           "lineup_info" => _cw_admin_lineup_info,
                                           "servername" => _server_name,
                                           "serverpwd" => _server_password,
                                           "do" => "editcw&amp;id=".$_GET['id']."",
                                           "what" => _button_value_edit,
                                           "cw_clantag" => re($get['clantag']),
                                           "cw_gegner" => re($get['gegner']),
                                           "cw_url" => $get['url'],
                                           "cw_xonx1" => $xonx1,
                                           "logo" => _cw_logo,
                                           "cw_xonx2" => $xonx2,
                                           "cw_maps" => re($get['maps']),
   "cw_matchadmins" => re($get['matchadmins']),
     "cw_lineup" => re($get['lineup']),
   "cw_glineup" => re($get['glineup']),
                                           "cw_servername" => re($get['servername']),
                                           "cw_serverip" => $get['serverip'],
                                           "cw_serverpwd" => re($get['serverpwd']),
                                           "cw_punkte" => $get['punkte'],
                                           "cw_gpunkte" => $get['gpunkte'],
                                           "cw_bericht" => re_bbcode($get['bericht']),
                                           "day" => date("d", $get['datum']),
   "dropdown_date" => $dropdown_date,
   "dropdown_time" => $dropdown_time,
                                           "month" => date("m", $get['datum']),
                                           "year" => date("Y", $get['datum']),
                                           "hour" => date("H", $get['datum']),
                                           "minute" => date("i", $get['datum']),
                                           "name" => _member_admin_squad,
   "countrys" => show_countrys($get['gcountry']),
   "squad_info" => _cw_admin_head_squads,
                                           "game" => _member_admin_game,
                                           "squads" => $squads,
                                           "cw_liga" => re($get['liga']),
   "country" => _cw_admin_head_country,
   "cw_gametype" => re($get['gametype'])));
      } elseif($do == "add") {
        if(empty($_POST['gegner']) || empty($_POST['clantag']) || empty($_POST['t']))
        {
          if(empty($_POST['gegner'])) $show = error(_cw_admin_empty_gegner, 1);
          elseif(empty($_POST['clantag'])) $show = error(_cw_admin_empty_clantag, 1);
          elseif(empty($_POST['t'])) $show = error(_empty_datum, 1);
        } else {
          if(empty($_POST['xonx1']) && empty($_POST['xonx2'])) $xonx = "";
          else $xonx = "`xonx` = '".$_POST['xonx1']."on".$_POST['xonx2']."',";

          $datum = mktime($_POST['h'],$_POST['min'],0,$_POST['m'],$_POST['t'],$_POST['j']);

          if($_POST['land'] == "lazy") $kid = "";
   else $kid = "`gcountry` = '".$_POST['land']."',";

          $qry = db("INSERT INTO ".$db['cw']."
SET ".$kid."
".$xonx."
`datum` = '".((int)$datum)."',
`squad_id` = '".((int)$_POST['squad'])."',
`clantag` = '".up($_POST['clantag'])."',
`gegner` = '".up($_POST['gegner'])."',
`url` = '".links($_POST['url'])."',
`liga` = '".up($_POST['liga'])."',
`gametype` = '".up($_POST['gametype'])."',
`punkte` = '".((int)$_POST['punkte'])."',
`gpunkte` = '".((int)$_POST['gpunkte'])."',
`maps` = '".up($_POST['maps'])."',
`serverip` = '".up($_POST['serverip'])."',
`servername` = '".up($_POST['servername'])."',
`serverpwd` = '".up($_POST['serverpwd'])."',
`lineup` = '".up($_POST['lineup'])."',
`glineup` = '".up($_POST['glineup'])."',
`matchadmins` = '".up($_POST['match_admins'])."',
`bericht` = '".up($_POST['bericht'],1)."'");

          $cwid = mysqli_insert_id($mysql);

          $tmp = $_FILES['logo']['tmp_name'];
          $type = $_FILES['logo']['type'];
          $end = explode(".", $_FILES['logo']['name']);
          $end = strtolower($end[count($end)-1]);

          if(!empty($tmp))
          {
            $img = @getimagesize($tmp);
if($img1[0])
            {
              @copy($tmp, basePath."/inc/images/clanwars/".mysqli_insert_id($mysql)."_logo.".strtolower($end));
              @unlink($tmp);
            }
          }

          $tmp1 = $_FILES['screen1']['tmp_name'];
          $type1 = $_FILES['screen1']['type'];
          $end1 = explode(".", $_FILES['screen1']['name']);
          $end1 = strtolower($end1[count($end1)-1]);

          if(!empty($tmp1))
          {
            $img1 = @getimagesize($tmp1);
if($img1[0])
            {
              @copy($tmp1, basePath."/inc/images/clanwars/".mysqli_insert_id($mysql)."_1.".strtolower($end1));
              @unlink($tmp1);
            }
          }

          $tmp2 = $_FILES['screen2']['tmp_name'];
          $type2 = $_FILES['screen2']['type'];
          $end2 = explode(".", $_FILES['screen2']['name']);
          $end2 = strtolower($end2[count($end2)-1]);

          if(!empty($tmp2))
          {
            $img2 = @getimagesize($tmp2);
if($img2[0])
            {
              @copy($tmp2, basePath."/inc/images/clanwars/".mysqli_insert_id($mysql)."_2.".strtolower($end2));
              @unlink($tmp2);
            }
          }

          $tmp3 = $_FILES['screen3']['tmp_name'];
          $type3 = $_FILES['screen3']['type'];
          $end3 = explode(".", $_FILES['screen3']['name']);
          $end3 = strtolower($end3[count($end3)-1]);

          if(!empty($tmp3))
          {
            $img3 = @getimagesize($tmp3);
if($img3[0])
            {
              @copy($tmp3, basePath."/inc/images/clanwars/".mysqli_insert_id($mysql)."_3.".strtolower($end3));
              @unlink($tmp3);
            }
          }

          $tmp4 = $_FILES['screen4']['tmp_name'];
          $type4 = $_FILES['screen4']['type'];
          $end4 = explode(".", $_FILES['screen4']['name']);
          $end4 = strtolower($end4[count($end4)-1]);
		  
		  if(!empty($tmp4))
          {
            $img4 = @getimagesize($tmp4);
if($img4[0])
            {
              @copy($tmp4, basePath."/inc/images/clanwars/".mysqli_insert_id($mysql)."_4.".strtolower($end4));
              @unlink($tmp4);
            }
          }

          $tmp5 = $_FILES['screen5']['tmp_name'];
          $type5 = $_FILES['screen5']['type'];
          $end5 = explode(".", $_FILES['screen5']['name']);
          $end5 = strtolower($end5[count($end5)-1]);
		  
		   if(!empty($tmp5))
          {
            $img5 = @getimagesize($tmp5);
if($img5[0])
            {
              @copy($tmp5, basePath."/inc/images/clanwars/".mysqli_insert_id($mysql)."_5.".strtolower($end5));
              @unlink($tmp5);
            }
          }

          $tmp6 = $_FILES['screen6']['tmp_name'];
          $type6 = $_FILES['screen6']['type'];
          $end6 = explode(".", $_FILES['screen6']['name']);
          $end6 = strtolower($end6[count($end6)-1]);

 		  if(!empty($tmp6))
          {
            $img6 = @getimagesize($tmp6);
if($img6[0])
            {
              @copy($tmp6, basePath."/inc/images/clanwars/".mysqli_insert_id($mysql)."_6.".strtolower($end6));
              @unlink($tmp6);
            }
          }

          $tmp7 = $_FILES['screen7']['tmp_name'];
          $type7 = $_FILES['screen7']['type'];
          $end7 = explode(".", $_FILES['screen7']['name']);
          $end7 = strtolower($end7[count($end7)-1]);

 		  if(!empty($tmp7))
          {
            $img7 = @getimagesize($tmp7);
if($img7[0])
            {
              @copy($tmp7, basePath."/inc/images/clanwars/".mysqli_insert_id($mysql)."_7.".strtolower($end7));
              @unlink($tmp7);
            }
          }

          $tmp8 = $_FILES['screen8']['tmp_name'];
          $type8 = $_FILES['screen8']['type'];
          $end8 = explode(".", $_FILES['screen8']['name']);
          $end8 = strtolower($end8[count($end8)-1]);

 		  if(!empty($tmp8))
          {
            $img8 = @getimagesize($tmp8);
if($img8[0])
            {
              @copy($tmp8, basePath."/inc/images/clanwars/".mysqli_insert_id($mysql)."_8.".strtolower($end8));
              @unlink($tmp8);
            }
          }

          $tmp9 = $_FILES['screen9']['tmp_name'];
          $type9 = $_FILES['screen9']['type'];
          $end9 = explode(".", $_FILES['screen9']['name']);
          $end9 = strtolower($end9[count($end9)-1]);
		  
		   if(!empty($tmp9))
          {
            $img9 = @getimagesize($tmp9);
if($img9[0])
            {
              @copy($tmp9, basePath."/inc/images/clanwars/".mysqli_insert_id($mysql)."_9.".strtolower($end9));
              @unlink($tmp9);
            }
          }

          $tmp10 = $_FILES['screen10']['tmp_name'];
          $type10 = $_FILES['screen10']['type'];
          $end10 = explode(".", $_FILES['screen10']['name']);
          $end10 = strtolower($end10[count($end10)-1]);
		  
		   if(!empty($tmp10))
          {
            $img10 = @getimagesize($tmp10);
if($img10[0])
            {
              @copy($tmp10, basePath."/inc/images/clanwars/".mysqli_insert_id($mysql)."_10.".strtolower($end10));
              @unlink($tmp10);
            }
          }

          if(!empty($tmp11))
          {
            $img11 = @getimagesize($tmp11);
if($img11[0])
            {
              @copy($tmp11, basePath."/inc/images/clanwars/".mysqli_insert_id($mysql)."_11.".strtolower($end11));
              @unlink($tmp11);
            }
          }

          $show = info(_cw_admin_added, "?admin=cw");
        }
      } elseif($do == "editcw") {

        if(empty($_POST['gegner']) || empty($_POST['clantag']) || empty($_POST['t']))
        {
          if(empty($_POST['gegner'])) $show = error(_cw_admin_empty_gegner, 1);
          elseif(empty($_POST['clantag'])) $show = error(_cw_admin_empty_clantag, 1);
          elseif(empty($_POST['t'])) $show = error(_empty_datum, 1);
        } else {
          if(empty($_POST['xonx1']) && empty($_POST['xonx2'])) $xonx = "";
          else $xonx = "`xonx` = '".$_POST['xonx1']."on".$_POST['xonx2']."',";

          $datum = mktime($_POST['h'],$_POST['min'],0,$_POST['m'],$_POST['t'],$_POST['j']);

          if($_POST['land'] == "lazy") $kid = "";
   else $kid = "`gcountry` = '".$_POST['land']."',";

          $qry = db("UPDATE ".$db['cw']."
SET ".$xonx."
".$kid."
`datum` = '".((int)$datum)."',
`squad_id` = '".((int)$_POST['squad'])."',
`clantag` = '".up($_POST['clantag'])."',
`gegner` = '".up($_POST['gegner'])."',
`url` = '".links($_POST['url'])."',
`liga` = '".up($_POST['liga'])."',
`gametype` = '".up($_POST['gametype'])."',
`punkte` = '".((int)$_POST['punkte'])."',
`gpunkte` = '".((int)$_POST['gpunkte'])."',
`maps` = '".up($_POST['maps'])."',
`serverip` = '".up($_POST['serverip'])."',
`servername` = '".up($_POST['servername'])."',
`serverpwd` = '".up($_POST['serverpwd'])."',
`lineup` = '".up($_POST['lineup'])."',
`glineup` = '".up($_POST['glineup'])."',
`matchadmins` = '".up($_POST['match_admins'])."',
`bericht` = '".up($_POST['bericht'],1)."'
WHERE id = '".intval($_GET['id'])."'");

          $cwid = $_GET['id'];

          $tmp = $_FILES['logo']['tmp_name'];
          $type = $_FILES['logo']['type'];
          $end = explode(".", $_FILES['logo']['name']);
          $end = strtolower($end[count($end)-1]);

          if(!empty($tmp))
          {
            $img = @getimagesize($tmp);
foreach($picformat AS $end1)
            {
              if(file_exists(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_logo.'.$end1))
              {
                @unlink(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_logo.'.$end1);
                break;
              }
            }
            if($img[0])
            {
              copy($tmp, basePath."/inc/images/clanwars/".intval($_GET['id'])."_logo.".strtolower($end));
              @unlink($tmp);
            }
          }

          $tmp1 = $_FILES['screen1']['tmp_name'];
          $type1 = $_FILES['screen1']['type'];
          $end1 = explode(".", $_FILES['screen1']['name']);
          $end1 = strtolower($end1[count($end1)-1]);

          if(!empty($tmp1))
          {
            $img1 = @getimagesize($tmp1);
foreach($picformat AS $endun1)
            {
              if(file_exists(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_1.'.$endun1))
              {
                @unlink(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_1.'.$endun1);
                break;
              }
            }

            if($img1[0])
            {
              copy($tmp1, basePath."/inc/images/clanwars/".intval($_GET['id'])."_1.".strtolower($end1));
              @unlink($tmp1);
            }
          }

$tmp2 = $_FILES['screen2']['tmp_name'];
          $type2 = $_FILES['screen2']['type'];
          $end2 = explode(".", $_FILES['screen2']['name']);
          $end2 = strtolower($end2[count($end2)-1]);

          if(!empty($tmp2))
          {
            $img2 = @getimagesize($tmp2);
foreach($picformat AS $endun2)
            {
              if(file_exists(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_2.'.$endun2))
              {
                @unlink(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_2.'.$endun2);
                break;
              }
            }
            if($img2[0])
            {
              copy($tmp2, basePath."/inc/images/clanwars/".intval($_GET['id'])."_2.".strtolower($end2));
              @unlink($tmp2);
            }
          }

$tmp3 = $_FILES['screen3']['tmp_name'];
          $type3 = $_FILES['screen3']['type'];
          $end3 = explode(".", $_FILES['screen3']['name']);
          $end3 = strtolower($end3[count($end3)-1]);

          if(!empty($tmp3))
          {
            $img3 = @getimagesize($tmp3);
foreach($picformat AS $endun3)
            {
              if(file_exists(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_3.'.$endun3))
              {
                @unlink(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_3.'.$endun3);
                break;
              }
            }
            if($img3[0])
            {
              copy($tmp3, basePath."/inc/images/clanwars/".intval($_GET['id'])."_3.".strtolower($end3));
              @unlink($tmp3);
            }
          }

$tmp4 = $_FILES['screen4']['tmp_name'];
          $type4 = $_FILES['screen4']['type'];
          $end4 = explode(".", $_FILES['screen4']['name']);
          $end4 = strtolower($end4[count($end4)-1]);

          if(!empty($tmp4))
          {
            $img4 = @getimagesize($tmp4);
foreach($picformat AS $endun4)
            {
              if(file_exists(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_4.'.$endun4))
              {
                @unlink(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_4.'.$endun4);
                break;
              }
            }
            if($img4[0])
            {
              copy($tmp4, basePath."/inc/images/clanwars/".intval($_GET['id'])."_4.".strtolower($end4));
              @unlink($tmp4);
            }
          }
		  
		  $tmp5 = $_FILES['screen5']['tmp_name'];
          $type5 = $_FILES['screen5']['type'];
          $end5 = explode(".", $_FILES['screen5']['name']);
          $end5 = strtolower($end5[count($end5)-1]);

          if(!empty($tmp5))
          {
            $img5 = @getimagesize($tmp5);
foreach($picformat AS $endun5)
            {
              if(file_exists(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_5.'.$endun5))
              {
                @unlink(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_5.'.$endun5);
                break;
              }
            }
            if($img5[0])
            {
              copy($tmp5, basePath."/inc/images/clanwars/".intval($_GET['id'])."_5.".strtolower($end5));
              @unlink($tmp5);
            }
          }
		  
		  $tmp6 = $_FILES['screen6']['tmp_name'];
          $type6 = $_FILES['screen6']['type'];
          $end6 = explode(".", $_FILES['screen6']['name']);
          $end6 = strtolower($end6[count($end6)-1]);

          if(!empty($tmp6))
          {
            $img6 = @getimagesize($tmp6);
foreach($picformat AS $endun6)
            {
              if(file_exists(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_6.'.$endun6))
              {
                @unlink(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_6.'.$endun6);
                break;
              }
            }
            if($img6[0])
            {
              copy($tmp6, basePath."/inc/images/clanwars/".intval($_GET['id'])."_6.".strtolower($end6));
              @unlink($tmp6);
            }
          }
		  
		  $tmp7 = $_FILES['screen7']['tmp_name'];
          $type7 = $_FILES['screen7']['type'];
          $end7 = explode(".", $_FILES['screen7']['name']);
          $end7 = strtolower($end7[count($end7)-1]);

          if(!empty($tmp7))
          {
            $img7 = @getimagesize($tmp7);
foreach($picformat AS $endun7)
            {
              if(file_exists(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_7.'.$endun7))
              {
                @unlink(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_7.'.$endun7);
                break;
              }
            }
            if($img7[0])
            {
              copy($tmp7, basePath."/inc/images/clanwars/".intval($_GET['id'])."_7.".strtolower($end7));
              @unlink($tmp7);
            }
          }
		  
		  $tmp8 = $_FILES['screen8']['tmp_name'];
          $type8 = $_FILES['screen8']['type'];
          $end8 = explode(".", $_FILES['screen8']['name']);
          $end8 = strtolower($end8[count($end8)-1]);

          if(!empty($tmp8))
          {
            $img8 = @getimagesize($tmp8);
foreach($picformat AS $endun8)
            {
              if(file_exists(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_8.'.$endun8))
              {
                @unlink(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_8.'.$endun8);
                break;
              }
            }
            if($img8[0])
            {
              copy($tmp8, basePath."/inc/images/clanwars/".intval($_GET['id'])."_8.".strtolower($end8));
              @unlink($tmp8);
            }
          }
		  
		  $tmp9 = $_FILES['screen9']['tmp_name'];
          $type9 = $_FILES['screen9']['type'];
          $end9 = explode(".", $_FILES['screen9']['name']);
          $end9 = strtolower($end9[count($end9)-1]);

          if(!empty($tmp9))
          {
            $img9 = @getimagesize($tmp9);
foreach($picformat AS $endun9)
            {
              if(file_exists(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_9.'.$endun9))
              {
                @unlink(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_9.'.$endun9);
                break;
              }
            }
            if($img9[0])
            {
              copy($tmp9, basePath."/inc/images/clanwars/".intval($_GET['id'])."_9.".strtolower($end9));
              @unlink($tmp9);
            }
          }
		  
		  $tmp10 = $_FILES['screen10']['tmp_name'];
          $type10 = $_FILES['screen10']['type'];
          $end10 = explode(".", $_FILES['screen10']['name']);
          $end10 = strtolower($end10[count($end10)-1]);

          if(!empty($tmp10))
          {
            $img10 = @getimagesize($tmp10);
foreach($picformat AS $endun10)
            {
              if(file_exists(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_10.'.$endun10))
              {
                @unlink(basePath.'/inc/images/clanwars/'.intval($_GET['id']).'_10.'.$endun10);
                break;
              }
            }
            if($img10[0])
            {
              copy($tmp10, basePath."/inc/images/clanwars/".intval($_GET['id'])."_10.".strtolower($end10));
              @unlink($tmp10);
            }
          }

          $show = info(_cw_admin_edited, "?admin=cw");
        }
      } elseif($do == "delete") {
        $qry = db("DELETE FROM ".$db['cw']."
WHERE id = '".intval($_GET['id'])."'");

        $qry = db("DELETE FROM ".$db['cw_comments']."
WHERE cw = '".intval($_GET['id'])."'");

        $show = info(_cw_admin_deleted, "?admin=cw");
      } elseif($do == "top") {
        $qry = db("UPDATE ".$db['cw']."
SET `top` = '".intval($_GET['set'])."'
WHERE id = '".intval($_GET['id'])."'");

        $show = info((empty($_GET['set']) ? _cw_admin_top_unsetted : _cw_admin_top_setted), "?admin=cw");
      } else {

if(is_numeric($_GET['squad']))    {
$whereqry = ' WHERE squad_id = '.$_GET['squad'].' ';
}

        $qry = db("SELECT * FROM ".$db['cw']." ".$whereqry."
ORDER BY datum DESC
LIMIT ".($page - 1)*$maxadmincw.",".$maxadmincw."");
        $entrys = cnt($db['cw']);
         $squads .= show(_cw_edit_select_field_squads, array("name" => _all,
"sel" => "",
"id" => "?admin=cw"));

$qrys = db("SELECT * FROM ".$db['squads']."
WHERE status = '1'
ORDER BY game ASC");

        while($gets = _fetch($qrys))
        {
if($gets['id'] == $_GET['squad']) { $sel = ' class="dropdownKat"'; } else { $sel = ""; }

          $squads .= show(_cw_edit_select_field_squads, array("name" => re($gets['name']),
"sel" => $sel,
"id" => "?admin=cw&amp;squad=".$gets['id'].""));
        }
        while($get = _fetch($qry))
        {
          $top = empty($get['top'])
               ? '<a href="?admin=cw&amp;do=top&amp;set=1&amp;id='.$get['id'].'"><img src="../inc/images/no.gif" alt="" title="'._cw_admin_top_set.'" /></a>'
               : '<a href="?admin=cw&amp;do=top&amp;set=0&amp;id='.$get['id'].'"><img src="../inc/images/yes.gif" alt="" title="'._cw_admin_top_unset.'" /></a>';

          $edit = show("page/button_edit_single", array("id" => $get['id'],
                                                        "action" => "admin=cw&amp;do=edit",
                                                        "title" => _button_title_edit));
          $delete = show("page/button_delete_single", array("id" => $get['id'],
                                                            "action" => "admin=cw&amp;do=delete",
                                                            "title" => _button_title_del,
                                                            "del" => convSpace(_confirm_del_cw)));

          $class = ($color % 2) ? "contentMainSecond" : "contentMainFirst"; $color++;
          $show_ .= show($dir."/clanwars_show", array("class" => $class,
                                                      "cw" => re($get['clantag'])." - ".re($get['gegner']),
                                                      "datum" => date("d.m.Y H:i",$get['datum'])._uhr,
                                                      "top" => $top,
                                                      "id" => $get['id'],
                                                      "edit" => $edit,
                                                      "delete" => $delete
                                                      ));
        }

        $show = show($dir."/clanwars", array("head" => _clanwars,
                                             "add" => _cw_admin_head,
                                             "date" => _datum,
                                             "titel" => _opponent,
                                              "squads" => $squads,
"what" => _filter,
"sort" => _ulist_sort,
                                             "show" => $show_,
                                             "navi" => nav($entrys,$maxadmincw,"?admin=cw&amp;squad=".$_GET['squad']."")
                                             ));
      }