<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6 Final
 * http://www.dzcp.de
 */

if(defined('_Clanwars')) {
  header("Content-type: text/html; charset=utf-8");
  $qry = db("SELECT * FROM ".$db['squads']."
             WHERE id = '".intval($_POST['squad'])."'");
  $get = _fetch($qry);

  $serverpwd = show(_cw_serverpwd, array("cw_serverpwd" => re($_POST['serverpwd'])));

  $img = squad($get['icon']);
  $show = show(_cw_details_squad, array("game" => re($get['game']),
                                                          "name" => re($get['name']),
                                        "id" => $_POST['squad'],
                                                          "img" => $img));
  $flagge = flag($get['gcountry']);
  $gegner = show(_cw_details_gegner_blank, array("gegner" => re($_POST['clantag']." - ".$_POST['gegner']),
                                                 "url" => links($_POST['url'])));
  $server = show(_cw_details_server, array("servername" => re($_POST['servername']),
                                           "serverip" => re($_POST['serverip'])));

  if($_POST['punkte'] == "0" && $_POST['gpunkte'] == "0") $result = _cw_no_results;
  else $result = cw_result_details($_POST['punkte'], $_POST['gpunkte']);

 $editcw = "";

  if($_POST['bericht']) $bericht = bbcode(re($_POST['bericht']),1);
  else $bericht = "&nbsp;";

  if(!empty($_POST['s1']))     $screen1 = '<img src="../inc/images/admin/cwscreen.jpg" alt="" />';
  else $screen1 = "";

  if(!empty($_POST['s2']))     $screen2 = '<img src="../inc/images/admin/cwscreen.jpg" alt="" />';
  else $screen2 = "";

  if(!empty($_POST['s3']))     $screen3 = '<img src="../inc/images/admin/cwscreen.jpg" alt="" />';
  else $screen3 = "";

  if(!empty($_POST['s4']))     $screen4 = '<img src="../inc/images/admin/cwscreen.jpg" alt="" />';
  else $screen4 = "";

  if(!empty($screen1) || !empty($screen2) || !empty($screen3) || !empty($screen4))
  {
    $screens = show($dir."/screenshots", array("head" => _cw_screens,
                                               "screenshot1" => _cw_screenshot." 1",
                                               "screenshot2" => _cw_screenshot." 2",
                                               "screenshot3" => _cw_screenshot." 3",
                                               "screenshot4" => _cw_screenshot." 4",
                                               "screen1" => $screen1,
                                               "screen2" => $screen2,
                                               "screen3" => $screen3,
                                               "screen4" => $screen4));
  }

  $datum = mktime($_POST['h'],$_POST['min'],0,$_POST['m'],$_POST['t'],$_POST['j']);
  if(empty($_POST['xonx1']) && empty($_POST['xonx2'])) $xonx = "";
  else $xonx = $_POST['xonx1']."on".$_POST['xonx2'];

  $index = show($dir."/details", array("head" => _cw_head_details,
                                                         "result_head" => _cw_head_results,
                                                         "lineup_head" => _cw_head_lineup,
                                                         "admin_head" => _cw_head_admin,
                                                         "gametype_head" => _cw_head_gametype,
                                                         "squad_head" => _cw_head_squad,
                                                         "flagge" => $flagge,
                                       "br1" => '',
                                       "br2" => '',
                                       "logo_squad" => '_defaultlogo.jpg',
                                       "logo_gegner" => '_defaultlogo.jpg',
                                                         "squad" => $show,
                                                         "squad_name" => re($get['name']),
                                                         "gametype" => re($_POST['gametype']),
                                                         "lineup" => preg_replace("#\,#","<br />", re($_POST['lineup'])),
                                                         "glineup" => preg_replace("#\,#","<br />", re($_POST['glineup'])),
                                                         "match_admins" => re($_POST['match_admins']),
                                       "datum" => _datum,
                                       "gegner" => _cw_head_gegner,
                                       "xonx" => _cw_head_xonx,
                                       "liga" => _cw_head_liga,
                                       "maps" => _cw_maps,
                                       "server" => _server,
                                       "result" => _cw_head_result,
                                       "players" => $players,
                                       "edit" => $editcw,
                                       "comments" => $comments,
                                       "bericht" => _cw_bericht,
                                       "serverpwd" => $serverpwd,
                                       "cw_datum" => date("d.m.Y H:i",$datum)._uhr,
                                       "cw_gegner" => $gegner,
                                       "cw_xonx" => re($xonx),
                                       "cw_liga" => re($_POST['liga']),
                                       "cw_maps" => re($_POST['maps']),
                                       "cw_server" => $server,
                                       "cw_result" => $result,
                                       "cw_bericht" => $bericht,
                                       "screenshots" => $screens));
    echo '<table class="mainContent" cellspacing="1">'.$index.'</table>';

    if(!mysqli_persistconns)
        $mysql->close(); //MySQL

    exit();
}