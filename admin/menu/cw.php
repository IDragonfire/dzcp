<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(_adminMenu != 'true') exit;

    $where = $where.': '._clanwars;
      if($do == "new")
      {
        $qry = db("SELECT * FROM ".$db['squads']." WHERE status = '1' ORDER BY game ASC"); $squads = '';
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
            `bericht` = '".up($_POST['bericht'])."'");

          $cwid = _insert_id();

        //Logo Upload
        $tmpname = $_FILES['logo']['tmp_name'];
        $type = $_FILES['logo']['type'];
        $end = explode(".", $_FILES['logo']['name']);
        $end = strtolower($end[count($end)-1]);
        if(!empty($tmpname)) {
            $img = @getimagesize($tmpname);
            if($img[0])
                move_uploaded_file($tmpname, basePath."/inc/images/clanwars/".$cwid."_logo.".strtolower($end));
        }

        //Screenshot Upload
        for ($zaehler = 1; $zaehler <= 20; $zaehler++) {
            if(isset($_FILES['screen'.$zaehler])) {
                $tmpname = $_FILES['screen'.$zaehler]['tmp_name'];
                $type = $_FILES['screen'.$zaehler]['type'];
                $end = explode(".", $_FILES['screen'.$zaehler]['name']);
                $end = strtolower($end[count($end)-1]);
                if(!empty($tmpname)) {
                    $img = @getimagesize($tmpname);
                    if($img[0])
                        move_uploaded_file($tmpname, basePath."/inc/images/clanwars/".$cwid."_".$zaehler.".".strtolower($end));
                }
            }
            else break;
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
`bericht` = '".up($_POST['bericht'])."'
WHERE id = '".intval($_GET['id'])."'");

        $cwid = intval($_GET['id']);

        //Logo Upload
        $tmpname = $_FILES['logo']['tmp_name'];
        $type = $_FILES['logo']['type'];
        $end = explode(".", $_FILES['logo']['name']);
        $end = strtolower($end[count($end)-1]);
        if(!empty($tmpname)) {
            $img = @getimagesize($tmpname);
            if($img[0]) {
                foreach($picformat AS $end_del) {
                    if(file_exists(basePath.'/inc/images/clanwars/'.$cwid.'_logo.'.$end_del)) {
                        unlink(basePath.'/inc/images/clanwars/'.$cwid.'_logo.'.$end_del);
                        break;
                    }
                }

                move_uploaded_file($tmpname, basePath."/inc/images/clanwars/".$cwid."_logo.".strtolower($end));
            }
        }

        //Screenshot Upload
        for ($zaehler = 1; $zaehler <= 20; $zaehler++) {
            if(isset($_FILES['screen'.$zaehler])) {
                $tmpname = $_FILES['screen'.$zaehler]['tmp_name'];
                $type = $_FILES['screen'.$zaehler]['type'];
                $end = explode(".", $_FILES['screen'.$zaehler]['name']);
                $end = strtolower($end[count($end)-1]);
                if(!empty($tmpname)) {
                    $img = @getimagesize($tmpname);
                    if($img[0]) {
                        foreach($picformat AS $end_del) {
                            if(file_exists(basePath.'/inc/images/clanwars/'.$cwid.'_'.$zaehler.'.'.$end_del)) {
                                  unlink(basePath.'/inc/images/clanwars/'.$cwid.'_'.$zaehler.'.'.$end_del);
                                  break;
                            }
                        }

                        move_uploaded_file($tmpname, basePath."/inc/images/clanwars/".$cwid."_".$zaehler.".".strtolower($end));
                    }
                }
            }
            else break;
        }

        $show = info(_cw_admin_edited, "?admin=cw");
    }
}
elseif($do == "delete")
{
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

        if(empty($show_))
            $show_ = '<tr><td colspan="5" class="contentMainSecond">'._no_entrys.'</td></tr>';

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